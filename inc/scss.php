<?php

/**
 * Gestion SCSS et compilation
 *
 * Fonctions pour compiler les fichiers SCSS en CSS
 * automatiquement selon les besoins.
 *
 * @package Mars
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Compilation automatique des fichiers SCSS (mode développement uniquement)
 */
function mars_compile_scss()
{
	// Ne pas compiler en production
	if (defined('WP_ENV') && WP_ENV === 'production') {
		return;
	}

	$scss_file = get_stylesheet_directory() . '/scss/style.scss';
	$css_file = get_stylesheet_directory() . '/style.css';

	// Vérifier si le fichier SCSS existe et s'il est plus récent que le CSS
	if (file_exists($scss_file)) {
		$scss_time = filemtime($scss_file);
		$css_time = file_exists($css_file) ? filemtime($css_file) : 0;

		// Si SCSS est plus récent ou si CSS n'existe pas
		if ($scss_time > $css_time) {
			// Essayer de compiler avec Sass si disponible
			if (function_exists('exec') && !empty(shell_exec('which sass'))) {
				$command = sprintf(
					'sass %s:%s --style expanded 2>&1',
					escapeshellarg($scss_file),
					escapeshellarg($css_file)
				);

				$output = shell_exec($command);

				// Log en cas d'erreur
				if (strpos($output, 'Error') !== false) {
					error_log('SCSS Compilation Error: ' . $output);
				}
			}
		}
	}
}

/**
 * Compile un fichier SCSS spécifique vers un fichier CSS
 *
 * @param string $scss_path Chemin relatif du fichier SCSS
 * @param string $css_path Chemin relatif du fichier CSS
 */
function mars_compile_scss_file($scss_path, $css_path)
{
	// Ne pas compiler en production
	if (defined('WP_ENV') && WP_ENV === 'production') {
		return;
	}

	$scss_file = get_stylesheet_directory() . '/' . $scss_path;
	$css_file = get_stylesheet_directory() . '/' . $css_path;

	// Vérifier si le fichier SCSS existe et s'il est plus récent que le CSS
	if (file_exists($scss_file)) {
		$scss_time = filemtime($scss_file);
		$css_time = file_exists($css_file) ? filemtime($css_file) : 0;

		// Si SCSS est plus récent ou si CSS n'existe pas
		if ($scss_time > $css_time) {
			// Essayer de compiler avec Sass si disponible
			if (function_exists('exec') && !empty(shell_exec('which sass'))) {
				$command = sprintf(
					'sass %s:%s --style expanded 2>&1',
					escapeshellarg($scss_file),
					escapeshellarg($css_file)
				);

				$output = shell_exec($command);

				// Log en cas d'erreur
				if (strpos($output, 'Error') !== false) {
					error_log('SCSS Compilation Error (' . $scss_path . '): ' . $output);
				}
			}
		}
	}
}

// Compiler SCSS au chargement de l'admin et du front-end
add_action('init', 'mars_compile_scss');
