<?php

/**
 * Enregistrement des scripts et styles
 *
 * Gestion de l'inclusion des fichiers CSS et JavaScript
 * pour le front-end et le back-end du thème.
 *
 * @package Mars
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Obtenir la version du fichier pour cache busting
 */
function mars_get_file_version($file)
{
	$file_path = get_stylesheet_directory() . $file;
	return file_exists($file_path) ? filemtime($file_path) : wp_get_theme()->get('Version');
}

/**
 * Enregistrement et chargement des styles et scripts du thème
 */
function mars_enqueue_scripts()
{
	// Styles
	wp_enqueue_style(
		'mars-style',
		get_stylesheet_directory_uri() . '/style.min.css',
		array(),
		mars_get_file_version('/style.min.css')
	);

	// Ajouter Font Awesome pour les icônes
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css',
		array(),
		'6.7.2'
	);

	// Scripts
	// Script pour la gestion des téléchargements de fichiers
	if (file_exists(get_stylesheet_directory() . '/js/file-upload.js')) {
		wp_enqueue_script(
			'mars-file-upload',
			get_stylesheet_directory_uri() . '/js/file-upload.js',
			array('jquery'),
			mars_get_file_version('/js/file-upload.js'),
			true
		);
	}

	// Script pour la gestion des optgroups (doit être chargé AVANT multiselect)
	if (file_exists(get_stylesheet_directory() . '/js/optgroup-handler.js')) {
		wp_enqueue_script(
			'mars-optgroup-handler',
			get_stylesheet_directory_uri() . '/js/optgroup-handler.js',
			array(),
			mars_get_file_version('/js/optgroup-handler.js'),
			true
		);
	}

	// Script pour les sélecteurs multiples personnalisés (dépend des optgroups)
	if (file_exists(get_stylesheet_directory() . '/js/multiselect.js')) {
		wp_enqueue_script(
			'mars-multiselect',
			get_stylesheet_directory_uri() . '/js/multiselect.js',
			array('jquery', 'mars-optgroup-handler'),
			mars_get_file_version('/js/multiselect.js'),
			true
		);
	}

	// Script pour les CTA dans l'en-tête
	if (file_exists(get_stylesheet_directory() . '/js/header-cta.js')) {
		wp_enqueue_script(
			'mars-header-cta',
			get_stylesheet_directory_uri() . '/js/header-cta.js',
			array('jquery'),
			mars_get_file_version('/js/header-cta.js'),
			true
		);
	}

	// Script pour le menu mobile
	if (file_exists(get_stylesheet_directory() . '/js/mobile-menu.js')) {
		wp_enqueue_script(
			'mars-mobile-menu',
			get_stylesheet_directory_uri() . '/js/mobile-menu.js',
			array(),
			mars_get_file_version('/js/mobile-menu.js'),
			true
		);
	}

	// Script optgroups déplacé plus haut pour être chargé avant multiselect

	// Script Lottie pour les animations (seulement sur la front-page)
	if (is_front_page()) {
		wp_enqueue_script(
			'dotlottie-wc',
			'https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.1/dist/dotlottie-wc.js',
			array(),
			'0.8.1',
			true
		);

		// Ajouter l'attribut type="module" au script Lottie
		add_filter('script_loader_tag', 'mars_add_module_to_lottie_script', 10, 2);
	}

	// Scripts et styles pour la page de recherche d'emplois
	if (is_page_template('page-search-jobs.php') || is_page('search-jobs')) {
		// JavaScript pour la page de recherche
		wp_enqueue_script(
			'mars-search-jobs-js',
			get_stylesheet_directory_uri() . '/js/search-jobs.js',
			array('jquery'),
			mars_get_file_version('/js/search-jobs.js'),
			true
		);
	}

	// Script pour les filtres mobiles sur la page jobs
	if (is_page_template('page-jobs.php') || is_page('jobs')) {
		wp_enqueue_script(
			'mars-jobs-filters-mobile',
			get_stylesheet_directory_uri() . '/js/jobs-filters-mobile.js',
			array('jquery'),
			mars_get_file_version('/js/jobs-filters-mobile.js'),
			true
		);
	}

	// Script pour le contexte des formulaires Gravity Forms sur les pages de job
	if (is_singular('job') && file_exists(get_stylesheet_directory() . '/js/gravity-forms-post-context.js')) {
		wp_enqueue_script(
			'mars-gravity-forms-context',
			get_stylesheet_directory_uri() . '/js/gravity-forms-post-context.js',
			array('jquery'),
			mars_get_file_version('/js/gravity-forms-post-context.js'),
			true
		);

		// Ajouter le post ID comme variable JavaScript
		wp_localize_script(
			'mars-gravity-forms-context',
			'mars_job_context',
			array(
				'post_id' => get_the_ID(),
				'ajax_url' => admin_url('admin-ajax.php')
			)
		);
	}

	// Script pour le footer parallax
	if (file_exists(get_stylesheet_directory() . '/js/footer-parallax.js')) {
		wp_enqueue_script(
			'mars-footer-parallax',
			get_stylesheet_directory_uri() . '/js/footer-parallax.js',
			array(),
			mars_get_file_version('/js/footer-parallax.js'),
			true
		);
	}

	// Scripts personnalisés
	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
	// Carousel Script (Shared)
	if (file_exists(get_stylesheet_directory() . '/js/carousel.js')) {
		wp_enqueue_script(
			'mars-carousel',
			get_stylesheet_directory_uri() . '/js/carousel.js',
			array(),
			mars_get_file_version('/js/carousel.js'),
			true
		);
	}
}
add_action('wp_enqueue_scripts', 'mars_enqueue_scripts');

/**
 * Ajouter des styles spécifiques pour l'éditeur de blocs
 */
function mars_enqueue_block_editor_assets()
{
	// Toujours essayer de compiler le SCSS (la fonction vérifie elle-même si c'est nécessaire)
	mars_compile_scss_file('scss/admin-styles.scss', 'css/admin-editor-styles.css');

	// Créer le fichier CSS s'il n'existe pas
	$css_file = get_stylesheet_directory() . '/css/admin-editor-styles.css';
	if (!file_exists($css_file)) {
		file_put_contents($css_file, '/* Styles générés automatiquement pour l\'éditeur de blocs */');
	}

	// Enregistrer et charger les styles d'éditeur
	wp_enqueue_style(
		'mars-editor-styles',
		get_template_directory_uri() . '/css/admin-editor-styles.css',
		array(),
		file_exists($css_file) ? filemtime($css_file) : time()
	);
}
add_action('enqueue_block_editor_assets', 'mars_enqueue_block_editor_assets');

/**
 * Ajouter des styles personnalisés à l'éditeur
 */
function mars_add_editor_styles()
{
	add_editor_style('css/admin-editor-styles.css');
}
add_action('admin_init', 'mars_add_editor_styles');

/**
 * Enregistrer et charger les bibliothèques Leaflet
 * pour la carte interactive globale
 */
function mars_enqueue_leaflet()
{
	// Vérifions si au moins un bloc 'global-map' est présent dans le contenu
	global $post;
	$enqueue_leaflet = false;

	// Dans l'éditeur admin, toujours charger Leaflet
	if (is_admin()) {
		$enqueue_leaflet = true;
	} elseif (is_singular() && $post && has_blocks($post->post_content)) {
		// Pour le front-end, vérifier si le bloc est utilisé
		$blocks = parse_blocks($post->post_content);
		foreach ($blocks as $block) {
			if ($block['blockName'] === 'acf/global-map') {
				$enqueue_leaflet = true;
				break;
			}
		}
	}

	// Si on a besoin de Leaflet, on charge les fichiers
	if ($enqueue_leaflet) {
		// CSS Leaflet (depuis CDN)
		wp_enqueue_style(
			'leaflet-css',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
			array(),
			'1.9.4'
		);

		// JavaScript Leaflet (depuis CDN)
		wp_enqueue_script(
			'leaflet-js',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
			array(),
			'1.9.4',
			true
		);
	}
}
add_action('wp_enqueue_scripts', 'mars_enqueue_leaflet');
add_action('enqueue_block_editor_assets', 'mars_enqueue_leaflet');

/**
 * Ajouter l'attribut type="module" au script Lottie
 */
function mars_add_module_to_lottie_script($tag, $handle)
{
	if ('dotlottie-wc' === $handle) {
		$tag = str_replace('<script ', '<script type="module" ', $tag);
	}
	return $tag;
}
