<?php
/*
$property_types = array(
    array(
        'label' => __('Real Property', 'idealbiz'),
        'value' => 'real',
    ),
    array(
        'label' => __('Without Property', 'idealbiz'),
        'value' => 'noproperty',
    ),
    array(
        'label' => __('Lease', 'idealbiz'),
        'value' => 'lease',
    )
);

foreach ($property_types as $type) :
    $property_checked = in_array($type['value'], explode(',', get_query_var('property_type')));
    ?>
    <label class="custom-control custom-checkbox idealbiz-checkbox property-type m-b-15">
        <input <?php if($property_checked) { echo 'checked'; } ?> type="checkbox" name="property_type" class="custom-control-input" value="<?php echo $type['value']; ?>">
        <span class="custom-control-label" for="property_type"><?php echo $type['label']; ?></span>
    </label>
<?php endforeach;
*/