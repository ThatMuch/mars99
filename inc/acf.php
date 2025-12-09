<?php

/**
 * Fonctions pour Advanced Custom Fields (ACF)
 *
 * Configuration et personnalisation d'ACF
 *
 * @package Mars
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Configurer les pages d'options ACF
 */
function mars_acf_options_page()
{
	if (!function_exists('acf_add_options_page')) {
		return;
	}

	// Page d'options générales
	acf_add_options_page(array(
		'page_title'   => 'General Options',
		'menu_title'  => 'General Options',
		'menu_slug'   => 'theme-general-settings',
		'capability'  => 'edit_posts',
		'redirect'    => false
	));
}
add_action('acf/init', 'mars_acf_options_page');

/**
 * Sauvegarder les champs ACF au format JSON
 */
function mars_acf_json_save_point($path)
{
	// Enregistrer les fichiers JSON dans /acf-json/
	$path = get_stylesheet_directory() . '/acf-json';
	return $path;
}
add_filter('acf/settings/save_json', 'mars_acf_json_save_point');

/**
 * Charger les fichiers JSON d'ACF
 */
function mars_acf_json_load_point($paths)
{
	// Ajouter le chemin de chargement
	$paths[] = get_stylesheet_directory() . '/acf-json';
	return $paths;
}
add_filter('acf/settings/load_json', 'mars_acf_json_load_point');
