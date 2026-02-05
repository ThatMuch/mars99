<?php get_header(); ?>



<main class="site-main">

	<div class="wrapper">

		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>

				<?php the_content(); ?>

			<?php endwhile; ?>

		<?php else : ?>



		<?php endif; ?>

	</div>

</main>

<?php get_footer(); ?>
