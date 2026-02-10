<?php

/**
 * Testimonials Block Initialization
 * @package Mars
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Témoignages
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_testimonials_block');
}

function register_testimonials_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'testimonials',
		'title'             => __('Témoignages', 'mars'),
		'description'       => __('Affiche les témoignages avec un style similaire aux avis Google.', 'mars'),
		'render_template'   => 'blocks/testimonials/block-testimonials.php',
		'category'          => 'mars-blocks',
		'icon'              => 'format-quote',
		'keywords'          => array('testimonial', 'avis', 'client', 'slider'),
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
		'enqueue_assets'    => function () {
			// Enqueue Swiper only if not already enqueued
			if (!wp_script_is('swiper', 'enqueued')) {
				wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');
				wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
			}

			// Enqueue Block Assets
			wp_enqueue_script('mars-testimonials', get_template_directory_uri() . '/blocks/testimonials/testimonials.js', array('jquery', 'swiper'), time(), true);
		},
	));

	if (function_exists('acf_add_local_field_group')) {
		// Créer les champs ACF pour le bloc
		acf_add_local_field_group(array(
			'key' => 'group_testimonials_block',
			'title' => 'Testimonials Block',
			'fields' => array(
				array(
					'key' => 'field_testimonials_title',
					'label' => 'Titre',
					'name' => 'title',
					'type' => 'text',
					'default_value' => 'Ce que nos clients disent de nous',
				),
				array(
					'key' => 'field_testimonials_subtitle',
					'label' => 'Sous-titre',
					'name' => 'subtitle',
					'type' => 'text',
					'default_value' => 'Témoignages',
				),
				array(
					'key' => 'field_testimonials_show_title',
					'label' => 'Afficher le titre',
					'name' => 'show_title',
					'type' => 'true_false',
					'default_value' => 1,
					'ui' => 1,
				),
				array(
					'key' => 'field_testimonials_count',
					'label' => 'Nombre de témoignages',
					'name' => 'count',
					'type' => 'number',
					'default_value' => -1,
					'instructions' => 'Entrez -1 pour afficher tous les témoignages.',
				),
				array(
					'key' => 'field_testimonials_selection_type',
					'label' => 'Type de sélection',
					'name' => 'selection_type',
					'type' => 'select',
					'choices' => array(
						'all' => 'Tous les témoignages',
						'category' => 'Par catégorie',
					),
					'default_value' => 'all',
				),
				array(
					'key' => 'field_testimonials_specific_category',
					'label' => 'Catégorie spécifique',
					'name' => 'specific_category',
					'type' => 'taxonomy',
					'taxonomy' => 'temoignage_category',
					'field_type' => 'select',
					'allow_null' => 0,
					'add_term' => 0,
					'save_terms' => 0,
					'load_terms' => 0,
					'return_format' => 'id',
					'multiple' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_testimonials_selection_type',
								'operator' => '==',
								'value' => 'category',
							),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/testimonials',
					),
				),
			),
		));
	}
}
