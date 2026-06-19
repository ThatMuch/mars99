<?php

/**
 * Testimonials Block Template.
 */

$block_id = 'testimonials-' . (isset($block['id']) ? $block['id'] : uniqid());
if (!empty($block['anchor'])) {
	$block_id = $block['anchor'];
}

$anchor     = 'id="' . esc_attr($block_id) . '" ';
$class_name = 'testimonials-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Paramètres communs
$title       = get_field('title') ?: 'Ce que nos clients disent de nous';
$subtitle    = get_field('subtitle') ?: 'Témoignages';
$show_title  = get_field('show_title') !== false;
$source_type = get_field('source_type') ?: 'custom_posts';

// ── Récupération des données selon la source ────────────────────────────────

if ($source_type === 'google_reviews') {

	$place_id   = get_field('google_place_id');
	$api_key    = get_field('google_api_key');
	$min_rating = get_field('google_min_rating') ?: 4;
	$g_count    = get_field('google_count');
	$cache_time = get_field('google_cache_time') ?: 24;
	$g_count    = (!empty($g_count) && is_numeric($g_count) && $g_count > 0) ? intval($g_count) : null;

	$reviews_data = array();
	if ($place_id && $api_key) {
		$reviews_data = mars_get_google_reviews($place_id, $api_key, $g_count, $min_rating, $cache_time);
	}

	// Filtrer les avis sans texte
	if (!empty($reviews_data) && empty($reviews_data['error']) && !empty($reviews_data['reviews'])) {
		$reviews_data['reviews'] = array_values(array_filter($reviews_data['reviews'], function ($r) {
			return !empty(trim($r['text']));
		}));
	}

	$has_items    = !empty($reviews_data) && empty($reviews_data['error']) && !empty($reviews_data['reviews']);
	$google_error = !empty($reviews_data['error']) ? $reviews_data['message'] : '';
} else {

	// Source : CPT temoignages
	$count          = get_field('count') ?: -1;
	$selection_type = get_field('selection_type') ?: 'all';
	$specific_cat   = get_field('specific_category');

	$args = array(
		'post_type'      => 'temoignages',
		'posts_per_page' => $count,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	if ($selection_type === 'category' && !empty($specific_cat)) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'temoignage_category',
				'field'    => 'term_id',
				'terms'    => $specific_cat,
			),
		);
	}

	$testimonials_query = new WP_Query($args);
	$has_items          = $testimonials_query->have_posts();
}

?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php _e('Aperçu du bloc Témoignages.', 'mars'); ?></p>
	</div>
<?php else : ?>

	<section <?php echo $anchor; ?>class="<?php echo esc_attr($class_name); ?> section section-testimonials" data-block-id="<?php echo esc_attr($block_id); ?>">
		<div class="container">
			<div class="row justify-content-between mb-4">
				<div class="col col-md-7">
					<div class="d-flex gap-4">
						<div>
							<?php if ($show_title) : ?>
								<?php if ($subtitle) : ?>
									<span class="tag"><?php echo esc_html($subtitle); ?></span>
								<?php endif; ?>
								<?php if ($title) : ?>
									<h2 class="section--title"><?php echo esc_html($title); ?></h2>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php if (!empty($reviews_data) && !$reviews_data['error']) : ?>
					<div class="rating-summary col col-md-3">
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
								$user_ratings_total = 0;
								if (isset($reviews_data['user_ratings_total']) && $reviews_data['user_ratings_total'] > 0) {
									$user_ratings_total = $reviews_data['user_ratings_total'];
								} else {
									// Fallback: utiliser le nombre d'avis récupérés
									$user_ratings_total = count($reviews_data['reviews']);
								}

								echo $user_ratings_total;
								?>
								avis Google <i class="ml-1 fa-solid fa-arrow-up-right-from-square"></i>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<?php if ($has_items) : ?>
				<div class="testimonials-summary">
					<div class="mars-carousel testimonials-carousel">
						<div class="mars-carousel-track">

							<?php if ($source_type === 'google_reviews') : ?>
								<?php foreach ($reviews_data['reviews'] as $review) :
									$long_text = mb_strlen($review['text']) > 200;
								?>
									<div class="mars-carousel-slide ">
										<div class="testimonial-review-card card testimonial-modal-trigger">
											<div class="review-content">
												<div class="review-header">
													<div class="review-author">
														<div>
															<h3 class="author-name h4"><?php echo esc_html($review['author']); ?></h3>

														</div>
														<span class="rating-google">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/blocks/google-reviews/icon-google.svg" alt="Logo Google" loading="lazy">
														</span>
													</div>

												</div>
												<div class="review-text">
													<?php echo esc_html($long_text ? mb_substr($review['text'], 0, 200) . '…' : $review['text']); ?>
												</div>
												<div class="review-rating">
													<?php for ($i = 1; $i <= 5; $i++) : ?>
														<i class="<?php echo $i <= $review['rating'] ? 'fas' : 'far'; ?> fa-star"></i>
													<?php endfor; ?>
												</div>
											</div>

											<div class="review-footer">
												<span class="review-date"><?php echo esc_html($review['relative_time']); ?></span>
											</div>


											<div class="testimonial-full-content" style="display:none;" aria-hidden="true">
												<div class="modal-author"><?php echo esc_html($review['author']); ?></div>
												<div class="modal-profession"></div>
												<div class="modal-date"><?php echo esc_html($review['relative_time']); ?></div>
												<div class="modal-rating"><?php echo intval($review['rating']); ?></div>
												<div class="modal-text"><?php echo nl2br(esc_html($review['text'])); ?></div>
												<div class="modal-promo"></div>
											</div>

										</div>
									</div>
								<?php endforeach; ?>

							<?php else : ?>

								<?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
									$profession = get_field('profession', get_the_ID());
									$promo      = get_field('promo', get_the_ID()) ?: '2026';
									$note       = get_field('note', get_the_ID());
								?>
									<div class="mars-carousel-slide">
										<div class="testimonial-review-card card testimonial-modal-trigger">
											<div class="review-content">

												<div class="review-header">
													<div class="review-author">
														<div>
															<h3 class="author-name h4"><?php the_title(); ?></h3>
															<?php if ($profession) : ?>
																<span class="author-profession"><?php echo esc_html($profession); ?></span>
															<?php endif; ?>
														</div>
													</div>

												</div>
												<div class="review-text">
													<?php echo get_the_excerpt(); ?>
												</div>
											</div>
											<div class="review-footer">
												<?php if ($promo) : ?>
													Promo <span class="author-promo"><?php echo esc_html($promo); ?></span>
												<?php endif; ?>
											</div>

											<div class="testimonial-full-content" style="display:none;" aria-hidden="true">
												<div class="modal-author"><?php the_title(); ?></div>
												<div class="modal-profession"><?php echo $profession ? esc_html($profession) : ''; ?></div>
												<div class="modal-date"><?php echo get_the_date(); ?></div>
												<div class="modal-rating"><?php echo esc_html($note); ?></div>
												<div class="modal-text"><?php the_content(); ?></div>
												<div class="modal-promo"><?php echo $promo ? esc_html($promo) : ''; ?></div>
											</div>
										</div>
									</div>
								<?php endwhile;
								wp_reset_postdata(); ?>

							<?php endif; ?>

						</div>
						<!-- Navigation -->
						<div class="mars-carousel-controls">
							<button class="mars-carousel-prev btn btn--outline btn--icon" aria-label="<?php _e('Précédent', 'mars'); ?>"><i class="fa fa-chevron-left"></i></button>
							<button class="mars-carousel-next btn btn--outline btn--icon" aria-label="<?php _e('Suivant', 'mars'); ?>"><i class="fa fa-chevron-right"></i></button>
						</div>
					</div>
				</div>

			<?php elseif ($source_type === 'google_reviews' && $google_error) : ?>
				<div class="alert alert-warning">
					<p><?php _e('Erreur lors de la récupération des avis Google :', 'mars'); ?> <?php echo esc_html($google_error); ?></p>
				</div>
			<?php elseif ($source_type === 'google_reviews' && (!$place_id || !$api_key)) : ?>
				<div class="alert alert-info">
					<p><?php _e('Veuillez configurer votre Place ID et votre clé API Google dans les réglages du bloc.', 'mars'); ?></p>
				</div>
			<?php else : ?>
				<div class="alert alert-warning">
					<p><?php _e('Aucun témoignage trouvé.', 'mars'); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
