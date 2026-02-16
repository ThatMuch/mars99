<?php

/**
 * Configuration de base du thème
 *
 * Fonctions de configuration de base du thème WordPress,
 * y compris l'enregistrement des menus, les supports de thème,
 * et autres fonctionnalités fondamentales.
 *
 * @package Mars
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Configuration des supports de thème
 */
function mars_theme_setup()
{
	// Support des images mises en avant
	add_theme_support('post-thumbnails');

	// Tailles d'images personnalisées
	set_post_thumbnail_size(385, 288, true); // Image mise en avant
	add_image_size('barbers', 288, 360, true);
	add_image_size('third-width', 377, 323, true);
	add_image_size('page-header', 1440, 430, true);

	// Support pour le titre de document
	add_theme_support('title-tag');

	// Support pour le logo personnalisé
	add_theme_support('custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array('site-title', 'site-description'),
	));

	// Support pour les blocs larges et pleine largeur
	add_theme_support('align-wide');

	// Palette de couleurs personnalisée pour l'éditeur
	add_theme_support('editor-color-palette', array(
		// Primary
		array('name' => 'Primary 50', 'slug' => 'primary-50', 'color' => '#FFF7FB'),
		array('name' => 'Primary 100', 'slug' => 'primary-100', 'color' => '#FFE7F6'),
		array('name' => 'Primary 200', 'slug' => 'primary-200', 'color' => '#FFD8EF'),
		array('name' => 'Primary 300', 'slug' => 'primary-300', 'color' => '#FFCAE6'),
		array('name' => 'Primary 400', 'slug' => 'primary-400', 'color' => '#FFB9DE'),
		array('name' => 'Primary 500', 'slug' => 'primary-500', 'color' => '#FAA4D1'),
		array('name' => 'Primary 600', 'slug' => 'primary-600', 'color' => '#F489C2'),
		array('name' => 'Primary 700', 'slug' => 'primary-700', 'color' => '#BC408B'),
		array('name' => 'Primary 800', 'slug' => 'primary-800', 'color' => '#911264'),
		array('name' => 'Primary 900', 'slug' => 'primary-900', 'color' => '#700550'),
		array('name' => 'Primary 950', 'slug' => 'primary-950', 'color' => '#53053C'),

		// Secondary
		array('name' => 'Secondary 50', 'slug' => 'secondary-50', 'color' => '#DEF8F7'),
		array('name' => 'Secondary 100', 'slug' => 'secondary-100', 'color' => '#CDF1F4'),
		array('name' => 'Secondary 200', 'slug' => 'secondary-200', 'color' => '#B9E7EB'),
		array('name' => 'Secondary 300', 'slug' => 'secondary-300', 'color' => '#A1DBDF'),
		array('name' => 'Secondary 400', 'slug' => 'secondary-400', 'color' => '#84CCCF'),
		array('name' => 'Secondary 500', 'slug' => 'secondary-500', 'color' => '#50B6BD'),
		array('name' => 'Secondary 600', 'slug' => 'secondary-600', 'color' => '#1EA1A9'),
		array('name' => 'Secondary 700', 'slug' => 'secondary-700', 'color' => '#16949A'),
		array('name' => 'Secondary 800', 'slug' => 'secondary-800', 'color' => '#007C84'),
		array('name' => 'Secondary 900', 'slug' => 'secondary-900', 'color' => '#175559'),
		array('name' => 'Secondary 950', 'slug' => 'secondary-950', 'color' => '#103C3F'),

		// Neutral
		array('name' => 'Neutral 50', 'slug' => 'neutral-50', 'color' => '#F6F6F7'),
		array('name' => 'Neutral 100', 'slug' => 'neutral-100', 'color' => '#EBEBEE'),
		array('name' => 'Neutral 200', 'slug' => 'neutral-200', 'color' => '#D5D5D8'),
		array('name' => 'Neutral 300', 'slug' => 'neutral-300', 'color' => '#D5D5D8'),
		array('name' => 'Neutral 400', 'slug' => 'neutral-400', 'color' => '#898A91'),
		array('name' => 'Neutral 500', 'slug' => 'neutral-500', 'color' => '#6B6B75'),
		array('name' => 'Neutral 600', 'slug' => 'neutral-600', 'color' => '#56565E'),
		array('name' => 'Neutral 700', 'slug' => 'neutral-700', 'color' => '#45454C'),
		array('name' => 'Neutral 800', 'slug' => 'neutral-800', 'color' => '#3B3B40'),
		array('name' => 'Neutral 900', 'slug' => 'neutral-900', 'color' => '#343537'),
		array('name' => 'Neutral 950', 'slug' => 'neutral-950', 'color' => '#232325'),

		// Base
		array('name' => 'White', 'slug' => 'white', 'color' => '#ffffff'),
		array('name' => 'Black', 'slug' => 'black', 'color' => '#000000'),
	));

	// Support pour les styles d'éditeur
	add_theme_support('editor-styles');

	// Support pour les fonctionnalités HTML5
	add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
}
add_action('after_setup_theme', 'mars_theme_setup');

/**
 * Enregistrement des menus du thème
 */
function mars_register_menus()
{
	register_nav_menus(
		array(
			'main-menu' => __('Main menu'),
			'footer-menu1' => __('Footer menu col 1'),
			'footer-menu2' => __('Footer menu col 2'),
			'footer-menu3' => __('Footer menu col 3'),
			'footer-menu4' => __('Footer menu col 4'),
			'footer-menu5' => __('Footer menu col 5'),
			'footer-menu6' => __('Footer menu col 6'),
		)
	);
}
add_action('init', 'mars_register_menus');

/**
 * Désactiver la barre d'administration pour tous sauf les administrateurs
 */
function mars_remove_admin_bar()
{
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'mars_remove_admin_bar');

/**
 * Ajouter des tailles d'images dans le menu déroulant de l'interface d'administration
 */
function mars_show_image_sizes($sizes)
{
	return array_merge($sizes, array(
		'barbers' => __('Barber Size'),
		'third-width' => __('One Third Width'),
		'page-header' => __('Page Header')
	));
}
add_filter('image_size_names_choose', 'mars_show_image_sizes');

/**
 * Désactiver les author dans les embeds
 */
add_filter('oembed_response_data', 'disable_embeds_filter_oembed_response_data_');
function disable_embeds_filter_oembed_response_data_($data)
{
	unset($data['author_url']);
	unset($data['author_name']);
	return $data;
}
