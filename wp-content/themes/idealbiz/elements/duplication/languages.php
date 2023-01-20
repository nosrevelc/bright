<tr class="form-field form-required">
    <th scope="row"><label for="site-test"><?php _e('Site languages', 'idealbiz'); ?> <span class="required">*</span></label></th>
    <td>
        <select style="width: 35%;" name="site_languages[]" id="site-languages" multiple>
            <?php
            foreach ($languages as $lg) {
                ?>
                <option <?php echo $lg['locale'] === 'en_US' ? 'selected locked="locked"' : ''; ?> value="<?php echo esc_attr($lg['locale']); ?>"><?php echo esc_attr($lg['name']); ?> - <?php echo esc_attr($lg['locale']); ?></option>
            <?php
            }
            ?>

        </select>
    </td>
</tr>