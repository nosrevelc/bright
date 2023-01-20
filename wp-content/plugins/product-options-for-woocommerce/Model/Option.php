<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Model_Option {  
  
  protected $_wpdb;
  protected $_mainTable;
    
  protected $_value;          
          
      
  public function __construct(){
    global $wpdb;
    
    $this->_wpdb = $wpdb;   
    $this->_mainTable = "{$this->_wpdb->prefix}pofw_product_option";

    include_once(Pektsekye_PO()->getPluginPath() . 'Model/Option/Value.php');		
    
    $this->_value = new Pektsekye_ProductOptions_Model_Option_Value();       
	
  }	


  public function getProductOptions($productId){
    $productId = (int) $productId;

    
    $options = array();
    
    $valuesByOptionId = array();  

    

    $valueRows = $this->_value->getProductValues($productId);      
    foreach($valueRows as $r){
      $valuesByOptionId[$r['option_id']][$r['value_id']] = $r;
    }    
          
    $select = "SELECT * FROM {$this->_mainTable} WHERE product_id = {$productId} ORDER BY sort_order, title"; 
    $rows = $this->_wpdb->get_results($select, ARRAY_A);    

  

    if(count($rows)==0){
        $o=0;
        /* 
        insert query:
          auto_increment
          product ID
          Name - Of Product Option
          Slug - must be unique
          price - optional
          field type - checkbox , radio ,etc
          required - 0, 1
          sort order - $o++ for auto
        */
        //***** Hightlight Product

        $this->_wpdb->query(
          "INSERT INTO `".$this->_mainTable."` 
          (`option_id`, `product_id`, `title`, `slug`, `price`, `type`, `required`, `sort_order`) 
          VALUES (NULL, 
          '".$productId."', 
          'Highlight', 
          'highlight', 
          '0.00', 
          'checkbox', 
          '0',
          '".$o++."');");

        $this->_wpdb->query(
          "INSERT INTO `".$this->_mainTable."` 
          (`option_id`, `product_id`, `title`, `slug`, `price`, `type`, `required`, `sort_order`) 
          VALUES (NULL, 
          '".$productId."', 
          'Certified Listing', 
          'certified', 
          '0.00', 
          'checkbox', 
          '0',
          '".$o++."');");      

      /****************************** Default published Fields */
      $def_pub_lang='es_US';
      $dfp = get_option( 'default_published_language' );
      if($dfp){
        $def_pub_lang=$dfp;
      }

      if (isset($GLOBALS["polylang"])) { 
        $arrayLanguages = $GLOBALS["polylang"]->model->get_languages_list(); 
        foreach($arrayLanguages as $lang){
          if($lang->locale==$def_pub_lang){
        
          $this->_wpdb->query( // video
            "INSERT INTO `".$this->_mainTable."` 
            (`option_id`, `product_id`, `title`, `slug`, `price`, `type`, `required`, `sort_order`) 
            VALUES (NULL, 
            '".$productId."', 
            'With Video In: ".strtoupper($lang->slug)."', 
            'video', 
            '0.00', 
            'checkbox', 
            '0',
            '".$o++."');");     
          
          $this->_wpdb->query( // social networks
            "INSERT INTO `".$this->_mainTable."` 
            (`option_id`, `product_id`, `title`, `slug`, `price`, `type`, `required`, `sort_order`) 
            VALUES (NULL, 
            '".$productId."', 
            'Social Networks In: ".strtoupper($lang->slug)."', 
            'social_networks', 
            '0.00', 
            'checkbox', 
            '0',
            '".$o++."');");
          }
        }
        /****************************** Default published Fields */

        /****************************** Other Langs Fields */
        foreach($arrayLanguages as $lang){
          if($lang->locale!=$def_pub_lang){
            $this->_wpdb->query( // published in
              "INSERT INTO `".$this->_mainTable."` 
              (`option_id`, `product_id`, `title`, `slug`, `price`, `type`, `required`, `sort_order`) 
              VALUES (NULL, 
              '".$productId."', 
              'Published In: ".strtoupper($lang->slug)."', 
              'published_".$lang->slug."', 
              '0.00', 
              'checkbox', 
              '0',
              '".$o++."');");  
          $this->_wpdb->query(
            "INSERT INTO `".$this->_mainTable."` 
            (`option_id`, `product_id`, `title`, `slug`, `price`, `type`, `required`, `sort_order`) 
            VALUES (NULL, 
            '".$productId."', 
            'Highlight In: ".strtoupper($lang->slug)."', 
            'highlight_".$lang->slug."', 
            '0.00', 
            'checkbox', 
            '0',
            '".$o++."');");     
          $this->_wpdb->query(
            "INSERT INTO `".$this->_mainTable."` 
            (`option_id`, `product_id`, `title`, `slug`, `price`, `type`, `required`, `sort_order`) 
            VALUES (NULL, 
            '".$productId."', 
            'With Video In: ".strtoupper($lang->slug)."', 
            'video_".$lang->slug."', 
            '0.00', 
            'checkbox', 
            '0',
            '".$o++."');");     
          $this->_wpdb->query(
            "INSERT INTO `".$this->_mainTable."` 
            (`option_id`, `product_id`, `title`, `slug`, `price`, `type`, `required`, `sort_order`) 
            VALUES (NULL, 
            '".$productId."', 
            'Social Networks In: ".strtoupper($lang->slug)."', 
            'social_networks_".$lang->slug."', 
            '0.00', 
            'checkbox', 
            '0',
            '".$o++."');");     
          }
        }
        /****************************** Other Langs Fields */
      }
    }

    
    $select = "SELECT * FROM {$this->_mainTable} WHERE product_id = {$productId} ORDER BY sort_order, title"; 
    $rows = $this->_wpdb->get_results($select, ARRAY_A);    
    
    foreach ($rows as $r){
      $r['values'] = isset($valuesByOptionId[$r['option_id']]) ? $valuesByOptionId[$r['option_id']] : array();
      $options[$r['option_id']] = $r;
    }
    

    return $options;
  }
  
  
  public function saveOptions($productId, $options){ 
    $productId = (int) $productId;

    
    
    foreach($options as $option){
      $optionId = (int) $option['option_id'];
      
      if (isset($option['is_delete']) && $option['is_delete'] == 1){
        $this->deleteOption($optionId);
        continue;
      }
      
      $title = esc_sql($option['title']);
      $slug = esc_sql($option['slug']);
      $type = esc_sql($option['type']);
      $required = isset($option['required']) && $option['required'] == 1 ? 1 : 0;
      $sortOrder = (int) $option['sort_order'];
      $price = isset($option['price']) ? (float) $option['price'] : 0;       

      if ($optionId > 0){
        $this->_wpdb->query("UPDATE {$this->_mainTable} SET title = '{$title}', slug = '{$slug}', type = '{$type}', required = {$required}, sort_order = {$sortOrder}, price = {$price}  WHERE option_id = {$optionId}");                    
      } else {
        $this->_wpdb->query("INSERT INTO {$this->_mainTable} SET product_id = {$productId}, title = '{$title}', slug = '{$slug}', type = '{$type}', required = {$required}, sort_order = {$sortOrder}, price = {$price}");      
        $optionId = $this->_wpdb->insert_id;
      }      

      
      if (isset($option['values'])){
        $this->_value->saveValues($productId, $optionId, $option['values']);
      }     
    }       
  }
  
  
  public function deleteOption($optionId){    
    $optionId = (int) $optionId;
    
    $this->_value->deleteValues($optionId);
          
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE option_id = {$optionId}");                                   
  }
  
  
  public function deleteProductOptions($productId){    
    $productId = (int) $productId;
    
    $this->_value->deleteProductValues($productId);
          
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id = {$productId}");                                   
  }

  				
  public function getOptionGroupByType($type){    
    $group = '';
    
    switch($type){
      case 'drop_down':
      case 'radio':
      case 'checkbox':            
      case 'multiple':
        $group = 'select';
        break;
      case 'field':
      case 'area':
        $group = 'text';
        break;               
    }    
    
    return $group;                               
  }		
	
		
}
