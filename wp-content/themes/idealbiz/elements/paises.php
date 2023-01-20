
<section class="homepage countries m-m-b-5 m-b-10 <?php if ($countries_background_image) echo 'background-image md-m-b-0'; ?>" style="<?php if ($countries_background_image) { ?>background-image: url('<?php echo $countries_background_image['url']; ?>'); <?php } ?>">

    <div class="container justify-content-between align-items-center d-none d-md-block">
<!--         <div class="row">
            <div class="col-md-12 text-center m-b-30">
                <h1 class=""><?php _e('Where?', 'idealbiz'); ?></h1>
                <h3 class="hidden-mobile"><?php echo get_field('countries_description'); ?></h3>                
            </div>
        </div> -->
    </div>
    <div class="site-blocks container big-width p-l-20 p-r-20">
        <h2 class="d-none d-xs-block text-xs-left m-h2 m-b-15">
            <?php _e('Where?', 'idealbiz'); ?>
            <div class="d-inline-block hidden-desktop"><?php infoModal(get_field('countries_description')); ?></div>
        </h2>
        <div class="row col-md-12 countries-slider h-slider ">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    $continents = getContinents();
                    $countries = getNetworkCountries();
                    $sl_continents = '';
                    foreach ($countries as $kcontinent => $countries) {
                        echo '<div class="swiper-slide b-r-5 o-hidden rectangle-square h-100px " style="background-image: url(' . get_template_directory_uri() . '/assets/img/continents/' . strtolower($kcontinent) . '.jpg)">
                                               <div class="content d-flex flex-column w-100 h-100 p-y-25 p-x-15">
                                                    <h2>' . $continents[$kcontinent] . '</h2>
                                                    <ul class="flags">';
                        //$i=0;
                        foreach ($countries as $country) {
                            $flag = DEFAULT_WP_CONTENT . '/plugins/polylang/flags/' . strtolower($country['iso']) . '.png';
                            echo '<li>
                                                                <a alt="' . $country['name'] . '" href="' . $country['link'] . '">
                                                                    <img src="' . $flag . '" title="' . $country['name'] . '" />
                                                                </a>
                                                            </li>';
                        }
                        echo '</ul>
                                                </div>
                                           </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="site-blocks d-flex flex-row flex-wrap justify-content-center container m-t-25 where-home hidden-desktop hidden-mobile">
        <?php
        $co = 0;
        $continents = getContinents();
        $countries = getNetworkCountries();
        $sl_continents = '';
        $toggle_where = '';
        foreach ($countries as $kcontinent => $countries) { ?>
            <div class="b-opts">
                <div class="b-opts-inner m-y-5 p-10">
                    <div data-href="#" data-co="<?php echo $co; ?>" style="background-size: cover; background-image: url('<?php echo get_template_directory_uri() . '/assets/img/continents/' . strtolower($kcontinent) . '.jpg'; ?>');" class="w-200px h-200px block stroke m-x-5 b-r-5 m-appicon mini-flags-a">
                     <div class="has-more <?php echo count($countries) <= 2 ? 'd-none' : ''; ?>"><span class="icofont-plus"></span></div>
                        <?php
                            $toggle_where .= '
                            <div class="b-opts-inner">
                            <div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke co-' . $co . '">
                                <div class="b-opts-d-open-inner white--background">
                                    <a href="#" class="b-opts-close"><i class="icon icon-close"></i></a>';
                            $ec = '';
                            foreach ($countries as $country) {
                                $flag = DEFAULT_WP_CONTENT . '/plugins/polylang/flags/' . strtolower($country['iso']) . '.png';
                                echo '
                                <a style="z-index:9999;" alt="' . $country['name'] . '" href="' . $country['link'] . '">
                                <img src="' . $flag . '" class="mini-flags w-30px h-20px" title="' . $country['name'] . '" />
                                </a>
                                ';
                                $ec .= '<a alt="' . $country['name'] . '" href="' . $country['link'] . '">
                                                <img src="' . $flag . '" title="' . $country['name'] . '" />
                                              </a>';
                            }
                            $toggle_where .= '   
                                            <div class="b-opts-body" style="padding: 0 20px !important;">
                                                <h3 class="font-weight-semi-bold m-b-20">' . $continents[$kcontinent] . '</h3>
                                                <div class="d-flex mini-flag-list">
                                                    ' . $ec . '
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                    </div>';
                            ?>
                    </div>
                </div>
                
                <span class="d-block d-md-none"><?php echo $continents[$kcontinent]; ?></span>
            </div>
        <?php $co++;
        } ?>
    </div>

</section>
