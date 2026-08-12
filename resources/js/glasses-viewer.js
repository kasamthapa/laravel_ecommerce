import * as THREE from 'three';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

const prepareModel = (model) => {
    model.traverse((child) => {
        if (!child.isMesh) {
            return;
        }

        child.frustumCulled = false;

        const materials = Array.isArray(child.material) ? child.material : [child.material];
        materials.forEach((material) => {
            if (!material) {
                return;
            }

            material.envMapIntensity = 1.15;
            material.needsUpdate = true;
        });
    });

    const initialBounds = new THREE.Box3().setFromObject(model);
    const initialSize = initialBounds.getSize(new THREE.Vector3());
    const longestSide = Math.max(initialSize.x, initialSize.y, initialSize.z);
    model.scale.setScalar(5.5 / longestSide);

    const scaledBounds = new THREE.Box3().setFromObject(model);
    const center = scaledBounds.getCenter(new THREE.Vector3());
    model.position.sub(center);
};

const disposeMaterial = (material) => {
    Object.values(material).forEach((value) => {
        if (value && typeof value.dispose === 'function') {
            value.dispose();
        }
    });

    material.dispose();
};

const disposeObject = (object) => {
    object.traverse((child) => {
        if (!child.isMesh) {
            return;
        }

        child.geometry?.dispose();

        const materials = Array.isArray(child.material) ? child.material : [child.material];
        materials.forEach((material) => material && disposeMaterial(material));
    });
};

/**
 * Mounts the Three.js viewer into a stage element and returns a dispose
 * function that fully tears the scene, renderer, and listeners back down.
 *
 * @param {HTMLElement} stage
 * @returns {(() => void)|null}
 */
const mountViewer = (stage) => {
    const existingCanvas = stage.querySelector('[data-glasses-canvas]');
    const modelPath = stage.dataset.modelPath;

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
    new GLTFLoader().load(
        modelPath,
        (gltf) => {
            if (signal.aborted) {
                disposeObject(gltf.scene);
                return;
            }

            prepareModel(gltf.scene);
            rotationPivot.add(gltf.scene);
            isModelReady = true;
        },
        undefined,
        () => {
            stage.classList.add('is-webgl-unavailable');
        },
    );

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
        let disposeViewer = null;

        const activate = (view) => {
            tabs.forEach((tab) => {
                const isActive = tab.dataset.viewTab === view;
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.classList.toggle('border-ink', isActive);
                tab.classList.toggle('text-ink', isActive);
                tab.classList.toggle('border-transparent', !isActive);
                tab.classList.toggle('text-stone', !isActive);
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
        };

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => activate(tab.dataset.viewTab));
        });

        window.addEventListener('pagehide', () => {
            if (disposeViewer) {
                disposeViewer();
                disposeViewer = null;
            }
        });
    });
};
