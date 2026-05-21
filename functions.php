<?php

/**
 * Fichier principal du thème
 *
 * Ce fichier charge tous les composants modulaires du thème
 * pour une meilleure organisation et maintenabilité.
 *
 * @package Mars
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

// Définir les constantes du thème
define('MARS_VERSION', '1.0.0');
define('MARS_DIR', get_template_directory());
define('MARS_URI', get_template_directory_uri());

/**
 * Fonction pour charger les fichiers de modules du thème
 */
function mars_load_theme_modules()
{
	$modules = [
		'acf-safe-helpers.php',        // Helpers sécurisés pour ACF
		'inc/setup.php',               // Configuration principale du thème
		'inc/utils.php',               // Fonctions utilitaires
		'inc/google-reviews-api.php',  // API Google Reviews (partagée par plusieurs blocs)
		'inc/enqueue.php',             // Enregistrement des scripts et styles
		'inc/admin.php',               // Personnalisation de l'interface d'administration
		'inc/blocks.php',              // Blocs Gutenberg personnalisés
		'inc/acf.php',        // Intégration avec Advanced Custom Fields
		'inc/widgets.php',    // Widgets personnalisés
		'inc/scss.php',       // Compilation SCSS (si présent)
		'inc/customizer.php', // Personnalisations du Customizer WordPress
		'inc/cpt.php',        // Custom Post Types
	];

	foreach ($modules as $module) {
		if (file_exists(get_template_directory() . '/' . $module)) {
			require_once get_template_directory() . '/' . $module;
		}
	}
}

// Charger tous les modules du thème
mars_load_theme_modules();

// Legacy code - sera migré progressivement vers l'architecture modulaire

//images
add_theme_support('post-thumbnails');

set_post_thumbnail_size(385, 288, true); //featured image - the true sets it to this size exactly, omitting this will scale longest side. Call by  the_post_thumbnail();
//medium add_image_size( 'two-col-standard', 534, 313, true ); //repeat this for other thumbs. Call by  the_post_thumbnail('blogfeatured');
add_image_size('barbers', 288, 360, true);
add_image_size('third-width', 377, 323, true);
add_image_size('page-header', 1440, 430, true);

// add menu support and fallback menu if menu doesn't exist
add_action('init', 'register_my_menus');

function register_my_menus()
{
	register_nav_menus(
		array(
			'main-menu' => __('Main menu'),
			'footer-menu1' => __('Footer menu col 1'),
			'footer-menu2' => __('Footer menu col 2'),
			'footer-menu3' => __('Footer menu col 3'),
			'footer-menu4' => __('Footer menu col 4'),
			'footer-bottom-menu' => __('Footer bottom menu (legal links)'),
		)
	);
}

//acf options page
if (function_exists('acf_add_options_page')) {

	acf_add_options_page(array(
		'page_title'   => 'General Options',
		'menu_title'  => 'General Options',
		'menu_slug'   => 'theme-general-settings',
		'capability'  => 'edit_posts',
		'redirect'    => false
	));
}

/**
 * Vider les règles de réécriture lors de l'initialisation du thème
 */
function mars_flush_rewrite_rules()
{
	$flushed = get_option('mars_rewrite_rules_flushed');

	// Si les règles n'ont pas encore été vidées, le faire maintenant
	if (!$flushed) {
		flush_rewrite_rules();
		update_option('mars_rewrite_rules_flushed', true);
	}
}
add_action('init', 'mars_flush_rewrite_rules', 20);

/**
 * Ajouter automatiquement loading="lazy" aux images dans wp_get_attachment_image
 */
function mars_add_lazy_loading_to_images($attr, $attachment, $size)
{
	// Ne pas ajouter loading="lazy" si déjà présent ou si c'est le logo dans le header
	if (isset($attr['loading']) || (isset($attr['class']) && strpos($attr['class'], 'header__logo-image') !== false)) {
		return $attr;
	}

	// Ajouter loading="lazy" par défaut
	$attr['loading'] = 'lazy';

	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'mars_add_lazy_loading_to_images', 10, 3);

/**
 * Ajouter loading="lazy" aux images dans le contenu des posts
 */
function mars_add_lazy_loading_to_content_images($content)
{
	// Utiliser une regex pour ajouter loading="lazy" aux images qui ne l'ont pas déjà
	$content = preg_replace_callback(
		'/<img([^>]*)>/i',
		function ($matches) {
			$img_tag = $matches[0];

			// Si loading= est déjà présent, ne pas modifier
			if (strpos($img_tag, 'loading=') !== false) {
				return $img_tag;
			}

			// Si c'est le logo du header, ne pas ajouter loading="lazy"
			if (strpos($img_tag, 'header__logo-image') !== false) {
				return $img_tag;
			}

			// Ajouter loading="lazy" avant la fermeture du tag
			return str_replace('>', ' loading="lazy">', $img_tag);
		},
		$content
	);

	return $content;
}
add_filter('the_content', 'mars_add_lazy_loading_to_content_images');
add_filter('widget_text_content', 'mars_add_lazy_loading_to_content_images');

function hide_update_notice_to_all_but_admin_users()
{
	if (!current_user_can('update_core')) {
		remove_action('admin_notices', 'update_nag', 3);
	}
}
add_action('init', 'hide_update_notice_to_all_but_admin_users');
