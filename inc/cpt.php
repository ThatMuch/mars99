<?php

/**
 * Custom Post Types Registration
 *
 * @package Mars
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Register Custom Post Types
 */
function mars_register_cpt()
{

	/**
	 * Post Type: Témoignages.
	 */
	$labels = array(
		'name'          => __('Témoignages', 'mars'),
		'singular_name' => __('Témoignage', 'mars'),
	);

	$args = array(
		'label'                 => __('Témoignages', 'mars'),
		'labels'                => $labels,
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'has_archive'           => false,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'delete_with_user'      => false,
		'exclude_from_search'   => false,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'rewrite'               => array('slug' => 'temoignages', 'with_front' => true),
		'query_var'             => true,
		'menu_icon'             => 'dashicons-format-quote',
		'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
	);

	register_post_type('temoignages', $args);

	/**
	 * Taxonomy: Catégories de témoignages.
	 */
	$labels = array(
		'name'              => __('Catégories de témoignages', 'mars'),
		'singular_name'     => __('Catégorie de témoignage', 'mars'),
	);

	$args = array(
		'label'                 => __('Catégories de témoignages', 'mars'),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => array('slug' => 'categorie-temoignage', 'with_front' => true,),
		'show_admin_column'     => true,
		'show_in_rest'          => true,
		'rest_base'             => 'temoignage_category',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'show_in_quick_edit'    => true,
	);
	register_taxonomy('temoignage_category', array('temoignages'), $args);

	/**
	 * Register ACF fields for Témoignages
	 */
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_temoignages',
			'title' => 'Informations Témoignage',
			'fields' => array(
				array(
					'key' => 'field_temoignage_profession',
					'label' => 'Profession / Poste',
					'name' => 'profession',
					'type' => 'text',
					'required' => 0,
				),
				array(
					'key' => 'field_temoignage_note',
					'label' => 'Note (sur 5)',
					'name' => 'note',
					'type' => 'number',
					'required' => 0,
					'default_value' => 5,
					'min' => 1,
					'max' => 5,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'temoignages',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));
	}
}

add_action('init', 'mars_register_cpt');
