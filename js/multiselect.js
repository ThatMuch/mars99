/**
 * Script pour gérer les éléments multiselect personnalisés
 *
 * Ce script transforme les éléments select multiples standard en composants multiselect personnalisés
 * avec fonctionnalités avancées : recherche, sélection/désélection, affichage d'options multiples, etc.
 */

(function($) {
    'use strict';

    // S'exécute lorsque le document est prêt
    $(document).ready(function() {
        // Initialise les multiselects
        initMultiselects();

        // Réinitialisation après le chargement AJAX de Gravity Forms
        $(document).on('gform_post_render', function() {
            initMultiselects();
        });

        // Écouter les événements de traitement des optgroups
        $(document).on('optgroupsProcessed', function(event) {
            const selectElement = $(event.detail.selectElement);
            // Si c'est un select multiple et qu'il n'est pas encore initialisé
            if (selectElement.is('[multiple]') && !selectElement.hasClass('multiselect-initialized')) {
                createMultiselect(selectElement);
            }
        });
    });

    /**
     * Initialise tous les multiselects dans la page
     */
    function initMultiselects() {
        // Ciblons les selects multiples, Gravity Forms et abyss-multiselect
        $('select[multiple], .ginput_container_multiselect select, .abyss-multiselect').each(function() {
            if (!$(this).hasClass('multiselect-initialized')) {
                createMultiselect($(this));
            }
        });

        // Écouteur d'événements global pour fermer les dropdowns ouverts
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.multiselect').length) {
                $('.multiselect__container.open').removeClass('open');
                $('.multiselect__dropdown.open').removeClass('open');
            }
        });
    }

    /**
     * Crée un composant multiselect personnalisé à partir d'un select standard
     * @param {jQuery} selectElement - L'élément select à transformer
     */
    function createMultiselect(selectElement) {
        // IMPORTANT: Traiter les optgroups AVANT d'initialiser le multiselect
        if (window.OptgroupHandler && typeof window.OptgroupHandler.processSelect === 'function') {
            window.OptgroupHandler.processSelect(selectElement[0]);
        }

        // Marque le select comme initialisé
        selectElement.addClass('multiselect-initialized');

        // Obtient le nom et l'ID pour préserver la fonctionnalité de formulaire
        const selectName = selectElement.attr('name');
        const selectId = selectElement.attr('id') || 'multiselect-' + Math.floor(Math.random() * 1000);

        // Crée le conteneur multiselect
        const multiselectContainer = $('<div>', {
            'class': 'multiselect',
            'data-name': selectName
        });

        // Crée le conteneur visible qui affiche les options sélectionnées
        const selectedContainer = $('<div>', {
            'class': 'multiselect__container',
            'tabindex': '0'
        });

        // Zone pour afficher les options sélectionnées
        const selectedOptionsContainer = $('<div>', {
            'class': 'multiselect__selected-options'
        });
        selectedContainer.append(selectedOptionsContainer);

        // Crée le dropdown pour les options
        const dropdown = $('<div>', {
            'class': 'multiselect__dropdown'
        });

        // Ajoute une zone de recherche si plus de 10 options
        if (selectElement.find('option').length > 10) {
            const searchContainer = $('<div>', {
                'class': 'multiselect__search'
            });

            const searchInput = $('<input>', {
                'type': 'text',
                'placeholder': 'Rechercher...'
            });

            searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();

                dropdown.find('.multiselect__dropdown-option').each(function() {
                    const optionText = $(this).text().toLowerCase();
                    if (optionText.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            searchContainer.append(searchInput);
            dropdown.append(searchContainer);
        }

        // Stocke le placeholder (première option avec valeur vide)
        let placeholderText = "Sélectionner"; // Texte par défaut
        const firstOption = selectElement.find('option:first');
        if (firstOption.length && firstOption.val() === '') {
            placeholderText = firstOption.text() || placeholderText;
        }

        // Ajoute les options et optgroups au dropdown
        selectElement.children().each(function() {
            if (this.tagName === 'OPTGROUP') {
                // Créer un groupe d'options
                const optgroupLabel = $('<div>', {
                    'class': 'multiselect__dropdown-optgroup-label',
                    'text': $(this).attr('label')
                });
                dropdown.append(optgroupLabel);

                // Ajouter les options du groupe
                $(this).find('option').each(function() {
                    const value = $(this).val();
                    const text = $(this).text();

                    if (value === '') return;

                    const option = $('<div>', {
                        'class': 'multiselect__dropdown-option multiselect__dropdown-option--grouped',
                        'data-value': value
                    });

                    const checkbox = $('<span>', {
                        'class': 'checkbox'
                    });

                    const label = $('<span>', {
                        'text': text
                    });

                    option.append(checkbox).append(label);

                    // Si l'option est présélectionnée
                    if ($(this).is(':selected')) {
                        checkbox.addClass('checked');
                        option.addClass('selected');
                        addSelectedOption(selectedOptionsContainer, value, text);
                    }

                    dropdown.append(option);
                });
            } else if (this.tagName === 'OPTION') {
                // Option directe (non groupée)
                const value = $(this).val();
                const text = $(this).text();

                // Ne pas inclure les options vides souvent utilisées comme placeholders dans le dropdown
                if (value === '' && $(this).is(':first-child')) return;

                const option = $('<div>', {
                    'class': 'multiselect__dropdown-option',
                    'data-value': value
                });

                const checkbox = $('<span>', {
                    'class': 'checkbox'
                });

                const label = $('<span>', {
                    'text': text
                });

                option.append(checkbox).append(label);

                // Si l'option est présélectionnée
                if ($(this).is(':selected')) {
                    checkbox.addClass('checked');
                    option.addClass('selected');
                    addSelectedOption(selectedOptionsContainer, value, text);
                }

                dropdown.append(option);
            }
        });

        // Gestion des clics sur les options (après création de toutes les options)
        dropdown.find('.multiselect__dropdown-option').on('click', function(e) {
            e.stopPropagation();
            const optionValue = $(this).data('value');
            const optionText = $(this).find('span:not(.checkbox)').text();
            const isSelected = $(this).hasClass('selected');

            if (isSelected) {
                // Désélectionne l'option
                $(this).removeClass('selected');
                $(this).find('.checkbox').removeClass('checked');
                removeSelectedOption(selectedOptionsContainer, optionValue);
                selectElement.find('option[value="' + optionValue + '"]').prop('selected', false);
            } else {
                // Sélectionne l'option
                $(this).addClass('selected');
                $(this).find('.checkbox').addClass('checked');
                addSelectedOption(selectedOptionsContainer, optionValue, optionText);
                selectElement.find('option[value="' + optionValue + '"]').prop('selected', true);
            }

            // Déclenche l'événement change pour que les validations fonctionnent
            selectElement.trigger('change');
        });

        // Gestion du clic sur le conteneur pour ouvrir/fermer le dropdown
        selectedContainer.on('click', function(e) {
            e.stopPropagation();
            $(this).toggleClass('open');
            dropdown.toggleClass('open');

            if (dropdown.hasClass('open')) {
                dropdown.find('.multiselect__search input').focus();
            }
        });

        // Gestion du clavier pour l'accessibilité
        selectedContainer.on('keydown', function(e) {
            // Espace ou Entrée pour ouvrir/fermer
            if (e.keyCode === 32 || e.keyCode === 13) {
                e.preventDefault();
                $(this).trigger('click');
            }
        });

        // Assemblage final du composant multiselect
        multiselectContainer.append(selectedContainer).append(dropdown);

        // Remplace le select original par notre multiselect personnalisé
        selectElement.hide().after(multiselectContainer);

        // Stocke le placeholder pour référence future
        if (placeholderText) {
            multiselectContainer.data('placeholder-text', placeholderText);
        }

        // Vérifie si des options sont déjà sélectionnées et ajoute un placeholder si nécessaire
        updatePlaceholder(selectedOptionsContainer);
    }

    /**
     * Ajoute une option sélectionnée au conteneur
     * @param {jQuery} container - Le conteneur d'options sélectionnées
     * @param {string} value - La valeur de l'option
     * @param {string} text - Le texte à afficher
     */
    function addSelectedOption(container, value, text) {
        // Supprime le placeholder si présent
        container.find('.placeholder').remove();

        // Crée l'élément pour l'option sélectionnée
        const selectedOption = $('<div>', {
            'class': 'multiselect__selected-option',
            'data-value': value
        });

        const optionText = $('<span>', {
            'text': text,
            'title': text // Pour le texte trop long
        });

        const removeBtn = $('<span>', {
            'class': 'remove-option',
            'html': '&times;'
        });

        // Gère la suppression au clic
        removeBtn.on('click', function(e) {
            e.stopPropagation();

            // Trouve le select original
            const multiselect = $(this).closest('.multiselect');
            const selectElement = multiselect.prev('select');

            // Désélectionne dans le select original
            selectElement.find('option[value="' + value + '"]').prop('selected', false);

            // Désélectionne dans le dropdown
            multiselect.find('.multiselect__dropdown-option[data-value="' + value + '"]')
                .removeClass('selected')
                .find('.checkbox').removeClass('checked');

            // Supprime l'option sélectionnée
            selectedOption.remove();

            // Met à jour le placeholder si nécessaire
            updatePlaceholder(container);

            // Déclenche l'événement change
            selectElement.trigger('change');
        });

        selectedOption.append(optionText).append(removeBtn);
        container.append(selectedOption);
    }

    /**
     * Supprime une option sélectionnée du conteneur
     * @param {jQuery} container - Le conteneur d'options sélectionnées
     * @param {string} value - La valeur de l'option à supprimer
     */
    function removeSelectedOption(container, value) {
        container.find('.multiselect__selected-option[data-value="' + value + '"]').remove();
        updatePlaceholder(container);
    }

    /**
     * Met à jour le placeholder si aucune option n'est sélectionnée
     * @param {jQuery} container - Le conteneur d'options sélectionnées
     */
    function updatePlaceholder(container) {
        if (container.children().length === 0) {
            const multiselect = container.closest('.multiselect');

            // Utilise le texte du placeholder stocké ou une valeur par défaut
            let placeholderText = multiselect.data('placeholder-text') || "Sélectionner";

            const placeholder = $('<div>', {
                'class': 'placeholder',
                'text': placeholderText
            });
            container.append(placeholder);
        }
    }

})(jQuery);
