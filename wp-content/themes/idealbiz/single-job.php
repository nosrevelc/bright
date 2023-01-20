<?php 
// Template Name: SingleJobPage
get_header(); ?>

<?php

 if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $meta = get_post_meta( $post->ID, 'post_fields', true ); 
    ?>



<?php endwhile; endif; ?>

<section class="single-job page-jobs">
    <div class="container text-center m-t-30">
        <h1><?php the_title(); ?></h1>
    </div>


    <div class="container d-flex flex-row flex-wrap justify-content-around m-b-30">
        <div class="col-md-12">
            <div class="generic-form">
                <div class="acf-form">
                    <div class="job-bm-job-submit">
                        <div class="row">
                        <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>



</section>

<?php get_footer(); ?>