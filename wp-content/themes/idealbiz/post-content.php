<?php // Template Name: Post Content


if(!is_user_logged_in()){
    wp_redirect(home_url());
}

get_header();
?>

<?php
$cl_shortcode_dashboard = get_field('dashboard_user_frontend');
$cl_shortcode_form_input = get_field('form_input_content');
$cl_shortcode_my_post = get_field('short_code_posts');
?>
 
    <div class="container text-center m-b-30" style="width:700px;">
        <h1 class="m-h2 text-xs-left" Style= "text-align:center;">
        
            <?php   echo the_title(); ?>

        </h1>
        
    </div>
<div class="container m-t-20 m-b-20">
<?php the_content(); ?> 
</div> 
<hr>  
    <div class="container m-t-20 m-b-20">
    <?php echo do_shortcode('[wpuf_account]');?>
    </div>   
<hr>  
<div class="container m-t-20 m-b-20">  
        <?php echo do_shortcode('[wpuf_dashboard post_type="post"]');?>
</div> 
<div class="container m-t-20 m-b-20">
    <h2 class="m-h2 text-xs-left" Style= "text-align:center;">    

        <?php   echo _e('Add New Post'); ?>

    </h2>
    <?php echo do_shortcode($cl_shortcode_form_input);?>
    </div>   
 
<?php get_footer(); ?>

<style>
    .attachment-thumbnail{
        width: 70px !important;
        height: 70px !important;
    }

</style>