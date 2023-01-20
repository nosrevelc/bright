<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */




defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 ); 
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_head', 'woocommerce_template_single_price', 10 );
 

?>
<section class="page shop-page shop-category_<?php echo $scs; ?>">
	<div class="container medium-width text-center">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title m-t-15 m-b-65">
		<?php
			if(isset($_GET['renew'])){
				echo __('Renew','idealbiz').'&nbsp;';
			}
			woocommerce_page_title(); 
		?>
		</h1>
	<?php endif; ?>

	<div class="row">
	<header class="woocommerce-products-header" style="width: 100%; margin-bottom:30px;">
			<?php
			/**
			 * Hook: woocommerce_archive_description.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			do_action( 'woocommerce_archive_description' );
			?>
		</header>
		<div class="col-md-9">
			<?php
		/**
		 * Hook: woocommerce_before_main_content.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */
		do_action( 'woocommerce_before_main_content' );

		?>

		<div class="block stroke dropshadow p-t-0 p-b-0 m-b-25 b-r-5 white--background <?php if($_GET['ptype']=='listing' || $_GET['ptype']=='wanted' || $_GET['ptype']=='upgrade'){ echo 'plist-listing'; } ?>">
			<?php
			$num_cols=0;
			if ( woocommerce_product_loop() ) { 

				/**
				 * Hook: woocommerce_before_shop_loop.
				 *
				 * @hooked woocommerce_output_all_notices - 10
				 * @hooked woocommerce_result_count - 20 
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );

				woocommerce_product_loop_start();

				if($_GET['ptype']=='listing' || $_GET['ptype']=='wanted' || $_GET['ptype']=='upgrade' ){ ?>
					<li class="col-md-3 legends"> 
						<div class="phead">
							<h2 class="blue--color text-left">
							<?php 
								if(isset($_GET['renew'])){
									echo __('Renew your service features','idealbiz').'&nbsp;';
								}else{
									_e('Choose our services features', 'idealbiz');
								}
								?>
							</h2>
						</div>	
						<div class="pbody">

						</div>
					</li>
					<?php $num_cols++; ?>
				<?php }

				if ( wc_get_loop_prop( 'total' ) ) {
					$pcolumn=1; 
					$broker = isBroker(NULL, pll_current_language());

					$i=0;
					while ( have_posts() ) {
						
						the_post(); 
						/**
						 * Hook: woocommerce_shop_loop.
						 */
						$f='';
						if($product->is_featured()){
							$f='featured ';
						}
						
						
						global $woocommerce;
						$items = $woocommerce->cart->get_cart();
						// NPMM - Não identifiquei o motivo de loop
						foreach($items as $item => $values) { 
							/* var_dump($values['data']->get_id()); */
						} 
						
							//SELECIONA O LISTING QUE DEVE APARECER NO PLANO DE BROKER 
							$plano_broker = get_field('default_broker');
							if($broker){
								if($broker['variation_duration'] == get_post_meta(get_the_ID(), 'plan_duration', true)){
									if ($plano_broker == true){
									?>
									<li data-pid="<?php echo get_the_ID(); ?>" data-attr="pcolumn-<?php echo $pcolumn++; ?>" <?php wc_product_class( $f.'col-md-3', $product ); ?> data-ptype="<?php echo get_post_meta($post->ID, '_product_type_meta_key', true); ?>">
									<?php
										if($product->is_featured()){ echo '<span class="featured-info">'.__('Best Value!', 'idealbiz').'</span>'; }
										do_action( 'woocommerce_shop_loop' );
										//wc_get_template_part( 'content', 'product' );
										wc_get_template_part( 'content-single-product-listing' );
									?>
									<?php /* var_dump(get_post_meta($post->ID, '_product_type_meta_key', true)); */?>
									</li>
									<?php }
									$num_cols++; 
									?>
									<?php
								}
							}else{
								?>
								<?php
								/* var_dump($values['data']); */
								if ($values['data']->get_id()==$product->get_id()){

								if($_GET['ptype']=='upgrade'){ ?>
									<li data-pid="<?php echo get_the_ID(); ?>" data-attr="pcolumn-<?php echo $pcolumn++; ?>" <?php wc_product_class( $f.'col-md-9', $product ); ?> data-ptype="<?php echo get_post_meta($post->ID, '_product_type_meta_key', true); ?>">
								<?php } else { ?>
									<li data-pid="<?php echo get_the_ID(); ?>" data-attr="pcolumn-<?php echo $pcolumn++; ?>" <?php wc_product_class( $f.'col-md-3', $product ); ?> data-ptype="<?php echo get_post_meta($post->ID, '_product_type_meta_key', true); ?>">
								<?php } ?>
								<?php
								
									if($product->is_featured()){ echo '<span class="featured-info">'.__('Best Value!', 'idealbiz').'</span>'; }
									do_action( 'woocommerce_shop_loop' );
									//wc_get_template_part( 'content', 'product' );
									
									wc_get_template_part( 'content-single-product-listing' );
									
								?>
								</li>
								
								<?php $num_cols++; ?>
								<?php
							}
						}
						
					}
				}

				woocommerce_product_loop_end(); 

				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			} else {
				/**
				 * Hook: woocommerce_no_products_found.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			}

			/**
			 * Hook: woocommerce_after_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );

			?>
		</div>
		<div class="col-md-3">
		<?php

		/**
		 * Hook: woocommerce_sidebar.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		
		if($_GET['ptype']=='listing' || $_GET['ptype']=='wanted' || $_GET['ptype']=='upgrade' ){
			dynamic_sidebar( 'listing-packs-sidebar' );
		}
		
		//do_action( 'woocommerce_sidebar' );

		?>
		</div>
	</div>	
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
.shop-page .content-area .plist .legends .pbody span{
	font-size : 1.35em !important;
}
</style>

<?php whiteBackground(); ?>

<?php


get_footer( 'shop' );
