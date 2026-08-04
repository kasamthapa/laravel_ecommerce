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

let lightboxEl = null;

const closeLightbox = () => {
    if (!lightboxEl) {
        return;
    }

    lightboxEl.classList.add('hidden');
    lightboxEl.classList.remove('flex');
    document.body.style.overflow = '';
};

const openLightbox = (images, startIndex) => {
    if (!lightboxEl) {
        lightboxEl = document.createElement('div');
        lightboxEl.className = 'fixed inset-0 z-50 hidden items-center justify-center bg-zinc-950/90 p-4';
        lightboxEl.innerHTML = `
            <button type="button" data-lightbox-close aria-label="Close" class="motion-press absolute right-5 top-5 grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></svg>
            </button>
            <button type="button" data-lightbox-prev aria-label="Previous image" class="motion-press absolute left-4 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </button>
            <img data-lightbox-image alt="" class="max-h-[85vh] max-w-[85vw] rounded-lg object-contain">
            <button type="button" data-lightbox-next aria-label="Next image" class="motion-press absolute right-4 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </button>
        `;
        document.body.append(lightboxEl);

        lightboxEl.querySelector('[data-lightbox-close]').addEventListener('click', closeLightbox);
        lightboxEl.addEventListener('click', (event) => {
            if (event.target === lightboxEl) {
                closeLightbox();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeLightbox();
            }
        });
    }

    let index = startIndex;
    const imageEl = lightboxEl.querySelector('[data-lightbox-image]');
    const prevBtn = lightboxEl.querySelector('[data-lightbox-prev]');
    const nextBtn = lightboxEl.querySelector('[data-lightbox-next]');

    const render = () => {
        imageEl.src = images[index];
    };
    render();

    prevBtn.classList.toggle('hidden', images.length < 2);
    nextBtn.classList.toggle('hidden', images.length < 2);
    prevBtn.onclick = () => {
        index = (index - 1 + images.length) % images.length;
        render();
    };
    nextBtn.onclick = () => {
        index = (index + 1) % images.length;
        render();
    };

    lightboxEl.classList.remove('hidden');
    lightboxEl.classList.add('flex');
    document.body.style.overflow = 'hidden';
};

const bindProductGallery = () => {
    document.querySelectorAll('[data-gallery]').forEach((gallery) => {
        const images = JSON.parse(gallery.dataset.images ?? '[]');
        const mainImage = gallery.querySelector('[data-gallery-main]');
        const stage = gallery.querySelector('[data-gallery-stage]');
        const thumbs = gallery.querySelectorAll('[data-gallery-thumb]');

        if (!(mainImage instanceof HTMLImageElement) || images.length === 0) {
            return;
        }

        let currentIndex = 0;

        const setActive = (index) => {
            currentIndex = index;
            mainImage.src = images[index];

            thumbs.forEach((thumb) => {
                const isActive = Number(thumb.dataset.index) === index;
                thumb.classList.toggle('border-[#092b83]', isActive);
                thumb.classList.toggle('border-zinc-200', !isActive);
            });
        };

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', () => setActive(Number(thumb.dataset.index)));
        });

        if (stage instanceof HTMLElement && window.matchMedia('(hover: hover)').matches) {
            stage.addEventListener('mousemove', (event) => {
                const rect = stage.getBoundingClientRect();
                const x = ((event.clientX - rect.left) / rect.width) * 100;
                const y = ((event.clientY - rect.top) / rect.height) * 100;

                mainImage.style.transformOrigin = `${x}% ${y}%`;
                mainImage.style.transform = 'scale(1.8)';
            });

            stage.addEventListener('mouseleave', () => {
                mainImage.style.transform = 'scale(1)';
            });
        }

        mainImage.addEventListener('click', () => openLightbox(images, currentIndex));
    });
};

const bindAdminCharts = async () => {
    const revenueCanvas = document.querySelector('[data-chart="revenue"]');
    const statusCanvas = document.querySelector('[data-chart="status"]');

    if (!(revenueCanvas instanceof HTMLCanvasElement) && !(statusCanvas instanceof HTMLCanvasElement)) {
        return;
    }

    const { Chart, registerables } = await import('chart.js');
    Chart.register(...registerables);

    if (revenueCanvas instanceof HTMLCanvasElement) {
        new Chart(revenueCanvas, {
            type: 'line',
            data: {
                labels: JSON.parse(revenueCanvas.dataset.labels ?? '[]'),
                datasets: [
                    {
                        label: 'Revenue (Rs.)',
                        data: JSON.parse(revenueCanvas.dataset.values ?? '[]'),
                        borderColor: '#092b83',
                        backgroundColor: 'rgba(9, 43, 131, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 0,
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });
    }

    if (statusCanvas instanceof HTMLCanvasElement) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: JSON.parse(statusCanvas.dataset.labels ?? '[]'),
                datasets: [
                    {
                        data: JSON.parse(statusCanvas.dataset.values ?? '[]'),
                        backgroundColor: ['#092b83', '#115be8', '#08765e', '#e25822', '#a855f7', '#71717a', '#dc2626'],
                    },
                ],
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
            },
        });
    }
};

const bindAppInteractions = () => {
    bindAutoSearch();
    bindMotionReveals();
    bindScrollProgress();
    bindFlashMessage();
    bindMobileMenu();
    bindGlassesViewer();
    bindProductGallery();
    bindAdminCharts();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindAppInteractions);
} else {
    bindAppInteractions();
}
import { bindGlassesViewer } from './glasses-viewer';
