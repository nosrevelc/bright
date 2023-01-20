<?php
$is_certified = get_field('listing_certification_status') == 'certification_finished';
$cl_company_associate = get_field('company_associate');

$cl_lable_service = __('_str service','idealbiz').' : ';
$cl_lable_opportunuty = __('_str Opportunity','idealbiz').' : ';
$cl_lable_company = __('_str Company','idealbiz').' : ';

$cl_sr_pay_lead_mode = get_field('sr_pay_lead_mode');
if ($cl_sr_pay_lead_mode === NULL) {
    $cl_sr_pay_lead_mode = ['value' => 'sr_pay_before', 'label' => 'Pay Before'];
}

$cl_rb_pay_lead_mode = get_field('rb_pay_lead_mode');
if($cl_rb_pay_lead_mode === NULL){
    $cl_rb_pay_lead_mode = ['value'=>'rb_pay_before','label'=>'Pay Before'];

}

?>
<a href="<?php echo the_permalink(); ?>" class="w-50px h-50px o-hidden no-decoration">
    <div class="p-5 m-10 expert-card position-relative d-flex flex-column black--color white--background dropshadow font-weight-medium">

        <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
            <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
        <?php endif; ?>
        <div class="d-flex flex-row center-content text-uppercase">
            <div>
                <img class="w-50px h-50px object-cover b-r" src="<?php echo get_field('foto')['sizes']['medium']; ?>">

            </div>
            <div>

            </div>
            
            <div class="cl_title m-l-10 p-l-10 w-230px"> 
            <span class="dashicons dashicons-businessman" style="font-size:1.3em"></span><?php the_title(); ?>

            <?php if($cl_company_associate[0]->post_title != Null){?>
                <div class="cl_mode_pay"><span class=" dashicons dashicons-building" style="font-size:1.5em;"></span><?php echo $cl_lable_company.$cl_company_associate[0]->post_title; ?></div>
                <?php 
                }
                ?>

                <div class="cl_mode_pay"><span class=" dashicons dashicons-yes" style="font-size:1.5em;"></span><?php echo $cl_lable_opportunuty.$cl_rb_pay_lead_mode['label']?></div>
                <div class="cl_mode_pay"><span class=" dashicons dashicons-yes" style="font-size:1.5em;"></span><?php echo $cl_lable_service.$cl_sr_pay_lead_mode['label']?></div>
                

                    
            </div>

            <div class="m-l-10 w-110px">

            <?php
            $location_objs = get_the_terms(get_the_ID(), 'location');
            if ($location_objs) { ?>
                <div class=" d-flex w-150px">

                    <?php
                    $country_iso = get_option('country_market');
                    $country_name = getCountry($country_iso)['country'];
                    $country_flag = DEFAULT_WP_CONTENT . '/plugins/polylang/flags/' . strtolower($country_iso) . '.png';

                    ?>
                    <div class="country-market-flag center-content">
                        <img src="<?php echo $country_flag; ?>" alt="<?php echo $country_name; ?>">
                    </div>

                    <div> <i class="icon-local "></i></div>
                    <div>
                        <?php
                        $location_string = join(', ', wp_list_pluck($location_objs, 'name'));
                        echo $location_string;

                        ?>
                    </div>
                </div>
                
            <?php } ?>


            </div>

            <?php

            $term_obj_list = get_the_terms(get_the_ID(), 'service_cat');
            if ($term_obj_list) { ?>
            
                <div class="cl_service m-l-10 p-l-10 w-290px">

                    <?php
                    $terms_string = '<span class=" dashicons dashicons-welcome-learn-more" style="font-size:1.5em;"></span>'.join(', ', wp_list_pluck($term_obj_list, 'name'));
                    echo $terms_string;
                    ?>
                </div>
            <?php } ?>

        </div>

    </div>
<style>

    .cl_title{
        border-left: 1px solid #cccccc;
        border-right: 1px solid #cccccc;
    }

    .cl_mode_pay {
        font-size: 0.75em;
        white-space: nowrap;
    }
    .country-market-flag img {
        margin-top: -10px;
    }

    .icon-local {
        margin-top: 0px;
    }

    .expert-card {
        height: 95%;
        border-radius: 5px;
    }

    .cl_service {
        font-size: 0.75em;
        border-left: 1px solid #cccccc;
    }

    .country-market-flag {
        display: block !important;
    }

    a:hover {
        text-decoration: none;
    }
</style>