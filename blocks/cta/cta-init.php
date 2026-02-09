<?php

/**
 * CTA Block Initialization
 * @package AbyssEnergy
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc CTA
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_cta_block');
}

function register_cta_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'cta',
		'title'             => __('Call to Action', 'abyssenergy'),
		'description'       => __('Affiche un bloc CTA avec titre, description, lien, et icone optionnel.', 'abyssenergy'),
		'render_template'   => 'blocks/cta/block-cta.php',
		'category'          => 'mars-blocks',
		'icon'              => 'megaphone',
		'keywords'          => array('cta', 'call to action', 'button', 'link'),
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
		'enqueue_style'     => get_template_directory_uri() . '/blocks/cta/cta.css',
		//		'enqueue_script'    => get_template_directory_uri() . '/blocks/cta/cta.js', // No JS needed initially
	));

	if (function_exists('acf_add_local_field_group')) {
		// Créer les champs ACF pour le bloc
		acf_add_local_field_group(array(
			'key' => 'group_cta_block',
			'title' => 'CTA Block',
			'fields' => array(
				array(
					'key' => 'field_cta_theme',
					'label' => 'Thème',
					'name' => 'theme',
					'type' => 'select',
					'choices' => array(
						'primary' => 'Primaire',
						'secondary' => 'Secondaire',
					),
					'default_value' => 'primary',
					'return_format' => 'value',
				),
				array(
					'key' => 'field_cta_icon',
					'label' => 'Icône',
					'name' => 'icon',
					'type' => 'image',
					'instructions' => 'Facultatif',
					'required' => 0,
					'return_format' => 'array',
					'preview_size' => 'thumbnail',
					'library' => 'all',
				),
				array(
					'key' => 'field_cta_title',
					'label' => 'Titre',
					'name' => 'title',
					'type' => 'text',
					'required' => 1,
					'default_value' => 'Titre du CTA',
				),
				array(
					'key' => 'field_cta_description',
					'label' => 'Description',
					'name' => 'description',
					'type' => 'textarea', // Changed to textarea for simplicity if wysiwyg is overkill, but sticking to prompt "Description" usually implies some text. Let's stick to textarea as it's cleaner for simple descriptions in CTAs.
					'required' => 1,
					'rows' => 3,
				),
				array(
					'key' => 'field_cta_link',
					'label' => 'Lien',
					'name' => 'link',
					'type' => 'link',
					'required' => 1,
					'return_format' => 'array',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/cta',
					),
				),
			),
		));
	}
}
