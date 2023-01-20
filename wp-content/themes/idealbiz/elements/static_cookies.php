<div class="site-blocks d-flex flex-row flex-wrap justify-content-center container m-t-25">
        <?php
        $business_options = get_field('business_options');
        $bo = 0;
        foreach ($business_options as $option) {
            $color_box = 'none'; 
            if ($option['icon_color'] != '') {
                $color_box = $option['icon_color'];
                $img_box = $option['bg_img_cookie']['url'];
                $img_ico = $option['$img_ico']['url'];
                $color_text_cookie = $option['color_text_cookie'];
            }
            if ($option['detach'] == $destaque){
            if ($option['external_link'] == '' && $option['button_link'] == '') { } else {
                ?>
                <div class="b-opts">
                    <div class="b-opts-inner m-y-5">
                        <a href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>" data-sbo="<?php echo $bo; ?>" style=" background-color: <?php echo $color_box; ?>; background-image:url( <?php echo $img_box; ?>); background-size: 100% 100%;opacity: <?php echo $transparence; ?> ; border: 1px solid rgba(219, 219, 219, 0.2)" class="d-block w-200px h-200px block p-t-5 m-x-5 b-r-5 m-appicon <?php if ($option['required_login']) echo 'lrm-login'; ?>">
                            <?php if ($img_ico != '') { ?>
                                <img src="<?php echo $img_ico; ?>" class="bo-svg" alt="<?php echo $option['title_desktop']; ?>" title="<?php echo $option['title_desktop']; ?>" />
                            <?php } else { ?>
                                <i class="white--color m-l-35 <?php echo $option['icon']; ?>"></i>
                            <?php } ?>
                            <h2 style = "color:<?php echo $color_text_cookie; ?>!important;" class="m-l-35 white--color d-none d-md-block first-line center-inblock"><?php echo str_replace_first(' ', '<br/>', $option['title_desktop']); ?></h2>
                        </a>
                        <div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke hidden-mobile d-none">
                            <div class="b-opts-d-open-inner white--background">
                                <div class="b-opts-body">
                                    <h3 class="font-weight-semi-bold m-b-20"><?php echo $option['title_desktop']; ?></h3>
                                    <p><?php echo $option['text']; ?></p>
                                    <a class="btn btn-blue m-t-5 white--background h-36px l-h-18 <?php if ($option['required_login']) echo 'required-login'; ?>" href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>"><?php _e('Selecionar', 'idealbiz'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span style = "color:<?php echo $color_text_mobile; ?>!important;" class="d-block d-md-none"><?php echo $option['title']; ?></span>
                </div>
            <?php }?>
        <?php
            }
        }
            $bo++;
         ?>
    </div>