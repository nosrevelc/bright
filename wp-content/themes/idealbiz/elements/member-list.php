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
<a href="<?php echo '#post-'.$post->ID; ?>" class="popUpForm info-modal w-35px h-35px o-hidden no-decoration">
    <div class="cl_card expert-card position-relative d-flex flex-column black--color white--background font-weight-medium">

        <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
            <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
        <?php endif; ?>
        <div class="cl_display center-content text-uppercase">
            <div class="cl_img_member">
                <img class="object-cover b-r" src="<?php echo get_field('foto')['sizes']['medium']; ?>">

            </div>
            <div>

            </div>
            
            <div class="cl_title"> 
            <span class="dashicons dashicons-businessman" style="font-size:1.3em"></span><?php the_title(); ?>

            <?php if($cl_company_associate[0]->post_title != Null){?>
                <div class="cl_mode_pay"><span class=" dashicons dashicons-building" style="font-size:1.5em;"></span><?php echo $cl_lable_company.$cl_company_associate[0]->post_title; ?></div>
                <?php 
                }
                ?>

                <!-- <div class="cl_mode_pay"><span class=" dashicons dashicons-yes" style="font-size:1.5em;"></span><?php echo $cl_lable_opportunuty.$cl_rb_pay_lead_mode['label']?></div>
                <div class="cl_mode_pay"><span class=" dashicons dashicons-yes" style="font-size:1.5em;"></span><?php echo $cl_lable_service.$cl_sr_pay_lead_mode['label']?></div> -->
                <div class="cl_service w-290px cl_mobile_show">
                    <?php echo cl_services_member_list()?>
                </div>

                    
            </div>

            <div class = "div_flag">

            <?php
            $location_objs = get_the_terms(get_the_ID(), 'location');
            if ($location_objs) { ?>
                <div class="cl_flag_country">

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

            <div class="cl_service m-l-10 p-l-10 w-290px cl_mobile_hidden">
                    <?php echo cl_services_member_list()?>
            </div>
            

        </div>

    </div>
            </a><?php echo meberPITModal($post->ID);?>
<style>
    .dashicons{
        height: 0px !important;
    }
.cl_flag_country{
    display: flex;
}
.div_flag{
    margin-left: 10px;
    width: 110px;
}
.cl_card {
    padding: 5px;
    margin: 1px;
    border: #cccccc 1px solid;
}
.cl_title{
        border-left: 1px solid #cccccc;
        border-right: 1px solid #cccccc;
        margin-left: 10px;
        padding-left: 10px;
        width: 230px;
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
    .cl_mobile_show{
        display:none;
    }
    .cl_mobile_hidden{
        display:block;
    }
    .cl_display{
        display: flex !important;
    }
    .cl_img_member > img{
        width: 35px;
        height: 35px;
    }
    @media only screen and (max-width: 768px) {
        .div_flag{
        margin-left:unset;
        width:unset;
    }
        .country-market-flag img {
            width: 25px;
        }
        .cl_flag_country{
        flex-direction: row;
        padding-top: 20px;
    }
        .cl_display{
        flex-direction: column;
        padding-top: 20px;
        padding-bottom: 20px;
        font-size: large;
    }
    .cl_img_member{
        margin-bottom: 20px;
    }
    .cl_img_member > img{
        width: 100px;
        height: 100px;
    }
    .cl_title{
        border-left: 0px solid #FFFFFF;
        border-right: 0px solid #FFFFFF;
        margin-left: 0px;
        padding-left: 0px;
        width: 80%;
        margin: auto;
    }
    .cl_service {
        font-size: 0.75em;
        border-left: 0px solid #cccccc;
    }
    .cl_mobile_show{
        display:block;
    }
    .cl_mobile_hidden{
        display:none;
    }

    
}

</style>