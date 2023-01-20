<?php // Template Name: Homepage v2

//https://idealbiz.io/pt/pt/nova-home-page/


?>

<section class="cl_header">
    <?php get_header(); ?>

    <div class="cl_menu">
        <?php do_action('mostra_menu_superior'); ?>

        <?php
        // detect browser and add browser name as a class in body tag 
        function detect_browser_body_class($classes)
        {
            global $is_lynx, $is_firefox, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
            if ($is_lynx) {
                $classes[] = 'lynx';
            } elseif ($is_gecko) {
                $classes[] = 'firefox';
            } elseif ($is_opera) {
                $classes[] = 'opera';
            } elseif ($is_NS4) {
                $classes[] = 'ns4';
            } elseif ($is_safari) {
                $classes[] = 'safari';
            } elseif ($is_chrome) {
                $classes[] = 'chrome';
            } elseif ($is_IE) {
                $classes[] = 'ie';
                if (preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version)) {
                    $classes[] = 'ie' . $browser_version[1];
                }
            } else {
                $classes[] = 'unknown';
            }
            if ($is_iphone) {
                $classes[] = 'iphone';
            }
            if (stristr($_SERVER['HTTP_USER_AGENT'], "mac")) {
                $classes[] = 'osx';
            } elseif (stristr($_SERVER['HTTP_USER_AGENT'], "linux")) {
                $classes[] = 'linux';
            } elseif (stristr($_SERVER['HTTP_USER_AGENT'], "windows")) {
                $classes[] = 'windows';
            }
            return $classes;
        }
        add_filter('body_class', 'detect_browser_body_class');

        $browser = detect_browser_body_class($classes)[0];
        $os = detect_browser_body_class($classes)[1];


        /* cl_alerta('Navegador '.$browser.' ver '.$browser_version[1].' Sistema OS '.$os); */
        
        ?>


    </div>
</section>
<?php
require_once MY_THEME_DIR . 'library/class-var_slides.php';
//IMAGE HEAD


$rand_img_apple = rand(0, 4);


$image_head_right = get_field('image_head_right');
$link_image_head_right = get_field('link_image_head_right');
$link_image_head_right_window = get_field('link_image_head_right_window');


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
$titulo = get_the_title($post);
//HERANÇA DA SUB HOME PAGE SÓ APARECE NA SUB HOME PAGE.
$button_options = get_field('business_option_buttons');
$transparence = str_replace(',',  '.', get_field('transparence_cookies'));
$color_text_mobile = get_field('color_text_to_mobile');
$type_of_display_of_cookies = get_field('type_of_display_of_cookies');

//COOKIES EXTRA 
$title_cookies_1 = $config_sub_homepage['title_cookie_extra_1'];
$cookies_of_page_1 = $config_sub_homepage['cookies_of_page_1'];
$mostrar_cookie_extra_1 = $config_sub_homepage['show_cookies_1'];
$view_button_all_in_section_1 = $config_sub_homepage['view_button_all_in_section_1'];

//PARCEIROS

$description_section_partner = get_field('description_section_partner');

/* //PRODUTOS
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
$select_page_all_product_2 = $config_sub_homepage['select_page_all_product_2']; */


// SLIDE POSTS
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





$cl_css = get_site_url() . '/wp-content/themes/idealbiz/assets/css/original_sub_home_page.css';


?>

<?php
// CHECA VERSÃO DO ios.
    $version = preg_replace("/(.*) OS ([0-9]*)_(.*)/","$2", $_SERVER['HTTP_USER_AGENT']);
    if ($os == 'iphone' && $version < 11){
    ?>
    <style>
        .aviso_versao{
            position:fixed;
            background-color: #F3D2D6;
            color:red;
            padding:10px;
        }

    </style>

    <div class="aviso_versao">
        <?php echo __('_str Important Notice - For best undistorted display please update your operating system. Its current version is: ','idealbiz'); echo __(' iOS Ver ','idealbiz').$version;?>
    </div>

    <?php
    }
?>
<style type="text/css">
    @import url('<?php echo $cl_css; ?>');
</style>

<?php if ($config_sub_homepage['welcome_message']) : ?>

    <section class="homepage hidden-mobile m-t-30 m-b-30">
        <div class="container welcome-message">
            <div class="row col-xs-12 justify-content-center">
                <?php if ($config_sub_homepage['image'] != '') { ?>
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
                    <?php if ($config_sub_homepage['registration_button']) {  ?>
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
                    <div class="image col-md-6" style="margin-left:0px;width:100%;height:300px;background-image: url('<?php echo $config_sub_homepage['image']['sizes']['medium_large']; ?>');"></div>
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
                    <?php if ($config_sub_homepage['registration_button']) {  ?>
                        <a class="btn p-y-13 p-x-40 m-t-15 lrm-register" href="#"><?php _e('Register', 'idealbiz'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<style>
    img {
        border-radius: 0px;
    }

    .homepage .countries-slider .swiper-slide.rectangle-square .content {
        color: #fff;
        background-color: rgba(0, 0, 0, .2);
    }

    .row {
        margin-right: 0px;
        margin-left: 0px !important;
    }

    .sp-pcp-post .sp-pcp-title,
    .sp-pcp-post .sp-pcp-title a {
        max-width: 31ch;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sp-pcp-post-content p {
        display: none;
    }

    .sp-pcp-post .sp-pcp-post-content .sp-pcp-readmore {
        text-align: center;
    }

    .slick-slide img {

        border-radius: 0px;
    }

    .woocommerce-loop-product__title {
        font-size: 1.3em;
    }

    .woocommerce-loop-product__title {
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

    .link_nome {
        font-size: 1.1em;
        font-weight: var(--font-weight);
    }

    .image {
        text-align: center;
    }

    .lista_cat a {
        color: #777;
    }

    #map {
        border: 2px solid #f1f1f1;
        border-radius: 0px;
    }


    #myVideo video {
        max-height: 645px;
        min-width: 100%;
        top: 0;
        position: fixed;
        object-fit: cover;
        z-index: -1;
        /* filter: brightness(40%); */
    }

    .img_head{
        height: 680px;
    }
    #myVideo{
        height: 675px;
    }




    .texto-img-head {
        display: flex;
        max-width: 1200px;
        margin: auto;
        align-items: center;
        /* DE VIDEO PARA FOTO */
        /* padding-bottom: 198px;*/
        /*padding-bottom: 84px;*/
        padding-top: 0px;
        padding-bottom: 0px;
    }

    .cl-div-img-head {
        /* position: absolute; */ 
        right: 0;
    }

    .cl-img-head img {
        width: 100%;
        max-width: 400px;
        height: auto;

    }

    .cl-div-buscador {
        background-color: #fff;
        padding: 7px;
        margin: 0 auto;

        border-top-left-radius: 31px;
        border-top-right-radius: 31px;
    }

    .cl-buscador {
        position: relative;
        margin-left: auto;
        margin-right: auto;
        left: 0;
        right: 0;
        text-align: center;
        min-width: 1290px;
        max-width: 1290px;
        top:-90px;
    }

    #titulo-cookies-2 {
        margin: 30px 0;
    }

    @media only screen and (max-width: 992px) {
       

        /**TOPO DO SITE */
                .cl_menu {
            margin-top: -29px;
        }


        #myVideo{
            height: 482px;
        }
       
       
        .img_head {
            background-size: contain;
            background-size: 100% 100%;
            margin-bottom: -190px;
        }

        .cl-img-head img {
            width: 100%;
            max-width: 300px;
            height: auto;
        }

        .cl-div-conteudo {
            font-size: x-large;
            text-align: center;
            font-family: var(--font-default), sans-serif;
        }

        .cl-buscador {
            min-width: 768px;
            max-width: 768px;
        }
        .search-form--header .text-global-search input {
        width: 768px;
        }

        .search-form--header .text-global-search input {
            width: 543px !important;
        }
    }

    @media only screen and (max-width: 1024px) {

        .cl-buscador {
            min-width: 900px;
            max-width: 900px;
        }

        .search-form--header .text-global-search input {
            width: 650px !important;
        }
    }

    .cl_section {
        min-width: 1439px;
        margin-top: 5px;
        text-align: center;

    }

    .cl_section div>h3 {
        padding-bottom: 0px;
        line-height: 1.5em;
        font-size: 21px;
        color: #000 !important;
    }


    .cl_big_title {
        text-align: center;
        font-weight: var(--font-weight);
        font-size: 3.45em;
        margin-top: -4px;
        font-family: var(--font-default), sans-serif;
        color: #464b4e;

    }

    .cl_main {
        width: 100%;
        background-color: #ffffff;
    }

    .swiper-wrapper {
        height: auto !important;
    }

    .dropshadow {
        box-shadow: 0 0 0 0 rgba(0, 0, 0, .0), 0 0 0 0 rgba(0, 0, 0, .0);
    }

    .white--background {
        background-color: #fff;
    }

    .cl_menu {
        min-height: 0px;
        background-color: #fff;
        /* background-color: tomato ; */
        z-index: 997
    }

    .cl_header {
        position: fixed;
        width: 100vw;
        z-index: 2;
    }


    /** BLOCO BUSCADOR */
    .search-form--header .text-global-search input {
        height: 50px;
        width: 1000px;
        font-size: 1.7em;
        padding-left: 50px;
    }

    .filters-container .text-global-search:before,
    .search-form--header .text-global-search:before {
        display: none;
    }

    .search-form--header .text-global-search ::placeholder {
        color: #c4c4c4;
        opacity: 1;
    }

    .search-form--header .text-global-search :-ms-input-placeholder {
        color: #c4c4c4;
    }

    .search-form--header .text-global-search ::-ms-input-placeholder {
        color: #c4c4c4;
    }

    .border-blue {
        border: 1px solid #005882;
    }

    .b-t-l-r {
        border-radius: 40px;
    }

    .search-form--header .btn-blue {
        background: #005882;
        border-width: 0;
        border-radius: 40px;
        font-size: medium;
        font-family: var(--font-default), sans-serif;
    }


    /** BLOCO FORM*/

    .main-form {
        display: flex;
        justify-content: space-around;
        margin-bottom: 26px;
        border-radius: 0px;
    }

    .main-form .content {
        /* background-color: tomato; */
        text-align: left;
        min-width: 50%;
        color: #fff;
        font-family: var(--font-default), sans-serif;
        margin-left: 50px;
    }

    .main-form .content .title {
        font-size: medium;
    }

    .main-form .content a {
        color: #fff;
        text-decoration-line: none;
    }

    .main-form .content a:hover {
        color: #fff;
        text-decoration-line: underline;
    }

    .main-form .sub_content {
        font-size: medium;
        color: #fff;
    }


    .main-form .form {

        max-width: 50%;
        margin-right: 50px;

    }

    .main-form .form,
    .main-form .content {
        padding-top: 50px;

    }

    .main-form .form input,
    .main-form .form textarea {
        font-family: var(--font-default), sans-serif;
        background-color: #fff;
        color: #00659b;
        border-color: #fff;
        font-size: 1.4em;
        height: 2.5em;
    }

    .main-form .form ::placeholder {
        color: #555;
        font-size: 1.1em;
        font-weight: var(--font-weight);
        font-family: 'Roboto', sans-serif;
        font-style: italic;
    }

    .main-form .form input[name="first_name"] {
        width: 17em;
    }

    .main-form .form textarea {
        height: 5em;
    }

    .main-form .cl_big_title {
        color: #ffffff;
        text-align: left;
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
        margin-top: 0px;
        font-family: var(--font-default), sans-serif;
        margin-bottom: 30px;
    }

    .main-form .acf-form-submit p {
        max-width: 105px;
        float: right;
        margin-top: -40px;
    }

    .main-form .form input[type="submit"] {
        background-color: #fff;
        color: #005882;
        margin-top: 45px;
    }

    .main-form .form .rc-anchor .rc-anchor-normal .rc-anchor-light {
        background-color: #005882;
        color: #fff;
    }

    .main-form .form .rc-anchor-light {
        background: #005882;
        color: #fff;
    }

    .wpcf7 form.failed .wpcf7-response-output,
    .wpcf7 form.aborted .wpcf7-response-output {
        border-color: #dc3232;
        color: #fff;
    }

    .wpcf7 form.sent .wpcf7-response-output{
        color:#fff;
    }


    /** BLOCO COMUNIDADE*/
    .main_comunidade {
        /* background-color: #fff; */

    }

    .main_comunidade .cl_big_title {
        padding-top: 45px;
        font-family: var(--font-default), sans-serif;
    }

    .comunidade {
        display: flex;
        justify-content: space-around;
        align-items: center;
        /* background-color: #649FD5; */
        padding: 50px;

    }

    .comunidade .img {
        /* background-color: tomato; */
    }

    .comunidade .conteudo {
        padding-bottom: 50px;
        /* background-color: greenyellow; */
    }

    .comunidade .botoes {
        /* background-color: pink; */

    }

    .cl_botoes {
        /* background-color:tomato; */
        display: flex;
        align-items: center;
        margin-left: 87px;
    }

    .cl_botao_titulo {
        /* background-color:green; */
        padding-top: 100px;
        margin-left: 25px;
    }

    .cl_botao_titulo a {
        color: #ffffff;
    }

    .cl_titulo_botao {
        /* background-color:#649FD5; */
    }

    .comunidade .dir .botoes {
        display: flex;
    }

    .comunidade .dir {
        padding-left: 35px;

    }

    .comunidade .dir .botoes button {
        width: 95%;
        background-color: #464b4e;
        font-size: medium;
        padding: 7px;
        border-radius: 0px;
        /* min-width: 170px; */
        /*min-height: 82px; */
    }

    .comunidade .dir .botoes button>a {
        color: #fff;
        line-height: 1.4em;
    }

    .comunidade .dir ul {
        list-style-type: none;
        margin: 30px auto;
        text-align: left;
        font-weight: var(--font-weight);
        font-size: medium;
    }

    .comunidade ul li {
        margin-left: -25px;
    }

    .comunidade .conteudo h3 {
        text-align: left;
    }
    .comunidade .conteudo h1{
            color:#464b4e;
    }

    /** BLOCO MARKET PLACE*/
    .mkt_place {
        display: flex;
        justify-content: center;
        column-gap: 20px;
        margin-left: 22px
    }

    .mkt_place img {
        /* background-color:yellow ; */
        width: 100%;
    }

    .mkt_place.titulo {
        /* background-color:greenyellow ; */
        width: 100%;
        font-family: var(--font-default), sans-serif;
        color: #005882;
        font-size: 18px;
        font-weight: var(--font-weight);
        padding-top: 20px;
        text-align: left;
    }

    .mkt_place .link {
        /* background-color:royalblue ; */
        width: 100%;
        margin-top: 20px;
    }

    .mkt_place .link a {
        /* background-color:royalblue ; */
        font-size: medium;
    }

    .mkt_place.link {
        font-size: 14px;
        padding-top: 10px;
        text-align: left;
    }

    .mkt_place.link a {
        color: #c4c4c4;
    }

    .cl_text_flutua {
        position: absolute;
        background-color: #ffffff;
        margin-left: 14px;
        margin-top: 14px;
        font-family: var(--font-default), sans-serif;
        font-size: 1.8em;
        font-weight: var(--font-weight);
        color: #005882;
        border-radius: 0px;
        padding-left: 15px;
        padding-right: 15px;
        text-align: center;
    }

    /** BLOCO SERVIÇOS INTEGRADOS*/
    .integrado {
        display: flex;
        justify-content: center;
        column-gap: 20px;
        margin-bottom: 30px;
    }

    .integrado img {
        /* background-color:yellow ; */
        width: 100%;
    }

    .integrado .titulo {
        /* background-color:greenyellow ; */
        width: 100%;
        font-family: var(--font-default), sans-serif;
        color: #005882;
        font-size: 18px;
        font-weight: var(--font-weight);
        padding-top: 20px;
        text-align: left;
    }

    .integrado .link {
        /* background-color:royalblue ; */
        width: 100%;
    }

    .integrado .link {
        font-size: 14px;
        padding-top: 10px;
        text-align: left;
    }

    .integrado .link a {
        color: #c4c4c4;
    }

    /**BLOCO APÓS O VIDEO */
    .nh_content1 {
        display: flex;
        /* background-color:teal ; */
        justify-content: center;
        align-items: center;
        margin-top: 70px;
    }

    .nh_content1_esq {
        width: 45%;
        /* background-color:tomato  */
        ;
        margin-left: 40px;
    }

    .nh_content1_dir {
        width: 45%;
        /* background-color:greenyellow ; */

    }

    .nh_content1_esq .titulo {
        color: #649FD5;
        font-size: 4.5em;
        font-weight: var(--font-weight);
        text-align: left;
        line-height: 1.1em;
        font-family: var(--font-default), sans-serif;
    }

    .nh_content1_esq .conteudo {
        color: #000;
        font-size: 1.2em;
        font-weight: var(--font-weight);
        margin-top: 25px;
        text-align: left;
        font-family: var(--font-default), sans-serif;
    }

    .nh_content1_dir img {
        width: 550px;
        padding-top: 20px;
    }

    .nh_content1_dir a {
        font-weight: var(--font-weight);
        font-size: large;

    }

    .nh_content1_dir .link {
        /* background-color: #1B7A87; */
        width: 500px;
        margin-top: 35px;
        margin-left: 80px;
        text-align: right;

    }

    /**BLOCOS DE SERVIÇO */
    .nh_sevicos {
        display: flex;
        justify-content: center;
        /* background-color: tomato; */
        padding-top: 20px;
        padding-bottom: 20px;

    }

    .nh_bloco_sevicos {
        width: 215px;
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .nh_bloco_sevicos div {
        /* background-color:yellowgreen; */
    }

    .nh_titulo {
        font-family: var(--font-default), sans-serif;
        color: #000;
        font-size: 18px;
        font-weight: var(--font-weight);
        padding-top: 20px;
        text-align: center;
    }

    .nh_icon {
        color: #005882;
        font-size: 1.7em;
        height: 2.2em;
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: center;
    }

    .nh_icon .dashicons {
        font-size: 1.9em;
        text-align: left;
        margin-left: -17px;

    }

    .nh_link {
        font-size: 14px;
        padding-top: 10px;
        text-align: left;
    }

    .nh_link a {
        color: #c4c4c4;
    }

    @media only screen and (max-width: 768px) {


        /** BLOCO FORM mobile*/

        .cl_big_title, .comunidade h1 , .cl_section h1{
        text-align: left;
        font-weight: var(--font-weight);
        font-size: 2.1em;
        margin-top: -4px;
        font-family: var(--font-default), sans-serif;
        color: #464b4e;
        }

        .cl_big_title{
            padding-left:10px;
        }



        .main-form {
            display: block;
            /* background-color: #00659b; */
            margin-bottom: 150px;
            border-radius: 0px;
        }


        .main-form .form textarea {
            height: 6.5em;
        }

        .main-form .form {
            max-width: 80%;
            margin: 10px auto;
            min-height: 470px;
        }

        .main-form .form input[name="first_name"] {
            width: 100%;
        }

        .form-row {
            display: block;
        }

        /** BLOCO COMUNIDADE mobile*/
        .comunidade,
        .comunidade .dir .botoes {
            display: unset;
        }

        .main_sev_prof .comunidade{
            display: flex;
            flex-direction: column-reverse;
            padding:0px;

        }

        .comunidade .dir .botoes button {
            margin: 10px auto;
            max-width: 85%;
            min-height: auto;
        }

        .comunidade .esq img {
            width: 100%;
            height: auto;
        }

        .comunidade .conteudo {
            padding-top: 50px;
            text-align: center;
            /* background-color: greenyellow; */
        }

        .comunidade .conteudo h3 {
            text-align: center;
        }

        .comunidade .dir {
            padding-right:5px;
            padding-left:5px;
        }

        .comunidade .conteudo{
            padding-top: 5px;
            padding-bottom: 5px;
        }



        /** BLOCO MARKET PLACE mobile*/
        .mkt_place {
            display: block;
        }

        /** BLOCO SERVIÇOS INTEGRADOS*/
        .integrado {
            display: block;

        }

        /**BLOCO APÓS O VIDEO mobile */
        .nh_content1_dir img {
            width: 100%;
            padding-top: 20px;
        }

        .nh_content1_dir .link {
            /* background-color: #1B7A87; */
            width: 100%;
            margin-top: 35px;
            margin-left: 0px;
            text-align: center;

        }

        .nh_content1_esq {
            width: 100%;
            /*  background-color:tomato ; */
            margin-left: 0px;
        }

        .nh_content1_dir {
            width: 100%;
            /*  background-color:greenyellow ; */

        }

        .nh_content1 {
            display: block;
        }

        .nh_content1_esq .titulo {
            color: #649FD5;
            font-size: 3em;
            font-weight: var(--font-weight);
            text-align: left;
            line-height: 1.1em;
        }

        .nh_content1_dir a {
            font-weight: var(--font-weight);
            font-size: 1.2em;

        }

        /**BLOCOS DE SERVIÇO mobile */
        .nh_icon,
        .dashicons,
        .nh_link,
        .nh_titulo {
            text-align: center;
        }

        .nh_sevicos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            /* background-color: tomato; */
            padding-bottom: 20px;
            padding-left: 0px;
            left: 0;
        }

        .nh_bloco_sevicos {
            width: 50%;
            padding-top: 0px;
            padding-bottom: 60px;
            padding-left: 0px;
            padding: 3px;
        }

        .nh_bloco_sevicos div {
            /* background-color:yellowgreen; */
        }

        #bnt_servicos {
            color: #fff;
            background-color: #464b4e;
            font-size: medium;
            padding: 7px;
            border-radius: 0px;
            min-width: 85%;
        }

        #bnt_parceiros {
            color: #fff;
            background-color: #464b4e;
            font-size: medium;
            padding: 7px;
            border-radius: 0px;
            margin-top: 10px;
        }

        /**TOPO DO SITE */
        .cl_menu {
            margin-top: -29px;
        }

        .img_head {
            /* background-size: contain; */
            background-size: auto;
            margin-bottom: 0px;
            background-color: #f2f2f2;
            z-index: 1;
        }

        #myVideo{
            height: 320px;
            /* background-color: red; */
        }

        /*IN AJUSTE BOTÃO PLAY TOPO NO MOBILE */
            .texto-img-head {
            display:block;
            justify-content: center;
            min-height: 0px;
            padding-top: 6px;
            padding-bottom: 0px;
        }

        .cl-img-head img {
            width: 100%;
            max-width: 200px;
            height: auto;
            top:0px;
        }
        .wp-block-columns{
            margin-bottom: -30px;
        }
        

        .cl-div-img-head {
            margin-left: 25%;

        }
        /*OUT AJUSTE BOTÃO PLAY TOPO NO MOBILE */

        .texto-img-head {
            justify-content: center;
            min-height: 0px;
            padding-top: 6px;
            padding-bottom: 0px;
        }

        .cl-div-buscador {
            display: none;
        }

        .cl_section {
            min-width: 100%;
            margin-top: 5px;
            text-align: center;
        }


        

        /**  FIM DO CODIGO MOBILE */
    }


    #id_content_1 {
        margin-top: 0px !important;
    }

    #bnt_servicos {
        color: #fff;
        background-color: #464b4e;
        font-size: medium;
        padding: 7px;
        border-radius: 0px;
        width: 300px;
    }

    #bnt_parceiros {
        color: #fff;
        background-color: #005882;
        font-size: medium;
        padding: 7px;
        border-radius: 0px;
        width: 200px;
    }

    #bnt_parceiros a {
        color: #fff;
    }

    #bnt_servicos a {
        color: #fff;
    }

    /* div h3{
    padding-bottom: 0px;
    background-color: #dc3232;
} */

    .container-partner .text-center h3 {
        margin-top: -18px;
    }



    .lrm-user-modal {
        position: fixed;
        top: 70;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(52, 54, 66, .9);
        z-index: 999999;
        overflow-y: auto;
        cursor: pointer;
        visibility: hidden;
        opacity: 0;
        -webkit-transition: opacity .3s, visibility .3s;
        -moz-transition: opacity .3s, visibility .3s;
        transition: opacity .3s, visibility .3s;
    }

    .img-head-apple {
        position: fixed;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        padding: 0px;
        justify-content: center;
        margin-bottom: 50px;
        /* background-color: tomato; */
        min-height: 100%;
        z-index: -1;
        filter: brightness(80%);
    }

    .cl_destaque {
        height: 250px !important;
    }

    .cl_cookies {
        height: 200px !important;
    }

    .cl_parceiros {
        height: 200px !important;
    }

    .main_comunidade .botoes a {
        color: #fff;
    }

    .p-t-30 {
        padding-top: 30px !important;
    }

    #cl_bg_f2f2f2 {
        background-color: #f2f2f2;
    }

    #cl_bg_00659b{
        background-color: #464b4e;
    }

    .site-blocks .b-opts-inner .block .bo-svg {
        margin-left: 66px;
        margin-top: 27px;

    }

    .object-cover {
        filter: gray;
        /* IE6-9 */
        -webkit-filter: grayscale(1);
        /* Google Chrome, Safari 6+ & Opera 15+ */
        filter: grayscale(100%);
        /* Microsoft Edge and Firefox 35+ */
        opacity: 0.85;

    }

    /* NPMM - CORREÇÃO BUG BOTÃO VIEDO SLIDE TOPO EM MOBILE NÃO ATUAVA... */
    /** Link Bug no Trello: https://trello.com/c/ErOIQKCs */

    .gutentor-image-thumb{
        z-index:9999;
    }

    /* Disable grayscale on hover */
    img:hover {
        -webkit-filter: grayscale(0);
        filter: none;
        opacity: 1;
    }

    .icon_title {
        display: flex;
        justify-items: center;

    }

    .dashicons-before {
        color: #005882;
        padding-top: 5px;
        padding-right: 5px;
    }
    a, a:hover{
        text-decoration: none;
    }

    .cl_section h1{
            color:#464b4e;
    }

    .d-md-block h2{
    font-weight: 600 !important;
    font-size: 1.38em;
}

</style>


<!-- NOVO TOPO -->
<?php 
$cl_blog_id = get_current_blog_id();

switch ($cl_blog_id) {
    
    case 86:
        $cl_total_video = 2;
        break;
    case 395:
        $cl_total_video = 1;
        break;
    case 305:
        $cl_total_video = 1;
        break;           
}


?>
<?php $rand = rand(0, $cl_total_video) ?>

<div class="img_head">
    <div id="myVideo">
    <?php the_content(); ?>
        <?php /* cl_alerta($os); */ ?>
    <!-- NPMM - INICIO CODIGO COMENTADO POIS FOI FEITO A TROCA DE VIDEO PARA IMAGENS -->
        <!-- <?php if ($os == 'iphone') { ?>
            <div class="img-head-apple"><img src="https://idealbiz.io/pt/wp-content/uploads/sites/video/<?php echo $cl_blog_id ?>/idealbiz-img-topo-4.jpg"></div>

        <?php } else { ?>

            <video autoplay muted loop playsinline>
                <source src="https://idealbiz.io/pt/wp-content/uploads/sites/video/<?php echo $cl_blog_id ?>/video-back-ground<?php echo $rand ?>.mp4" type="video/mp4">
                Your browser does not support HTML5 video.
            
                
            </video>

        <?php } ?> -->

    </div>
    <!-- NPMM - FIM CODIGO COMENTADO POIS FOI FEITO A TROCA DE VIDEO PARA IMAGENS -->
<!--     <div class="texto-img-head">
        <div class="cl-div-conteudo">
            
        </div>
        <div class="cl-div-img-head">
            <?php if ($image_head_right) { ?>
                <a href="<?php echo $link_image_head_right; ?>" target="<?php echo $link_image_head_right_window;  ?>">
                    <div class="cl-img-head"><img src="<?php echo $image_head_right ?>" alt="Image Head Right iDealBiz"></div>
                </a>
            <?php } ?>
        </div>
    </div> -->
    <div class="cl-buscador">

        <div class="cl-div-buscador">
            <form id="search-form--header" class="search-form--header" action="<?php echo home_url('/'); ?>" method="get">
                <label class="text-global-search border-blue b-t-l-r b-b-l-r">
                    <input class="g-search" type="text" name="s" id="search" autocomplete="off" minlength="3" placeholder="<?php echo _e('Search in all Idealbiz sites:') . ' ' . home_url('/'); ?>" value="<?php the_search_query(); ?>" />
                </label>
                <button class="btn btn-blue m-l-10 font-btn-plus blue--hover" type="submit" value="Submit"><?php _e('Search', 'idealbiz'); ?></button>
            </form>
        </div>
    </div>
</div>
<!-- FIM NOVO TOPO -->
<div class="cl_main">

    <!-- INI NOVO CODIGO SLIDE COOKIES -->
    <div class="cl_big_title p-t-20" id="cl_bg_f2f2f2"><?php echo get_field('title_cookies') ?></div>
    <?php if (!$button_options) { ?>
        <?php
        if ($type_of_display_of_cookies == false) {
            $destaque = false;
            include(MY_THEME_DIR . 'elements/static_cookies.php');
            /* get_template_part('/elements/static_cookies'); */
        } else {
            
            $destaque = false;
            include(MY_THEME_DIR . 'elements/cookies.php');
            /* get_template_part('/elements/cookies'); */
        }
        ?>
    <?php } ?>
    <!-- FIM NOVO CODIGO SLIDE COOKIES -->

    <!-- INI DESTASQUES -->
    <?php if (get_field('title_detach')) { ?>
        <div class="cl_big_title p-t-20" id="cl_bg_f2f2f2"><?php echo get_field('title_detach') ?></div>
        <?php if (!$button_options) { ?>
            <?php
            if ($type_of_display_of_cookies == true) {
                $destaque = true;
                include(MY_THEME_DIR . 'elements/static_destaque.php');
                /* get_template_part('/elements/static_cookies'); */
            } else {
                $destaque = true;
                include(MY_THEME_DIR . 'elements/destaques.php');
                /* get_template_part('/elements/cookies'); */
            }
            ?>
        <?php } ?>
    <?php } ?>
    <!-- FIM DESTAQUES -->


<!-- ///INI SLIDER TIPO PARCEIROS -->
    <?php
    //Em $conj_val Colocar "_" antes do Número Ex.: _1
    $conj_val = '_44';
    $cl_varParceiro = new Conj_Var_Partner();
    $cl_varParceiro->Var_Partner($conj_val);
    ?>

    <div class="cl_big_title p-t-15"><?php echo $cl_varParceiro->title_section_member; ?></div>

    <?php
    include(MY_THEME_DIR . 'elements/silider_parceiros.php');
    wp_reset_postdata();
    ?>
<!-- ///FIM SLIDER TIPO PARCEIROS -->


<!-- 1 INI MODULO DE CODIGO -->
<div class="container">
        <div class="cl_big_title" id="id_content_1"><?php echo get_field('title_content_1') ?></div>
        <div class="cl_section">
            <?php echo get_field('content_1') ?>
        </div>
    </div>

<!-- 1- INICIO EXIBE MODULOS SLIDERS listing ou membro ou cookies-->
<?php    
        
        $id_modulo = '_1';
        include(MY_THEME_DIR.'elements/modulo_slider.php');
               
?>
<!-- 1- FIM  EXIBE MODULOS SLIDERS listing ou membro ou cookies-->      
 <!-- FIM 1 MODULO DE CODIGO -->  

<!-- 2 INI MODULO DE CODIGO -->
    <div id='cl_bg_f2f2f2' class="w-100">
        <div class="container">
            <div class="cl_big_title"><?php echo get_field('title_content_2') ?></div>
            <div class="cl_section">
                <?php echo get_field('content_2') ?>
            </div>
        </div>
<!-- 2- INICIO EXIBE MODULOS SLIDERS listing ou membro ou cookies-->

<?php    
        
        $id_modulo = '_2';
        include(MY_THEME_DIR.'elements/modulo_slider.php');
        
?>
<!-- 2- FIM  EXIBE MODULOS SLIDERS listing ou membro ou cookies-->

</div>
<!-- FIM CONTEUDO 2 MODULO DE CODIGO -->    



<!-- 3 INI MODULO DE CODIGO -->
    <div class="container p-t-30 p-b-30">
        <div class="cl_big_title"><?php echo get_field('title_content_3') ?></div>
        <div class="cl_section">
            <?php echo get_field('content_3') ?>
        </div>
    </div>
<!-- 3- INICIO EXIBE MODULOS SLIDERS listing ou membro ou cookies-->
<?php    
        
        $id_modulo = '_3';
        include(MY_THEME_DIR.'elements/modulo_slider.php');
            
    
?>
<!-- 3- FIM  EXIBE MODULOS SLIDERS listing ou membro ou cookies-->    
<!-- FIM 3 MODULO DE CODIGO -->

<!-- 4 INI MODULO DE CODIGO -->
    <div id='cl_bg_f2f2f2' class="w-100">
        <div class="container">
            <div class="cl_big_title"><?php echo get_field('title_content_4') ?></div>
            <div class="cl_section">
                <?php echo get_field('content_4') ?>
            </div>
        </div>
    </div>
<!-- 4- INICIO EXIBE MODULOS SLIDERS listing ou membro ou cookies-->
<?php    
        
        $id_modulo = '_4';
        include(MY_THEME_DIR.'elements/modulo_slider.php');
            
    
?>
<!-- 4- FIM  EXIBE MODULOS SLIDERS listing ou membro ou cookies-->    
<!-- FIM 4 MODULO DE CODIGO -->  

<!-- 5 INI MODULO DE CODIGO -->
    <div class="container p-t-30 p-b-30">
        <div class="cl_big_title"><?php echo get_field('title_content_5') ?></div>
        <div class="cl_section">
            <?php echo get_field('content_5') ?>
        </div>
    </div>
<!-- 5- INICIO EXIBE MODULOS SLIDERS listing ou membro ou cookies-->
<?php    
        
        $id_modulo = '_5';
        include(MY_THEME_DIR.'elements/modulo_slider.php');
                
?>
<!-- 5- FIM  EXIBE MODULOS SLIDERS listing ou membro ou cookies-->      
<!-- FIM 5 MODULO DE CODIGO -->

<!-- 6 INI MODULO DE CODIGO -->
    <div id='cl_bg_f2f2f2' class="w-100">
        <div class="container">
            <div class="cl_big_title"><?php echo get_field('title_content_6') ?></div>
            <div class="cl_section">
                <?php echo get_field('content_6') ?>
            </div>
        </div>
    </div>
<!-- 6- INICIO EXIBE MODULOS SLIDERS listing ou membro ou cookies-->
<?php    
        
        $id_modulo = '_6';
        include(MY_THEME_DIR.'elements/modulo_slider.php');
              
?>
<!-- 6- FIM  EXIBE MODULOS SLIDERS listing ou membro ou cookies-->        
<!-- FIM 6 MODULO DE CODIGO -->    

<!-- 7 INI MODULO DE CODIGO -->
    <div class="container p-t-30 p-b-30">
        <div class="cl_big_title"><?php echo get_field('title_content_7') ?></div>
        <div class="cl_section">
            <?php echo get_field('content_7') ?>
        </div>
    </div>
<!-- 7- INICIO EXIBE MODULOS SLIDERS listing ou membro ou cookies-->
<?php    
        
        $id_modulo = '_7';
        include(MY_THEME_DIR.'elements/modulo_slider.php');
               
?>
<!-- 7- FIM  EXIBE MODULOS SLIDERS listing ou membro ou cookies-->        
<!-- FIM 7 MODULO DE CODIGO -->

<!-- 8 INI MODULO DE CODIGO -->
    <div id='cl_bg_f2f2f2' class="w-100">
        <div class="container">
            <div class="cl_big_title"><?php echo get_field('title_content_8') ?></div>
            <div class="cl_section">
                <?php echo get_field('content_8') ?>
            </div>
        </div>
    </div>
<!-- 8- INICIO EXIBE MODULOS SLIDERS listing ou membro ou cookies-->
<?php    
        
        $id_modulo = '_8';
        include(MY_THEME_DIR.'elements/modulo_slider.php');
               
?>
<!-- 8- FIM  EXIBE MODULOS SLIDERS listing ou membro ou cookies-->     
<!-- FIM 8 MODULO DE CODIGO -->

<!-- 9 INI MODULO DE CODIGO -->
    <div id='cl_bg_00659b' class="w-100">
        <div class="container p-t-30 p-b-30">
            <div class="cl_big_title"><?php echo get_field('title_content_9') ?></div>
            <div class="cl_section">
                <?php echo get_field('content_9') ?>
            </div>
        </div>
    </div>
<!-- 9- INICIO EXIBE MODULOS SLIDERS listing ou membro ou cookies-->
<?php    
        
        $id_modulo = '_9';
        include(MY_THEME_DIR.'elements/modulo_slider.php');
               
?>
<!-- 9- FIM  EXIBE MODULOS SLIDERS listing ou membro ou cookies-->          
<!-- FIM 9 MODULO DE CODIGO -->    



    <!-- //INICIO CÓDIGO jQUERY SLIDE DE LISTING -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.jquery.min.js"></script>

    <script>
        jQuery(document).ready(($) => {


            $(window).bind('orientationchange', function() {
                console.log('window resized..');
                this.location.reload(false);



            });

            var size_screen = $(window).width();
            var divWidth = $(".container").width();

            /* alert('Tela '+size_screen+' Div '+divWidth); */


            if (window.matchMedia("(max-width: 767px)").matches) {
                var px_slider_Per_View_cookies = 278; //initiate as false
                var px_slider_Per_View_slider = 330; //initiate as false
                $('.expert-card').attr('style', 'width: 300px !important');
                /* alert('A '+px_slider_Per_View_cookies); */
            } else {
                var px_slider_Per_View_cookies = 235; //initiate as false
                var px_slider_Per_View_slider = 345; //initiate as false
                $('.b-opts-inner #Cookie_2').removeClass('w-278px').addClass('w-200px');
                /* alert('B '+px_slider_Per_View_cookies); */
            }
            var px_slider_Per_View_partner = 160; //initiate as false
            var px_slider_Per_View_listing = 278; //initiate as false        e

            var slider_Per_View_cookies = Math.round((divWidth / px_slider_Per_View_cookies));
            var slider_Per_View_listing = Math.round((divWidth / px_slider_Per_View_listing));
            var slider_Per_View_partner = Math.round((divWidth / px_slider_Per_View_partner));
            var slider_Per_View_slider = Math.round((divWidth / px_slider_Per_View_slider));

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

<!-- //FIM CÓDIGO jQUERY SLIDE DE LISTING -->


    <?php
    get_footer();
    ?>