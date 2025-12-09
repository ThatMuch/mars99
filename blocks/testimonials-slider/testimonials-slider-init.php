<?php

/**
 * Enregistrement du bloc ACF Testimonials Slider
 */

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_testimonials_slider_block');
}

function register_testimonials_slider_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'testimonials-slider',
		'title'             => __('Testimonials Slider', 'abyssenergy'),
		'description'       => __('Un slider pour afficher les témoignages clients.', 'abyssenergy'),
		'render_template'   => 'blocks/testimonials-slider/block-testimonials-slider.php',
		'category'          => 'formatting',
		'icon'              => 'testimonial',
		'keywords'          => array('testimonials', 'slider', 'témoignages', 'clients'),
		'supports'          => array(
			'align' => true,
			'mode' => false,
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
		'enqueue_style'     => get_template_directory_uri() . '/blocks/testimonials-slider/testimonials-slider.css',
		'enqueue_script'    => get_template_directory_uri() . '/blocks/testimonials-slider/testimonials-slider.js',
		'enqueue_assets'    => function () {
			// Assurez-vous que Swiper est chargé
			wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');
			wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
		}
	));
}

/**
 * Création des champs ACF pour le bloc Testimonials Slider
 */
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group(array(
		'key' => 'group_testimonials_slider',
		'title' => 'Testimonials Slider Settings',
		'fields' => array(
			array(
				'key' => 'field_testimonials_slider_title',
				'label' => 'Titre de la section',
				'name' => 'title',
				'type' => 'text',
				'instructions' => 'Entrez le titre de la section des témoignages.',
				'default_value' => 'Témoignages de nos clients',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_testimonials_slider_subtitle',
				'label' => 'Sous-titre de la section',
				'name' => 'subtitle',
				'type' => 'text',
				'instructions' => 'Entrez le sous-titre de la section des témoignages.',
				'default_value' => 'Ce que disent nos clients',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_testimonials_selection_type',
				'label' => 'Type de sélection',
				'name' => 'selection_type',
				'type' => 'radio',
				'instructions' => 'Choisir comment sélectionner les témoignages à afficher',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'all' => 'Tous les témoignages',
					'specific_category' => 'Par catégorie spécifique',
				),
				'allow_null' => 0,
				'other_choice' => 0,
				'default_value' => 'all',
				'layout' => 'horizontal',
				'return_format' => 'value',
			),
			array(
				'key' => 'field_testimonials_specific_category',
				'label' => 'Sélectionner une catégorie',
				'name' => 'specific_category',
				'type' => 'taxonomy',
				'instructions' => 'Sélectionnez la catégorie de témoignages à afficher',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_testimonials_selection_type',
							'operator' => '==',
							'value' => 'specific_category',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'testimonial-category',
				'field_type' => 'select',
				'allow_null' => 0,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'multiple' => 0,
			),
			array(
				'key' => 'field_testimonials_slider_image',
				'label' => 'Image de la section témoignage',
				'name' => 'image',
				'type' => 'image',
				'instructions' => 'Téléchargez une image pour la section témoignage.',
				'required' => 1,
				'return_format' => 'id',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array(
				'key' => 'field_testimonials_slider_header_alignment',
				'label' => 'Alignement du header',
				'name' => 'header_alignment',
				'type' => 'radio',
				'instructions' => 'Choisir l\'alignement du titre et de l\'image',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'right' => 'Droite',
					'left' => 'Gauche',
				),
				'allow_null' => 0,
				'other_choice' => 0,
				'default_value' => 'right',
				'layout' => 'horizontal',
				'return_format' => 'value',
			)
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/testimonials-slider',
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
