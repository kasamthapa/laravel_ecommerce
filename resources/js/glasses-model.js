import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

/**
 * Shared GLB loading/disposal helpers used by both the drag-to-rotate 3D
 * viewer and the webcam try-on overlay, so there is exactly one loader
 * implementation for the product's model_path.
 */

export const prepareModel = (model) => {
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

    // The uniform scale above normalizes the model's LONGEST bounding-box
    // axis to 5.5 units — for glasses, that's often the temple arms' Z
    // depth, not the lens-to-lens width. Callers that need to fit the frame
    // to a real measurement (e.g. try-on scaling to the wearer's eye
    // distance) need the actual left-right (X) extent, which can be
    // meaningfully smaller than 5.5 — so it's captured here rather than
    // assumed.
    const scaledSize = scaledBounds.getSize(new THREE.Vector3());
    model.userData.fittedWidth = scaledSize.x;
};

export const disposeMaterial = (material) => {
    Object.values(material).forEach((value) => {
        if (value && typeof value.dispose === 'function') {
            value.dispose();
        }
    });

    material.dispose();
};

export const disposeObject = (object) => {
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
 * Loads a GLB, centers/scales it via prepareModel(), and resolves with the
 * ready-to-add scene. If `signal` aborts before (or as) the load finishes,
 * the loaded scene is disposed instead of resolving, so callers don't need
 * to guard against a stale mount handing back a model after teardown.
 *
 * @param {string} modelPath
 * @param {{ signal?: AbortSignal }} [options]
 * @returns {Promise<THREE.Object3D>}
 */
export const loadModel = (modelPath, { signal } = {}) => new Promise((resolve, reject) => {
    new GLTFLoader().load(
        modelPath,
        (gltf) => {
            if (signal?.aborted) {
                disposeObject(gltf.scene);
                reject(new DOMException('Aborted', 'AbortError'));
                return;
            }

            prepareModel(gltf.scene);
            resolve(gltf.scene);
        },
        undefined,
        (error) => reject(error),
    );
});
