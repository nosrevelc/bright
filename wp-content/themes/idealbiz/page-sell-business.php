<?php
// Template Name: Sell Business
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
            'value' => 'listing_plan',
            'compare' => '='
        )
    ),
    'orderby'        => 'meta_value_num',
    'meta_key'       => '_price',
    'order'          => 'asc'
);
$wc_query = new WP_Query($params);
$broker = isBroker(NULL, pll_current_language());
$num_cols=0;
?>

<section class="page sell-business">

    <div class="container text-center m-t-30">
        <!-- <h1 class="m-b-15"><?php _e('The best place to sell your business. Create your listing in less than 1 minute.', 'idealbiz'); ?></h1> -->
        <?php the_content(); ?>
    </div>
<?php if ($hidden_table_price_sell){?>
    <div class="price-table shop-page m-t-25 col-md-12 d-flex justify-content-center">
        <?php

        do_action('woocommerce_before_main_content'); ?>

        <div class="block stroke dropshadow p-t-0 p-b-0 m-b-25 b-r-5 white--background plist-listing change--link">

            <?php woocommerce_product_loop_start(); ?>

            <li class="<?php echo $broker ? 'col-md-4' : 'col-md-3' ; ?> legends">
                <div class="phead">
                    <h2 class="blue--color text-left">
                        <?php _e('Choose our services features', 'idealbiz'); ?>
                    </h2>
                </div>
                <div class="pbody">

                </div>
                <?php $num_cols++; ?>
            </li>

            <?php

            if ($wc_query->have_posts()) {
                $pcolumn = 1;
                
                while ($wc_query->have_posts()) : $wc_query->the_post();
                    $product = wc_get_product($wc_query->post);
                    $pid = wc_get_product(get_the_ID());
                    $f = '';
                    if ($product->is_featured()) {
                        $f = 'featured ';
                    }

                    if ($broker) {
                        if ($broker['variation_duration'] == get_post_meta(get_the_ID(), 'plan_duration', true)) {
                            ?>
                            <li data-pid="<?php echo get_the_ID(); ?>" data-attr="pcolumn-<?php echo $pcolumn++; ?>" <?php wc_product_class($f . 'col-md-8', $product); ?>>
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
                                        $product = wc_get_product($pid);
                                        if ($product->is_featured()) {
                                            echo '<span class="featured-info">' . __('Best Value!', 'idealbiz') . '</span>';
                                        }
                                        //wc_get_template_part( 'content', 'product' );
                                        wc_get_template_part('content-single-product-listing');
                                        ?>
                        </li>
                        <?php $num_cols++; ?> 
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
<div class="container text-center m-t-30">
<a  id="btn_sell_bussines" class="btn btn-blue m-t-5 white--background h-36px l-h-18 lrm-login to-submit" href="<?php echo get_permalink(pll_get_post(get_page_by_path('listings/submit-listing')->ID)); ?>"><?php _e('str_Add listing', 'idealbiz') ?></a>
</div>
</section>

<style>
<?php 
$max_width= 100/$num_cols;
?>
@media (min-width: 768px){
    .plist .col-md-3 {
        -ms-flex: 0 0 <?php echo $max_width ?>%;
        flex: 0 0 <?php echo $max_width ?>%;
        max-width: <?php echo $max_width ?>%;
    } 
}
</style>


<?php get_footer(); ?>