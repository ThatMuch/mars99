<?php

/**
 * Initialisation du bloc Timeline
 * @package AbyssEnergy
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Timeline
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_timeline_block');
}

function register_timeline_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'timeline',
		'title'             => __('Timeline', 'abyssenergy'),
		'description'       => __('Affiche une timeline verticale avec des événements.', 'abyssenergy'),
		'render_template'   => 'blocks/timeline/block-timeline.php',
		'category'          => 'mars-blocks',
		'icon'              => 'calendar-alt',
		'keywords'          => array('timeline', 'événements', 'chronologie'),
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
		'enqueue_style'     => get_template_directory_uri() . '/blocks/timeline/timeline.css',
		'enqueue_script'    => get_template_directory_uri() . '/blocks/timeline/timeline.js',
	));

	if (function_exists('acf_add_local_field_group')) {
		// Créer les champs ACF pour le bloc
		acf_add_local_field_group(array(
			'key' => 'group_timeline_block',
			'title' => 'Timeline Block',
			'fields' => array(
				array(
					'key' => 'field_timeline_title',
					'label' => 'Titre de la timeline',
					'name' => 'timeline_title',
					'type' => 'text',
					'instructions' => 'Entrez le titre de la timeline.',
					'required' => 0,
					'default_value' => '',
					'placeholder' => 'Notre parcours',
				),
				array(
					'key' => 'field_timeline_subtitle',
					'label' => 'Sous-titre de la timeline',
					'name' => 'timeline_subtitle',
					'type' => 'text',
					'instructions' => 'Entrez le sous-titre de la timeline.',
					'required' => 0,
					'default_value' => '',
					'placeholder' => 'Notre parcours',
				),
				array(
					'key' => 'field_timeline_description',
					'label' => 'Description de la timeline',
					'name' => 'timeline_description',
					'type' => 'wysiwyg',
					'instructions' => 'Entrez la description de la timeline.',
					'required' => 0,
					'default_value' => '',
					'placeholder' => 'Un aperçu de notre parcours',
				),
				array(
					'key' => 'field_timeline_files',
					'label' => 'Fichiers associés',
					'name' => 'timeline_files',
					'type' => 'gallery',
					'instructions' => 'Ajouter des fichiers associés à la timeline (PDF, images, etc.).',
					'required' => 0,
					'return_format' => 'array',
					'library' => 'all',
					'min' => '',
					'max' => '',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => 'pdf,jpg,jpeg,png,gif',
					'insert' => 'append',
					'preview_size' => 'medium',
				),
				array(
					'key' => 'field_timeline_steps',
					'label' => 'Étapes',
					'name' => 'steps',
					'type' => 'repeater',
					'instructions' => 'Ajouter des étapes à la timeline.',
					'sub_fields' => array(
						array(
							'key' => 'field_step_title',
							'label' => 'Titre',
							'name' => 'title',
							'type' => 'text',
							'required' => 1,
						),
						array(
							'key' => 'field_step_excerpt',
							'label' => 'Extrait',
							'name' => 'excerpt',
							'type' => 'textarea',
							'required' => 0,
						),
						array(
							'key' => 'field_step_description',
							'label' => 'Description',
							'name' => 'description',
							'type' => 'wysiwyg',
							'required' => 0,
						),
						array(
							'key' => 'field_step_image',
							'label' => 'Image',
							'name' => 'image',
							'type' => 'image',
							'required' => 0,
							'return_format' => 'array',
							'preview_size' => 'medium',
							'library' => 'all',
						),
						array(
							'key' => 'field_step_cta',
							'label' => 'Appel à l\'action (CTA)',
							'name' => 'cta',
							'type' => 'link',
							'required' => 0,
							'return_format' => 'array'
						)
					),
					'min' => 1,
					'layout' => 'block',
					'button_label' => 'Ajouter une étape',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/timeline',
					),
				),
			),
		));
	}
}
