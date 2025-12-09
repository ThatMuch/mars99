(function($) {
    /**
     * Initialise Swiper pour les sliders de témoignages
     */
    function initTestimonialsSliders() {
        $('.testimonials-slider').each(function() {
            const slider = this;

            new Swiper(slider, {
              slidesPerView: 1,
              spaceBetween: 24,
              loop: true,
              autoplay: {
                delay: 5000,
                disableOnInteraction: false,
              },
              navigation: {
                nextEl: ".right",
                prevEl: ".left",
              },

              breakpoints: {
                // Quand la largeur de la fenêtre est >= 768px
                768: {
                  slidesPerView: 2,
                  spaceBetween: 24,
                },
                // Quand la largeur de la fenêtre est >= 992px
                992: {
                  slidesPerView: 3,
                  spaceBetween: 24,
                },
              },
            });
        });
    }

    // Initialiser les sliders au chargement de la page
    $(document).ready(function() {
        initTestimonialsSliders();
    });

    // Réinitialiser les sliders lorsqu'un bloc ACF est ajouté ou modifié (pour l'éditeur Gutenberg)
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=testimonials-slider', initTestimonialsSliders);
    }

})(jQuery);
