<?php
get_header();
?>

<?php
get_template_part('template-parts/page-header', null, array(
	'show_thumbnail' => false,
	'col_left' => 'col-md-12'
));
?>

<main>
	<?php the_content(); ?>
</main>
<?php get_footer(); ?>
