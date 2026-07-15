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

const bindViewer = (stage) => {
    const canvas = stage.querySelector('[data-glasses-canvas]');

    if (!(stage instanceof HTMLElement) || !(canvas instanceof HTMLCanvasElement)) {
        return;
    }

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
        return;
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
    scene.environment = environmentGenerator.fromScene(new RoomEnvironment(), 0.04).texture;
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

    let isModelReady = false;
    new GLTFLoader().load(
        '/models/sunglasses-khronos.glb',
        (gltf) => {
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
    });

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
    });

    stage.addEventListener('pointerup', stopDragging);
    stage.addEventListener('pointercancel', stopDragging);
    stage.addEventListener('lostpointercapture', () => {
        isDragging = false;
        stage.classList.remove('is-dragging');
    });
    stage.addEventListener('dblclick', () => {
        rotation.targetX = -0.12;
        rotation.targetY = -0.4;
        rotation.velocityX = 0;
        rotation.velocityY = 0;
    });

    const resizeObserver = new ResizeObserver(resize);
    resizeObserver.observe(stage);

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(([entry]) => {
            isVisible = entry.isIntersecting;
        });
        observer.observe(stage);
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
};

export const bindGlassesViewer = () => {
    document.querySelectorAll('[data-glasses-viewer]').forEach(bindViewer);
};
