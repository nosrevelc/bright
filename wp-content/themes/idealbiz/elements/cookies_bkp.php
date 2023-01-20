<?php 
        
        if($slide_mobile == false){
            $hiddem_desktop = 'hidden-desktop';
            $hiddem_mobile = 'hidden-mobile';
            $d_none = 'd-none';
            $b_opts = 'b-opts';
            $size = 'w-200px h-200px';
            $font_size = '';
                
 ?>


<div class="container">
<?php  if($title_cookies_1){ ?>       

    <div class="row_left hidden-mobile">
    <h1 class="text-left row_tittle_desktop w-100 m-b-1 " ><?php echo $title_cookies_1; ?></h1>
    </div>
    <div class="row_center hidden-desktop">
    <h1 class="text-center row_tittle_mpbile w-100 m-b-11 " style="font-size:2em !important;" ><?php  echo $cl_varParceiro->title_section_member; ?></h1>
    </div>

    
    <?php }?>
    <?php  

    if($view_button_all_in_section_1){ ?>

    <div class="row_left text-left hidden-mobile" >
    <?php echo '<a class="btn-blue " href="'.$cookies_of_page_1->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div>
    <div class="row_center text-center hidden-desktop" >
    <?php echo '<a class="btn-blue " href="'.$cookies_of_page_1->ID->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div>
 
    <?php }?>
    </div>

     <!-- INICIO COKIES TELEMÓVEL -->
     <div class="p-t-10">
     <div class="site-blocks d-flex flex-row flex-wrap justify-content-center container <?php echo $hiddem_desktop;?>">        
            <?php
            $business_options = get_field('business_options',$cookies_of_page_1->ID);
            
            $bo = 0;
                                    foreach ($business_options as $option) {
                                        $color_box = 'none'; 
                                    if ($option['icon_color'] != '') {
                                        $color_box = $option['icon_color'];
                                        $img_box = $option['bg_img_cookie']['url'];
                                        $img_ico = $option['image']['url'];
                                        $color_text_cookie = $option['color_text_cookie'];
                                    }
                                    if ($option['external_link'] == '' && $option['button_link'] == '') { } else {
                                        ?>
                                            <div class="b-opts">
                                                <div class="b-opts-inner m-y-5 " ">
                                                    <a href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>" data-sbo="<?php echo $bo; ?>" style=" background-color: <?php echo $color_box; ?>; background-image:url( <?php echo $img_box; ?>); background-size: 100% 100%;opacity: <?php echo $transparence; ?> ; border: 1px solid rgba(219, 219, 219, 0.2)" class="d-block w-200px h-200px block p-t-5 m-x-5 b-r-5 m-appicon <?php if ($option['required_login']) echo 'lrm-login'; ?>">
                                                        <?php if ($option['image'] != '') { ?>
                                                            <img src="<?php echo $img_ico; ?>" class="bo-svg" alt="<?php echo $option['title_desktop']; ?>" title="<?php echo $option['title_desktop']; ?>" />
                                                        <?php } else { ?>
                                                            <i class="white--color m-l-35 <?php echo $option['icon']; ?>" ></i>
                                                        <?php } ?>
                                                        <h2 style = "color:<?php echo $color_text_cookie; ?>!important;" class="m-l-35 white--color d-none d-md-block first-line center-inblock"><?php echo str_replace_first(' ', '<br/>', $option['title_desktop']); ?></h2>
                                                    </a>
                                                </div>
                                                <span style = "color:<?php echo $color_text_mobile; ?>!important;" class="d-block d-md-none"><?php echo $option['title']; ?></span>
                                            </div>
                                    <?php }?>
                                    <?php
                                        }
                                        $bo++;
                                    ?>
    </div>
    <!-- FIM COKIES TELEMÓVEL -->
 
<!-- INICIO COKIES DESKTOP -->
<div class="site-blocks container big-width <?php echo $hiddem_mobile;?>">


    <div class="row col-md-12 countrie <?php echo $hiddem_mobile;?> ">
        <div class="swiper-container cl_slider_cookies">
            <div class="swiper-wrapper">
                                    <?php
                            $business_options = get_field('business_options',$cookies_of_page_1->ID);
                            
                            $bo = 0;
                            foreach ($business_options as $option) {
                                $color_box = 'none'; 
                            if ($option['icon_color'] != '') {
                                $color_box = $option['icon_color'];
                                $img_box = $option['bg_img_cookie']['url'];
                                $img_ico = $option['image']['url'];
                                $color_text_cookie = $option['color_text_cookie'];
                            }
                            if ($option['external_link'] == '' && $option['button_link'] == '') { } else {
                                ?>
                                <div class="swiper-slide">
                                    <div class="<?php echo $b_opts;?>">
                                        <div class="b-opts-inner">
                                            <a href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>" data-sbo="<?php echo $bo; ?>" style=" background-color: <?php echo $color_box; ?>; background-image:url( <?php echo $img_box; ?>); background-size: 100% 100%;opacity: <?php echo $transparence; ?> ; border: 1px solid rgba(219, 219, 219, 0.2)" class="d-block <?php echo $size;?> block p-t-5 m-x-5 b-r-5 m-appicon <?php if ($option['required_login']) echo 'lrm-login'; ?>">
                                                <?php if ($option['image'] != '') { ?>
                                                    <img src="<?php echo $img_ico; ?>" class="bo-svg" alt="<?php echo $option['title_desktop']; ?>" title="<?php echo $option['title_desktop']; ?>" />
                                                <?php } else { ?>
                                                    <i class="white--color m-l-35 <?php echo $option['icon']; ?>"></i>
                                                <?php } ?>
                                                <h2 style = " <?php echo $font_size;?> color:<?php echo $color_text_cookie; ?>!important;" class="m-l-35 white--color <?php echo $d_none;?> d-md-block first-line center-inblock"><?php echo str_replace_first(' ', '<br/>', $option['title_desktop']); ?></h2>
                                            </a>
                                            <div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke <?php echo $hiddem_mobile;?> d-none">
                                                <div class="b-opts-d-open-inner white--background">
                                                    <div class="b-opts-body">
                                                        <h3 class="font-weight-semi-bold m-b-20"><?php echo $option['title_desktop']; ?></h3>
                                                        <p><?php echo $option['text']; ?></p>
                                                        <a class="btn btn-blue m-t-5 white--background h-36px l-h-18 <?php if ($option['required_login']) echo 'required-login'; ?>" href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>"><?php _e('Selecionar', 'idealbiz'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            <?php }?>
                            <?php
                                }
                                $bo++;
                            ?>
            </div>
        </div>
            <!-- <div class="swiper-button-next "><i class="icofont-thin-right"></i></div>dropshadow
            <div class="swiper-button-prev "><i class="icofont-thin-left"></i></div> -->
        </div>
</div> 
     </div>
<?php /* var_dump($business_options); */?>
<!-- FIM COKIES DESKTOP -->

<?php 

}else{

$hiddem_desktop = 'hidden-mobile';
$hiddem_mobile = '';
$d_none = '';
$b_opts = '';
$size = 'w-230px h-200px';
$font_size = 'font-size:1.7em !important;';
?>
    <div class="container">
<?php if($title_cookies_1){ ?>       

    <div class="row_left hidden-mobile">
    <h1 class="text-left row_tittle_desktop w-100 m-b-1 " ><?php echo $title_cookies_1; ?></h1>
    </div>
    <div class="row_center hidden-desktop">
    <h1 class="text-center row_tittle_mpbile w-100 m-b-11 " style="font-size:2em !important;" ><?php  echo $title_cookies_1; ?></h1>
    </div>

    
    <?php }?>
    <?php  

    if($view_button_all_in_section_1){ ?>

    <div class="row_left text-left hidden-mobile" >
    <?php echo '<a class="btn-blue " href="'.$cookies_of_page_1->ID->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div>
    <div class="row_center text-center hidden-desktop p-b-30" >
    <?php echo '<a class="btn-blue " href="'.$cookies_of_page_1->ID->guid.'" title="'.__('Listings','idealbiz').'">'.__('View All Listings','idealbiz').'</a>'; ?>
    </div>
 
    <?php }?>
    </div>


<div class="site-blocks container big-width <?php echo $hiddem_mobile;?>">


<div class="row col-md-12 countrie <?php echo $hiddem_mobile;?> ">
    <div class="swiper-container cl_slider_cookies">
        <div class="swiper-wrapper">
                                <?php
                        $business_options = get_field('business_options',$cookies_of_page_1->ID);
                        
                        $bo = 0;
                        foreach ($business_options as $option) {
                            $color_box = 'none'; 
                        if ($option['icon_color'] != '') {
                            $color_box = $option['icon_color'];
                            $img_box = $option['bg_img_cookie']['url'];
                            $img_ico = $option['image']['url'];
                            $color_text_cookie = $option['color_text_cookie'];
                        }
                        if ($option['external_link'] == '' && $option['button_link'] == '') { } else {
                            ?>
                            <div class="swiper-slide">
                                <div class="<?php echo $b_opts;?>">
                                    <div class="b-opts-inner">
                                        <a href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>" data-sbo="<?php echo $bo; ?>" style=" background-color: <?php echo $color_box; ?>; background-image:url( <?php echo $img_box; ?>); background-size: 100% 100%;opacity: <?php echo $transparence; ?> ; border: 1px solid rgba(219, 219, 219, 0.2)" class="d-block <?php echo $size;?> block p-t-5 m-x-5 b-r-5 m-appicon <?php if ($option['required_login']) echo 'lrm-login'; ?>">
                                            <?php if ($option['image'] != '') { ?>
                                                <img src="<?php echo $img_ico; ?>" class="bo-svg" alt="<?php echo $option['title_desktop']; ?>" title="<?php echo $option['title_desktop']; ?>" />
                                            <?php } else { ?>
                                                <i class="white--color m-l-35 <?php echo $option['icon']; ?>"></i>
                                            <?php } ?>
                                            <h2 style = " <?php echo $font_size;?> color:<?php echo $color_text_cookie; ?>!important;" class="m-l-35 white--color <?php echo $d_none;?> d-md-block first-line center-inblock"><?php echo str_replace_first(' ', '<br/>', $option['title_desktop']); ?></h2>
                                        </a>
                                        <div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke <?php echo $hiddem_mobile;?> d-none">
                                            <div class="b-opts-d-open-inner white--background">
                                                <div class="b-opts-body">
                                                    <h3 class="font-weight-semi-bold m-b-20"><?php echo $option['title_desktop']; ?></h3>
                                                    <p><?php echo $option['text']; ?></p>
                                                    <a class="btn btn-blue m-t-5 white--background h-36px l-h-18 <?php if ($option['required_login']) echo 'required-login'; ?>" href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>"><?php _e('Selecionar', 'idealbiz'); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                        <?php }?>
                        <?php
                            }
                            $bo++;
                        ?>
        </div>
    </div>
        <!-- <div class="swiper-button-next "><i class="icofont-thin-right"></i></div>
        <div class="swiper-button-prev "><i class="icofont-thin-left"></i></div> -->
    </div>
</div> 
 </div>
<?php /* var_dump($business_options); */?>
<!-- FIM COKIES DESKTOP -->

<?php }?>