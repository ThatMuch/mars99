<?php

/**
 * Card Block Initialization
 * @package AbyssEnergy
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Card
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_card_block');
}

function register_card_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'card',
		'title'             => __('Carte Simple', 'abyssenergy'),
		'description'       => __('Affiche une carte avec image, titre et description.', 'abyssenergy'),
		'render_template'   => 'blocks/card/block-card.php',
		'category'          => 'mars-blocks',
		'icon'              => 'id-alt',
		'keywords'          => array('card', 'carte', 'image', 'content'),
		'supports'          => array(
			'align' => true,
			'mode' => true,
			'jsx' => true
		),
		'example'           => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview' => true
				)
			)
		),
		'enqueue_style'     => get_template_directory_uri() . '/blocks/card/card.css',
		'enqueue_script'    => get_template_directory_uri() . '/blocks/card/card.js',
	));

	if (function_exists('acf_add_local_field_group')) {
		// Créer les champs ACF pour le bloc
		acf_add_local_field_group(array(
			'key' => 'group_card_block',
			'title' => 'Card Block',
			'fields' => array(
				array(
					'key' => 'field_card_style',
					'label' => 'Style',
					'name' => 'style',
					'type' => 'select',
					'choices' => array(
						'neutral' => 'Neutre',
						'primary' => 'Primaire',
						'secondary' => 'Secondaire',
					),
					'default_value' => 'neutral',
					'ui' => 1,
					'return_format' => 'value',
				),
				array(
					'key' => 'field_card_icon',
					'label' => 'Icône',
					'name' => 'icon',
					'type' => 'select',
					'instructions' => 'Choisissez une icône Font Awesome',
					'required' => 0,
					'choices' => array(
						'fas fa-bolt' => 'Éclair',
						'fas fa-download' => 'Télécharger',
						'fas fa-external-link-alt' => 'Lien externe',
						'fas fa-file-pdf' => 'Fichier PDF',
						'fas fa-envelope' => 'Email',
						'fas fa-phone' => 'Téléphone',
						'fas fa-arrow-right' => 'Flèche droite',
						'fas fa-arrow-left' => 'Flèche gauche',
						'fas fa-plus' => 'Plus',
						'fas fa-info-circle' => 'Information',
						'fas fa-star' => 'Étoile',
						'fas fa-heart' => 'Coeur',
						'fas fa-share' => 'Partager',
						'fas fa-search' => 'Recherche',
						'fas fa-calendar' => 'Calendrier',
						'fas fa-user' => 'Utilisateur',
						'fas fa-cog' => 'Paramètres',
						'fas fa-home' => 'Accueil',
						'fas fa-shopping-cart' => 'Shopping',
						'fas fa-play' => 'Jouer',
						'fas fa-check' => 'Validation',
						'fas fa-shield-alt' => 'Sécurité',
						'fas fa-leaf' => 'Écologie',
						'fas fa-chart-line' => 'Croissance',
						'fas fa-handshake' => 'Partenariat',
						'fas fa-globe' => 'Mondial',
						'fas fa-tools' => 'Outils',
						'fas fa-lightbulb' => 'Idée',
					),
					'default_value' => false,
					'allow_null' => 1,
					'ui' => 1,
					'return_format' => 'value',
				),
				array(
					'key' => 'field_card_title',
					'label' => 'Titre',
					'name' => 'title',
					'type' => 'text',
					'required' => 1,
					'default_value' => 'Titre de la carte',
				),
				array(
					'key' => 'field_card_excerpt',
					'label' => 'Extrait',
					'name' => 'excerpt',
					'type' => 'textarea',
					'required' => 0,
					'rows' => 4,
					'new_lines' => 'br',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_card_style',
								'operator' => '==',
								'value' => 'showmore',
							),
						),
					),
				),
				array(
					'key' => 'field_card_description',
					'label' => 'Description',
					'name' => 'description',
					'type' => 'wysiwyg',
					'required' => 0
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/card',
					),
				),
			),
		));
	}
}
