<select name="country_select" class="country-select" data-placeholder="<?php _e('Choose your country'); ?>">
    <option></option>
    <?php foreach ($sites_countries as $site_country) :
        foreach ($all_countries as $country) : if ($site_country['country'] == $country->iso) : ?>
                <option data-url="<?php echo $site_country['url']; ?>" value="<?php echo $site_country['site_id']; ?>"><?php echo $country->country; ?></option>
    <?php break; endif; 
        endforeach;
    endforeach; ?>
</select>