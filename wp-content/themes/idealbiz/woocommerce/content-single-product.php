<?php


/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );



if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'woocommerce_before_single_product_summary' );

	?>
<div class="site-blocks d-flex flex-row flex-wrap justify-content-start"> 
	<div class="img_produto black--color font-weight-medium">
		<?php
		global $product;

		

		$cl_short_description = $product->short_description;

		$featured_image = wp_get_attachment_url( get_post_thumbnail_id($product_id));
		?> 
	<img width="450" height="450" src="<?php echo $featured_image; ?>" class="wp_post-image">

	
	</div>
	<div class="align-middle m-t-50 m-l-30">
	<div class="summary entry-summary">
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );

		wp_reset_postdata();
		?>
	</div>
	</div>

</div>


	<div class="p-t-30">
	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10black--color font-weight-medium
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
	
	</div>
</div>

<!-- INICIO NOVO CODIGO SLIDE COOKIES -->



<div class="d-none">
    <?php 
	include(MY_THEME_DIR.'elements/cookies.php');
	wp_reset_postdata();
    ?>
</div>
<?php if ($business_options){?>
<div class="container">

<div class="row_left p-t-20">
<h1 class="text-left row_tittle_desktop w-100 m-b-1 hidden-mobile" style="font-size:1.7em !important;" ><?php  _e( 'Related Service&hellip;', 'idealbiz' ) ; ?></h1>
</div>

<div class="row_center p-t-20">
<h1 class="row_tittle_mobile m-b-11 hidden-desktop" style="text-align:center;font-size:1.7em !important;" ><?php  _e( 'Related Service&hellip;', 'idealbiz' ) ; ?></h1>
</div>


    <?php 
	include(MY_THEME_DIR.'elements/cookies.php');
	wp_reset_postdata();
    ?>
</div>
<?php }?>

<!-- FIM NOVO CODIGO SLIDE COOKIES -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.jquery.min.js"></script>

<script>
       

    jQuery(document).ready(($) => {

        $(window).bind( 'orientationchange', function(){            
            console.log('window resized..');
            this.location.reload(false);
            /* alert($(window).width()+'px de Largura - Após rotacionar...'); */ 
        });

        var slider_Per_View_cookies = 6; //initiate as false
        var slider_Per_View_partner = 9; //initiate as false
        var slider_Per_View_listing = 5; //initiate as false        
        var slider_Per_View_slider = 4; //initiate as false

        if (($(window).width() >= 200) && ($(window).width() <= 329 )){            
            slider_Per_View_cookies = 1;
            slider_Per_View_listing = 1;
            slider_Per_View_partner = 1;
            slider_Per_View_slider = 1;
        }
        if (($(window).width() >= 330)&&($(window).width() <= 400)){            
            slider_Per_View_cookies = 1;
            slider_Per_View_listing = 1;
            slider_Per_View_partner = 2;
            slider_Per_View_slider = 1;
        }
        if (($(window).width() >= 401)&&($(window).width() <= 767)){            
            slider_Per_View_cookies = 1;
            slider_Per_View_listing = 1;
            slider_Per_View_partner = 3;
            slider_Per_View_slider = 1;
        }
        if (($(window).width() >= 768)&&($(window).width() <= 1024)){            
            slider_Per_View_cookies = 2;
            slider_Per_View_listing = 3;
            slider_Per_View_partner = 3;
            slider_Per_View_slider = 2;
        }
        if (($(window).width() >= 1025)&&($(window).width() <= 1450)){            
            slider_Per_View_cookies = 5;
            slider_Per_View_listing = 5;
            slider_Per_View_partner = 9;
            slider_Per_View_slider = 4;
        }
        if ($(window).width() >= 1451){            
            slider_Per_View_cookies = 5;
            slider_Per_View_listing = 5;
            slider_Per_View_partner = 9;
            slider_Per_View_slider = 4;
        }

		var cl_slider_listing = new Swiper('.cl_slider_cookies', {           
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
            enabled: true,
        },
        mousewheel: {
            sensitivity: 1,
        },
        
    
        });
	});	
	</Script>


<style>

.woocommerce div.product div.images img {
    display: block;
    width: 100%;
    height: auto;border: 1px solid #000000;
    width: 48%;
    clear: none;
}

.woocommerce #content div.product .woocommerce-tabs, 
.woocommerce div.product .woocommerce-tabs, 
.woocommerce-page #content div.product .woocommerce-tabs, 
.woocommerce-page div.product .woocommerce-tabs {
    clear: both;
}

.woocommerce div.product {
    margin-bottom: 0;
    position: relative;
}
.fieldset{
	display: none;
}
.entry-summary {
	width: 100%;
    height: auto;
}
.wp_post-image{
	max-width: 540px;
	width: 100%;
    height: auto;
}
.product .woocommerce-product-gallery {
	 width: 100%;
    height: auto;
}
.single_add_to_cart_button{
	line-height: 1em;
    padding-left: 20px;
    padding-right: 20px;
    padding: .692em 1.125em;
    display: inline-block;
    color: #f3f5fa;
    border-radius: 3px;
    transition: all .2s ease;
    background-color: #E28C23;
    border: 0;
	margin-top: 15px;
	font-size: medium;
	font-weight: 700;
}

.input-text{
	padding: 10px;
	margin: 5px;
	font-size: medium;
	max-width: 70px;
	text-align: center;
}

.variations select{
	padding: 10px;
	margin: 5px;
	font-size: medium;
}

.woocommerce-Price-amount{
	font-size: large;
	max-width: 5px;
	font-weight: 700;
}

.product_title h1{
	font-size: 3em;
}

.row {
  width: 100%;
  margin: 0 auto;
}
.block {
  width: 100px;
  float: left;
}

.added_to_cart{
	line-height: 1em;
    padding-left: 20px;
    padding-right: 20px;
    padding: .692em 1.125em;
    display: inline-block;
    color: #f3f5fa;
    border-radius: 3px;
    transition: all .2s ease;
	background: #E28C23;
    border-width: 0;
	margin-left: 20px;
	font-size: medium;
	font-weight: 700;
}

/** TAB */

ul.tabs {
    list-style: none;
    padding: 0 0 0 1em;
    margin: 0 0 1.618em;
    overflow: hidden;
    position: relative;
	font-size: larger;
	font-weight: 700;
}
 ul.tabs li{
    display: inline-block;
}

ul.tabs li.active {
    background: #fff;
    z-index: 2;
    border-bottom-color: #fff;
}

ul.tabs li {
    border: 1px solid #d3ced2;
    background-color: #ebe9eb;
    display: inline-block;
    position: relative;
    z-index: 0;
    border-radius: 4px 4px 0 0;
    margin: 0 -5px;
    padding: 0 1em;
}
ul.tabs{
    content: " ";
    width: 100%;
    bottom: 0;
    left: 0;
    border-bottom: 1px solid #d3ced2;
    z-index: 1;
	list-style: none;
    padding: 0 0 0 1em;
    margin: 0 0 1.618em;
    overflow: hidden;
    position: relative;
}
ul.tabs li.active {
    background: #fff;
    z-index: 2;
    border-bottom-color: #fff;
}
ul.tabs li.active {
    background: #fff;
    z-index: 2;
    border-bottom-color: #fff;
}
ul.tabs li.active a{
	display: inline-block;
    padding: .5em 0;
    font-weight: 700;
    color: #515151;
    text-decoration: none;
}
.woocommerce-breadcrumb, 
.product_meta, 
.woocommerce-Tabs-panel--description h2{
	display:none;

}
.button .product_type_simple a{
	display:none;
	color:#E28C23;
}

.size-woocommerce_thumbnail{
	width: 190px !important;
	height: 190px !important;
	border-radius: 10px;
}

.woocommerce-loop-product__title{
	font-size: 1em;
}

.woocommerce-product-details__short-description{
	width: 455px;
	text-align: justify;
  	text-justify: inter-word;
	font-size: 1.38em;
    font-weight: 400;
}

.product_type_simple{
	color:#ffffff;
	background: #14307b;
	border-width: 0;
	line-height: 1em;
	font-weight: 700;
	display: inline-block;
	border-radius: 3px;
	padding: .692em 1.125em;
	}

.img_produto img{

	border-radius:7px;
}

.wp_post-image {
    max-width: 270px;

}

/* To hide the additional information tab */
li.additional_information_tab {
    display: none !important;
}


</style>

