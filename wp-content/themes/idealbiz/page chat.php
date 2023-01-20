<?php 
// Template Name: Chat
get_header(); ?>

<?php

 if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $meta = get_post_meta( $post->ID, 'post_fields', true ); 
    ?>

<?php endwhile; endif; ?>

<section class="page">
    <div class="container text-center m-t-30">
                <a class="go-search font-weight-medium d-flex align-items-center" href="javascript: history.go(-1);">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back to your seach', 'idealbiz'); ?></span>
                </a>
            </div>
            <div class="w-100 text-center">
                <h1><?php the_title(); ?></h1>
            </div>
            <div class="chat container position-relative p-b-15 dropshadow d-flex flex-column black--color white--background  m-t-30"">

                <div class="col-md-12">
                    <div class="d-flex flex-column flex-wrap justify-content-start container m-t-25">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
    </div>             
</section>

<?php get_footer(); ?>