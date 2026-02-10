<?php

/**
 * Testimonials Block Template.
 */

// Créer un id unique pour ce bloc
$block_id = 'testimonials-' . (isset($block['id']) ? $block['id'] : uniqid());
if (!empty($block['anchor'])) {
	$block_id = $block['anchor'];
}

// Support custom "anchor" values.
$anchor = 'id="' . esc_attr($block_id) . '" ';

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'testimonials-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Ce que nos clients disent de nous';
$subtitle = get_field('subtitle') ?: 'Témoignages';
$show_title = get_field('show_title') !== false;
$count = get_field('count') ?: -1;
$selection_type = get_field('selection_type') ?: 'all';
$specific_category = get_field('specific_category');

// Query Testimonials
$args = array(
	'post_type' => 'temoignages',
	'posts_per_page' => $count,
	'orderby' => 'date',
	'order' => 'DESC',
);

// If filtering by category
if ($selection_type === 'category' && !empty($specific_category)) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'temoignage_category',
			'field'    => 'term_id',
			'terms'    => $specific_category,
		),
	);
}

$testimonials_query = new WP_Query($args);
$has_testimonials = $testimonials_query->have_posts();


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

			</div>

			<?php if ($has_testimonials) : ?>
				<div class="testimonials-summary">
					<div class="mars-carousel testimonials-carousel">
						<div class="mars-carousel-track">
							<?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
								$profession = get_field('profession', get_the_ID());
								$promo = get_field('promo', get_the_ID()) ?: '2026';
							?>
								<div class="mars-carousel-slide">
									<div class="testimonial-review-card card">
										<div class="review-header">
											<div class="review-author">
												<div>
													<h3 class="author-name h4"><?php the_title(); ?></h3>
													<?php if ($profession) : ?>
														<span class="author-profession"><?php echo esc_html($profession); ?></span>
													<?php endif; ?>
												</div>
												<!-- Optional Icon -->
												<!-- <span class="rating-icon"><i class="dashicons dashicons-format-quote"></i></span> -->
											</div>
											<button class="btn btn__white btn--icon testimonial-modal-trigger" aria-label="<?php _e('Lire le témoignage complet', 'mars'); ?>"><i class="fa fa-plus"></i></button>
										</div>
										<div class="review-text">
											<?php
											// Truncate if needed, or show full content
											echo get_the_excerpt();
											?>
										</div>
										<div class="review-footer">
											<?php if ($promo) : ?>
												Promo <span class="author-promo"><?php echo esc_html($promo); ?></span>
											<?php endif; ?>
										</div>

										<!-- Hidden Full Content for Modal -->
										<div class="testimonial-full-content" style="display: none;" aria-hidden="true">
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
						</div>
						<!-- Navigation -->
						<div class="mars-carousel-controls">
							<button class="mars-carousel-prev btn btn--outline btn--icon" aria-label="<?php _e('Précédent', 'mars'); ?>"><i class="fa fa-chevron-left"></i></button>
							<button class="mars-carousel-next btn btn--outline btn--icon" aria-label="<?php _e('Suivant', 'mars'); ?>"><i class="fa fa-chevron-right"></i></button>
						</div>
					</div>
				</div>
			<?php else : ?>
				<div class="alert alert-warning">
					<p><?php _e('Aucun témoignage trouvé.', 'mars'); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
