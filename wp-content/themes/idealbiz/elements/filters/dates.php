<?php

$dates = array(
    array(
        'label' => __('Today', 'idealbiz'),
        'value' => 'today',
        'date' => 'today',
    ),
    array(
        'label' => __('Last 14 Days', 'idealbiz'),
        'value' => '14',
        'date' => sprintf('- %d days', intval('14')),
    ),
    array(
        'label' => __('Last 30 Days', 'idealbiz'),
        'value' => '30',
        'date' => sprintf('- %d days', intval('30')),
    ),
    array(
        'label' => __('Any time', 'idealbiz'),
        'value' => 'all',
        'date' => '',
    )
);

foreach ($dates as $date) :
    $count = IDB_Listings::count_listings($date['date']);
    $checked = $date['value'] == get_query_var( 'age', 'all' );
    ?>
    <label class="custom-control custom-radio idealbiz-radio age-<?php echo $date['value']; ?> m-b-15">
        <input <?php if($count == 0) { echo 'disabled'; } ?> <?php if($checked) { echo 'checked'; } ?> name="age" type="radio" class="custom-control-input" value="<?php echo $date['value']; ?>">
        <span class="custom-control-label" for="age"><?php echo $date['label']; ?><!-- (<span class="count"><?php echo $count; ?></span>)--></span>
    </label>
<?php endforeach;
