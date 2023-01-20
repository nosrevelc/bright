<?php
// Template Name: Franchises
get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = 6;


$word = get_query_var('search');

$catg = get_query_var('franchise_cat');
if($catg){
    $catg = array (
        'taxonomy' => 'franchise_cat',
        'field' => 'term_id',
        'terms' => $catg,
    );
}

$loca = get_query_var('location');
if($loca){
    $loca = array (
        'taxonomy' => 'location',
        'field' => 'term_id',
        'terms' => $loca,
    );
}

$args = array(
    's' => $word,
    'posts_per_page' => $posts_per_page,
    'post_type' => 'franchise',
    'post_status' => 'publish',
    'paged' => $paged,
    'tax_query' => array(
        $catg,
        'relation' => 'AND',
        $loca
    ),
);

$franchises = new WP_Query($args);
$total = $franchises->found_posts;
?>

<section class="franchise-page position-relative container medium-width">
    <div class="container text-center m-t-30">
        <?php
            if($word || $catg || $loca){
                $args = array(
                    's' => $word,
                    'post_type' => 'franchise',
                    'post_status' => 'publish',
                    'tax_query' => array(
                        $catg,
                        'relation' => 'AND',
                        $loca
                    ),
                );
                $qtotal = new WP_Query($args);
           
                echo '<h1>'.__("Franchise Search Results:",'idealbiz').'<br/>';
                echo '<span class="extra_small-font">'.'('.$qtotal->found_posts.' '.__('Results found','idealbiz').')</span></h1>';
            }else{
                echo '<h1>'.get_the_title().'</h1>'; 
            }
            the_content();
         ?>
          <br/><br/>
    </div>
    <div class="row">
        <div class="col-md-12 franchise">
            <div class="">
                <div class="row franchise-container">
                    <?php
                    if ($franchises->have_posts()) :
                        while ($franchises->have_posts()) : $franchises->the_post(); 
                            get_template_part('/elements/franchises');
                        endwhile;
                    else :
                        get_template_part('/elements/no_results');
                    endif;
                    ?>
                </div>
                    <?php
                     echo pagination($paged, $franchises->max_num_pages, 3, 'franchise');
                    ?>
            </div>
        </div>
    </div>
</section>

<div class="sidebar-overlay"></div>


<?php get_footer(); ?> 