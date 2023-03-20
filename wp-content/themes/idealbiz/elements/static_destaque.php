<div id='cl_bg_f2f2f2' class="w-100">
    <div class="d-flex flex-row flex-wrap justify-content-center container">
        <?php
        $business_options = get_field('business_options');
        /* var_dump($business_options); */
        $bo = 0;
        foreach ($business_options as $option) {
            /*             $color_box = 'none'; 
            if ($option['icon_color'] != '') { */
            $color_box = $option['icon_color'];
            $img_box = $option['bg_img_cookie_and_deteach']['url'];
            $img_ico = $option['image_ico']['url'];
            $color_text_cookie = $option['color_text_cookie'];
            /* } */
            if ($option['detach'] == $destaque) {
                if ($option['external_link'] == '' && $option['button_link'] == '') {
                } else {
        ?>
                    <div class="b-opts card_destaque">
                        <div class="b-opts-inner m-y-5 sub_card_destaque">
                            <a href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>" data-sbo="<?php echo $bo; ?>" style=" background-color: <?php echo $color_box; ?>; background-image:url( <?php echo $img_box; ?>);opacity: <?php echo $transparence; ?> ;" class="d-block size_destaque block p-t-5 m-x-5 b-r-5 m-appicon <?php if ($option['required_login']) echo 'lrm-login'; ?>">
                                <?php if ($img_ico != '') { ?>
                                    <img src="<?php echo $img_ico; ?>" class="bo-svg" alt="<?php echo $option['title_desktop']; ?>" title="<?php echo $option['title_desktop']; ?>" />
                                <?php } else { ?>
                                    <i class="white--color m-l-35 <?php echo $option['icon']; ?>"></i>
                                <?php } ?>
                                <h2 <?php echo $color_text_cookie; ?> class="titulo_destaque m-l-35  d-md-block first-line center-inblock"><?php echo $option['title_desktop']; ?></h2>
                                <div class="cl_texto_cookies" style=" <?php echo $font_size; ?>" class="m-l-35  <?php echo $d_none; ?> d-md-block first-line center-inblock"><?php echo $option['text']; ?></div>
                            </a>
                            <!--                         <div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke d-none">
                            <div class="b-opts-d-open-inner white--background">
                                <div class="b-opts-body">
                                    <h3 class="font-weight-semi-bold m-b-20"><?php echo $option['title_desktop']; ?></h3>
                                    <p><?php echo $option['text']; ?></p>
                                    <a class="btn btn-blue m-t-5 white--background h-36px l-h-18 <?php if ($option['required_login']) echo 'required-login'; ?>" href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>"><?php _e('Selecionar', 'idealbiz'); ?></a>
                                </div>
                            </div>
                        </div> -->
                        </div>
                        <!-- <span class="d-block d-md-none"><?php echo $option['title']; ?></span> -->
                    </div>
                <?php } ?>
        <?php
            }
        }
        $bo++;
        ?>
    </div>
</div>

<style>
    .card_destaque {
        padding: 10px;
        box-shadow: 4px 4px 5px #888888;
        margin: 25px 10px;
        background-color: #fff;
    }

    .cl_texto_cookies {
        top: 0px;
        font-size: 100%;
        color: #888888;
        padding-top: 200px;
    }

    .sub_card_destaque a {

        background-position: center;
        background-repeat: no-repeat;
        background-size: 100% 60%;
        text-decoration-line: none;
        text-decoration: none;
        -webkit-text-decoration-skip: none;
        color: #888888;

    }

    .container_destaque {
        max-width: 1198px !important;
    }

    .size_destaque {
        width: 200px;
        height: 290px;
    }

    .titulo_destaque {
        font-size: 2em !important;
        color: #888888;
    }

    @media only screen and (max-width: 768px) {
        .size_destaque {
            width: 120px;
            height: 180px;
        }

        .cl_texto_cookies {
            font-size: 0.90em !important;
            padding-top: 115px;
        }

        .titulo_destaque {
            font-size: 1.4em !important;
            margin-top: -10px;
            font-weight: var(--font-weight);
            font-family: var(--font-default), sans-serif;
        }

        .sub_card_destaque a {

            background-size: 160px 120px;
            background-position: 0 17px;


        }
    }

    @media only screen and (max-width: 1024px) {

        .size_destaque {
            width: 175px;
            height: 290px;
        }

        .cl_texto_cookies {
            padding-top: 190px;
        }

    }

    @media only screen and (max-width: 915px) {

        .size_destaque {
            width: 120px;
            height: 180px;
        }

        .cl_texto_cookies {
            padding-top: 115px;
        }

    }
</style>