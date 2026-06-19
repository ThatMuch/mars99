<?php

/**
 * Google Reviews Block Initialization
 *
 * @package Mars
 */

if (!defined('ABSPATH')) {
	exit;
}

// Shared Google API function is loaded via inc/google-reviews-api.php (see functions.php).

/**
 * Enregistrement du bloc Google Reviews
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_google_reviews_block');
}

function register_google_reviews_block()
{
	acf_register_block_type(array(
		'name'            => 'google-reviews',
		'title'           => __('Avis Google', 'mars'),
		'description'     => __('Affiche les avis Google de votre entreprise.', 'mars'),
		'render_template' => 'blocks/google-reviews/block-google-reviews.php',
		'category'        => 'mars-blocks',
		'icon'            => 'star-filled',
		'keywords'        => array('google', 'reviews', 'avis', 'témoignages', 'rating'),
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
		'enqueue_script'  => get_template_directory_uri() . '/blocks/google-reviews/google-reviews.js',
	));

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key'    => 'group_google_reviews',
			'title'  => 'Paramètres des avis Google',
			'fields' => array(
				array(
					'key'           => 'field_google_reviews_title',
					'label'         => 'Titre de la section',
					'name'          => 'title',
					'type'          => 'text',
					'default_value' => 'Ce que nos clients disent de nous',
				),
				array(
					'key'           => 'field_google_reviews_subtitle',
					'label'         => 'Sous-titre',
					'name'          => 'subtitle',
					'type'          => 'text',
					'default_value' => 'Avis Google',
				),
				array(
					'key'           => 'field_google_reviews_show_title',
					'label'         => 'Afficher le titre',
					'name'          => 'show_title',
					'type'          => 'true_false',
					'default_value' => 1,
					'ui'            => 1,
				),
				array(
					'key'          => 'field_google_reviews_place_id',
					'label'        => 'ID du lieu Google',
					'name'         => 'place_id',
					'type'         => 'text',
					'instructions' => 'Entrez l\'ID de votre entreprise sur Google (Place ID). Vous pouvez le trouver sur <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank">cette page</a>.',
					'required'     => 1,
					'placeholder'  => 'ChIJrTLr-GyuEmsRBfy61i59si0',
				),
				array(
					'key'          => 'field_google_reviews_api_key',
					'label'        => 'Clé API Google',
					'name'         => 'api_key',
					'type'         => 'text',
					'instructions' => 'Entrez votre clé API Google avec les API Places et Maps activées.',
					'required'     => 1,
					'placeholder'  => 'AIzaSyA-XXXXXXXXXXXXXXXXXXXXXXXXXX',
				),
				array(
					'key'           => 'field_google_reviews_count',
					'label'         => 'Nombre d\'avis à afficher',
					'name'          => 'reviews_count',
					'type'          => 'number',
					'instructions'  => 'Nombre d\'avis à afficher. Laissez vide pour tous les avis disponibles.<br><strong>Note :</strong> L\'API Google Places retourne 5 avis maximum.',
					'default_value' => '',
					'placeholder'   => 'Tous les avis (max 5)',
					'min'           => 1,
					'max'           => 5,
					'step'          => 1,
				),
				array(
					'key'           => 'field_google_reviews_min_rating',
					'label'         => 'Note minimum',
					'name'          => 'min_rating',
					'type'          => 'number',
					'instructions'  => 'Filtrer les avis avec une note minimum (de 1 à 5).',
					'default_value' => 1,
					'min'           => 1,
					'max'           => 5,
					'step'          => 1,
				),
				array(
					'key'           => 'field_google_reviews_cache_time',
					'label'         => 'Durée du cache (heures)',
					'name'          => 'cache_time',
					'type'          => 'number',
					'instructions'  => 'Combien d\'heures conserver les avis en cache avant de les actualiser.',
					'default_value' => 24,
					'min'           => 1,
					'max'           => 168,
					'step'          => 1,
				),
				array(
					'key'          => 'field_google_reviews_image',
					'label'        => 'Image de la section',
					'name'         => 'image',
					'type'         => 'image',
					'required'     => 0,
					'return_format' => 'id',
					'preview_size' => 'medium',
					'library'      => 'all',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/google-reviews',
					),
				),
			),
			'active'      => true,
			'show_in_rest' => 0,
		));
	}
}
