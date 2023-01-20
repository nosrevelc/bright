<?php // Template Name: PremiumBuyer
/*
if (!is_user_logged_in()) {
    wp_redirect(home_url());
}
*/

get_header();

?>
<?php
if (have_posts()) : while (have_posts()) : the_post();
        $meta = get_post_meta($post->ID, 'post_fields', true);

        ?>

<?php endwhile;
endif;


?>
<section class=" page p-relative z-index-2">

    <?php
    $premium_buyer = isPremiumBuyer();

    if ($premium_buyer) {



        //  var_dump(wp_get_current_user());



        $last_date = get_user_meta($premium_buyer_user->ID, 'premium_buyer_last_view', false);
        set_query_var('age', '7');
        add_filter('the_title', 'wpb_lastvisit_the_title', 10, 2);
        add_action('pre_get_posts', 'all_status');
        function all_status($query)
        {
            if ($query->get('post_type') == 'listing') {
                $post_status = array('publish', 'pending');
                $query->set('post_status', $post_status);
            }
        }



        echo '
        <div class="container text-center m-t-30">
            <h1>' . __('Your exclusive Premium Buyer Listings', 'idealbiz') . '</h1>
            ' . __('Your newly listings with less than 7 days', 'idealbiz') . '<br/><br/><br/><br/>
        </div>
        <style>
            .status-badge{ display: block !important; }
        </style>
        ';



        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $posts_per_page = (new IDB_Listings)->posts_per_page;

        echo '<style> .idealbiz-radio.age-all{ display: none; } </style>';

        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type' => 'listing',
            'post_status' => ['publish', 'pending'],
            'paged' => $paged,
            'inclusive' => true,
            'suppress_filters' => true,
            'date_query' => array(
                array(
                    'after' => '-7 days',
                    'column' => 'post_date',
                )
            )
        );
        $listings = new WP_Query($args);
        $total = $listings->found_posts;

        ?>

        <nav class="sidebar-filters">
            <form class="filters d-block d-md-none">
                <div class="filters-container col-md-10">
                    <h2 class="font-weight-bold"><?php _e('Filter your search', 'idealbiz'); ?></h2>
                    <div class="filter m-y-15">
                        <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Price', 'idealbiz'); ?></h3>
                        <?php get_template_part('/elements/filters/price'); ?>
                    </div>


                    <div class="filter m-t-15">
                        <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Certification', 'idealbiz'); ?></h3>
                        <?php get_template_part('/elements/filters/certification'); ?>
                    </div>
                    <div class="d-none flex-row justify-content-between m-t-50">
                        <a class="btn-blue normal-line-height" href="#">Aplicar Filtros</a>
                    </div>
                </div>
            </form>
        </nav>

        <section class="listing-page position-relative container p-relative z-index-2">
            <div class="loading d-none w-100 h-100">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <form class="d-none d-md-block col-md-4 filters">
                    <div class="filters-container col-md-12 col-lg-10 dropshadow box">
                        <h2 class="font-weight-bold"><?php _e('Filter your search', 'idealbiz'); ?></h2>
                        <div class="filter m-t-25">
                            <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Age of Listing', 'idealbiz'); ?></h3>
                            <?php get_template_part('/elements/filters/dates'); ?>
                        </div>
                        <?php /*
                        <div class="filter m-t-15">
                            <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Type of Business', 'idealbiz'); ?></h3>
                            <?php get_template_part('/elements/filters/property_type'); ?>
                            <?php get_template_part('/elements/filters/franchise'); ?>
                        </div> */ ?>
                        <div class="filter m-y-35">
                            <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Price', 'idealbiz'); ?></h3>
                            <?php get_template_part('/elements/filters/price'); ?>
                        </div>


                        <div class="filter">
                            <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Certification', 'idealbiz'); ?></h3>
                            <?php get_template_part('/elements/filters/certification'); ?>
                        </div>
                        <div class="d-none flex-row justify-content-between m-t-50">
                            <a class="btn-blue normal-line-height" href="#">Aplicar Filtros</a>
                        </div>
                    </div>
                </form>
                <div class="col-md-8 listings">
                    <div class="row count-order">
                        <div class="m-r-50">
                            <h3 class="results-count base_color--color font-weight-bold"><?php Component_Listings::results_count($paged, $posts_per_page, $total); ?></h3>
                        </div>
                        <div class="order-by row">
                            <h3 class="base_color--color font-weight-bold"><?php _e('Order by:', 'idealbiz'); ?></h3>
                            <div class="dropdown m-l-10">
                                <a class="dropdown-toggle light-blue--color" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <h3 class="d-inline-block"></h3>
                                    <i class="icon-dropdown"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" data-order="" href="#"><?php _e('Insertion Date', 'idealbiz'); ?></a>
                                    <a class="dropdown-item" data-order="price-asc" href="#"><?php _e('Price: low to high', 'idealbiz'); ?></a>
                                    <a class="dropdown-item" data-order="price-desc" href="#"><?php _e('Price: high to low', 'idealbiz'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="d-block d-md-none m-l-10 m-t-10 filter-sidebar-collapse light-blue--color"><?php _e('Filters', 'idealbiz'); ?></h3>
                    <div class="">
                        <div class="row listings-container">
                            <?php
                                if ($listings->have_posts()) :
                                    while ($listings->have_posts()) : $listings->the_post();
                                        get_template_part('/elements/listings');
                                    endwhile;
                                else :
                                    get_template_part('/elements/no_results');
                                endif;
                                ?>
                        </div>
                        <div class="pagination d-flex justify-content-center m-t-30">
                            <?php
                                $total_pages = $listings->max_num_pages;
                                Component_Listings::pagination($total_pages);
                                ?>
                        </div>
                    </div>
                </div>
                <div class="d-none ajax-data" data-ajax-url="<?php echo site_url() . '/wp-admin/admin-ajax.php?lang=' . ICL_LANGUAGE_CODE;?>"></div>
            </div>
        </section>
    <?php
    wp_reset_postdata();
    remove_filter('the_title', 'wpb_lastvisit_the_title');
    }

    if (!$premium_buyer) {

        ?>
            <div class="container text-center m-t-30 m-b-30">
                <h1><?php the_title(); ?></h1>
                <br /><br />
                <?php the_content(); ?>
            </div>

            <div class="shop-page">
                <div class="container text-center m-t-80 content-area">

                    <?php
                        $subs=getSubscriptionsOfUser(NULL, 'premium_buyer');
                        if ($subs!='') {
                            echo '<div class="woocommerce"><h1 class="m-b-60">' . __('Your Current Subscription', 'idealbiz') . '</h1>';
                            echo $subs;
                            echo '<style>.broker-subscriptions{display:none;}</style>';
                            echo '<br/><br/><br/></div>';
                        } else {
                    ?>
                    <?php
                            $params = array(
                                'post_type' => 'product',
                                'meta_query' => array(
                                    array(
                                        'key' => '_product_type_meta_key',
                                        'value' => 'premium_buyer_plan',
                                        'compare' => '='
                                    )
                                )
                            );
                            $wc_query = new WP_Query($params);
                            global $post, $product;
                            if ($wc_query->have_posts()) {

                                while ($wc_query->have_posts()) {
                                    $wc_query->the_post();
                                    $pid = get_the_ID();
                                    $product = wc_get_product($pid);

                                    global $product, $post;

                                    echo '<ul class="products plan_list row">';
                                    echo '<div class="col-md-12 columns-4 grid-container grid-container--fill">';

  

                                    if ($product->is_type('variable')) {
                                        $variations = $product->get_available_variations();

                                        foreach ($variations as $key => $value) {

                                            // var_dump($product->get_default_attributes());

                                            //  var_dump($value);

                                            $product = wc_get_product($pid);

                                            $plan_duration_days = get_post_meta($value['variation_id'], 'variation_duration', true);
                                            //echo $plan_duration_days;
                                            $ppm = '';
                                            if (!empty($plan_duration_days)) {
                                                $ppm = '<span class="ppm tiny-number blue-grey--color">' .calculatePricePerMonthWithTax($product, $value['display_price'],$plan_duration_days) . get_woocommerce_currency_symbol() . '/' . __('month', 'idealbiz') . '</span>';
                                            } 
                                            //var_dump($variations);

                                            $h = '';
                                            $variation_name = implode('/', $value['attributes']);
                                            if ($variation_name == reset($product->get_variation_default_attributes())) {
                                                $h = 'hover';
                                            }
                                            if($variation_name!=''){
                                                echo '
                                                    <li class="white--background plan_col ' . $h . '"> 
                                                        <div class="phead">
                                                            <h4 class="light-blue--color">' . get_the_title() . '<br/>(' . $variation_name . ')</h4>
                                                            <h5 class="big-number black--color"><span class="currency">' . get_woocommerce_currency_symbol() . '</span>' . calculatePriceWithTax($product,$value['display_price']) . '' . $ppm . '</h5>
                                                            <h4 class="m-t-10"' . __('by', 'idealbiz') . ' ' . plansDuration()[$plan_duration_days] . '</h4>
                                                        </div>	
                                                        <div class="pbody">
                                                        <p>'.
                                                        __('Register as a Premium Buyer and comfortably receive new business opportunities by email for','idealbiz').' '.plansDuration()[$plan_duration_days].'.'
                                                        .'</p>';
                                                            
                                                            
                                                echo '<a href="' . wc_get_checkout_url() . '?add-to-cart=' . $value['variation_id'] . '" type="submit" class="btn-plan lrm-login">' .
                                                    apply_filters("single_add_to_cart_text", __("Add to cart", "woocommerce"), $product->product_type) . '
                                                            </a>
                                                        ';

                                                echo '</div>';

                                                echo '</li>';
                                            }
                                        }
                                    }
                                    
                                    echo '</div></ul>
                                    </form>';
                                } // end while

                            } // end if

                        }

                        wp_reset_postdata();
                        ?>

                </div>
            </div>

        <?php
        }
        ?>


        </section>
        <?php //whiteBackground(); 
        ?>

        <?php


        //save date user entered here
        update_user_meta(get_current_user_id(), 'premium_buyer_last_view', date("Y-m-d H:i:s"));
        //to testing
        //update_user_meta( get_current_user_id(), 'premium_buyer_last_view', '2020-03-03 11:59:33' );


        //echo '<pre>';
        //var_dump($p);
        //echo '</pre>';
        remove_filter('the_title', 'wpb_lastvisit_the_title');
        ?>
        <div class="sidebar-overlay"></div>
        <?php get_footer(); ?>