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

// MediaPipe's face-geometry pipeline assumes a virtual camera with this
// vertical FOV when it computes facialTransformationMatrixes — matching it
// here is what makes that matrix line up with what the real camera sees.
const CANONICAL_VERTICAL_FOV_DEG = 63;
// The canonical face model is authored in centimeters. A full adult face
// (temple to temple) is roughly this wide — used only as a starting-point
// scale guess; the debug panel's Scale slider is the real calibration tool
// during live testing.
const ASSUMED_FACE_WIDTH_CM = 15;

const NO_FACE_HINT_DELAY_MS = 6000;

// Fit defaults — found via live testing on Cobalt Flight Aviator (2026-08),
// since this GLB's own authored orientation and MediaPipe's canonical face
// model convention couldn't be known without a camera. Still exposed via
// ?debug=1 in case a different model or face needs a different calibration.
const FIT_LERP = 0.4;
const DEFAULT_SCALE = 1.01;
const DEFAULT_OFFSET_X = -0.2;
const DEFAULT_OFFSET_Y = 2.6;
const DEFAULT_OFFSET_Z = -3.7;
const DEFAULT_PITCH_DEG = 4;
const DEFAULT_YAW_DEG = 3;
const DEFAULT_ROLL_DEG = -1;

// Approximate adult head dimensions (cm), used as an invisible occluder so
// the glasses correctly disappear behind the head/ear as it turns, instead
// of always drawing on top of the video regardless of depth.
const OCCLUDER_WIDTH_CM = 15;
const OCCLUDER_HEIGHT_CM = 21;
const OCCLUDER_DEPTH_CM = 19;
// Centers the occluder roughly mid-head, behind the face surface the
// glasses sit on (DEFAULT_OFFSET_Z) by about half a head's depth.
const DEFAULT_OCCLUDER_Z = DEFAULT_OFFSET_Z - OCCLUDER_DEPTH_CM / 2;

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
        // The full 3D head-pose matrix — position, pitch, yaw, and roll
        // together — instead of estimating each separately from 2D
        // landmarks. This is what lets the glasses track every axis of
        // head movement, the way a real pair would move with your head.
        outputFacialTransformationMatrixes: true,
    });
};

const supportsRequiredApis = () =>
    typeof navigator !== 'undefined' &&
    !!navigator.mediaDevices &&
    typeof navigator.mediaDevices.getUserMedia === 'function' &&
    typeof WebAssembly !== 'undefined';

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
    const tint = stage.dataset.frameTint || stage.dataset.lensTint
        ? { frame: stage.dataset.frameTint || undefined, lens: stage.dataset.lensTint || undefined }
        : undefined;
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
    // is running.
    const tuning = {
        scale: DEFAULT_SCALE,
        offsetX: DEFAULT_OFFSET_X,
        offsetY: DEFAULT_OFFSET_Y,
        offsetZ: DEFAULT_OFFSET_Z,
        pitchDeg: DEFAULT_PITCH_DEG,
        yawDeg: DEFAULT_YAW_DEG,
        rollDeg: DEFAULT_ROLL_DEG,
        lerp: FIT_LERP,
        occluderScale: 1,
        occluderZ: DEFAULT_OCCLUDER_Z,
    };

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

    // Perspective camera at the origin looking down -Z, matching the virtual
    // camera MediaPipe assumes when it computes facialTransformationMatrixes
    // — the matrix only lines up with the video if this camera matches it.
    const camera = new THREE.PerspectiveCamera(CANONICAL_VERTICAL_FOV_DEG, 1, 0.01, 1000);

    // faceAnchor receives the tracked head pose every frame (position +
    // full 3D rotation, smoothed). pivot is a child of it that applies the
    // live-tunable calibration (scale + offsets + base rotation correction)
    // on top, since the exact alignment between this GLB's own axes and
    // MediaPipe's canonical face model isn't something that can be known in
    // advance — it's what the debug panel is for.
    const faceAnchor = new THREE.Group();
    scene.add(faceAnchor);

    const pivot = new THREE.Group();
    faceAnchor.add(pivot);

    // Invisible head-shaped occluder: writes to the depth buffer but never
    // draws color, so glasses geometry that ends up behind it (e.g. the far
    // temple arm as the head turns) fails the depth test and isn't drawn —
    // the transparent canvas then lets the real video show through there
    // instead, giving the illusion the head is actually blocking the
    // glasses rather than the glasses floating in front of it regardless of
    // pose. Standard AR technique; the alternative (a mesh built from the
    // exact face contour) needs MediaPipe's canonical face mesh triangle
    // data, which isn't bundled with this package.
    const occluder = new THREE.Mesh(
        new THREE.SphereGeometry(1, 24, 24),
        new THREE.MeshBasicMaterial({ colorWrite: false }),
    );
    occluder.renderOrder = -1;
    faceAnchor.add(occluder);

    let isModelReady = false;
    let modelWidth = 5.5;
    loadModel(modelPath, { signal, tint })
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
        camera.aspect = bounds.width / bounds.height;
        camera.updateProjectionMatrix();
        // Mirrors the render (matching the CSS-mirrored video) by flipping
        // clip-space X after projection, rather than mirroring the camera
        // itself — negating the camera's own transform would flip the view
        // matrix's handedness and break backface culling on the model.
        camera.projectionMatrix.elements[0] *= -1;
    };

    resizeObserver = new ResizeObserver(resizeCamera);
    resizeObserver.observe(stage);

    if ('IntersectionObserver' in window) {
        intersectionObserver = new IntersectionObserver(([entry]) => {
            isVisible = entry.isIntersecting;
        });
        intersectionObserver.observe(stage);
    }

    // Smoothed head pose, decomposed from each frame's transformation
    // matrix and eased toward with lerp/slerp (matrices can't be linearly
    // blended directly without producing invalid intermediate transforms).
    const targetMatrix = new THREE.Matrix4();
    const targetPosition = new THREE.Vector3();
    const targetQuaternion = new THREE.Quaternion();
    const targetScale = new THREE.Vector3();
    const smoothedPosition = new THREE.Vector3();
    const smoothedQuaternion = new THREE.Quaternion();
    let poseInitialized = false;

    const applyPoseMatrix = (matrixData) => {
        targetMatrix.fromArray(matrixData);
        targetMatrix.decompose(targetPosition, targetQuaternion, targetScale);

        if (!poseInitialized) {
            smoothedPosition.copy(targetPosition);
            smoothedQuaternion.copy(targetQuaternion);
            poseInitialized = true;
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

        // MediaPipe's VIDEO running mode requires each detectForVideo
        // timestamp to be strictly greater than the previous one. Raw
        // performance.now() can repeat on the same rounded millisecond on
        // some browsers (timer-resolution privacy protections), which
        // throws — and with no isolation around it, that one throw used to
        // permanently stop the whole renderer.setAnimationLoop callback,
        // freezing the glasses in place with no further head tracking. A
        // monotonic counter plus a try/catch around only the detection call
        // means a single bad frame is skipped instead of ending tracking.
        let frameTimestamp = 0;

        renderer.setAnimationLoop(() => {
            if (!isVisible) {
                return;
            }

            if (video.readyState >= 2 && video.videoWidth > 0 && faceLandmarker) {
                try {
                    frameTimestamp += 1;
                    const result = faceLandmarker.detectForVideo(video, frameTimestamp);
                    const matrix = result.facialTransformationMatrixes?.[0];

                    if (matrix) {
                        hasEverSeenFace = true;
                        clearTimeout(noFaceTimer);
                        noFaceTimer = null;
                        hideStatus();
                        applyPoseMatrix(matrix.data);
                    }
                } catch {
                    // Skip this frame's detection; rendering below still
                    // proceeds toward the last known-good target.
                }
            }

            if (poseInitialized) {
                smoothedPosition.lerp(targetPosition, tuning.lerp);
                smoothedQuaternion.slerp(targetQuaternion, tuning.lerp);
                faceAnchor.position.copy(smoothedPosition);
                faceAnchor.quaternion.copy(smoothedQuaternion);
            }

            // Calibration on top of the tracked pose: a starting scale
            // guess (real face width in cm / this model's own width) times
            // the debug slider's multiplier, an XYZ nudge, and a base
            // rotation correction for whatever this GLB's authored
            // orientation turns out to be relative to MediaPipe's canonical
            // face model — all meant to be dialed in live, not guessed.
            const baseScale = (ASSUMED_FACE_WIDTH_CM / modelWidth) * tuning.scale;
            pivot.scale.setScalar(baseScale);
            pivot.position.set(tuning.offsetX, tuning.offsetY, tuning.offsetZ);
            pivot.rotation.set(
                THREE.MathUtils.degToRad(tuning.pitchDeg),
                THREE.MathUtils.degToRad(tuning.yawDeg),
                THREE.MathUtils.degToRad(tuning.rollDeg),
            );

            occluder.scale.set(
                (OCCLUDER_WIDTH_CM / 2) * tuning.occluderScale,
                (OCCLUDER_HEIGHT_CM / 2) * tuning.occluderScale,
                (OCCLUDER_DEPTH_CM / 2) * tuning.occluderScale,
            );
            occluder.position.set(tuning.offsetX, tuning.offsetY, tuning.occluderZ);

            renderer.render(scene, camera);

            if (isModelReady) {
                stage.classList.add('is-tryon-ready');
            }
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
