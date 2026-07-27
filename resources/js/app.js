const bindAutoSearch = () => {
    document.querySelectorAll('[data-auto-search-form]').forEach((form) => {
        const input = form.querySelector('[data-auto-search-input]');

        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        let timeoutId;
        let previousValue = input.value.trim();

        input.addEventListener('input', () => {
            window.clearTimeout(timeoutId);

            timeoutId = window.setTimeout(() => {
                const nextValue = input.value.trim();

                if (nextValue === previousValue) {
                    return;
                }

                previousValue = nextValue;
                form.requestSubmit();
            }, 450);
        });
    });
};

const bindMotionReveals = () => {
    const revealElements = document.querySelectorAll('[data-motion-reveal]');

    if (revealElements.length === 0) {
        return;
    }

    if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        revealElements.forEach((element) => {
            element.classList.add('is-visible');
        });

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        {
            rootMargin: '0px 0px -12% 0px',
            threshold: 0.12,
        },
    );

    revealElements.forEach((element, index) => {
        element.classList.add('motion-reveal');
        element.style.setProperty('--motion-delay', `${Math.min(index * 55, 260)}ms`);
        observer.observe(element);
    });
};

const bindScrollProgress = () => {
    const progressBar = document.querySelector('[data-scroll-progress]');

    if (!(progressBar instanceof HTMLElement)) {
        return;
    }

    let ticking = false;

    const updateProgress = () => {
        const scrollableHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = scrollableHeight > 0 ? window.scrollY / scrollableHeight : 0;

        progressBar.style.setProperty('--scroll-progress', Math.min(Math.max(progress, 0), 1).toString());
        ticking = false;
    };

    const requestUpdate = () => {
        if (ticking) {
            return;
        }

        ticking = true;
        window.requestAnimationFrame(updateProgress);
    };

    updateProgress();
    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);
};

const bindFlashMessage = () => {
    const flash = document.querySelector('[data-flash-message]');

    if (!(flash instanceof HTMLElement)) {
        return;
    }

    window.setTimeout(() => {
        flash.style.opacity = '0';
        window.setTimeout(() => flash.remove(), 500);
    }, 4000);
};

const bindMobileMenu = () => {
    const toggle = document.querySelector('[data-mobile-menu-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (!(toggle instanceof HTMLElement) || !(menu instanceof HTMLElement)) {
        return;
    }

    toggle.addEventListener('click', () => {
        const isNowHidden = menu.classList.toggle('hidden');
        toggle.setAttribute('aria-expanded', isNowHidden ? 'false' : 'true');
    });
};

const bindAppInteractions = () => {
    bindAutoSearch();
    bindMotionReveals();
    bindScrollProgress();
    bindFlashMessage();
    bindMobileMenu();
    bindGlassesViewer();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindAppInteractions);
} else {
    bindAppInteractions();
}
import { bindGlassesViewer } from './glasses-viewer';
