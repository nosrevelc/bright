<?php
// Template Name: Member List



get_header();


function cl_services_member_list()
{
    $term_obj_list = get_the_terms(get_the_ID(), 'service_cat');
    if ($term_obj_list) {

        $terms_string = '<span class=" dashicons dashicons-welcome-learn-more" style="font-size:1.5em;"></span>' . join(', ', wp_list_pluck($term_obj_list, 'name'));
        return $terms_string;
    }
}


$pageid = get_the_ID();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
/* $posts_per_page = (new IDB_Experts)->posts_per_page; */
$posts_per_page = 30;

/* var_dump($member_category); */
$word = get_query_var('search');
$catg = get_query_var('service_cat');
global $wp;

//$current_url = home_url(add_query_arg(array(), $wp->request));
$current_url = getLinkByTemplate('page-memberlist.php');


if (!$_GET['by_name'] || $_GET['by_name'] == 'ASC') {
    $cl_orderName = array('ASC', 'Z-A', $current_url . '?by_name=DESC', '<span class="dashicons dashicons-arrow-up"></span>');
} else {
    $cl_orderName = array('DESC', 'A-Z', $current_url . '?by_name=ASC', '<span class="dashicons dashicons-arrow-down"></span>');
}


if ($catg) {
    $catg = array(
        's' => $word,
        'taxonomy' => 'service_cat',
        'field' => 'term_id',
        'terms' => $catg,
        'tax_query' => array(
            array(
                'taxonomy' => 'member_cat',        // taxonomy name
                'field' => 'cat',           // term_id, slug or name
                'terms' => $member_category // term id, term slug or term name
            )

        )
    );
}

$loca = get_query_var('location');
if ($loca) {
    $loca = array(
        's' => $word,
        'taxonomy' => 'location',
        'field' => 'term_id',
        'terms' => $loca,

    );
}

$includeIds = array();
if (WEBSITE_SYSTEM == '1') {
    $experts_with_fees = getExpertsWithActiveFees();
    if (empty($experts_with_fees)) {
        $includeIds = array(-1);
    } else {
        $includeIds = $experts_with_fees;
    }
}




$args = array(
    's' => $word,
    'posts_per_page' => $posts_per_page,
    'post_type' => 'expert',
    'post_status' => 'publish',
    'post__in' => $includeIds,
    'paged' => $paged,
    'orderby'   => 'post_title',
    'order' => $cl_orderName[0],
    'tax_query' => array(
        $catg,
        'relation' => 'AND',
        $loca
    ),
);

$experts = new WP_Query($args);

?>



<div class="cl_display justify-content-center">
    <div class="cl_mobile_show text-center p-b-30">
        <h1><?php echo get_the_title($pageid) ?></h1>
    </div>
    <div class="experts-container"><?php cl_voltar(); ?></div>

    <div class="cl_menu_membro ">

        <div class="pull-right d-md-none">
            <?php if ($phone = get_field('phone', 'option')) : ?>
                <a href="tel:<?php echo $phone['call_code'] . $phone['number']; ?>" class="light-blue--color phone-number-right"><?php echo $phone['number']; ?></a>
            <?php endif; ?>
            <a class="greyer--color" href="<?php echo get_permalink(pll_get_post(get_page_by_path('contacts')->ID)); ?>"><?php _e('Contacts', 'idealbiz'); ?></a>
        </div>

        <ul class="sub-menu box black--color white--background">
            <li class="hex-a55df1 menu-item">
                <div class="icoMiddle">
                    <a data-iconbg="#a55df1" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" title="<?php _e('My Account', 'idealbiz'); ?>">
                        <i class="icon icon-perfil"></i> <?php _e('My Account', 'idealbiz'); ?>
                    </a>
                </div>
            </li>

            <li class="hex-a55df1 menu-item d-none">
                <div class="icoMiddle">
                    <a data-iconbg="#a55df1" href="<?php echo getLinkByTemplate('premium-buyer.php'); ?>" title="<?php _e('Premium Buyer', 'idealbiz'); ?>">

                        <?php
                        $premium_buyer = isPremiumBuyer();
                        if (is_array($premium_buyer) && $premium_buyer['status'] == 3) {
                            echo '<i class="icofont-check valid-check-plan green--color"></i>';
                        } elseif (is_array($premium_buyer) && $premium_buyer['status'] == 2) {
                            echo '<i class="icofont-check valid-check-plan yellow--color"></i>';
                        } else {
                        }
                        ?>
                        <?php _e('Premium Buyer', 'idealbiz'); ?> echo '<div class="">
                            <h1>' . get_the_title($pageid) . '</h1>
                        </div>';
                    </a>
                </div>
            </li>
            <!-- INICIO MENU POSTS -->
            <li class="hex-a55df1 menu-item">
                <div class="icoMiddle text-secondary">
                    <!--                 <a data-iconbg="#a55df1" href="<?php echo getLinkByTemplate('post-content.php'); ?>" title="<?php _e('Post Content', 'idealbiz'); ?>">
                   
                    <?php _e('Panel Post Content', 'idealbiz'); ?><span class="icon dashicons dashicons-welcome-write-blog"></span>
                    
                </a> -->
                    <span class="icon dashicons dashicons-welcome-write-blog"></span><?php _e('Panel Post Content', 'idealbiz'); ?>
                </div>
            </li>
            <!-- FINAL MENU POSTS -->
            <!-- Broker no menu -->
            <!--         <li class="hex-a55df1 menu-item">
                        <a data-iconbg="#a55df1" href="<?php echo getLinkByTemplate('broker-account.php'); ?>" title="<?php _e('Broker Account', 'idealbiz'); ?>">
                            
                            <?php
                            $broker = isBroker();
                            //echo  pll_current_language();
                            if (array_key_exists('status', $broker)) {
                                if (is_array($broker) && $broker['status'] == 3) {
                                    echo '<i class="icofont-check valid-check-plan green--color"></i>';
                                    _e('Broker Account', 'idealbiz');
                                } elseif (is_array($broker) && $broker['status'] == 2) {
                                    echo '<i class="icofont-check valid-check-plan yellow--color"></i>';
                                    _e('Broker Account', 'idealbiz');
                                }
                            } else {
                                _e('Add Broker Subscription', 'idealbiz');
                            }
                            ?>
                            
                        </a>
                    </li> -->



            <li class="hex-a55df1 menu-item">
                <div class="icoMiddle">
                    <a data-iconbg="#a55df1" href="<?php echo wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) ?>" title="<?php _e('My Listings', 'idealbiz'); ?>">
                        <i class="icon icon-vender"></i><?php _e('My Listings', 'idealbiz'); ?>
                    </a>
                </div>
            </li>
            <li class="hex-a55df1 menu-item">
                <div class="icoMiddle">
                    <a data-iconbg="#a55df1" href="<?php echo wc_get_endpoint_url('favorites', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) ?>" title="<?php _e('My Favorites', 'idealbiz'); ?>">
                        <i class="icon icofont-heart"></i> <?php _e('My Favorites', 'idealbiz'); ?>
                    </a>
                </div>
            </li>
            <?php
            $sr = new \WP_Query(
                array(
                    'post_type'      => 'service_request',
                    'posts_per_page' => 1,
                )
            );
            //if ($sr->have_posts()) {
            ?>
            <li class="hex-a55df1 menu-item">
                <div class="icoMiddle">
                    <a data-iconbg="#a55df1" href="<?php echo wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) . '?home=1'; ?>">
                        <span class="icon dashicons dashicons-admin-generic"></span><?php _e('Service Requests', 'idealbiz'); ?>
                    </a>
                </div>
            </li>
            <?php //} 
            ?>

            <li class="hex-a55df1 menu-item">
                <div class="icoMiddle">
                    <a data-iconbg="#a55df1" href="<?php echo getLinkByTemplate('RecommendedBusiness.php') . '?recommended=1'; ?>">

                        <span class="icon dashicons dashicons-money-alt"></span><?php _e('_str Business opportunity', 'idealbiz'); ?>
                    </a>
                </div>
            </li>

            <li class="hex-a55df1 menu-item">
                <div class="icoMiddle">
                    <a data-iconbg="#a55df1" href="<?php echo $current_url; ?>">

                        <span class="icon dashicons dashicons-editor-removeformatting"></span><?php _e('_str Clean Filter', 'idealbiz'); ?>
                    </a>
                </div>
            </li>

            <li class="hex-a55df1 menu-item">
                <div class="icoMiddle">
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/'))) ?>" title="<?php echo esc_attr__('Log out', 'idealbiz'); ?>" class="account-nav__link">
                        <span class="icon dashicons dashicons-exit"></span><?php esc_html_e('Log out', 'idealbiz') ?>
                    </a>
                </div>
            </li>


        </ul>
    </div>


    <div>

        <section class="experts-page position-relative container medium-width">
            <div class="container text-center m-t-30 cl_mobile_hidden">


                <?php
                if ($word || $catg || $loca) {
                    $args = array(
                        's' => $word,
                        'posts_per_page' => $posts_per_page,
                        'post_type' => 'expert',
                        'post_status' => 'publish',
                        'tax_query' => array(
                            $catg,
                            'relation' => 'AND',
                            $loca
                        ),
                    );

                    $experts = new WP_Query($args);
                    $show_map = true;
                    $cl_cleanFilter = true;

                    echo '<h1>' . __("Expert Search Results:", 'idealbiz') . '<br/>';
                    echo '<span class="extra_small-font">' . '(' . $experts->found_posts . ' ' . __('Results found', 'idealbiz') . ')</span></h1>';
                ?>

                <?php


                } else {

                    echo '<div class=""><h1>' . get_the_title($pageid) . '</h1></div>';
                }

                echo get_post_field('post_content', $pageid);

                // the_content();
                ?>
                <br /><br />
            </div>
            <div class="row">


                <div class="col-md-9 experts">

                    <div>
                        <div class="woocommerce w-100">
                            <div>



                                <div class="d-flex search-bar medium-width p-t-5 p-b-5  m-0-auto toggle-search" id="site-search">
                                    <p class="d-none"><?php _e('Search ', 'idealbiz'); ?></p>

                                    <form role="search" method="get" id="search-form--header" class=" cl_search search-form--header w-100" action="<?php echo $current_url; ?>">

                                        <div class="text-global-search border-blue b-t-l-r b-b-l-r d-w-100">
                                            <input name="search" type="text" autocomplete="off" minlength="3" placeholder="<?php _e('_str Search', 'idealbiz'); ?>" value="<?php if (isset($_REQUEST['search'])) {
                                                                                                                                                                                echo $_REQUEST['search'];
                                                                                                                                                                            } ?>" />
                                        </div>

                                        <div class="expert-location--select border-blue m-l--1 b-t-r-r b-b-r-r d-w-100">
                                            <select data-placeholder="<?php _e('Location'); ?>" name="location">
                                                <option></option>
                                                <?php
                                                $terms = get_terms(
                                                    array('taxonomy' => 'location', 'hide_empty' => false, 'parent' => 0) //change to true after
                                                );
                                                foreach ($terms as $term) {
                                                    $selected = '';
                                                    if (isset($_REQUEST['location'])) {
                                                        if ($_REQUEST['location'] == $term->term_id) {
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                    echo sprintf(
                                                        '<option value="%1$d" %3$s>%2$s</option>',
                                                        $term->term_id,
                                                        $term->name,
                                                        $selected
                                                    );
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="listing_cat--select border-blue m-l--1 b-t-r-r b-b-r-r d-w-100">
                                            <select class="generic-search-field" data-placeholder="<?php _e('Area of ​​Expertise', 'idealbiz'); ?>" name="service_cat">
                                                <option></option>
                                                <?php
                                                $terms = get_terms(
                                                    array('taxonomy' => 'service_cat', 'hide_empty' => false, 'parent' => 0) //change to true after
                                                );
                                                foreach ($terms as $term) {
                                                    $selected = '';
                                                    if (isset($_REQUEST['service_cat'])) {
                                                        if ($_REQUEST['service_cat'] == $term->term_id) {
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                    echo sprintf(
                                                        '<option value="%1$d" %3$s>%2$s</option>',
                                                        $term->term_id,
                                                        $term->name,
                                                        $_REQUEST['service_cat'] == $term->term_id ? 'selected' : ''
                                                    );
                                                }
                                                ?>
                                            </select>
                                        </div>


                                        <div><button class="btn btn-blue m-l-10 font-btn-plus blue--hover" type="submit" value="Submit"><?php _e('Search', 'idealbiz'); ?></button></div>

                                    </form>
                                </div>
                                <?php
                                $show_map = true;
                                if ($show_map == true) {
                                    $zoom_map = 2;
                                    $latitude_center = floatval($config_sub_homepage['latitude_sub']);
                                    $longitude_center = floatval($config_sub_homepage['longitude_sub']);
                                    echo '<div>';
                                    include(MY_THEME_DIR . 'elements/member-map.php');
                                    echo '</div>';
                                }
                                ?>

                            </div>

                            <div class="d-flex">
                                <div>
                                    <?php get_field('localation_member', 'options');
                                    echo '<div class="cl_order"><a href="' . $cl_orderName[2] . '">' . __('_str Order By Name', 'idealbiz') . ' : ' . $cl_orderName[1] . $cl_orderName[3] . '</a></div>';
                                    ?>
                                </div>
                                <?php if($cl_cleanFilter == true){?>
                                <div class="icoMiddle cl_order">
                                    <a data-iconbg="#a55df1" href="<?php echo $current_url; ?>">

                                        <span class="icon dashicons dashicons-editor-removeformatting"></span><?php _e('_str Clean Filter', 'idealbiz'); ?>
                                    </a>
                                </div>
                                <?php }?>
                            </div>
                            <?php

                            if ($experts->have_posts()) :

                                while ($experts->have_posts()) : $experts->the_post();
                                    get_template_part('/elements/member-list') . '<br/>';

                                endwhile;
                            else :
                                get_template_part('/elements/no_results');
                            endif;
                            ?>
                        </div>
                        <?php
                        echo pagination($paged, $experts->max_num_pages, 4, 'expert');
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<?php if (!$member_category) { ?>
    <section class="d-none page">
        <div class="container text-center m-t-30 m-b-30">
            <hr class="m-t-0 m-b-50 clear m-x-15">
            <h1><?php _e('Apply as Expert', 'idealbiz'); ?></h1>
            <p class="text-center"><?php _e('Become an iDealBiz Consultant. Submit your application here!', 'idealbiz'); ?></p>
            <a class="btn-blue normal-line-height lrm-login" href="<?php echo getLinkByTemplate('submit-expert.php') ?>"><?php _e('Apply', 'idealbiz', 'idealbiz'); ?></a>
        </div>
    </section>
<?php } ?>

<div class="sidebar-overlay"></div>


<?php get_footer(); ?>

<style>

    #map {
        height: 400px !important;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .cl_mobile_show {
        display: none;
    }

    .cl_mobile_hidden {
        display: block;
    }

    .search-form--header .expert-location--select {
        width: 35%;
    }

    .search-form--header .listing_cat--select {
        width: 55%;
    }

    .search-form--header .text-global-search {
        width: 85%;
    }

    .cl_order a {
        padding-left: 30px;
        padding-top: 25px;
        font-weight: 600;
        font-size: 0.9em;
        text-transform: uppercase;
    }

    .m-t-20 {
        margin-top: 312px !important;
    }

    .cl_search {
        z-index: 999;
        max-width: 97%;
        margin: 0 auto;

    }

    .icoMiddle i {
        vertical-align: middle;
        margin-right: 15px;

    }

    .col-md-9 {
        max-width: 100% !important;
        /* right: -220px !important; */
    }

    .cl_menu_membro {
        /* text-align: right; */
        list-style-type: none;
        padding-top: 215px;
        /* padding-right: 20px; */

    }

    .cl_menu_membro>.sub-menu {
        list-style-type: none;
        font-size: 1.2em;
        border: 1px solid #cccccc;
        border-radius: 10px;
        padding-top: 15px;
        padding-bottom: 15px;
        /*  position: fixed;
        z-index: 999; */


    }

    .cl_menu_membro>.sub-menu li {
        padding-bottom: 7px;
        margin-top: 7px;
        margin-right: 30px;
        margin-left: -23px;

    }

    .cl_menu_membro>.sub-menu a {
        text-decoration: none;
    }

    .icon {
        font-size: 1.6em;
        margin-right: 20px;
    }

    .expert-card:hover {
        color: #005882;
        background-color: #DEDBDB;
    }

    .sidebar-filters {
        left: 100px !important;
        top: 170px !important;
        height: 70vh !important;
    }

    @media only screen and (max-width: 768px) {
        .search-form--header .expert-location--select {
            width: 90%;

        }

        .search-form--header .listing_cat--select {
            width: 90%;
        }

        .search-form--header .text-global-search {
            width: 90%;
            margin-bottom: 10px;
        }

        .search-form--header div {
            margin-bottom: 10px;
            text-align: center;
        }

        .cl_search {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cl_mobile_show {
            display: block;
        }

        .cl_mobile_hidden {
            display: none;
        }

        .cl_display {
            flex-direction: column;
        }

        .cl_menu_membro {
            margin: auto;
            width: 80%;
        }

        .cl_menu_membro {

            padding-top: unset;

        }

        .cl_menu_membro>.sub-menu {

            font-size: 0.9em;

        }

    }
</style>