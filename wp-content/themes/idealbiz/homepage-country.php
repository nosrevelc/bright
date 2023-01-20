<?php // Template Name: Homepage Country

get_header();

$business_background_image = get_field('business_option_image');
$countries_background_image = get_field('countries_image');
$welcome_message = get_field('welcome_message');

?>



<section class="homepage <?php if ($business_background_image) echo 'background-image'; ?>" style="<?php if ($business_background_image) { ?>background-image: url('<?php echo $business_background_image['url']; ?>'); <?php } ?>">
    <div class="container text-center">
        <h1 class="m-h2 text-xs-left">
            <?php _e('What is the ideal option for your business?', 'idealbiz'); ?>
            <div class="d-inline-block hidden-desktop"><?php infoModal(get_field('business_option_description')); ?></div>
        </h1>
        <h3 class="hidden-mobile"><?php echo get_field('business_option_description'); ?></h3>
    </div>

    <div class="site-blocks d-flex flex-row flex-wrap justify-content-start container m-t-25">
        <?php
        $business_options = get_field('business_options');
        $bo = 0;
        foreach ($business_options as $option) {
            $color_box = 'none'; 
            if ($option['icon_color'] != '') {
                $color_box = $option['icon_color'];
            }
            if ($option['external_link'] == '' && $option['button_link'] == '') { } else {
                ?>
                <div class="b-opts">
                    <div class="b-opts-inner m-y-5">
                        <a href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>" data-sbo="<?php echo $bo; ?>" style="background-color: <?php echo $color_box; ?> ; border: 1px solid rgba(219, 219, 219, 0.1)" class="d-block w-200px h-200px block p-t-5 m-x-5 b-r-5 m-appicon <?php if ($option['required_login']) echo 'lrm-login'; ?>">
                            <?php if ($option['icon'] == '') { ?>
                                <img src="<?php echo $option['image']['url']; ?>" class="bo-svg" alt="<?php echo $option['title_desktop']; ?>" title="<?php echo $option['title_desktop']; ?>" />
                            <?php } else { ?>
                                <i class="white--color m-l-35 <?php echo $option['icon']; ?>"></i>
                            <?php } ?>
                            <h2 class="m-l-35 white--color d-none d-md-block first-line center-inblock"><?php echo str_replace_first(' ', '<br/>', $option['title_desktop']); ?></h2>
                        </a>
                        <div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke hidden-mobile d-none">
                            <div class="b-opts-d-open-inner white--background">
                                <div class="b-opts-body">
                                    <h3 class="font-weight-semi-bold m-b-20"><?php echo $option['title_desktop']; ?></h3>
                                    <p><?php echo $option['text']; ?></p>
                                    <a class="btn btn-blue m-t-5 white--background h-36px l-h-18 <?php if ($option['required_login']) echo 'required-login'; ?>" href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>"><?php _e('Selecionar', 'idealbiz'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="d-block d-md-none"><?php echo $option['title']; ?></span>
                </div>

        <?php
            }
            $bo++;
        } ?>
    </div>
</section>

<?php if ($welcome_message['enabled']) : ?>
    <section class="homepage hidden-mobile m-t-30">
        <div class="container welcome-message">
            <div class="row col-xs-12 justify-content-center">
                <?php if ($welcome_message['image']) : ?>
                    <div class="image col-md-6" style="background-image: url('<?php echo $welcome_message['image']['sizes']['medium_large']; ?>');"></div>
                <?php endif; ?>
                <div class="col-md-6 content">
                    <?php if ($welcome_message['title']) : ?>
                        <h1 class="font-weight-semi-bold"><?php echo $welcome_message['title']; ?></h1>
                    <?php endif; ?>
                    <?php if ($welcome_message['text']) : ?>
                        <div class="text">
                            <?php echo $welcome_message['text']; ?>
                        </div>
                    <?php endif; ?>
                    <a class="btn p-y-13 p-x-40 m-t-15 lrm-register" href="#"><?php _e('Register', 'idealbiz'); ?></a>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>


<section class="homepage m-t-30 m-b-10">
    <div class="container welcome-message ">
        <h1 class="text-center w-100 m-b-15 hidden-mobile"><?php _e('Featured Business', 'idealbiz'); ?></h1>
        <h2 class="text-left w-100 m-b-15 hidden-desktop"><?php _e('Featured Business', 'idealbiz'); ?></h2>
        <div style="text-align:center;">
        <?php echo '<a class="btn-blue" href="'.getLinkByTemplate('page-listings.php').'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
        </div>
        <br/>
        <div class="row col-xs-12 justify-content-center hight-home-slider p-relative">
            <div class="swiper-container listing-page">
                <div class="swiper-wrapper listings">
                    <?php
                   
                    $high_args = array(
                        'posts_per_page' => 12,
                        'post_type' => 'listing',
                        'post_status' => 'publish',
                        'boost' => 'highlight' 
                    );

                    $current_blod_id = get_current_blog_id();
                    $broadcasted_children = array();
                    $q_broadcasted = "SELECT * FROM ib__3wp_broadcast_broadcastdata WHERE blog_id = {$current_blod_id}";
                    $countries = array();
                    $bposts = array();
                    $r_breadcasted = $wpdb->get_results($q_broadcasted, ARRAY_A);
                    
                    foreach ($r_breadcasted as $kr_breadcasted => $b) {
                        $b_aux = array();
                        $b_aux['post_id'] = $b['post_id'];
                        $b_aux['blog_id'] = $b['blog_id'];
                        $b_aux['data'] = unserialize(base64_decode($b['data']));
                        if (array_key_exists('linked_parent', $b_aux['data'])) { 
                            if ($b_aux['data']["linked_parent"]) {
                                $broadcasted_children[] = $b_aux;
                                $countries[$b_aux['data']["linked_parent"]['blog_id']][] = $b['post_id'];
                            }
                        }
                    }
                    //$s='';
                    //$time_start = microtime(true); 
                    //$high = get_transient('high_args');
                    //if ($high === false) {
                    //echo 'query';
                    $high = new WP_Query($high_args);
                    //  set_transient('high_args', $high, 3600 * 2);
                    //}else{
                    //echo 'cache';
                    //}
                    if ($high->have_posts()) {
                        while ($high->have_posts()) {
                            $high->the_post();
                            echo '<div class="swiper-slide hight-slide">';
                            set_query_var('countries', $countries);
                            get_template_part('/elements/listings');
                            /*
                                $is_certified = get_field('listing_certification_status', get_the_ID()) == 'certification_finished';
                                if ($is_certified) {
                                    $badge = get_template_directory_uri() . '/assets/img/badge.png';
                                    echo '<div class="certified-badge" style="background-image: url('.$badge.'"></div>';
                                }
                                echo '<div class="image col-md-6" style="height: 100%; background-image: url('.get_field('featured_image',get_the_ID())['sizes']['medium'].');"></div>';
                                echo '<div class="col-md-6 content">
                                        <div class="category">
                                            <span class="star"><i class="icofont-star"></i></span>
                                            <span>'.get_field('category',get_the_ID())->name.'</span>
                                        </div>
                                        <div class="location p-x-10 p-y-10 font-weight-bold">
                                            <i class="icon-local"></i>
                                            <span class="text-uppercase"><'.get_field('location',get_the_ID())->name.'</span>
                                        </div>
                                        <h1 class="font-weight-semi-bold">'.get_the_title().'</h1>
                                        <div class="text">
                                        <span class="price m-t-30">'.IDB_Listing_Data::get_listing_value(get_the_ID(), 'price_type').'</span>
                                        </div>
                                    <a class="btn p-y-13 p-x-40 m-t-15 lrm-register" href="'.get_the_permalink().'">'.__('View', 'idealbiz').'</a>
                                </div> */
                            echo '</div>';
                        }
                        wp_reset_postdata();
                    }
                    ?>
                </div>
            </div>
            <div class="swiper-button-next d-none"><i class="icofont-thin-right"></i></div>
            <div class="swiper-button-prev d-none"><i class="icofont-thin-left"></i></div>
        </div>
    </div>

</section>
<?php



//$time_end = microtime(true);
//$execution_time = ($time_end - $time_start);
//echo '<b>Total Execution Time:</b> '.$execution_time.' secs';

?>

<?php //whiteBackground(); 
?>
<hr class="m-t-15 m-b-15 clear m-m-b-25 m-m-t-15 m-m-r-15  m-m-l-15">
<section class="homepage countries m-m-b-5 m-b-60 <?php if ($countries_background_image) echo 'background-image md-m-b-0'; ?>" style="<?php if ($countries_background_image) { ?>background-image: url('<?php echo $countries_background_image['url']; ?>'); <?php } ?>">
    <div class="container justify-content-between align-items-center d-none d-md-block">
        <div class="row">
            <div class="col-md-12 text-center m-b-30">
                <h1 class=""><?php _e('Where?', 'idealbiz'); ?></h1>
                <h3 class="hidden-mobile"><?php echo get_field('countries_description'); ?></h3>
                <?php the_content(); ?>
            </div>
        </div>
    </div>
    <div class="site-blocks container big-width p-l-20 p-r-20">
        <h2 class="d-none d-xs-block text-xs-left m-h2 m-b-15">
            <?php _e('Where?', 'idealbiz'); ?>
            <div class="d-inline-block hidden-desktop"><?php infoModal(get_field('countries_description')); ?></div>
        </h2>
        <div class="row col-md-12 countries-slider h-slider hidden-mobile">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    $continents = getContinents();
                    $countries = getNetworkCountries();
                    $sl_continents = '';
                    foreach ($countries as $kcontinent => $countries) {
                        echo '<div class="swiper-slide b-r-5 o-hidden rectangle-square h-200px " style="background-image: url(' . get_template_directory_uri() . '/assets/img/continents/' . strtolower($kcontinent) . '.jpg)">
                                               <div class="content d-flex flex-column w-100 h-100 p-y-25 p-x-15">
                                                    <h2>' . $continents[$kcontinent] . '</h2>
                                                    <ul class="flags">';
                        //$i=0;
                        foreach ($countries as $country) {
                            $flag = DEFAULT_WP_CONTENT . '/plugins/polylang/flags/' . strtolower($country['iso']) . '.png';
                            echo '<li>
                                                                <a alt="' . $country['name'] . '" href="' . $country['link'] . '">
                                                                    <img src="' . $flag . '" title="' . $country['name'] . '" />
                                                                </a>
                                                            </li>';
                        }
                        echo '</ul>
                                                </div>
                                           </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>


    <div class="site-blocks d-flex flex-row flex-wrap justify-content-start container m-t-25 where-home hidden-desktop">
        <?php
        $co = 0;
        $continents = getContinents();
        $countries = getNetworkCountries();
        $sl_continents = '';
        $toggle_where = '';
        foreach ($countries as $kcontinent => $countries) { ?>
            <div class="b-opts">
                <div class="b-opts-inner m-y-5 p-10">
                    <div data-href="#" data-co="<?php echo $co; ?>" style="background-size: cover; background-image: url('<?php echo get_template_directory_uri() . '/assets/img/continents/' . strtolower($kcontinent) . '.jpg'; ?>');" class="w-200px h-200px block stroke m-x-5 b-r-5 m-appicon mini-flags-a">
                        <div class="has-more <?php echo count($countries) <= 2 ? 'd-none' : ''; ?>"><span class="icofont-plus"></span></div>
                        <?php
                            $toggle_where .= '
                            <div class="b-opts-inner">
                            <div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke co-' . $co . '">
                                <div class="b-opts-d-open-inner white--background">
                                    <a href="#" class="b-opts-close"><i class="icon icon-close"></i></a>';
                            $ec = '';
                            foreach ($countries as $country) {
                                $flag = DEFAULT_WP_CONTENT . '/plugins/polylang/flags/' . strtolower($country['iso']) . '.png';
                                echo '
                                <a style="z-index:9999;" alt="' . $country['name'] . '" href="' . $country['link'] . '">
                                <img src="' . $flag . '" class="mini-flags w-30px h-20px" title="' . $country['name'] . '" />
                                </a>
                                ';
                                $ec .= '<a alt="' . $country['name'] . '" href="' . $country['link'] . '">
                                                <img src="' . $flag . '" title="' . $country['name'] . '" />
                                              </a>';
                            }
                            $toggle_where .= '   
                                            <div class="b-opts-body" style="padding: 0 20px !important;">
                                                <h3 class="font-weight-semi-bold m-b-20">' . $continents[$kcontinent] . '</h3>
                                                <div class="d-flex mini-flag-list">
                                                    ' . $ec . '
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                    </div>';
                            ?>
                    </div>
                </div>
                <span class="d-block d-md-none"><?php echo $continents[$kcontinent]; ?></span>
            </div>
        <?php $co++;
        } ?>
    </div>

</section>

<?php if(get_field('newsletter_shortcode')){ ?>
<div class="newsletter m-t-50 md-m-t-0">
    <div class="container medium-width">
        <div class="row">
            <div class="col-md-3 d-flex align-items-center">
                <h1><?php _e('Alerts by email', 'idealbiz'); ?></h1>
            </div>
            <div class="col-md-5 d-flex align-items-center m-b-15 md-m-b-0">
                <span class="text"><?php _e('Receive by email business opportunities even before they are published on the website.', 'idealbiz'); ?></span>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <div class="input-group">
                    <?php echo do_shortcode(get_field('newsletter_shortcode')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php

echo '<div class="site-blocks h-0 o-hidden">
        <div class="b-opts">' .
    $toggle_where .
    '</div>
     </div>';


get_footer();

?>