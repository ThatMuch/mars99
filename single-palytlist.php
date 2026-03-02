<?php

/**
 * Template part for displaying single playlist posts.
 * Key used: palytlist
 */

get_header();
?>

<main class="site-main">
	<div class="container">
		<article id="post-<?php the_ID(); ?>" <?php post_class('single-playlist'); ?>>

			<div class="single-playlist__grid">

				<!-- Left Column: Featured Image -->
				<div class="single-playlist__media">
					<?php if (has_post_thumbnail()) : ?>
						<figure class="single-playlist__thumbnail">
							<?php the_post_thumbnail('large', ['class' => 'img-responsive']); ?>
						</figure>
					<?php else : ?>
						<!-- Fallback image layout when no featured image exists -->
						<div class="single-playlist__thumbnail-placeholder">
							<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
								<circle cx="8.5" cy="8.5" r="1.5"></circle>
								<polyline points="21 15 16 10 5 21"></polyline>
							</svg>
						</div>
					<?php endif; ?>
				</div>

				<!-- Right Column: Title and Content -->
				<div class="single-playlist__content-wrapper">
					<header class="single-playlist__header">
						<?php the_title('<h1 class="single-playlist__title">', '</h1>'); ?>
					</header>

					<div class="single-playlist__content">
						<?php
						// Output the Gutenberg blocks/content, including the audio playlist block
						the_content();
						?>
					</div>
				</div>

			</div>

		</article>
	</div>
</main>

<?php get_footer(); ?>
