<?php $footer_logo = get_theme_mod('mars_footer_logo', ''); ?>
</div><!-- #site-content-wrapper -->
<div id="footer-parallax-wrapper">
    <footer class='footer'>
        <div class='footer-links'>
            <div class='footer-logo'>
                <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php bloginfo('name'); ?>" loading="lazy">
            </div>
            <div class='footer-container'>
                <?php wp_nav_menu(array('theme_location' => 'footer-menu1', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>
                <?php wp_nav_menu(array('theme_location' => 'footer-menu2', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>
                <?php wp_nav_menu(array('theme_location' => 'footer-menu3', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>
                <?php wp_nav_menu(array('theme_location' => 'footer-menu4', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>
            </div>
        </div>
    </footer>

    <div class='footer-bottom'>
        <div class='container d-flex justify-content-center align-items-center'>
            <p>
                Copyright &copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?>. All Rights Reserved.
                <?php
                // Display footer menu if it exists
                if (has_nav_menu('footer-bottom-menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-bottom-menu',
                        'container' => false,
                        'items_wrap' => ' | %3$s',
                        'depth' => 1
                    ));
                }
                ?>
            </p>
        </div>
    </div>
</div>


<?php wp_footer(); ?>
</body>

</html>
