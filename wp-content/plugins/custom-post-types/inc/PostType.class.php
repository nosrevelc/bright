<?php

namespace CPT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class PostType
{
    public function __construct()
    {
        $this->default_args = [
            'description'        => __('Post type created with the "Custom post type" plugin.', 'custom-post-types'),
            'public'             => true,
            'hierarchical'       => false,
            'exclude_from_search'       => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'menu_icon'          => 'dashicons-tag',
            'capabilities' => [
                'edit_post'          => 'edit_posts',
                'read_post'          => 'edit_posts',
                'delete_post'        => 'edit_posts',
                'edit_posts'         => 'edit_posts',
                'edit_others_posts'  => 'edit_posts',
                'delete_posts'       => 'edit_posts',
                'publish_posts'      => 'edit_posts',
                'read_private_posts' => 'edit_posts'
            ],
            'supports' => [
                'title',
            ],
            'has_archive'        => false,
            'rewrite'            => [
                'with_front' => false
            ],
        ];

        $this->default_labels = [
            'name'                     => _x('%s', 'post type general name', 'custom-post-types'),
            'menu_name'                => __('%s', 'custom-post-types'),
            'singular_name'            => _x('%s', 'post type singular name', 'custom-post-types'),
            'add_new'                  => _x('Add New', 'post', 'custom-post-types'),
            'add_new_item'             => __('Add New %s', 'custom-post-types'),
            'edit_item'                => __('Edit %s', 'custom-post-types'),
            'new_item'                 => __('New %s', 'custom-post-types'),
            'view_item'                => __('View %s', 'custom-post-types'),
            'view_items'               => __('View %s', 'custom-post-types'),
            'search_items'             => __('Search %s', 'custom-post-types'),
            'not_found'                => __('No %s found.', 'custom-post-types'),
            'not_found_in_trash'       => __('No %s found in Trash.', 'custom-post-types'),
            'parent_item_colon'        => __('Parent %s', 'custom-post-types'),
            'all_items'                => __('All %s', 'custom-post-types'),
            'archives'                 => __('%s Archives', 'custom-post-types'),
            'attributes'               => __('%s Attributes', 'custom-post-types'),
            'insert_into_item'         => __('Insert into %s', 'custom-post-types'),
            'uploaded_to_this_item'    => __('Uploaded to this %s', 'custom-post-types'),
            'featured_image'           => __('Featured image', 'custom-post-types'),
            'set_featured_image'       => __('Set featured image', 'custom-post-types'),
            'remove_featured_image'    => __('Remove featured image', 'custom-post-types'),
            'use_featured_image'       => __('Use as featured image', 'custom-post-types'),
            'filter_items_list'        => __('Filter %s list', 'custom-post-types'),
            'items_list_navigation'    => __('%s list navigation', 'custom-post-types'),
            'items_list'               => __('%s list', 'custom-post-types'),
            'item_published'           => __('%s published.', 'custom-post-types'),
            'item_published_privately' => __('%s published privately.', 'custom-post-types'),
            'item_reverted_to_draft'   => __('%s reverted to draft.', 'custom-post-types'),
            'item_scheduled'           => __('%s scheduled.', 'custom-post-types'),
            'item_updated'             => __('%s updated.', 'custom-post-types'),
        ];
    }
    private function get_default_labels($singular, $plural)
    {
        $labels = $this->default_labels;
        foreach ($labels as $key => $label) {
            $is_singular = !in_array($key, ['name', 'menu_name', 'view_items', 'search_items', 'not_found', 'not_found_in_trash', 'all_items', 'filter_items_list', 'items_list_navigation', 'items_list']);
            $labels[$key] = sprintf($label, ($is_singular ? $singular : $plural));
        }
        return $labels;
    }
    public function register($id = '', $singular = '', $plural = '', $args = [], $labels = [])
    {
        if (empty($id) || empty($singular) || empty($plural)) return false;
        $register_labels = array_replace_recursive($this->get_default_labels($singular, $plural), $labels);
        $register_args = array_replace_recursive($this->default_args, $args);
        if ((isset($args['admin_only']) ? $args['admin_only'] : '') == 'true') {
            $register_args['capabilities'] = [
                'edit_post'          => 'update_core',
                'read_post'          => 'update_core',
                'delete_post'        => 'update_core',
                'edit_posts'         => 'update_core',
                'edit_others_posts'  => 'update_core',
                'delete_posts'       => 'update_core',
                'publish_posts'      => 'update_core',
                'read_private_posts' => 'update_core'
            ];
        }
        $register_args['labels'] = $register_labels;
        register_post_type($id, $register_args);
    }
    public function update_old_post_meta($meta = [], $post_id)
    {
        if (!is_array($meta) || empty($meta) || !get_post($post_id)) return;

        $text_field_map = [
            'cpt_id' => 'id',
            'cpt_singular_name' => 'singular',
            'cpt_add_new_item' => 'labels_add_new_item',
            'cpt_edit_item' => 'labels_edit_item',
            'cpt_new_item' => 'labels_new_item',
            'cpt_view_item' => 'labels_view_item',
            'cpt_view_items' => 'labels_view_items',
            'cpt_search_items' => 'labels_search_items',
            'cpt_not_found' => 'labels_not_found',
            'cpt_not_found_in_trash' => 'labels_not_found_in_trash',
            'cpt_parent_item_colon' => 'labels_parent_item_colon',
            'cpt_all_items' => 'labels_all_items',
            'cpt_archives' => 'labels_archives',
            'cpt_icon' => 'menu_icon'
        ];

        foreach ($text_field_map as $old_key => $new_key) {
            if (isset($meta[$old_key])) update_post_meta($post_id, $new_key, $meta[$old_key]);
        }

        $select_fields_map = [
            'cpt_hierarchical' => 'hierarchical',
            'cpt_public' => 'public',
            'cpt_role' => 'admin_only',
        ];

        foreach ($select_fields_map as $old_key => $new_key) {
            if (isset($meta[$old_key])) {
                update_post_meta($post_id, $new_key, ($meta[$old_key] == "1" ? 'true' : 'false'));
                if ($old_key == 'cpt_public') update_post_meta($post_id, 'has_archive', ($meta[$old_key] == "1" ? 'true' : 'false'));
            }
        }

        $supports = ['title', 'page-attributes', 'author', 'custom-fields', 'comments'];
        if (isset($meta['cpt_editor']) && $meta['cpt_editor'] == "1") $supports[] = 'editor';
        if (isset($meta['cpt_excerpt']) && $meta['cpt_excerpt'] == "1") $supports[] = 'excerpt';
        if (isset($meta['cpt_thumbnail']) && $meta['cpt_thumbnail'] == "1") $supports[] = 'thumbnail';
        update_post_meta($post_id, 'supports', $supports);

        update_post_meta($post_id, 'plural', get_the_title($post_id));

        return;
    }
}
