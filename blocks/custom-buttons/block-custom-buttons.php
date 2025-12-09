<?php

/**
 * Block Template: Custom Buttons
 *
 * Template pour le rendu du bloc boutons personnalisés
 *
 * @package Mars
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
	exit;
}

// Récupérer les attributs du bloc
$buttons = isset($attributes['buttons']) ? $attributes['buttons'] : array();
$alignment = isset($attributes['alignment']) ? $attributes['alignment'] : 'left';
$spacing = isset($attributes['spacing']) ? $attributes['spacing'] : 'normal';

// Si pas de boutons, ne rien afficher
if (empty($buttons)) {
	return;
}

// Classes CSS pour le conteneur
$classes = array(
	'wp-block-mars-custom-buttons',
	'custom-buttons-group',
	'alignment-' . esc_attr($alignment),
	'spacing-' . esc_attr($spacing)
);

// Enqueue du style frontend si nécessaire
wp_enqueue_style('mars-custom-buttons-style');
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
	<?php foreach ($buttons as $button) :
		// Vérifier que le bouton a les données nécessaires
		if (empty($button['url']) || empty($button['text'])) {
			continue;
		}

		// Préparer les classes CSS du bouton
		$button_classes = array('custom-button');
		$button_style = isset($button['style']) ? $button['style'] : 'primary';
		$button_classes[] = 'btn--' . esc_attr($button_style);

		// Récupérer les propriétés du bouton
		$url = esc_url($button['url']);
		$text = esc_html($button['text']);
		$icon = isset($button['icon']) ? esc_attr($button['icon']) : '';
		$download = isset($button['download']) && $button['download'];
		$target = isset($button['target']) ? esc_attr($button['target']) : '_self';

		// Attributs HTML du lien
		$link_attributes = array();
		$link_attributes[] = 'href="' . $url . '"';
		$link_attributes[] = 'class="' . esc_attr(implode(' ', $button_classes)) . '"';

		if ($target === '_blank') {
			$link_attributes[] = 'target="_blank"';
			$link_attributes[] = 'rel="noopener noreferrer"';
		}

		if ($download) {
			$link_attributes[] = 'download';
		}
	?>
		<a <?php echo implode(' ', $link_attributes); ?>>
			<?php if ($icon) : ?>
				<i class="<?php echo esc_attr($icon); ?>" aria-hidden="true"></i>
			<?php endif; ?>
			<span class="button-text"><?php echo $text; ?></span>
		</a>
	<?php endforeach; ?>
</div>
