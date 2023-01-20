<?php
/**
 * Cart item data (when outputting non-flat)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-item-data.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version 	2.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $pID;
global $wpdb;
global $bought_upgrades;


?>
<ul class="variation text-left">
	<?php 
	
	foreach ( $item_data as $data ) : 
		$select = "SELECT v.value_id, v.option_id, v.product_id, v.title, v.price, v.sort_order, o.slug
		FROM ".$wpdb->prefix."pofw_product_option_value as v LEFT JOIN ".$wpdb->prefix."pofw_product_option as o ON v.option_id = o.option_id 
		WHERE v.title ='".$data['display']."' AND v.product_id=".$pID; 
		//echo $select;
		$rows = $wpdb->get_results($select, ARRAY_A);
		/********* this make broker EN option at reduced price ********* 
		if(is_admin()){
		}else{
			foreach(getLangSlug('all') as $actL){
				$b= isBroker(NULL,$actL);
				if($b){
					for($x=0; $x<count($rows); $x++){
						if($rows[$x]['slug'] == 'published_'.$b['publish_in_language']){
							if(countListsOfBroker(get_current_user_id(),$b['publish_in_language']) < $b['list_slots']){
								$rows[$x]['price'] = $b['listing_price_override'];
							}
						}
					}
				}
			}
		}
		*/


	    $upgrade=0;
        if(get_post_meta($pID, '_product_type_meta_key', true) == 'upgrade_plan'){
			$upgrade=1;

			$related_post_id=$_POST['related_post_id'];
			if(!$_POST['related_post_id']){
				$related_post_id= explode('&id=',$_SERVER['HTTP_REFERER'])[1];
				if(!$related_post_id){
					$related_post_id = str_replace("related_post_id=", "", explode("&", $_POST['post_data'])[0] ); 
				}
			}


			$order_of_product = get_field('active_order',$related_post_id);
			$all_purchased_days=90;

			$bought_upgrades= array();
			if(is_it_a_shop_order($order_of_product)){

				$o = new WC_Order( $order_of_product );

				foreach( $o->get_items() as $item ){
					$product_id = $item->get_product_id();
					$all_purchased_days = get_post_meta( $product_id, 'plan_duration', true );
					
				//	echo $product_id.'-----';

					$bought_upgrades['product_id'] = $product_id;
					$bought_upgrades['all_purchased_days'] = $all_purchased_days;
			
					foreach ($item->get_meta_data() as $metaData) {
						$attribute = $metaData->get_data();
						$bought_upgrades[] = $attribute['key'];
					}  
					

				}
			}
			if(!$bought_upgrades['product_id']){
				global $wpdb;
					$posts = $wpdb->get_results("SELECT * FROM $wpdb->postmeta
					WHERE meta_key = '_product_type_meta_key' AND  meta_value = 'upgrade_plan' LIMIT 1", ARRAY_A);  
		
					$bought_upgrades['product_id']=$posts[0]["post_id"];
			}
		
			$date_expire='';
			$format_in = 'Ymd';
			$format_out = 'Y-m-d';
			if(get_field('expire_date',$related_post_id)){
				$date_aux = DateTime::createFromFormat($format_in, get_field('expire_date',$related_post_id));
				$date_expire= $date_aux->format( $format_out ) . ' 00:00:00';
			}else if($o->order_date){
				$date_expire = $o->order_date;
			}else{
				$date_expire = '2020-12-31 00:00:00';
			}
		
			if($all_purchased_days <= 0 || !$bought_upgrades['all_purchased_days']){
				$all_purchased_days=90;
				$bought_upgrades['all_purchased_days'] = $all_purchased_days;
			}
			/*
			$now = time();
			$mod_date = strtotime($date_expire."+ ".$all_purchased_days." days");
			$datediff = $mod_date - $now;
			*/

			$now = time();
			$your_date = strtotime($date_expire);
			$datediff = $your_date - $now;
			$billing_days =  round($datediff / (60 * 60 * 24)); 

			$bought_upgrades['billing_days'] = $billing_days;


			$product_opt = $wpdb->get_row( 'SELECT * 
			FROM ib_'.get_current_blog_id().'_pofw_product_option_value 
			WHERE product_id = '.$bought_upgrades['product_id'].'
			AND title="'.$data['display'].'"');

			$iscertified = $wpdb->get_row( 'SELECT * 
				FROM ib_'.get_current_blog_id().'_pofw_product_option
				WHERE option_id = '.$product_opt->option_id.'
				AND slug="certified"');
				if($iscertified){
					$formatted_price = number_format(($product_opt->price * 1) / 1,2,'.','');
				}else{
					$formatted_price = number_format(($product_opt->price * $bought_upgrades['billing_days']) / $bought_upgrades['all_purchased_days'],2,'.','');
				}
                          

			$rows[0]['price'] = $formatted_price;
			//echo '<br/>';

			//echo  $bought_upgrades['billing_days'];
			//echo '<br/>';
			//echo $bought_upgrades['all_purchased_days'];

			//echo $related_post_id;

		}

		?>
		
		<li class="h-35px d-flex">
			<p class="font-weight-semi-bold w-p-100 l-h-35 m-b-0 <?php echo sanitize_html_class( 'variation-' . $data['display'] ); ?>" data-select="<?php echo sanitize_html_class( 'variation-' . $data['display'] ); ?>">
				<?php 
				echo $data['display']; ?>
			</p>
			<p class="w-60px m-b-0 l-h-38 text-right">
			
				<?php 
				
				echo get_woocommerce_currency_symbol().($rows[0]['price'] + 0); 
				?>
			</p>
		</li>
	<?php endforeach; ?>
</ul> 
