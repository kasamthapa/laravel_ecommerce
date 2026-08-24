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

const bindFormLoadingState = () => {
    document.querySelectorAll('form[data-loading-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');

            if (!(button instanceof HTMLButtonElement) || button.disabled) {
                return;
            }

            button.disabled = true;
            button.textContent = button.dataset.loadingLabel || 'Please wait…';
        });
    });
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
        lightboxEl.className = 'fixed inset-0 z-50 hidden items-center justify-center bg-ink/90 p-4';
        lightboxEl.innerHTML = `
            <button type="button" data-lightbox-close aria-label="Close" class="motion-press absolute right-5 top-5 grid h-11 w-11 place-items-center rounded-sm bg-cream/10 text-cream hover:bg-cream/20">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
            </button>
            <button type="button" data-lightbox-prev aria-label="Previous image" class="motion-press absolute left-4 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-sm bg-cream/10 text-cream hover:bg-cream/20">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </button>
            <img data-lightbox-image alt="" class="max-h-[85vh] max-w-[85vw] rounded-sm object-contain">
            <button type="button" data-lightbox-next aria-label="Next image" class="motion-press absolute right-4 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-sm bg-cream/10 text-cream hover:bg-cream/20">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
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
                thumb.classList.toggle('border-volt', isActive);
                thumb.classList.toggle('border-hairline', !isActive);
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
                        borderColor: '#2f4f3e',
                        backgroundColor: 'rgba(47, 79, 62, 0.08)',
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
                        backgroundColor: ['#2f4f3e', '#8b7355', '#a68a64', '#a64d3a', '#6b6b66', '#c7c0b0', '#4a7c4e'],
                    },
                ],
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
            },
        });
    }
};

const bindStickyCta = () => {
    document.querySelectorAll('[data-sticky-cta]').forEach((bar) => {
        const targetSelector = bar.dataset.track;
        const target = targetSelector ? document.querySelector(targetSelector) : null;
        const button = bar.querySelector('[data-sticky-cta-button]');

        if (!(target instanceof HTMLElement)) {
            return;
        }

        // Reveal only once the real buy box has scrolled fully above the
        // viewport, not just whenever it happens to not be intersecting
        // (which is also true before the user has scrolled down at all).
        const observer = new IntersectionObserver(([entry]) => {
            const scrolledPast = entry.boundingClientRect.bottom < 0;
            bar.classList.toggle('translate-y-full', !scrolledPast);
        });
        observer.observe(target);

        button?.addEventListener('click', () => {
            window.Livewire?.dispatch('sticky-add-to-cart');
        });
    });
};

const bindAppInteractions = () => {
    bindAutoSearch();
    bindFormLoadingState();
    bindFlashMessage();
    bindMobileMenu();
    bindProductViewToggle();
    bindProductGallery();
    bindAdminCharts();
    bindScrollReveal();
    bindStickyCta();
    bindCarouselViewers();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindAppInteractions);
} else {
    bindAppInteractions();
}
import { bindProductViewToggle, bindCarouselViewers } from './glasses-viewer';
import { bindScrollReveal } from './scroll-reveal';
