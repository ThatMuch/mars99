<?php

/**
 * Playlist Block Initialization
 * @package Mars
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_playlist_block');
}

function register_playlist_block()
{
	acf_register_block_type(array(
		'name'              => 'playlist',
		'title'             => __('Playlist Audio', 'mars'),
		'description'       => __('Affiche un lecteur audio avec une liste de pistes.', 'mars'),
		'render_template'   => 'blocks/playlist/block-playlist.php',
		'category'          => 'mars-blocks',
		'icon'              => 'playlist-audio',
		'keywords'          => array('playlist', 'audio', 'lecteur', 'musique', 'player'),
		'supports'          => array(
			'align' => true,
			'mode' => true,
			'jsx' => true
		),
		'enqueue_script'    => get_template_directory_uri() . '/blocks/playlist/playlist.js',
		'example'           => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview' => true
				)
			)
		)
	));

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_playlist_block',
			'title' => 'Paramètres du bloc : Playlist',
			'fields' => array(
				array(
					'key' => 'field_playlist_repeater',
					'label' => 'Pistes Audio',
					'name' => 'tracks',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Ajouter une piste',
					'sub_fields' => array(
						array(
							'key' => 'field_playlist_title',
							'label' => 'Titre',
							'name' => 'titre',
							'type' => 'text',
							'required' => 1,
						),
						array(
							'key' => 'field_playlist_description',
							'label' => 'Description',
							'name' => 'description',
							'type' => 'textarea',
							'rows' => 3,
						),
						array(
							'key' => 'field_playlist_audio_file',
							'label' => 'Fichier audio',
							'name' => 'fichier_audio',
							'type' => 'file',
							'return_format' => 'url',
							'mime_types' => 'mp3,wav,ogg',
							'required' => 1,
						)
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/playlist',
					),
				),
			),
		));
	}
}
