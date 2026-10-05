(function ($) {
    /**
     * Initialise Swiper pour le carrousel des derniers articles
     */
    function initRecentPostsSliders() {
        $('.recent-posts-slider').each(function () {
            const slider = this;

            new Swiper(slider, {
                slidesPerView: 1,
                spaceBetween: 24,
                navigation: {
                    nextEl: $(slider).closest('.recent-posts-block').find('.right')[0],
                    prevEl: $(slider).closest('.recent-posts-block').find('.left')[0],
                },

                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 24,
                    },
                    1280: {
                        slidesPerView: 4,
                        spaceBetween: 24,
                    },
                },
            });
        });
    }

    // Initialiser les sliders au chargement de la page
    $(document).ready(function () {
        initRecentPostsSliders();
    });

    // Réinitialiser les sliders lorsqu'un bloc ACF est ajouté ou modifié (pour l'éditeur Gutenberg)
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=recent-posts', initRecentPostsSliders);
    }

})(jQuery);
