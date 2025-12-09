<?php

/**
 * Template Part: Page Header
 *
 * Affiche l'en-tête de la page avec titre, sous-titre, description, boutons et thumbnail
 *
 * @package Mars
 */

// Récupérer les données passées en arguments ou utiliser les valeurs par défaut
$subtitle = isset($args['subtitle']) ? $args['subtitle'] : safe_get_field_with_default('subtitle', false, '');
$description = isset($args['description']) ? $args['description'] : safe_get_field_with_default('description', false, '');
$buttons = isset($args['buttons']) ? $args['buttons'] : safe_get_field('buttons');
$title = isset($args['title']) ? $args['title'] : get_the_title();
$show_thumbnail = isset($args['show_thumbnail']) ? $args['show_thumbnail'] : true;
$thumbnail_size = isset($args['thumbnail_size']) ? $args['thumbnail_size'] : 'medium';
$custom_content = isset($args['custom_content']) ? $args['custom_content'] : '';
$custom_thumbnail = isset($args['custom_thumbnail']) ? $args['custom_thumbnail'] : '';
$content_col_class = !$custom_thumbnail ? 'col-md-12' : (isset($args['content_col_class']) ? $args['content_col_class'] : 'col-md-7');
$thumbnail_col_class = isset($args['thumbnail_col_class']) ? $args['thumbnail_col_class'] : 'col-md-5';
$row_classes = isset($args['row_classes']) ? $args['row_classes'] : '';
?>

<div class="page-header <?php echo has_post_thumbnail() ? 'has-thumbnail' : 'no-thumbnail'; ?>">
	<div class="container">
		<div class="row <?php echo esc_attr($row_classes); ?>">
			<div class="col <?php echo esc_attr($content_col_class); ?>">
				<?php if ($custom_content): ?>
					<?php echo $custom_content; ?>
				<?php else: ?>
					<h1><?php echo esc_html($title); ?></h1>
					<?php if ($subtitle): ?>
						<?php echo $subtitle; ?>
					<?php endif; ?>
					<?php if ($description): ?>
						<div class="page-description">
							<?php echo $description; ?>
						</div>
					<?php endif; ?>
					<?php if ($buttons): ?>
						<div class="page-buttons">
							<?php foreach ($buttons as $button): ?>
								<a href="<?php echo esc_url($button['link']['url']); ?>" class="btn <?php echo $button['style'] === 'fill' ? "btn--primary" : "btn--outline"; ?>" target="<?php echo $button['link']['target'] ? $button['link']['target'] : '_self'; ?>">
									<div class="btn__content">
										<?php echo esc_html($button['link']['title']); ?>
										<?php if ($button['icon']): ?>
											<span class="btn__icon"><?php echo $button['icon']; ?></span>
										<?php endif; ?>
									</div>
									<div class="btn__overlay"></div>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php if ($custom_thumbnail): ?>
				<div class="col <?php echo esc_attr($thumbnail_col_class); ?>">
					<?php echo $custom_thumbnail; ?>
				<?php elseif ($show_thumbnail && has_post_thumbnail()): ?>
					<div class="page-thumbnail">
						<?php the_post_thumbnail($thumbnail_size); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
