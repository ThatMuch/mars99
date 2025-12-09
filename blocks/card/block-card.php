<?php

/**
 * Block Name: Card
 * Description: Carte simple avec image, titre et description
 */

// Créer un id unique pour ce bloc
$id = 'card-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Créer le nom de classe
$className = 'card-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Récupérer les champs
$image = get_field('image');
$title = get_field('title');
$description = get_field('description');
$excerpt = get_field('excerpt');
$style = get_field('style');
// Mode preview avec données factices
if ($is_preview && empty($title)) {
	$title = 'Titre de la carte';
	$description = 'Description de la carte avec du contenu d\'exemple pour montrer le rendu final.';
	$image = array(
		'alt' => 'Image d\'exemple',
		'url' => get_template_directory_uri() . '/images/abyss-energy-logo.webp'
	);
}
?>

<?php if ($style === 'showmore'): ?>
	<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="card-wrapper">
			<button class="card-button" aria-label="Expand content"><i class="fa fa-plus"></i></button>
			<div class="card-header <?php if ($image):  ?>has-image<?php endif; ?>">
				<?php if ($image): ?>
					<div class="card-image">
						<img src="<?php echo esc_url($image['url']); ?>"
							alt="<?php echo esc_attr($image['alt']); ?>"
							loading="lazy">
					</div>
				<?php endif; ?>
				<?php if ($title): ?>
					<h4 class="card-title"><?php echo wp_kses_post($title); ?></h4>
				<?php endif; ?>
			</div>

			<div class="card-content">
				<?php if ($excerpt) : ?>
					<div class="card-description">
						<?php echo wp_kses_post($excerpt); ?>
					</div>
				<?php endif; ?>
			</div>

		</div>
		<?php if (!$is_preview): ?>
			<div class="card-modal" id="<?php echo esc_attr($id); ?>-modal" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($id); ?>-modal-title">
				<div class="card-modal-content">
					<div class="card-header">
						<?php if ($image): ?>
							<div class="card-image">
								<img src="<?php echo esc_url($image['url']); ?>"
									alt="<?php echo esc_attr($image['alt']); ?>"
									loading="lazy">
							</div>
						<?php endif; ?>
						<?php if ($title): ?>
							<h4 class="card-title"><?php echo wp_kses_post($title); ?></h4>
							<button class="modal-close" aria-label="Close content"><i class="fa fa-times"></i></button>
						<?php endif; ?>
					</div>
					<div class="card-content">
						<?php if ($description): ?>
							<div class="card-description">
								<?php echo wp_kses_post($description); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
	</div>
<?php endif; ?>
<?php else: ?>
	<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="card-wrapper">
			<div class="card-header">
				<?php if ($image): ?>
					<div class="card-image">
						<img src="<?php echo esc_url($image['url']); ?>"
							alt="<?php echo esc_attr($image['alt']); ?>"
							loading="lazy">
					</div>
				<?php endif; ?>
				<?php if ($title): ?>
					<h4 class="card-title"><?php echo wp_kses_post($title); ?></h4>
				<?php endif; ?>
			</div>

			<div class="card-content">
				<?php if ($description): ?>
					<div class="card-description">
						<?php echo wp_kses_post($description); ?>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>
<?php endif; ?>
