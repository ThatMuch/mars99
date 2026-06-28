<?php

/**
 * Block Name: Cards Slider
 * Description: Slider CSS pour afficher une liste de cartes
 */

// Créer un id unique pour ce bloc
$id = 'cards-slider-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Créer le nom de classe
$className = 'cards-slider-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Check preview mode
$is_preview = isset($is_preview) ? $is_preview : false;

$cards = get_field('cards');

if ($is_preview && empty($cards)) {
	// Dummy data for preview
	$cards = array(
		array(
			'style' => 'primary',
			'titre' => 'Yamas',
			'description' => 'Règles de vie sociale (Non-violence, Vérité...).'
		),
		array(
			'style' => 'light',
			'titre' => 'Niyamas',
			'description' => 'Règles de vie personnelle (Discipline, Contentement...).'
		),
		array(
			'style' => 'light',
			'titre' => 'Asanas',
			'description' => 'La pratique posturale (pour préparer le corps).'
		),
		array(
			'style' => 'light',
			'titre' => 'Pranayama',
			'description' => 'Le contrôle du souffle (pour calmer le mental).'
		)
	);
}

?>

<?php if ($is_preview): ?>
	<div class="cards-slider-block">
		<p class="cards-slider__empty"><?php _e('Aucune carte à afficher. Veuillez en ajouter dans les paramètres du bloc.', 'mars'); ?></p>
	</div>
<?php else: ?>

	<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<?php if (!empty($cards)): ?>
			<div class="cards-slider__container">
				<div class="cards-slider__track">
					<?php foreach ($cards as $index => $card):
						$style = $card['style'] ?? 'light';
						$titre = $card['titre'] ?? '';
						$description = $card['description'] ?? '';
						$number = $index + 1;
						$icon = $card['icon'] ?? '';

						// Determine SVG background to use based on style
						$style_classes = [
							'primary'         => 'is-primary',
							'secondary'       => 'is-secondary',
							'secondary-light' => 'is-secondary-light',
						];
						$background_class = $style_classes[$style] ?? 'is-light';
					?>
						<div class="cards-slider__card <?php echo esc_attr($background_class); ?>">
							<div class="cards-slider__card-content">
								<?php if ($icon): ?>
									<div class="card-icon">
										<i class="<?php echo esc_attr($icon); ?>" aria-hidden="true"></i>
									</div>
								<?php endif; ?>
								<h3 class="cards-slider__title"><?php echo esc_html($titre); ?></h3>
								<div class="cards-slider__description">
									<?php echo wp_kses_post($description); ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php else: ?>
			<p class="cards-slider__empty"><?php _e('Aucune carte à afficher. Veuillez en ajouter dans les paramètres du bloc.', 'mars'); ?></p>
		<?php endif; ?>
	</div>

<?php endif; ?>
