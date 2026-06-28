<?php

/**
 * Block Name: Card
 * Description: Carte simple avec image, titre et description
 */

// Créer un id unique pour ce bloc
$id = 'card-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Créer le nom de classe
$className = 'card-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Récupérer les champs
$icon = get_field('icon');
$title = get_field('title');
$description = get_field('description');
$excerpt = get_field('excerpt');
$style = get_field('style');
$format = get_field('format');
// Mode preview avec données factices

?>
<?php if ($is_preview) : ?>
	<div id="<?php echo esc_attr($id); ?>" class="block-preview-message">
		<div class="cta-content">
			<?php if ($icon): ?>
				<div class="card-icon">
					<i class="<?php echo esc_attr($icon); ?>" aria-hidden="true"></i>
				</div>
			<?php endif; ?>

			<h2 class="cta-title h1"><?php echo esc_html($title); ?></h2>
			<p class="cta-description"><?php echo wp_kses_post($description); ?></p>
		</div>
	</div>
<?php elseif (!empty($title) || !empty($description) || !empty($link_url)) : ?>
	<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> card-block-<?php echo esc_attr($style); ?> <?php echo ($format === 'list') ? 'list' : ''; ?>">

		<?php if ($icon): ?>
			<div class="card-icon">
				<i class="<?php echo esc_attr($icon); ?>" aria-hidden="true"></i>
			</div>
		<?php endif; ?>
		<div>
			<?php if ($title): ?>
				<h4 class="card-title"><?php echo wp_kses_post($title); ?></h4>
			<?php endif; ?>
			<?php if ($description): ?>
				<div class="card-description">
					<?php echo wp_kses_post($description); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
