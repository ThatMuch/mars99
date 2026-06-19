<?php

/**
 * Features Block Template.
 */

// Créer un id unique pour ce bloc
$block_id = 'features-' . (isset($block['id']) ? $block['id'] : uniqid());
if (!empty($block['anchor'])) {
	$block_id = $block['anchor'];
}

$anchor = 'id="' . esc_attr($block_id) . '" ';

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'features-block';
if (! empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (! empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title');
$subtitle = get_field('subtitle');
$description = get_field('description');
$background_image = get_field('background_image');
$features = get_field('features') ?: array();
$button = get_field('main_button');
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title ?: 'Bloc Features'); ?></h3>
		<p><?php _e('Aperçu du bloc des fonctionnalités. Configurez les champs dans le panneau de droite.', 'abyssenergy'); ?></p>
		<?php if (empty($features)) : ?>
			<p><em><?php _e('Aucune fonctionnalité ajoutée. Cliquez sur "Ajouter un élément" pour commencer.', 'abyssenergy'); ?></em></p>
		<?php endif; ?>
	</div>
<?php else :  ?>
	<!-- Features Block -->
	<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>" data-block-id="<?php echo esc_attr($block_id); ?>" style="background-image: url('<?php echo esc_url($background_image['url']); ?>');">

		<?php if ($title || $subtitle || $description) : ?>
			<div class="section-header text-center">
				<div class="section-header-content mx-auto">
					<?php if ($title) : ?>
						<h2 class="section--title h3 mb-4"><?php echo esc_html($title); ?></h2>
					<?php endif; ?>
					<?php if ($description) : ?>
						<div class="section--description mb-5"><?php echo $description; ?></div>
					<?php endif; ?>
					<?php if ($button && !empty($button['url']) && !empty($button['title'])) : ?>
						<div class="section-header-button">
							<a href="<?php echo esc_url($button['url']); ?>" class="btn btn-primary btn-pill">
								<span class="btn__content"><?php echo esc_html($button['title']); ?></span>
								<div class="btn__overlay"></div>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($features) :
			$grid_class = 'feature__grid';
			if (count($features) === 4) {
				$grid_class .= ' feature__grid--count-4';
			}
		?>
			<div class="<?php echo esc_attr($grid_class); ?>">
				<?php foreach ($features as $feature) : ?>

					<div class="feature-card h-100">
						<?php if (!empty($feature['icon'])) : ?>
							<div class="feature-icon">
								<img src="<?php echo esc_url(wp_get_attachment_image_url($feature['icon'], 'full')); ?>" alt="<?php echo esc_attr(get_post_meta($feature['icon'], '_wp_attachment_image_alt', true)); ?>" class="img-fluid no-animation" loading="lazy" />
							</div>
						<?php endif; ?>
						<?php if (!empty($feature['title'])) : ?>
							<h3 class="feature-title h4 mb-2 no-animation"><?php echo esc_html($feature['title']); ?></h3>
						<?php endif; ?>
						<?php if (!empty($feature['description'])) : ?>
							<div class="feature-description no-animation"><?php echo $feature['description']; ?></div>
						<?php endif; ?>
					</div>

				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="no-features-message text-center">
				<p class="text-muted"><?php _e('Aucune fonctionnalité à afficher. Ajoutez des éléments dans les paramètres du bloc.', 'abyssenergy'); ?></p>
			</div>
		<?php endif; ?>

	</section>
<?php endif; ?>
