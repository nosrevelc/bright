<?php
$is_certified = get_field('listing_certification_status') == 'certification_finished';
$post_id = get_the_ID();
$broadcasted_site_id = false;

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
<a <?php 
    if(isset($_POST['allsites'])){
        if ($_POST['allsites']) {
            echo 'target="_blank" ';
        }
    }
     ?> href="<?php echo the_permalink(); ?>" class="<?php if (has_term('highlight', 'boost')) {
                                                            echo 'highlight';
                                                        } ?> 
<?php if (!get_user_meta(get_current_user_id(), 'read_post_' . $post_id, false)) {
    echo 'unread-post';
} ?> listing position-relative d-flex flex-column m-r-25 m-t-25 black--color white--background dropshadow font-weight-medium">
    <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
        <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
    <?php endif;
    ?>
    <div class="status-badge stroke dropshadow <?php echo get_post_status(); ?>"><?php echo getPostStatus(get_post_status()); ?></div>
    <div class="category">
        <span class="star"><i class="icofont-star"></i></span>
        <span><?php echo get_field('category')->name; ?></span>
    </div>
    <div class="image-container w-100">
        <img class="w-100 h-100" src="<?php echo $featured_image; ?>">
        <div class="location p-x-10 p-y-10 font-weight-bold">
            <i class="icon-local"></i>
            <span class="text-uppercase"><?php echo esc_html(get_field('location')->name);  ?></span>
        </div>
    </div>
    <div class="listing-info h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">
        <span class="title font-weight-bold"><?php the_title(); ?></span>
        <span class="price m-t-30"><?php echo IDB_Ipost_Data::get_listing_value($post_id, 'price_type', $broadcasted_site_id); ?></span>
    </div>
</a>