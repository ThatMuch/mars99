<?php

/**
 * Cards Slider Block Initialization
 * @package Mars
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_cards_slider_block');
}

function register_cards_slider_block()
{
	acf_register_block_type(array(
		'name'              => 'cards-slider',
		'title'             => __('Cards Slider (Sans JS)', 'mars'),
		'description'       => __('Slider horizontal de cartes, fonctionnant uniquement en CSS.', 'mars'),
		'render_template'   => 'blocks/cards-slider/block-cards-slider.php',
		'category'          => 'mars-blocks',
		'icon'              => 'slides',
		'keywords'          => array('slider', 'cards', 'css', 'horizontal'),
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
		)
	));

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_cards_slider_block',
			'title' => 'Paramètres du bloc : Cards Slider',
			'fields' => array(
				array(
					'key' => 'field_cards_slider_repeater',
					'label' => 'Cartes',
					'name' => 'cards',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Ajouter une carte',
					'sub_fields' => array(
						array(
							'key' => 'field_cs_style',
							'label' => 'Style de la carte',
							'name' => 'style',
							'type' => 'select',
							'choices' => array(
								'primary' => 'Fond foncé (Primaire)',
								'light'   => 'Fond clair (Rose)',
							),
							'default_value' => 'light',
							'return_format' => 'value',
						),
						array(
							'key' => 'field_cs_title',
							'label' => 'Titre',
							'name' => 'titre',
							'type' => 'text',
							'required' => 1,
						),
						array(
							'key' => 'field_cs_description',
							'label' => 'Description',
							'name' => 'description',
							'type' => 'wysiwyg',
							'media_upload' => 0,
							'toolbar' => 'basic',
						)
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/cards-slider',
					),
				),
			),
		));
	}
}
