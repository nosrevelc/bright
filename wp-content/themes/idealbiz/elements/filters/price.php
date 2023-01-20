<?php
$max_price = IDB_Pricing::get_listings_max_price();
$min_price = IDB_Pricing::get_listings_min_price();
?>
<input type="text" class="js-range-slider" data-min="<?php echo $min_price; ?>" data-max="<?php echo $max_price; ?>" data-from="<?php echo get_query_var('price_min'); ?>" data-to="<?php echo get_query_var('price_max'); ?>" name="price_range" value="" />