<?php

/**
 * Ouvrage Grid Block Initialization
 * @package Mars
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_ouvrage_grid_block');
}

function register_ouvrage_grid_block()
{
	acf_register_block_type(array(
		'name'              => 'ouvrage-grid',
		'title'             => __('Grille d\'Ouvrages', 'mars'),
		'description'       => __('Affiche une grille des ouvrages.', 'mars'),
		'render_template'   => 'blocks/ouvrage-grid/block-ouvrage-grid.php',
		'category'          => 'mars-blocks',
		'icon'              => 'book',
		'keywords'          => array('ouvrage', 'grid', 'livre', 'book'),
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
			'key' => 'group_ouvrage_grid_block',
			'title' => 'Paramètres du bloc : Grille d\'Ouvrages',
			'fields' => array(
				array(
					'key' => 'field_ouvrage_grid_posts_per_page',
					'label' => 'Nombre d\'ouvrages à afficher',
					'name' => 'posts_per_page',
					'type' => 'number',
					'default_value' => -1,
					'min' => -1,
					'instructions' => 'Mettez -1 pour afficher tous les ouvrages.',
				)
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/ouvrage-grid',
					),
				),
			),
		));
	}
}
