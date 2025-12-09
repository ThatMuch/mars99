<?php

/**
 * Template Name: Classic Page
 *
 * Template pour afficher le contenu d'une page classique
 * Ce template peut être assigné à n'importe quelle page depuis l'administration WordPress
 */

get_header();
?>

<?php
get_template_part('template-parts/page-header', null, array(
	'thumbnail_position' => 'right',
	'col_left' => 'col-md-7',
	'col_right' => 'col-md-5',
));
?>

<main>
	<div class="container my-5">
		<?php
		while (have_posts()) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</main>

<?php get_footer(); ?>
