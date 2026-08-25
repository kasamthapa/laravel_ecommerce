import * as THREE from 'three';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';
import { loadModel, disposeObject } from './glasses-model';

/**
 * Mounts the Three.js viewer into a stage element and returns a dispose
 * function that fully tears the scene, renderer, and listeners back down.
 *
 * @param {HTMLElement} stage
 * @returns {(() => void)|null}
 */
const AUTO_ROTATE_SPEED = 0.0032;

const mountViewer = (stage) => {
    const existingCanvas = stage.querySelector('[data-glasses-canvas]');
    const modelPath = stage.dataset.modelPath;
    const tint = stage.dataset.frameTint || stage.dataset.lensTint
        ? { frame: stage.dataset.frameTint || undefined, lens: stage.dataset.lensTint || undefined }
        : undefined;
    // Opt-in only (data-autorotate="true") — the PDP's Photos/3D View tab
    // keeps its existing drag-only behavior untouched; only stages that
    // explicitly ask for it get a slow continuous spin.
    const autoRotate = stage.dataset.autorotate === 'true';

    if (!(stage instanceof HTMLElement) || !(existingCanvas instanceof HTMLCanvasElement) || !modelPath) {
        return null;
    }

    // A <canvas> can only ever hold one WebGL context — once a prior mount's
    // context is lost via forceContextLoss(), re-requesting one on the same
    // node returns that same dead context. Swap in a fresh node so remounting
    // (toggling Photos/3D View back and forth) always gets a live context.
    const canvas = existingCanvas.cloneNode();
    existingCanvas.replaceWith(canvas);

    let renderer;

    try {
        renderer = new THREE.WebGLRenderer({
            alpha: true,
            antialias: true,
            canvas,
            powerPreference: 'high-performance',
            premultipliedAlpha: false,
        });
    } catch {
        stage.classList.add('is-webgl-unavailable');
        return null;
    }

    renderer.setClearColor(0x000000, 0);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.08;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(34, 1, 0.1, 100);
    camera.position.set(0, 0.1, 10.6);

    const environmentGenerator = new THREE.PMREMGenerator(renderer);
    const environmentTexture = environmentGenerator.fromScene(new RoomEnvironment(), 0.04).texture;
    scene.environment = environmentTexture;
    environmentGenerator.dispose();

    scene.add(new THREE.HemisphereLight(0xffffff, 0x38445b, 1.35));

    const keyLight = new THREE.DirectionalLight(0xffffff, 3.8);
    keyLight.position.set(4, 6, 8);
    scene.add(keyLight);

    const rimLight = new THREE.DirectionalLight(0xb9dbff, 2.2);
    rimLight.position.set(-5, 1, 4);
    scene.add(rimLight);

    const rotationPivot = new THREE.Group();
    scene.add(rotationPivot);

    const controller = new AbortController();
    const { signal } = controller;

    let isModelReady = false;
    loadModel(modelPath, { signal, tint })
        .then((modelScene) => {
            rotationPivot.add(modelScene);
            isModelReady = true;
        })
        .catch(() => {
            if (!signal.aborted) {
                stage.classList.add('is-webgl-unavailable');
            }
        });

    const rotation = {
        x: -0.12,
        y: -0.4,
        targetX: -0.12,
        targetY: -0.4,
        velocityX: 0,
        velocityY: 0,
    };
    let isDragging = false;
    let isVisible = true;
    let lastPointerX = 0;
    let lastPointerY = 0;

    const resize = () => {
        const bounds = stage.getBoundingClientRect();

        if (bounds.width === 0 || bounds.height === 0) {
            return;
        }

        renderer.setSize(bounds.width, bounds.height, false);
        camera.aspect = bounds.width / bounds.height;
        camera.updateProjectionMatrix();
    };

    const stopDragging = (event) => {
        if (!isDragging) {
            return;
        }

        isDragging = false;
        stage.classList.remove('is-dragging');

        if (event.pointerId !== undefined && stage.hasPointerCapture(event.pointerId)) {
            stage.releasePointerCapture(event.pointerId);
        }
    };

    stage.addEventListener('pointerdown', (event) => {
        if (!isModelReady) {
            return;
        }

        isDragging = true;
        lastPointerX = event.clientX;
        lastPointerY = event.clientY;
        rotation.velocityX = 0;
        rotation.velocityY = 0;
        stage.classList.add('is-dragging');
        stage.setPointerCapture(event.pointerId);
    }, { signal });

    stage.addEventListener('pointermove', (event) => {
        if (!isDragging) {
            return;
        }

        const deltaX = event.clientX - lastPointerX;
        const deltaY = event.clientY - lastPointerY;
        lastPointerX = event.clientX;
        lastPointerY = event.clientY;
        rotation.targetY += deltaX * 0.014;
        rotation.targetX += deltaY * 0.014;
        rotation.velocityY = deltaX * 0.0018;
        rotation.velocityX = deltaY * 0.0018;
    }, { signal });

    stage.addEventListener('pointerup', stopDragging, { signal });
    stage.addEventListener('pointercancel', stopDragging, { signal });
    stage.addEventListener('lostpointercapture', () => {
        isDragging = false;
        stage.classList.remove('is-dragging');
    }, { signal });
    stage.addEventListener('dblclick', () => {
        rotation.targetX = -0.12;
        rotation.targetY = -0.4;
        rotation.velocityX = 0;
        rotation.velocityY = 0;
    }, { signal });

    const resizeObserver = new ResizeObserver(resize);
    resizeObserver.observe(stage);

    let intersectionObserver;
    if ('IntersectionObserver' in window) {
        intersectionObserver = new IntersectionObserver(([entry]) => {
            isVisible = entry.isIntersecting;
        });
        intersectionObserver.observe(stage);
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    resize();
    renderer.setAnimationLoop(() => {
        if (!isVisible || !isModelReady) {
            return;
        }

        if (!isDragging && !prefersReducedMotion) {
            rotation.targetX += rotation.velocityX;
            rotation.targetY += rotation.velocityY;
            rotation.velocityX *= 0.9;
            rotation.velocityY *= 0.9;

            // Idle auto-spin: only once any drag-release momentum has
            // settled, so a flick doesn't visibly fight the constant drift.
            if (autoRotate && Math.abs(rotation.velocityY) < 0.0004) {
                rotation.targetY += AUTO_ROTATE_SPEED;
            }
        }

        rotation.x = THREE.MathUtils.lerp(rotation.x, rotation.targetX, 0.14);
        rotation.y = THREE.MathUtils.lerp(rotation.y, rotation.targetY, 0.14);
        rotationPivot.rotation.set(rotation.x, rotation.y, -0.025);
        renderer.render(scene, camera);
        stage.classList.add('is-webgl-ready');
    });

    return () => {
        controller.abort();
        renderer.setAnimationLoop(null);
        resizeObserver.disconnect();
        intersectionObserver?.disconnect();
        disposeObject(scene);
        environmentTexture?.dispose();
        renderer.dispose();
        renderer.forceContextLoss();
        stage.classList.remove('is-webgl-ready', 'is-webgl-unavailable', 'is-dragging');
    };
};

/**
 * Binds every Photos/3D View toggle group on the page. The 3D viewer is
 * mounted lazily on first activation and fully disposed whenever the
 * customer switches back to Photos, so repeated toggling never leaks
 * WebGL resources.
 */
export const bindProductViewToggle = () => {
    document.querySelectorAll('[data-product-view]').forEach((group) => {
        const tabs = group.querySelectorAll('[data-view-tab]');
        const panels = group.querySelectorAll('[data-view-panel]');
        const stage = group.querySelector('[data-glasses-viewer]');
        const tryOnStage = group.querySelector('[data-face-tryon]');
        let disposeViewer = null;
        let disposeTryOn = null;

        if (tryOnStage) {
            // Warm the MediaPipe WASM download on idle, well before a
            // customer would realistically click the Try On tab, so most of
            // the startup latency is already paid for by the time they do.
            const scheduleIdle = window.requestIdleCallback || ((callback) => setTimeout(callback, 2000));
            scheduleIdle(() => {
                import('./face-tryon').then(({ preloadFaceTryOn }) => preloadFaceTryOn());
            });
        }

        const activate = (view) => {
            tabs.forEach((tab) => {
                const isActive = tab.dataset.viewTab === view;
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.classList.toggle('border-volt', isActive);
                tab.classList.toggle('text-bone', isActive);
                tab.classList.toggle('border-transparent', !isActive);
                tab.classList.toggle('text-smoke', !isActive);
            });

            panels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.viewPanel !== view);
            });

            if (view === '3d' && stage && !disposeViewer) {
                disposeViewer = mountViewer(stage);
            } else if (view !== '3d' && disposeViewer) {
                disposeViewer();
                disposeViewer = null;
            }

            if (view === 'tryon' && tryOnStage && !disposeTryOn) {
                // The MediaPipe face tracker is a multi-hundred-KB dependency
                // only needed on this tab, so it's fetched on demand rather
                // than bundled into every page load.
                let cancelled = false;
                disposeTryOn = () => {
                    cancelled = true;
                };

                import('./face-tryon').then(({ mountTryOn }) => {
                    if (cancelled) {
                        return;
                    }

                    disposeTryOn = mountTryOn(tryOnStage);
                });
            } else if (view !== 'tryon' && disposeTryOn) {
                disposeTryOn();
                disposeTryOn = null;
            }
        };

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => activate(tab.dataset.viewTab));
        });

        // Secondary entry points into a tab (e.g. the "Try it on" pill
        // overlaid on the product photo) that should behave exactly like
        // clicking the real tab, keeping its active/selected state in sync.
        group.querySelectorAll('[data-view-tab-proxy]').forEach((proxy) => {
            proxy.addEventListener('click', () => activate(proxy.dataset.viewTabProxy));
        });

        window.addEventListener('pagehide', () => {
            if (disposeViewer) {
                disposeViewer();
                disposeViewer = null;
            }

            if (disposeTryOn) {
                disposeTryOn();
                disposeTryOn = null;
            }
        });
    });
};

/**
 * Mounts the same drag-viewer used by the Photos/3D View tab into every
 * carousel card that has a model — but only while the card is actually
 * scrolled into view. Each mount is a real WebGL context plus a PMREM
 * environment bake and its own render loop, so eagerly mounting every card
 * in a "Best Sellers" rail at once would stack up several live contexts a
 * customer never scrolls to. Gating on IntersectionObserver keeps at most
 * a couple mounted at a time regardless of how many carousel cards have a
 * model_path, and tears each one down again once it scrolls back out.
 */
export const bindCarouselViewers = () => {
    const stages = document.querySelectorAll('[data-carousel-viewer]');

    if (stages.length === 0 || !('IntersectionObserver' in window)) {
        return;
    }

    const disposers = new WeakMap();

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            const stage = entry.target;

            if (entry.isIntersecting && !disposers.has(stage)) {
                const dispose = mountViewer(stage);

                if (dispose) {
                    disposers.set(stage, dispose);
                }
            } else if (!entry.isIntersecting && disposers.has(stage)) {
                disposers.get(stage)();
                disposers.delete(stage);
            }
        });
    }, { rootMargin: '160px', threshold: 0.2 });

    stages.forEach((stage) => observer.observe(stage));

    window.addEventListener('pagehide', () => {
        stages.forEach((stage) => disposers.get(stage)?.());
    });
};
