<?php

namespace CPT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class Template
{
    public function __construct()
    {
        //
    }
    public function get_blank_template_path()
    {
        $theme_dir = get_stylesheet_directory();
        return $theme_dir . '/single-cpt-blank-template.php';
    }
    public function get_blank_template()
    {
        $cpt_template = $this->get_blank_template_path();
        return file_exists($cpt_template) ? $cpt_template : false;
    }
    public function get_upload_template_path()
    { // 2.x Compatibility
        $current_theme = wp_get_theme();
        $current_theme_name = $current_theme->get(is_child_theme() ? 'Template' : 'TextDomain');
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        return $upload_dir . '/custom-templates/' . $current_theme_name . '-single.php';
    }
    public function get_upload_template()
    { // 2.x Compatibility
        $cpt_template = $this->get_upload_template_path();
        return file_exists($cpt_template) ? $cpt_template : false;
    }
    public function get_theme_single_path($args = [])
    {
        $current_theme = wp_get_theme();
        $current_theme_name = $current_theme->get(is_child_theme() ? 'Template' : 'TextDomain');
        $current_theme_path = is_child_theme() ? get_template_directory() : get_stylesheet_directory();
        return $current_theme_path . '/' . (isset($args[$current_theme_name]['file-name']) ? $args[$current_theme_name]['file-name'] : 'single.php');
    }
    public function copy_from_old_version()
    {
        $from_upload = $this->get_upload_template();
        $to_blank = $this->get_blank_template();
        if (!$from_upload || $to_blank) return false;
        if (!copy($from_upload, $this->get_blank_template_path())) return false;
        return true;
    }
    public function copy_from_current_theme($args = [])
    {
        if ($this->get_blank_template()) return false;
        $single_post_template_path = $this->get_theme_single_path($args);
        if (!file_exists($single_post_template_path)) return false;
        $single_template_content = file_get_contents($single_post_template_path);
        $current_theme = wp_get_theme();
        $current_theme_name = $current_theme->get(is_child_theme() ? 'Template' : 'TextDomain');
        $replace_func = isset($args[$current_theme_name]['replace-callback']) ? $args[$current_theme_name]['replace-callback'] : function ($content) {
            $default_regexs = ["#while(.+)endwhile;#s", "#while ((.+)) {([^}]+)#s"];
            foreach ($default_regexs as $regex) {
                if (preg_match($regex, $content, $matches)) {
                    return preg_replace($regex, "custom_post_types_get_custom_template();", $content);
                    break;
                }
            }
            return false;
        };
        if (!is_callable($replace_func) || !$replace_func($single_template_content)) return false;
        $new_file_content = $replace_func($single_template_content);
        $new_file = fopen($this->get_blank_template_path(), "w") or die("Unable to create the template file! Check permission.");
        fwrite($new_file, $new_file_content);
        fclose($new_file);
        return true;
    }
    public function update_old_post_meta($meta = [], $post_id)
    {
        if (!is_array($meta) || empty($meta) || !get_post($post_id)) return;

        if (isset($meta['field_used_by'])) update_post_meta($post_id, 'supports', $meta['field_used_by']);

        return;
    }
}
