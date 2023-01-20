<?php
// Template Name: Add Wanted Business
get_header();

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action('woocommerce_single_product_head', 'woocommerce_template_single_price', 10);

$hidden_table_price_sell = get_field('hidden_table_price_sell');

$params = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => '_product_type_meta_key',
            'value' => 'wanted_plan',
            'compare' => '='
        )
    ),
    'orderby'        => 'meta_value_num',
    'meta_key'       => '_price',
    'order'          => 'asc'
);
$wc_query = new WP_Query($params);
?>

<section class="page wanted-business">

    <div class="container text-center m-t-30">
       <!--  <h1 class="m-b-15"><?php _e('The best place to look for a business. Create your ad in less than 1 minute.', 'idealbiz'); ?></h1> -->
        <?php the_content(); ?>
        <a id="btn_wanted" class="btn btn-blue m-t-5 white--background h-36px l-h-18 lrm-login to-submit" href="<?php echo getLinkByTemplate('submit-wanted.php'); ?>"><?php _e('str_Add wanted business', 'idealbiz') ?></a>
    </div>
    <?php if ($hidden_table_price_sell){?>
    <div class="price-table shop-page m-t-25 col-md-12 d-flex justify-content-center">
        <?php

        do_action('woocommerce_before_main_content'); ?>

        <div class="block stroke dropshadow p-t-0 p-b-0 m-b-25 b-r-5 white--background plist-listing change--link">

            <?php woocommerce_product_loop_start(); ?>

            <li class="col-md-3 legends">
                <div class="phead">
                    <h2 class="blue--color text-left">
                        <?php _e('Choose our services features', 'idealbiz'); ?>
                    </h2>
                </div>
                <div class="pbody">

                </div>
            </li>

            <?php

            if ($wc_query->have_posts()) {
                $pcolumn = 1;
                $broker = isBroker(NULL, pll_current_language());
                while ($wc_query->have_posts()) : $wc_query->the_post();
                    $product = wc_get_product($wc_query->post);
                    $f = '';
                    if ($product->is_featured()) {
                        $f = 'featured ';
                    }

                    if ($broker) {
                        if ($broker['variation_duration'] == get_post_meta(get_the_ID(), 'plan_duration', true)) {
                            ?>
                            <li data-pid="<?php echo get_the_ID(); ?>" data-attr="pcolumn-<?php echo $pcolumn++; ?>" <?php wc_product_class($f . 'col-md-9', $product); ?>>
                                <?php
                                                if ($product->is_featured()) {
                                                    echo '<span class="featured-info">' . __('Best Value!', 'idealbiz') . '</span>';
                                                }
                                                //wc_get_template_part( 'content', 'product' );
                                                wc_get_template_part('content-single-product-listing');
                                                ?>
                            </li>
                        <?php
                                    }
                                } else {
                                    ?>
                        <li data-pid="<?php echo get_the_ID(); ?>" data-attr="pcolumn-<?php echo $pcolumn++; ?>" <?php wc_product_class($f . 'col-md-3', $product); ?>>
                            <?php
                                        if ($product->is_featured()) {
                                            echo '<span class="featured-info">' . __('Best Value!', 'idealbiz') . '</span>';
                                        }
                                        //wc_get_template_part( 'content', 'product' );
                                        wc_get_template_part('content-single-product-listing');
                                        ?>
                        </li>
            <?php
                    }
                endwhile;
            } else {
                do_action('woocommerce_no_products_found');
            }
            woocommerce_product_loop_end();
            wp_reset_postdata();

            do_action('woocommerce_after_main_content');
            ?>

        </div>
    </div>

    <?php }?>
</section>



<?php get_footer(); ?>