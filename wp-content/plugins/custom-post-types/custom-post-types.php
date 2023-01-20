<?php
/*
Plugin Name: Custom post types
Plugin URI: https://totalpress.org/plugins/custom-post-types?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types
Description: Create / manage custom post types, custom taxonomies, custom fields and custom templates easily, directly from the WordPress dashboard without writing code.
Author: TotalPress.org
Author URI: https://totalpress.org/?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types
Text Domain: custom-post-types
Domain Path: /languages/
Version: 3.0.11
*/

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

define('CPT_VER', get_file_data(__FILE__, ['Version' => 'Version'], false)['Version']);
define('CPT_PATH', plugin_dir_path(__FILE__));
define('CPT_URL', plugin_dir_url(__FILE__));
define('CPT_PLUGIN_URL', 'https://totalpress.org/plugins/custom-post-types?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types');
define('CPT_PLUGIN_DEV_URL', 'https://www.andreadegiovine.it/?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types');
define('CPT_PLUGIN_DOC_URL', 'https://totalpress.org/docs/custom-post-types.html?doc&utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types');
define('CPT_PLUGIN_DONATE_URL', 'https://totalpress.org/donate?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=custom-post-types');
define('CPT_PLUGIN_WP_URL', 'https://wordpress.org/plugin/custom-post-types');
define('CPT_PLUGIN_SUPPORT_URL', 'https://wordpress.org/support/plugin/custom-post-types');
define('CPT_PLUGIN_REVIEW_URL', 'https://wordpress.org/support/plugin/custom-post-types/reviews/#new-post');

foreach (glob(CPT_PATH . "inc/*.php") as $filename) {
    include_once $filename;
}

if (!class_exists('totalpress_custom_post_types')) {
    class totalpress_custom_post_types
    {
        public $cpt_ui_name = 'manage_cpt';
        public $priority = PHP_INT_MAX;

        public function __construct()
        {
            add_action('init', [$this, 'textdomain'], -1);
            add_action('init', [$this, 'action_links']);
            add_action('init', [$this, 'settings_page']);
            add_action('init', [$this, 'ui_cpts'], $this->priority);
            add_action('init', [$this, 'ui_cpts_hooks']);
            add_action('init', [$this, 'created_cpts'], 0);
            add_action('init', [$this, 'created_taxs'], 0);
            add_action('init', [$this, 'created_fields_groups'], $this->priority);
            add_action('init', [$this, 'created_templates'], $this->priority);
            add_action('init', [$this, 'notices']);
            add_action('init', [$this, 'init_assets'], $this->priority);
            add_action('wp', [$this, 'shortcodes']);

            add_action('init', [$this, 'update_from_old_version']);
        }

        public function update_from_old_version()
        {
            if ((isset($_GET['post_type']) ? $_GET['post_type'] : '') == $this->cpt_ui_name &&
                (isset($_GET['page']) ? $_GET['page'] : '') == 'tools' &&
                (isset($_GET['action']) ? $_GET['action'] : '') == 'database-update' &&
                wp_verify_nonce((isset($_GET['nonce']) ? $_GET['nonce'] : ''), 'cpt-nonce')
            ) {
                update_option('custom_post_types_update_post_types_from_old_version', 0);
                update_option('custom_post_types_update_taxs_from_old_version', 0);
                update_option('custom_post_types_update_fieldsgroups_from_old_version', 0);
                update_option('custom_post_types_update_templates_from_old_version', 0);
                (new CPT\Notice)->add(
                    'db_update_notice',
                    __('The database update process was repeated.', 'custom-post-types'),
                    'warning',
                    true,
                    false
                );
            }

            $post_types_from_old_version = get_option('custom_post_types_update_post_types_from_old_version', 0);
            if ($post_types_from_old_version != 1) {
                $created_post_types = get_posts(['posts_per_page' => -1, 'post_type' => $this->cpt_ui_name]);
                foreach ($created_post_types as $post) {
                    $post_meta = get_post_meta($post->ID, '_cpt_settings_meta', true);
                    (new CPT\PostType())->update_old_post_meta($post_meta, $post->ID);
                }
                update_option('custom_post_types_update_post_types_from_old_version', 1);
            }

            $taxs_from_old_version = get_option('custom_post_types_update_taxs_from_old_version', 0);
            if ($taxs_from_old_version != 1) {
                $created_taxs = get_posts(['posts_per_page' => -1, 'post_type' => $this->cpt_ui_name . '_tax']);
                foreach ($created_taxs as $post) {
                    $post_meta = get_post_meta($post->ID, '_tax_settings_meta', true);
                    (new CPT\Taxonomy())->update_old_post_meta($post_meta, $post->ID);
                }
                update_option('custom_post_types_update_taxs_from_old_version', 1);
            }

            $fieldsgroups_from_old_version = get_option('custom_post_types_update_fieldsgroups_from_old_version', 0);
            if ($fieldsgroups_from_old_version != 1) {
                $created_fieldsgroups = get_posts(['posts_per_page' => -1, 'post_type' => $this->cpt_ui_name . '_field']);
                foreach ($created_fieldsgroups as $post) {
                    $post_meta = get_post_meta($post->ID, '_field_settings_meta', true);
                    (new CPT\MetaBox())->update_old_post_meta($post_meta, $post->ID);
                }
                update_option('custom_post_types_update_fieldsgroups_from_old_version', 1);
            }

            $templates_from_old_version = get_option('custom_post_types_update_templates_from_old_version', 0);
            if ($templates_from_old_version != 1) {
                $created_templates = get_posts(['posts_per_page' => -1, 'post_type' => $this->cpt_ui_name . '_template']);
                foreach ($created_templates as $post) {
                    $post_meta = get_post_meta($post->ID, '_template_settings_meta', true);
                    (new CPT\Template())->update_old_post_meta($post_meta, $post->ID);
                }
                update_option('custom_post_types_update_templates_from_old_version', 1);
            }
        }

        public function textdomain()
        {
            load_plugin_textdomain('custom-post-types', false, CPT_PATH . 'languages');
        }

        public function ui_cpts()
        {

            $default_args = [
                'public'             => false,
                'publicly_queryable' => false,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => false,
                'rewrite'            => false,
                'capabilities' => [
                    'edit_post'          => 'update_core',
                    'read_post'          => 'update_core',
                    'delete_post'        => 'update_core',
                    'edit_posts'         => 'update_core',
                    'edit_others_posts'  => 'update_core',
                    'delete_posts'       => 'update_core',
                    'publish_posts'      => 'update_core',
                    'read_private_posts' => 'update_core'
                ],
                'has_archive'        => false,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => [''], //['title'],
                'menu_icon'          => 'dashicons-index-card',
                'can_export'         => false,
            ];

            $advanced_view_button_field = [
                'key' => 'advanced_fields',
                'label' => '',
                'info' => '',
                'required' => false,
                'type' => 'html',
                'extra' => [
                    'content' => sprintf(
                        '<button class="button button-secondary advanced-view"><span class="dashicons dashicons-insert"></span><span class="label">%s</span></button>',
                        __('Advanced view', 'custom-post-types')
                    )
                ],
                'wrap' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ]
            ];

            $ui_cpt_args = cpt_get_ui_cpts($this->cpt_ui_name);

            foreach ($ui_cpt_args as $cpt_id => $ui_cpt) {
                $cpt_args = array_replace_recursive($default_args, $ui_cpt['args']);

                (new CPT\PostType)->register(
                    $ui_cpt['id'],
                    $ui_cpt['singular'],
                    $ui_cpt['plural'],
                    $cpt_args,
                    $ui_cpt['labels']
                );

                (new CPT\Column())->add(
                    $ui_cpt['id'],
                    isset($ui_cpt['columns']) ? $ui_cpt['columns'] : []
                );

                $cpt_metabox = 'cpt_get_metabox_' . $ui_cpt['id'];
                if (!function_exists($cpt_metabox)) continue;
                $meta_fields =  $cpt_metabox($this->cpt_ui_name);
                if (in_array($ui_cpt['id'], [$this->cpt_ui_name, $this->cpt_ui_name . '_tax'])) array_push($meta_fields, $advanced_view_button_field);

                (new CPT\MetaBox())->add(
                    $ui_cpt['id'],
                    sprintf(__('%s settings', 'custom-post-types'), $ui_cpt['singular']),
                    $meta_fields,
                    isset($ui_cpt['metabox_position']) ? $ui_cpt['metabox_position'] : 'normal'
                );
            }
        }

        public function ui_cpts_hooks()
        {
            $no_title_ui_cpts = [$this->cpt_ui_name, $this->cpt_ui_name . '_tax'];

            add_action('save_post', function ($post_id) use ($no_title_ui_cpts) {
                $post_type = get_post($post_id)->post_type;
                $post_status = get_post($post_id)->post_status;
                if (!in_array($post_type, $no_title_ui_cpts) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || $post_status == 'trash') return $post_id;
                $new_title = isset($_POST['meta-fields']['plural']) && !empty($_POST['meta-fields']['plural']) ? $_POST['meta-fields']['plural'] : 'CPT_' . $post_id;
                global $wpdb;
                $wpdb->update($wpdb->posts, ['post_title' => $new_title], ['ID' => $post_id]);
                return $post_id;
            }, $this->priority);

            add_action('edit_form_after_title', function () use ($no_title_ui_cpts) {
                $screen = get_current_screen();
                $post = isset($_GET['post']) && get_post($_GET['post']) ? get_post($_GET['post']) : false;
                if (!in_array($screen->post_type, $no_title_ui_cpts) || !in_array($screen->id, $no_title_ui_cpts) || !$post) return;
                printf('<h1 style="padding: 0;">%s</h1>', $post->post_title);
            }, $this->priority);

            add_action('wp_ajax_cpt_get_extra_fields', function () {
                $nonce = isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'cpt-nonce') ? true : false;
                $field_type = isset($_POST['field-type']) ? $_POST['field-type'] : false;
                $available_fields = (new CPT\Fields)->available_fields;
                $fields = isset($available_fields[$field_type]['fields']) ? $available_fields[$field_type]['fields'] : [];
                if (empty($field_type) || !is_array($fields) || !$nonce) wp_send_json_error();
                $fields_parent = isset($_POST['parent']) ? $_POST['parent'] : '';
                ob_start();
                foreach ($fields as $field) {
                    $field['parent'] = $fields_parent . '[extra][' . $field_type . ']';
                    (new CPT\MetaBox())->the_field_wrap($field);
                }
                $output = ob_get_clean();
                wp_send_json_success($output);
            });

            add_action('wp_ajax_cpt_get_terms_array', function () {
                $nonce = isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cpt-nonce') ? true : false;
                $taxonomy = isset($_REQUEST['taxonomy']) ? $_REQUEST['taxonomy'] : false;
                $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
                if (empty($taxonomy) || !$nonce) wp_send_json_error();
                $terms = get_terms([
                    'taxonomy' => $taxonomy,
                    'name__like' => $search,
                    'hide_empty' => false,
                    'number' => 10
                ]);
                $output = [];
                foreach ($terms as $term) {
                    $output[] = [
                        'id' => $term->term_id,
                        'text' => cpt_get_term_title_with_parents($term->term_id)
                    ];
                }
                wp_send_json_success($output);
            });

            add_action('wp_ajax_cpt_get_posts_array', function () {
                $nonce = isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cpt-nonce') ? true : false;
                $post_type = isset($_REQUEST['post_type']) ? $_REQUEST['post_type'] : false;
                $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
                if (empty($post_type) || !$nonce) wp_send_json_error();
                $posts = get_posts([
                    'post_type' => $post_type,
                    's' => $search,
                    'numberposts' => 10
                ]);
                $output = [];
                foreach ($posts as $post) {
                    $output[] = [
                        'id' => $post->ID,
                        'text' => cpt_get_post_title_with_parents($post->ID)
                    ];
                }
                wp_send_json_success($output);
            });

            add_action('wp_ajax_cpt_get_template_fields', function () {
                $nonce = isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'cpt-nonce') ? true : false;
                $post_type = isset($_POST['post-type']) ? $_POST['post-type'] : false;
                if (!$post_type || !$nonce) wp_send_json_error();
                $post_type_fields = cpt_get_fields_by_post_type($post_type);
                $list = '';
                if (!empty($post_type_fields)) {
                    $list .= '<div class="cpt-field" style="width: 100%;">' . sprintf(
                        '<div class="cpt-field-wrap"><div class="field-input">%s</div></div>',
                        __('Shortcodes of available fields and taxonomies:', 'custom-post-types')
                    ) . '</div>';
                }
                foreach ($post_type_fields as $key => $field) {
                    ob_start(); ?>
                    <div class="cpt-field" style="width: 100%;">
                        <div class="cpt-field-wrap">
                            <div class="field-input">
                                <input type="text" value="<?php echo htmlentities(sprintf('[cpt-field key="%s"]', $key)); ?>" title="<?php _e('Click to copy', 'custom-post-types'); ?>" class="copy" readonly>
                            </div>
                        </div>
                    </div>
                <?php
                    $list .= ob_get_clean();
                }
                if (!empty($post_type_fields)) {
                    $list .= '<div class="cpt-field" style="width: 100%;">' . sprintf(
                        '<div class="cpt-field-wrap"><div class="field-input">%s</div></div>',
                        __('Use these shortcodes to dynamically show values ​​based on the single post of the chosen post type.', 'custom-post-types')
                    ) . '</div>';
                }
                wp_send_json_success($list);
            });
        }

        public function created_cpts()
        {
            $created_post_types = get_posts(['posts_per_page' => -1, 'post_type' => $this->cpt_ui_name]);

            foreach ($created_post_types as $post_type) {

                $post_metas = get_post_meta($post_type->ID, '');

                if (!isset($post_metas['id']) || !isset($post_metas['singular']) || !isset($post_metas['plural'])) continue;

                $cpt_key = $post_metas['id'][0];
                $cpt_singular = $post_metas['singular'][0];
                $cpt_plural = $post_metas['plural'][0];
                $cpt_slug = isset($post_metas['slug'][0]) && !empty($post_metas['slug'][0]) ? sanitize_title($post_metas['slug'][0]) : sanitize_title($post_metas['plural'][0]);

                unset($post_metas['id']);
                unset($post_metas['singular']);
                unset($post_metas['plural']);
                unset($post_metas['slug']);

                foreach ($post_metas as $key => $value) {
                    $single_meta = get_post_meta($post_type->ID, $key, true);
                    if (substr($key, 0, 7) === "labels_") {
                        if (!empty($single_meta)) {
                            $post_metas['labels'][str_replace("labels_", "", $key)] = $single_meta;
                        }
                        unset($post_metas[$key]);
                    } elseif (substr($key, 0, 1) === "_" || empty($single_meta)) {
                        unset($post_metas[$key]);
                    } else {
                        $post_metas[$key] = in_array($single_meta, ['true', 'false']) ? ($single_meta === 'true') : $single_meta;
                    }
                }

                $registration_labels = apply_filters("cpt_register_labels_$cpt_key", isset($post_metas['labels']) && is_array($post_metas['labels']) ? $post_metas['labels'] : []);
                unset($post_metas['labels']);

                $post_metas['rewrite']['slug'] = $cpt_slug;

                $registration_args = apply_filters("cpt_register_args_$cpt_key", $post_metas);

                (new CPT\PostType)->register(
                    $cpt_key,
                    $cpt_singular,
                    $cpt_plural,
                    $registration_args,
                    $registration_labels
                );
            }

            $this->flush_rewrite_rules($created_post_types);
        }

        public function created_taxs()
        {
            $created_post_types = get_posts(['posts_per_page' => -1, 'post_type' => $this->cpt_ui_name . '_tax']);

            foreach ($created_post_types as $post_type) {

                $post_metas = get_post_meta($post_type->ID, '');

                if (!isset($post_metas['id']) || !isset($post_metas['singular']) || !isset($post_metas['plural'])) continue;

                $cpt_key = $post_metas['id'][0];
                $cpt_singular = $post_metas['singular'][0];
                $cpt_plural = $post_metas['plural'][0];
                $cpt_slug = isset($post_metas['slug'][0]) && !empty($post_metas['slug'][0]) ? sanitize_title($post_metas['slug'][0]) : sanitize_title($post_metas['plural'][0]);

                unset($post_metas['id']);
                unset($post_metas['singular']);
                unset($post_metas['plural']);
                unset($post_metas['slug']);

                foreach ($post_metas as $key => $value) {
                    $single_meta = get_post_meta($post_type->ID, $key, true);
                    if (substr($key, 0, 7) === "labels_") {
                        if (!empty($single_meta)) {
                            $post_metas['labels'][str_replace("labels_", "", $key)] = $single_meta;
                        }
                        unset($post_metas[$key]);
                    } elseif (substr($key, 0, 1) === "_" || empty($single_meta)) {
                        unset($post_metas[$key]);
                    } else {
                        $post_metas[$key] = in_array($single_meta, ['true', 'false']) ? ($single_meta === 'true') : $single_meta;
                    }
                }

                $post_types = isset($post_metas['supports']) ? $post_metas['supports'] : [];
                unset($post_metas['supports']);

                $registration_labels = apply_filters("cpt_register_tax_labels_$cpt_key", isset($post_metas['labels']) && is_array($post_metas['labels']) ? $post_metas['labels'] : []);
                unset($post_metas['labels']);

                $post_metas['rewrite']['slug'] = $cpt_slug;

                $registration_args = apply_filters("cpt_register_tax_args_$cpt_key", $post_metas);

                (new CPT\Taxonomy)->register(
                    $cpt_key,
                    $cpt_singular,
                    $cpt_plural,
                    $post_types,
                    $registration_args,
                    $registration_labels
                );
            }

            $this->flush_rewrite_rules($created_post_types);
        }

        public function created_fields_groups()
        {
            $created_fields_groups = get_posts(['posts_per_page' => -1, 'post_type' => $this->cpt_ui_name . '_field']);

            foreach ($created_fields_groups as $fields_group) {
                $position = !empty(get_post_meta($fields_group->ID, 'position', true)) ? get_post_meta($fields_group->ID, 'position', true) : 'normal';
                $order = get_post_meta($fields_group->ID, 'order', true);
                $supports = get_post_meta($fields_group->ID, 'supports', true);
                $admin_only = get_post_meta($fields_group->ID, 'admin_only', true);
                $fields = !empty(get_post_meta($fields_group->ID, 'fields', true)) ? array_map(
                    function ($field) {
                        $field['extra'] = isset($field['extra'][$field['type']]) ? $field['extra'][$field['type']] : [];
                        foreach ($field as $key => $value) {
                            if (substr($key, 0, 5) === "wrap_") {
                                if (!empty($value)) {
                                    $field['wrap'][str_replace("wrap_", "", $key)] = $value;
                                }
                                unset($field[$key]);
                            }
                        }
                        return $field;
                    },
                    get_post_meta($fields_group->ID, 'fields', true)
                ) : [];
                if (is_array($supports) && (($admin_only == 'true' && current_user_can('administrator')) || ($admin_only !== 'true' && current_user_can('edit_posts')))) {
                    foreach ($supports as $post_type) {
                        (new CPT\MetaBox())->add(
                            $post_type,
                            __($fields_group->post_title, 'custom-post-types'),
                            $fields,
                            $position
                        );
                    }
                }
            }
        }

        public function created_templates()
        {
            $created_templates = get_posts(['posts_per_page' => -1, 'post_type' => $this->cpt_ui_name . '_template']);

            add_filter('template_include', function ($load_template) use ($created_templates) {
                $template = new CPT\Template();
                $template_blank_path = $template->get_blank_template();
                $themes_supports_rules = apply_filters('cpt_themes_supports_rules', []);
                if (!$template_blank_path) {
                    if ($template->copy_from_current_theme($themes_supports_rules) || $template->copy_from_old_version())
                        $template_blank_path = $template->get_blank_template();
                }
                if (!$template_blank_path) return $load_template;

                global $post;
                $current_post_type = isset($post->post_type) ? $post->post_type : false;

                foreach ($created_templates as $created_template) {
                    $created_template_post_type = get_post_meta($created_template->ID, 'supports', true);
                    $created_template_wrap = get_post_meta($created_template->ID, 'wrap', true);
                    if (empty($created_template_post_type)) continue;
                    if ($created_template_wrap !== 'blank') continue;
                    if ($created_template_post_type == $current_post_type) return $template_blank_path;
                }

                return $load_template;
            }, 0);

            add_filter('the_content', [$this, 'filter_the_content'], $this->priority);
        }

        public function filter_the_content($content)
        {
            $created_templates = get_posts(['posts_per_page' => -1, 'post_type' => $this->cpt_ui_name . '_template']);
            global $post;
            $current_post_type = $post->post_type;

            foreach ($created_templates as $created_template) {
                $created_template_post_type = get_post_meta($created_template->ID, 'supports', true);
                if (empty($created_template_post_type)) continue;
                if ($created_template_post_type == $current_post_type) {
                    remove_filter('the_content', [$this, 'filter_the_content'], $this->priority);
                    $content = apply_filters('the_content', $created_template->post_content);
                    add_filter('the_content', [$this, 'filter_the_content'], $this->priority);
                };
            }

            return $content;
        }

        public function init_assets()
        {
            add_action('admin_enqueue_scripts', function () {
                wp_enqueue_media();
                wp_enqueue_editor();
                wp_enqueue_style($this->cpt_ui_name . '_css', plugins_url('assets/css/backend.css', __FILE__));
                wp_enqueue_script($this->cpt_ui_name . '_js', plugins_url('assets/js/backend.js', __FILE__), ['jquery', 'wp-i18n']);
                wp_add_inline_script($this->cpt_ui_name . '_js', "window.lodash = _.noConflict();");
                $vars = [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'ajax_nonce' => wp_create_nonce('cpt-nonce'),
                ];
                wp_localize_script($this->cpt_ui_name . '_js', 'cpt', $vars);
                wp_set_script_translations($this->cpt_ui_name . '_js', 'custom-post-types');
            });
            add_action('admin_footer', function () {
                echo '<div class="cpt-import-svg">';
                foreach (glob(CPT_PATH . "assets/*.svg") as $filename) {
                    echo file_get_contents($filename);
                }
                echo '</div>';
            });
        }

        public function action_links()
        {
            add_filter('plugin_action_links', function ($links, $file) {
                if ($file == 'custom-post-types/custom-post-types.php') {
                    $links[] = sprintf('<a href="%s" target="_blank"> %s </a>', CPT_PLUGIN_SUPPORT_URL, __('Support', 'custom-post-types'));
                }
                return $links;
            }, $this->priority, 2);
        }

        public function notices()
        {
            if (!is_admin()) return;
            $buttons = [
                [
                    'link' => CPT_PLUGIN_URL,
                    'label' => __('Get PRO version', 'custom-post-types'),
                    'target' => '_blank',
                    'cta' => true
                ],
                [
                    'link' => CPT_PLUGIN_REVIEW_URL,
                    'label' => __('Write a Review', 'custom-post-types'),
                    'target' => '_blank'
                ],
                [
                    'link' => CPT_PLUGIN_DONATE_URL,
                    'label' => __('Make a Donation', 'custom-post-types'),
                    'target' => '_blank'
                ],
            ];
            if ($this->is_pro_version_active()) {
                unset($buttons[0]);
            }
            (new CPT\Notice)->add(
                'welcome_notice',
                __('Thanks for using this plugin! Do you want to help us grow to add new features?', 'custom-post-types') . '<br><br>' . sprintf(__('The new version %s introduces a lot of new features and improves the core of the plugin.<br>For any problems you can download the previous version %s from the official page of the plugin from WordPress.org (Advanced View > Previous version).', 'custom-post-types'), '<u>' . CPT_VER . '</u>', '<u>2.1.19</u>'),
                'success',
                false,
                30,
                $buttons
            );


            if (cpt_is_current_screen($this->cpt_ui_name . '_template')) {
                $template = new CPT\Template();
                $template_blank_path = $template->get_blank_template();
                $themes_supports_rules = apply_filters('cpt_themes_supports_rules', []);
                if (!$template_blank_path) {
                    if ($template->copy_from_current_theme($themes_supports_rules) || $template->copy_from_old_version())
                        $template_blank_path = $template->get_blank_template();
                }
                if (!$template_blank_path) {
                    $buttons = [
                        [
                            'link' => CPT_PLUGIN_SUPPORT_URL,
                            'label' => __('Ask for integration', 'custom-post-types'),
                            'target' => '_blank'
                        ],
                        [
                            'link' => 'https://developer.wordpress.org/themes/template-files-section/post-template-files/',
                            'label' => __('WordPress documentation', 'custom-post-types'),
                            'target' => '_blank'
                        ]
                    ];
                    (new CPT\Notice)->add('theme_not_supported', __('The "Blank" wrap type cannot be used for custom templates.<br>The current theme folder does not contain the "single.php" file or the file does not contain the classic loop (<em>while (have_posts()) {...}</em>).', 'custom-post-types'), 'error', true, false, $buttons);
                }
            }
        }

        public function shortcodes()
        {
            if (!is_admin() && !cpt_is_rest()) {
                global $post;
                add_shortcode('custom-field', function ($atts) { // 2.x Compatibility
                    $a = shortcode_atts([
                        'id' => false,
                    ], $atts);
                    return do_shortcode(sprintf('[cpt-field key="%s"]', $a['id']));
                });
                add_shortcode('cpt-field', function ($atts) {
                    $a = shortcode_atts([
                        'key' => false,
                        'post-id' => false
                    ], $atts);
                    $errors = false;
                    if (!$a['key']) {
                        $errors[] = __('Missing field "key".', 'custom-post-types');
                    }
                    if ($errors) {
                        return current_user_can('edit_posts') ? "<pre>" . implode("</pre><pre>", $errors) . "</pre>" : '';
                    }
                    return cpt_get_field($a['key'], $a['post-id']);
                });
                add_shortcode('custom-tax', function ($atts) { // 2.x Compatibility
                    $a = shortcode_atts([
                        'id' => false,
                    ], $atts);
                    return do_shortcode(sprintf('[cpt-terms key="%s"]', $a['id']));
                });
                add_shortcode('cpt-terms', function ($atts) use ($post) {
                    $a = shortcode_atts([
                        'key' => false,
                        'post-id' => false
                    ], $atts);
                    $errors = false;
                    if (!$a['key']) {
                        $errors[] = __('Missing field "key".', 'custom-post-types');
                    }
                    if ($errors) {
                        return current_user_can('edit_posts') ? "<pre>" . implode("</pre><pre>", $errors) . "</pre>" : '';
                    }
                    $post = $a['post-id'] && get_post($a['post-id']) ? get_post($a['post-id']) : $post;
                    $get_terms = get_the_terms($post->ID, $a['key']);
                    $terms = [];
                    foreach ($get_terms as $term) {
                        $terms[] = sprintf('<a href="%1$s" title="%2$s" aria-title="%2$s">%2$s</a>', get_term_link($term->term_id), $term->name);
                    }
                    return implode(', ', $terms);
                });
            }
        }

        public function settings_page()
        {
            add_action('admin_menu', function () {
                remove_submenu_page('edit.php?post_type=' . $this->cpt_ui_name, 'post-new.php?post_type=' . $this->cpt_ui_name);
                add_submenu_page('edit.php?post_type=' . $this->cpt_ui_name, __('Tools & Infos &lsaquo; Extend / Manage', 'custom-post-types'), __('Tools & Infos', 'custom-post-types'), 'administrator', 'tools', function () {
                    $main_page_url = menu_page_url('tools', false);
                    $export_page_url = $main_page_url . '&action=export';
                    $import_page_url = $main_page_url . '&action=import';
                    $db_update_page_url = $main_page_url . '&action=database-update&nonce=' . wp_create_nonce('cpt-nonce');
                    $request_page = isset($_GET['action']) && !empty($_GET['action']) && in_array($_GET['action'], ['export', 'import']) ? $_GET['action'] : 'main';
                    $is_current_page = function ($current) use ($request_page) {
                        return $current == $request_page;
                    };

                ?>
                    <div class="cpt-tools-page">
                        <h1><?php _e('Custom post types &rsaquo; Tools & Infos', 'custom-post-types'); ?></h1>
                        <nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
                            <?php
                            printf(
                                '<a href="%s" class="nav-tab %s">%s</a>',
                                $main_page_url,
                                $is_current_page('main') ? 'nav-tab-active' : '',
                                __('Infos', 'custom-post-types')
                            );
                            printf(
                                '<a href="%s" class="nav-tab %s">%s</a>',
                                $export_page_url,
                                $is_current_page('export') ? 'nav-tab-active' : '',
                                __('Export', 'custom-post-types')
                            );
                            printf(
                                '<a href="%s" class="nav-tab %s">%s</a>',
                                $import_page_url,
                                $is_current_page('import') ? 'nav-tab-active' : '',
                                __('Import', 'custom-post-types')
                            );
                            printf(
                                '<a href="%s" class="nav-tab" target="_blank">%s <span class="dashicons dashicons-external" style="text-decoration: none;"></span></a>',
                                CPT_PLUGIN_DOC_URL,
                                __('Documentation', 'custom-post-types')
                            );
                            ?>
                        </nav>
                        <div class="cpt-tools-page-content page-<?php echo $request_page; ?>">
                            <?php
                            switch ($request_page) {
                                case 'import':
                                    echo '<p>' . __('This tool allows you to <u>import</u> all plugin settings (post types, taxonomies, field groups and templates).', 'custom-post-types') . '</p>';
                                    if (!$this->is_pro_version_active()) {
                                        echo $this->get_pro_banner();
                                    } else {
                                        do_action('cpt_import_page');
                                    }
                                    break;
                                case 'export':
                                    echo '<p>' . __('This tool allows you to <u>export</u> all plugin settings (post types, taxonomies, field groups and templates).', 'custom-post-types') . '</p>';
                                    if (!$this->is_pro_version_active()) {
                                        echo $this->get_pro_banner();
                                    } else {
                                        do_action('cpt_export_page');
                                    }
                                    break;
                                default:
                            ?>
                                    <p>
                                        <?php printf(__('This plugin was created and designed entirely by <strong>Andrea De Giovine</strong>, an <a href="%s" title="italian freelance developer">Italian freelance developer <span class="dashicons dashicons-external" style="text-decoration: none;"></span></a> with over 10 years of experience in developing WordPress components (themes and plugins).', 'custom-post-types'), CPT_PLUGIN_DEV_URL); ?>
                                    </p>
                                    <p>
                                        <?php _e('The purpose of the plugin is to <u>extend the features of the CMS</u> by adding custom content types without writing code or knowledge of development languages.', 'custom-post-types'); ?>
                                    </p>
                                    <p>
                                        <?php _e('This plugin is <strong>FREE</strong> and the developer guarantees frequent updates (for security and compatibility), if this plugin is useful <u>please support the development</u>.', 'custom-post-types'); ?>
                                    </p>
                                    <?php do_action('cpt_license_key_form'); ?>
                                    <div class="cpt-container">
                                        <div class="cpt-row">
                                            <div class="cpt-col-3">
                                                <h2><?php _e('Support the project', 'custom-post-types'); ?></h2>
                                                <?php
                                                if (!$this->is_pro_version_active()) {
                                                    printf(
                                                        '<p><a href="%s" class="button button-primary button-hero" target="_blank">%s</a></p>',
                                                        CPT_PLUGIN_URL,
                                                        __('Get PRO version', 'custom-post-types')
                                                    );
                                                }
                                                printf(
                                                    '<p><a href="%s" class="button button-primary" target="_blank">%s</a></p>',
                                                    CPT_PLUGIN_DONATE_URL,
                                                    __('Make a Donation', 'custom-post-types')
                                                );
                                                printf(
                                                    '<p><a href="%s" class="button button-primary" target="_blank">%s</a></p>',
                                                    CPT_PLUGIN_REVIEW_URL,
                                                    __('Write a Review', 'custom-post-types')
                                                );
                                                ?>
                                            </div>
                                            <div class="cpt-col-3">
                                                <h2><?php _e('Other infos', 'custom-post-types'); ?></h2>
                                                <?php
                                                printf(
                                                    '<p><a href="%s" class="button button-secondary" target="_blank">%s</a></p>',
                                                    CPT_PLUGIN_WP_URL,
                                                    __('WordPress.org Plugin Page', 'custom-post-types')
                                                );
                                                printf(
                                                    '<p><a href="%s" class="button button-secondary" target="_blank">%s</a></p>',
                                                    CPT_PLUGIN_SUPPORT_URL,
                                                    __('Official Support Page', 'custom-post-types')
                                                );
                                                printf(
                                                    '<p><a href="%s" class="button button-secondary" target="_blank">%s</a></p>',
                                                    CPT_PLUGIN_DOC_URL,
                                                    __('Plugin Documentation', 'custom-post-types')
                                                );
                                                ?>
                                            </div>
                                            <div class="cpt-col-3">
                                                <h2><?php _e('Tools', 'custom-post-types'); ?></h2>
                                                <?php
                                                printf(
                                                    '<p><a href="%s" class="button button-secondary">%s</a></p>',
                                                    $export_page_url,
                                                    __('Export settings', 'custom-post-types')
                                                );
                                                printf(
                                                    '<p><a href="%s" class="button button-secondary">%s</a></p>',
                                                    $import_page_url,
                                                    __('Import settings', 'custom-post-types')
                                                );
                                                // printf(
                                                //     '<p><a href="%s" class="button button-secondary">%s</a></p>',
                                                //     $db_update_page_url,
                                                //     __('Redo database update (2.x -> 3.x)', 'custom-post-types')
                                                // );
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                    break;
                            }
                            ?>
                        </div>
                    </div>
<?php
                });
            });
        }

        public function get_pro_banner()
        {
            $output = '<p><strong>' . __('This feature is included in the <u>PRO version</u> only.', 'custom-post-types') . '</strong></p>';
            $output .= sprintf(
                '<p><a href="%s" class="button button-primary button-hero" target="_blank">%s</a></p>',
                CPT_PLUGIN_URL,
                __('Get PRO version', 'custom-post-types')
            );
            return '<div class="cpt-pro-banner">' . $output . '</div>';
        }

        public function is_pro_version_active()
        {
            $return = false;
            $pro_version = in_array('custom-post-types-pro/custom-post-types-pro.php', apply_filters('active_plugins', get_option('active_plugins')));
            if ($pro_version) {
                $return = true;
            }
            return $return;
        }

        public function flush_rewrite_rules($posts = [])
        {
            $ids = [];
            foreach ($posts as $post) {
                if ($post->ID) $ids[] = $post->ID;
            }
            if (!empty($ids)) {
                $registered_cpt_ids = get_option('custom_post_types_registered_cpt_ids', []);
                $cpt_ids_already_registered = !array_diff($ids, $registered_cpt_ids);
                if (empty($registered_cpt_ids) || !$cpt_ids_already_registered) {
                    $new_registered_cpt_ids = array_merge($registered_cpt_ids, $ids);
                    update_option('custom_post_types_registered_cpt_ids', array_unique($new_registered_cpt_ids));
                    flush_rewrite_rules();
                }
            }
        }
    }
    new totalpress_custom_post_types();
}
