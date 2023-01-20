<?php

$listing_data = new IDB_Listing_Data;

foreach ($listing_data->get_listing_data() as $data) : ?>
    <div class="listing-data m-b-30">
        <h3 class="listing-data-title"><?php echo $data['name']; ?></h3>
        <?php foreach ($data['items'] as $item) : ?>
                <div class="listing-data-item offset-md-1 d-flex m-t-15">
                    <span class="listing-data-item-title col-md-6 p-0"><?php echo $item['title']; ?></span>
                    <?php if (isset($item['is_link']) && $item['is_link']) : ?>
                        <a href="<?php echo esc_url($item['value']); ?>" target="_blank" class="listing-data-item-value item-link" style=""><?php echo $item['value']; ?></a>
                    <?php else: ?>
                        <span class="listing-data-item-value" style=""><?php echo $item['value']; ?></span>
                    <?php endif; ?>
                </div>
        <?php endforeach; ?>

    </div>
<?php endforeach;
