<?php

/**
 * Block Name: Derniers Articles
 * Description: Affiche les derniers articles du blog dans des cartes
 */

// Créer un id unique pour ce bloc
$id = 'recent-posts-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Créer le nom de classe
$className = 'recent-posts-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Check preview mode
$is_preview = isset($is_preview) ? $is_preview : false;

$eyebrow = get_field('eyebrow');
$title = get_field('title');
$posts_per_page = get_field('posts_per_page') ?: 4;

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => $posts_per_page,
	'post_status'    => 'publish',
);

$posts_query = new WP_Query($args);

?>

<?php if ($is_preview): ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title ?: __('Derniers articles', 'mars')); ?></h3>
		<p><?php _e('Ceci est un bloc affichant les derniers articles du blog.', 'mars'); ?></p>
		<?php if ($posts_query->have_posts()): ?>
			<p><?php _e('Nombre d\'articles affichés : ', 'mars'); ?> <?php echo esc_attr($posts_query->post_count); ?></p>
		<?php else: ?>
			<p><?php _e('Aucun article trouvé.', 'mars'); ?></p>
		<?php endif; ?>
	</div>
<?php else: ?>
	<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<?php if ($eyebrow || $title): ?>
			<div class="section-title">
				<?php if ($eyebrow): ?>
					<span class="tag"><?php echo esc_html($eyebrow); ?></span>
				<?php endif; ?>
				<?php if ($title): ?>
					<h2><?php echo esc_html($title); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ($posts_query->have_posts()): ?>
			<div class="recent-posts-grid">
				<?php while ($posts_query->have_posts()): $posts_query->the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="recent-post-card">
						<h3 class="recent-post-card__title no-animation"><?php the_title(); ?></h3>
						<div class="recent-post-card__excerpt no-animation">
							<?php
							if (has_excerpt()) {
								echo wp_kses_post(wp_trim_words(get_the_excerpt(), 30));
							} else {
								echo wp_kses_post(wp_trim_words(get_the_content(), 30));
							}
							?>
						</div>
						<div class="recent-post-card__date"><?php echo esc_html(get_the_date()); ?></div>
					</a>
				<?php endwhile;
				wp_reset_postdata(); ?>
			</div>
		<?php else: ?>
			<p class="recent-posts-block__empty"><?php _e('Aucun article à afficher.', 'mars'); ?></p>
		<?php endif; ?>
	</div>
<?php endif; ?>
