<?php

/**
 * Playlists List Block Initialization
 * @package Mars
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_playlists_list_block');
}

function register_playlists_list_block()
{
	acf_register_block_type(array(
		'name'              => 'playlists-list',
		'title'             => __('Liste des Playlists', 'mars'),
		'description'       => __('Affiche une grille des playlists disponibles.', 'mars'),
		'render_template'   => 'blocks/playlists-list/block-playlists-list.php',
		'category'          => 'mars-blocks',
		'icon'              => 'grid-view',
		'keywords'          => array('playlist', 'liste', 'grille', 'audio'),
		'supports'          => array(
			'align' => true,
			'mode' => true,
			'jsx' => true
		)
	));

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_playlists_list_block',
			'title' => 'Paramètres du bloc : Liste des Playlists',
			'fields' => array(
				array(
					'key' => 'field_playlists_list_count',
					'label' => 'Nombre de playlists à afficher',
					'name' => 'count',
					'type' => 'number',
					'default_value' => -1,
					'instructions' => 'Mettre -1 pour toutes les afficher.',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/playlists-list',
					),
				),
			),
		));
	}
}
