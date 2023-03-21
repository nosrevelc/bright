
<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 * 
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */



defined( 'ABSPATH' ) || exit;

global $product;
global $bought_upgrades;
global $wp;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
/* do_action( 'woocommerce_before_single_product' ); */

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

?>
<div id="product-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<?php

	
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'woocommerce_before_single_product_summary' );
	?>
	
	<div class="summary entry-summary black--color">
		<?php
		
		$plan_duration_days = get_post_meta( get_the_ID(), 'plan_duration', true );

		$ptype = get_post_meta($post->ID, '_product_type_meta_key', true);
	
		global $bought_upgrades;
		if($_GET['ptype'] == 'upgrade'){
				$order_of_product = get_field('active_order',$_GET['id']);
				$bought_upgrades= array();
				if(is_it_a_shop_order($order_of_product)){
					$o = new WC_Order($order_of_product);
					foreach( $o->get_items() as $item ){
						$product_id = $item->get_product_id();
						$all_purchased_days = get_post_meta( $product_id, 'plan_duration', true );
						$bought_upgrades['product_id'] = $product_id;
						$bought_upgrades['all_purchased_days'] = $all_purchased_days;
						foreach ($item->get_meta_data() as $metaData) {
							$attribute = $metaData->get_data();
							$bought_upgrades[] = $attribute['key'];
						}  
					}
				}
				if(!$bought_upgrades['product_id']){
						$posts = $wpdb->get_results("SELECT * FROM $wpdb->postmeta
						WHERE meta_key = '_product_type_meta_key' AND  meta_value = 'upgrade_plan' LIMIT 1", ARRAY_A);  
						$bought_upgrades['product_id']=$posts[0]["post_id"];
				}

				if($all_purchased_days <= 0){
					$all_purchased_days=90;
					$bought_upgrades['all_purchased_days'] = $all_purchased_days;
				}

				$date_expire='';
				$format_in = 'Ymd';
				$format_out = 'Y-m-d';
				if(get_field('expire_date',$_GET['id'])){
					$date_aux = DateTime::createFromFormat($format_in, get_field('expire_date',$_GET['id']));
					$date_expire= $date_aux->format( $format_out ) . ' 00:00:00';
				}else if($o->order_date){
					$date_expire = $o->order_date;
					$date_expire = strtotime($o->order_date."+ ".$all_purchased_days." days");
				}else{
					$date_expire = '2020-12-31 00:00:00';
				}

				/*
				$now = time();
				//$mod_date = strtotime($date_expire."+ ".$all_purchased_days." days");
				$mod_date = strtotime($date_expire." days");


				echo '<br/>'.$mod_date;
				echo '<br/>'.$now;

				//$datediff = $mod_date - $now;
				$datediff = $date_expire - $now;
				echo '<br/>'.$datediff;

				$billing_days= round($datediff / (60 * 60 * 24));
				*/
				$now = time();
				$your_date = strtotime($date_expire);
				$datediff = $your_date - $now;
				$billing_days =  round($datediff / (60 * 60 * 24));

				$bought_upgrades['billing_days'] = $billing_days;
			}
		

		$ppm='';
		$ppmNumber = round(($product->get_regular_price()/(daysToMonth()[$plan_duration_days])),2);
		if (!empty($plan_duration_days) && $ptype != 'upgrade_plan') {
			$broker_desc= '';
			$b=isBroker(NULL, pll_current_language());

			if($b){
				if (countListsOfBroker() < $b['list_slots']) {
					$broker_desc= '('.__('Included in active Broker Subscription','idealbiz').')<br/>';
				}else{
					$broker_desc= '('.__('Broker Subscription Extra Listing','idealbiz').')<br/>';
				}
			}
			//Editado Pleo Cleverson esconde preço por mês
			/* $ppm='<span class="ppm tiny-number blue-grey--color">
			'.$broker_desc.'
			'.( $ppmNumber == 0 ? '' : ''.$ppmNumber.get_woocommerce_currency_symbol().'/'.__('month', 'idealbiz') ).'
			</span>'; */
		}
		?>
		
		<div class="phead"> 


			<?php if($ptype == 'upgrade_plan'){ ?>
				<h2 class="blue--color"><?php echo $product->get_title(); ?></h2>
				<h4 class="m-t-10"><?php echo __('for', 'idealbiz').' '.$bought_upgrades['billing_days']. ' '. __('Days','idealbiz'); ?></h4>
			<?php }else{ ?>
				
					<h4 class="blue--color"><?php echo $product->get_title(); ?></h4>
					<h5 class="big-number black--color">
						<span class="currency"><?php echo get_woocommerce_currency_symbol().'</span>'.$product->get_regular_price(); ?><?php echo $ppm; ?>
					</h5>
					<a href="<?php echo wc_get_checkout_url(); ?>" class="d-none btn-blue select-or-checkout"><?php _e('Register Today', 'idealbiz'); ?></a>
					<h4 class="m-t-10">
					<?php echo __('by', 'idealbiz').' '.plansDuration()[$plan_duration_days]; ?>
					</h4>			
				<?php } ?>	
		
		</div>
			
		<div class="ptop">
			<?php
			/* @hooked woocommerce_template_single_title - 5 
			* @hooked woocommerce_template_single_rating - 10
			* @hooked woocommerce_template_single_price - 10 */
			do_action( 'woocommerce_single_product_head' );
			?>
		</div>
		<div class="pbody">
			<?php 
			/**
			 * Hook: woocommerce_single_product_summary. 
			 *
			 * 
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			
			// "Coloca as Opções do produito Ex.: Destaque" do_action( 'woocommerce_single_product_summary' );


			/* do_action( 'woocommerce_after_shop_loop_item' ); */


			?>
		</div>
	</div>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );

	
	$cl_add_cart = home_url($wp->request).'/?add-to-cart='.$product->id;
	

	?>
	<a class="btn btn-blue m-l-10 font-btn-plus blue--hover" href="<?php echo $cl_add_cart;?>" ><?php _e('str_Select_this_Pack','idealbiz'); ?></a>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
<style>

.shop-page .content-area .plist .summary .phead .big-number{
	font-family: var(--font-default), Arial;
	font-weight: 900;
	color:#353535;
}

</style>