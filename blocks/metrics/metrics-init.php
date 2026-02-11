<?php

/**
 * Metrics Block Initialization.
 * @package AbyssEnergy
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Metrics
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_metrics_block');
}

function register_metrics_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'metrics',
		'title'             => __('Métriques', 'abyssenergy'),
		'description'       => __('Affiche des métriques clés avec des graphiques.', 'abyssenergy'),
		'render_template'   => 'blocks/metrics/block-metrics.php',
		'category'          => 'mars-blocks',
		'icon'              => 'chart-bar',
		'keywords'          => array('metrics', 'data', 'charts'),
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
		'enqueue_style'     => get_template_directory_uri() . '/blocks/metrics/metrics.css',
		'enqueue_script'    => get_template_directory_uri() . '/blocks/metrics/metrics.js',
	));

	if (function_exists('acf_add_local_field_group')) {
		// Créer les champs ACF pour le bloc
		acf_add_local_field_group(array(
			'key' => 'group_metrics',
			'title' => 'Paramètres du bloc Métriques',
			'fields' => array(
				array(
					'key' => 'field_metrics_items',
					'label' => 'Métriques',
					'name' => 'metrics',
					'type' => 'repeater',
					'instructions' => 'Ajoutez des métriques à afficher.',
					'min' => 1,
					'layout' => 'row',
					'button_label' => 'Ajouter une métrique',
					'sub_fields' => array(
						array(
							'key' => 'field_metric_value',
							'label' => 'Valeur',
							'name' => 'value',
							'type' => 'text',
							'instructions' => 'Entrez la valeur de la métrique.',
							'required' => 1,
							'wrapper' => array(
								'class' => 'metric-value-field'
							),
						),
						array(
							'key' => 'field_metric_label',
							'label' => 'Libellé',
							'name' => 'label',
							'type' => 'text',
							'instructions' => 'Entrez le libellé de la métrique.',
							'required' => 1,
							'wrapper' => array(
								'class' => 'metric-label-field'
							),
						),
						array(
							'key' => 'field_metric_excerpt',
							'label' => 'Texte',
							'name' => 'excerpt',
							'type' => 'textarea',
							'instructions' => 'Entrez le texte de la métrique.',
							'required' => 1,
							'wrapper' => array(
								'class' => 'metric-label-field'
							),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/metrics',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',

		));
	}
}
