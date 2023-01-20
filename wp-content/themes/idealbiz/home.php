<?php // Template Name: News & Advices



get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = 12;


$word = $_REQUEST['search'];

$catg = $_REQUEST['category'];

if($catg){
    $catg = array (
        'taxonomy' => 'category',
        'field' => 'term_id',
        'terms' => $catg,
    );
}

if($_REQUEST['category']==1){
    $catg='';
}
$args = array(
    's' => $word,
    'posts_per_page' => $posts_per_page,
    'post_type' => 'post',
    'post_status' => 'publish',
    'paged' => $paged,
    'tax_query' => array(
        $catg
    ),
);

$posts = new WP_Query($args);
$total = $posts->found_posts;
?>

<section class="page blog-page position-relative container medium-width">
    
    <div class="container text-center m-t-30">
        <?php
            if($word || $catg){
                $args = array(
                    's' => $word,
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'tax_query' => array(
                        $catg,
                        'relation' => 'AND',
                        $loca
                    ),
                );
                $qtotal = new WP_Query($args);
           
                echo '<h1>'.__("Posts Search Results:",'idealbiz').'<br/>';
                echo '<span class="extra_small-font">'.'('.$qtotal->found_posts.' '.__('Results found','idealbiz').')</span></h1>';
            }else{
                echo '<h1>'.__('News & Advices').'</h1>'; 
            }
            //the_content();
         ?>
          <br/><br/>
    </div>
    <div class="d-flex flex-column flex-wrap justify-content-start container">
        <?php

        
        echo '<ul class="category-menu">';
        $i=0;
        foreach (get_categories('orderby=count&order=DESC') as $category ) 
        {
             if( $category->category_parent == '0') 
             { 
                $url = '';
                $url =  get_permalink( get_option( 'page_for_posts' ) ) . '?type=post&category=' . $category->term_id ;
                $current_page_item='';
                if($category->term_id == $_REQUEST['category']){
                    $current_page_item= 'current_page_item';
                }
                if(!$catg && $i == 0){
                    $current_page_item= 'current_page_item';
                }
                echo '<li class="'. $current_page_item .' cat-item cat-item-' . $category->term_id . '"><a href="' . $url . '">' . $category->name . '</a></li>';
             }
             $i++;
        }
        echo '</ul>';
        ?>
    </div>

    <div class="row">
        <div class="col-md-12 post">
            <div class="">
                <div class="row franchise-container">
                    <?php
                    $np=0;
                    if ($posts->have_posts()) :
                        while ($posts->have_posts()) : $posts->the_post(); 
                            get_template_part('/elements/posts');
                        endwhile;
                    else :
                        $np=1;
                        echo '<br/><br/>';
                        get_template_part('/elements/no_results');
                        echo '<br/><br/>';
                    endif;
                    ?>
                </div>
                    <?php
                        if(!$np)
                            echo pagination($paged, $posts->max_num_pages, 3, 'post');
                    ?>
            </div>
        </div>
    </div>
</section>

<div class="sidebar-overlay"></div>


<?php get_footer(); ?> 