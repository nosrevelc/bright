<tr class="form-field form-required">
    <th scope="row"><label for="site-test"><?php _e('Country Market', 'idealbiz'); ?> <span class="required">*</span></label></th>
    <td>
        <select name="country_market" id="country_market">
            <?php
            foreach ($countries as $country) { ?>
                <option value="<?php echo $country->iso; ?>"><?php echo $country->country; ?></option>
            <?php } ?>
        </select>
    </td>
</tr>