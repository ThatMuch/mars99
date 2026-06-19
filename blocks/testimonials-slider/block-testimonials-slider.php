<?php

/**
 * Testimonials Slider Block Template.
 */

$block_id = 'testimonials-slider-' . (isset($block['id']) ? $block['id'] : uniqid());
if (!empty($block['anchor'])) {
	$block_id = $block['anchor'];
}

$anchor     = 'id="' . esc_attr($block_id) . '" ';
$class_name = 'testimonials-slider-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Paramètres communs
$title       = get_field('title') ?: 'Témoignages de nos clients';
$subtitle    = get_field('subtitle');
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

	if (!empty($reviews_data) && empty($reviews_data['error']) && !empty($reviews_data['reviews'])) {
		$reviews_data['reviews'] = array_values(array_filter($reviews_data['reviews'], function ($r) {
			return !empty(trim($r['text']));
		}));
	}

	$has_items    = !empty($reviews_data) && empty($reviews_data['error']) && !empty($reviews_data['reviews']);
	$google_error = !empty($reviews_data['error']) ? $reviews_data['message'] : '';

} else {

	$selection_type   = get_field('selection_type') ?: 'all';
	$specific_cat     = get_field('specific_category');
	$image            = get_field('image');
	$header_alignment = get_field('header_alignment') ?: 'right';

	$args = array(
		'post_type'      => 'testimonials',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	if ($selection_type === 'specific_category' && !empty($specific_cat)) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'testimonial-category',
				'field'    => 'term_id',
				'terms'    => $specific_cat,
			),
		);
	}

	$testimonials = new WP_Query($args);
	$has_items    = $testimonials->have_posts();
}

?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php esc_html_e('Aperçu du bloc Témoignages', 'mars'); ?></p>
	</div>
<?php else : ?>

	<?php if ($has_items) : ?>
		<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>" data-block-id="<?php echo esc_attr($block_id); ?>">
			<div class="container">

				<?php if ($source_type === 'custom_posts') : ?>
					<div class="testimonials-slider-block-header align-<?php echo esc_attr($header_alignment ?? 'right'); ?>">
						<div class="section-title">
							<?php if ($subtitle) : ?>
								<span class="section--subtitle"><?php echo esc_html($subtitle); ?></span>
							<?php endif; ?>
							<?php if ($title) : ?>
								<h2><?php echo esc_html($title); ?></h2>
							<?php endif; ?>
						</div>
						<?php if (!empty($image)) : ?>
							<img src="<?php echo esc_url(wp_get_attachment_image_url($image, 'full')); ?>"
								alt="<?php echo esc_attr(get_post_meta($image, '_wp_attachment_image_alt', true)); ?>"
								class="img-fluid testimonial-section-image"
								loading="lazy" />
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="testimonials-slider-block-header">
						<div class="section-title">
							<?php if ($subtitle) : ?>
								<span class="section--subtitle"><?php echo esc_html($subtitle); ?></span>
							<?php endif; ?>
							<?php if ($title) : ?>
								<h2><?php echo esc_html($title); ?></h2>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="testimonials-slider swiper">
					<div class="swiper-wrapper">

						<?php if ($source_type === 'google_reviews') : ?>

							<?php foreach ($reviews_data['reviews'] as $review) : ?>
								<div class="swiper-slide">
									<div class="testimonial-card card google-review-card">
										<div class="border"></div>
										<div class="testimonial-header">
											<div class="review-author-info">
												<span class="author-name"><?php echo esc_html($review['author']); ?></span>
												<span class="rating-google">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/blocks/google-reviews/icon-google.svg" alt="Logo Google" loading="lazy">
												</span>
											</div>
										</div>
										<div class="testimonial-content">
											<?php echo nl2br(esc_html($review['text'])); ?>
										</div>
										<div class="review-rating">
											<?php for ($i = 1; $i <= 5; $i++) : ?>
												<i class="<?php echo $i <= $review['rating'] ? 'fas' : 'far'; ?> fa-star"></i>
											<?php endfor; ?>
										</div>
										<div class="review-footer">
											<span class="review-date"><?php echo esc_html($review['relative_time']); ?></span>
										</div>
									</div>
								</div>
							<?php endforeach; ?>

						<?php else : ?>

							<?php while ($testimonials->have_posts()) : $testimonials->the_post();
								$categories     = get_the_terms(get_the_ID(), 'testimonial-category');
								$category_class = '';
								if ($categories && !is_wp_error($categories)) {
									$category_class = esc_attr($categories[0]->slug) . '-card';
								}
							?>
								<div class="swiper-slide">
									<div class="testimonial-card card <?php echo esc_attr($category_class); ?>">
										<div class="border"></div>
										<?php if ($categories && !is_wp_error($categories)) :
											foreach ($categories as $category) : ?>
												<span class="testimonial-category mb-3"><?php echo esc_html($category->name); ?></span>
											<?php endforeach;
										endif; ?>
										<div class="testimonial-header">
											<?php if (has_post_thumbnail()) : ?>
												<div class="testimonial-logo">
													<?php the_post_thumbnail('medium'); ?>
												</div>
											<?php endif; ?>
										</div>
										<div class="testimonial-content">
											<?php the_content(); ?>
										</div>
									</div>
								</div>
							<?php endwhile;
							wp_reset_postdata(); ?>

						<?php endif; ?>

					</div>
					<!-- Navigation -->
					<div class="d-flex justify-content-center align-items-center mt-4 gap-3">
						<div class="left btn btn--outline btn--icon"><i class="fa fa-chevron-left"></i></div>
						<div class="right btn btn--outline btn--icon"><i class="fa fa-chevron-right"></i></div>
					</div>
				</div>
			</div>
		</section>

	<?php elseif ($source_type === 'google_reviews' && !empty($google_error)) : ?>
		<div class="alert alert-warning">
			<p><?php _e('Erreur lors de la récupération des avis Google :', 'mars'); ?> <?php echo esc_html($google_error); ?></p>
		</div>
	<?php elseif ($source_type === 'google_reviews' && (!$place_id || !$api_key)) : ?>
		<div class="alert alert-info">
			<p><?php _e('Veuillez configurer votre Place ID et votre clé API Google dans les réglages du bloc.', 'mars'); ?></p>
		</div>
	<?php else : ?>
		<div class="alert alert-warning">
			<?php _e('Aucun témoignage trouvé.', 'mars'); ?>
		</div>
	<?php endif; ?>

<?php endif; ?>
