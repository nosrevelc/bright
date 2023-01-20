
<?php
if (!defined('ABSPATH')) exit;
global $bought_upgrades;
//$product = new WC_Product(10);  
/*


    global $bought_upgrades;

    if($bought_upgrades['prodcut_id']){
      $product = new WC_Product(10);  
    }
<?php  var_dump($bought_upgrades); ?>
    */


   
    
?>

<div class="pofw-product-options-wrapper pofw_product_options" id="pofw_product_options-<?php the_ID() ?>">
<?php do_action('add_listing_id'); ?>
  <div class="fieldset">
    <?php foreach($this->getOptions() as $id => $option): ?> 
      <div class="field <?php echo $option['required'] == 1 ? 'pofw-required' : ''; ?>">
        <label for="select_<?php echo $id; ?>">
          <span><?php echo htmlspecialchars($option['title']); ?></span>
          <?php if (($option['type'] == 'field' || $option['type'] == 'area') && $option['price'] != 0): ?>
           <span class="pofw-price"><?php echo $this->formatPrice($option['price']);?></span> 
          <?php endif; ?>
        </label>
        <div class="control">
          <?php if ($option['type'] == 'radio'): ?>
              <div class="options-list nested">
                <?php if ($option['required'] != 1): ?>
                <div class="choice">
                  <input type="radio" name="pofw_option[<?php echo $id; ?>]" id="pofw_option_[<?php echo $id; ?>]_none_value" class="pofw-option" value="">
                  <label for="pofw_option_[<?php echo $id; ?>]_none_value"><span><?php echo __('None', 'product-options-for-woocommerce') ?></span></label>
                </div>              
                <?php endif; ?>              
                <?php foreach($option['values'] as $vid => $value): ?>   
                  <?php if($value['price'] != ''): ?>          
                    <div class="choice">
                      <input type="radio" name="pofw_option[<?php echo $id; ?>]" id="pofw_option_value_<?php echo $vid; ?>" class="pofw-option" value="<?php echo $vid; ?>">
                      <label for="pofw_option_value_<?php echo $vid; ?>"><span><?php echo htmlspecialchars($value['title']); ?></span><?php echo $value['price'] != 0 ? '<span class="pofw-price"> '. $this->formatPrice($value['price']) .'</span>' : ''; ?></label>
                    </div>
                  <?php endif; ?>  
                <?php endforeach; ?>          
              </div>
            <?php elseif ($option['type'] == 'checkbox'): 
              

              ?>         
              <div class="options-list nested">
                <?php 
                
                $au = getAvailableUpgrades($_GET['id']);

                foreach($option['values'] as $vid => $value): 
                
                  if($_GET['ptype'] == 'upgrade'){
                   
                    $bought_upgrades = getBoughtUpgrades($_GET['id']);
                    
                    global $wpdb;
                    if(!$bought_upgrades['product_id']){
                        $posts = $wpdb->get_results("SELECT * FROM $wpdb->postmeta
                        WHERE meta_key = '_product_type_meta_key' AND  meta_value = 'upgrade_plan' LIMIT 1", ARRAY_A);  
                        $bought_upgrades['product_id']=$posts[0]["post_id"];
                    }

                    $all_purchased_days = get_post_meta( $bought_upgrades['product_id'], 'plan_duration', true );
                    $bought_upgrades['all_purchased_days'] = $all_purchased_days;

                    $date_expire='';
                    $format_in = 'Ymd';
                    $format_out = 'Y-m-d';

                    if(get_field('expire_date',$_GET['id'])){
                      $date_aux = DateTime::createFromFormat($format_in, get_field('expire_date',$_GET['id']));
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

                    
                    $now = time();
                    $your_date = strtotime($date_expire);
                    $datediff = $your_date - $now;
                    $billing_days =  round($datediff / (60 * 60 * 24));

                    $bought_upgrades['billing_days'] = $billing_days;
                
                         //echo $value['title'];
                        if($bought_upgrades['product_id']){
                          //$original_product = new WC_Product($bought_upgrades['product_id']);  
                          global $wpdb;
                          $product_opt = $wpdb->get_row( 'SELECT * 
                                                    FROM ib_'.get_current_blog_id().'_pofw_product_option_value 
                                                    WHERE product_id = '.$bought_upgrades['product_id'].'
                                                    AND title="'.$value['title'].'"');

                                              /*      echo 'SELECT * 
                                                    FROM ib_'.get_current_blog_id().'_pofw_product_option_value 
                                                    WHERE product_id = '.$bought_upgrades['product_id'].'
                                                    AND title="'.$value['title'].'"'; */

                          $iscertified = $wpdb->get_results( 'SELECT * 
                          FROM ib_'.get_current_blog_id().'_pofw_product_option
                          WHERE option_id = '.$product_opt->option_id.'
                          AND slug="certified"');
        
                         // echo $product_opt->price;

                          if(count($iscertified)){
                            //echo $product_opt->price;
                            $value['price'] =  (($product_opt->price * 1) / 1);
                          }else{
                            //echo $bought_upgrades['billing_days'].'--'.$bought_upgrades['all_purchased_days'];
                            $value['price'] =  (($product_opt->price * $bought_upgrades['billing_days']) / $bought_upgrades['all_purchased_days']);
                          }
                  
                          // $au array of available upgrades
                          $key = array_search($value['title'], $au);  // if admin translate en to other lang
                          if(!$key){
                            $key = array_search($option['title'], $au); // if admin not translate option
                          }
                      
                        if($key){
                          if($value['price'] != '' ||  $value['price']==0): ?>           
                            <div class="choice">
                              <input type="checkbox" name="pofw_option[<?php echo $id; ?>][]" id="pofw_option_value_<?php echo $vid; ?>" class="pofw-option p-o-<?php echo $vid; ?>" data-check="<?php echo sanitize_html_class( 'variation-' . $value['title'] ); ?>" value="<?php echo $vid; ?>">
                              <label class="pofw_option_value_<?php echo $vid; ?>" for="pofw_option_value_<?php echo $vid; ?>"><span><?php echo htmlspecialchars($value['title']); ?></span><?php echo $value['price'] != 0 ? '<span class="pofw-price"> '. $this->formatPrice($value['price']) .'</span>' : '<span class="pofw-price"> '. get_woocommerce_currency_symbol() .' 0 </span>'; ?></label>
                            </div>
                          <?php endif; 
                        }
                        if($_GET['certify']==1 && count($iscertified)){
                          echo '<script>
                                jQuery(document).ready(($) => {
                                  jQuery("[data-ptype=\'upgrade_plan\']").addClass("hover");
                                  jQuery("[data-ptype=\'upgrade_plan\']").addClass("active");
                                  jQuery(".single_add_to_cart_button").click();
                                });
                          </script>';
                          echo '<style>
                                  .shop-page .content-area .plist li.active .pbody .choice label:after{ opacity: 1 !important; }
                                </style>';
                        }
                        
                      }
                      
                      ?>
                      
                <?php }else{ /* Not upgrade */ ?>   

                  <?php if($value['price'] != '' ||  $value['price']==0): ?>           
                    <div class="choice">
                      <input type="checkbox" 
                      name="pofw_option[<?php echo $id; ?>][]" 
                      id="pofw_option_value_<?php echo $vid; ?>" 
                      class="pofw-option p-o-<?php echo $vid; ?>" 
                      data-check="<?php echo sanitize_html_class( 'variation-' . $value['title'] ); ?>" 
                      value="<?php echo $vid; ?>">
                      <label for="pofw_option_value_<?php echo $vid; ?>"><span><?php echo htmlspecialchars($value['title']); ?></span><?php echo $value['price'] != 0 ? '<span class="pofw-price"> '. $this->formatPrice($value['price']) .'</span>' : '<span class="pofw-price"> '. get_woocommerce_currency_symbol() .' 0 </span>'; ?></label>
                    </div>
                  <?php endif; ?>  

                <?php } ?>
                <?php endforeach; ?>          
              </div>
              

            <?php elseif ($option['type'] == 'drop_down'): ?>         
              <select name="pofw_option[<?php echo $id; ?>]" id="pofw_option_<?php echo $id; ?>" class="pofw-option">
                <option value=""><?php echo esc_html__('-- please select --', 'product-options-for-woocommerce') ?></option>
                <?php foreach($option['values'] as $vid => $value): ?>   
                  <?php if($value['price'] != ''): ?>     
                    <option value="<?php echo $vid; ?>"><?php echo htmlspecialchars($value['title']) .' '. $this->formatPrice($value['price']); ?></option>                   
                  <?php endif; ?>   
                <?php endforeach; ?>          
              </select>    
            <?php elseif ($option['type'] == 'multiple'): ?>         
              <select name="pofw_option[<?php echo $id; ?>][]" id="pofw_option_<?php echo $id; ?>" class="pofw-option" multiple="multiple">
                <option value=""><?php echo esc_html__('-- please select --', 'product-options-for-woocommerce') ?></option>
                <?php foreach($option['values'] as $vid => $value): ?> 
                  <?php if($value['price'] != ''): ?>     
                    <option value="<?php echo $vid; ?>"><?php echo htmlspecialchars($value['title']) .' '. $this->formatPrice($value['price']); ?></option>                   
                  <?php endif; ?>  
                <?php endforeach; ?>          
              </select>   
            <?php elseif ($option['type'] == 'field'): ?>         
              <input type="text" name="pofw_option[<?php echo $id; ?>]" id="pofw_option_<?php echo $id; ?>" class="pofw-option" value="" autocomplete="off">  
            <?php elseif ($option['type'] == 'area'): ?>         
              <textarea name="pofw_option[<?php echo $id; ?>]" id="pofw_option_<?php echo $id; ?>" class="pofw-option" rows="4"></textarea>                                                           
            <?php endif; ?>                             
        </div>
      </div> 
    <?php endforeach; ?>                
  </div>
</div> 
<script type="text/javascript">

  var config = {  
    requiredText : "<?php echo __('This field is required.', 'product-options-for-woocommerce'); ?>",
    productId : <?php echo (int) $this->getProductId(); ?>,    
    productPrice : <?php echo (float) $this->getProductPrice(); ?>,
    numberOfDecimals : <?php echo (int) $this->getNumberOfDecimals(); ?>,    
    decimalSeparator : "<?php echo $this->getDecimalSeparator(); ?>",
    thousandSeparator : "<?php echo $this->getThousandSeparator(); ?>",
    currencyPosition : "<?php echo $this->getCurrencyPosition(); ?>",
    isOnSale : <?php echo (int) $this->getIsOnSale(); ?>       
  };
  
  var optionData = <?php echo $this->getOptionDataJson(); ?>;
   
  jQuery.extend(config, optionData);
    
  jQuery('#pofw_product_options-<?php the_ID() ?>').pofwProductOptions(config);    

</script>