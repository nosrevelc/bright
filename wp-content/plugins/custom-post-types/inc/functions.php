<?php

use CPT\Fields;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function cpt_get_field_input_name($key = '', $parent = false)
{
    if (empty($key)) return;
    return "meta-fields" . ($parent ? $parent : '') . '[' . $key . ']';
}

function cpt_get_field_input_id($key = '', $parent = false)
{
    if (empty($key)) return;
    $parent = $parent ? str_replace('][', '-', $parent) : '';
    $parent = str_replace('[', '-', $parent);
    $parent = str_replace(']', '', $parent);
    return "meta-fields" . $parent . '-' . $key;
}

function get_post_types_select_options($key)
{
    $registered_post_types = get_post_types(['_builtin' => false], 'objects');
    $exclude = [$key, $key . "_tax", $key . "_field", $key . "_template"];
    $post_types = [
        'post' => __('Posts', 'custom-post-types'),
        'page' => __('Pages', 'custom-post-types'),
    ];
    foreach ($registered_post_types  as $post_type) {
        if (in_array($post_type->name, $exclude)) continue;
        $post_types[$post_type->name] = $post_type->label;
    }
    return $post_types;
}

function cpt_get_options_from_textarea($options_string = '') // Trnasform textare row to option array for fields
{
    $rows = explode(PHP_EOL, $options_string);
    $options_array = [];
    foreach ($rows as $row) {
        if (strpos($row, '|') !== false) {
            $options_array[trim(explode('|', $row)[0])] = trim(explode('|', $row)[1]);
        } else {
            $options_array[trim($row)] = trim($row);
        }
    }
    return $options_array;
}

function cpt_get_metabox_manage_cpt() // Create/edit new post type
{
    return [
        [ //singular
            'key' => 'singular',
            'label' => __('Singular', 'custom-post-types'),
            'info' => __('Singular name.', 'custom-post-types'),
            'required' => true,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Product', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //plural
            'key' => 'plural',
            'label' => __('Plural', 'custom-post-types'),
            'info' => __('Plural name.', 'custom-post-types'),
            'required' => true,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Products', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_add_new_item
            'key' => 'labels_add_new_item',
            'label' => __('Add new item', 'custom-post-types'),
            'info' => __('The add new item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Add new product', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_edit_item
            'key' => 'labels_edit_item',
            'label' => __('Edit item', 'custom-post-types'),
            'info' => __('The edit item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Edit product', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_new_item
            'key' => 'labels_new_item',
            'label' => __('New item', 'custom-post-types'),
            'info' => __('The new item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: New product', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_view_item
            'key' => 'labels_view_item',
            'label' => __('View item', 'custom-post-types'),
            'info' => __('The view item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: View product', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_view_items
            'key' => 'labels_view_items',
            'label' => __('View items', 'custom-post-types'),
            'info' => __('The view items text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: View products', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_search_items
            'key' => 'labels_search_items',
            'label' => __('Search items', 'custom-post-types'),
            'info' => __('The search item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Search products', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_not_found
            'key' => 'labels_not_found',
            'label' => __('Not found', 'custom-post-types'),
            'info' => __('The not found text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: No product found', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_not_found_in_trash
            'key' => 'labels_not_found_in_trash',
            'label' => __('Not found in trash', 'custom-post-types'),
            'info' => __('The not found in trash text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: No product found in trash', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_parent_item_colon
            'key' => 'labels_parent_item_colon',
            'label' => __('Parent item', 'custom-post-types'),
            'info' => __('The parent item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Parent product', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_all_items
            'key' => 'labels_all_items',
            'label' => __('All items', 'custom-post-types'),
            'info' => __('The all items text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: All products', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_archives
            'key' => 'labels_archives',
            'label' => __('Archivies', 'custom-post-types'),
            'info' => __('The archives text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Product archives', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //id
            'key' => 'id',
            'label' => __('Key', 'custom-post-types'),
            'info' => __('Post type key.', 'custom-post-types'),
            'required' => true,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: products', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'key-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //slug
            'key' => 'slug',
            'label' => __('Slug', 'custom-post-types'),
            'info' => __('Permalink base for posts (if empty, plural is used).', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: product', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'slug-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //supports
            'key' => 'supports',
            'label' => __('Supports', 'custom-post-types'),
            'info' => __('Set the available components when editing a post.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'multiple' => true,
                'options' => [
                    'title' => __('Title', 'custom-post-types'),
                    'editor' => __('Editor', 'custom-post-types'),
                    'comments' => __('Comments', 'custom-post-types'),
                    'revisions' => __('Revisions', 'custom-post-types'),
                    'trackbacks' => __('Trackbacks', 'custom-post-types'),
                    'author' => __('Author', 'custom-post-types'),
                    'excerpt' => __('Excerpt', 'custom-post-types'),
                    'page-attributes' => __('Page attributes', 'custom-post-types'),
                    'thumbnail' => __('Thumbnail', 'custom-post-types'),
                    'custom-fields' => __('Custom fields', 'custom-post-types'),
                    'post-formats' => __('Post formats', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //menu_icon
            'key' => 'menu_icon',
            'label' => __('Menu icon', 'custom-post-types'),
            'info' => __('Url to the icon, base64-encoded SVG using a data URI, name of a <a href="https://developer.wordpress.org/resource/dashicons" target="_blank" rel="nofolow">Dashicons</a> e.g. \'dashicons-chart-pie\'.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('dashicons-tag', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //public
            'key' => 'public',
            'label' => __('Public', 'custom-post-types'),
            'info' => __('If set to "YES" it will be shown in the frontend and will have a permalink and a single template.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('YES', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //admin only
            'key' => 'admin_only',
            'label' => __('Administrators only', 'custom-post-types'),
            'info' => __('If set to "YES" only the administrators can create / modify these contents, if "NO" all the roles with the minimum capacity of "edit_posts".', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //hierarchical
            'key' => 'hierarchical',
            'label' => __('Hierarchical', 'custom-post-types'),
            'info' => __('If set to "YES" it will be possible to set a parent POST TYPE (as for pages).', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //has_archive
            'key' => 'has_archive',
            'label' => __('Has archive', 'custom-post-types'),
            'info' => __('If set to "YES" the url of the post type archive will be reachable.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //exclude_from_search
            'key' => 'exclude_from_search',
            'label' => __('Exclude from search', 'custom-post-types'),
            'info' => __('If set to "YES" these posts will be excluded from the search results.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //show_in_rest
            'key' => 'show_in_rest',
            'label' => __('Show in rest', 'custom-post-types'),
            'info' => __('If set to "YES" API endpoints will be available (required for Gutenberg and other builders).', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('YES', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ]
    ];
}

function cpt_get_metabox_manage_cpt_tax($key) // Create/edit new taxonomy
{
    return [
        [ //singular
            'key' => 'singular',
            'label' => __('Singular', 'custom-post-types'),
            'info' => __('Singular name.', 'custom-post-types'),
            'required' => true,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Partner', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //plural
            'key' => 'plural',
            'label' => __('Plural', 'custom-post-types'),
            'info' => __('Plural name.', 'custom-post-types'),
            'required' => true,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Partners', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_add_new_item
            'key' => 'labels_add_new_item',
            'label' => __('Add new item', 'custom-post-types'),
            'info' => __('The add new item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Add new partner', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_edit_item
            'key' => 'labels_edit_item',
            'label' => __('Edit item', 'custom-post-types'),
            'info' => __('The edit item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Edit partner', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_new_item_name
            'key' => 'labels_new_item_name',
            'label' => __('New item name', 'custom-post-types'),
            'info' => __('The new item name text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Partner name', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_view_item
            'key' => 'labels_view_item',
            'label' => __('View item', 'custom-post-types'),
            'info' => __('The view item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: View partner', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_update_item
            'key' => 'labels_update_item',
            'label' => __('Update item', 'custom-post-types'),
            'info' => __('The update item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Update partner', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_search_items
            'key' => 'labels_search_items',
            'label' => __('Search items', 'custom-post-types'),
            'info' => __('The search item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Search partners', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_not_found
            'key' => 'labels_not_found',
            'label' => __('Not found', 'custom-post-types'),
            'info' => __('The not found text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: No partner found', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_parent_item
            'key' => 'labels_parent_item',
            'label' => __('Parent item', 'custom-post-types'),
            'info' => __('The parent item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Parent partner', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_parent_item_colon
            'key' => 'labels_parent_item_colon',
            'label' => __('Parent item', 'custom-post-types'),
            'info' => __('The parent item text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: Parent partner', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //labels_all_items
            'key' => 'labels_all_items',
            'label' => __('All items', 'custom-post-types'),
            'info' => __('The all items text.', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: All partners', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //id
            'key' => 'id',
            'label' => __('Key', 'custom-post-types'),
            'info' => __('Taxonomy key.', 'custom-post-types'),
            'required' => true,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: partner', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'key-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //slug
            'key' => 'slug',
            'label' => __('Slug', 'custom-post-types'),
            'info' => __('Permalink base for terms (if empty, plural is used).', 'custom-post-types'),
            'required' => false,
            'type' => 'text',
            'extra' => [
                'placeholder' => __('ex: partners', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => 'slug-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //supports
            'key' => 'supports',
            'label' => __('Assignment', 'custom-post-types'),
            'info' => __('Choose for which POST TYPE use this taxonomy.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'multiple' => true,
                'options' => get_post_types_select_options($key),
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //public
            'key' => 'public',
            'label' => __('Public', 'custom-post-types'),
            'info' => __('If set to "YES" it will be shown in the frontend and will have a permalink and a archive template.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('YES', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //admin only
            'key' => 'admin_only',
            'label' => __('Administrators only', 'custom-post-types'),
            'info' => __('If set to "YES" only the administrators can create / modify these contents, if "NO" all the roles with the minimum capacity of "edit_posts".', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //hierarchical
            'key' => 'hierarchical',
            'label' => __('Hierarchical', 'custom-post-types'),
            'info' => __('If set to "YES" it will be possible to set a parent TAXONOMY (as for the posts categories).', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => 'advanced-field',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ]
    ];
}

function cpt_get_new_field_args() // Fieldsbroup fields dropdown
{
    $available_fields_options = (new Fields)->available_fields_options;
    $form_fields = (new Fields)->get_new_field_form();
    $form_fields[3]['extra']['options'] = $available_fields_options;
    return $form_fields;
}
function cpt_get_metabox_manage_cpt_field($key) // Create/edit new field group
{
    // $available_fields_options = (new Fields)->available_fields_options;
    return [
        [ //position
            'key' => 'position',
            'label' => __('Position', 'custom-post-types'),
            'info' => __('If set to "NORMAL" it will be shown at the bottom of the central column, if "SIDEBAR" it will be shown in the sidebar.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('NORMAL', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'normal' => __('NORMAL', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                    'side' => __('SIDEBAR', 'custom-post-types'),
                    'advanced' => __('ADVANCED', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //order
            'key' => 'order',
            'label' => __('Order', 'custom-post-types'),
            'info' => __('Field groups with a lower order will appear first', 'custom-post-types'),
            'required' => false,
            'type' => 'number',
            'extra' => [
                'placeholder' => __('ex: 10', 'custom-post-types')
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //supports
            'key' => 'supports',
            'label' => __('Assignment', 'custom-post-types'),
            'info' => __('Choose for which POST TYPE use this taxonomy.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'multiple' => true,
                'options' => get_post_types_select_options($key),
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ //admin only
            'key' => 'admin_only',
            'label' => __('Administrators only', 'custom-post-types'),
            'info' => __('If set to "YES" only the administrators can create / modify these contents, if "NO" all the roles with the minimum capacity of "edit_posts".', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'multiple' => false,
                'options' => [
                    'true' => __('YES', 'custom-post-types'),
                    'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ // fields
            'key' => 'fields',
            'label' => __('Fields list', 'custom-post-types'),
            'info' => 'Le istruzioni2',
            'required' => false,
            'type' => 'repeater',
            'extra' => [
                'fields' => cpt_get_new_field_args()
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => 'add-field',
            ]
        ],
    ];
}

function cpt_get_metabox_manage_cpt_template($key) // Create/edit new template
{
    return [
        [ //wrap
            'key' => 'wrap',
            'label' => __('Wrap type', 'custom-post-types'),
            'info' => __('Choose for which POST TYPE use this template.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'multiple' => false,
                'placeholder' => __('Normal (like blog post)', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                'options' => [
                    'normal' => __('Normal (like blog post)', 'custom-post-types'),
                    'blank' => __('Blank template', 'custom-post-types') . '*',
                ],
            ],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
                'layout' => ''
            ]
        ],
        [ //supports
            'key' => 'supports',
            'label' => __('Used by', 'custom-post-types'),
            'info' => __('Choose for which POST TYPE use this template.', 'custom-post-types'),
            'required' => false,
            'type' => 'select',
            'extra' => [
                'multiple' => false,
                'options' => get_post_types_select_options($key),
            ],
            'wrap' => [
                'width' => '',
                'class' => 'template-for',
                'id' => '',
                'layout' => ''
            ]
        ],
        [ //extra
            'key' => 'extra',
            'label' => '',
            'info' => false,
            'required' => false,
            'type' => 'template',
            'extra' => [],
            'wrap' => [
                'width' => '',
                'class' => 'extra-fields',
                'id' => '',
                'layout' => ''
            ]
        ],
    ];
}

function cpt_get_metabox_ds() // cpt_get_metabox_ds() //design system
{
    return [
        [ // text
            'key' => 'titolo',
            'label' => 'Titolo',
            'info' => 'Le istruzioni',
            'required' => false,
            'type' => 'text',
            'extra' => [
                'prepend' => 'Prima',
                'append' => 'Dopo',
            ],
            'wrap' => [
                'width' => '50',
                'class' => '',
                'id' => ''
            ]
        ],
        [ // select multiple
            'key' => 'categoria',
            'label' => 'Categoria',
            'info' => 'Le istruzioni2',
            'required' => false,
            'type' => 'select',
            'extra' => [
                'multiple' => true,
                'options' => [
                    'uno' => 'Uno',
                    '2' => 'Due',
                    'tre' => '3',
                    'Test'
                ],
            ],
            'wrap' => [
                'width' => '50',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ // textarea
            'key' => 'descrizione',
            'label' => 'Descrizione',
            'info' => 'Le istruzioni244',
            'required' => false,
            'type' => 'textarea',
            'extra' => [],
            'wrap' => [
                'width' => '50',
                'class' => '',
                'id' => ''
            ]
        ],
        [ // select
            'key' => 'tipo',
            'label' => 'Tipo',
            'info' => 'Le istruzioni2',
            'required' => false,
            'type' => 'select',
            'extra' => [
                'multiple' => false,
                'options' => [
                    'uno' => 'Uno',
                    '2' => 'Due',
                    'tre' => '3',
                    'Test'
                ],
            ],
            'wrap' => [
                'width' => '50',
                'class' => '',
                'id' => '',
            ]
        ],
        [ // file
            'key' => 'immagine',
            'label' => 'Immagine',
            'info' => 'Le istruzioni22',
            'required' => false,
            'type' => 'file',
            'extra' => [
                'types' => ['video', 'application/pdf', 'image']
            ],
            'wrap' => [
                'width' => '70',
                'class' => '',
                'id' => '',
                'layout' => 'horizontal'
            ]
        ],
        [ // email
            'key' => 'email',
            'label' => 'Data',
            'info' => 'Le istruzioni22',
            'required' => false,
            'type' => 'email',
            'extra' => [],
            'wrap' => [
                'width' => '30',
                'class' => '',
                'id' => '',
            ]
        ],
        [ // editor
            'key' => 'descrizione334',
            'label' => 'Editor visuale',
            'info' => 'Le istruzioni22',
            'required' => false,
            'type' => 'tinymce',
            'extra' => [],
            'wrap' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ]
        ],
        [ // checkbox
            'key' => 'tipo343434',
            'label' => 'Tipo',
            'info' => 'Le istruzioni2',
            'required' => false,
            'type' => 'checkbox',
            'extra' => [
                'options' => [
                    'uno' => 'Uno',
                    '2' => 'Due',
                    'tre' => '3',
                    'Test'
                ],
            ],
            'wrap' => [
                'width' => '50',
                'class' => '',
                'id' => '',
            ]
        ],
        [ // radio
            'key' => 'tipo34343',
            'label' => 'Tipo',
            'info' => 'Le istruzioni2',
            'required' => false,
            'type' => 'radio',
            'extra' => [
                'options' => [
                    'uno' => 'Uno',
                    '2' => 'Due',
                    'tre' => '3',
                    'Test'
                ],
            ],
            'wrap' => [
                'width' => '50',
                'class' => '',
                'id' => '',
            ]
        ],
        [ // color
            'key' => 'tipo343',
            'label' => 'Tipo',
            'info' => 'Le istruzioni2',
            'required' => false,
            'type' => 'color',
            'extra' => [
                'alpha' => true
            ],
            'wrap' => [
                'width' => '50',
                'class' => '',
                'id' => '',
            ]
        ],
        [ // date
            'key' => 'birthdate',
            'label' => 'Una data',
            'info' => 'Le istruzioni2',
            'required' => false,
            'type' => 'date',
            'extra' => [
                'max' => false,
                'min' => false,
            ],
            'wrap' => [
                'width' => '50',
                'class' => '',
                'id' => '',
            ]
        ],
        [ // repeater
            'key' => 'ripetitore',
            'label' => 'Una data',
            'info' => 'Le istruzioni2',
            'required' => false,
            'type' => 'repeater',
            'extra' => [
                'fields' => [
                    [
                        'key' => 'tipo34344',
                        'label' => 'Tipo Gruppo',
                        'info' => 'Le istruzioni2',
                        'required' => false,
                        'type' => 'color',
                        'extra' => [
                            'alpha' => false
                        ],
                        'wrap' => [
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ]
                    ],
                    [
                        'key' => 'birthdate232',
                        'label' => 'Una data gruppo',
                        'info' => 'Le istruzioni2',
                        'required' => false,
                        'type' => 'date',
                        'extra' => [
                            'max' => false,
                            'min' => false,
                        ],
                        'wrap' => [
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ]
                    ],
                    [
                        'key' => 'ripetitore',
                        'label' => 'Una data',
                        'info' => 'Le istruzioni2',
                        'required' => false,
                        'type' => 'repeater',
                        'extra' => [
                            'fields' => [
                                [
                                    'key' => 'tipo34344',
                                    'label' => 'Tipo Gruppo',
                                    'info' => 'Le istruzioni2',
                                    'required' => false,
                                    'type' => 'color',
                                    'extra' => [
                                        'alpha' => false
                                    ],
                                    'wrap' => [
                                        'width' => '50',
                                        'class' => '',
                                        'id' => '',
                                    ]
                                ],
                                [
                                    'key' => 'birthdate232',
                                    'label' => 'Una data gruppo',
                                    'info' => 'Le istruzioni2',
                                    'required' => false,
                                    'type' => 'date',
                                    'extra' => [
                                        'max' => false,
                                        'min' => false,
                                    ],
                                    'wrap' => [
                                        'width' => '50',
                                        'class' => '',
                                        'id' => '',
                                    ]
                                ]
                            ]
                        ],
                        'wrap' => [
                            'width' => '100',
                            'class' => '',
                            'id' => '',
                        ]
                    ],
                ]
            ],
            'wrap' => [
                'width' => '50',
                'class' => '',
                'id' => '',
            ]
        ],
    ];
}

function cpt_get_ui_cpts($key) // Args for system cpt
{
    return [
        [ // Create/edit new post type
            'id' => $key,
            'singular' => __('Post type', 'custom-post-types'),
            'plural' => __('Post types', 'custom-post-types'),
            'labels' => [
                'name'               => _x('Custom post types', 'Dashboard menu', 'custom-post-types'),
                'singular_name'      => __('Post type', 'custom-post-types'),
                'menu_name'          => __('Extend / Manage', 'custom-post-types'),
                'name_admin_bar'     => __('Post type', 'custom-post-types'),
                'add_new'            => __('Add post type', 'custom-post-types'),
                'add_new_item'       => __('Add new post type', 'custom-post-types'),
                'new_item'           => __('New post type', 'custom-post-types'),
                'edit_item'          => __('Edit post type', 'custom-post-types'),
                'view_item'          => __('View post type', 'custom-post-types'),
                'all_items'          => _x('Post types', 'Dashboard menu', 'custom-post-types'),
                'search_items'       => __('Search post type', 'custom-post-types'),
                'not_found'          => __('No post type available.', 'custom-post-types'),
                'not_found_in_trash' => __('No post type in the trash.', 'custom-post-types')
            ],
            'args' => [
                'description'        => __('Create and manage custom post types.', 'custom-post-types'),
            ],
            'columns' => [
                'title' => [
                    'label' => __('Plural', 'custom-post-types'),
                ],
                'item_key' => [
                    'label' => __('Key', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        echo get_post_meta($post_id, 'id', true);
                    }
                ],
                'item_count' => [
                    'label' => __('Count', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        $key = get_post_meta($post_id, 'id', true);
                        if (empty($key) || !(isset(wp_count_posts($key)->publish) ? wp_count_posts($key)->publish : false)) {
                            echo "0";
                            return;
                        }
                        printf(
                            '<a href="%s" title="%s">%s</a>',
                            admin_url('edit.php?post_type=' . $key),
                            __('View', 'custom-post-types'),
                            wp_count_posts($key)->publish
                        );
                    }
                ],
                'date' => [],
            ]
        ],
        [ // Create/edit new tax
            'id' => $key . '_tax',
            'singular' => __('Taxonomy', 'custom-post-types'),
            'plural' => __('Taxonomies', 'custom-post-types'),
            'labels' => [
                'name'               => __('Custom taxonomies', 'custom-post-types'),
                'singular_name'      => __('Taxonomy', 'custom-post-types'),
                'menu_name'          => __('Taxonomy', 'custom-post-types'),
                'name_admin_bar'     => __('Taxonomy', 'custom-post-types'),
                'add_new'            => __('Add taxonomy', 'custom-post-types'),
                'add_new_item'       => __('Add new taxonomy', 'custom-post-types'),
                'new_item'           => __('New taxonomy', 'custom-post-types'),
                'edit_item'          => __('Edit taxonomy', 'custom-post-types'),
                'view_item'          => __('View taxonomy', 'custom-post-types'),
                'all_items'          => __('Taxonomies', 'custom-post-types'),
                'search_items'       => __('Search taxonomy', 'custom-post-types'),
                'not_found'          => __('No taxonomy available.', 'custom-post-types'),
                'not_found_in_trash' => __('No taxonomy in the trash.', 'custom-post-types')
            ],
            'args' => [
                'description'        => __('Create and manage custom taxonomies.', 'custom-post-types'),
                'show_in_menu' => 'edit.php?post_type=' . $key
            ],
            'columns' => [
                'title' => [
                    'label' => __('Plural', 'custom-post-types'),
                ],
                'item_key' => [
                    'label' => __('Key', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        echo get_post_meta($post_id, 'id', true);
                    }
                ],
                'item_count' => [
                    'label' => __('Count', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        $key = get_post_meta($post_id, 'id', true);
                        if (empty($key) || is_wp_error(wp_count_terms(['taxonomy' => $key]))) {
                            echo "0";
                            return;
                        }
                        printf(
                            '<a href="%s" title="%s">%s</a>',
                            admin_url('edit-tags.php?taxonomy=' . $key),
                            __('View', 'custom-post-types'),
                            wp_count_terms(['taxonomy' => $key])
                        );
                    }
                ],
                'used_by' => [
                    'label' => __('Used by', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        $supports = get_post_meta($post_id, 'supports', true);
                        if (empty($supports)) return;
                        $output = [];
                        foreach ($supports as $post_type) {
                            if (!get_post_type_object($post_type)) continue;
                            $output[] = sprintf(
                                '<a href="%s" title="%s">%s</a>',
                                admin_url('edit.php?post_type=' . $post_type),
                                __('View', 'custom-post-types'),
                                get_post_type_object($post_type)->labels->name
                            );
                        }
                        echo implode(', ', $output);
                    }
                ],
                'date' => [],
            ]
        ],
        [ // Create/edit new fieldsgroup
            'id' => $key . '_field',
            'singular' => __('Field group', 'custom-post-types'),
            'plural' => __('Field groups', 'custom-post-types'),
            'labels' => [
                'name'               => __('Custom field groups', 'custom-post-types'),
                'singular_name'      => __('Field group', 'custom-post-types'),
                'menu_name'          => __('Field group', 'custom-post-types'),
                'name_admin_bar'     => __('Field group', 'custom-post-types'),
                'add_new'            => __('Add field group', 'custom-post-types'),
                'add_new_item'       => __('Add new field group', 'custom-post-types'),
                'new_item'           => __('New field group', 'custom-post-types'),
                'edit_item'          => __('Edit field group', 'custom-post-types'),
                'view_item'          => __('View field group', 'custom-post-types'),
                'all_items'          => __('Field groups', 'custom-post-types'),
                'search_items'       => __('Search field group', 'custom-post-types'),
                'not_found'          => __('No field group available.', 'custom-post-types'),
                'not_found_in_trash' => __('No field group in the trash.', 'custom-post-types')
            ],
            'args' => [
                'description'        => __('Create and manage custom field groups.', 'custom-post-types'),
                'show_in_menu' => 'edit.php?post_type=' . $key,
                'supports' => ['title']
            ],
            'columns' => [
                'title' => [
                    'label' => __('Field group name', 'custom-post-types'),
                ],
                'item_count' => [
                    'label' => __('Fields', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        $fields = get_post_meta($post_id, 'fields', true);
                        if (empty($fields)) return;
                        $fields_labels_array = array_map(
                            function ($field) {
                                return $field['label'];
                            },
                            $fields
                        );
                        echo implode(', ', $fields_labels_array);
                    }
                ],
                'item_position' => [
                    'label' => __('Position', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        $available = [
                            '' => __('NORMAL', 'custom-post-types'),
                            'normal' => __('NORMAL', 'custom-post-types'),
                            'side' => __('SIDEBAR', 'custom-post-types'),
                            'advanced' => __('ADVANCED', 'custom-post-types'),
                        ];
                        echo $available[get_post_meta($post_id, 'position', true)];
                    }
                ],
                'used_by' => [
                    'label' => __('Used by', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        $supports = get_post_meta($post_id, 'supports', true);
                        if (empty($supports)) return;
                        $output = [];
                        foreach ($supports as $post_type) {
                            if (!get_post_type_object($post_type)) continue;
                            $output[] = sprintf(
                                '<a href="%s" title="%s">%s</a>',
                                admin_url('edit.php?post_type=' . $post_type),
                                __('View', 'custom-post-types'),
                                get_post_type_object($post_type)->labels->name
                            );
                        }
                        echo implode(', ', $output);
                    }
                ],
                'date' => [],
            ]
        ],
        [ // Create/edit new template
            'id' => $key . '_template',
            'singular' => __('Template', 'custom-post-types'),
            'plural' => __('Templates', 'custom-post-types'),
            'labels' => [
                'name'               => __('Custom templates', 'custom-post-types'),
                'singular_name'      => __('Template', 'custom-post-types'),
                'menu_name'          => __('Template', 'custom-post-types'),
                'name_admin_bar'     => __('Template', 'custom-post-types'),
                'add_new'            => __('Add template', 'custom-post-types'),
                'add_new_item'       => __('Add new template', 'custom-post-types'),
                'new_item'           => __('New template', 'custom-post-types'),
                'edit_item'          => __('Edit template', 'custom-post-types'),
                'view_item'          => __('View template', 'custom-post-types'),
                'all_items'          => __('Templates', 'custom-post-types'),
                'search_items'       => __('Search template', 'custom-post-types'),
                'not_found'          => __('No template available.', 'custom-post-types'),
                'not_found_in_trash' => __('No template in the trash.', 'custom-post-types')
            ],
            'args' => [
                'description'        => __('Create and manage custom templates.', 'custom-post-types'),
                'show_in_menu' => 'edit.php?post_type=' . $key,
                'supports' => ['title', 'editor']
            ],
            'metabox_position' => 'side',
            'columns' => [
                'title' => [
                    'label' => __('Template name', 'custom-post-types'),
                ],
                'wrap' => [
                    'label' => __('Wrap type', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        $available = [
                            '' => __('NORMAL', 'custom-post-types'),
                            'normal' => __('NORMAL', 'custom-post-types'),
                            'blank' => __('BLANK', 'custom-post-types'),
                        ];
                        echo $available[get_post_meta($post_id, 'position', true)];
                    }
                ],
                'used_by' => [
                    'label' => __('Used by', 'custom-post-types'),
                    'callback' => function ($post_id) {
                        $supports = [get_post_meta($post_id, 'supports', true)];
                        if (empty($supports)) return;
                        $output = [];
                        foreach ($supports as $post_type) {
                            if (!get_post_type_object($post_type)) continue;
                            $output[] = sprintf(
                                '<a href="%s" title="%s">%s</a>',
                                admin_url('edit.php?post_type=' . $post_type),
                                __('View', 'custom-post-types'),
                                get_post_type_object($post_type)->labels->name
                            );
                        }
                        echo implode(', ', $output);
                    }
                ],
                'date' => [],
            ]
        ],
    ];
}

function cpt_get_fields_by_post_type($post_type = false)
{
    if (!$post_type) return [];
    $created_fields_groups = get_posts([
        'posts_per_page' => -1,
        'post_type' => 'manage_cpt_field'
    ]);
    $fields = [];

    if (post_type_supports($post_type, 'title')) $fields['title'] = ['label' => __('Post title', 'custom-post-types')];
    if (post_type_supports($post_type, 'editor')) $fields['content'] = ['label' => __('Post content', 'custom-post-types')];
    if (post_type_supports($post_type, 'excerpt')) $fields['excerpt'] = ['label' => __('Post excerpt', 'custom-post-types')];
    if (post_type_supports($post_type, 'thumbnail')) $fields['thumbnail'] = ['label' => __('Post image', 'custom-post-types')];
    if (post_type_supports($post_type, 'author')) $fields['author'] = ['label' => __('Post author', 'custom-post-types')];
    $fields['written_date'] = ['label' => __('Post date', 'custom-post-types')];
    $fields['modified_date'] = ['label' => __('Post modified date', 'custom-post-types')];

    foreach ($created_fields_groups as $index => $created_fields_group) {
        $fields_group_post_types = get_post_meta($created_fields_group->ID, 'supports', true);
        if (!in_array($post_type, $fields_group_post_types)) {
            unset($created_fields_groups[$index]);
            continue;
        }
        $fields_group_fields = get_post_meta($created_fields_group->ID, 'fields', true);
        if (!empty($fields_group_fields)) {
            foreach ($fields_group_fields as $field) {
                $fields[$field['key']] = [
                    'label' => $field['label'],
                    'type' => $field['type'],
                ];
            }
        }
    }
    return $fields;
}

function cpt_is_current_screen($screen)
{
    if (is_admin() && basename($_SERVER['PHP_SELF']) == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == $screen) return true;
    return false;
}

function custom_post_types_get_custom_template() // 2.x Compatibility
{
    cpt_get_custom_template();
}

function cpt_get_custom_template()
{
    the_content();
}

function cpt_is_rest()
{
    $prefix = rest_get_url_prefix();
    if (
        defined('REST_REQUEST') && REST_REQUEST // (#1)
        || isset($_GET['rest_route']) // (#2)
        && strpos(trim($_GET['rest_route'], '\\/'), $prefix, 0) === 0
    )
        return true;
    // (#3)
    global $wp_rewrite;
    if ($wp_rewrite === null) $wp_rewrite = new WP_Rewrite();

    // (#4)
    $rest_url = wp_parse_url(trailingslashit(rest_url()));
    $current_url = wp_parse_url(add_query_arg(array()));
    return strpos($current_url['path'], $rest_url['path'], 0) === 0;
}

function get_custom_field($field_id = null, $post_id = null) // 2.x Compatibility
{
    return cpt_get_field($field_id);
}

function cpt_get_field($key, $post_id = false)
{
    global $post;
    $post = $post_id && get_post($post_id) ? get_post($post_id) : $post;
    $core_fields = [
        'title' => get_the_title($post->ID),
        'content' => get_the_content($post->ID),
        'excerpt' => get_the_excerpt($post->ID),
        'thumbnail' => get_the_post_thumbnail($post->ID, 'full'),
        'author' => sprintf('<a href="%1$s" title="%2$s" aria-title="%2$s">%2$s</a>', get_author_posts_url(get_the_author_meta('ID')), get_the_author()),
        'written_date' => get_the_date(get_option('date_format', "d/m/Y"), $post->ID),
        'modified_date' => get_the_modified_date(get_option('date_format', "d/m/Y"), $post->ID),
    ];
    $value = isset($core_fields[$key]) ? $core_fields[$key] : get_post_meta($post->ID, $key, true);
    $post_type_fields = cpt_get_fields_by_post_type($post->post_type);
    $type = isset($post_type_fields[$key]['type']) ? $post_type_fields[$key]['type'] : $key;
    $output = $value;
    $output = apply_filters("cpt_get_field_type_$type", $output, $value, $post->post_type, $post->ID);
    $output = apply_filters("cpt_get_field_$key", $output, $value, $post->post_type, $post->ID);
    $output = is_array($output) ? (current_user_can('edit_posts') ? '<pre>' . print_r($output, true) . '</pre>' : '') : $output;
    return $output;
}

function cpt_sanitize_repeater_field($fields = [])
{
    if (empty($fields)) return [];
    foreach ($fields as $i => $field) {
        $fields[$i]['extra'] = isset($field['extra'][$field['type']]) ? $field['extra'][$field['type']] : $field['extra'];
        $fields[$i]['wrap']['width'] = isset($field['wrap']['width']) && !empty($field['wrap']['width']) ? (int) $field['wrap']['width'] : (isset($field['wrap_width']) && !empty($field['wrap_width']) ? $field['wrap_width'] : 100);
        unset($fields[$i]['wrap_width']);
        $fields[$i]['wrap']['class'] = isset($field['wrap']['class']) && !empty($field['wrap']['class']) ? $field['wrap']['class'] : (isset($field['wrap_class']) && !empty($field['wrap_class']) ? $field['wrap_class'] : false);
        unset($fields[$i]['wrap_class']);
        $fields[$i]['wrap']['id'] = isset($field['wrap']['id']) && !empty($field['wrap']['id']) ? $field['wrap']['id'] : (isset($field['wrap_id']) && !empty($field['wrap_id']) ? $field['wrap_id'] : false);
        unset($fields[$i]['wrap_id']);
        $fields[$i]['wrap']['layout'] = isset($field['wrap']['layout']) && $field['wrap']['layout'] == 'horizontal' ? 'horizontal' : (isset($field['wrap_layout']) && !empty($field['wrap_layout']) ? $field['wrap_layout'] : 'vertical');
        unset($fields[$i]['wrap_layout']);
    }
    return $fields;
}

function cpt_get_post_title_with_parents($post_id = 0, $string = '')
{
    $post = get_post($post_id);
    if ($post_id == 0 || !$post) return $string;
    $string = empty($string) ? $post->post_title : $string;
    if ($post->post_parent == 0) return $string;
    $string = get_the_title($post->post_parent) . ' > ' . $string;
    return cpt_get_post_title_with_parents($post->post_parent, $string);
}

function cpt_get_term_title_with_parents($term_id = 0, $string = '')
{
    $term = get_term($term_id);
    if ($term_id == 0 || !$term) return $string;
    $string = empty($string) ? $term->name : $string;
    if ($term->parent == 0) return $string;
    $string = get_term($term->parent)->name . ' > ' . $string;
    return cpt_get_term_title_with_parents($term->parent, $string);
}
