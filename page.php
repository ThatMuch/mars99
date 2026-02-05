<?php
get_header();
?>

<?php
get_template_part('template-parts/page-header', null, array(
	'thumbnail_position' => 'right',
	'col_left' => 'col-md-7',
	'col_right' => 'col-md-5'
));
?>

<main>
	<div class="container">
		<?php the_content(); ?>
	</div>
</main>

<?php get_footer(); ?>
