/**
 * Metrics Block JavaScript
 * Gère l'expansion/contraction des cartes métriques et l'animation des nombres
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les blocs de métriques sur la page
    const metricsBlocks = document.querySelectorAll('.metrics-block');

    metricsBlocks.forEach(block => {
        const grid = block.querySelector('.metrics-grid');
        const cards = block.querySelectorAll('.metric-card');
        const buttons = block.querySelectorAll('.metric-button');
        const valueElements = block.querySelectorAll('.metric-value');

        if (!grid || !cards.length) return;

        let expandedCard = null;
        let animationTriggered = false;

        buttons.forEach((button, index) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const clickedCard = cards[index];

                // Si la carte cliquée est déjà étendue, la fermer
                if (expandedCard === clickedCard) {
                    collapseAllCards();
                    return;
                }

                // Sinon, étendre la carte cliquée
                expandCard(clickedCard);
            });
        });

        /**
         * Animation des nombres de 0 à leur valeur finale
         */
        function animateNumbers() {
            if (animationTriggered) return;
            animationTriggered = true;

            valueElements.forEach(element => {
                const finalValue = element.textContent.trim();
                const numericValue = parseFloat(finalValue.replace(/[^\d.,]/g, '').replace(',', '.'));

                // Si ce n'est pas un nombre, ne pas animer
                if (isNaN(numericValue)) return;

                // Extraire le suffixe (%, K, M, etc.)
                const suffix = finalValue.replace(/[\d.,\s]/g, '');

                // Déterminer la durée de l'animation
                const duration = 2000; // 2 secondes
                const startTime = Date.now();

                // Sauvegarder la valeur originale
                element.setAttribute('data-original-value', finalValue);

                function updateNumber() {
                    const elapsed = Date.now() - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Utiliser une fonction d'easing pour un effet plus fluide
                    const easedProgress = easeOutCubic(progress);
                    const currentValue = numericValue * easedProgress;

                    // Formater le nombre selon sa valeur
                    let formattedValue;
                    if (numericValue >= 1000) {
                        formattedValue = Math.floor(currentValue).toLocaleString();
                    } else if (numericValue >= 10) {
                        formattedValue = Math.floor(currentValue);
                    } else {
                        formattedValue = currentValue.toFixed(1);
                    }

                    element.textContent = formattedValue + suffix;

                    if (progress < 1) {
                        requestAnimationFrame(updateNumber);
                    } else {
                        // S'assurer que la valeur finale est exacte
                        element.textContent = finalValue;
                    }
                }

                // Commencer l'animation
                element.textContent = '0' + suffix;
                updateNumber();
            });
        }

        /**
         * Fonction d'easing pour un effet plus fluide
         */
        function easeOutCubic(t) {
            return 1 - Math.pow(1 - t, 3);
        }

        /**
         * Observer d'intersection pour déclencher l'animation quand le bloc est visible
         */
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !animationTriggered) {
                    animateNumbers();
                }
            });
        }, {
            threshold: 0.3 // Déclencher quand 30% du bloc est visible
        });

        observer.observe(block);

        /**
         * Étend une carte et cache les autres
         */
        function expandCard(cardToExpand) {
            // Marquer la carte comme étendue
            expandedCard = cardToExpand;

            cards.forEach(card => {
                if (card === cardToExpand) {
                    // Étendre la carte sélectionnée
                    card.classList.add('expanded');
                    card.classList.remove('hidden');
                } else {
                    // Cacher les autres cartes
                    card.classList.add('hidden');
                    card.classList.remove('expanded');
                }
            });

            // Ajouter une classe au grid pour les styles globaux
            grid.classList.add('has-expanded-card');

            // Faire défiler vers la carte étendue si nécessaire
            setTimeout(() => {
                cardToExpand.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 300);
        }

        /**
         * Ferme toutes les cartes et les remet à l'état normal
         */
        function collapseAllCards() {
            expandedCard = null;

            cards.forEach(card => {
                card.classList.remove('expanded', 'hidden');
            });

            grid.classList.remove('has-expanded-card');
        }

        // Fermer les cartes étendues en cliquant en dehors
        document.addEventListener('click', function(e) {
            if (expandedCard && !block.contains(e.target)) {
                collapseAllCards();
            }
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && expandedCard) {
                collapseAllCards();
            }
        });
    });
});
