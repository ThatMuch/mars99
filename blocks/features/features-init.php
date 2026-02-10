<?php

/**
 * Initialisation du bloc "Features"
 *
 * @package AbyssEnergy
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}
/**
 * Enregistrement du bloc Features
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_features_block');
}

function register_features_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'features',
		'title'             => __('Features', 'abyssenergy'),
		'description'       => __('Affiche une liste de caractéristiques.', 'abyssenergy'),
		'render_template'   => 'blocks/features/block-features.php',
		'category'          => 'mars-blocks',
		'icon'              => 'list-view',
		'keywords'          => array('features', 'list', 'liste'),
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
	));
	// Créer les champs ACF pour le bloc
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_features',
			'title' => 'Paramètres du bloc Features',
			'fields' => array(
				array(
					'key' => 'field_features_title',
					'label' => 'Titre de la section',
					'name' => 'title',
					'type' => 'text',
					'instructions' => 'Entrez le titre de la section des caractéristiques.',
					'default_value' => 'Nos caractéristiques principales',
					'placeholder' => '',
					'required' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'maxlength' => '',
					'size' => '',
					'prepend' => '',
					'append' => '',
				),
				array(
					'key' => 'field_features_description',
					'label' => 'Description de la section',
					'name' => 'description',
					'type' => 'wysiwyg',
					'instructions' => 'Entrez la description de la section des caractéristiques.',
					'default_value' => '',
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
				array(
					'key' => 'field_features_background_image',
					'label' => 'Image de fond',
					'name' => 'background_image',
					'type' => 'image',
					'instructions' => 'Téléchargez une image pour l\'arrière-plan.',
					'required' => 0,
					'return_format' => 'array',
					'preview_size' => 'medium',
					'library' => 'all',
				),
				array(
					'key' => 'field_features_button',
					'label' => 'Bouton principal',
					'name' => 'main_button',
					'type' => 'link',
					'instructions' => 'Ajouter un bouton principal pour la section des caractéristiques (optionnel).',
					'required' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'array',

				),
				array(
					'key' => 'field_features_items',
					'label' => 'Caractéristiques',
					'name' => 'features',
					'type' => 'repeater',
					'instructions' => 'Ajoutez les caractéristiques à afficher.',
					'required' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => '',
					'min' => 1,
					'max' => 0,
					'layout' => 'block',
					'hide_empty_message' => 0,
					'sub_fields' => array(
						array(
							'key' => 'field_feature_icon',
							'label' => 'Icône',
							'name' => 'icon',
							'type' => 'image',
							'instructions' => 'Téléchargez une image pour l\'icône.',
							'required' => 1,
							'return_format' => 'id',
							'preview_size' => 'medium',
							'library' => 'all',
						),
						array(
							'key' => 'field_feature_title',
							'label' => 'Titre de la caractéristique',
							'name' => 'title',
							'type' => 'text',
							'instructions' => 'Entrez le titre de la caractéristique.',
							'required' => 1,
							'default_value' => '',
							'placeholder' => '',
							'maxlength' => '',
							'size' => '',
							'prepend' => '',
							'append' => '',
						),
						array(
							'key' => 'field_feature_description',
							'label' => 'Description de la caractéristique',
							'name' => 'description',
							'type' => 'wysiwyg',
							'instructions' => 'Entrez la description de la caractéristique.',
							'required' => 1,
							'default_value' => '',
							'placeholder' => '',
							'maxlength' => '',
							'size' => '',
							'prepend' => '',
							'append' => '',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/features',
					),
				),
			),
		));
	}
}
