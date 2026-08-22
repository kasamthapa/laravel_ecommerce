import * as THREE from 'three';
import { FilesetResolver, FaceLandmarker } from '@mediapipe/tasks-vision';
import { loadModel, disposeObject } from './glasses-model';

// MediaPipe ships its WASM runtime and doesn't publish it as importable JS,
// so it's loaded from jsdelivr at runtime (code only — no user data ever
// touches this request). The face landmark model file is similarly fetched
// from Google's public model CDN on first use and then served from the
// browser's HTTP cache.
const WASM_BASE = 'https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@1.0.1/wasm';
const FACE_MODEL_URL = 'https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task';

// Indices into MediaPipe's 478-point face mesh.
const LEFT_EYE_OUTER = 33;
const RIGHT_EYE_OUTER = 263;
const NOSE_BRIDGE = 168;
const NOSE_TIP = 1;

const NO_FACE_HINT_DELAY_MS = 6000;
const FIT_LERP = 0.3;
// Real eyewear typically spans roughly 2.0-2.4x the outer-eye-corner
// distance (frames extend past the eyes to the temples) — the original
// 1.35 default under-sized the frame, rendering it like a small sticker
// rather than a properly fitted pair of glasses.
const FIT_WIDTH_FACTOR = 2.2;
const YAW_SENSITIVITY = 2.4;
const MAX_YAW = 0.55;

// The wasm fileset is a multi-MB binary — cache the resolved fileset across
// mounts so toggling the Try On tab off and on doesn't re-fetch/re-init it.
let filesetResolverPromise = null;
const getFilesetResolver = () => {
    if (!filesetResolverPromise) {
        filesetResolverPromise = FilesetResolver.forVisionTasks(WASM_BASE);
    }

    return filesetResolverPromise;
};

/**
 * Warms the MediaPipe WASM runtime without touching the camera, so the
 * multi-MB fileset is already resolved by the time a customer clicks the
 * Try On tab. Called on idle from glasses-viewer.js, only on pages that
 * actually have a Try On tab, so it never runs for the rest of the site.
 */
export const preloadFaceTryOn = () => {
    getFilesetResolver().catch(() => {});
};

const createLandmarker = async (delegate) => {
    const filesetResolver = await getFilesetResolver();

    return FaceLandmarker.createFromOptions(filesetResolver, {
        baseOptions: {
            modelAssetPath: FACE_MODEL_URL,
            delegate,
        },
        runningMode: 'VIDEO',
        numFaces: 1,
    });
};

const supportsRequiredApis = () =>
    typeof navigator !== 'undefined' &&
    !!navigator.mediaDevices &&
    typeof navigator.mediaDevices.getUserMedia === 'function' &&
    typeof WebAssembly !== 'undefined';

/**
 * Maps a normalized face-landmark coordinate (0..1 in the raw camera frame)
 * into stage-pixel space, accounting for the object-fit: cover crop applied
 * to the mirrored video element.
 */
const projectLandmark = (landmark, bounds, videoAspect) => {
    const boundsAspect = bounds.width / bounds.height;
    let visibleWidth = 1;
    let visibleHeight = 1;

    if (videoAspect > boundsAspect) {
        visibleWidth = boundsAspect / videoAspect;
    } else {
        visibleHeight = videoAspect / boundsAspect;
    }

    const cropX0 = (1 - visibleWidth) / 2;
    const cropY0 = (1 - visibleHeight) / 2;
    const screenX = (landmark.x - cropX0) / visibleWidth;
    const screenY = (landmark.y - cropY0) / visibleHeight;

    return {
        x: (screenX - 0.5) * bounds.width,
        y: -(screenY - 0.5) * bounds.height,
    };
};

/**
 * Mounts the webcam try-on overlay into a stage element and returns a
 * dispose function that stops the camera stream and fully tears down the
 * Three.js renderer and MediaPipe face landmarker.
 *
 * @param {HTMLElement} stage
 * @returns {(() => void)|null}
 */
export const mountTryOn = (stage) => {
    const modelPath = stage.dataset.modelPath;
    const video = stage.querySelector('[data-tryon-video]');
    const existingCanvas = stage.querySelector('[data-tryon-canvas]');
    const statusEl = stage.querySelector('[data-tryon-status]');
    const statusText = stage.querySelector('[data-tryon-status-text]');
    const retryButton = stage.querySelector('[data-tryon-retry]');
    const debugPanel = stage.querySelector('[data-tryon-debug]');

    if (!(stage instanceof HTMLElement) || !modelPath || !(video instanceof HTMLVideoElement) || !(existingCanvas instanceof HTMLCanvasElement)) {
        return null;
    }

    // Same rule as the 3D viewer: never reuse a canvas across mounts, since a
    // canvas keeps returning its prior (lost) WebGL context after disposal.
    const canvas = existingCanvas.cloneNode();
    existingCanvas.replaceWith(canvas);

    const controller = new AbortController();
    const { signal } = controller;

    // Live-tunable fit values, editable via the ?debug=1 panel while Try On
    // is running. Defaults match the shipped constants above.
    const tuning = { scale: FIT_WIDTH_FACTOR, offsetX: 0, offsetY: 0, lerp: FIT_LERP };

    debugPanel?.querySelectorAll('[data-tryon-debug-control]').forEach((input) => {
        const key = input.dataset.tryonDebugControl;
        const valueEl = debugPanel.querySelector(`[data-tryon-debug-value="${key}"]`);
        tuning[key] = parseFloat(input.value);

        input.addEventListener('input', () => {
            tuning[key] = parseFloat(input.value);

            if (valueEl) {
                valueEl.textContent = tuning[key].toFixed(2);
            }
        }, { signal });
    });

    let disposed = false;
    let stream = null;
    let renderer = null;
    let faceLandmarker = null;
    let resizeObserver = null;
    let intersectionObserver = null;
    let noFaceTimer = null;
    let hasEverSeenFace = false;
    let isVisible = true;

    const showStatus = (message, { retry = false } = {}) => {
        if (statusText) {
            statusText.textContent = message;
        }

        statusEl?.classList.remove('hidden');
        statusEl?.classList.add('flex');
        retryButton?.classList.toggle('hidden', !retry);
        stage.classList.remove('is-tryon-ready');
    };

    const hideStatus = () => {
        statusEl?.classList.add('hidden');
        statusEl?.classList.remove('flex');
    };

    const stopStream = () => {
        stream?.getTracks().forEach((track) => track.stop());
        stream = null;
    };

    const dispose = () => {
        if (disposed) {
            return;
        }

        disposed = true;
        controller.abort();
        clearTimeout(noFaceTimer);
        resizeObserver?.disconnect();
        intersectionObserver?.disconnect();
        stopStream();
        faceLandmarker?.close();
        faceLandmarker = null;

        if (renderer) {
            renderer.setAnimationLoop(null);
            disposeObject(scene);
            renderer.dispose();
            renderer.forceContextLoss();
        }

        video.srcObject = null;
        stage.classList.remove('is-tryon-ready');
    };

    if (!supportsRequiredApis()) {
        showStatus("Your browser doesn't support the camera try-on feature. Try the latest Chrome, Edge, or Safari.");
        return dispose;
    }

    try {
        renderer = new THREE.WebGLRenderer({
            alpha: true,
            antialias: true,
            canvas,
            powerPreference: 'high-performance',
            premultipliedAlpha: false,
        });
    } catch {
        showStatus("Your browser doesn't support the camera try-on feature. Try the latest Chrome, Edge, or Safari.");
        return dispose;
    }

    renderer.setClearColor(0x000000, 0);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;

    const scene = new THREE.Scene();
    scene.add(new THREE.HemisphereLight(0xffffff, 0x38445b, 1.6));

    const keyLight = new THREE.DirectionalLight(0xffffff, 2.4);
    keyLight.position.set(2, 3, 5);
    scene.add(keyLight);

    const camera = new THREE.OrthographicCamera();
    const pivot = new THREE.Group();
    scene.add(pivot);

    let isModelReady = false;
    let modelWidth = 5.5;
    loadModel(modelPath, { signal })
        .then((modelScene) => {
            pivot.add(modelScene);
            modelWidth = modelScene.userData.fittedWidth || 5.5;
            isModelReady = true;
        })
        .catch(() => {
            if (!signal.aborted) {
                showStatus("This frame's 3D model failed to load. Please refresh and try again.", { retry: true });
            }
        });

    const resizeCamera = () => {
        const bounds = stage.getBoundingClientRect();

        if (bounds.width === 0 || bounds.height === 0) {
            return;
        }

        renderer.setSize(bounds.width, bounds.height, false);
        camera.left = -bounds.width / 2;
        camera.right = bounds.width / 2;
        camera.top = bounds.height / 2;
        camera.bottom = -bounds.height / 2;
        camera.near = -1000;
        camera.far = 1000;
        camera.updateProjectionMatrix();
    };

    resizeObserver = new ResizeObserver(resizeCamera);
    resizeObserver.observe(stage);

    if ('IntersectionObserver' in window) {
        intersectionObserver = new IntersectionObserver(([entry]) => {
            isVisible = entry.isIntersecting;
        });
        intersectionObserver.observe(stage);
    }

    // Smoothed pivot transform, updated from face landmarks and lerped
    // toward each frame's target to damp per-frame landmark jitter.
    const fit = {
        x: 0, y: 0, scale: 1, roll: 0, yaw: 0,
        targetX: 0, targetY: 0, targetScale: 1, targetRoll: 0, targetYaw: 0,
    };
    let fitInitialized = false;

    const applyLandmarks = (landmarks, bounds) => {
        const videoAspect = video.videoWidth / video.videoHeight;
        const leftEye = projectLandmark(landmarks[LEFT_EYE_OUTER], bounds, videoAspect);
        const rightEye = projectLandmark(landmarks[RIGHT_EYE_OUTER], bounds, videoAspect);
        const noseBridge = projectLandmark(landmarks[NOSE_BRIDGE], bounds, videoAspect);
        const noseTip = projectLandmark(landmarks[NOSE_TIP], bounds, videoAspect);

        const eyeDx = rightEye.x - leftEye.x;
        const eyeDy = rightEye.y - leftEye.y;
        const eyeDistance = Math.hypot(eyeDx, eyeDy) || 1;
        const eyeMidX = (leftEye.x + rightEye.x) / 2;

        fit.targetX = noseBridge.x + tuning.offsetX;
        fit.targetY = noseBridge.y + tuning.offsetY;
        fit.targetScale = (eyeDistance / modelWidth) * tuning.scale;
        fit.targetRoll = -Math.atan2(eyeDy, eyeDx);
        fit.targetYaw = THREE.MathUtils.clamp(((noseTip.x - eyeMidX) / eyeDistance) * YAW_SENSITIVITY, -MAX_YAW, MAX_YAW);

        if (!fitInitialized) {
            fit.x = fit.targetX;
            fit.y = fit.targetY;
            fit.scale = fit.targetScale;
            fit.roll = fit.targetRoll;
            fit.yaw = fit.targetYaw;
            fitInitialized = true;
        }
    };

    const armNoFaceTimer = () => {
        if (noFaceTimer || hasEverSeenFace) {
            return;
        }

        noFaceTimer = setTimeout(() => {
            if (!disposed) {
                showStatus("We can't find your face — try moving into good light and centering your face in the frame.");
            }
        }, NO_FACE_HINT_DELAY_MS);
    };

    const startLoop = () => {
        hideStatus();
        armNoFaceTimer();
        resizeCamera();

        renderer.setAnimationLoop(() => {
            if (!isVisible) {
                return;
            }

            if (video.readyState >= 2 && video.videoWidth > 0) {
                const bounds = stage.getBoundingClientRect();
                const result = faceLandmarker.detectForVideo(video, performance.now());

                if (result.faceLandmarks && result.faceLandmarks.length > 0) {
                    hasEverSeenFace = true;
                    clearTimeout(noFaceTimer);
                    noFaceTimer = null;
                    hideStatus();
                    applyLandmarks(result.faceLandmarks[0], bounds);
                }
            }

            fit.x = THREE.MathUtils.lerp(fit.x, fit.targetX, tuning.lerp);
            fit.y = THREE.MathUtils.lerp(fit.y, fit.targetY, tuning.lerp);
            fit.scale = THREE.MathUtils.lerp(fit.scale, fit.targetScale, tuning.lerp);
            fit.roll = THREE.MathUtils.lerp(fit.roll, fit.targetRoll, tuning.lerp);
            fit.yaw = THREE.MathUtils.lerp(fit.yaw, fit.targetYaw, tuning.lerp);

            pivot.position.set(fit.x, fit.y, 0);
            pivot.rotation.set(0, fit.yaw, fit.roll);
            pivot.scale.setScalar(fit.scale);

            renderer.render(scene, camera);
            stage.classList.add('is-tryon-ready');
        });
    };

    const start = () => {
        showStatus('Starting camera…');
        stopStream();

        navigator.mediaDevices
            .getUserMedia({ video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }, audio: false })
            .then((mediaStream) => {
                if (disposed) {
                    mediaStream.getTracks().forEach((track) => track.stop());
                    return Promise.reject(new DOMException('Aborted', 'AbortError'));
                }

                stream = mediaStream;
                video.srcObject = mediaStream;
                return video.play();
            })
            .then(() => createLandmarker('GPU').catch(() => createLandmarker('CPU')))
            .then((landmarker) => {
                if (disposed) {
                    landmarker.close();
                    return;
                }

                faceLandmarker = landmarker;
                startLoop();
            })
            .catch((error) => {
                if (disposed || error?.name === 'AbortError') {
                    return;
                }

                if (error?.name === 'NotAllowedError' || error?.name === 'PermissionDeniedError') {
                    showStatus('Camera access was denied. Enable camera permissions for this site to try frames on.', { retry: true });
                } else if (error?.name === 'NotFoundError') {
                    showStatus('No camera was found on this device.');
                } else {
                    showStatus("We couldn't start the camera try-on. Please try again.", { retry: true });
                }
            });
    };

    retryButton?.addEventListener('click', start, { signal });
    start();

    return dispose;
};
