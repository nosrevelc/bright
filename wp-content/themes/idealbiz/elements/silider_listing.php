

<?php 

if ($cl_varlisting->listing_our_wanted == 'wanted'){
    $cl_resultado = 'page-wanted.php';
}else{
    $cl_resultado = 'page-result_listings.php';           
}
?> 
<!--     INICIO TITULOS E BOTÃO DESKTOP-->

<div class="container hidden-mobile m-t-20">
<?php  if($cl_varlisting->title_section_linsting){ ?>       
<div class="row_left ">
<h1 class="text-left row_tittle_desktop w-100 m-b-1 hidden-mobile" ><?php echo $cl_varlisting->title_section_linsting; ?></h1>
</div>
<?php }?>

<?php  if($cl_varlisting->view_all_opportunities ==true){ ?>
<div class="text-left hidden-mobile" >
<?php echo '<a class="btn-blue " href="'.getLinkByTemplate($cl_resultado).'?listing_cat='.$cl_varlisting->filter_view_button_all_opportunities.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
</div>
<?php }?>

</div>
<!--     TITULO TITULOS BOTÃO DESKTOP-->
<!--     INICIO TITULOS E BOTÃO MOBILE-->
<div class="container hidden-desktop m-t-20">

<?php  if($cl_varlisting->title_section_linsting){ ?>      
<div class="row_center ">
<h1 class="text-center row_tittle_mpbile w-100 m-b-11 hidden-desktop" style="font-size:2em !important;" ><?php  echo $cl_varlisting->title_section_linsting; ?></h1>
</div>
<?php }?>
<?php if($cl_varlisting->view_all_opportunities){?>

<div class="row_center text-center hidden-desktop" >
<?php echo '<a class="btn-blue " href="'.getLinkByTemplate($cl_resultado).'?listing_cat='.$cl_varlisting->filter_view_button_all_opportunities.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
</div>
<?php }?>
</div>
<!--     TITULO TITULOS BOTÃO MOBILE-->


<div class=" container">    
<div class=" container-3 row col-xs-12 justify-content-center hight-home-slider p-relative ">
<div class="swiper-container o-hidden listing-page cl_slider_listing cl_sld_listing<?php echo $id_listing;?>">
    <div class="swiper-wrapper listings ">
        <?php 
            if($cl_varlisting->listing_our_wanted=='listing'){
                $cl_template = 'listings';
            }else{
                $cl_template = 'wanted';
                $cl_varlisting->highlight = '';
            }
        ?>
           <?php
            
           if($cl_varlisting->highlight){
                $boost = 'highlight';
           }else{
            $boost = '';
           }

            if ( $cl_varlisting->categoria_anuncios)  {

                $high_args = array(
                    'post_type' => $cl_varlisting->listing_our_wanted ,
                    'posts_per_page' => $cl_varlisting->anuncio_por_pagina ,
                    'post_status' => 'publish',
                    'boost' => $boost,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'listing_cat',        // taxonomy name
                            'field' => 'cat',           // term_id, slug or name
                            'terms' => $cl_varlisting->categoria_anuncios // term id, term slug or term name
                        ) 

                ));
            }else{
                
                $high_args = array(
                    'post_type' => $cl_varlisting->listing_our_wanted,
                    'posts_per_page' => $cl_varlisting->cl_posts_per_page,
                    'post_status' => 'publish',
                    'boost' => $boost,
                    );
                }



            $high = new WP_Query($high_args);

                if ($high->have_posts()) {
                    while ($high->have_posts()) {
                        $high->the_post();
                        echo '<div class="swiper-slide hight-slide">';
                        set_query_var('countries', $countries);
                        get_template_part('/elements/'.$cl_template.'');
                       

                        echo '</div>';
                    }
                    /* wp_reset_postdata(); */
                }  
            ?>
            
        </div>
        
    </div>
 <div class="swiper-button-prev" id="swiper-button-prev<?php echo $id_listing;?>"></div>
 <div class="swiper-button-next" id="swiper-button-next<?php echo $id_listing;?>"></div>

</div>
</div>



<style>
.listing-page .job-bm-archive .listing .listing-info .title, .listing-page .listings .listing .listing-info .title, .page-jobs .job-bm-archive .listing .listing-info .title, .page-jobs .listings .listing .listing-info .title {
    max-width: 25ch;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>