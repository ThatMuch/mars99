document.addEventListener('DOMContentLoaded',function () {
  const buttons = document.body.querySelectorAll(".card-button");
  if (!buttons.length) return;

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

  buttons.forEach((button) => {
    button.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();

      // Trouver la carte parent et sa modale correspondante
      const cardBlock = button.closest('.card-block');
      if (!cardBlock) return;

      const cardId = cardBlock.id;
      const modalId = cardId + '-modal';
      const modal = document.getElementById(modalId);

      if (modal) {
        showModal(modal);
      }
    });
  });

  // Gérer toutes les modales de cartes
  const modals = document.querySelectorAll(".card-modal");

  modals.forEach((modal) => {
    // Fermer le modal en cliquant sur le backdrop
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        hideModal(modal);
      }
    });

    // Événement spécifique pour le bouton de fermeture
    const closeButton = modal.querySelector(".modal-close");
    if (closeButton) {
      closeButton.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        hideModal(modal);
      });
    }
  });

  // Fermer avec la touche Escape
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      const activeModal = document.querySelector(".card-modal.active");
      if (activeModal) {
        hideModal(activeModal);
      }
    }
  });

	  /**
	   * Affiche le modal
	   */
	function showModal(modal) {
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
