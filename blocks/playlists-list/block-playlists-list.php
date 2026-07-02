<?php

/**
 * Block Name: Liste des Playlists
 *
 * This is the template that displays the playlists list block.
 */

// get block attributes
$count = get_field('count') ?: -1;

$args = array(
	'post_type' => 'palytlist',
	'posts_per_page' => $count,
	'post_status' => 'publish',
	'orderby' => 'date',
	'order' => 'DESC',
);

$playlists = new WP_Query($args);

$id = 'playlists-list-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

$className = 'playlists-list';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}
?>
<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<p><?php _e('Liste des Playlists', 'mars'); ?></p>
	</div>
<?php else : ?>
	<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="playlists-list__grid">
			<?php if ($playlists->have_posts()) : ?>
				<?php while ($playlists->have_posts()) : $playlists->the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="playlists-list__card">
						<div class="playlists-list__thumbnail">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium'); ?>
							<?php else : ?>
								<div class="playlists-list__thumbnail-placeholder"></div>
							<?php endif; ?>
						</div>
						<div class="playlists-list__content">
							<h3 class="playlists-list__title"><?php the_title(); ?></h3>
							<div class="playlists-list__excerpt">
								<?php
								if (has_excerpt()) {
									echo wp_trim_words(get_the_excerpt(), 20);
								} else {
									echo wp_trim_words(get_the_content(), 20);
								}
								?>
							</div>
							<span class="playlists-list__count mt-2">
								<?php
								echo mars_get_playlist_track_count(get_the_ID()) . ' ' . __('exercices', 'mars');
								?>
							</span>
						</div>
						<div class="playlists-list__action">
							<span class="playlists-list__icon-btn">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M4 6H20M4 12H20M4 18H14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
									<path d="M18 15L21 18L18 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>
						</div>
					</a>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<p><?php _e('Aucune playlist trouvée.', 'mars'); ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
