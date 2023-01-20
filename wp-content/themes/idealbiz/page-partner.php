<?php
// Template Name: Partner

get_header();

$pageid = get_the_ID();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = new IDB_Experts();
$posts_per_page = 14; //NOTA CLEVERSON "SET DE NOVO VALOR NA CLASSE IDB_Experts"
$member_category = get_field('member_category');
/* var_dump($member_category); */
$word = get_query_var('search');
$catg = get_query_var('service_cat');



if($member_category){
    if ($catg) {
        $catg = array(
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
            'taxonomy' => 'location',
            'field' => 'term_id',
            'terms' => $loca,
            'tax_query' => array(
                array(
                    'taxonomy' => 'member_cat',        // taxonomy name
                    'field' => 'cat',           // term_id, slug or name
                    'terms' => $member_category // term id, term slug or term name
                )

            )
        );
    }
}else{
    if ($catg) {
        $catg = array(
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
            'taxonomy' => 'location',
            'field' => 'term_id',
            'terms' => $loca, 
        ); 
    }
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

if($member_category){
    $args = array(
        's' => $word,
        'posts_per_page' => $posts_per_page,
        'post_type' => 'expert',
        'post_status' => 'publish',
        'post__in' => $includeIds,
        'paged' => $paged,
        'tax_query' => array(
            $catg,
            'relation' => 'AND',
            $loca
        ),
        'tax_query' => array(
            array(
                'taxonomy' => 'member_cat',        // taxonomy name
                'field' => 'cat',           // term_id, slug or name
                'terms' => $member_category // term id, term slug or term name
            )

        )
    );

    $experts = new WP_Query($args);
    $total = $experts->found_posts;
    ?>

    <section class="experts-page position-relative container medium-width">
        <div class="container text-center m-t-30">
            <?php
            if ($word || $catg || $loca) {
                $args = array(
                    's' => $word,
                    'post_type' => 'expert',
                    'post_status' => 'publish',
                    'tax_query' => array(
                        $catg,
                        'relation' => 'AND',
                        $loca
                    ),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'member_cat',        // taxonomy name
                            'field' => 'cat',           // term_id, slug or name
                            'terms' => $member_category // term id, term slug or term name
                        )

                    )
                );
            
            $qtotal = new WP_Query($args);

            echo '<h1>' . __("Expert Search Results:", 'idealbiz') . '<br/>';
            echo '<span class="extra_small-font">' . '(' . $qtotal->found_posts . ' ' . __('Results found', 'idealbiz') . ')</span></h1>';
        } else {
            echo '<h1>' . get_the_title($pageid) . '</h1>';
        }

 }else{
    $args = array(
        's' => $word,
        'posts_per_page' => $posts_per_page,
        'post_type' => 'expert',
        'post_status' => 'publish',
        'post__in' => $includeIds,
        'paged' => $paged,
        'tax_query' => array(
            $catg,
            'relation' => 'AND',
            $loca
        ),
    );

    $experts = new WP_Query($args);
    $total = $experts->found_posts;
    ?>

    <section class="experts-page position-relative container medium-width">
        <div class="container text-center m-t-30">
            <?php
            if ($word || $catg || $loca) {
                $args = array(
                    's' => $word,
                    'post_type' => 'expert',
                    'post_status' => 'publish',
                    'tax_query' => array(
                        $catg,
                        'relation' => 'AND',
                        $loca
                    ),
                );
            
            $qtotal = new WP_Query($args);

            echo '<h1>' . __("Expert Search Results:", 'idealbiz') . '<br/>';
            echo '<span class="extra_small-font">' . '(' . $qtotal->found_posts . ' ' . __('Results found', 'idealbiz') . ')</span></h1>';
        } else {
            echo '<h1>' . get_the_title($pageid) . '</h1>';
        }

 }
        echo get_post_field('post_content', $pageid);

        // the_content();
        ?>
        <br /><br />
    </div>
    <div class="row">

        <div class="col-md-12 experts">
            <div class="">
                <div class="row experts-container">
                    <?php

                    if ($experts->have_posts()) :
                        while ($experts->have_posts()) : $experts->the_post();
                            get_template_part('/elements/partner');
                        endwhile;
                    else :
                        get_template_part('/elements/no_results');
                    endif;
                    ?>
                </div>
                <?php
                echo pagination($paged, $experts->max_num_pages, 3, 'expert');
                ?>
            </div>
        </div>
    </div>
</section>


<?php if (!$member_category){?>
<section class="d-none page">
    <div class="container text-center m-t-30 m-b-30">
        <hr class="m-t-0 m-b-50 clear m-x-15">
        <h1><?php _e('Apply as Expert', 'idealbiz'); ?></h1>
        <p class="text-center"><?php _e('Become an iDealBiz Consultant. Submit your application here!', 'idealbiz'); ?></p>
        <a class="btn-blue normal-line-height lrm-login" href="<?php echo getLinkByTemplate('submit-expert.php') ?>"><?php _e('Apply', 'idealbiz', 'idealbiz'); ?></a>
    </div>
</section>
<?php }?>

<div class="sidebar-overlay"></div>


<?php get_footer(); ?>