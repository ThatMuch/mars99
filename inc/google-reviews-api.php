<?php

/**
 * Google Reviews API — shared utility
 *
 * Provides mars_get_google_reviews() via the Google Places API (max 5 reviews).
 *
 * Phase 2 placeholder: mars_get_all_google_reviews_business_profile() for
 * Google Business Profile API (OAuth2, unlimited reviews).
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Fetch and cache Google reviews via the Places Details API.
 *
 * NOTE: Google Places API returns a maximum of 5 reviews regardless of the
 * requested count. To retrieve all reviews, use the Google Business Profile
 * API (requires OAuth2 — see mars_get_all_google_reviews_business_profile stub below).
 *
 * @param string   $place_id   Google Place ID.
 * @param string   $api_key    Google API key (Places + Maps enabled).
 * @param int|null $count      Max reviews to display. Null/0 = show all retrieved.
 * @param int      $min_rating Minimum star rating to include (1–5).
 * @param int      $cache_time Cache duration in hours.
 * @return array{error:bool, name:string, rating:float, url:string, user_ratings_total:int, all_reviews:array, reviews:array}
 */
function mars_get_google_reviews($place_id, $api_key, $count = null, $min_rating = 1, $cache_time = 24)
{
	$limit_reviews = (!empty($count) && is_numeric($count) && $count > 0);
	$max_reviews   = $limit_reviews ? intval($count) : 50;

	$cache_key      = 'mars_google_reviews_' . md5($place_id . $api_key . $max_reviews . $min_rating . 'notranslate_fr');
	$cached_reviews = get_transient($cache_key);

	if (false !== $cached_reviews) {
		if ($limit_reviews && isset($cached_reviews['all_reviews'])) {
			$cached_reviews['reviews'] = array_slice($cached_reviews['all_reviews'], 0, $count);
		}
		return $cached_reviews;
	}

	$request_url = add_query_arg(array(
		'place_id'                => $place_id,
		'fields'                  => 'name,rating,reviews,url,user_ratings_total',
		'reviews_sort'            => 'newest',
		'reviews_no_translations' => 'true',
		'language'                => 'fr',
		'key'                     => $api_key,
	), 'https://maps.googleapis.com/maps/api/place/details/json');

	$response = wp_remote_get($request_url);

	if (is_wp_error($response)) {
		return array('error' => true, 'message' => $response->get_error_message());
	}

	$data = json_decode(wp_remote_retrieve_body($response), true);

	if (!isset($data['status']) || $data['status'] !== 'OK') {
		return array(
			'error'   => true,
			'message' => isset($data['error_message']) ? $data['error_message'] : 'Erreur inconnue',
		);
	}

	$place_data = array(
		'error'               => false,
		'name'                => $data['result']['name'] ?? '',
		'rating'              => isset($data['result']['rating']) ? floatval($data['result']['rating']) : 0,
		'url'                 => $data['result']['url'] ?? '',
		'user_ratings_total'  => isset($data['result']['user_ratings_total']) ? intval($data['result']['user_ratings_total']) : 0,
		'all_reviews'         => array(),
		'reviews'             => array(),
	);

	if (isset($data['result']['reviews'])) {
		foreach ($data['result']['reviews'] as $review) {
			if ($review['rating'] >= $min_rating) {
				$place_data['all_reviews'][] = array(
					'author'        => $review['author_name'],
					'avatar'        => $review['profile_photo_url'] ?? '',
					'rating'        => $review['rating'],
					'text'          => $review['text'],
					'time'          => $review['time'],
					'relative_time' => $review['relative_time_description'],
				);
			}
		}
	}

	if ($place_data['user_ratings_total'] === 0) {
		$place_data['user_ratings_total'] = count($place_data['all_reviews']);
	}

	$place_data['reviews'] = $limit_reviews
		? array_slice($place_data['all_reviews'], 0, $count)
		: $place_data['all_reviews'];

	set_transient($cache_key, $place_data, $cache_time * HOUR_IN_SECONDS);

	return $place_data;
}

// ---------------------------------------------------------------------------
// Phase 2 stub — Google Business Profile API (OAuth2, all reviews, paginated)
// ---------------------------------------------------------------------------
// function mars_get_all_google_reviews_business_profile($account_name, $location_name, $oauth_token, $page_size = 50) {
//     // Endpoint: https://mybusiness.googleapis.com/v4/{name}/reviews
//     // Requires OAuth2 access token (not just an API key).
//     // Returns paginated reviews with nextPageToken support.
// }
// ---------------------------------------------------------------------------

/**
 * Cron: daily refresh of cached reviews for all pages using a reviews block.
 */
function mars_schedule_google_reviews_update()
{
	if (!wp_next_scheduled('mars_update_google_reviews')) {
		wp_schedule_event(time(), 'daily', 'mars_update_google_reviews');
	}
}
add_action('wp', 'mars_schedule_google_reviews_update');

function mars_update_all_google_reviews()
{
	$review_blocks = array('acf/google-reviews', 'acf/testimonials', 'acf/testimonials-slider');

	$query = new WP_Query(array(
		'post_type'      => 'any',
		'posts_per_page' => -1,
	));

	if (!$query->have_posts()) {
		return;
	}

	while ($query->have_posts()) {
		$query->the_post();
		$content = get_the_content();

		foreach ($review_blocks as $block_name) {
			if (!has_block($block_name, $content)) {
				continue;
			}
			foreach (parse_blocks($content) as $block) {
				if ($block['blockName'] !== $block_name) {
					continue;
				}
				$data       = $block['attrs']['data'] ?? array();
				$place_id   = $data['place_id'] ?? ($data['google_place_id'] ?? '');
				$api_key    = $data['api_key'] ?? ($data['google_api_key'] ?? '');
				$count      = $data['reviews_count'] ?? ($data['google_count'] ?? null);
				$min_rating = $data['min_rating'] ?? ($data['google_min_rating'] ?? 1);

				if ($place_id && $api_key) {
					$limit        = (!empty($count) && is_numeric($count) && $count > 0);
					$max          = $limit ? intval($count) : 50;
					$cache_key    = 'mars_google_reviews_' . md5($place_id . $api_key . $max . $min_rating . 'notranslate_fr');
					delete_transient($cache_key);
				}
			}
		}
	}
	wp_reset_postdata();
}
add_action('mars_update_google_reviews', 'mars_update_all_google_reviews');

function mars_force_google_reviews_update()
{
	if (!current_user_can('manage_options')) {
		wp_die('Accès refusé');
	}
	mars_update_all_google_reviews();
	wp_redirect(admin_url('edit.php?post_type=page'));
	exit;
}
add_action('admin_post_mars_update_google_reviews', 'mars_force_google_reviews_update');

function mars_add_update_reviews_button($views)
{
	$views['mars_update_reviews'] = sprintf(
		'<a href="%s" class="button button-primary">%s</a>',
		esc_url(admin_url('admin-post.php?action=mars_update_google_reviews')),
		__('Actualiser les avis Google', 'mars')
	);
	return $views;
}
add_filter('views_edit-page', 'mars_add_update_reviews_button');
