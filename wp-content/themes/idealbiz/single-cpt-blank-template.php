<?php
get_header();


if (have_posts()) : custom_post_types_get_custom_template();
endif; ?>

<?php get_footer(); ?>