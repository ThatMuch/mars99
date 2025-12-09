<?php get_header(); ?>



<section class='content'>

	<div class="wrapper">

		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>

				<?php the_content(); ?>

			<?php endwhile; ?>

		<?php else : ?>



		<?php endif; ?>

	</div>

</section>

<?php get_footer(); ?>
