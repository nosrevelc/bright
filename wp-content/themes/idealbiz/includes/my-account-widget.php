<?php

$first_name   = false;

?>
<?php if (is_user_logged_in()) :
    $current_user = wp_get_current_user();

    if ( in_array( 'consultant', $current_user->roles, true ) || $expert ) {
        $cl_consultant = 'consultant';
    }else{
        $cl_consultant = 'customer';
    }

    $first_name   = $current_user->first_name;
    ?>

    <div class="my-account-header">
        <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" title="<?php _e('My Account', 'idealbiz'); ?>">
            <?php if ($first_name) {
                    printf(
                        '<span class="account__user__name">%1$s %2$s</span>',
                        esc_html__('Hi!', 'idealbiz'),
                        esc_html__($first_name)
                    );
                } ?><?php echo get_avatar(
                            get_current_user_id(),
                            38,
                            '',
                            esc_html__('User avatar', 'i'),
                            array('class' => 'account__user-image')
                        ) ?>
        </a>
        <div class="pull-right d-md-none">
            <?php if ($phone = get_field('phone', 'option')) : ?>
                <a href="tel:<?php echo $phone['call_code'] . $phone['number']; ?>" class="light-blue--color phone-number-right"><?php echo $phone['number']; ?></a>
            <?php endif; ?>
            <a class="greyer--color" href="<?php echo get_permalink(pll_get_post(get_page_by_path('contacts')->ID)); ?>"><?php _e('Contacts', 'idealbiz'); ?></a>
        </div>

        <ul class="sub-menu stroke dropshadow">
            <li class="hex-a55df1 menu-item">
                <a data-iconbg="#a55df1" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" title="<?php _e('My Account', 'idealbiz'); ?>">
                    <i class="icon icon-perfil"></i> <?php _e('My Account', 'idealbiz'); ?>
                </a>
            </li>
            <!-- <li class="hex-a55df1 menu-item">
                    <a data-iconbg="#a55df1" 
           
                </li> -->
                <!-- NPMM -SUSPENÇO DIA 05/12/2022 PELA REORGANIZAÇÃO DE MENUS FEITA POR DR. ALBERTO -->
            <!-- <li class="hex-a55df1 menu-item">
                <a data-iconbg="#a55df1" href="<?php echo wc_get_endpoint_url('chat', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) ?>" title="<?php _e('My Account', 'idealbiz'); ?>">
                    <i class="icon icon-categories"></i> 
                    <?php echo do_shortcode('[fep_shortcode_new_message_count show_bracket="1"]'); ?> <?php _e('Chat Messages', 'idealbiz'); ?>
                </a>
            </li> -->
            <li class="hex-a55df1 menu-item d-none">
                <a data-iconbg="#a55df1" href="<?php echo getLinkByTemplate('premium-buyer.php'); ?>" title="<?php _e('Premium Buyer', 'idealbiz'); ?>">
                    <i class="icon icon-comprar"></i>
                    <?php
                        $premium_buyer = isPremiumBuyer();
                        if (is_array($premium_buyer) && $premium_buyer['status'] == 3) {
                            echo '<i class="icofont-check valid-check-plan green--color"></i>';
                        } elseif (is_array($premium_buyer) && $premium_buyer['status'] == 2) {
                            echo '<i class="icofont-check valid-check-plan yellow--color"></i>';
                        } else { }
                        ?>
                    <?php _e('Premium Buyer', 'idealbiz'); ?>
                </a>
            </li>
             <!-- INICIO MENU POSTS --> 
             <!-- NPMM -SUSPENÇO DIA 05/12/2022 PELA REORGANIZAÇÃO DE MENUS FEITA POR DR. ALBERTO -->  
            <!-- <li class="hex-a55df1 menu-item">
                <a data-iconbg="#a55df1" href="<?php echo getLinkByTemplate('post-content.php'); ?>" title="<?php _e('Post Content', 'idealbiz'); ?>">
                    <i class="icon icon-perfil"></i>
                    <?php
                        
                            _e('Panel Post Content', 'idealbiz');

                        ?>
                    
                    </a>
            </li> -->
            <!-- FINAL MENU POSTS -->
        <!-- Broker no menu -->
        <li class="hex-a55df1 menu-item">
                        <a data-iconbg="#a55df1" href="<?php echo getLinkByTemplate('broker-account.php'); ?>" title="<?php _e('Broker Account', 'idealbiz'); ?>">
                            <i class="icon icon-especialistas"></i>
                            <?php
                                $broker = isBroker();
                                //echo  pll_current_language();
                                if (array_key_exists('status', $broker)) { 
                                    if (is_array($broker) && $broker['status'] == 3) {
                                        echo '<i class="icofont-check valid-check-plan green--color"></i>';
                                        _e('Broker Account', 'idealbiz');
                                    } elseif (is_array($broker) && $broker['status'] == 2) {
                                        echo '<i class="icofont-check valid-check-plan yellow--color"></i>';
                                        _e('Broker Account', 'idealbiz');
                                    } 
                                }else { 
                                    _e('Add Broker Subscription', 'idealbiz');
                                }
                                ?>
                            
                        </a>
                    </li>


            <!-- NPMM -SUSPENÇO DIA 05/12/2022 PELA REORGANIZAÇÃO DE MENUS FEITA POR DR. ALBERTO -->                    
            <!-- <li class="hex-a55df1 menu-item">
                <a data-iconbg="#a55df1" href="<?php echo wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) ?>" title="<?php _e('My Listings', 'idealbiz'); ?>">
                    <i class="icon icon-vender"></i> <?php _e('My Listings', 'idealbiz'); ?>
                </a>
            </li> -->
            <li class="hex-a55df1 menu-item">
                <a data-iconbg="#a55df1" href="<?php echo wc_get_endpoint_url('favorites', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) ?>" title="<?php _e('My Favorites', 'idealbiz'); ?>">
                    <i class="icofont-heart" style="line-height: 30px;"></i> <?php _e('My Favorites', 'idealbiz'); ?>
                </a>
            </li>
            <?php
            $sr = new \WP_Query(
                array(
                    'post_type'      => 'service_request',
                    'posts_per_page' => 1,
                )
            );
            if ( $sr->have_posts() ) {
            ?>
            <!-- NPMM -SUSPENÇO DIA 05/12/2022 PELA REORGANIZAÇÃO DE MENUS FEITA POR DR. ALBERTO -->
            
                <?php if ($cl_consultant === 'customer'){?>
                    <li class="hex-a55df1 menu-item">
                        <a data-iconbg="#a55df1" href="<?php echo wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?home=1'; ?>">
                            <i class="icon-servicos" style="line-height: 30px;"></i> <?php _e('Service Requests', 'idealbiz'); ?>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>


           <?php 
              

            
            $cl_Member = checkisMember();
           
            if ($cl_Member == true){
           
           ?>     
            <li class="hex-a55df1 menu-item">
                <a data-iconbg="#a55df1" href="<?php echo getLinkByTemplate('idealbiz-network.php'); ?>">
                
                <i class="icon icon-aconselhamento-fiscal" style="line-height: 30px;"></i> <?php _e( '_str Members', 'idealbiz' ); ?>
                </a>
            </li>

            <?php } ?>

            <li class="hex-a55df1 menu-item">
                <a href="<?php echo esc_url(wp_logout_url(home_url('/'))) ?>" title="<?php echo esc_attr__('Log out', 'idealbiz'); ?>" class="account-nav__link">
                    <i class="icon icon-arrowleft"></i> <?php esc_html_e('Log out', 'idealbiz') ?>
                </a>
            </li>


        </ul>
    </div>

    <?php else : ?>

     <!--NPMM TROCA O LINK DE REGISTO PARA NOVO FORM-->   
<!--     <a class="login-register p-y-6 pointer register lrm-login"><?php _e('Login', 'idealbiz') ?></a>
    <span class="p-y-6 d-none d-lg-block m-l-15">|</span>
    <a class="login-register p-y-6 pointer login" href="<?php echo get_permalink(pll_get_post(get_page_by_path('register-new-user')->ID)); ?>'">
    <?php _e('Register', 'idealbiz') ?></a>
    <a class="my-account-header lrm-login pointer d-none d-md-block"><i class="icon-perfil"></i></a>
    <div class="pull-right d-md-none">
        <?php if ($phone = get_field('phone', 'option')) : ?>
            <a href="tel:<?php echo $phone['call_code'] . $phone['number']; ?>" class="light-blue--color phone-number-right"><?php echo $phone['number']; ?></a>
        <?php endif; ?>
        <a class="greyer--color" href="<?php echo get_permalink(pll_get_post(get_page_by_path('contacts')->ID)); ?>"><?php _e('Contacts', 'idealbiz'); ?></a>
    </div> -->


<a class="login-register p-y-6 pointer register lrm-login"><?php _e('Login', 'idealbiz') ?></a>
    <span class="p-y-6 d-none d-lg-block m-l-15">|</span>
    <a class="login-register p-y-6 pointer login lrm-register"><?php _e('Register', 'idealbiz') ?></a>
    <a class="my-account-header lrm-login pointer d-none d-md-block"><i class="icon-perfil"></i></a>
    <div class="pull-right d-md-none">
        <?php if ($phone = get_field('phone', 'option')) : ?>
            <a href="tel:<?php echo $phone['call_code'] . $phone['number']; ?>" class="light-blue--color phone-number-right"><?php echo $phone['number']; ?></a>
        <?php endif; ?>
        <a class="greyer--color" href="<?php echo get_permalink(pll_get_post(get_page_by_path('contacts')->ID)); ?>"><?php _e('Contacts', 'idealbiz'); ?></a>
    </div>

<?php endif ?>