/**
 * Testimonials Block Scripts
 */
(function($) {
    'use strict';

    /**
     * Initialise le carousel pour les témoignages
     */
    function initTestimonialsCarousel() {
        if (typeof MarsCarousel === 'undefined') {
            console.warn('MarsCarousel class not found');
            return;
        }

        const carousels = document.querySelectorAll('.testimonials-carousel');
        carousels.forEach(container => {
            new MarsCarousel(container, {
                autoplay: true,
                loop: true
            });
        });
    }

    /**
     * Crée la structure HTML du modal
     */
	function createTestimonialModal() {
        if (document.getElementById('testimonial-modal')) {
            return document.getElementById('testimonial-modal');
        }

        const modal = document.createElement('div');
        modal.id = 'testimonial-modal';
        modal.className = 'testimonial-modal';
        modal.innerHTML = `
            <div class="testimonial-modal-content">
                <button class="modal-close" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
                <div class="testimonial-modal-body">
                    <div class="modal-header-content">
                         <h3 class="modal-author-name h3"></h3>
                         <div class="modal-author-details">
                            <span class="modal-author-profession"></span>
                         </div>
                    </div>
                    <div class="modal-review-text no-animation"></div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        // Event listeners for closing
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideTestimonialModal(modal);
            }
        });

        const closeBtn = modal.querySelector('.modal-close');
        closeBtn.addEventListener('click', function() {
            hideTestimonialModal(modal);
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                hideTestimonialModal(modal);
            }
        });

        return modal;
	}

    function showTestimonialModal(modal) {
        const scrollY = window.scrollY;

        // Disable scroll behavior to prevent jumps
        document.documentElement.style.scrollBehavior = 'auto';

        // Fixer le body tout en gardant la position
        document.body.style.top = `-${scrollY}px`;
        document.body.classList.add('modal-open');
        modal.classList.add('active');
    }

    function hideTestimonialModal(modal) {
        modal.classList.remove('active');

        // Récupérer la position de scroll
        const scrollY = parseInt(document.body.style.top || '0') * -1;

        document.body.classList.remove('modal-open');
        document.body.style.top = '';

        // Restaurer la position instantanément
        window.scrollTo(0, scrollY);

        // Re-enable smooth scroll after a small delay to ensure the jump is processed
        setTimeout(() => {
            document.documentElement.style.scrollBehavior = '';
        }, 10);
    }

    /**
     * Ouvrir le modal pour un élément déclencheur spécifique
     */
	function openTestimonialModal(trigger) {
        const card = trigger.closest('.testimonial-review-card');
        if (!card) return;

        const fullContent = card.querySelector('.testimonial-full-content');
        if (!fullContent) return;

        const modal = createTestimonialModal();

        // Populate modal
        const author = fullContent.querySelector('.modal-author').innerHTML;
        const profession = fullContent.querySelector('.modal-profession').innerHTML;
        const text = fullContent.querySelector('.modal-text').innerHTML;


        modal.querySelector('.modal-author-name').innerHTML = author;
        modal.querySelector('.modal-author-profession').innerHTML = profession;
        modal.querySelector('.modal-review-text').innerHTML = text;

        showTestimonialModal(modal);
    }

    /**
     * Initialiser les événements des modales via délégation
     */
    function initTestimonialModals() {
        // Vérifier si l'écouteur est déjà attaché pour éviter les doublons
        if (document.body.classList.contains('testimonials-handler-attached')) {
            return;
        }

        document.body.addEventListener('click', function(e) {
            const trigger = e.target.closest('.testimonial-modal-trigger');
            if (trigger) {
                e.preventDefault();
                openTestimonialModal(trigger);
            }
        });

        document.body.classList.add('testimonials-handler-attached');
	}



    /**
     * Initialiser tous les blocs Témoignages
     */
    function initTestimonialsBlocks() {
        // Initialiser les sliders
        initTestimonialsCarousel();
        // Initialiser les modale (attache l'écouteur global une seule fois)
        initTestimonialModals();
    }

    // Initialiser au chargement de la page
    $(document).ready(function() {
        initTestimonialsBlocks();
    });

    // Réinitialiser les blocs lors de l'édition dans Gutenberg (ACF)
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=testimonials', function() {
            initTestimonialsBlocks();
        });
    }

})(jQuery);
