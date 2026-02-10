/**
 * Mars Carousel - Lightweight CSS-first carousel
 *
 * Usage:
 * new MarsCarousel(containerElement, options);
 */
class MarsCarousel {
    constructor(container, options = {}) {
        this.container = typeof container === 'string' ? document.querySelector(container) : container;
        if (!this.container) return;

        this.track = this.container.querySelector('.mars-carousel-track');
        if (!this.track) return;

        this.slides = Array.from(this.track.children);
        this.prevBtn = this.container.querySelector('.mars-carousel-prev');
        this.nextBtn = this.container.querySelector('.mars-carousel-next');

        // Configuration
        this.options = Object.assign({
            loop: false,
            autoplay: false,
            autoplayDelay: 3000,
            align: 'start' // start, center, end
        }, options);

        this.init();
    }

    init() {
        this.setupEvents();
        this.updateButtons();

        if (this.options.autoplay) {
            this.startAutoplay();
        }
    }

    setupEvents() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.scroll('prev');
            });
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.scroll('next');
            });
        }

        // Update buttons on scroll
        this.track.addEventListener('scroll', () => {
            this.updateButtons();
        }, { passive: true });

        // Pause autoplay on interaction
        this.container.addEventListener('mouseenter', () => this.stopAutoplay());
        this.container.addEventListener('mouseleave', () => {
            if (this.options.autoplay) this.startAutoplay();
        });
        this.container.addEventListener('touchstart', () => this.stopAutoplay(), { passive: true });
    }

    scroll(direction) {
        const slideWidth = this.slides[0].offsetWidth;
        const gap = parseInt(window.getComputedStyle(this.track).gap || '0');
        const scrollAmount = slideWidth + gap;

        const currentScroll = this.track.scrollLeft;
        const targetScroll = direction === 'next'
            ? currentScroll + scrollAmount
            : currentScroll - scrollAmount;

        this.track.scrollTo({
            left: targetScroll,
            behavior: 'smooth'
        });
    }

    updateButtons() {
        if (!this.prevBtn || !this.nextBtn) return;

        const tolerance = 10; // px
        const maxScroll = this.track.scrollWidth - this.track.clientWidth;

        // Prev button
        if (this.track.scrollLeft <= tolerance) {
            this.prevBtn.classList.add('disabled');
            this.prevBtn.setAttribute('disabled', 'true');
        } else {
            this.prevBtn.classList.remove('disabled');
            this.prevBtn.removeAttribute('disabled');
        }

        // Next button
        if (this.track.scrollLeft >= maxScroll - tolerance) {
            this.nextBtn.classList.add('disabled');
            this.nextBtn.setAttribute('disabled', 'true');
        } else {
            this.nextBtn.classList.remove('disabled');
            this.nextBtn.removeAttribute('disabled');
        }
    }

    startAutoplay() {
        this.stopAutoplay();
        this.autoplayInterval = setInterval(() => {
            const maxScroll = this.track.scrollWidth - this.track.clientWidth;
            if (this.track.scrollLeft >= maxScroll - 10) {
                // Loop back to start if loop is enabled, otherwise stop
                if (this.options.loop) {
                    this.track.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    this.stopAutoplay();
                }
            } else {
                this.scroll('next');
            }
        }, this.options.autoplayDelay);
    }

    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }
}

// Expose global
window.MarsCarousel = MarsCarousel;
