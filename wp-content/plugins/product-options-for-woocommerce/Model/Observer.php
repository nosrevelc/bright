<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Model_Observer {  

  protected $_productOptions = array();          
  protected $_poPriceAdded = array(); 
                
      
  public function __construct(){
    add_action('woocommerce_add_to_cart_validation', array($this, 'validate_selected_options'), 10, 2); 
    add_action('woocommerce_add_cart_item_data', array($this, 'save_selected_options'), 10, 2);    
    add_filter('woocommerce_get_item_data', array($this, 'display_selected_options_on_checkout'), 10, 2);
    add_action('woocommerce_new_order_item', array($this, 'display_selected_options_with_order_info'), 1, 3);  
    add_action('woocommerce_before_calculate_totals', array($this, 'add_option_price_on_checkout'), 99);	
		add_action('delete_post', array($this, 'delete_post'));    	          		
  }	


  public function getOptionModel(){
    include_once(Pektsekye_PO()->getPluginPath() . 'Model/Option.php');		
    return new Pektsekye_ProductOptions_Model_Option();    
  }  
  
  
  public function getProductOptions($productId){
    if (!isset($this->_productOptions[$productId])){    
      $this->_productOptions[$productId] = $this->getOptionModel()->getProductOptions($productId);
    }
    return $this->_productOptions[$productId];
  }


  public function validate_selected_options($isValid, $productId){
         
    if (!$isValid){
      return false;    
    }
    
    foreach ($this->getProductOptions($productId) as $option){
      $oId = (int) $option['option_id'];      
      if ($option['required'] == 1 && (empty($_POST['pofw_option'][$oId]) || (is_array($_POST['pofw_option'][$oId]) && $_POST['pofw_option'][$oId][0] == ''))){                     
        $isValid = false;
        wc_add_notice(__('Please specify the product required option(s).', 'product-options-for-woocommerce' ), 'error');
        break;       
      }              											                                                    
    }  
      
    return $isValid;
  }


  public function save_selected_options($cart_item_data, $product_id){ 
    if (isset($_POST['pofw_option'])) {   
      $optionValues = (array) $_POST['pofw_option'];
      foreach($optionValues as $oId => $value){

        if (is_array($value)){
          $value = array_map('intval', $value);
        } elseif (ctype_digit($value)){
          $value = (int) $value;
        } else {
          $value = sanitize_textarea_field(stripslashes($value));
        }
        $cart_item_data['pofw_option'][$oId] = $value;        
      }
    }
    return $cart_item_data;
  }


  public function display_selected_options_on_checkout($cart_data, $cart_item){
    
    $custom_items = array(); 
    if (isset($cart_item['pofw_option'])) {

    if ( function_exists('pll_the_languages') ) {
      $cart_item['product_id'] = pll_get_post($cart_item['product_id']);
    }

      $custom_items = $this->formatSelectedValues($cart_item['product_id'], $cart_item['pofw_option']);
    }
    return array_merge((array)$cart_data, $custom_items);
  }
 

  public function display_selected_options_with_order_info($item_id, $values, $cart_item_key){
    if (isset($values->legacy_values['pofw_option'])){   

      if ( function_exists('pll_the_languages') ) {
        $productid= pll_get_post($values->legacy_values['product_id']);
      }

      $selectedValues = $this->formatSelectedValues($productid, $values->legacy_values['pofw_option']);      
      foreach ($selectedValues as $value){
        wc_add_order_item_meta($item_id, $value['name'], $value['value']);
      }
    }
  }


  public function add_option_price_on_checkout($cart){

    global $bought_upgrades;
    foreach (WC()->cart->get_cart() as $key => $citem) {
    
      if (!isset($citem['pofw_option']) || isset($this->_poPriceAdded[$key]))
        continue;
        
      $selectedValues = $citem['pofw_option'];
               
      $productId = $citem["variation_id"] == 0 ? $citem["product_id"] : $citem["variation_id"];
      
      $_product = wc_get_product($productId);
      $upgrade=0;
      if(get_post_meta($productId, '_product_type_meta_key', true) == 'upgrade_plan'){
        $upgrade=1;


        $related_post_id=$_POST['related_post_id'];
        if(!$_POST['related_post_id']){
          $related_post_id= explode('&id=',$_SERVER['HTTP_REFERER'])[1];
          if(!$related_post_id){
            $related_post_id = str_replace("related_post_id=", "", explode("&", $_POST['post_data'])[0] ); 
          }
        }

          $order_of_product = get_field('active_order', $related_post_id);
         $bought_upgrades= array();
         $all_purchased_days=90;
        if(is_it_a_shop_order($order_of_product)){
          $o = new WC_Order( $order_of_product );
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
              global $wpdb;
                  $posts = $wpdb->get_results("SELECT * FROM $wpdb->postmeta
                  WHERE meta_key = '_product_type_meta_key' AND  meta_value = 'upgrade_plan' LIMIT 1", ARRAY_A);  
      
                  $bought_upgrades['product_id']=$posts[0]["post_id"]; 
          }
      
          $date_expire='';
          $format_in = 'Ymd';
          $format_out = 'Y-m-d';
          if(get_field('expire_date', $related_post_id)){
              $date_aux = DateTime::createFromFormat($format_in, get_field('expire_date', $related_post_id));
              $date_expire= $date_aux->format( $format_out ) . ' 00:00:00';
          }else if($o->order_date){
              $date_expire = $o->order_date;
          }else{
              $date_expire = '2020-12-31 00:00:00';
          }
      
          if($all_purchased_days <= 0 ){
              $all_purchased_days=90;
          }
          $bought_upgrades['all_purchased_days'] = $all_purchased_days;
          /*
          $now = time();
          $mod_date = strtotime($date_expire."+ ".$all_purchased_days." days");
          $datediff = $mod_date - $now;
          $billing_days= round($datediff / (60 * 60 * 24));
          */
          $now = time();
          $your_date = strtotime($date_expire);
          $datediff = $your_date - $now;
          $billing_days =  round($datediff / (60 * 60 * 24));

          $bought_upgrades['billing_days'] = $billing_days;

          //echo $bought_upgrades['product_id'];
      
      }


      $orgPrice = $citem["data"]->get_regular_price();//$_product->get_price();

      
      $optionPrice = 0;
        
      foreach ($this->getProductOptions($productId) as $oId => $option){
        if (!isset($selectedValues[$oId])){
          continue;
        }

        $selectedValue = $selectedValues[$oId];
        
        if ($option['type'] == 'drop_down' || $option['type'] == 'radio'){
          if (is_array($selectedValue)){
            continue;
          }
          $vId = (int) $selectedValue;
          if (isset($option['values'][$vId])){
            
            
            if($upgrade){
              $optionPrice +=  (((float) $option['values'][$vId]['price'] * $bought_upgrades['billing_days']) / $bought_upgrades['all_purchased_days']);
            }else{
              $optionPrice += (float) $option['values'][$vId]['price'];
            }

          }
        } elseif ($option['type'] == 'multiple' || $option['type'] == 'checkbox'){
          foreach ((array) $selectedValue as $vId){
            if (isset($option['values'][$vId])){

              if($upgrade){
                global $wpdb;
                $product_opt = $wpdb->get_row( 'SELECT * 
                FROM ib_'.get_current_blog_id().'_pofw_product_option_value 
                WHERE product_id = '.$bought_upgrades['product_id'].'
                AND title="'.$option['values'][$vId]['title'].'"');
                  $iscertified = $wpdb->get_row( 'SELECT * 
                  FROM ib_'.get_current_blog_id().'_pofw_product_option
                  WHERE option_id = '.$product_opt->option_id.'
                  AND slug="certified"');
                  if($iscertified){
                    $optionPrice +=  (((float) $product_opt->price * 1) / 1);
                   // $optionPrice+=3;
                  }else{
                    //echo $product_opt->price;
                    //echo $bought_upgrades['billing_days'];
                    //echo $bought_upgrades['all_purchased_days'];
                    $optionPrice +=  (((float) $product_opt->price * $bought_upgrades['billing_days']) / $bought_upgrades['all_purchased_days']);
                    //$optionPrice+=$bought_upgrades['all_purchased_days'];
                  }
               }else{
                $optionPrice += (float) $option['values'][$vId]['price'];
                //$optionPrice+=1;
              }
              
            }            
          }          
        } elseif ($option['type'] == 'field' || $option['type'] == 'area'){
          if (is_array($selectedValue)){
            continue;
          }
          if (!empty($selectedValue)){
            if($upgrade){
              global $wpdb;
              $product_opt = $wpdb->get_row( 'SELECT * 
              FROM ib_'.get_current_blog_id().'_pofw_product_option_value 
              WHERE product_id = '.$bought_upgrades['product_id'].'
              AND title="'.$option['values'][$vId]['title'].'"');

                $iscertified = $wpdb->get_row( 'SELECT * 
                FROM ib_'.get_current_blog_id().'_pofw_product_option
                WHERE option_id = '.$product_opt->option_id.'
                AND slug="certified"');
                if($iscertified){
                  $optionPrice +=  (((float) $product_opt->price * 1) / 1);
                }else{
                  $optionPrice +=  (((float) $product_opt->price * $bought_upgrades['billing_days']) / $bought_upgrades['all_purchased_days']);
                }
            }else{
              $optionPrice += (float) $option['price'];
            }
          }
        }
                           											                                                    
      }

      $citem["data"]->set_price($orgPrice + $optionPrice);

      $this->_poPriceAdded[$key] = 1;      
    }
      
    WC()->cart->set_session();  
  }
  


  public function formatSelectedValues($productId, $selectedValues){
    $formatedValues = array();

    foreach ($this->getProductOptions($productId) as $oId => $option){
      if (!isset($selectedValues[$oId])){
        continue;
      }

      $selectedValue = $selectedValues[$oId];

      $value = '';        
      if ($option['type'] == 'drop_down' || $option['type'] == 'radio'){
        if (is_array($selectedValue)){
          continue;
        }
        $vId = (int) $selectedValue;
        if (isset($option['values'][$vId])){
          $value = $option['values'][$vId]['title'];
        }
      } elseif ($option['type'] == 'multiple' || $option['type'] == 'checkbox'){
        foreach ((array) $selectedValue as $vId){
          if (isset($option['values'][$vId])){
            $value .= ($value != '' ? ', ' : '') . $option['values'][$vId]['title'];
          }            
        }          
      } elseif ($option['type'] == 'field' || $option['type'] == 'area'){
        if (is_array($selectedValue)){
          continue;
        }
        $value = $selectedValue;
      }
      
      if ($value != ''){
        $formatedValues[] = array("name" => $option['title'], "value" => $value);
      }                      											                                                    
    }
    
    return $formatedValues;    
  }  

		
	public function delete_post($id){
		if (!current_user_can('delete_posts') || !$id || get_post_type($id) != 'product'){
			return;
		}
    $this->getOptionModel()->deleteProductOptions($id);
	}		
		
}
