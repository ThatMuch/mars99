<?php $footer_logo = get_theme_mod('mars_footer_logo', ''); ?>
</div><!-- #site-content-wrapper -->
<div id="footer-parallax-wrapper">
    <footer class='footer'>
        <div class='footer-top'>
            <div class='footer-logo'>
                <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php bloginfo('name'); ?>" loading="lazy">
            </div>

            <?php // the site tagline
            $footer_tagline = get_bloginfo('description');
            if ($footer_tagline) {
                echo '<h2 class="tagline">' . esc_html($footer_tagline) . '</h2>';
            }
            ?>


        </div>
        <div class="footer-menu">
            <div>
                <img src="<?php echo get_template_directory_uri(); ?>/img/LogoQualiopi-300dpi-Avec-Marianne.webp" alt="Logo Qualiopi" class="img-qualiopi">
                <p>Organisme de formation privé enregistré sous le numéro 11752167375. Cet enregistrement ne vaut pas  agrément de l’État. La certification qualité a été délivréeau titre de la catégorie d’action suivante : Actions de Formation</p>
            </div>
            <div>
                <?php
                // Display footer menu if it exists
                if (has_nav_menu('footer-menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu',
                        'container' => false,
                        'items_wrap' => '%3$s',
                        'depth' => 1
                    ));
                }
                ?>
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
