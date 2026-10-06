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

/**
 * Exclure le logo du header du lazy-load (EWWW respecte la classe skip-lazy).
 * Le logo est rendu via the_custom_logo() -> wp_get_attachment_image(), donc
 * on l'ajoute via ce filtre plutôt qu'en manipulant le HTML final.
 */
function mars_skip_lazy_for_logo($attr)
{
	if (isset($attr['class']) && false !== strpos($attr['class'], 'custom-logo')) {
		$attr['class'] = trim($attr['class'] . ' skip-lazy');
	}

	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'mars_skip_lazy_for_logo', 20);

/**
 * Ajoute une classe CSS à un élément DOM sans dupliquer une classe existante.
 */
function mars_dom_add_class(DOMElement $element, $class)
{
	$classes = $element->hasAttribute('class')
		? preg_split('/\s+/', trim($element->getAttribute('class')))
		: array();

	if (!in_array($class, $classes, true)) {
		$classes[] = $class;
	}

	$element->setAttribute('class', trim(implode(' ', $classes)));
}

/**
 * Sur la page d'accueil, le hero (premier bloc du contenu : H1, sous-titre,
 * boutons, image Frame-156, logo Qualiopi) ne doit ni être animé (les
 * animations CSS text-appear/image-appear partent de opacity:0, ce qui
 * empêche Chrome de le retenir comme candidat LCP), ni être lazy-loadé.
 */
function mars_optimize_front_page_hero($content)
{
	if (is_admin() || !is_front_page() || false === strpos($content, '<img')) {
		return $content;
	}

	libxml_use_internal_errors(true);
	$dom = new DOMDocument();
	$loaded = $dom->loadHTML(
		'<!DOCTYPE html><html><body><div id="mars-hero-root">' . $content . '</div></body></html>',
		LIBXML_NOERROR | LIBXML_NOWARNING
	);
	libxml_clear_errors();

	if (!$loaded) {
		return $content;
	}

	$root = $dom->getElementById('mars-hero-root');
	if (!$root) {
		return $content;
	}

	// Le hero est le premier élément enfant du contenu.
	$hero = null;
	foreach ($root->childNodes as $node) {
		if (XML_ELEMENT_NODE === $node->nodeType) {
			$hero = $node;
			break;
		}
	}

	if (!$hero) {
		return $content;
	}

	$xpath = new DOMXPath($dom);

	foreach ($xpath->query('.//p | .//h2 | .//h3', $hero) as $el) {
		mars_dom_add_class($el, 'no-animation');
	}

	foreach ($xpath->query('.//img', $hero) as $img) {
		mars_dom_add_class($img, 'skip-lazy');
		$img->removeAttribute('loading');

		$src = $img->getAttribute('src') . ' ' . $img->getAttribute('data-src');
		if (false !== strpos($src, 'Frame-156')) {
			$img->setAttribute('fetchpriority', 'high');
		}
	}

	$html = '';
	foreach ($root->childNodes as $child) {
		$html .= $dom->saveHTML($child);
	}

	return $html;
}
add_filter('the_content', 'mars_optimize_front_page_hero', 20);

/**
 * Charger la police Cal Sans via une balise <link> dédiée plutôt que via un
 * @import dans style.min.css : un @import n'est découvert qu'après le
 * téléchargement + parsing complet du CSS qui le contient (ici ~2,6 s).
 */
function mars_enqueue_google_fonts()
{
	wp_enqueue_style(
		'mars-google-fonts',
		'https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap',
		array(),
		null
	);
}
add_action('wp_enqueue_scripts', 'mars_enqueue_google_fonts');

/**
 * Preconnect vers Google Fonts pour accélérer la résolution DNS/TLS avant
 * même que le navigateur ne découvre la feuille de style ci-dessus.
 */
function mars_google_fonts_preconnect($hints, $relation_type)
{
	if ('preconnect' !== $relation_type) {
		return $hints;
	}

	$hints[] = array('href' => 'https://fonts.googleapis.com');
	$hints[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous');

	return $hints;
}
add_filter('wp_resource_hints', 'mars_google_fonts_preconnect', 10, 2);
