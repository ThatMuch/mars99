/**
 * Timeline Block JavaScript
 * Gère l'ouverture des modales pour afficher les descriptions complètes
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les blocs timeline sur la page
    const timelineBlocks = document.querySelectorAll('.timeline-block');
    let scrollPosition = 0;

    /**
     * Sauvegarde la position de scroll actuelle
     */
    function saveScrollPosition() {
        scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
    }

    /**
     * Restaure la position de scroll sauvegardée
     */
    function restoreScrollPosition() {
        window.scrollTo(0, scrollPosition);
    }

    timelineBlocks.forEach(block => {
        const buttons = block.querySelectorAll('.timeline-button');

        if (!buttons.length) return;

        // Créer le modal une seule fois par bloc
        const modal = createModal();
        document.body.appendChild(modal);

        buttons.forEach((button, index) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const step = button.closest('.timeline-step');
                if (!step) return;

                // Récupérer les données de l'étape
                const title = step.querySelector('.timeline-step-title').textContent;
                const image = step.querySelector('.timeline-step-image img');
                const stepData = getStepData(block, index);

                // Remplir le modal avec les données
                populateModal(modal, title, image, stepData);

                // Afficher le modal
                showModal(modal);
            });
        });

        // Fermer le modal en cliquant sur le backdrop ou le bouton fermer
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideModal(modal);
            }
        });

        // Événement spécifique pour le bouton de fermeture
        const closeButton = modal.querySelector('.modal-close');
        if (closeButton) {
            closeButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                hideModal(modal);
            });
        }

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                hideModal(modal);
            }
        });
    });

    /**
     * Crée la structure HTML du modal
     */
    function createModal() {
        const modal = document.createElement('div');
        modal.className = 'timeline-modal';
        modal.innerHTML = `
            <div class="timeline-modal-content timeline-step">
			<div class="timeline-step-header">
			<div class="timeline-step-image">
                            <img src="" alt="" loading="lazy">
                        </div>
                        <h3 class="timeline-step-title h4"></h3>
                <button class="modal-close" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
				</div>
                    <div class="timeline-step-content">
                        <div class="timeline-step-description"></div>
                        <div class="timeline-step-cta mb-4"></div>
                    </div>
            </div>
        `;
        return modal;
    }

    /**
     * Récupère toutes les données d'une étape depuis les données PHP
     */
    function getStepData(block, stepIndex) {
        // Essayer de récupérer les données depuis un attribut data ou une variable globale
        const blockId = block.getAttribute('data-block-id') || 'timeline';
        const timelineData = window[`timelineData_${blockId}`];

        if (timelineData && timelineData.steps && timelineData.steps[stepIndex]) {
            return timelineData.steps[stepIndex];
        }

        // Fallback : récupérer depuis les éléments cachés ou data attributes
        const stepElement = block.querySelectorAll('.timeline-step')[stepIndex];
        const hiddenDescription = stepElement?.querySelector('[data-description]');

        return {
            description: hiddenDescription ? hiddenDescription.getAttribute('data-description') : 'Description non disponible.',
            cta: null
        };
    }

    /**
     * Remplit le modal avec les données de l'étape
     */
    function populateModal(modal, title, imageElement, stepData) {
        const modalTitle = modal.querySelector('.timeline-step-title');
        const modalImage = modal.querySelector('.timeline-step-image img');
        const modalImageContainer = modal.querySelector('.timeline-step-image');
        const modalDescription = modal.querySelector('.timeline-step-description');
        const modalCta = modal.querySelector('.timeline-step-cta');

        modalTitle.textContent = title;
        modalDescription.innerHTML = stepData.description || 'Description non disponible.';

        if (imageElement) {
            modalImage.src = imageElement.src;
            modalImage.alt = imageElement.alt;
            modalImageContainer.style.display = 'block';
        } else {
            modalImageContainer.style.display = 'none';
        }

        // Gérer le CTA
        if (stepData.cta && stepData.cta.url && stepData.cta.title) {
            modalCta.innerHTML = `<a href="${stepData.cta.url}" class="btn btn--primary" target="${stepData.cta.target || '_self'}" rel="noopener"><div class="btn__content">${stepData.cta.title}</div><div class="btn__overlay"></div></a>`;
        } else {
            modalCta.innerHTML = '';
        }
    }

    /**
     * Affiche le modal
     */
    function showModal(modal) {
        // Sauvegarder la position avant d'ouvrir le modal
        saveScrollPosition();
        document.body.style.top = `-${scrollPosition}px`;

        modal.classList.add('active');
        document.body.classList.add('modal-open');

        // Focus sur le modal pour l'accessibilité
        const closeButton = modal.querySelector('.modal-close');
        if (closeButton) {
            closeButton.focus();
        }
    }

    /**
     * Cache le modal
     */
    function hideModal(modal) {
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');

        // Restaurer la position après avoir fermé le modal
        document.body.style.top = '';
        restoreScrollPosition();
    }
});
