<?php

$max_price = IDB_Pricing::get_listings_max_price();
$min_price = IDB_Pricing::get_listings_min_price();

?>

<div class="d-none price-data" data-max="<?php echo $max_price; ?>" data-min="<?php echo $min_price; ?>"></div>