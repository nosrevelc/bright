<div class="row col-md-12 countries-slider h-slider">
    <div class="swiper-container">
        <div class="swiper-wrapper">

            <?php
                $continents = new IDB_Continents;
                foreach($continents->get_continent_countries() as $continent_code => $continent) : ?>
                    <div class="swiper-slide rectangle-square h-265px" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/continents/<?php echo $continent_code; ?>.jpg)">
                        <div class="content d-flex flex-column w-100 h-100 p-y-25 p-x-15">
                            <h2><?php echo $continent['name']; ?></h2> <?php
                            foreach($continent['countries'] as $country_code => $country) : ?>
                                <a href="<?php echo $country['url']; ?>"><?php echo $country['name']; ?></a> <?php
                            endforeach; ?>
                        </div>
                    </div> <?php
                endforeach; ?>
        
        </div>
    </div>
    <!--
                <a class="icon-arrowleft btn-prev pointer d-inline-block m-t-15"></a>
                <a class="icon-arrowleft btn-next pointer d-inline-block m-t-23 m-l-5"></a>
                -->
</div>