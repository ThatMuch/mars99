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
                <p> <?php // afficher le titre du nav_menu footer-menu1
                    echo wp_get_nav_menu_name('footer-menu1');
                    ?> </p>
                <?php
                // Display footer menu if it exists
                if (has_nav_menu('footer-menu1')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu1',
                        'container' => false,
                        'items_wrap' => '<ul class="menu">%3$s</ul>',
                        'depth' => 1
                    ));
                }
                ?>
            </div>
            <div>
                <p> <?php // afficher le titre du nav_menu footer-menu2
                    echo wp_get_nav_menu_name('footer-menu2');
                    ?> </p>
                <?php
                // Display footer menu if it exists
                if (has_nav_menu('footer-menu2')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu2',
                        'container' => false,
                        'items_wrap' => '<ul class="menu">%3$s</ul>',
                        'depth' => 1
                    ));
                }
                ?>
            </div>
            <div>
                <p> <?php // afficher le titre du nav_menu footer-menu3
                    echo wp_get_nav_menu_name('footer-menu3');
                    ?> </p>
                <?php
                // Display footer menu if it exists
                if (has_nav_menu('footer-menu3')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu3',
                        'container' => false,
                        'items_wrap' => '<ul class="menu">%3$s</ul>',
                        'depth' => 1
                    ));
                }
                ?>
            </div>
            <div>
                <p> <?php // afficher le titre du nav_menu footer-menu4
                    echo wp_get_nav_menu_name('footer-menu4');
                    ?> </p>
                <?php
                // Display footer menu if it exists
                if (has_nav_menu('footer-menu4')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu4',
                        'container' => false,
                        'items_wrap' => '<ul class="menu">%3$s</ul>',
                        'depth' => 1
                    ));
                }
                ?>
            </div>
        </div>
    </footer>

    <div class='footer-bottom'>
        <div class=' d-flex justify-content-between align-items-center w-100'>
            <div class='d-flex gap-2'>
                <p>
                    Copyright &copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?>. Tous droits réservés.
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
                <a href="<?php echo get_permalink(get_page_by_path('politique-de-confidentialite')); ?>">Politique de confidentialité</a>
                <a href="<?php echo get_permalink(get_page_by_path('mentions-legales')); ?>">Mentions légales</a>
                <a href="<?php echo get_permalink(get_page_by_path('conditions-generales')); ?>">Conditions générales de vente</a>
            </div>
            <a class="link-thatmuch" href="https://thatmuch.fr" target="_blank" rel="noopener noreferrer" aria-label="Logo THATMUCH">
                <img loading="lazy" class="img-thatmuch" src="<?php echo get_template_directory_uri(); ?>/img/LogoTHATMUCH_Footer.webp" alt="Logo THATMUCH">
            </a>
        </div>
    </div>
</div>


<?php wp_footer(); ?>
</body>

</html>
