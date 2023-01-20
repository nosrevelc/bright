<?php

$listing_financial = IDB_Listing_Data::get_listing_financial();

foreach ($listing_financial as $detail) : ?>
    <div class="financial-detail m-b-5 d-flex flex-row justify-content-between">
        <span class="title"><?php echo $detail['title']; ?></span>
        <span class="detail"><?php echo IDB_Listing_Data::get_listing_value($post_id, $detail['meta_key']); ?></span>
    </div>
<?php endforeach;
