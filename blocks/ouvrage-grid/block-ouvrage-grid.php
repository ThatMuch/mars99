<?php

/**
 * Block Name: Ouvrage Grid
 * Description: Affiche une grille des ouvrages
 */

// Créer un id unique pour ce bloc
$id = 'ouvrage-grid-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Créer le nom de classe
$className = 'ouvrage-grid-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Check preview mode
$is_preview = isset($is_preview) ? $is_preview : false;

$posts_per_page = get_field('posts_per_page') ?: -1;

$args = array(
	'post_type'      => 'ouvrage',
	'posts_per_page' => $posts_per_page,
	'post_status'    => 'publish',
);

$ouvrages_query = new WP_Query($args);

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<?php if ($ouvrages_query->have_posts()): ?>
		<div class="ouvrage-grid">
			<?php while ($ouvrages_query->have_posts()): $ouvrages_query->the_post();
				$auteur = get_field('auteur');
				$description = get_field('description');
				$lien = get_field('lien');
			?>
				<div class="ouvrage-card">
					<div class="ouvrage-card__image-wrapper">
						<?php if (has_post_thumbnail()): ?>
							<?php the_post_thumbnail('large', ['class' => 'ouvrage-card__image', 'loading' => 'lazy']); ?>
						<?php endif; ?>

						<?php if ($lien): ?>
							<a href="<?php echo esc_url($lien); ?>" class="ouvrage-card__cart-btn" target="_blank" rel="noopener noreferrer" title="Acheter <?php echo esc_attr(get_the_title()); ?>">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="9" cy="21" r="1"></circle>
									<circle cx="20" cy="21" r="1"></circle>
									<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
								</svg>
							</a>
						<?php endif; ?>
					</div>
					<div class="ouvrage-card__content">
						<?php if ($auteur): ?>
							<div class="ouvrage-card__auteur"><?php echo esc_html($auteur); ?></div>
						<?php endif; ?>

						<h3 class="ouvrage-card__title"><?php the_title(); ?></h3>

						<?php if ($description): ?>
							<div class="ouvrage-card__description">
								<?php echo wp_kses_post($description); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endwhile;
			wp_reset_postdata(); ?>
		</div>
	<?php elseif ($is_preview): ?>
		<p class="ouvrage-grid__empty"><?php _e('Mode aperçu : Aucun ouvrage trouvé.', 'mars'); ?></p>
	<?php else: ?>
		<p class="ouvrage-grid__empty"><?php _e('Aucun ouvrage trouvé.', 'mars'); ?></p>
	<?php endif; ?>
</div>
