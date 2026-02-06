<?php

/**
 * Personnalisations du Customizer WordPress
 *
 * Configurations pour le Customizer WordPress, incluant le CTA Header et autres options.
 *
 * @package Mars
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Configuration du CTA Header via le Customizer WordPress
 */
function mars_customize_register($wp_customize)
{
	// Panel Header Button
	$wp_customize->add_panel('mars_header_panel', array(
		'title'       => __('Call to action menu', 'mars'),
		'priority'    => 30,
		'description' => __('Sélectionnez un menu pour configurer son bouton.', 'mars'),
	));

	$menus = wp_get_nav_menus();

	if (!empty($menus)) {
		foreach ($menus as $menu) {
			$menu_id = $menu->term_id;
			$menu_name = $menu->name;

			// Section per Menu
			$wp_customize->add_section('mars_header_section_' . $menu_id, array(
				'title'  => $menu_name,
				'panel'  => 'mars_header_panel',
			));

			// Button Text
			$wp_customize->add_setting('header_btn_text_' . $menu_id, array(
				'default'   => '',
				'transport' => 'refresh',
			));
			$wp_customize->add_control('header_btn_text_' . $menu_id, array(
				'label'    => __('Texte du bouton', 'mars'),
				'section'  => 'mars_header_section_' . $menu_id,
				'type'     => 'text',
			));

			// Link Type (Page or Custom URL)
			$wp_customize->add_setting('header_btn_link_type_' . $menu_id, array(
				'default'   => 'page',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			));
			$wp_customize->add_control('header_btn_link_type_' . $menu_id, array(
				'label'    => __('Type de lien', 'mars'),
				'section'  => 'mars_header_section_' . $menu_id,
				'type'     => 'radio',
				'choices'  => array(
					'page' => __('Sélectionner une page/article', 'mars'),
					'custom' => __('URL personnalisée', 'mars'),
				),
			));

			// Button Link - Page/Post Selector
			$wp_customize->add_setting('header_btn_link_page_' . $menu_id, array(
				'default'   => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint',
			));
			$wp_customize->add_control('header_btn_link_page_' . $menu_id, array(
				'label'    => __('Sélectionner une page', 'mars'),
				'section'  => 'mars_header_section_' . $menu_id,
				'type'     => 'dropdown-pages',
				'active_callback' => function () use ($menu_id, $wp_customize) {
					return 'page' === $wp_customize->get_setting('header_btn_link_type_' . $menu_id)->value();
				},
			));

			// Button Link - Custom URL
			$wp_customize->add_setting('header_btn_link_custom_' . $menu_id, array(
				'default'   => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'esc_url_raw',
			));
			$wp_customize->add_control('header_btn_link_custom_' . $menu_id, array(
				'label'    => __('URL personnalisée', 'mars'),
				'section'  => 'mars_header_section_' . $menu_id,
				'type'     => 'url',
				'description' => __('Entrez une URL complète (ex: https://example.com)', 'mars'),
				'active_callback' => function () use ($menu_id, $wp_customize) {
					return 'custom' === $wp_customize->get_setting('header_btn_link_type_' . $menu_id)->value();
				},
			));

			// Button Style
			$wp_customize->add_setting('header_btn_style_' . $menu_id, array(
				'default'   => 'btn__primary',
				'transport' => 'refresh',
			));
			$wp_customize->add_control('header_btn_style_' . $menu_id, array(
				'label'    => __('Style du bouton', 'mars'),
				'section'  => 'mars_header_section_' . $menu_id,
				'type'     => 'select',
				'choices'  => array(
					'btn__primary'   => 'Primaire',
					'btn__secondary' => 'Secondaire',
					'btn__outline'   => 'Contour',
					'btn__white'     => 'Blanc',
				),
			));
			// Icon Class
			$wp_customize->add_setting('header_btn_icon_' . $menu_id, array(
				'default'   => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			));
			$wp_customize->add_control('header_btn_icon_' . $menu_id, array(
				'label'    => __('Classe icône Font Awesome', 'mars'),
				'section'  => 'mars_header_section_' . $menu_id,
				'type'     => 'text',
				'description' => __('Ex: fa-solid fa-phone', 'mars'),
			));
		}
	}
}
add_action('customize_register', 'mars_customize_register');



/**
 * Ajouter le logo du footer dans le Customizer
 */
function mars_customize_register_footer_logo($wp_customize)
{
	// Ajouter le logo du footer
	$wp_customize->add_setting('mars_footer_logo', array(
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Image_Control(
		$wp_customize,
		'mars_footer_logo',
		array(
			'label'    => __('Logo du Footer', 'mars'),
			'section'  => 'title_tagline',
			'settings' => 'mars_footer_logo',
			'priority' => 30,
		)
	));
}
add_action('customize_register', 'mars_customize_register_footer_logo');
