<?php // Template Name: Sub Homepage v2

get_header();

require_once MY_THEME_DIR.'library/class-var_slides.php';

$config_sub_homepage = get_field('config_sub_homepage');
$business_background_image = get_field('business_option_image');

$countries_background_image = $config_sub_homepage['countries_image'];


$zoom_map = (int)$config_sub_homepage['zoom_map_sub'];
$latitude_center = floatval($config_sub_homepage['latitude_sub']);
$longitude_center = floatval($config_sub_homepage['longitude_sub']);

$zoom_mapx = (int)get_field('zoom_map_sub');
$latitude_centerx = floatval(get_field('latitude_sub'));
$longitude_centerx = floatval(get_field('longitude_sub'));

$show_member_map = $config_sub_homepage['show_member_map'];

//COOKIES
$post = get_the_ID();
$titulo = get_the_title( $post );
$button_options = get_field('business_option_buttons');
$transparence = str_replace(',',  '.' , get_field('transparence_cookies'));
$color_text_mobile = get_field('color_text_to_mobile');
$type_of_display_of_cookies = get_field('type_of_display_of_cookies');
//COOKIES EXTRA 

$title_cookies_1 = $config_sub_homepage['title_cookie_extra_1'];
$cookies_of_page_1 = $config_sub_homepage['cookies_of_page_1'];
$mostrar_cookie_extra_1= $config_sub_homepage['show_cookies_1'];
$view_button_all_in_section_1= $config_sub_homepage['view_button_all_in_section_1'];

//PRODUTOS
$title_section_products = $config_sub_homepage['title_section_products'];
$mostrar_produtos = $config_sub_homepage['mostrar_produtos'];
$quantidade_de_produtos = $config_sub_homepage['quantidade_de_produtos'];
$categorias_de_pordutos = $config_sub_homepage['categorias_de_pordutos'];
$view_all_products = $config_sub_homepage['view_all_products'];
$select_page_all_product = $config_sub_homepage['select_page_all_product'];

//1-PRODUTOS
$mostrar_produtos_1 = $config_sub_homepage['mostrar_produtos_1'];
$quantidade_de_produtos_1 = $config_sub_homepage['quantidade_de_produtos_1'];
$categorias_de_pordutos_1 = $config_sub_homepage['categorias_de_pordutos_1'];
$view_all_products_1 = $config_sub_homepage['view_all_products_1'];
$select_page_all_product_1 = $config_sub_homepage['select_page_all_product_1'];
//2-PRODUTOS
$mostrar_produtos_2 = $config_sub_homepage['mostrar_produtos_2'];
$quantidade_de_produtos_2 = $config_sub_homepage['quantidade_de_produtos_2'];
$categorias_de_pordutos_2 = $config_sub_homepage['categorias_de_pordutos_2'];
$view_all_products_2 = $config_sub_homepage['view_all_products_2'];
$select_page_all_product_2 = $config_sub_homepage['select_page_all_product_2'];


// SLIDE POSTOS
$title_section_slide_post = $config_sub_homepage['title_section_slide_post'];
$slider_content = $config_sub_homepage['slider_content'];
$cl_model_slide = $config_sub_homepage['slide_post_model'];
$type_slide = $config_sub_homepage['type_slide'];
$post_category_slider = $config_sub_homepage['post_category_slider'];
$design = $config_sub_homepage['design'];
$the_amount = $config_sub_homepage['the_amount'];
$show_dots = $config_sub_homepage['show_dots'];
$cl_shortcode = $config_sub_homepage['shortcode_new_model'];
$view_all_posts = $config_sub_homepage['view_all_posts'];
$select_page_all_posts = $config_sub_homepage['select_page_all_posts'];



// COUNTRY NEWSLETTER
$title_section_contry_newsletter = $config_sub_homepage['title_section_contry_newsletter'];
$flag_contry = $config_sub_homepage['flag_contry'];
$countries_image = $config_sub_homepage['countries_image'];
$countries_description = $config_sub_homepage['countries_description'];
$newsletter_shortcode = $config_sub_homepage['newsletter_shortcode'];


$cl_css =get_site_url().'/wp-content/themes/idealbiz/assets/css/original_sub_home_page.min.css';
?>
<style type="text/css"> @import url('<?php echo $cl_css;?>');</style>



<?php if ($config_sub_homepage['welcome_message']) : ?>
    <!-- <section class="homepage hidden-mobile m-t-30"> -->
    <section class="homepage hidden-mobile m-t-30 m-b-30">
        <div class="container welcome-message">
            <div class="row col-xs-12 justify-content-center">
                <?php if ($config_sub_homepage['image'] !='') { ?>
                    <div class="image col-md-6" style="background-image: url('<?php echo $config_sub_homepage['image']['sizes']['medium_large']; ?>');"></div>
                <?php } ?>
                <div class="col-md-6 content">
                    <?php if ($config_sub_homepage['title']) : ?>
                        <h1 class="font-weight-semi-bold"><?php echo $config_sub_homepage['title']; ?></h1>
                    <?php endif; ?>
                    <?php if ($config_sub_homepage['text']) : ?>
                        <div class="text">
                            <?php echo $config_sub_homepage['text']; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($config_sub_homepage['registration_button']){  ?>
                    <a class="btn p-y-13 p-x-40 m-t-15 lrm-register" href="#"><?php _e('Register', 'idealbiz'); ?></a>
                    <?php } ?> 
                </div>
            </div>
        </div>
    </section>


    <section class="homepage hidden-desktop m-t-30 m-b-30">
        <div class="container welcome-message">
            <div class="row col-xs-12 justify-content-center " style="margin-left:17px;margin-right:0px;">
                <?php if ($config_sub_homepage['image']) : ?><br>
                    <div  class="image col-md-6" style="margin-left:0px;width:100%;height:300px;background-image: url('<?php echo $config_sub_homepage['image']['sizes']['medium_large']; ?>');"></div>
                <?php endif; ?>
                <div class="col-md-6 content" Style="margin-left:0px;">
                    <?php if ($config_sub_homepage['title']) : ?>
                        <h1 class="font-weight-semi-bold"><?php echo $config_sub_homepage['title']; ?></h1>
                    <?php endif; ?>
                    <?php if ($config_sub_homepage['text']) : ?>
                        <div class="text">
                            <?php echo $config_sub_homepage['text']; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($config_sub_homepage['registration_button']){  ?>
                    <a class="btn p-y-13 p-x-40 m-t-15 lrm-register" href="#"><?php _e('Register', 'idealbiz'); ?></a>
                    <?php }?>
                </div>
            </div>
        </div>
    </section>

<?php endif; ?>



<!-- INICIO DOS PAISES -->
<?php if ($config_sub_homepage['flag_contry']) {  ?>
    <?php get_template_part('/elements/paises');?> 
<?php } ?>
<!-- FIM DOS PAISES -->

<div class="container text-center">
        <h1 class="m-h2 text-xs-left" Style= "text-align:center;">
        <?php  
        if (get_field('title_sub_homepage')){ 
            echo $titulo; 
           }?>
            <div class="d-inline-block hidden-desktop"><!-- <?php infoModal(get_field('business_option_description')); ?> --></div>
        </h1>
        
    </div>


<div class="container">
<?php the_content(); ?> 
</div>   
<section class="homepage <?php if ($business_background_image) echo 'background-image'; ?>" style="<?php if ($business_background_image) { ?>background-image: url('<?php echo $business_background_image['url']; ?>'); <?php } ?>">
  
<!-- INICIO NOVO CODIGO SLIDE COOKIES -->

  <?php if($button_options){?> 
    <?php
    if($type_of_display_of_cookies == true){
        get_template_part('/elements/cookies');
        
    }else{
        get_template_part('/elements/static_cookies');
    }
    ?>
  <?php }?>
<!-- FIM NOVO CODIGO SLIDE COOKIES -->



<div ><?php get_post_field('post_content', $pageid);?></div>

    <div class="container text-center m-t-30">
    <?php echo get_field('content_after_mosaic');?>
        <?php if(get_field('business_option_description')){?>
            <h3 class="font-weight-semi-bold m-b-20">
                <?php echo get_field('business_option_description');?>
            </h3>
        <?php }?>
    </div>
</section>


<!-- ///INICIO PARCEIROS -->
<?php
$conj_val = '';
$cl_varParceiro = new Conj_Var_Member();
$cl_varParceiro->Var_Member($conj_val);
    if($cl_varParceiro->show_section_member){
        include(MY_THEME_DIR.'elements/silider_parceiros.php');
    }   
wp_reset_postdata();
 ?>
<!-- ///FIM PARCEIROS -->

<!-- INICIO SEGUNDA SECÇÃO COOKIES -->
<?php if($mostrar_cookie_extra_1){ ?>

<?php
    $slide_mobile = wp_is_mobile();
      include(MY_THEME_DIR.'elements/cookies.php');
      wp_reset_postdata();
?>
 <?php }?>
<!-- FIM SEGUNDA SECÇÃO COOKIES -->


<!-- INICIO MEMBROS -->
<?php
$conj_val = '_3';
$cl_varParceiro = new Conj_Var_Member();
$cl_varParceiro->Var_Member($conj_val);
    if($cl_varParceiro->show_section_member){
        include(MY_THEME_DIR.'elements/silider_membros.php');
    }   
wp_reset_postdata();
 ?>



<!-- INICIO DOS LISTINGS -->
<?php
$id_listing = '';
$conj_val = '';
$cl_varlisting = new Conj_var_listing();
$cl_varlisting->Var_listing($conj_val);
    if($cl_varlisting->slide_listing){
    include(MY_THEME_DIR.'elements/silider_listing.php');
    }
wp_reset_postdata();
$id_listing = '_1';
$conj_val = '_1';
$cl_varlisting = new Conj_var_listing();
$cl_varlisting->Var_listing($conj_val);
    if($cl_varlisting->slide_listing){
    include(MY_THEME_DIR.'elements/silider_listing.php');
    }
wp_reset_postdata();
$id_listing = '_2';
$conj_val = '_2';
$cl_varlisting = new Conj_var_listing();
$cl_varlisting->Var_listing($conj_val);

    if($cl_varlisting->slide_listing){
    include(MY_THEME_DIR.'elements/silider_listing.php');
    }
wp_reset_postdata();

//MEMBROS
$conj_val = '_1';
$cl_varParceiro = new Conj_Var_Member();
$cl_varParceiro->Var_Member($conj_val);
    if($cl_varParceiro->show_section_member){
        include(MY_THEME_DIR.'elements/silider_membros.php');
    }   
wp_reset_postdata();
?>

<!-- INICIO MAPA MEMBRO -->
<?php if($show_member_map){ ?>
<div style="height: 720px;">

<?php
        
      include(MY_THEME_DIR.'elements/member-map.php');
?>

</div>
 <?php }?>
<!-- FIM MAPA MEMBRO -->

<?php


$id_listing = '_3';
$conj_val = '_3';
$cl_varlisting = new Conj_var_listing();
$cl_varlisting->Var_listing($conj_val);
    if($cl_varlisting->slide_listing){
    include(MY_THEME_DIR.'elements/silider_listing.php');
    }
wp_reset_postdata();
?>
<!-- FINAL DOS LISTINGS -->

<?php 

$conj_val = '_2';
$cl_varParceiro = new Conj_Var_Member();
$cl_varParceiro->Var_Member($conj_val);
    if($cl_varParceiro->show_section_member){
        include(MY_THEME_DIR.'elements/silider_membros.php');
    }   
wp_reset_postdata();

?>

<!--  INICIO PRODUTOS DOS LISTINGS -->



<div class="container p-t-20">
<?php if ($mostrar_produtos){?>
<?php  if($title_section_products){ ?>          
    <div class="row_left hidden-mobile">
    <h1 class="text-left row_tittle_desktop w-100 m-b-1 " ><?php echo $title_section_products; ?></h1>
    </div>
    <div class="row_center hidden-desktop">
    <h1 class="text-center row_tittle_mpbile w-100 m-b-11 " style="font-size:2em !important;" ><?php  echo $title_section_products; ?></h1>
    </div>    
    <?php }?>
    <?php  
    if($view_all_products){ ?>  
    <div class="row_left text-left hidden-mobile" >
    <?php echo '<a class="btn-blue" href="'.$select_page_all_product->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div>
    <div class="row_center text-center hidden-desktop" >
    <?php echo '<a class="btn-blue" href="'.$select_page_all_product->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div> 
    <?php }?>
</div>  




    <div class="container justify-content-between align-items-center">
        <?php 
        if ($categorias_de_pordutos && $mostrar_produtos){
        echo do_shortcode ('[woopspro_products_slider cats="'.$categorias_de_pordutos.'" slide_to_show="'.$quantidade_de_produtos.'"]'); 
        }else{
            echo "<div id='alerta'>";
            echo _e('Precisa Pelo menos escolher uma categoria para exibir os produstos');
            echo "</div>";
        }
        ?>
    </div>

<?php } ?>  

<!--  FINAL PRODUTOS DOS LISTINGS -->



<!-- 1 - INICIO PRODUTOS DOS LISTINGS -->
<div class="container p-t-20">
<?php if ($mostrar_produtos_1){?>
    <?php  if($title_section_products_1){ ?>          
    <div class="row_left hidden-mobile">
    <h1 class="text-left row_tittle_desktop w-100 m-b-1 " ><?php echo $title_section_products_1; ?></h1>
    </div>
    <div class="row_center hidden-desktop">
    <h1 class="text-center row_tittle_mpbile w-100 m-b-11 " style="font-size:2em !important;" ><?php  echo $title_section_products_1; ?></h1>
    </div>    
    <?php }?>
    <?php  
    if($view_all_products_1){ ?>  
    <div class="row_left text-left hidden-mobile" >
    <?php echo '<a class="btn-blue" href="'.$select_page_all_product_1->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div>
    <div class="row_center text-center hidden-desktop" >
    <?php echo '<a class="btn-blue" href="'.$select_page_all_product_1->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div> 
    <?php }?>
</div>  

    <div class="container justify-content-between align-items-center">
        <?php 
        if ($categorias_de_pordutos_1 && $mostrar_produtos_1){
        echo do_shortcode ('[woopspro_products_slider cats="'.$categorias_de_pordutos_1.'" slide_to_show="'.$quantidade_de_produtos_1.'"]'); 
        }else{
            echo "<div id='alerta'>";
            echo _e('Precisa Pelo menos escolher uma categoria para exibir os produstos');
            echo "</div>";
        }
        ?>
    </div>

<?php } ?>  

<!-- 1 - FINAL PRODUTOS DOS LISTINGS -->

<!-- 2 - INICIO PRODUTOS DOS LISTINGS -->
<div class="container p-t-20">
<?php if ($mostrar_produtos_2){?>
    <?php  if($title_section_products_2){ ?>          
    <div class="row_left hidden-mobile">
    <h1 class="text-left row_tittle_desktop w-100 m-b-1 " ><?php echo $title_section_products_2; ?></h1>
    </div>
    <div class="row_center hidden-desktop">
    <h1 class="text-center row_tittle_mpbile w-100 m-b-11 " style="font-size:2em !important;" ><?php  echo $title_section_products_2; ?></h1>
    </div>    
    <?php }?>
    <?php  
    if($view_all_products_2){ ?>  
    <div class="row_left text-left hidden-mobile" >
    <?php echo '<a class="btn-blue" href="'.$select_page_all_product_2->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div>
    <div class="row_center text-center hidden-desktop" >
    <?php echo '<a class="btn-blue" href="'.$select_page_all_product_2->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div> 
    <?php }?>
</div>  

    <div class="container justify-content-between align-items-center">
        <?php 
        if ($categorias_de_pordutos_2 && $mostrar_produtos_2){
        echo do_shortcode ('[woopspro_products_slider cats="'.$categorias_de_pordutos_1.'" slide_to_show="'.$quantidade_de_produtos_2.'"]'); 
        }else{
            echo "<div id='alerta'>";
            echo _e('Precisa Pelo menos escolher uma categoria para exibir os produstos');
            echo "</div>";
        }
        ?>
    </div>

<?php } ?>  

<!-- 2 - FINAL PRODUTOS DOS LISTINGS -->


<!-- INICIO POSTS -->
<?php if ($slider_content){?>



    <div class="container p-t-20">

    <?php  if($title_section_slide_post){ ?>          
    <div class="row_left hidden-mobile">
    <h1 class="text-left row_tittle_desktop w-100 m-b-1 " ><?php echo $title_section_slide_post; ?></h1>
    </div>
    <div class="row_center hidden-desktop">
    <h1 class="text-center row_tittle_mpbile w-100 m-b-11 " style="font-size:2em !important;" ><?php  echo $title_section_slide_post; ?></h1>
    </div>    
    <?php }?>
    <?php  
    if($view_all_posts){ ?>  
    <div class="row_left text-left hidden-mobile" >
    <?php echo '<a class="btn-blue" href="'.$select_page_all_posts->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div>
    <div class="row_center text-center hidden-desktop" >
    <?php echo '<a class="btn-blue" href="'.$select_page_all_posts->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div> 
    <?php }?>
</div>  <br/><br/>
    

<div class="container">
    
    <?php if($cl_model_slide=='true'){
        echo do_shortcode('['.$type_slide.' limit="'.$the_amount.'" design="'.$design.'" category="'.$post_category_slider.'" show_author=”false” show_date="false" media_size="full" post_type="post" dots="'.$show_dots.'"]' );
    }else{
        echo do_shortcode('[smart_post_show id="'.$cl_shortcode.'"]');
    }
   ?>
</div>

<?php } ?>
<!-- FIM POSTS -->



<?php

//$time_end = microtime(true);
//$execution_time = ($time_end - $time_start);
//echo '<b>Total Execution Time:</b> '.$execution_time.' secs';

?>

<?php //whiteBackground(); 

?>
</section>



<!-- //INICIO cod SLIDE DE LISTING -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.jquery.min.js"></script>

<script>
       

    jQuery(document).ready(($) => {
        

        $(window).bind( 'orientationchange', function(){            
            console.log('window resized..');
            this.location.reload(false);
            

                            
        });
        
        var size_screen = $(window).width();
        var divWidth = $(".container").width();

        /* alert('Tela '+size_screen+' Div '+divWidth); */

        
        if (window.matchMedia("(max-width: 767px)").matches){
            var px_slider_Per_View_cookies = 278; //initiate as false
            var px_slider_Per_View_slider = 330;  //initiate as false
            $('.expert-card').attr('style', 'width: 300px !important');            
        /* alert('A '+px_slider_Per_View_cookies); */
        }else {
            var px_slider_Per_View_cookies = 235; //initiate as false
            var px_slider_Per_View_slider = 345;  //initiate as false
            $('.b-opts-inner #Cookie_2').removeClass('w-278px').addClass('w-200px');
        /* alert('B '+px_slider_Per_View_cookies); */
        }
        var px_slider_Per_View_partner = 160; //initiate as false
        var px_slider_Per_View_listing = 278; //initiate as false        e

        var slider_Per_View_cookies = Math.round((divWidth/px_slider_Per_View_cookies));
        var slider_Per_View_listing = Math.round((divWidth/px_slider_Per_View_listing));
        var slider_Per_View_partner = Math.round((divWidth/px_slider_Per_View_partner));
        var slider_Per_View_slider = Math.round((divWidth/px_slider_Per_View_slider));

        /* alert('Cookies '+slider_Per_View_cookies+' Listing '+slider_Per_View_listing+' Partner '+slider_Per_View_partner+' Member '+slider_Per_View_slider); */

       
        var cl_slider_cookies = new Swiper('.cl_slider_cookies', {           
        nextButton: '.swiper-listing-next',
        prevButton: '.swiper-listing-prev',
        slidesPerView: slider_Per_View_cookies,
        paginationClickable: true,
        lazyLoading: true,
        spaceBetween: 1,
        /* flipEffect: {
        rotate: 30,
        slideShadows: true,
        }, */
        simulateTouch: true,
        loopedSlides: 6,
        autoplay: {
            delay: 50,
            disableOnInteraction: true,
        },
        speed: 15000,
        loop: true,

        freeMode: {
            enabled: false,
        },
        /* mousewheel: {
            sensitivity: 1,
        }, */
        
    
        });

        
        //Todos Jquery do Listings

        var cl_slider_listing = new Swiper('.cl_sld_listing', {           
        slidesPerView: slider_Per_View_listing,     
        lazyLoading: true,
        navigation: {
          nextEl: "#swiper-button-next",
          prevEl: "#swiper-button-prev",
        }            
        });
        var cl_slider_listing_1 = new Swiper('.cl_sld_listing_1', {           
        slidesPerView: slider_Per_View_listing,     
        lazyLoading: true,
        navigation: {
            nextEl: "#swiper-button-next_1",
            prevEl: "#swiper-button-prev_1",
        }            
        }); 
        var cl_slider_listing_2 = new Swiper('.cl_sld_listing_2', {           
        slidesPerView: slider_Per_View_listing,     
        lazyLoading: true,
        navigation: {
            nextEl: "#swiper-button-next_2",
            prevEl: "#swiper-button-prev_2",
        }            
        });        
        var cl_slider_listing_3 = new Swiper('.cl_sld_listing_3', {           
        slidesPerView: slider_Per_View_listing,     
        lazyLoading: true,
        navigation: {
            nextEl: "#swiper-button-next_3",
            prevEl: "#swiper-button-prev_3",
        }            
        });
        var cl_slider_listing_4 = new Swiper('.cl_sld_listing_4', {           
        slidesPerView: slider_Per_View_listing,     
        lazyLoading: true,
        navigation: {
            nextEl: "#swiper-button-next_4",
            prevEl: "#swiper-button-prev_4",
        }            
        }); 


        
            var cl_slider = new Swiper('.cl_slider', {
            slidesPerView: slider_Per_View_slider,
            paginationClickable: true,
            lazyLoading: true,
            spaceBetween: 5,
            flipEffect: {
            rotate: 30,
            slideShadows: false,
        },
            simulateTouch: true,
            loopedSlides: 6,
            autoplay: {
                delay: 200,
                disableOnInteraction: false,
            },
            speed: 60000,
            loop: true,

            freeMode: {
                enabled: false,
            },
            /* mousewheel: {
                sensitivity: 1,
            }, */
                
            
        }); 



        


        var cl_slider_partner = new Swiper('.cl_slider_partner', {
        slidesPerView: slider_Per_View_partner,
        paginationClickable: false,
        lazyLoading: true,
        spaceBetween: 2,
        flipEffect: {
        rotate: 30,
        slideShadows: false,
        },
        simulateTouch: true,
        loopedSlides: 6,
        autoplay: {
            delay: 200,
            disableOnInteraction: false,
        },
        speed: 60000,
        loop: true,

        freeMode: {
            enabled: false,
        },
        /* mousewheel: {
            sensitivity: .5,
        }, */

        }); 

        


        /* $(".locality-tab").on("click",function(){ 
        setTimeout(function () {
        newSwiper.update();
        }, 400);
        }); */

});   

</script>


<!-- //FINAL cod SLIDE DE PARCEIROS -->



<?php if(get_field('newsletter_shortcode')){ ?>
<!-- <div class="newsletter m-t-50 md-m-t-0">
    <div class="container medium-width">
        <div class="row">
            <div class="col-md-3 d-flex align-items-center">
                <h1><?php _e('Alerts by email', 'idealbiz'); ?></h1>
            </div>
            <div class="col-md-3 d-flex align-items-center m-b-15 md-m-b-0">
                <span class="text"><?php echo __('Receive by email business opportunities even before they are published on the website.', 'idealbiz'); ?></span>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <div class="input-group">
                    <?php echo do_shortcode(get_field('newsletter_shortcode')); ?>
                </div>
            </div>
        </div>
    </div>
</div> -->
<?php } ?>
<?php
get_footer();
?>
<style>

    .homepage .countries-slider .swiper-slide.rectangle-square .content {
    color: #fff;
    background-color: rgba(0,0,0,.2);
}
.row {
    margin-right: 0px;
    margin-left: 0px !important;
}

.sp-pcp-post .sp-pcp-title, .sp-pcp-post .sp-pcp-title a{
max-width: 31ch;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.sp-pcp-post-content p{
    display : none;
}

.sp-pcp-post .sp-pcp-post-content .sp-pcp-readmore {
    text-align: center;
}

.slick-slide img{

border-radius:7px;
}

.woocommerce-loop-product__title{
    font-size: 1.3em;
}

.woocommerce-loop-product__title{
    max-width: 25ch;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap; 
}

/* width */
::-webkit-scrollbar {
  width: 17px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #f1f1f1;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555; 
}

/**MAPA DE MEMBRO*/

.link_nome{
        font-size: 1.1em;
        font-weight: 400;
    }

    .image{
        text-align: center;
    }
    .lista_cat a{
        color:#777;
    }

    #map{
        border: 2px solid #f1f1f1;
        border-radius: 5px;
    }

/**RECAPCHA
 *DA SUB ROMEPAGE
 *Newsletter
 */
.wpcf7 .wpcf7-recaptcha iframe {
-webkit-transform: scale(.6);
-ms-transform: scale(.6);
float:left;
}
.newsletter form p {
    margin-bottom: -10px;
}
.rc-anchor-light, #rc-anchor-container {
    background: #939696 !important;
    color: #000;
}
.rc-anchor-normal .rc-anchor-content {
    height: 74px;
    width: 206px;
    background-color: #000 !important;
}
.wpcf7-form-control-wrap {
    margin-left: 12px;
    margin-top: -20px;
}
.input-group-append p{
    margin-left: -43px;
}
.newsletter {
    padding: 13px !important;
}
#bnt_parceiros{
    color:#fff;
    background-color: #353535;
    font-size: large;
    padding: 15px;
    border-radius: 25px;
    width: 250px;
}

#bnt_parceiros a{
    color:#fff;
}
</style>