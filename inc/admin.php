<?php

/**
 * Fonctionnalités d'administration
 *
 * Personnalisation de l'interface d'administration WordPress
 *
 * @package Mars
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Supprimer les styles de bloc par défaut
 */
function mars_remove_block_styles()
{
	wp_dequeue_style('wp-block-columns');
	wp_dequeue_style('wp-block-column');
}
add_action('wp_enqueue_scripts', 'mars_remove_block_styles');

/**
 * Masquer les notifications de mise à jour pour les non-administrateurs
 */
function mars_hide_update_notice()
{
	if (!current_user_can('update_core')) {
		remove_action('admin_notices', 'update_nag', 3);
	}
}
add_action('admin_head', 'mars_hide_update_notice', 1);

/**
 * Personnaliser le texte de pied de page d'administration
 */
function mars_change_footer_admin()
{
	echo '<span id="thatmuch">Développé par <a href="https://thatmuch.fr" target="_blank">THATMUCH</a></span>';
}
add_filter('admin_footer_text', 'mars_change_footer_admin');

/**
 * Supprimer le logo WordPress du menu d'administration
 */
function mars_remove_wp_logo($wp_admin_bar)
{
	$wp_admin_bar->remove_node('wp-logo');
}
add_action('admin_bar_menu', 'mars_remove_wp_logo', 999);

/**
 * Personnaliser l'écran de connexion
 */
function mars_custom_login()
{
	// Style personnalisé pour l'écran de connexion
	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/css/login-styles.css" />';
}
add_action('login_head', 'mars_custom_login');

/**
 * Changer l'URL du logo de connexion
 */
function mars_login_logo_url()
{
	return home_url();
}
add_filter('login_headerurl', 'mars_login_logo_url');

/**
 * Changer le titre du logo de connexion
 */
function mars_login_logo_url_title()
{
	return get_bloginfo('name');
}
add_filter('login_headertext', 'mars_login_logo_url_title');
