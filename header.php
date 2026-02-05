<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<?php
// Add page slug to body class
$page_slug = get_post_field('post_name', get_post());
?>

<body <?php body_class('page-' . esc_attr($page_slug)); ?>>
	<div id="site-content-wrapper">

		<header class="header">
			<div class="header__logo">
				<?php
				if (function_exists('the_custom_logo')) {
					the_custom_logo();
				} else { ?>
					<a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo-link">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg" alt="<?php bloginfo('name'); ?>" class="header__logo-image">
					</a>
				<?php } ?>
			</div>
			<div>
				<input class="side-menu" type="checkbox" id="side-menu" />
				<label class="hamb" for="side-menu"><span class="hamb-line"></span></label>
				<nav class='main-menu'>
					<?php wp_nav_menu(array('theme_location' => 'main-menu', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '',)); ?>
				</nav>
				<?php
				// Récupérer l'ID du menu assigné à la location 'main-menu'
				$locations = get_nav_menu_locations();
				$menu_id = isset($locations['main-menu']) ? $locations['main-menu'] : 0;

				$btn_text = '';
				$btn_link = '';
				$btn_style = 'btn__primary';

				if ($menu_id) {
					$btn_text = get_theme_mod('header_btn_text_' . $menu_id, '');
					$btn_style = get_theme_mod('header_btn_style_' . $menu_id, 'btn__primary');

					// Récupérer le type de lien
					$link_type = get_theme_mod('header_btn_link_type_' . $menu_id, 'page');

					if ($link_type === 'page') {
						// Utiliser le sélecteur de page
						$page_id = get_theme_mod('header_btn_link_page_' . $menu_id, '');
						if ($page_id) {
							$btn_link = get_permalink($page_id);
						}
					} else {
						// Utiliser l'URL personnalisée
						$btn_link = get_theme_mod('header_btn_link_custom_' . $menu_id, '');
					}
				}

				if ($btn_text && $btn_link) :
				?>
					<a class="btn <?php echo esc_attr($btn_style); ?> custom-btn-header" href="<?php echo esc_url($btn_link); ?>">
						<div class="btn__content"><?php echo esc_html($btn_text); ?></div>
						<div class="btn__overlay"></div>
					</a>
				<?php endif; ?>
			</div>
		</header>
