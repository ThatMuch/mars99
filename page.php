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
	<?php the_content(); ?>
</main>

<?php get_footer(); ?>
