<?php

namespace CPT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class MetaBox
{
    public function __construct()
    {
        //
    }

    public function ajax_listener()
    {
        add_action('wp_ajax_cpt_get_fields', function () {
            $nonce = isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'cpt-nonce') ? true : false;
            $fields = isset($_POST['fields']) && is_array(json_decode(stripslashes($_POST['fields']), true)) ? json_decode(stripslashes($_POST['fields']), true) : [];
            if (empty($fields) || !$nonce) wp_send_json_error();
            $fields_parent = isset($_POST['parent']) ? $_POST['parent'] : '';
            ob_start();
?>
            <div class="cpt-fields-wrap inner">
                <div class="buttons">
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
                        $field['parent'] = $fields_parent;
                        $this->the_field_wrap($field);
                    }
                    ?>
                </div>
                <div class="confirm-remove-wrap">
                    <button class="button button-primary confirm-remove" title="<?php _e('Remove', 'custom-post-types'); ?>">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </div>
            </div>
        <?php
            $output = ob_get_clean();
            wp_send_json_success($output);
        });
    }

    public function the_field_wrap($config = [])
    {
        $type = isset($config['type']) && !empty($config['type']) ? $config['type'] : 'text';
        $key = isset($config['key']) && !empty($config['key']) ? $config['key'] : false;
        $label = isset($config['label']) && !empty($config['label']) ? $config['label'] : false;
        $info = isset($config['info']) && !empty($config['info']) ? $config['info'] : false;
        $required = isset($config['required']) && !empty($config['required']) && $config['required'] == 'true' ? true : false;
        $extra = isset($config['extra']) && !empty($config['extra']) ? $config['extra'] : [];
        $wrap_width = isset($config['wrap']['width']) && !empty($config['wrap']['width']) ? (int) $config['wrap']['width'] : (isset($config['wrap_width']) && !empty($config['wrap_width']) ? $config['wrap_width'] : 100);
        $wrap_class = isset($config['wrap']['class']) && !empty($config['wrap']['class']) ? $config['wrap']['class'] : (isset($config['wrap_class']) && !empty($config['wrap_class']) ? $config['wrap_class'] : false);
        $wrap_id = isset($config['wrap']['id']) && !empty($config['wrap']['id']) ? $config['wrap']['id'] : (isset($config['wrap_id']) && !empty($config['wrap_id']) ? $config['wrap_id'] : false);
        $wrap_layout = isset($config['wrap']['layout']) && $config['wrap']['layout'] == 'horizontal' ? 'horizontal' : (isset($config['wrap_layout']) && !empty($config['wrap_layout']) ? $config['wrap_layout'] : 'vertical');

        $post_id = isset($config['post_id']) && !empty($config['post_id']) ? $config['post_id'] : false;
        $meta_value = isset($config['value']) && !empty($config['value']) ? $config['value'] : '';

        $parent = isset($config['parent']) && !empty($config['parent']) ? $config['parent'] : false;

        $config = [
            'parent' => $parent,
            'type' => $type,
            'key' => $key,
            'label' => $label,
            'info' => $info,
            'required' => $required,
            'extra' => $extra,
            'wrap' => [
                'width' => $wrap_width,
                'class' => $wrap_class,
                'id' => $wrap_id,
                'layout' => $wrap_layout
            ],
            'post_id' => $post_id,
            'value' => $meta_value,
        ];
        ?>
        <div class="cpt-field<?php $wrap_class ? printf(' %s', $wrap_class) : ''; ?>" <?php $wrap_id ? printf(' id="%s"', $wrap_id) : ''; ?><?php $wrap_width ? printf(' style="width: %s;"', $wrap_width . '%') : ''; ?>>

            <?php
            if (in_array($type, ['extra', 'template'])) {
                do_action("cpt_field_$type", $config);
            } else {
            ?>
                <div class="cpt-field-wrap <?php echo $wrap_layout; ?>">
                    <?php
                    $label ? printf(
                        '<label class="field-label" for="%s">%s</label>',
                        cpt_get_field_input_id($key, $parent),
                        __($label, 'custom-post-types') . ($required ? sprintf('<span class="dashicons dashicons-warning required-icon" title="%s"></span>', __('This field is required', 'custom-post-types')) : '')
                    ) : '';
                    ?>
                    <div class="field-input">
                        <input type="hidden" name="<?php echo cpt_get_field_input_name($key, $parent); ?>" value="">
                        <?php
                        if (in_array($type, ['text', 'email', 'tel', 'number'])) $type = 'text';
                        do_action("cpt_field_$type", $config);
                        ?>
                    </div>
                    <?php
                    $info .= $required ? sprintf('<div class="required-label">- %s</div>', __('This field is required', 'custom-post-types')) : '';
                    $info ? printf('<div class="field-info">%s</div>', $info) : '';
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
<?php
    }

    public function add($post_type = null, $name = null, $fields = null, $position = 'normal')
    {
        if (!$post_type || !$name || !$fields) {
            return;
        }
        add_action('add_meta_boxes', function ($posttype) use (&$post_type, &$name, &$fields, &$position) {
            if ($posttype !== $post_type) {
                return;
            }
            $register_slug = sanitize_title($name);
            add_meta_box(
                $register_slug,
                __($name, 'custom-post-types'),
                function ($post) use (&$fields) {
                    wp_nonce_field('cpt_fields_nonce', 'fields_nonce');
                    echo '<div class="cpt-fields-wrap">
                    <div style="display: none;">
                        <input type="email" name="email">
                        <input type="password" name="password" autocomplete="new-password">
                    </div>';
                    foreach ($fields as $field) {
                        $meta_value = get_post_meta($post->ID, $field['key'], true);
                        $field['post_id'] = $post->ID;
                        $field['value'] = $meta_value;
                        $this->the_field_wrap($field);
                    }
                    echo '</div>';
                },
                $post_type,
                $position,
                'default'
            );
        });
        add_action('save_post_' . $post_type, function ($post_id) use (&$fields) {
            if (!isset($_POST['fields_nonce']) || !wp_verify_nonce($_POST['fields_nonce'], 'cpt_fields_nonce') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||  !current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
            $meta_values = isset($_POST['meta-fields']) ? $_POST['meta-fields'] : [];
            $fields = !empty($fields) ? $fields : [];
            foreach ($fields as $field) {
                $meta_key = $field['key'];
                $meta_type = $field['type'];
                if (isset($meta_values[$meta_key])) {
                    $sanitize_value = apply_filters('cpt_sanitize_field_' . $meta_type, $meta_values[$meta_key]);
                } else {
                    $sanitize_value = '';
                }
                update_post_meta($post_id, $meta_key, $sanitize_value);
            }
        });

        $this->ajax_listener();

        return $this;
    }

    public function update_old_post_meta($meta = [], $post_id)
    {
        if (!is_array($meta) || empty($meta) || !get_post($post_id)) return;
        if (isset($meta['field_position'])) update_post_meta($post_id, 'position', $meta['field_position']);
        if (isset($meta['field_post_types'])) update_post_meta($post_id, 'supports', $meta['field_post_types']);
        if (isset($meta['field_role'])) update_post_meta($post_id, 'admin_only', ($meta['field_role'] == "1" ? 'true' : 'false'));
        if (isset($meta['fields'])) {
            if (empty($meta['fields'])) return;
            $new_fields = [];
            foreach ($meta['fields'] as $old_key => $old_settings) {
                if (!isset($old_settings['type']) || empty($old_settings['type'])) continue;
                if ($old_settings['type'] == 'textarea') $old_settings['type'] = 'tinymce';
                if ($old_settings['type'] == 'code') $old_settings['type'] = 'textarea';
                if ($old_settings['type'] == 'dropdown') $old_settings['type'] = 'select';
                if ($old_settings['type'] == 'post_relationship') $old_settings['type'] = 'post_rel';
                if ($old_settings['type'] == 'taxonomy_relationship') $old_settings['type'] = 'tax_rel';
                if ($old_settings['type'] == 'image') $old_settings['type'] = 'text';
                if ($old_settings['type'] == 'file') $old_settings['type'] = 'text';

                $new_version_field = [
                    'label' => isset($old_settings['name']) ? $old_settings['name'] : '',
                    'key' => $old_key,
                    'type' => $old_settings['type'],
                    'required' => isset($old_settings['required']) && $old_settings['required'] == 1 ? 'true' : '',
                ];
                if (isset($old_settings['options']) && !empty($old_settings['options'])) $new_version_field['extra'][$old_settings['type']]['options'] = $old_settings['options'];
                if (isset($old_settings['types']) && !empty($old_settings['types'])) $new_version_field['types'] = $old_settings['types'];
                if ($new_version_field['type'] == 'post_rel') $new_version_field['extra']['post_rel']['post_type'] = $old_settings['options'];
                if ($new_version_field['type'] == 'tax_rel') $new_version_field['extra']['tax_rel']['taxonomy'] = $old_settings['options'];
                $new_fields[] = $new_version_field;
            }
            update_post_meta($post_id, 'fields', $new_fields);
        }
        return;
    }
}
