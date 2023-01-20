<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Model_Option_Value {  
  
  protected $_wpdb;
  protected $_mainTable;         
          
      
  public function __construct(){
    global $wpdb;
    
    $this->_wpdb = $wpdb;   
    $this->_mainTable = "{$this->_wpdb->prefix}pofw_product_option_value";     
  }	


  public function getProductValues($productId){ 

    global $bought_upgrades;

    $productId = (int) $productId;
    $secTable = "{$this->_wpdb->prefix}pofw_product_option";    

    $select = "SELECT v.value_id, v.option_id, v.product_id, v.title, v.price, v.sort_order, o.slug
    FROM {$this->_mainTable} as v LEFT JOIN ".$secTable." as o ON v.option_id = o.option_id 
    WHERE v.product_id = {$productId} ORDER BY v.sort_order, v.title"; 
    //echo $select;

    $options_price = array();
    $options_price = $this->_wpdb->get_results($select, ARRAY_A);

    /********* this make broker EN option at reduced price *********
    if(is_admin()){

    }else{
      foreach(getLangSlug('all') as $actL){
        $b= isBroker(NULL,$actL);
          if($b){
            for($x=0; $x<count($options_price); $x++){
              if($options_price[$x]['slug'] == 'published_'.$b['publish_in_language']){
               // echo 'published_'.$b['publish_in_language'];
              //  echo countListsOfBroker(get_current_user_id(),$b['publish_in_language']). '- '. $b['list_slots'];
                if(countListsOfBroker(get_current_user_id(),$b['publish_in_language']) < $b['list_slots']){
                  $options_price[$x]['price'] = $b['listing_price_override'];
                  //echo  $b['listing_price_override'];
                }
              }
            }
          }
      }
    }
    */


    $upgrade=0;
      if(get_post_meta($productId, '_product_type_meta_key', true) == 'upgrade_plan'){
        $upgrade=1;

        $related_post_id=$_POST['related_post_id'];
        if(!$_POST['related_post_id']){
          $related_post_id= explode('&id=',$_SERVER['HTTP_REFERER'])[1];
          if(!$related_post_id){
            $related_post_id = str_replace("related_post_id=", "", explode("&", $_POST['post_data'])[0] ); 
          }
          if(!$related_post_id){
            $related_post_id = get_field('active_order',$_GET['id']);
          }
        }
        //var_dump($related_post_id);
        //die();
      
          $order_of_product = get_field('active_order',$related_post_id);
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
          if(get_field('expire_date',$related_post_id)){
              $date_aux = DateTime::createFromFormat($format_in, get_field('expire_date',$related_post_id));
              $date_expire= $date_aux->format( $format_out ) . ' 00:00:00';
          }else if($o->order_date){
              $date_expire = $o->order_date;
          }else{
              $date_expire = '2020-12-31 00:00:00';
          }
      
          if($all_purchased_days <= 0){
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

          if(is_admin()){
            $bought_upgrades['billing_days']=1;
            $bought_upgrades['all_purchased_days']=1;
          }
    
          for($x=0; $x<count($options_price); $x++){
              if($options_price[$x]['slug'] == 'certified'){
                $options_price[$x]['price'] = (((float) $options_price[$x]['price'] * 1) / 1);
              }else{
                $options_price[$x]['price'] = (((float) $options_price[$x]['price'] * $bought_upgrades['billing_days']) / $bought_upgrades['all_purchased_days']);
              }
          }
      }else{
        $options_price[$x]['price'] = (float) $options_price[$x]['price'];
      }

      
      //var_dump($bought_upgrades['billing_days']);
      //var_dump($bought_upgrades['all_purchased_days']);
      //var_dump($options_price);
      //die();
      //(((float) $option['values'][$vId]['price'] * $bought_upgrades['billing_days']) / $bought_upgrades['all_purchased_days']);

    return $options_price;    
  }
  

  public function saveValues($productId, $optionId, $values){ 
    $productId = (int) $productId;
    $optionId = (int) $optionId;
    foreach($values as $value){
      $valueId = (int) $value['value_id'];
      
      if (isset($value['is_delete']) && $value['is_delete'] == 1){
        $this->deleteValue($valueId);
        continue;
      }
              
      $title = esc_sql($value['title']);
      $price = (float) $value['price'];         
      $sortOrder = (int) $value['sort_order'];    
      

      if ($valueId > 0){
        $this->_wpdb->query("UPDATE {$this->_mainTable} SET title = '{$title}', price = {$price}, sort_order = {$sortOrder}  WHERE value_id = {$valueId}");                    
      } else {
        $this->_wpdb->query("INSERT INTO {$this->_mainTable} SET product_id = {$productId}, option_id = {$optionId}, title = '{$title}', price = {$price}, sort_order = {$sortOrder}");      
      }       
    }
  }


  public function deleteValue($valueId){    
    $valueId = (int) $valueId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE value_id = {$valueId}");                              
  }


  public function deleteValues($optionId){    
    $optionId = (int) $optionId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE option_id = {$optionId}");                              
  }
  
  
  public function deleteProductValues($productId){    
    $productId = (int) $productId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id = {$productId}");                              
  }  	
  
  
}
