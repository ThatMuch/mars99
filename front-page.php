<?php
get_header();
?>

<?php
// get_template_part('template-parts/page-header', null, array(
// 	'show_thumbnail' => false,
// 	'col_left' => 'col-md-12'
// ));
?>

<main class="site-main">
	<div class="container">
		<?php the_content(); ?>
	</div>
</main>
<?php get_footer(); ?>
