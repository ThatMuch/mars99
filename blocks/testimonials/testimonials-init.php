<?php

/**
 * Testimonials Block Initialization
 * @package Mars
 */

if (!defined('ABSPATH')) {
	exit;
}

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_testimonials_block');
}

function register_testimonials_block()
{
	acf_register_block_type(array(
		'name'            => 'testimonials',
		'title'           => __('Témoignages', 'mars'),
		'description'     => __('Affiche les témoignages avec un style similaire aux avis Google.', 'mars'),
		'render_template' => 'blocks/testimonials/block-testimonials.php',
		'category'        => 'mars-blocks',
		'icon'            => 'format-quote',
		'keywords'        => array('testimonial', 'avis', 'client', 'slider', 'google'),
		'supports'        => array(
			'align' => true,
			'mode'  => true,
			'jsx'   => true,
		),
		'example'         => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array('is_preview' => true),
			),
		),
		'enqueue_assets'  => function () {
			wp_enqueue_style('mars-testimonials', get_template_directory_uri() . '/blocks/testimonials/testimonials.css', array(), time());
			wp_enqueue_script('mars-testimonials', get_template_directory_uri() . '/blocks/testimonials/testimonials.js', array('jquery', 'mars-carousel'), time(), true);
		},
	));

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key'    => 'group_testimonials_block',
			'title'  => 'Testimonials Block',
			'fields' => array(

				// ── Source ──────────────────────────────────────────────────────
				array(
					'key'           => 'field_testimonials_source_type',
					'label'         => 'Source des témoignages',
					'name'          => 'source_type',
					'type'          => 'select',
					'choices'       => array(
						'custom_posts'  => 'Témoignages personnalisés (CPT)',
						'google_reviews' => 'Avis Google',
					),
					'default_value' => 'custom_posts',
					'instructions'  => 'Choisissez la source des témoignages à afficher.',
				),

				// ── Titre ───────────────────────────────────────────────────────
				array(
					'key'           => 'field_testimonials_title',
					'label'         => 'Titre',
					'name'          => 'title',
					'type'          => 'text',
					'default_value' => 'Ce que nos clients disent de nous',
				),
				array(
					'key'           => 'field_testimonials_subtitle',
					'label'         => 'Sous-titre',
					'name'          => 'subtitle',
					'type'          => 'text',
					'default_value' => 'Témoignages',
				),
				array(
					'key'           => 'field_testimonials_show_title',
					'label'         => 'Afficher le titre',
					'name'          => 'show_title',
					'type'          => 'true_false',
					'default_value' => 1,
					'ui'            => 1,
				),

				// ── Champs CPT (conditionnels) ───────────────────────────────
				array(
					'key'               => 'field_testimonials_count',
					'label'             => 'Nombre de témoignages',
					'name'              => 'count',
					'type'              => 'number',
					'default_value'     => -1,
					'instructions'      => 'Entrez -1 pour afficher tous les témoignages.',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_testimonials_source_type',
								'operator' => '==',
								'value'    => 'custom_posts',
							),
						),
					),
				),
				array(
					'key'               => 'field_testimonials_selection_type',
					'label'             => 'Type de sélection',
					'name'              => 'selection_type',
					'type'              => 'select',
					'choices'           => array(
						'all'      => 'Tous les témoignages',
						'category' => 'Par catégorie',
					),
					'default_value'     => 'all',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_testimonials_source_type',
								'operator' => '==',
								'value'    => 'custom_posts',
							),
						),
					),
				),
				array(
					'key'               => 'field_testimonials_specific_category',
					'label'             => 'Catégorie spécifique',
					'name'              => 'specific_category',
					'type'              => 'taxonomy',
					'taxonomy'          => 'temoignage_category',
					'field_type'        => 'select',
					'allow_null'        => 0,
					'add_term'          => 0,
					'save_terms'        => 0,
					'load_terms'        => 0,
					'return_format'     => 'id',
					'multiple'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_testimonials_source_type',
								'operator' => '==',
								'value'    => 'custom_posts',
							),
							array(
								'field'    => 'field_testimonials_selection_type',
								'operator' => '==',
								'value'    => 'category',
							),
						),
					),
				),

				// ── Champs Google Reviews (conditionnels) ────────────────────
				array(
					'key'               => 'field_testimonials_google_place_id',
					'label'             => 'ID du lieu Google (Place ID)',
					'name'              => 'google_place_id',
					'type'              => 'text',
					'instructions'      => 'Trouvez votre Place ID sur <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank">cette page Google</a>.',
					'required'          => 0,
					'placeholder'       => 'ChIJrTLr-GyuEmsRBfy61i59si0',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_testimonials_source_type',
								'operator' => '==',
								'value'    => 'google_reviews',
							),
						),
					),
				),
				array(
					'key'               => 'field_testimonials_google_api_key',
					'label'             => 'Clé API Google',
					'name'              => 'google_api_key',
					'type'              => 'text',
					'instructions'      => 'Clé API Google avec les API Places et Maps activées.',
					'required'          => 0,
					'placeholder'       => 'AIzaSyA-XXXXXXXXXXXXXXXXXXXXXXXXXX',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_testimonials_source_type',
								'operator' => '==',
								'value'    => 'google_reviews',
							),
						),
					),
				),
				array(
					'key'               => 'field_testimonials_google_min_rating',
					'label'             => 'Note minimum',
					'name'              => 'google_min_rating',
					'type'              => 'number',
					'instructions'      => 'N\'afficher que les avis avec au moins cette note (1–5).',
					'default_value'     => 4,
					'min'               => 1,
					'max'               => 5,
					'step'              => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_testimonials_source_type',
								'operator' => '==',
								'value'    => 'google_reviews',
							),
						),
					),
				),
				array(
					'key'               => 'field_testimonials_google_count',
					'label'             => 'Nombre d\'avis à afficher',
					'name'              => 'google_count',
					'type'              => 'number',
					'instructions'      => 'Laissez vide pour afficher tous les avis récupérés.<br><strong>Note :</strong> l\'API Google Places retourne 5 avis maximum.',
					'default_value'     => '',
					'placeholder'       => 'Tous (max 5 via API)',
					'min'               => 1,
					'max'               => 5,
					'step'              => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_testimonials_source_type',
								'operator' => '==',
								'value'    => 'google_reviews',
							),
						),
					),
				),
				array(
					'key'               => 'field_testimonials_google_cache_time',
					'label'             => 'Durée du cache (heures)',
					'name'              => 'google_cache_time',
					'type'              => 'number',
					'instructions'      => 'Durée de conservation des avis en cache avant actualisation.',
					'default_value'     => 24,
					'min'               => 1,
					'max'               => 168,
					'step'              => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_testimonials_source_type',
								'operator' => '==',
								'value'    => 'google_reviews',
							),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/testimonials',
					),
				),
			),
		));
	}
}
