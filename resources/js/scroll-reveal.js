/**
 * Subtle fade-and-rise reveal for elements marked [data-reveal], triggered
 * once as each scrolls into view (never re-triggers on scroll back up).
 * Respects prefers-reduced-motion by revealing everything immediately.
 */
export const bindScrollReveal = () => {
    const elements = document.querySelectorAll('[data-reveal]');

    if (elements.length === 0) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add('is-revealed'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-revealed');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    elements.forEach((element) => observer.observe(element));
};
