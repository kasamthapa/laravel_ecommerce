/**
 * Lightweight scroll parallax for [data-parallax] elements — each moves at
 * a fraction of scroll speed (data-parallax="0.15") via the --parallax-y
 * custom property consumed by the [data-parallax] CSS rule, recomputed on
 * scroll/resize with requestAnimationFrame throttling so it never runs more
 * than once per frame. Disabled entirely under prefers-reduced-motion,
 * same rule the rest of the site's motion follows.
 */
export const bindParallax = () => {
    const elements = document.querySelectorAll('[data-parallax]');

    if (elements.length === 0 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    let ticking = false;

    const update = () => {
        elements.forEach((element) => {
            const speed = parseFloat(element.dataset.parallax) || 0.15;
            const rect = element.getBoundingClientRect();
            const viewportCenter = window.innerHeight / 2;
            const elementCenter = rect.top + rect.height / 2;
            const offset = (elementCenter - viewportCenter) * speed;
            element.style.setProperty('--parallax-y', `${offset}px`);
        });

        ticking = false;
    };

    const onScroll = () => {
        if (ticking) {
            return;
        }

        ticking = true;
        requestAnimationFrame(update);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
};
