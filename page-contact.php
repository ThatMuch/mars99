<?php

/**
 * Template Name: Contact
 *
 * Template pour afficher le formulaire de contact
 * Ce template peut être assigné à n'importe quelle page depuis l'administration WordPress
 */

get_header();
$subtitle = get_field('subtitle');
?>

<main class="site-main">
	<div class="container">
		<?php the_content(); ?>
	</div>
</main>
<?php get_footer(); ?>
