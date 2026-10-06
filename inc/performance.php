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
 * Désactiver les emojis natifs de WordPress (script de détection + styles
 * inline + DNS prefetch) : le site n'en a pas l'usage et ça ajoute une
 * requête JS + un bloc de style inline sur chaque page.
 */
function mars_disable_wp_emojis()
{
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

	add_filter('tiny_mce_plugins', 'mars_disable_emojis_tinymce');
	add_filter('wp_resource_hints', 'mars_disable_emojis_dns_prefetch', 10, 2);
}
add_action('init', 'mars_disable_wp_emojis');

function mars_disable_emojis_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	}

	return array();
}

function mars_disable_emojis_dns_prefetch($urls, $relation_type)
{
	if ('dns-prefetch' === $relation_type) {
		$urls = array_diff($urls, array('//s.w.org'));
	}

	return $urls;
}

/**
 * Les icônes codées en dur dans les templates (chevrons, "+", étoiles) sont
 * désormais des SVG inline (voir inc/icons.php). Il reste deux sources
 * d'icônes au choix libre de l'éditeur, qui dépendent de Font Awesome et ne
 * peuvent pas être couvertes par un jeu fixe de SVG : l'icône du bouton de
 * header (Customizer, texte libre) et le champ ACF "icon" du bloc Card.
 * On ne charge donc Font Awesome que si la page en a effectivement besoin.
 */
function mars_page_needs_font_awesome()
{
	$locations = get_nav_menu_locations();
	$menu_id = isset($locations['main-menu']) ? $locations['main-menu'] : 0;

	if ($menu_id && get_theme_mod('header_btn_icon_' . $menu_id, '')) {
		return true;
	}

	global $post;
	if (function_exists('has_block') && $post instanceof WP_Post && has_block('acf/card', $post)) {
		return true;
	}

	return false;
}

/**
 * Charger le widget d'accessibilité à la demande plutôt qu'au chargement de
 * la page : son script (363 Ko) était parser-blocking dans le <body> et
 * retardait la découverte des images (donc le LCP).
 *
 * Le bouton du widget n'existe pas dans le HTML servi par le serveur : il
 * est créé par widget.min.js lui-même au runtime (vérifié sur le HTML rendu
 * en production, aucune balise du widget n'est présente avant son script).
 * Il n'y a donc rien sur quoi brancher un vrai "au premier clic sur CE
 * bouton" sans reproduire l'UI du plugin nous-mêmes (fragile, risque de
 * désynchronisation si le plugin change). À la place, le script n'est
 * exécuté qu'à la première interaction utilisateur sur la page (clic,
 * touche, scroll, tap), avec un filet de sécurité après 5 s d'inactivité
 * pour ne jamais priver un visiteur qui n'interagit pas (ex. lecteur
 * d'écran qui lit la page sans bouger) d'un outil d'accessibilité.
 *
 * Le plugin imprime son <script> directement dans le HTML plutôt que de
 * l'enregistrer via wp_enqueue_script() (il ne fait que reproduire la
 * convention de nommage id="{handle}-js" de WordPress), donc
 * script_loader_tag ne s'applique jamais à son tag : on agit directement
 * sur le buffer de sortie final.
 */
function mars_lazyload_accessibility_widget_buffer($html)
{
	if (false === strpos($html, 'accessibility-widget') || false === strpos($html, 'widget.min.js')) {
		return $html;
	}

	return preg_replace_callback(
		'#<script\b([^>]*)\bsrc=(["\'])([^"\']*accessibility-widget[^"\']*widget\.min\.js[^"\']*)\2([^>]*)>#i',
		function ($matches) {
			$before = trim(preg_replace('/\s+(defer|async)\b/i', '', $matches[1]));
			$after  = trim(preg_replace('/\s+(defer|async)\b/i', '', $matches[4]));
			$attrs  = trim($before . ' ' . $after);

			return sprintf(
				'<script type="text/plain" data-mars-lazy-src="%s"%s>',
				esc_attr($matches[3]),
				$attrs ? ' ' . $attrs : ''
			);
		},
		$html
	);
}

function mars_start_accessibility_widget_buffer()
{
	if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
		return;
	}

	ob_start('mars_lazyload_accessibility_widget_buffer');
}
add_action('template_redirect', 'mars_start_accessibility_widget_buffer', 0);

/**
 * Script qui charge réellement les <script type="text/plain" data-mars-lazy-src>
 * à la première interaction (voir js/lazy-third-party.js).
 */
function mars_enqueue_lazy_third_party_loader()
{
	if (is_admin()) {
		return;
	}

	wp_enqueue_script(
		'mars-lazy-third-party',
		get_stylesheet_directory_uri() . '/js/lazy-third-party.js',
		array(),
		mars_get_file_version('/js/lazy-third-party.js'),
		array('strategy' => 'defer')
	);
}
add_action('wp_enqueue_scripts', 'mars_enqueue_lazy_third_party_loader');

/**
 * Exclure le logo du header du lazy-load (EWWW respecte la classe skip-lazy)
 * et de l'animation image-appear (qui le ferait partir d'opacity:0). Le logo
 * est rendu via the_custom_logo() -> wp_get_attachment_image(), donc on
 * l'ajoute via ce filtre plutôt qu'en manipulant le HTML final.
 *
 * Priorité 20 : passe après mars_add_lazy_loading_to_images() (functions.php,
 * priorité par défaut 10), qui ajoute loading="lazy" car son exclusion ne
 * reconnaît que la classe header__logo-image, pas custom-logo. On retire
 * donc cet attribut ici plutôt que de modifier ce filtre plus ancien.
 */
function mars_skip_lazy_for_logo($attr)
{
	if (isset($attr['class']) && false !== strpos($attr['class'], 'custom-logo')) {
		$attr['class'] = trim($attr['class'] . ' skip-lazy no-animation');
		unset($attr['loading']);
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
 * Dérive un intitulé lisible depuis le nom de fichier d'une URL, pour servir
 * de aria-label à un lien sans nom accessible (ex : "Certification-Qualianor-
 * IDYT-2.pdf" -> "Certification Qualianor IDYT 2 (PDF)").
 */
function mars_label_from_url($url)
{
	$path = wp_parse_url((string) $url, PHP_URL_PATH);
	if (!$path) {
		return '';
	}

	$filename = pathinfo($path, PATHINFO_FILENAME);
	$extension = strtoupper(pathinfo($path, PATHINFO_EXTENSION));

	$label = trim(preg_replace('/[-_]+/', ' ', $filename));
	if ('' === $label) {
		return '';
	}

	return $extension ? $label . ' (' . $extension . ')' : $label;
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
		'<!DOCTYPE html><html><head><meta charset="utf-8"></head><body><div id="mars-hero-root">' . $content . '</div></body></html>',
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
		mars_dom_add_class($img, 'no-animation');
		$img->removeAttribute('loading');

		$src = $img->getAttribute('src') . ' ' . $img->getAttribute('data-src');
		if (false !== strpos($src, 'Frame-156')) {
			$img->setAttribute('fetchpriority', 'high');
		}
	}

	// Liens sans nom accessible (ex : un lien qui ne contient qu'une image
	// avec alt="") : on en dérive un depuis l'URL de destination.
	foreach ($xpath->query('.//a', $hero) as $a) {
		if ($a->hasAttribute('aria-label') || $a->hasAttribute('title') || '' !== trim($a->textContent)) {
			continue;
		}

		$label = mars_label_from_url($a->getAttribute('href'));
		if ('' !== $label) {
			$a->setAttribute('aria-label', $label);
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

/**
 * Différer card.js (bloc ACF "Card"). Le handle est enregistré par ACF via
 * la clé 'enqueue_script' (simple URL), ce qui ne permet pas de passer un
 * tableau $args avec 'strategy' => 'defer' comme sur les scripts que le
 * thème enregistre lui-même : on passe donc par script_loader_tag.
 */
function mars_defer_card_block_script($tag, $handle)
{
	if ('block-acf-card' !== $handle || false !== strpos($tag, ' defer')) {
		return $tag;
	}

	return str_replace(' src=', ' defer src=', $tag);
}
add_filter('script_loader_tag', 'mars_defer_card_block_script', 10, 2);

/**
 * Le script multiselect.js (et son dépendant optgroup-handler.js) ne sert
 * qu'aux select multiples (voir les sélecteurs dans js/multiselect.js :
 * select[multiple], .abyss-multiselect). Gravity Forms n'est pas utilisé
 * sur ce site. On ne les charge donc que si la page contient réellement
 * un tel select.
 */
function mars_page_needs_multiselect()
{
	if (is_admin()) {
		return true;
	}

	global $post;

	return $post instanceof WP_Post
		&& (false !== strpos($post->post_content, 'abyss-multiselect')
			|| preg_match('/<select[^>]*\bmultiple\b/i', $post->post_content));
}

/**
 * dotlottie-wc (363 Ko depuis unpkg) n'est utile que si le contenu affiche
 * réellement un élément <dotlottie-wc>. Sur la home actuelle, ce n'est pas
 * le cas : le script était chargé pour rien sur chaque visite.
 */
function mars_page_needs_lottie()
{
	if (is_admin()) {
		return true;
	}

	global $post;

	return $post instanceof WP_Post && false !== strpos($post->post_content, 'dotlottie-wc');
}
