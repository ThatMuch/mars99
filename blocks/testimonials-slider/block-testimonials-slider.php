<?php

/**
 * Testimonials Slider Block Template.
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
$block_id = 'testimonials-slider-' . (isset($block['id']) ? $block['id'] : uniqid());
if (!empty($block['anchor'])) {
	$block_id = $block['anchor'];
}

// Support custom "anchor" values.
$anchor = 'id="' . esc_attr($block_id) . '" ';

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'testimonials-slider-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Témoignages de nos clients';
$subtitle = get_field('subtitle');
$image = get_field('image');
$selection_type = get_field('selection_type') ?: 'all';
$specific_category = get_field('specific_category');
$header_alignment = get_field('header_alignment') ?: 'right';

// Arguments de base pour la requête
$args = array(
	'post_type' => 'testimonials',
	'posts_per_page' => -1, // Afficher tous les témoignages
	'orderby' => 'date',
	'order' => 'DESC',
);

// Si on veut une catégorie spécifique
if ($selection_type === 'specific_category' && !empty($specific_category)) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'testimonial-category',
			'field'    => 'term_id',
			'terms'    => $specific_category,
		),
	);
}

$testimonials = new WP_Query($args);
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php esc_html_e('Aperçu du bloc Témoignages', 'abyssenergy'); ?></p>
	</div>
<?php else : ?>

	<?php if ($testimonials->have_posts()) : ?>
		<!-- Testimonials Slider Block -->
		<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>" data-block-id="<?php echo esc_attr($block_id); ?>">
			<div class="container">
				<div class="testimonials-slider-block-header align-<?php echo esc_attr($header_alignment); ?>">
					<div class="section-title">
						<?php if ($subtitle) : ?>
							<span class="section--subtitle"><?php echo esc_html($subtitle); ?></span>
						<?php endif; ?>
						<?php if ($title) : ?>
							<h2><?php echo esc_html($title); ?></h2>
						<?php endif; ?>
					</div>
					<?php if ($image) : ?>
						<img src="<?php echo esc_url(wp_get_attachment_image_url($image, 'full')); ?>" alt="<?php echo esc_attr(get_post_meta($image, '_wp_attachment_image_alt', true)); ?>" class="img-fluid testimonial-section-image" loading="lazy" />
					<?php endif; ?>
				</div>
				<div class="testimonials-slider swiper">
					<div class="swiper-wrapper">
						<?php while ($testimonials->have_posts()) : $testimonials->the_post();
							$categories = get_the_terms(get_the_ID(), 'testimonial-category');
							$category_class = '';
							if ($categories && !is_wp_error($categories) && !empty($categories)) {
								$category_class = esc_html($categories[0]->slug) . '-card';
							}
						?>
							<div class="swiper-slide">
								<div class="testimonial-card card <?php echo esc_attr($category_class); ?>">
									<div class="border"></div>
									<?php

									if ($categories && !is_wp_error($categories) && !empty($categories)) {
										foreach ($categories as $category) {  ?>
											<span class="testimonial-category mb-3">
												<?php
												echo esc_html($category->name);
												?></span>
									<?php
										}
									} ?>
									</span>

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
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
					<!-- Navigation -->
					<div class="d-flex justify-content-center align-items-center mt-4 gap-3">
						<div class="left btn btn--outline  btn--icon"><i class="fa fa-chevron-left"></i></div>
						<div class="right btn btn--outline  btn--icon"><i class="fa fa-chevron-right"></i></div>
					</div>
				</div>
			</div>
		</section>
	<?php else : ?>
		<div class="alert alert-warning">
			<?php _e('Aucun témoignage trouvé.', 'abyssenergy'); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
