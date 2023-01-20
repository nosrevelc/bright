<?php

/* if (!is_user_logged_in()) {
    $redirect = ( strpos( $_SERVER['REQUEST_URI'], '/options.php' ) && wp_get_referer() ) ? wp_get_referer() : set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    wp_redirect(get_site_url().'/'.pll_languages_list()[0].'/login-register/?redirect_to='.$redirect );
} */




$is_certified = get_field('listing_certification_status') == 'certification_finished';
$post_id = get_the_ID();
$broadcasted_site_id = false;

$cl_stamp = get_field('stamp');
$img_ico = get_field('icon_stamp');
$cl_icon_stamp = $img_ico['url'];


if (get_field('type_of_listing')->name != Null){
    $cl_type_listing = get_field('type_of_listing')->name;
}else{
    $cl_type_listing = __('str_Listing','idealbiz');
}

foreach ($countries as $k => $country) {
    if (in_array($post_id, $country)) {
        $broadcasted_site_id = $k;
        break;
    }
}

if($broadcasted_site_id) {
    $parent_id = get_broadcast_parent_id($post_id);
    switch_to_blog($broadcasted_site_id);
    $featured_image = get_field('featured_image', $parent_id)['sizes']['medium'];
    restore_current_blog();
}
else {
    $featured_image = get_field('featured_image', $post_id)['sizes']['medium'];
}


?>
<!-- pointer login lrm-register -->
<!-- <a class="login-register p-y-6 pointer login lrm-register"><?php _e('Register', 'idealbiz') ?></a> -->
<a <?php 
    if(isset($_POST['allsites'])){
        if ($_POST['allsites']) {
            echo 'target="_blank" ';
        }
    }
     ?> href="<?php echo the_permalink(); ?>" class="  <?php if (has_term('highlight', 'boost')) {
                                                            echo 'highlight';
                                                        } ?> 

<?php if($_SESSION["login_listing"] == 1){?>
 login lrm-register
 <?php } ?>







<?php if (!get_user_meta(get_current_user_id(), 'read_post_' . $post_id, false)) {
    echo 'unread-post';
} ?> listing position-relative d-flex flex-column m-r-25 m-t-25 black--color white--background dropshadow font-weight-medium">

    <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
        <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
    <?php endif;
    ?>
    <div class="status-badge stroke dropshadow <?php echo get_post_status(); ?>"><?php echo getPostStatus(get_post_status()); ?></div>
    <div class="category">
    
        <sapn class="dashicons dashicons-megaphone"></sapn>
        <span class="type_listing"><?php echo $cl_type_listing ; ?></span>
    </div>
    <div class="image-container w-100">
    <div class="location p-x-10 p-y-27 font-weight-bold">
            <i class="icon-local"></i>
            <span class="text-uppercase cl_local"><?php echo esc_html(get_field('location')->name);  ?></span>            
        </div>
    <span class="star"><i class="icofont-star"></i></span>
    <span class="cat_name"><?php echo get_field('category')->name; ?></span>
    
        <img class="w-100 h-100" src="<?php echo $featured_image; ?>">

    </div>
    <div class="listing-info h-100 d-flex justify-content-between flex-column p-y-10 p-x-17 d-none">
        
        <span class="title font-weight-bold"><?php the_title(); ?></span>
        <?php if(IDB_Listing_Data::get_listing_value($post_id, 'price_type', $broadcasted_site_id) != 'No Traslate this String'){?>
        <span class="price m-t-30"><?php echo IDB_Listing_Data::get_listing_value($post_id, 'price_type', $broadcasted_site_id); ?></span>
        <?php } ?>
    </div>


    <?php if($cl_stamp ){ ?>
    <img class="stamp" src="<?php echo $cl_icon_stamp; ?>">
    <?php }?>
</a>

<style>

.stamp{
    position: absolute;
    display: block;
    bottom: .1rem;
    right: .1rem;
    width: 3rem;
    height: 3rem;
    background-size: contain;
    background-position: 50%;
    background-repeat: no-repeat;
    z-index: 10;
}

.type_listing{
    font-family: var(--font-default), sans-serif !important;
    font-weight: var(--font-weight) !important;
    top:5px;
    margin: 0 auto;
    font-weight: 500;
    padding: 5px 5px;
    margin-left: -5px;
    font-weight: 800;
    font-size: 1.7em!important;
}
.cl_local{
    position: absolute;
    white-space: nowrap;
    top:25px!important;
    font-weight: 600;
}
.cat_name, .star{
    position: absolute;
    color:#fff!important;
    font-weight: 800;
}
.cat_name{
    padding-left: 23px;
}
.star{
    left: 10px;
}
.dashicons{
    color:rgba(19,151,225,.5);
    padding-top:7px;
}
.font-weight-bold{
    font-weight: lighter !important;
}



</style>