<?php

/**
 * Testimonials Slider Block Initialization
 * @package Mars
 */

if (!defined('ABSPATH')) {
	exit;
}

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_testimonials_slider_block');
}

function register_testimonials_slider_block()
{
	acf_register_block_type(array(
		'name'            => 'testimonials-slider',
		'title'           => __('Testimonials Slider', 'mars'),
		'description'     => __('Un slider pour afficher les témoignages clients.', 'mars'),
		'render_template' => 'blocks/testimonials-slider/block-testimonials-slider.php',
		'category'        => 'mars-blocks',
		'icon'            => 'testimonial',
		'keywords'        => array('testimonials', 'slider', 'témoignages', 'clients', 'google'),
		'supports'        => array(
			'align' => true,
			'mode'  => false,
			'jsx'   => true,
		),
		'example'         => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array('is_preview' => true),
			),
		),
		'enqueue_assets'  => function () {
			wp_enqueue_script('testimonials-slider-js', get_template_directory_uri() . '/blocks/testimonials-slider/testimonials-slider.js', array(), time(), true);
			wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');
			wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
		},
	));

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key'    => 'group_testimonials_slider',
			'title'  => 'Testimonials Slider Settings',
			'fields' => array(

				// ── Source ──────────────────────────────────────────────────────
				array(
					'key'           => 'field_ts_source_type',
					'label'         => 'Source des témoignages',
					'name'          => 'source_type',
					'type'          => 'select',
					'choices'       => array(
						'custom_posts'   => 'Témoignages personnalisés (CPT)',
						'google_reviews' => 'Avis Google',
					),
					'default_value' => 'custom_posts',
					'instructions'  => 'Choisissez la source des témoignages à afficher.',
				),

				// ── Titre ───────────────────────────────────────────────────────
				array(
					'key'           => 'field_ts_title',
					'label'         => 'Titre de la section',
					'name'          => 'title',
					'type'          => 'text',
					'default_value' => 'Témoignages de nos clients',
				),
				array(
					'key'           => 'field_ts_subtitle',
					'label'         => 'Sous-titre de la section',
					'name'          => 'subtitle',
					'type'          => 'text',
					'default_value' => 'Ce que disent nos clients',
				),

				// ── Champs CPT (conditionnels) ───────────────────────────────
				array(
					'key'               => 'field_ts_selection_type',
					'label'             => 'Type de sélection',
					'name'              => 'selection_type',
					'type'              => 'radio',
					'choices'           => array(
						'all'               => 'Tous les témoignages',
						'specific_category' => 'Par catégorie spécifique',
					),
					'default_value'     => 'all',
					'layout'            => 'horizontal',
					'return_format'     => 'value',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_ts_source_type',
								'operator' => '==',
								'value'    => 'custom_posts',
							),
						),
					),
				),
				array(
					'key'               => 'field_ts_specific_category',
					'label'             => 'Sélectionner une catégorie',
					'name'              => 'specific_category',
					'type'              => 'taxonomy',
					'taxonomy'          => 'testimonial-category',
					'field_type'        => 'select',
					'allow_null'        => 0,
					'add_term'          => 0,
					'save_terms'        => 0,
					'load_terms'        => 0,
					'multiple'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_ts_source_type',
								'operator' => '==',
								'value'    => 'custom_posts',
							),
							array(
								'field'    => 'field_ts_selection_type',
								'operator' => '==',
								'value'    => 'specific_category',
							),
						),
					),
				),
				array(
					'key'               => 'field_ts_image',
					'label'             => 'Image de la section témoignage',
					'name'              => 'image',
					'type'              => 'image',
					'required'          => 0,
					'return_format'     => 'id',
					'preview_size'      => 'medium',
					'library'           => 'all',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_ts_source_type',
								'operator' => '==',
								'value'    => 'custom_posts',
							),
						),
					),
				),
				array(
					'key'               => 'field_ts_header_alignment',
					'label'             => 'Alignement du header',
					'name'              => 'header_alignment',
					'type'              => 'radio',
					'choices'           => array(
						'right' => 'Droite',
						'left'  => 'Gauche',
					),
					'default_value'     => 'right',
					'layout'            => 'horizontal',
					'return_format'     => 'value',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_ts_source_type',
								'operator' => '==',
								'value'    => 'custom_posts',
							),
						),
					),
				),

				// ── Champs Google Reviews (conditionnels) ────────────────────
				array(
					'key'               => 'field_ts_google_place_id',
					'label'             => 'ID du lieu Google (Place ID)',
					'name'              => 'google_place_id',
					'type'              => 'text',
					'instructions'      => 'Trouvez votre Place ID sur <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank">cette page Google</a>.',
					'required'          => 0,
					'placeholder'       => 'ChIJrTLr-GyuEmsRBfy61i59si0',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_ts_source_type',
								'operator' => '==',
								'value'    => 'google_reviews',
							),
						),
					),
				),
				array(
					'key'               => 'field_ts_google_api_key',
					'label'             => 'Clé API Google',
					'name'              => 'google_api_key',
					'type'              => 'text',
					'instructions'      => 'Clé API Google avec les API Places et Maps activées.',
					'required'          => 0,
					'placeholder'       => 'AIzaSyA-XXXXXXXXXXXXXXXXXXXXXXXXXX',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_ts_source_type',
								'operator' => '==',
								'value'    => 'google_reviews',
							),
						),
					),
				),
				array(
					'key'               => 'field_ts_google_min_rating',
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
								'field'    => 'field_ts_source_type',
								'operator' => '==',
								'value'    => 'google_reviews',
							),
						),
					),
				),
				array(
					'key'               => 'field_ts_google_count',
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
								'field'    => 'field_ts_source_type',
								'operator' => '==',
								'value'    => 'google_reviews',
							),
						),
					),
				),
				array(
					'key'               => 'field_ts_google_cache_time',
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
								'field'    => 'field_ts_source_type',
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
						'value'    => 'acf/testimonials-slider',
					),
				),
			),
			'active'       => true,
			'show_in_rest' => 0,
		));
	}
}
