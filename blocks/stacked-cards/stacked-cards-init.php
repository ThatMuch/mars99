<?php

/**
 * Stacked Cards Block Initialization
 * @package Mars
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Stacked Cards
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_stacked_cards_block');
}

function register_stacked_cards_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'stacked-cards',
		'title'             => __('Stacked Cards', 'mars'),
		'description'       => __('A container block that stacks its inner blocks on scroll.', 'mars'),
		'render_template'   => 'blocks/stacked-cards/block-stacked-cards.php',
		'category'          => 'mars-blocks',
		'icon'              => 'images-alt2', // Icon related to stacking/layers
		'keywords'          => array('stack', 'cards', 'scroll', 'sticky'),
		'supports'          => array(
			'align' => true,
			'mode' => true,
			'jsx' => true, // Enable InnerBlocks
			'anchor' => true,
		),
		'example'           => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview' => true
				)
			)
		),
		// No specific enqueue here, as we'll add styles to main stylesheet or block specific if preferred.
		// For now, let's assume it's part of the main build or loaded via block assets if configured that way.
		// 'enqueue_style'     => get_template_directory_uri() . '/blocks/stacked-cards/stacked-cards.css',
	));
}
