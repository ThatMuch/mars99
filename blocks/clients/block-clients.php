<?php

/**
 * Clients slider block template.
 * @param  array $block The block settings and attributes.
 * @param  string $content The block inner HTML (empty).
 * @param  bool $is_preview True during backend preview render.
 * @param  int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param  array $context The context provided to the block by the post or its parent block.
 *
 */

// Créer un id unique pour ce bloc
$block_id = 'clients-' . (isset($block['id']) ? $block['id'] : uniqid());
if (!empty($block['anchor'])) {
	$block_id = $block['anchor'];
}

// Support custom "anchor" values.
$anchor = 'id="' . esc_attr($block_id) . '" ';

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'clients-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$show_title = get_field('show_title') !== false;
$gallery = get_field('gallery') ?: array();

?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3>Our clients</h3>
		<p><?php _e('Aperçu du slider des clients. Les logos des clients ne seront pas affichés dans l\'éditeur.', 'mars'); ?></p>
	</div>
<?php else : ?>
	<?php if ($gallery) : ?>
		<!-- Clients Slider Block -->

		<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>" data-block-id="<?php echo esc_attr($block_id); ?>">
			<div class="container">
				<div class="clients-block-wrapper">
					<div class="clients-slider">
						<div id="clients-slider-inner" class="clients-slider-inner">
							<?php foreach ($gallery as $image) : ?>
								<div class="client-logo">
									<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="img-fluid" loading="lazy">
								</div>
							<?php endforeach; ?>

							<?php foreach ($gallery as $image) : ?>
								<div class="client-logo">
									<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="img-fluid" loading="lazy">
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			</div>
		</section>
	<?php else : ?>
		<div class="alert alert-warning">
			<?php _e('Aucun client trouvé.', 'mars'); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
