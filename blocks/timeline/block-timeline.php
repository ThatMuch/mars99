<?php

/** Block name: Timeline
 * Description: A custom timeline block to showcase steps.
 */

// Créer le nom de la classe
$className = 'timeline-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Récupérer les champs
$title = get_field('timeline_title');
$subtitle = get_field('timeline_subtitle');
$steps = get_field('steps');
$timeline_files = get_field('timeline_files');
$timeline_description = get_field('timeline_description');
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php esc_html_e('Aperçu du bloc Timeline', 'abyssenergy'); ?></p>
	</div>
<?php else : ?>
	<?php if ($steps) : ?>
		<?php
		// Créer un ID unique pour ce bloc
		$block_id = 'timeline_' . (isset($block['id']) ? $block['id'] : uniqid());

		// Préparer les données des étapes pour JavaScript
		$steps_data = array();
		foreach ($steps as $step) {
			$steps_data[] = array(
				'title' => $step['title'],
				'excerpt' => $step['excerpt'],
				'description' => $step['description'],
				'image' => $step['image'],
				'cta' => $step['cta']
			);
		}
		?>
		<script>
			window.timelineData_<?php echo esc_js($block_id); ?> = {
				steps: <?php echo wp_json_encode($steps_data); ?>
			};
		</script>

		<!-- Timeline Block -->
		<div class="<?php echo esc_attr($className); ?>" data-block-id="<?php echo esc_attr($block_id); ?>" id="<?php echo esc_attr($block_id); ?>">
			<div class="container">
				<div class="section-header">
					<?php if ($subtitle) : ?>
						<p class="section--subtitle text-center"><?php echo esc_html($subtitle); ?></p>
					<?php endif; ?>
					<h2 class="section--title text-center"><?php echo wp_kses_post($title); ?></h2>
					<?php if (!empty($timeline_description)) : ?>
						<div class="section--description text-center"><?php echo wp_kses_post($timeline_description); ?></div>
					<?php endif; ?>
				</div>
				<div class="timeline-steps" style="<?php echo 'grid-template-rows: repeat(' . count($steps) . ', 200px);'; ?>">
					<?php
					$count = 0;
					foreach ($steps as $step) :
						$count++;
					?>
						<div class="item <?php echo 'item-' . $count; ?>">
							<div class="timeline-step">
								<div class="timeline-step-header">
									<?php if (!empty($step['image'])) : ?>
										<div class="timeline-step-image">
											<img src="<?php echo esc_url($step['image']['url']); ?>"
												alt="<?php echo esc_attr($step['image']['alt']); ?>"
												loading="lazy">
										</div>
									<?php endif; ?>
									<h3 class="timeline-step-title h4"><?php echo wp_kses_post($step['title']); ?></h3>
									<?php if (!empty($step['description'])) : ?>
										<button class="timeline-button" aria-label="Open content"><i class="fa fa-plus"></i></button>
									<?php endif; ?>
								</div>
								<div class="timeline-step-content">
									<p class="timeline-step-description"><?php echo wp_kses_post($step['excerpt']); ?></p>
								</div>
							</div>
							<span class="timeline-count"><?php echo $count; ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="timeline-files">
					<?php if ($timeline_files) : ?>
						<?php
						$count = 0;
						foreach ($timeline_files as $file) :
						?>
							<div class="timeline-file">
								<img src="<?php echo esc_url(get_template_directory_uri() . "/blocks/timeline/file-arrow-down.svg"); ?>" alt="Dowlnload icon" class="file-icon" loading="lazy">
								<a href="<?php echo esc_url($file['url']); ?>" class="btn btn--primary" target="_blank" rel="noopener">
					<div class="btn__content"><?php echo esc_html($file['title'] ?: basename($file['url'])); ?></div>
					<div class="btn__overlay"></div>
				</a>
							</div>
						<?php $count++;
						endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
