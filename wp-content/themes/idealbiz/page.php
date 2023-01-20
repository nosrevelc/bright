<?php get_header(); ?>

<?php

 if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $meta = get_post_meta( $post->ID, 'post_fields', true ); 
    ?>



<?php endwhile; endif; ?>

<section class="page">
    <div class="container text-center m-t-30">
        <h1><?php the_title(); ?></h1>
    </div>

    <?php
    $showback = 0;
    $parent_id = wp_get_post_parent_id(get_the_ID());
    if(get_page_template_slug($parent_id)=='page-jobs.php'){
        $showback = 1;
    }
    if(get_page_template_slug($parent_id)=='page-resumes.php'){
        $showback = 1;
    }
    if($showback ==1){
    ?>
    <div class="container m-b-15">
        <a class="go-search font-weight-medium d-flex align-items-center" href="javascript: history.go(-1);">
            <i class="icon-dropdown"></i>
            <span class="font-weight-bold m-l-5"><?php _e('Go back', 'idealbiz'); ?></span>
        </a>
    </div>
    <?php
    }
    ?>

    <div class="d-flex flex-column flex-wrap justify-content-start container m-t-25">
        <?php the_content(); ?>
    </div>

</section>

<?php get_footer(); ?>