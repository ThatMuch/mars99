<?php

/**
 * Widgets et sidebars personnalisés
 *
 * Enregistrement et configuration des widgets et sidebars
 *
 * @package Mars
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrer les sidebars du thème
 */
function mars_register_sidebars()
{
	// Sidebar principale
	register_sidebar(array(
		'name'          => __('Sidebar principale', 'mars'),
		'id'            => 'main-sidebar',
		'description'   => __('Widgets affichés dans la sidebar principale.', 'mars'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	// Sidebar du pied de page
	register_sidebar(array(
		'name'          => __('Footer Widgets', 'mars'),
		'id'            => 'footer-widgets',
		'description'   => __('Widgets affichés dans le pied de page.', 'mars'),
		'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	));
}
add_action('widgets_init', 'mars_register_sidebars');
