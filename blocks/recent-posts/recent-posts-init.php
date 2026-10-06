<?php

/**
 * Recent Posts Block Initialization
 * @package Mars
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_recent_posts_block');
}

function register_recent_posts_block()
{
	acf_register_block_type(array(
		'name'              => 'recent-posts',
		'title'             => __('Derniers Articles', 'mars'),
		'description'       => __('Affiche les derniers articles du blog dans un carrousel de cartes.', 'mars'),
		'render_template'   => 'blocks/recent-posts/block-recent-posts.php',
		'category'          => 'mars-blocks',
		'icon'              => 'admin-post',
		'keywords'          => array('posts', 'articles', 'blog', 'recent', 'carrousel', 'slider'),
		'supports'          => array(
			'align' => true,
			'mode' => true,
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
		'enqueue_assets'    => function () {
			wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');
			wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', array('strategy' => 'defer'));
			wp_enqueue_script('recent-posts-js', get_template_directory_uri() . '/blocks/recent-posts/recent-posts.js', array('swiper-js'), mars_get_file_version('/blocks/recent-posts/recent-posts.js'), array('strategy' => 'defer'));
		},
	));

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_recent_posts_block',
			'title' => 'Paramètres du bloc : Derniers Articles',
			'fields' => array(
				array(
					'key' => 'field_recent_posts_eyebrow',
					'label' => 'Eyebrow',
					'name' => 'eyebrow',
					'type' => 'text',
					'required' => 0,
				),
				array(
					'key' => 'field_recent_posts_title',
					'label' => 'Titre de la section',
					'name' => 'title',
					'type' => 'text',
					'required' => 0,
				),
				array(
					'key' => 'field_recent_posts_count',
					'label' => 'Nombre d\'articles à afficher',
					'name' => 'posts_per_page',
					'type' => 'number',
					'default_value' => 8,
					'min' => 1,
				)
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/recent-posts',
					),
				),
			),
		));
	}
}
