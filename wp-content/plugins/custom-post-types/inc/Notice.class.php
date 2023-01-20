<?php

namespace CPT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class Notice
{
    public function __construct()
    {
        //
    }
    public function dismissed_notices()
    {
        return get_option('custom_post_types_dismissed_notices', []);
    }
    public function dismiss_notice($id, int $days = 2)
    {
        $dismissed_notices = $this->dismissed_notices();
        $dismissed_notices[$id] = strtotime("+$days day", time());
        update_option('custom_post_types_dismissed_notices', $dismissed_notices);
    }
    public function is_dismissed($id)
    {
        $dismissed_notices = $this->dismissed_notices();
        return isset($dismissed_notices[$id]) && time() < intval($dismissed_notices[$id]);
    }
    public function current_url()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    public function add($key = false, $message = '', $type = 'warning', $dismissible = false, $close_btn = 2, $buttons = [])
    {
        if (!$key) return;
        $key = sanitize_title($key);
        if ($this->is_dismissed($key) && $close_btn) return;
        $type = in_array($type, ['error', 'warning', 'success', 'info']) ? $type : 'info';
        $class = "notice notice-$type cpt-notice" . ($dismissible ? ' is-dismissible' : '');
        $notice_buttons = [];
        foreach ($buttons as $button) {
            $is_cta = isset($button['cta']) ? $button['cta'] : false;
            $notice_buttons[] = sprintf(
                '<a href="%s"%s target="%s">%s%s</a>',
                isset($button['link']) ? $button['link'] : '',
                $is_cta ? ' class="button button-secondary"' : '',
                isset($button['target']) ? $button['target'] : '_self',
                isset($button['label']) ? $button['label'] : '',
                isset($button['target']) && $button['target'] == '_blank' && !$is_cta ? ' <span class="dashicons dashicons-external"></span>' : ''
            );
        }
        if ($close_btn) {
            $notice_buttons[] = sprintf(
                '<a href="%s">%s</a>',
                add_query_arg(['cpt-action' => 'dismiss', 'notice' => $key, 'days' => intval($close_btn), 'nonce' => wp_create_nonce('cpt-nonce')], $this->current_url()),
                sprintf(__('Dismiss notice for %s days', 'custom-post-types'), intval($close_btn))
            );
        }

        $title = __('<strong>Custom post types</strong> notice:', 'custom-post-types');

        add_action('admin_notices', function () use ($class, $message, $notice_buttons, $title) {
            printf(
                '<div class="%s"><p class="title">%s</p><p class="message">%s</p>%s</div>',
                $class,
                $title,
                $message,
                !empty($notice_buttons) ? '<p class="actions">' . implode(' - ', $notice_buttons) . '</p>' : ''
            );
        });
        if (!$close_btn) return;
        add_action('admin_init', function () use ($key) {
            $valid_notice = isset($_GET['notice']) && $_GET['notice'] == $key ? true : false;
            if (!$valid_notice || !\wp_verify_nonce($_GET['nonce'], 'cpt-nonce')) return;
            $days = isset($_GET['days']) ? $_GET['days'] : false;
            if (isset($_GET['cpt-action']) && $_GET['cpt-action'] == 'dismiss') {
                $this->dismiss_notice($key, intval($days));
                $redirect_url = remove_query_arg(['cpt-action', 'notice', 'days', 'nonce'], $this->current_url());
                wp_redirect($redirect_url);
                exit;
            }
        });
    }
}
