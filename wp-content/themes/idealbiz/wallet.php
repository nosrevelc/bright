<?php // Template Name: Wallet

if(!is_user_logged_in()){
    wp_redirect(home_url());
}

get_header();

?>

<?php
 if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $meta = get_post_meta( $post->ID, 'post_fields', true ); 
    
 ?>

<?php endwhile; endif; 

?>
<section class="page">
    <div class="container text-center m-t-30">
        <h1><?php the_title(); ?></h1>
    </div>
    <br/><br/>
<div class="container medium-width text-center">
    <div class="row">
        <div class="col-md-9">
            <div class="block stroke dropshadow p-15 m-b-25 b-r-5 white--background plist-listing">
                <div class="phead" style="height: 92px;">
                    <h2 class="blue--color text-left">
                        Choose our services features
                    </h2>
                </div>	
                <div class="pbody">

                    
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="buy_points block stroke dropshadow p-t-25 p-b-10 m-b-25 b-r-5 white--background">
            <h2 class="widget-title m-b-10"><?php _e('My iDealBiz Coins', 'idealbiz'); ?></h2>
                <div class="buy_points_content">

                    <div class="text-center d-flex">
                                <p class="font-weight-semi-bold w-p-100 l-h-35 m-b-0">
                                </p> 
                                <p class="w-60px m-b-0 l-h-38 text-right">
                                <?php echo do_shortcode('[mycred_my_balance]'); ?>
                                </p>					
                    </div>

                    <p class="icoin-total">
                        <strong>Subtotal:</strong> 
                        <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol">€</span>800</span>	
                        </p>

                    
                    <p class="woocommerce-mini-cart__buttons buttons"><a href="http://idealbiz.lc/pt/en/cart/" class="button wc-forward">Ver carrinho</a><a href="http://idealbiz.lc/pt/en/checkout/" class="button checkout wc-forward">Finalizar compras</a></p>

                    

                </div>
            </div>
        </div>
    </div>    
</div> 

    <div class="shop-page d-none">
        <div class="container text-center m-t-80 content-area">


        <?php
        $subs= getSubscriptionsOfUser();
        if($subs!=''){
            echo '<div class="woocommerce"><h1 class="m-b-60">'. __('Current Subscriptions', 'idealbiz').'</h1>';
            echo $subs;
            echo '<style>.premium-buyer-subscriptions{display:none;}</style>';
            echo '<br/><br/><br/></div>';
        }
        ?>

            <?php
            $params = array(
                'post_type' => 'product',
                'meta_query' => array(
                    array(
                        'key' => '_product_type_meta_key',
                        'value' => 'broker_plan',
                        'compare' => '='
                    )
                )
            );
            $wc_query = new WP_Query($params);
            global $post, $product;
            if( $wc_query->have_posts() ) {  
               
                    while( $wc_query->have_posts() ) {
                        $wc_query->the_post();
                        $pid= get_the_ID();
                        $product = wc_get_product(get_the_ID());
                        global $product, $post;

                        

                            if ( $product->is_type( 'variable' ) ) {
                                $variations = $product->get_available_variations();

                                $pAtts = $product->get_attributes();
                                foreach ($pAtts as $pk => $pa){
                                    foreach ($pa['options'] as $pot){ // $pot = language var_dump($pot);

                                        if (strpos($subs, $pot) !== false) {

                                        }else{    
                                            if(count($pa['options']) > 1){
                                                echo '<h1 class="m-b-80">'.__('To publish in','idealbiz').' '.$pot.'</h1>';
                                            }
                                            echo '<ul class="products plan_list row'.(count($pa['options'])>1 ? 'm-b-120' : '').'">';
                                            echo '<div class="col-md-12 columns-4 grid-container grid-container--fill">';
                                            foreach ($variations as $key => $value){
                                                if( in_array( $pot ,$value['attributes'] ) ){
                                                    $plan_duration_days = get_post_meta( $value['variation_id'], 'variation_duration', true );
                                                    $ppm='';
                                                    if (!empty($plan_duration_days)) {
                                                        $ppm='<span class="ppm tiny-number blue-grey--color">'.round(($value['display_price']/($plan_duration_days/30)),2).get_woocommerce_currency_symbol().'/'.__('month', 'idealbiz').'</span>';
                                                    }
                                                    $h='';
                                                    $variation_name = implode('/', array_reverse($value['attributes']));
                                                    if(count($pa['options']) > 1){
                                                        if(reset(array_reverse($value['attributes'])) == reset(array_slice($product->get_variation_default_attributes(), 1, 1))){
                                                            $h='hover';
                                                        }
                                                    }else{
                                                        if($variation_name == reset($product->get_variation_default_attributes())){
                                                            $h='hover';
                                                        }
                                                    }
                                                    echo '<li class="white--background plan_col '.$h.'"> 
                                                            <div class="phead">
                                                                <h4 class="light-blue--color">'.get_the_title().'<br/>('.$variation_name.')</h4>
                                                                <h5 class="big-number black--color"><span class="currency">'.get_woocommerce_currency_symbol().'</span>'.$value['display_price'].''.$ppm.'</h5>
                                                                <h4 class="m-t-10"'. __('by', 'idealbiz').' '.plansDuration()[$plan_duration_days].'</h4>
                                                            </div>	
                                                            <div class="pbody">
                                                                <p>'.$value["variation_description"].'</p>';
                                                        echo '<a href="'.wc_get_checkout_url().'?add-to-cart='.$value['variation_id'].'" type="submit" class="btn-plan">'.
                                                                    apply_filters("single_add_to_cart_text", __( "Add to cart", "woocommerce" ), $product->product_type).'
                                                                </a>';
                                                        echo'</div>';
                                                    echo '</li>';
                                                }
                                            }
                                            echo '</div></ul>';
                                        }
                                    }
                                break;
                                }
                               // echo '</pre>';
                            }
                    echo '</form>';

                } // end while
           
            } // end if
            
            wp_reset_postdata();
            ?>

        </div>
    </div>

    <?php whiteBackground(); ?>
</section>

<br/><br/>
         <?php the_content(); ?>

<?php
//echo '<pre>';
//var_dump($p);
//echo '</pre>';
?>

<?php get_footer(); ?>