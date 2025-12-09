/**
 * Mobile Menu JavaScript
 * Gère l'ouverture/fermeture du menu mobile et empêche le scroll
 */

document.addEventListener('DOMContentLoaded', function() {
    const sideMenuCheckbox = document.querySelector('.side-menu');
    const body = document.body;

    /**
     * Utilitaire pour gérer les classes de scroll
     * Priorise modal-open sur mobile-menu-open
     */
    function updateScrollClasses() {
        const hasModal = body.classList.contains('modal-open');
        const hasMenuOpen = sideMenuCheckbox && sideMenuCheckbox.checked;

        if (hasModal) {
            // Si une modale est ouverte, elle a priorité
            body.classList.remove('mobile-menu-open');
            body.classList.add('modal-open');
        } else if (hasMenuOpen) {
            // Sinon, si le menu est ouvert
            body.classList.add('mobile-menu-open');
        } else {
            // Sinon, aucune des deux
            body.classList.remove('mobile-menu-open', 'modal-open');
        }
    }

    if (sideMenuCheckbox) {
        sideMenuCheckbox.addEventListener('change', function() {
            updateScrollClasses();
        });
    }

    // Fermer le menu si on redimensionne la fenêtre (retour au desktop)
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) { // Plus grand que mobile
            if (sideMenuCheckbox) {
                sideMenuCheckbox.checked = false;
            }
            updateScrollClasses();
        }
    });

    // Fermer le menu si on clique sur un lien (navigation)
    const menuLinks = document.querySelectorAll('.main-menu a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (sideMenuCheckbox && sideMenuCheckbox.checked) {
                sideMenuCheckbox.checked = false;
                updateScrollClasses();
            }
        });
    });

    // Observer les changements de classe modal-open pour gérer les conflits
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                // Délai pour laisser les autres scripts modifier les classes
                setTimeout(updateScrollClasses, 10);
            }
        });
    });

    // Observer les changements sur le body
    observer.observe(body, {
        attributes: true,
        attributeFilter: ['class']
    });
});
