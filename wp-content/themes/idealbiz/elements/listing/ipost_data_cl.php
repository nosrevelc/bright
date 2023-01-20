<?php

$ipost_data = new IDB_Listing_Data;

foreach ($ipost_data->get_ipost_data() as $data) : ?>
    <div class="ipost-data m-b-30">
        <h3 class="ipost-data-title"><?php echo $data['name']; ?></h3>
        <?php foreach ($data['items'] as $item) : ?>
                <div class="ipost-data-item offset-md-1 d-flex m-t-15">
                    <span class="ipost-data-item-title col-md-6 p-0"><?php echo $item['title']; ?></span>
                    <?php if (isset($item['is_link']) && $item['is_link']) : ?>
                        <a href="<?php echo esc_url($item['value']); ?>" target="_blank" class="ipost-data-item-value item-link" style=""><?php echo $item['value']; ?></a>
                    <?php else: ?>
                        <span class="ipost-data-item-value" style=""><?php echo $item['value']; ?></span>
                    <?php endif; ?>
                </div>
        <?php endforeach; ?>

    </div>
<?php endforeach;
