<?php

/**
 * Google Reviews Block Initialization
 *
 * @package AbyssEnergy
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Google Reviews
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_google_reviews_block');
}

function register_google_reviews_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'google-reviews',
		'title'             => __('Avis Google', 'abyssenergy'),
		'description'       => __('Affiche les avis Google de votre entreprise.', 'abyssenergy'),
		'render_template'   => 'blocks/google-reviews/block-google-reviews.php',
		'category'          => 'mars-blocks',
		'icon'              => 'star-filled',
		'keywords'          => array('google', 'reviews', 'avis', 'témoignages', 'rating'),
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
		'enqueue_script'    => get_template_directory_uri() . '/blocks/google-reviews/google-reviews.js',
	));

	// Créer les champs ACF pour le bloc
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_google_reviews',
			'title' => 'Paramètres des avis Google',
			'fields' => array(
				array(
					'key' => 'field_google_reviews_title',
					'label' => 'Titre de la section',
					'name' => 'title',
					'type' => 'text',
					'instructions' => 'Entrez le titre de la section des avis Google.',
					'default_value' => 'Ce que nos clients disent de nous',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_google_reviews_subtitle',
					'label' => 'Sous-titre',
					'name' => 'subtitle',
					'type' => 'text',
					'instructions' => 'Entrez un sous-titre optionnel.',
					'default_value' => 'Avis Google',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_google_reviews_show_title',
					'label' => 'Afficher le titre',
					'name' => 'show_title',
					'type' => 'true_false',
					'instructions' => 'Cochez pour afficher le titre.',
					'default_value' => 1,
					'ui' => 1,
				),
				array(
					'key' => 'field_google_reviews_place_id',
					'label' => 'ID du lieu Google',
					'name' => 'place_id',
					'type' => 'text',
					'instructions' => 'Entrez l\'ID de votre entreprise sur Google (Place ID). Vous pouvez le trouver sur <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank">cette page</a>.',
					'required' => 1,
					'placeholder' => 'ChIJrTLr-GyuEmsRBfy61i59si0',
					'prepend' => '',
					'append' => '',
				),
				array(
					'key' => 'field_google_reviews_api_key',
					'label' => 'Clé API Google',
					'name' => 'api_key',
					'type' => 'text',
					'instructions' => 'Entrez votre clé API Google avec les API Places et Maps activées.',
					'required' => 1,
					'placeholder' => 'AIzaSyA-XXXXXXXXXXXXXXXXXXXXXXXXXX',
					'prepend' => '',
					'append' => '',
				),
				array(
					'key' => 'field_google_reviews_count',
					'label' => 'Nombre d\'avis à afficher',
					'name' => 'reviews_count',
					'type' => 'number',
					'instructions' => 'Entrez le nombre d\'avis à afficher. Laissez vide ou 0 pour afficher tous les avis disponibles.<br><strong>Note:</strong> L\'API Google Places ne retourne que 5 avis maximum, même si votre entreprise en a plus.',
					'default_value' => '',
					'placeholder' => 'Tous les avis (max 5)',
					'min' => 1,
					'max' => 5,
					'step' => 1,
				),
				array(
					'key' => 'field_google_reviews_min_rating',
					'label' => 'Note minimum',
					'name' => 'min_rating',
					'type' => 'number',
					'instructions' => 'Filtrer les avis avec une note minimum (de 1 à 5).',
					'default_value' => 1,
					'min' => 1,
					'max' => 5,
					'step' => 1,
				),
				array(
					'key' => 'field_google_reviews_cache_time',
					'label' => 'Durée du cache (heures)',
					'name' => 'cache_time',
					'type' => 'number',
					'instructions' => 'Combien d\'heures conserver les avis en cache avant de les actualiser.',
					'default_value' => 24,
					'min' => 1,
					'max' => 168,
					'step' => 1,
				),
				array(
					'key' => 'field_google_reviews_image',
					'label' => 'Image de la section',
					'name' => 'image',
					'type' => 'image',
					'instructions' => 'Téléchargez une image pour la section des avis Google (optionnel).',
					'required' => 0,
					'return_format' => 'id',
					'preview_size' => 'medium',
					'library' => 'all',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/google-reviews',
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
			'show_in_rest' => 0,
		));
	}
}

/**
 * Fonction pour récupérer et mettre en cache les avis Google
 */
function abyssenergy_get_google_reviews($place_id, $api_key, $count = null, $min_rating = 1, $cache_time = 24)
{
	// Si count est vide, null ou 0, récupérer tous les avis
	$limit_reviews = (!empty($count) && is_numeric($count) && $count > 0);
	$max_reviews = $limit_reviews ? intval($count) : 50; // Google API limite à ~5 avis par défaut, mais on peut demander plus

	// Clé de cache unique pour ces paramètres
	$cache_key = 'abyssenergy_google_reviews_' . md5($place_id . $api_key . $max_reviews . $min_rating);

	// Vérifier si les données sont en cache
	$cached_reviews = get_transient($cache_key);

	if (false !== $cached_reviews) {
		// Si on a des données en cache et qu'on veut limiter, appliquer la limite
		if ($limit_reviews && isset($cached_reviews['all_reviews'])) {
			$cached_reviews['reviews'] = array_slice($cached_reviews['all_reviews'], 0, $count);
		}
		return $cached_reviews;
	}

	// Si pas en cache, faire la requête API
	// IMPORTANT: L'API Google Places ne retourne que 5 avis maximum
	// même si votre entreprise en a plus. C'est une limitation de Google.
	$url = 'https://maps.googleapis.com/maps/api/place/details/json';
	$args = array(
		'place_id' => $place_id,
		'fields' => 'name,rating,reviews,url,user_ratings_total',
		'reviews_sort' => 'newest', // Trier par les plus récents
		'key' => $api_key
	);

	$request_url = add_query_arg($args, $url);
	$response = wp_remote_get($request_url);

	if (is_wp_error($response)) {
		return array(
			'error' => true,
			'message' => $response->get_error_message()
		);
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);

	// Vérifier si la réponse est valide
	if ($data['status'] !== 'OK') {
		return array(
			'error' => true,
			'message' => isset($data['error_message']) ? $data['error_message'] : 'Erreur inconnue'
		);
	}

	// Debug temporaire pour voir les champs disponibles
	if (is_admin()) {
		error_log('Google API Response fields: ' . print_r(array_keys($data['result']), true));
		error_log('user_ratings_total raw value: ' . var_export($data['result']['user_ratings_total'] ?? 'NOT_PRESENT', true));
	}

	// Préparer les données à retourner
	$place_data = array(
		'error' => false,
		'name' => $data['result']['name'],
		'rating' => isset($data['result']['rating']) ? $data['result']['rating'] : 0,
		'url' => isset($data['result']['url']) ? $data['result']['url'] : '',
		'user_ratings_total' => isset($data['result']['user_ratings_total']) ? intval($data['result']['user_ratings_total']) : null,
		'all_reviews' => array(),
		'reviews' => array()
	);

	// Collecter tous les avis qui respectent la note minimum
	if (isset($data['result']['reviews'])) {
		foreach ($data['result']['reviews'] as $review) {
			if ($review['rating'] >= $min_rating) {
				$review_data = array(
					'author' => $review['author_name'],
					'avatar' => isset($review['profile_photo_url']) ? $review['profile_photo_url'] : '',
					'rating' => $review['rating'],
					'text' => $review['text'],
					'time' => $review['time'],
					'relative_time' => $review['relative_time_description']
				);
				$place_data['all_reviews'][] = $review_data;
			}
		}
	}


	// Appliquer la limite pour l'affichage
	if ($limit_reviews) {
		$place_data['reviews'] = array_slice($place_data['all_reviews'], 0, $count);
	} else {
		$place_data['reviews'] = $place_data['all_reviews'];
	}

	// Si user_ratings_total n'est pas disponible, utiliser le nombre d'avis récupérés comme fallback
	if ($place_data['user_ratings_total'] === null || $place_data['user_ratings_total'] === 0) {
		$place_data['user_ratings_total'] = count($place_data['all_reviews']);
		// Si on n'a pas non plus d'avis récupérés, essayer un fallback basé sur les données existantes
		if ($place_data['user_ratings_total'] === 0 && !empty($place_data['rating'])) {
			// Estimation basée sur le fait qu'il faut au moins quelques avis pour avoir une note
			$place_data['user_ratings_total'] = max(5, count($place_data['all_reviews']));
		}
		if (is_admin()) {
			error_log('Used fallback for user_ratings_total: ' . $place_data['user_ratings_total']);
		}
	} else {
		if (is_admin()) {
			error_log('Using API user_ratings_total: ' . $place_data['user_ratings_total']);
		}
	}

	// Mettre en cache pour la durée spécifiée (en heures)
	set_transient($cache_key, $place_data, $cache_time * HOUR_IN_SECONDS);

	return $place_data;
}

/**
 * Mise en place du cron pour actualiser les avis
 */
function abyssenergy_schedule_reviews_update()
{
	if (!wp_next_scheduled('abyssenergy_update_google_reviews')) {
		wp_schedule_event(time(), 'daily', 'abyssenergy_update_google_reviews');
	}
}
add_action('wp', 'abyssenergy_schedule_reviews_update');

/**
 * Fonction exécutée par le cron pour actualiser les avis
 */
function abyssenergy_update_all_google_reviews()
{
	// Récupérer toutes les pages contenant le bloc Google Reviews
	$args = array(
		'post_type' => 'any',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => '_wp_page_template',
				'value' => 'page-templates/template-flexible.php',
				'compare' => '='
			)
		)
	);

	$query = new WP_Query($args);

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();

			// Vérifier si la page contient notre bloc
			$content = get_the_content();
			if (has_block('acf/google-reviews', $content)) {
				// Supprimer le cache pour forcer une actualisation
				$blocks = parse_blocks($content);

				foreach ($blocks as $block) {
					if ($block['blockName'] === 'acf/google-reviews') {
						if (isset($block['attrs']['data'])) {
							$place_id = isset($block['attrs']['data']['place_id']) ? $block['attrs']['data']['place_id'] : '';
							$api_key = isset($block['attrs']['data']['api_key']) ? $block['attrs']['data']['api_key'] : '';
							$reviews_count = isset($block['attrs']['data']['reviews_count']) ? $block['attrs']['data']['reviews_count'] : '';
							$min_rating = isset($block['attrs']['data']['min_rating']) ? $block['attrs']['data']['min_rating'] : 1;

							if ($place_id && $api_key) {
								// Générer la clé de cache avec la logique de limitation
								$limit_reviews = (!empty($reviews_count) && is_numeric($reviews_count) && $reviews_count > 0);
								$max_reviews = $limit_reviews ? intval($reviews_count) : 50;
								$cache_key = 'abyssenergy_google_reviews_' . md5($place_id . $api_key . $max_reviews . $min_rating);
								delete_transient($cache_key);
							}
						}
					}
				}
			}
		}
	}

	wp_reset_postdata();
}
add_action('abyssenergy_update_google_reviews', 'abyssenergy_update_all_google_reviews');

// Ajouter un hook pour forcer l'actualisation manuelle depuis l'admin
function abyssenergy_force_reviews_update()
{
	if (!current_user_can('manage_options')) {
		wp_die('Accès refusé');
	}

	abyssenergy_update_all_google_reviews();

	wp_redirect(admin_url('edit.php?post_type=page'));
	exit;
}
add_action('admin_post_update_google_reviews', 'abyssenergy_force_reviews_update');

// Ajouter un bouton dans l'admin pour actualiser les avis
function abyssenergy_add_update_reviews_button($views)
{
	$url = admin_url('admin-post.php?action=update_google_reviews');
	$views['update_reviews'] = sprintf(
		'<a href="%s" class="button button-primary">%s</a>',
		esc_url($url),
		__('Actualiser les avis Google', 'abyssenergy')
	);
	return $views;
}
add_filter('views_edit-page', 'abyssenergy_add_update_reviews_button');
