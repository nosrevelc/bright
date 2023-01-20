<?php

namespace CPT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class Taxonomy
{
    public function __construct()
    {
        $this->default_args = [
            'description'        => __('Taxonomy created with the "Custom post type" plugin.', 'custom-post-types'),
            'public'             => true,
            'hierarchical'       => false,
            // 'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            // 'show_in_nav_menus'       => true,
            // 'show_in_admin_bar'       => false,
            'show_in_rest'       => true,
            'show_admin_column' => true,
            'capabilities' => [
                'manage_terms'          => 'edit_posts',
                'edit_terms'          => 'edit_posts',
                'delete_terms'        => 'edit_posts',
                'assign_terms'         => 'edit_posts'
            ],
            'rewrite'            => [
                'with_front' => false,
                'hierarchical' => true
            ],
            // 'query_var'          =>  true,
        ];

        $this->default_labels = [
            'name'                     => _x('%s', 'taxonomy general name', 'custom-post-types'),
            'menu_name'                => __('%s', 'custom-post-types'),
            'singular_name'            => _x('%s', 'taxonomy singular name', 'custom-post-types'),
            'all_items'                => __('All %s', 'custom-post-types'),
            'edit_item'                => __('Edit %s', 'custom-post-types'),
            'view_item'                => __('View %s', 'custom-post-types'),
            'update_item'                => __('Update %s', 'custom-post-types'),
            'add_new_item'             => __('Add %s', 'custom-post-types'),
            'new_item_name'                 => __('%s name', 'custom-post-types'),
            'parent_item'        => __('Parent %s', 'custom-post-types'),
            'parent_item_colon'        => __('Parent %s', 'custom-post-types'),
            'search_items'             => __('Search %s', 'custom-post-types'),
            'popular_items'             => __('Popular %s', 'custom-post-types'),
            'separate_items_with_commas' => __('Separate %s with commas', 'custom-post-types'),
            'add_or_remove_items' => __('Add or remove %s', 'custom-post-types'),
            'choose_from_most_used' => __('Choose from the most used %s', 'custom-post-types'),
            'not_found'                => __('No %s found.', 'custom-post-types'),
            'back_to_items'                => __('← Back to %s', 'custom-post-types'),
        ];
    }
    private function get_default_labels($singular, $plural)
    {
        $labels = $this->default_labels;
        foreach ($labels as $key => $label) {
            $is_singular = !in_array($key, ['name', 'menu_name', 'popular_items', 'search_items', 'not_found', 'all_items', 'back_to_items', 'add_or_remove_items', 'separate_items_with_commas']);
            $labels[$key] = sprintf($label, ($is_singular ? $singular : $plural));
        }
        return $labels;
    }
    public function register($id = '', $singular = '', $plural = '', $post_types = [], $args = [], $labels = [])
    {
        if (empty($id) || empty($singular) || empty($plural)) return false;
        $register_labels = array_replace_recursive($this->get_default_labels($singular, $plural), $labels);
        $register_args = array_replace_recursive($this->default_args, $args);
        if ((isset($args['admin_only']) ? $args['admin_only'] : '') == 'true') {
            $register_args['capabilities'] = [
                'manage_terms'          => 'update_core',
                'edit_terms'          => 'update_core',
                'delete_terms'        => 'update_core',
                'assign_terms'         => 'update_core',
            ];
        }
        $register_args['labels'] = $register_labels;
        register_taxonomy($id, $post_types, $register_args);
    }
    public function update_old_post_meta($meta = [], $post_id)
    {
        if (!is_array($meta) || empty($meta) || !get_post($post_id)) return;

        $text_field_map = [
            'tax_id' => 'id',
            'tax_singular_name' => 'singular',
            'tax_search_items' => 'labels_search_items',
            'tax_all_items' => 'labels_all_items',
            'tax_parent_item' => 'labels_parent_item',
            'tax_parent_item_colon' => 'labels_parent_item_colon',
            'tax_edit_item' => 'labels_edit_item',
            'tax_update_item' => 'labels_update_item',
            'tax_add_new_item' => 'labels_add_new_item',
            'tax_new_item_name' => 'labels_new_item_name',
        ];

        foreach ($text_field_map as $old_key => $new_key) {
            if (isset($meta[$old_key])) update_post_meta($post_id, $new_key, $meta[$old_key]);
        }

        $select_fields_map = [
            'tax_hierarchical' => 'hierarchical',
            'tax_public' => 'public',
            'tax_role' => 'admin_only',
        ];

        foreach ($select_fields_map as $old_key => $new_key) {
            if (isset($meta[$old_key])) update_post_meta($post_id, $new_key, ($meta[$old_key] == "1" ? 'true' : 'false'));
        }

        update_post_meta($post_id, 'plural', get_the_title($post_id));

        if (isset($meta['tax_post_types'])) update_post_meta($post_id, 'supports', $meta['tax_post_types']);

        return;
    }
}
