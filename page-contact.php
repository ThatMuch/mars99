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

<div class="page page_contact">
	<?php
	get_template_part('template-parts/page-header', null, array(
		'subtitle' => $subtitle,
		'thumbnail_position' => 'right',
		'col_left' => 'col-md-8',
		'col_right' => 'col-md-4',
		'buttons' => false
	));
	?>
	<div class="container">
		<?php the_content(); ?>
	</div>
	<?php get_footer(); ?>
