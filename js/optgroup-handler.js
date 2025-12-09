/**
 * Optgroup Handler
 * Convertit automatiquement les options avec la valeur "optgroup" en balises <optgroup>
 * Fonctionne avec tous les selects du site, y compris Gravity Forms
 */

/**
 * Traite tous les selects existants sur la page
 */
function processAllSelects() {
    const selects = document.querySelectorAll('select');
    selects.forEach(processSelect);
}

/**
 * Traite un select individuel pour convertir les options optgroup
 */
function processSelect(selectElement) {
    // Éviter le double traitement
    if (selectElement.dataset.optgroupProcessed === 'true') {
        return;
    }

    const options = Array.from(selectElement.options);
    let hasOptgroups = false;
    let currentOptgroup = null;
    const newElements = [];

    // Parcourir toutes les options
    for (let i = 0; i < options.length; i++) {
        const option = options[i];

        if (option.value === 'optgroup' || option.classList.contains('optgroup-header')) {
            // Cette option doit devenir un optgroup
            hasOptgroups = true;

            // Fermer l'optgroup précédent s'il existe
            if (currentOptgroup) {
                newElements.push(currentOptgroup);
            }

            // Créer un nouveau optgroup
            currentOptgroup = document.createElement('optgroup');
            currentOptgroup.label = option.textContent.trim();

            // Copier les attributs de l'option vers l'optgroup si nécessaire
            if (option.hasAttribute('data-group-id')) {
                currentOptgroup.setAttribute('data-group-id', option.getAttribute('data-group-id'));
            }

        } else if (option.value !== '') { // Ignorer les options vides (placeholder)
            // Option normale
            const newOption = document.createElement('option');
            newOption.value = option.value;
            newOption.textContent = option.textContent;
            newOption.selected = option.selected;

            // Copier les autres attributs
            Array.from(option.attributes).forEach(attr => {
                if (attr.name !== 'value' && attr.name !== 'selected') {
                    newOption.setAttribute(attr.name, attr.value);
                }
            });

            if (currentOptgroup) {
                currentOptgroup.appendChild(newOption);
            } else {
                newElements.push(newOption);
            }
        }
    }

    // Ajouter le dernier optgroup s'il existe
    if (currentOptgroup) {
        newElements.push(currentOptgroup);
    }

    // Remplacer le contenu du select seulement si des optgroups ont été trouvés
    if (hasOptgroups) {
        // Conserver les options vides (placeholder) au début
        const placeholderOption = selectElement.querySelector('option[value=""]');
        
        // Vider le select
        selectElement.innerHTML = '';

        // Remettre le placeholder en premier si il existe
        if (placeholderOption) {
            selectElement.appendChild(placeholderOption.cloneNode(true));
        }

        // Ajouter tous les nouveaux éléments
        newElements.forEach(element => {
            selectElement.appendChild(element);
        });

        // Marquer comme traité
        selectElement.dataset.optgroupProcessed = 'true';

        // Ajouter une classe CSS pour le styling
        selectElement.classList.add('has-optgroups');

        // Déclencher un événement personnalisé pour notifier les autres scripts
        const event = new CustomEvent('optgroupsProcessed', { 
            detail: { selectElement: selectElement },
            bubbles: true 
        });
        selectElement.dispatchEvent(event);
    }
}

// Exposer les fonctions globalement pour permettre l'intégration avec d'autres scripts
window.OptgroupHandler = {
    processSelect: processSelect,
    processAllSelects: processAllSelects,
    forceReprocess: function() {
        processAllSelects();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Traiter tous les selects existants
    processAllSelects();

    // Observer les changements DOM pour les selects ajoutés dynamiquement (comme Gravity Forms)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Chercher les nouveaux selects dans le noeud ajouté
                        const selects = node.querySelectorAll ? node.querySelectorAll('select') : [];
                        selects.forEach(processSelect);

                        // Vérifier si le noeud lui-même est un select
                        if (node.tagName === 'SELECT') {
                            processSelect(node);
                        }
                    }
                });
            }
        });
    });

    // Observer tout le document
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Hook spécifique pour Gravity Forms
    if (typeof gform !== 'undefined') {
        // Traiter les selects après le rendu des formulaires Gravity Forms
        document.addEventListener('gform_post_render', function(event) {
            setTimeout(() => {
                const form = document.getElementById('gform_' + event.detail.formId);
                if (form) {
                    const selects = form.querySelectorAll('select');
                    selects.forEach(processSelect);
                }
            }, 100);
        });
    }

    // Hook pour traiter les selects avant l'initialisation de Gravity Forms
    document.addEventListener('gform_post_conditional_logic', function(event) {
        const form = document.getElementById('gform_' + event.detail.formId);
        if (form) {
            const selects = form.querySelectorAll('select:not([data-optgroup-processed="true"])');
            selects.forEach(processSelect);
        }
    });
});