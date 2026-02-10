<?php

/**
 * Block Name: CTA
 * Description: Call to Action Block
 */

// Créer un id unique pour ce bloc
$id = 'cta-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Créer le nom de classe
$className = 'cta-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Récupérer les champs
$theme = get_field('theme') ?: 'primary';
$className .= ' theme-' . $theme;

$icon = get_field('icon');
$title = get_field('title');
$description = get_field('description');
$link = get_field('link');

// Mode preview avec données factices
if ($is_preview && empty($title)) {
	$title = 'Titre du Call to Action';
	$description = 'Ceci est une description exemple pour le bloc Call to Action.';
	$link = array(
		'title' => 'Bouton d\'action',
		'url' => '#',
		'target' => ''
	);
	// Optional: Add a placeholder icon if needed
}

// Build link attributes if link exists
$link_url = $link['url'] ?? '';
$link_title = $link['title'] ?? 'En savoir plus';
$link_target = $link['target'] ?? '_self';

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="cta-content">
		<?php if ($icon): ?>
			<div class="cta-icon">
				<img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>" class="no-animation" loading="lazy">
			</div>
		<?php endif; ?>
		<?php if ($title): ?>
			<h2 class="cta-title h1"><?php echo wp_kses_post($title); ?></h2>
		<?php endif; ?>

		<?php if ($description): ?>
			<div class="cta-description">
				<?php echo wp_kses_post($description); ?>
			</div>
		<?php endif; ?>

		<?php if ($link_url): ?>
			<a href="<?php echo esc_url($link_url); ?>" class="btn btn-<?php echo esc_attr($theme); ?>" target="<?php echo esc_attr($link_target); ?>">
				<span class="btn__content"><?php echo esc_html($link_title); ?></span>
				<span class="btn__overlay"></span>

			</a>
		<?php endif; ?>
	</div>
</div>
