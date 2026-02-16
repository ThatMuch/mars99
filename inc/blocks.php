<?php

/**
 * Blocs Gutenberg personnalisés
 *
 * Enregistrement et configuration des blocs Gutenberg
 * personnalisés pour le thème.
 *
 * @package Mars
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Charger les fichiers d'initialisation des blocs
 */
function mars_load_blocks()
{
	$blocks_directory = get_template_directory() . '/blocks/';
	// Liste des dossiers de blocks à enregistrer
	$blocks = array(
		'card',
		'clients',
		'custom-buttons',
		'features',
		'google-reviews',
		'metrics',
		'timeline',
		'cta',
		'testimonials',
		'stacked-cards',
	);

	foreach ($blocks as $block) {
		$init_file = $blocks_directory . $block . '/' . $block . '-init.php';
		if (file_exists($init_file)) {
			require_once $init_file;
		}
	}

	require_once get_template_directory() . '/blocks/card/card-init.php';
}
// Charger les fichiers d'initialisation tôt
add_action('after_setup_theme', 'mars_load_blocks');


/**
 * Ajouter une catégorie de blocs personnalisée
 */
function mars_block_categories($categories, $post)
{
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'mars-blocks',
				'title' => __('Mars', 'mars'),
				'icon'  => 'admin-site',
			),
		)
	);
}
add_filter('block_categories_all', 'mars_block_categories', 10, 2);
