<?php 
$pageid = get_the_ID();
/* $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; */
$paged = '';
$word = get_query_var('search');
$catg = get_query_var('service_cat');



if($cl_varParceiro->member_category_section){
    if ($catg) {
        $catg = array(
            'taxonomy' => 'service_cat',
            'field' => 'term_id',
            'terms' => $catg,
            'orderby'=>'rand',
            'tax_query' => array(
                array(
                    'taxonomy' => 'member_cat',        // taxonomy name
                    'field' => 'cat',           // term_id, slug or name
                    'terms' => $cl_varParceiro->member_category_section // term id, term slug or term name
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
            'orderby'=>'rand',
            'tax_query' => array(
                array(
                    'taxonomy' => 'member_cat',        // taxonomy name
                    'field' => 'cat',           // term_id, slug or name
                    'terms' => $cl_varParceiro->member_category_section // term id, term slug or term name
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
            'orderby'=>'rand'
        );
    }

    $loca = get_query_var('location');
    if ($loca) {
        $loca = array(
            'taxonomy' => 'location',
            'field' => 'term_id',
            'terms' => $loca,
            'orderby'=>'rand'
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

if($cl_varParceiro->member_category_section){
    $args = array(
        's' => $word,
        'posts_per_page' => $cl_varParceiro->number_of_members,
        'post_type' => 'expert',
        'post_status' => 'publish',
        'post__in' => $includeIds,
        'paged' => $paged,
        'orderby'=>'rand',
        'tax_query' => array(
            $catg,
            'relation' => 'AND',
            $loca
        ),
        'tax_query' => array(
            array(
                'taxonomy' => 'member_cat',        // taxonomy name
                'field' => 'cat',           // term_id, slug or name
                'terms' => $cl_varParceiro->member_category_section // term id, term slug or term name
            )

        )
    );

    $experts = new WP_Query($args);
    $total = $experts->found_posts;
    ?>

    <section class="position-relative container">
    <a name="anchor_member2" id="anchor_member2"><span style="color:#ffffff">....</span></a>
        <div class="text-center">
            <?php
            if ($word || $catg || $loca) {
                $args = array(
                    's' => $word,
                    'post_type' => 'expert',
                    'post_status' => 'publish',
                    'orderby'=>'rand',
                    'tax_query' => array(
                        $catg,
                        'relation' => 'AND',
                        $loca
                    ),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'member_cat',        // taxonomy name
                            'field' => 'cat',           // term_id, slug or name
                            'terms' => $cl_varParceiro->member_category_section // term id, term slug or term name
                        )

                    )
                );
            
                $qtotal = new WP_Query($args);

                echo '<h1>' . __("Expert Search Results:", 'idealbiz') . '<br/>';
                echo '<span class="extra_small-font">' . '(' . $qtotal->found_posts . ' ' . __('Results found', 'idealbiz') . ')</span></h1>';
            } else {
                ?>
                <?php  if($cl_varParceiro->title_section_member){ ?>       
    
                <div class="row_left hidden-mobile">
                <h1 class="text-left row_tittle_desktop w-100 m-b-1 " ><?php echo $cl_varParceiro->title_section_member; ?></h1>
                </div>
                <div class="row_center hidden-desktop">
                <h1 class="text-center row_tittle_mpbile w-100 m-b-11 " style="font-size:2em !important;" ><?php  echo $cl_varParceiro->title_section_member; ?></h1>
                </div>
                
                <?php }?>
                <?php  
                if($cl_varParceiro->view_all_member_partner){ ?>  
                <div class="row_left text-left hidden-mobile" >
                <?php echo '<a class="btn-blue" href="'.$cl_varParceiro->select_page_all_member_partner->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
                </div>
                <div class="row_center text-center hidden-desktop" >
                <?php echo '<a class="btn-blue" href="'.$cl_varParceiro->select_page_all_member_partner->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
                </div>
                <?php }?>
    
    
            <?php } ?>
    <?php
     }else{
    $args = array(
        's' => $word,
        'posts_per_page' => $cl_varParceiro->number_of_members,
        'post_type' => 'expert',
        'post_status' => 'publish',
        'post__in' => $includeIds,
        'paged' => $paged,
        'orderby'=>'rand',
        'tax_query' => array(
            $catg,
            'relation' => 'AND',
            $loca
        ),
    );

    $experts = new WP_Query($args);
    $total = $experts->found_posts;
    ?>

 <section class="position-relative container">
        <div class="text-center">
            <?php
            if ($word || $catg || $loca) {
                $args = array(
                    's' => $word,
                    'post_type' => 'expert',
                    'post_status' => 'publish',
                    'orderby'=>'rand',
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
            
            echo '<div class="hidden-mobile"><div class="text-left m-t-20"style="font-size:0.8em !important;"><h1>'.$cl_varParceiro->title_section_member.'</h1></div></div>';
            echo '<div class="hidden-desktop"><div class="text-center"style="font-size:1.3em !important;"><h1>'.$cl_varParceiro->title_section_member.'</h1></div></div>';
        }

 }

        // the_content();
        ?>
    </div><br/><br/>
    <div class="container-2"> 
    <div class="cl_slider o-hidden">
        <div class="swiper-wrapper">
        <?php

                if ($experts->have_posts()) :
                    while ($experts->have_posts()) : $experts->the_post();
                    ?>
                    <div class="m-y-5 swiper-slide expert-card-2 expert-card">
                    <?php get_template_part('/elements/experts');?>
                    </div>   
                    <?php
                    endwhile;
                else :
                get_template_part('/elements/no_results');
                endif;
            ?>
        </div>
    </div>
    </div>
</section>




<div class="sidebar-overlay"></div>