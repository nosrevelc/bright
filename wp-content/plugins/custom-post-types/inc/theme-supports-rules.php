<?php

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

add_filter('cpt_themes_supports_rules', function ($supports_rules = []) {
    $supports_rules = [
        'twentytwenty' => [
            'file-name' => 'singular.php',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part( 'template-parts/content', get_post_type() )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'secretum' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part( 'template-parts/post/content', get_post_format() )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'qaengine' => [
            'file-name' => 'framework/templates/single.php',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace('echo $the_content', 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'enfold' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part( 'includes/loop', 'index' )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'boss' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part( 'content', get_post_format() )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'flatsome' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part( 'template-parts/posts/layout', get_theme_mod('blog_post_layout','right-sidebar') )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'hueman' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("hu_get_content( 'tmpl/single-tmpl')", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'aardvark' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part( 'lib/sections/single/post-content' )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'kadence' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("do_action( 'kadence_single' )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'ascend' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part('templates/content', 'single')", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'virtue' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part( 'templates/content', 'single' )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'pinnacle' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part('templates/content', 'single')", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'blocksy' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part( 'template-parts/content', get_post_type() )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'astra' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("astra_content_loop()", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'envo-magazine' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $content_to_replace = str_replace("get_template_part( 'content', 'single' )", 'custom_post_types_get_custom_template()', $content_to_replace);
                return $content_to_replace;
            },
        ],
        'nisarg' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $regex = "#while(.+)endwhile;#s";
                if (preg_match($regex, $content_to_replace, $matches)) {
                    return preg_replace($regex, "custom_post_types_get_custom_template(); echo '</main>';", $content_to_replace);
                }
                return $content_to_replace;
            },
        ],
        'pointfinder' => [
            'file-name' => '',
            'replace-callback' => function ($content_to_replace) {
                $regex = '#\$post_type(.+)get_footer()#s';
                if (preg_match($regex, $content_to_replace, $matches)) {
                    return preg_replace($regex, "echo '<section role=\"main\"><div class=\"pf-container\"><div class=\"pf-row\"><div class=\"col-lg-12\">'; custom_post_types_get_custom_template(); echo '</div></div></div></section>';", $content_to_replace);
                }
                return $content_to_replace;
            },
        ],
        '__x__' => [
            'file-name' => 'framework/views/ethos/wp-single.php',
            'replace-callback' => function ($content_to_replace) {
                $regex = "#while(.+)endwhile;#s";
                if (preg_match($regex, $content_to_replace, $matches)) {
                    return preg_replace($regex, "custom_post_types_get_custom_template();", $content_to_replace);
                }
                return $content_to_replace;
            },
        ],
        'oceanwp' => [
            'file-name' => 'singular.php',
            'replace-callback' => function ($content_to_replace) {
                $regex = "#while(.+)endwhile;#s";
                if (preg_match($regex, $content_to_replace, $matches)) {
                    return preg_replace($regex, "custom_post_types_get_custom_template();", $content_to_replace);
                }
                return $content_to_replace;
            },
        ],
    ];
    return $supports_rules;
});
