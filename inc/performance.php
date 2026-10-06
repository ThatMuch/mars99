<?php

/**
 * Optimisations de performance front-end (chargement des ressources)
 *
 * Ce fichier regroupe les ajustements liés à l'ordre/chargement des assets
 * (scripts tiers, polices, images du hero) sans toucher aux plugins.
 *
 * @package Mars
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Différer le widget d'accessibilité (plugin tiers) : son script de 363 Ko
 * est actuellement parser-blocking dans le <body> et retarde la découverte
 * des images (donc le LCP).
 */
function mars_defer_accessibility_widget_script($tag, $handle)
{
	if ('accessibility-widget-js' !== $handle) {
		return $tag;
	}

	if (false !== strpos($tag, ' defer')) {
		return $tag;
	}

	return str_replace(' src=', ' defer src=', $tag);
}
add_filter('script_loader_tag', 'mars_defer_accessibility_widget_script', 10, 2);
