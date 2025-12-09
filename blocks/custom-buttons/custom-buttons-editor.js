/**
 * Custom Buttons Block - Editor Script
 * Bloc personnalisé pour créer des boutons avec styles et icônes FontAwesome
 */

(function (blocks, i18n, element, components, blockEditor, data) {
    'use strict';

    const { registerBlockType } = blocks;
    const { __ } = i18n;
    const { Component, Fragment, createElement } = element;
    const {
        PanelBody,
        SelectControl,
        TextControl,
        ToggleControl,
        Button,
        Toolbar,
        ToolbarGroup
    } = components;
    const {
        InspectorControls,
        BlockControls,
        AlignmentToolbar
    } = blockEditor;

    // Helper function pour créer des éléments plus facilement
    const el = createElement;

    // Liste des icônes FontAwesome populaires
    const fontAwesomeIcons = [
        { label: 'Aucune icône', value: '' },
        { label: 'Télécharger', value: 'fas fa-download' },
        { label: 'Lien externe', value: 'fas fa-external-link-alt' },
        { label: 'Fichier PDF', value: 'fas fa-file-pdf' },
        { label: 'Email', value: 'fas fa-envelope' },
        { label: 'Téléphone', value: 'fas fa-phone' },
        { label: 'Flèche droite', value: 'fas fa-arrow-right' },
        { label: 'Flèche gauche', value: 'fas fa-arrow-left' },
        { label: 'Plus', value: 'fas fa-plus' },
        { label: 'Information', value: 'fas fa-info-circle' },
        { label: 'Étoile', value: 'fas fa-star' },
        { label: 'Coeur', value: 'fas fa-heart' },
        { label: 'Partager', value: 'fas fa-share' },
        { label: 'Recherche', value: 'fas fa-search' },
        { label: 'Calendrier', value: 'fas fa-calendar' },
        { label: 'Utilisateur', value: 'fas fa-user' },
        { label: 'Paramètres', value: 'fas fa-cog' },
        { label: 'Accueil', value: 'fas fa-home' },
        { label: 'Shopping', value: 'fas fa-shopping-cart' },
        { label: 'Jouer', value: 'fas fa-play' }
    ];

    class CustomButtonsBlock extends Component {
        constructor(props) {
            super(props);
            this.addButton = this.addButton.bind(this);
            this.updateButton = this.updateButton.bind(this);
            this.removeButton = this.removeButton.bind(this);
        }

        addButton() {
            const { attributes, setAttributes } = this.props;
            const { buttons } = attributes;

            const newButton = {
                text: 'Nouveau bouton',
                url: '',
                style: 'primary',
                icon: '',
                target: '_self',
                download: false
            };

            setAttributes({
                buttons: [...buttons, newButton]
            });
        }

        updateButton(index, key, value) {
            const { attributes, setAttributes } = this.props;
            const { buttons } = attributes;

            const updatedButtons = [...buttons];
            updatedButtons[index] = {
                ...updatedButtons[index],
                [key]: value
            };

            setAttributes({ buttons: updatedButtons });
        }

        removeButton(index) {
            const { attributes, setAttributes } = this.props;
            const { buttons } = attributes;

            const updatedButtons = buttons.filter((_, i) => i !== index);
            setAttributes({ buttons: updatedButtons });
        }

        render() {
            const { attributes, setAttributes, className } = this.props;
            const { buttons, alignment, spacing } = attributes;

            const blockClasses = [
                className,
                'custom-buttons-group',
                `alignment-${alignment}`,
                `spacing-${spacing}`
            ].filter(Boolean).join(' ');

            // Créer les panneaux de boutons
            const buttonPanels = buttons.map((button, index) => {
                return el(PanelBody, {
                    key: index,
                    title: `${__('Bouton')} ${index + 1}: ${button.text || __('Sans titre')}`,
                    initialOpen: false
                }, [
                    el(TextControl, {
                        key: 'text',
                        label: __('Texte du bouton'),
                        value: button.text || '',
                        onChange: (value) => this.updateButton(index, 'text', value)
                    }),

                    el(TextControl, {
                        key: 'url',
                        label: __('URL'),
                        value: button.url || '',
                        onChange: (value) => this.updateButton(index, 'url', value),
                        help: __('URL du lien ou chemin vers le fichier à télécharger')
                    }),

                    el(SelectControl, {
                        key: 'style',
                        label: __('Style du bouton'),
                        value: button.style || 'primary',
                        options: [
                            { label: __('Principal'), value: 'primary' },
                            { label: __('Contour'), value: 'outline' },
                            { label: __('Secondaire'), value: 'secondary' },
                            { label: __('Texte seulement'), value: 'text' }
                        ],
                        onChange: (value) => this.updateButton(index, 'style', value)
                    }),

                    el(SelectControl, {
                        key: 'icon',
                        label: __('Icône FontAwesome'),
                        value: button.icon || '',
                        options: fontAwesomeIcons,
                        onChange: (value) => this.updateButton(index, 'icon', value)
                    }),

                    el(SelectControl, {
                        key: 'target',
                        label: __('Cible du lien'),
                        value: button.target || '_self',
                        options: [
                            { label: __('Même fenêtre'), value: '_self' },
                            { label: __('Nouvelle fenêtre'), value: '_blank' }
                        ],
                        onChange: (value) => this.updateButton(index, 'target', value)
                    }),

                    el(ToggleControl, {
                        key: 'download',
                        label: __('Téléchargement de fichier'),
                        checked: button.download || false,
                        onChange: (value) => this.updateButton(index, 'download', value),
                        help: __('Cochez si ce bouton doit télécharger un fichier')
                    }),

                    el(Button, {
                        key: 'remove',
                        isDestructive: true,
                        onClick: () => this.removeButton(index),
                        style: { marginTop: '10px' }
                    }, __('Supprimer ce bouton'))
                ]);
            });

            // Créer les éléments de contrôle
            const controls = el(Fragment, {}, [
                el(BlockControls, { key: 'block-controls' }, [
                    el(ToolbarGroup, { key: 'alignment' }, [
                        el(AlignmentToolbar, {
                            value: alignment,
                            onChange: (value) => setAttributes({ alignment: value })
                        })
                    ]),
                    el(ToolbarGroup, { key: 'add-button' }, [
                        el(Button, {
                            icon: 'plus-alt',
                            label: __('Ajouter un bouton'),
                            onClick: this.addButton
                        })
                    ])
                ]),

                el(InspectorControls, { key: 'inspector' }, [
                    el(PanelBody, {
                        title: __('Paramètres généraux'),
                        initialOpen: true
                    }, [
                        el(SelectControl, {
                            label: __('Espacement'),
                            value: spacing,
                            options: [
                                { label: __('Compact'), value: 'compact' },
                                { label: __('Normal'), value: 'normal' },
                                { label: __('Large'), value: 'large' }
                            ],
                            onChange: (value) => setAttributes({ spacing: value })
                        })
                    ]),
                    ...buttonPanels
                ])
            ]);

            // Créer le contenu principal
            let mainContent;

            if (buttons.length === 0) {
                mainContent = el('div', {
                    className: 'custom-buttons-placeholder'
                }, [
                    el('p', { key: 'text' }, __('Cliquez sur "Ajouter un bouton" pour commencer')),
                    el(Button, {
                        key: 'add-btn',
                        isPrimary: true,
                        onClick: this.addButton
                    }, __('Ajouter un bouton'))
                ]);
            } else {
                const buttonElements = buttons.map((button, index) => {
                    const buttonClasses = [
                        'custom-button',
                        `btn-${button.style || 'primary'}`,
                        'editor-button'
                    ].join(' ');

                    const buttonContent = [];

                    if (button.icon) {
                        buttonContent.push(
                            el('i', {
                                key: 'icon',
                                className: button.icon,
                                'aria-hidden': 'true'
                            })
                        );
                    }

                    buttonContent.push(
                        el('span', {
                            key: 'text',
                            className: 'button-text'
                        }, button.text || __('Texte du bouton'))
                    );

                    return el('div', {
                        key: index,
                        className: 'custom-button-wrapper'
                    }, [
                        el('a', {
                            key: 'button',
                            className: buttonClasses,
                            onClick: (e) => e.preventDefault()
                        }, buttonContent),
                        el(Button, {
                            key: 'remove',
                            className: 'custom-button-remove',
                            icon: 'no-alt',
                            label: __('Supprimer'),
                            onClick: () => this.removeButton(index)
                        })
                    ]);
                });

                buttonElements.push(
                    el(Button, {
                        key: 'add-more',
                        className: 'custom-button-add',
                        icon: 'plus-alt',
                        onClick: this.addButton
                    }, __('Ajouter un bouton'))
                );

                mainContent = buttonElements;
            }

            return el(Fragment, {}, [
                controls,
                el('div', {
                    className: blockClasses
                }, mainContent)
            ]);
        }
    }

    // Enregistrer le bloc
    registerBlockType('mars/custom-buttons', {
        title: __('Boutons personnalisés'),
        description: __('Créez des boutons avec styles personnalisés et icônes FontAwesome'),
        icon: 'button',
        category: 'design',
        keywords: [__('bouton'), __('lien'), __('téléchargement'), __('icône')],

        attributes: {
            buttons: {
                type: 'array',
                default: []
            },
            alignment: {
                type: 'string',
                default: 'left'
            },
            spacing: {
                type: 'string',
                default: 'normal'
            }
        },

        edit: CustomButtonsBlock,

        save: function() {
            // Le rendu est géré côté serveur
            return null;
        }
    });

})(
    window.wp.blocks,
    window.wp.i18n,
    window.wp.element,
    window.wp.components,
    window.wp.blockEditor,
    window.wp.data
);
