<?php

/**
 * Block Metrics Template.
 *
 */

// Créer un id unique pour ce bloc
$block_id = 'metrics-' . (isset($block['id']) ? $block['id'] : uniqid());
if (!empty($block['anchor'])) {
	$block_id = $block['anchor'];
}

$anchor = 'id="' . esc_attr($block_id) . '" ';

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'metrics-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$metrics = get_field('metrics') ?: array();
$excerpt = get_field('excerpt');

?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<p><?php _e('Aperçu du bloc des métriques. Configurez les champs dans le panneau de droite.', 'abyssenergy'); ?></p>
		<?php if (empty($metrics)) : ?>
			<p><em><?php _e('Aucune métrique ajoutée. Cliquez sur "Ajouter un élément" pour commencer.', 'abyssenergy'); ?></em></p>
		<?php endif; ?>
	</div>
<?php else : ?>

	<!-- Metrics Block -->
	<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>" data-block-id="<?php echo esc_attr($block_id); ?>">
		<div class="container">
			<?php if ($metrics) : ?>
				<div class="metrics-grid">
					<?php foreach ($metrics as $index => $metric) : ?>
						<div class="metric-card <?php echo
												$index === 0 ? 'first-child' : '';
												?>">
							<?php if (!empty($metric['value'])) : ?>
								<h3 class="metric-value"><?php echo esc_html($metric['value']); ?></h3>
							<?php endif; ?>
							<div class="metric-content">
								<?php if (!empty($metric['label'])) : ?>
									<p class="metric-label"><?php echo esc_html($metric['label']); ?></p>
								<?php endif; ?>
								<?php if (!empty($metric['excerpt'])) : ?>
									<div class="metric-excerpt"><?php echo wp_kses_post($metric['excerpt']); ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

<?php endif; ?>
