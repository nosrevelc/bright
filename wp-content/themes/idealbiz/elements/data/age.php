<?php

$dates = array(
    array(
        'value' => 'today',
        'date' => 'today',
    ),
    array(
        'value' => '14',
        'date' => sprintf('- %d days', intval('14')),
    ),
    array(
        'value' => '30',
        'date' => sprintf('- %d days', intval('30')),
    ),
    array(
        'value' => 'all',
        'date' => '',
    )
);

foreach ($dates as $date) :
    $count = IDB_Listings::count_listings($date['date']);

    ?>
    <div class="d-none age-data" data-value="<?php echo $date['value']; ?>" data-count="<?php echo $count; ?>"></div>
<?php endforeach;
