<?php

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

add_action('cpt_field_text', function ($config = []) { ?>
    <input type="<?php echo $config['type']; ?>" name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']); ?>" id="<?php echo cpt_get_field_input_id($config['key'], $config['parent']); ?>" value="<?php echo $config['value']; ?>" placeholder="<?php echo isset($config['extra']['placeholder']) ? $config['extra']['placeholder'] : ''; ?>" autocomplete="nope" <?php echo $config['required'] ? ' required' : ''; ?>>
<?php });

add_filter('cpt_sanitize_field_text', function ($value) {
    return sanitize_text_field($value);
});

add_filter('cpt_sanitize_field_email', function ($value) {
    return sanitize_text_field($value);
});

add_filter('cpt_sanitize_field_tel', function ($value) {
    return sanitize_text_field($value);
});

add_action('cpt_field_textarea', function ($config = []) { ?>
    <textarea name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']); ?>" id="<?php echo cpt_get_field_input_id($config['key'], $config['parent']); ?>" placeholder="<?php echo isset($config['extra']['placeholder']) ? $config['extra']['placeholder'] : ''; ?>" autocomplete="nope" <?php echo $config['required'] ? ' required' : ''; ?>><?php echo $config['value']; ?></textarea>
<?php });

add_filter('cpt_sanitize_field_textarea', function ($value) {
    return sanitize_textarea_field($value);
});

add_action('cpt_field_select', function ($config = []) {
    $placeholder = isset($config['extra']['placeholder']) ? $config['extra']['placeholder'] : false;
    $is_multiple = isset($config['extra']['multiple']) && $config['extra']['multiple'] == 'true' ? true : false;
    $options = isset($config['extra']['options']) && !empty($config['extra']['options']) ? $config['extra']['options'] : [];
    if (!is_array($options)) $options = cpt_get_options_from_textarea($options);
?>
    <select name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']) . ($is_multiple ? '[]' : ''); ?>" id="<?php echo cpt_get_field_input_id($config['key'], $config['parent']); ?>" <?php echo $is_multiple ? ' multiple data-multiple="yes"' : ''; ?><?php echo $placeholder ? ' data-placeholder="' . $placeholder . '"' : ''; ?> style="width: 100%;" autocomplete="nope" <?php echo $config['required'] ? ' required' : ''; ?>>
        <option value=""></option>
        <?php
        foreach ($options as $key => $option) {
            printf(
                '<option value="%s"%s>%s</option>',
                $key,
                ((is_array($config['value']) && in_array($key, $config['value'])) || $key == $config['value'] ? ' selected="selected"' : ''),
                $option
            );
        }
        ?>
    </select>
<?php });

add_action('cpt_field_checkbox', function ($config = []) {
    $options = isset($config['extra']['options']) && !empty($config['extra']['options']) ? $config['extra']['options'] : [];
    if (!is_array($options)) $options = cpt_get_options_from_textarea($options);
    foreach ($options as $key => $option) {
        printf(
            '<label><input type="checkbox" name="%s" value="%s"%s> %s</label><br>',
            cpt_get_field_input_name($config['key'], $config['parent']) . '[]',
            $key,
            ((is_array($config['value']) && in_array($key, $config['value'])) || $key == $config['value'] ? ' checked="checked"' : '') . ($config['required'] ? ' required' : ''),
            $option
        );
    }
});

add_action('cpt_field_tax_rel', function ($config = []) {
    $is_multiple = isset($config['extra']['multiple']) && $config['extra']['multiple'] == 'true' ? true : false;
    $taxonomy = isset($config['extra']['taxonomy']) && !empty($config['extra']['taxonomy']) ? $config['extra']['taxonomy'] : 'category';
?>
    <select name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']) . ($is_multiple ? '[]' : ''); ?>" id="<?php echo cpt_get_field_input_id($config['key'], $config['parent']); ?>" <?php echo $is_multiple ? ' multiple data-multiple="yes"' : ''; ?><?php echo $taxonomy ? ' data-taxonomy="' . $taxonomy . '"' : ''; ?> style="width: 100%;" autocomplete="nope" <?php echo $config['required'] ? ' required' : ''; ?>>
        <option value=""></option>
        <?php
        if (isset($config['value'])) {
            $term_ids = is_array($config['value']) ? $config['value'] : (empty($config['value']) ? [] : [$config['value']]);
            foreach ($term_ids as $term_id) {
                $term = get_term($term_id);
                if (!isset($term->name)) continue;
                printf(
                    '<option value="%s" selected="selected">%s</option>',
                    $term_id,
                    cpt_get_term_title_with_parents($term_id)
                );
            }
        }
        ?>
    </select>
<?php });

add_action('cpt_field_post_rel', function ($config = []) {
    $is_multiple = isset($config['extra']['multiple']) && $config['extra']['multiple'] == 'true' ? true : false;
    $post_type = isset($config['extra']['post_type']) && !empty($config['extra']['post_type']) ? $config['extra']['post_type'] : 'post';
?>
    <select name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']) . ($is_multiple ? '[]' : ''); ?>" id="<?php echo cpt_get_field_input_id($config['key'], $config['parent']); ?>" <?php echo $is_multiple ? ' multiple data-multiple="yes"' : ''; ?><?php echo $post_type ? ' data-post-type="' . $post_type . '"' : ''; ?> style="width: 100%;" autocomplete="nope" <?php echo $config['required'] ? ' required' : ''; ?>>
        <option value=""></option>
        <?php
        if (isset($config['value'])) {
            $post_ids = is_array($config['value']) ? $config['value'] : (empty($config['value']) ? [] : [$config['value']]);
            foreach ($post_ids as $post_id) {
                $post = get_post($post_id);
                if (!isset($post->post_title)) continue;
                printf(
                    '<option value="%s" selected="selected">%s</option>',
                    $post_id,
                    cpt_get_post_title_with_parents($post_id)
                );
            }
        }
        ?>
    </select>
<?php });

add_action('cpt_field_radio', function ($config = []) {
    $options = isset($config['extra']['options']) && !empty($config['extra']['options']) ? $config['extra']['options'] : [];
    if (!is_array($options)) $options = cpt_get_options_from_textarea($options);
    foreach ($options as $key => $option) {
        printf(
            '<label><input type="radio" name="%s" value="%s"%s> %s</label><br>',
            cpt_get_field_input_name($config['key'], $config['parent']),
            $key,
            ((is_array($config['value']) && in_array($key, $config['value'])) || $key == $config['value'] ? ' checked="checked"' : '') . ($config['required'] ? ' required' : ''),
            $option
        );
    }
});

add_action('cpt_field_file', function ($config = []) { ?>
    <div class="file-field" data-type="<?php echo htmlspecialchars(json_encode(isset($config['extra']['types']) && !empty($config['extra']['types']) ? $config['extra']['types'] : ['image']), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="text" name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']); ?>" value="<?php echo $config['value']; ?>" <?php echo $config['required'] ? ' required' : ''; ?>>
        <div class="upload-wrap">
            <div class="upload-preview">
                <?php
                echo $config['value'] && wp_get_attachment_image($config['value'], 'thumbnail', false, []) ? wp_get_attachment_image($config['value'], 'thumbnail', false, []) : '<img width="150" height="150" style="display: none;" class="attachment-thumbnail size-thumbnail" alt="" loading="lazy">';
                ?>
            </div>
            <div class="upload-actions" title="<?php echo $config['value'] && get_post($config['value']) ? basename(get_attached_file($config['value'])) : __('Choose', 'custom-post-types'); ?>">
                <div class="file-name" dir="rtl"><?php echo $config['value'] && get_post($config['value']) ? basename(get_attached_file($config['value'])) : ''; ?></div>
                <div class="buttons">
                    <button class="button button-secondary upload" id="<?php echo 'field_' . $config['key']; ?>" title="<?php _e('Choose', 'custom-post-types'); ?>">
                        <span class="dashicons dashicons-upload"></span>
                    </button>
                    <button class="button button-secondary remove" <?php echo empty($config['value']) ? ' disabled="disabled"' : ''; ?> title="<?php _e('Remove', 'custom-post-types'); ?>">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php });

add_filter('cpt_sanitize_field_file', function ($value) {
    return get_post($value) ? $value : '';
});

add_action('cpt_field_tinymce', function ($config = []) { ?>
    <div class="wysiwyg-field">
        <textarea name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']); ?>" id="<?php echo cpt_get_field_input_id($config['key'], $config['parent']); ?>" placeholder="<?php echo isset($config['extra']['placeholder']) ? $config['extra']['placeholder'] : ''; ?>" autocomplete="nope" autoresize="<?php echo isset($config['extra']['autoresize']) && $config['extra']['autoresize'] == 'true' ? 'yes' : 'no'; ?>"<?php echo $config['required'] ? ' required' : ''; ?>><?php echo $config['value']; ?></textarea>
    </div>
<?php
});

add_action('cpt_field_color', function ($config = []) { ?>
    <div class="color-field" data-alpha="<?php echo isset($config['extra']['alpha']) && $config['extra']['alpha'] ? 'yes' : 'no'; ?>">
        <div class="color-wrap">
            <div class="preview"></div>
            <input type="text" name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']); ?>" id="<?php echo cpt_get_field_input_id($config['key'], $config['parent']); ?>" value="<?php echo $config['value']; ?>" autocomplete="nope" <?php echo $config['required'] ? ' required' : ''; ?>>
        </div>
    </div>
<?php });

add_action('cpt_field_date', function ($config = []) {
    $value = !empty($config['value']) && DateTime::createFromFormat('Y-m-d', $config['value']) ? DateTime::createFromFormat('Y-m-d', $config['value'])->format('d/m/Y') : $config['value'];
?>
    <div class="date-field" data-min="<?php echo isset($config['extra']['min']) && $config['extra']['min'] ? $config['extra']['min'] : 'no'; ?>" data-max="<?php echo isset($config['extra']['max']) && $config['extra']['max'] ? $config['extra']['max'] : 'no'; ?>">
        <input type="text" name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']); ?>" id="<?php echo cpt_get_field_input_id($config['key'], $config['parent']); ?>" value="<?php echo $value; ?>" autocomplete="nope" <?php echo $config['required'] ? ' required' : ''; ?>>
    </div>
<?php });

add_action('cpt_field_time', function ($config = []) {
    $value = !empty($config['value']) && DateTime::createFromFormat('H:i', $config['value']) ? DateTime::createFromFormat('H:i', $config['value'])->format('H:i') : $config['value'];
?>
    <div class="field-input time-field" data-min="<?php echo isset($config['extra']['min']) && $config['extra']['min'] ? $config['extra']['min'] : 'no'; ?>" data-max="<?php echo isset($config['extra']['max']) && $config['extra']['max'] ? $config['extra']['max'] : 'no'; ?>">
        <select name="<?php echo cpt_get_field_input_name($config['key'], $config['parent']); ?>" id="<?php echo cpt_get_field_input_id($config['key'], $config['parent']); ?>" style="width: 100%;" autocomplete="nope" <?php echo $config['required'] ? ' required' : ''; ?>>
            <option value=""></option>
            <?php
            if (!empty($value))
                printf(
                    '<option value="%s" selected="selected">%s</option>',
                    $value,
                    $value
                );
            ?>
        </select>
    </div>
<?php });

add_filter('cpt_sanitize_field_date', function ($value) {
    $date = DateTime::createFromFormat('d/m/Y', $value);
    return $date ? $date->format('Y-m-d') : '';
});

add_action('cpt_field_repeater', function ($config = []) {
    $values = is_array($config['value']) && !empty($config['value']) ? $config['value'] : [];
    $fields = isset($config['extra']['fields']) && !empty($config['extra']['fields']) ? $config['extra']['fields'] : [];
    $parent_base = ($config['parent'] ? $config['parent'] : '') . '[' . $config['key'] . ']';
    $fields = cpt_sanitize_repeater_field($fields);
?>
    <div class="group-field" data-fields="<?php echo htmlspecialchars(json_encode($fields), ENT_QUOTES, 'UTF-8'); ?>" data-name="<?php echo ($config['parent'] ? $config['parent'] : '') . '[' . $config['key'] . ']'; ?>">
        <div class="group-field-wrap">
            <?php
            foreach ($values as $index => $value) {
                $parent = $parent_base . '[' . $index . ']';
            ?>
                <div class="cpt-fields-wrap inner">
                    <div class="buttons">
                        <div class="order"></div>
                        <span class="button button-secondary move" title="<?php _e('Reorder', 'custom-post-types'); ?>">
                            <span class="dashicons dashicons-move"></span>
                        </span>
                        <button class="button button-secondary remove" title="<?php _e('Remove', 'custom-post-types'); ?>">
                            <span class="dashicons dashicons-remove"></span>
                        </button>
                    </div>
                    <div class="group-fields-wrap">
                        <?php
                        foreach ($fields as $field) {
                            $field['post_id'] = $config['post_id'];
                            $field['value'] = isset($value[$field['key']]) ? $value[$field['key']] : '';
                            $field['parent'] = $parent;
                            (new CPT\MetaBox())->the_field_wrap($field);
                        }
                        ?>
                    </div>
                    <div class="confirm-remove-wrap">
                        <button class="button button-primary confirm-remove" title="<?php _e('Remove', 'custom-post-types'); ?>">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="buttons">
            <button class="add" title="<?php _e('Add', 'custom-post-types'); ?>">
                <span class="dashicons dashicons-insert"></span>
            </button>
        </div>
    </div>
    <?php });

add_filter('cpt_sanitize_field_repeater', function ($value) {
    return array_values($value);
});

add_action('cpt_field_html', function ($config = []) {
    echo isset($config['extra']['content']) && !empty($config['extra']['content']) ? $config['extra']['content'] : '';
});

add_action('cpt_field_extra', function ($config = []) {
    $values = is_array($config['value']) && !empty($config['value']) ? $config['value'] : [];
    foreach ($values as $type => $sub_fields) {
        $available_fields = (new CPT\Fields)->available_fields;
        $fields = isset($available_fields[$type]['fields']) ? $available_fields[$type]['fields'] : [];
        if (!$fields) continue;
        foreach ($fields as $field) {
            $field['parent'] = (isset($config['parent']) ? $config['parent'] : '') . '[extra][' . $type . ']';
            $field['value'] = isset($sub_fields[$field['key']]) ? $sub_fields[$field['key']] : '';
            (new CPT\MetaBox())->the_field_wrap($field);
        }
    }
});

add_action('cpt_field_template', function ($config = []) {
    global $post;
    $post_type = get_post_meta($post->ID, 'supports', true);
    $post_type_fields = cpt_get_fields_by_post_type($post_type);
    $post_type_taxs = get_object_taxonomies($post_type);
    if (!empty($post_type_fields)) { ?>
        <div class="cpt-field" style="width: 100%;">
            <div class="cpt-field-wrap">
                <div class="field-input">
                    <?php _e('Shortcodes of available fields and taxonomies:', 'custom-post-types'); ?>
                </div>
            </div>
        </div>
    <?php }
    foreach ($post_type_fields as $key => $field) { ?>
        <div class="cpt-field" style="width: 100%;">
            <div class="cpt-field-wrap">
                <div class="field-input">
                    <input type="text" value="<?php echo htmlentities(sprintf('[cpt-field key="%s"]', $key)); ?>" title="<?php _e('Click to copy', 'custom-post-types'); ?>" class="copy" readonly>
                </div>
            </div>
        </div>
    <?php }
    foreach ($post_type_taxs as $tax) { ?>
        <div class="cpt-field" style="width: 100%;">
            <div class="cpt-field-wrap">
                <div class="field-input">
                    <input type="text" value="<?php echo htmlentities(sprintf('[cpt-terms key="%s"]', $tax)); ?>" title="<?php _e('Click to copy', 'custom-post-types'); ?>" class="copy" readonly>
                </div>
            </div>
        </div>
    <?php }
    if (!empty($post_type_fields)) { ?>
        <div class="cpt-field" style="width: 100%;">
            <div class="cpt-field-wrap">
                <div class="field-input">
                    <?php _e('Use these shortcodes to dynamically show values ​​based on the single post of the chosen post type.', 'custom-post-types'); ?>
                </div>
            </div>
        </div>
<?php }
});

add_filter('cpt_get_field_type_file', function ($output) {
    $file_type = get_post_mime_type($output);
    $file_types = explode('/', $file_type);
    $main_type = isset($file_types[0]) ? $file_types[0] : false;
    if ($main_type && $main_type == 'image') {
        return wp_get_attachment_image($output, 'full');
    }
    return wp_get_attachment_url($output);
});

add_filter('cpt_get_field_type_select', function ($output) {
    if (empty($output)) return;
    return is_array($output) ? implode(', ', $output) : $output;
});

add_filter('cpt_get_field_type_checkbox', function ($output) {
    if (empty($output)) return;
    return is_array($output) ? implode(', ', $output) : $output;
});

add_filter('cpt_get_field_type_date', function ($output) {
    if (empty($output)) return;
    $config_format = get_option('date_format');
    return !empty($config_format) ? date($config_format, strtotime($output)) : $output;
});

add_filter('cpt_get_field_type_time', function ($output) {
    if (empty($output)) return;
    $config_format = get_option('time_format');
    return !empty($config_format) ? date($config_format, strtotime($output)) : $output;
});

add_filter('cpt_get_field_type_post_rel', function ($output) {
    if (empty($output)) return;
    if (is_array($output)) {
        $posts = [];
        foreach ($output as $post_id) {
            if (!get_post((int) $post_id)) continue;
            $posts[] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', get_permalink((int) $post_id), get_the_title((int) $post_id));
        }
        return implode(', ', $posts);
    }
    if (!get_post((int) $output)) return;
    return sprintf('<a href="%1$s" title="%2$s">%2$s</a>', get_permalink((int) $output), get_the_title((int) $output));
});

add_filter('cpt_get_field_type_tax_rel', function ($output) {
    if (empty($output)) return;
    if (is_array($output)) {
        $terms = [];
        foreach ($output as $term_id) {
            if (!get_term((int) $term_id)) continue;
            $terms[] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', get_term_link((int) $term_id), get_term((int) $term_id)->name);
        }
        return implode(', ', $terms);
    }
    if (!get_term((int) $output)) return;
    return sprintf('<a href="%1$s" title="%2$s">%2$s</a>', get_term_link((int) $output), get_term((int) $output)->name);
});
