<?php /*

  This file is part of a child theme called idealbiz-child.
  Functions in this file will be loaded before the parent theme's functions.
  For more information, please read
  https://developer.wordpress.org/themes/advanced-topics/child-themes/

*/

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles()
{
  $parenthandle = 'parent-style';
  $theme        = wp_get_theme();

  wp_enqueue_style(
    'bootstrap-css',
    'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'
  );

  wp_enqueue_style(
    $parenthandle,
    get_template_directory_uri() . '/assets/css/main.css',
    array(),
    $theme->parent()->get('Version')
  );

  wp_enqueue_style(
    'child-style',
    get_stylesheet_uri(),
    array($parenthandle),
    $theme->get('Version')
  );
}

/*  Add your own functions below this line.
    ======================================== */
