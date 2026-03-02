<?php

/**
 * Block Name: Playlist Audio
 * Description: Lecteur audio avec une playlist intégrée
 */

// Créer un id unique pour ce bloc
$id = 'playlist-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Créer le nom de classe
$className = 'playlist-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Check preview mode
$is_preview = isset($is_preview) ? $is_preview : false;

$tracks = get_field('tracks');

if ($is_preview && empty($tracks)) {
	// Dummy data for preview
	$tracks = array(
		array(
			'titre' => 'Méditation guidée - 10 minutes',
			'description' => 'Une courte méditation pour se recentrer et retrouver son calme.',
			'fichier_audio' => '#'
		),
		array(
			'titre' => 'Relaxation profonde',
			'description' => 'Audio pour vous aider à vous détendre avant de dormir.',
			'fichier_audio' => '#'
		)
	);
}

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<?php if (!empty($tracks)): ?>
		<div class="audio-player">
			<!-- Hidden Audio Element -->
			<audio class="audio-player__element" preload="metadata"></audio>

			<!-- Player UI -->
			<div class="audio-player__ui">
				<div class="audio-player__controls">
					<button class="audio-player__btn audio-player__btn--prev" aria-label="Piste précédente">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polygon points="19 20 9 12 19 4 19 20"></polygon>
							<line x1="5" y1="19" x2="5" y2="5"></line>
						</svg>
					</button>

					<button class="audio-player__btn audio-player__btn--play" aria-label="Lecture">
						<!-- Play Icon -->
						<svg class="icon-play" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polygon points="5 3 19 12 5 21 5 3"></polygon>
						</svg>
						<!-- Pause Icon (hidden by default) -->
						<svg class="icon-pause" style="display:none;" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="6" y="4" width="4" height="16"></rect>
							<rect x="14" y="4" width="4" height="16"></rect>
						</svg>
					</button>

					<button class="audio-player__btn audio-player__btn--next" aria-label="Piste suivante">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polygon points="5 4 15 12 5 20 5 4"></polygon>
							<line x1="19" y1="5" x2="19" y2="19"></line>
						</svg>
					</button>
				</div>

				<div class="audio-player__info">
					<div class="audio-player__now-playing">
						<h4 class="audio-player__title">Sélectionnez une piste</h4>
						<p class="audio-player__desc"></p>
					</div>

					<div class="audio-player__progress">
						<span class="audio-player__time audio-player__time--current">0:00</span>
						<input type="range" class="audio-player__seek-bar" value="0" min="0" max="100" step="1" aria-label="Barre de progression">
						<span class="audio-player__time audio-player__time--total">0:00</span>
					</div>
				</div>

				<div class="audio-player__options">
					<div class="audio-player__volume">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
							<path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
						</svg>
						<input type="range" class="audio-player__volume-slider" min="0" max="1" step="0.01" value="1" aria-label="Volume">
					</div>

					<button class="audio-player__btn audio-player__btn--toggle-list" aria-expanded="false" aria-controls="playlist-tracks-<?php echo esc_attr($id); ?>" aria-label="Afficher/Masquer la playlist">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="8" y1="6" x2="21" y2="6"></line>
							<line x1="8" y1="12" x2="21" y2="12"></line>
							<line x1="8" y1="18" x2="21" y2="18"></line>
							<line x1="3" y1="6" x2="3.01" y2="6"></line>
							<line x1="3" y1="12" x2="3.01" y2="12"></line>
							<line x1="3" y1="18" x2="3.01" y2="18"></line>
						</svg>
					</button>
				</div>
			</div>

			<!-- Tracklist -->
			<div id="playlist-tracks-<?php echo esc_attr($id); ?>" class="audio-player__tracklist" style="display: none;">
				<ul class="audio-player__tracks">
					<?php foreach ($tracks as $index => $track):
						$titre = $track['titre'] ?? '';
						$description = $track['description'] ?? '';
						$fichier_audio = $track['fichier_audio'] ?? '';
					?>
						<li class="audio-player__track-item"
							data-index="<?php echo esc_attr($index); ?>"
							data-src="<?php echo esc_url($fichier_audio); ?>"
							data-title="<?php echo esc_attr($titre); ?>"
							data-desc="<?php echo esc_attr($description); ?>">
							<div class="track-info">
								<span class="track-number"><?php echo ($index + 1); ?></span>
								<div class="track-details">
									<h5 class="track-title"><?php echo esc_html($titre); ?></h5>
									<?php if ($description): ?>
										<p class="track-description"><?php echo esc_html($description); ?></p>
									<?php endif; ?>
								</div>
							</div>
							<div class="track-status">
								<svg class="icon-playing" style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<polygon points="5 3 19 12 5 21 5 3"></polygon>
								</svg>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	<?php else: ?>
		<div class="block-preview-message">
			<p><?php _e('Aucune piste dans la playlist. Veuillez ajouter des pistes dans les paramètres du bloc.', 'mars'); ?></p>
		</div>
	<?php endif; ?>
</div>
