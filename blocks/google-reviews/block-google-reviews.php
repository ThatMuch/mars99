<?php

/**
 * Google Reviews Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or its parent block.
 */

// Créer un id unique pour ce bloc
$block_id = 'google-reviews-' . (isset($block['id']) ? $block['id'] : uniqid());
if (!empty($block['anchor'])) {
	$block_id = $block['anchor'];
}

// Support custom "anchor" values.
$anchor = 'id="' . esc_attr($block_id) . '" ';

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'google-reviews-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Ce que nos clients disent de nous';
$subtitle = get_field('subtitle') ?: 'Avis Google';
$show_title = get_field('show_title') !== false;
$place_id = get_field('place_id');
$api_key = get_field('api_key');
$reviews_count = get_field('reviews_count');
$min_rating = get_field('min_rating') ?: 1;
$cache_time = get_field('cache_time') ?: 24;
$image = get_field('image');

// Déterminer si on limite le nombre d'avis
$limit_count = (!empty($reviews_count) && is_numeric($reviews_count) && $reviews_count > 0) ? intval($reviews_count) : null;

// Récupérer les avis
$reviews_data = array();
if ($place_id && $api_key) {
	$reviews_data = abyssenergy_get_google_reviews($place_id, $api_key, $limit_count, $min_rating, $cache_time);
}

// Filtrer les avis pour ne garder que ceux qui ont du texte
if (!empty($reviews_data) && !$reviews_data['error'] && !empty($reviews_data['reviews'])) {
	$reviews_data['reviews'] = array_filter($reviews_data['reviews'], function ($review) {
		return !empty($review['text']) && trim($review['text']) !== '';
	});
	// Réindexer le tableau pour éviter les problèmes
	$reviews_data['reviews'] = array_values($reviews_data['reviews']);
}

// The total number of reviews ever fetched from Google (not just the displayed ones)
$allReviewsCount = 0;
if (!empty($reviews_data) && !$reviews_data['error']) {
	$allReviewsCount = count($reviews_data['all_reviews']);
}

// Message informatif pour l'admin
$admin_info = '';
if ($is_preview && !empty($reviews_data) && !$reviews_data['error']) {
	$total_reviews = count($reviews_data['all_reviews'] ?? $reviews_data['reviews']);
	$displayed_reviews = count($reviews_data['reviews']);
	$user_ratings_total = isset($reviews_data['user_ratings_total']) && $reviews_data['user_ratings_total'] > 0
		? $reviews_data['user_ratings_total']
		: $total_reviews;

	if ($limit_count && $total_reviews > $limit_count) {
		$admin_info = sprintf(
			__('Affichage limité à %d avis sur %d récupérés (%d avis total sur Google).', 'abyssenergy'),
			$displayed_reviews,
			$total_reviews,
			$user_ratings_total
		);
	} elseif (!$limit_count) {
		$admin_info = sprintf(
			__('Affichage de tous les avis récupérés (%d avis sur %d total sur Google).', 'abyssenergy'),
			$displayed_reviews,
			$user_ratings_total
		);
	}
	// Ajouter info sur limitation Google
	if ($total_reviews >= 5) {
		$admin_info .= ' ' . __('Note: L\'API Google ne retourne que 5 avis maximum.', 'abyssenergy');
	}
}

?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php _e('Aperçu du bloc d\'avis Google. Les avis réels s\'afficheront sur le site.', 'abyssenergy'); ?></p>
		<?php if ($admin_info) : ?>
			<div class="block-preview-info alert alert-info">
				<small><?php echo esc_html($admin_info); ?></small>
			</div>
		<?php endif; ?>
	</div>
<?php else : ?>

	<?php if ($admin_info && current_user_can('edit_posts')) : ?>
		<div class="block-preview-info alert alert-info">
			<small><?php echo esc_html($admin_info); ?></small>
		</div>
	<?php endif; ?>

	<!-- Google Reviews Block -->
	<section <?php echo $anchor; ?>class="<?php echo esc_attr($class_name); ?> section section-reviews" data-block-id="<?php echo esc_attr($block_id); ?>">
		<div class="container">
			<div class="row justify-content-between mb-4">
				<div class="col col-md-7">
					<div class="d-flex gap-4">
						<?php if ($image) : ?>
							<div class="section--image">
								<?php echo wp_get_attachment_image($image, 'medium', false, array('loading' => 'lazy')); ?>
							</div>
						<?php endif; ?>
						<div>
							<?php if ($show_title) : ?>
								<?php if ($subtitle) : ?>
									<span class="section--subtitle"><?php echo esc_html($subtitle); ?></span>
								<?php endif; ?>
								<?php if ($title) : ?>
									<h2 class="section--title"><?php echo esc_html($title); ?></h2>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php if (!empty($reviews_data) && !$reviews_data['error']) : ?>
					<div class="rating-summary col col-md-2">
						<div class="average-rating">
							<span class="rating-value"><?php echo number_format($reviews_data['rating'], 1); ?></span>
							<div class="rating-stars">
								<?php
								$rating = $reviews_data['rating'];
								for ($i = 1; $i <= 5; $i++) {
									if ($i <= $rating) {
										echo '<i class="fas fa-star"></i>';
									} elseif ($i - 0.5 <= $rating) {
										echo '<i class="fas fa-star-half-alt"></i>';
									} else {
										echo '<i class="far fa-star"></i>';
									}
								}
								?>
							</div>
							<a href="<?php echo esc_url($reviews_data['url'] ?? '#'); ?>" target="_blank" class="based-on">
								<?php
								// Debug complet des données
								echo "<!-- DEBUG: ";
								echo "reviews_data keys: " . implode(', ', array_keys($reviews_data));
								echo " | user_ratings_total exists: " . (array_key_exists('user_ratings_total', $reviews_data) ? 'YES' : 'NO');
								echo " | user_ratings_total value: " . var_export($reviews_data['user_ratings_total'] ?? 'NOT_SET', true);
								echo " | reviews count: " . count($reviews_data['reviews']);
								echo " -->";

								$user_ratings_total = 0;
								if (isset($reviews_data['user_ratings_total']) && $reviews_data['user_ratings_total'] > 0) {
									$user_ratings_total = $reviews_data['user_ratings_total'];
									echo "<!-- Using user_ratings_total: $user_ratings_total -->";
								} else {
									// Fallback: utiliser le nombre d'avis récupérés
									$user_ratings_total = count($reviews_data['reviews']);
									echo "<!-- Using fallback count: $user_ratings_total -->";
								}
								echo sprintf(__('Based on %d reviews', 'abyssenergy'), $user_ratings_total);
								?>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<?php if (!empty($reviews_data) && !$reviews_data['error']) : ?>
				<div class="google-reviews-summary">
					<div class="google-reviews-slider swiper-container">
						<div class="swiper-wrapper">
							<?php foreach ($reviews_data['reviews'] as $review) : ?>
								<div class="swiper-slide">
									<div class="review-card card">
										<div class="review-header">
											<div class="review-author">
												<h3 class="author-name h4"><?php echo esc_html($review['author']); ?></h3>
												<span class="rating-google"><img src="<?php echo get_stylesheet_directory_uri(); ?>/blocks/google-reviews/icon-google.svg" alt="Logo Google" loading="lazy"></span>
											</div>
										</div>
										<div class="review-text">
											<?php
											$text = esc_html($review['text']);
											echo strlen($text) > 200 ? substr($text, 0, 200) . '...' : $text;
											?>
										</div>
										<div class="review-rating">
											<div>
												<?php for ($i = 1; $i <= 5; $i++) : ?>
													<?php if ($i <= $review['rating']) : ?>
														<i class="fas fa-star"></i>
													<?php else : ?>
														<i class="far fa-star"></i>
													<?php endif; ?>
												<?php endfor; ?>
											</div>
											<span class="icon-verified"></span>
										</div>

										<div class="review-footer">
											Posted
											<div class="review-date">
												<?php echo esc_html($review['relative_time']); ?>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<!-- Navigation -->
						<div class="d-flex justify-content-center align-items-center mt-4 gap-3">
							<div class="left btn btn--outline btn--icon"><i class="fa fa-chevron-left"></i></div>
							<div class="right btn btn--outline btn--icon"><i class="fa fa-chevron-right"></i></div>
						</div>
					</div>
				</div>
			<?php elseif (!empty($reviews_data) && $reviews_data['error']) : ?>
				<div class="alert alert-warning">
					<p><?php _e('Erreur lors de la récupération des avis Google:', 'abyssenergy'); ?> <?php echo esc_html($reviews_data['message']); ?></p>
				</div>
			<?php else : ?>
				<div class="alert alert-info">
					<p><?php _e('Veuillez configurer votre ID de lieu Google et votre clé API pour afficher les avis.', 'abyssenergy'); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
