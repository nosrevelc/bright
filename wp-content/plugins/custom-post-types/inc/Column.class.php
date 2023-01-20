<?php

namespace CPT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class Column
{
    public function __construct()
    {
        //
    }
    public function add($post_type = 'post', $columns = [])
    {
        if (empty($post_type) || empty($columns)) return;
        global $pagenow;
        if ('edit.php' === $pagenow && isset($_GET['post_type']) && $_GET['post_type'] === $post_type) {
            add_filter('manage_posts_columns', function ($post_columns) use ($columns) {
                if (isset($columns['title'])) {
                    $stored_title_label = $post_columns['title'];
                }
                if (isset($columns['date'])) {
                    $stored_date_label = $post_columns['date'];
                }
                unset($post_columns['title']);
                unset($post_columns['date']);

                foreach ($columns as $key => $args) {
                    if ($key == 'title' && empty($args['label'])) $args['label'] = $stored_title_label;
                    if ($key == 'date' && empty($args['label'])) $args['label'] = $stored_date_label;
                    $post_columns[$key] = $args['label'];
                }
                return $post_columns;
            });
            add_action('manage_posts_custom_column', function ($post_column, $post_id) use ($columns) {
                if(isset($columns[$post_column]['callback'])) $columns[$post_column]['callback']($post_id);
            }, 10, 2);
        }
    }
}
