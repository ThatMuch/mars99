/**
 * Testimonials Block Scripts
 */
(function($) {
    'use strict';

    /**
     * Initialise le slider Swiper pour les témoignages
     */
    function initTestimonialsSlider() {
        // Vérifier si Swiper est disponible
        if (typeof Swiper === 'undefined') {
            console.warn('Swiper not loaded');
            return;
        }

        // Initialiser le slider
        const reviewsSliders = document.querySelectorAll('.testimonials-slider');

        reviewsSliders.forEach(slider => {
            new Swiper(slider, {
                slidesPerView: 1,
                spaceBetween: 24,
                loop: false,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.right',
                    prevEl: '.left',
                },
                breakpoints: {
                    // Quand la largeur de la fenêtre est >= 768px
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24
                    },
                    // Quand la largeur de la fenêtre est >= 992px
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    }
                }
            });
        });
    }

    /**
     * Initialiser tous les blocs Témoignages
     */
    function initTestimonialsBlocks() {
        // Initialiser les sliders
        initTestimonialsSlider();
    }

    // Initialiser au chargement de la page
    $(document).ready(function() {
        // Charger Swiper si nécessaire (si non déjà chargé par le thème ou un autre bloc)
        // Note: Idéalement, Swiper devrait être enqueued via PHP si utilisé par plusieurs blocs
        if (document.querySelector('.testimonials-slider') && typeof Swiper === 'undefined') {
            const swiperCss = document.createElement('link');
            swiperCss.rel = 'stylesheet';
            swiperCss.href = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css';
            document.head.appendChild(swiperCss);

            const swiperJs = document.createElement('script');
            swiperJs.src = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js';
            swiperJs.onload = function() {
                initTestimonialsSlider();
            };
            document.body.appendChild(swiperJs);
        } else {
            initTestimonialsSlider();
        }
    });

    // Réinitialiser les blocs lors de l'édition dans Gutenberg (ACF)
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=testimonials', function() {
            initTestimonialsBlocks();
        });
    }

})(jQuery);
