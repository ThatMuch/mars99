<?php

/**
 * FAQ Block Initialization
 * @package Mars
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc FAQ
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_faq_block');
}

function register_faq_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'faq',
		'title'             => __('FAQ', 'mars'),
		'description'       => __('Affiche une liste de questions/réponses avec accordéon.', 'mars'),
		'render_template'   => 'blocks/faq/block-faq.php',
		'category'          => 'mars-blocks',
		'icon'              => 'editor-help',
		'keywords'          => array('faq', 'questions', 'accordion'),
		'supports'          => array(
			'align' => true,
			'mode' => true,
			'jsx' => false // Repater based
		),
		'enqueue_assets' => function () {
			wp_enqueue_script(
				'block-faq-js',
				get_template_directory_uri() . '/blocks/faq/faq.js',
				array('jquery'),
				filemtime(get_template_directory() . '/blocks/faq/faq.js'),
				true
			);
		},
		'example'           => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview' => true,
					'faq_items_0_question' => 'Exemple de question ?',
					'faq_items_0_answer'   => 'Ceci est une réponse exemple pour prévisualiser le bloc.',
				)
			)
		),
	));

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_block_faq',
			'title' => 'Bloc FAQ',
			'fields' => array(
				array(
					'key' => 'field_faq_items',
					'label' => 'Questions / Réponses',
					'name' => 'faq_items',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Ajouter une question',
					'sub_fields' => array(
						array(
							'key' => 'field_faq_question',
							'label' => 'Question',
							'name' => 'question',
							'type' => 'text',
							'required' => 1,
						),
						array(
							'key' => 'field_faq_answer',
							'label' => 'Réponse',
							'name' => 'answer',
							'type' => 'wysiwyg',
							'required' => 1,
							'media_upload' => 0,
							'toolbar' => 'basic',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/faq',
					),
				),
			),
		));
	}
}
