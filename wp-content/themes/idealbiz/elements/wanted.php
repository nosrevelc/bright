<?php
$is_certified = get_field('listing_certification_status') == 'certification_finished';
?>

<a href="<?php echo the_permalink(); ?>" class="<?php if (!get_user_meta(get_current_user_id(), 'read_post_' . get_the_ID(), false)) {
                                                    echo 'unread-post';
                                                } ?> listing position-relative d-flex flex-column m-r-25 m-t-25 black--color white--background dropshadow font-weight-medium">
    <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
        <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
    <?php endif; ?>
    <div class="status-badge stroke dropshadow <?php echo get_post_status(); ?>"><?php echo getPostStatus(get_post_status()); ?></div>
    <div class="category">
        <span><?php echo get_field('category')->name; ?></span>
    </div>
    <div class="image-container w-100">
        <img class="w-100 h-100" src="<?php echo get_field('featured_image')['sizes']['medium']; ?>">
        <div class="location p-x-10 p-y-10 font-weight-bold">
            <i class="icon-local"></i>
            <span class="text-uppercase"><?php echo esc_html(get_field('location')->name);  ?></span>
        </div>
    </div>
    <div class="listing-info h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">
        <span class="title font-weight-bold"><?php the_title(); ?></span>
    </div>
</a>