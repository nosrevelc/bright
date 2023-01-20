<?php // Template Name: Checkout

if(!is_user_logged_in()){
    
    wp_redirect(home_url());
}

get_header(); 


?>
<?php

 if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $meta = get_post_meta( $post->ID, 'post_fields', true ); 
    ?>

<?php endwhile; endif; ?>

<section class="page">
    <div class="container medium-width text-center">
        <h1 class="page-title m-t-15 m-b-45"><?php the_title(); ?></h1>
         <?php the_content(); ?>
    </div>
    <?php whiteBackground(); ?>
</section>

<?php
/* echo '<pre>';
var_dump($meta);
echo '</pre>'; */
?>



<?php get_footer(); ?>