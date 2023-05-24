
<footer>
<hr class="m-0 p-b-20 w-100 clear">
    <div class="container medium-width">
        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12 d-none d-md-block">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location'    => 'footer-menu',
                        'depth'             => 1,
                        'container'         => 'ul',
                        'container_class'   => 'nav navbar-nav navbar-right',
                        'menu_class'        => 'footer-menu',
                        'fallback_cb'       => 'false'
                    )
                );
                ?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12 m-p-l-0">
                <?php
                    $social_links = '';
                    $asset_uri = get_stylesheet_directory_uri() . '/assets/img/email';
                    foreach ( get_social_networks() as $network => $social_network ) {
                        if ( empty( $social_network['url'] ) ) {
                            continue;
                        }
                        $social_links .= sprintf(
                            '<li class="social__item">
                                <a href="%1$s" target="_blank" class="social__link '.$network.'">
                                    <i class="icon icon-'.$network.'"></i>
                                </a>
                            </li>',
                            esc_url( $social_network['url'] ),
                            esc_url( sprintf( '%1$s/%2$s.png', $asset_uri, $network ) ),
                            esc_attr( $social_network['title'] )
                        );
                    }?>
                    <ul class="social d-flex justify-content-end">
                        <?php echo $social_links; ?>
                    </ul>
            </div>
        </div>
    </div>
<?php
global $pID;
?>
<script async src="https://cse.google.com/cse.js?cx=fb7fe69bdc108dde7"></script>
<script async defer src="<?php echo get_template_directory_uri(); ?>/assets/js/core.js"></script>
</footer>

<?php wp_footer(); ?>

<?php if(isset($whitebg)){ ?>
    <!-- white--bg -->
    </div>
<?php } 

global $toggle_services;

echo '<div class="site-blocks h-0 o-hidden">
        <div class="b-opts">'.
            $toggle_services.
        '</div>
     </div>';




?>
<script>
var lastScrollTop = 0;
jQuery(window).scroll(function(event){
   var st = jQuery(this).scrollTop();
   if (st > lastScrollTop){
        jQuery('body').find('#hubspot-messages-iframe-container').addClass('vis');
   } else {
        jQuery('body').find('#hubspot-messages-iframe-container').removeClass('vis');

   }
   lastScrollTop = st;
});
</script>
<style>
#hubspot-messages-iframe-container{
  bottom:0px !important;
  transition: all 0.5s ease;
}
#hubspot-messages-iframe-container.vis{
    bottom: -800px !important;
}

footer .footer-menu li a {
    color: #FDFDFD !important;
    font-weight: var(--font-weight);
    font-size: 1.3em !important;
}

</style>

</body>
</html>