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
	<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>" data-block-id="<?php echo esc_attr($block_id); ?>">
		<div class="container">
			<?php if ($title || $subtitle) : ?>
				<div class="section-header col-md-6">
					<?php if ($subtitle) : ?>
						<p class="section--subtitle"><?php echo esc_html($subtitle); ?></p>
					<?php endif; ?>
					<?php if ($title) : ?>
						<h2 class="section--title"><?php echo esc_html($title); ?></h2>
					<?php endif; ?>
					<?php if ($button && !empty($button['url']) && !empty($button['title'])) : ?>
						<div class="section-header-button">
							<a href="<?php echo esc_url($button['url']); ?>" class="btn btn-primary">
				<div class="btn__content"><?php echo esc_html($button['title']); ?></div>
				<div class="btn__overlay"></div>
			</a>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ($features) : ?>
				<div class="row">
					<?php foreach ($features as $feature) : ?>
						<div class="col col-lg-5 ">
							<div class="feature-card h-100">
								<div>
									<?php if (!empty($feature['title'])) : ?>
										<h3 class="feature-title"><?php echo esc_html($feature['title']); ?></h3>
									<?php endif; ?>
									<?php if (!empty($feature['description'])) : ?>
										<div class="feature-description"><?php echo $feature['description']; ?></div>
									<?php endif; ?>
								</div>
								<?php if (!empty($feature['icon'])) : ?>
									<div class="feature-icon">
										<img src="<?php echo esc_url(wp_get_attachment_image_url($feature['icon'], 'full')); ?>" alt="<?php echo esc_attr(get_post_meta($feature['icon'], '_wp_attachment_image_alt', true)); ?>" class="img-fluid" loading="lazy" />
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="no-features-message text-center">
					<p class="text-muted"><?php _e('Aucune fonctionnalité à afficher. Ajoutez des éléments dans les paramètres du bloc.', 'abyssenergy'); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
