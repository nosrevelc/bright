<?php
$is_certified = get_field('listing_certification_status') == 'certification_finished';
$cl_margen_partner = 'm-10';
?>
<div class="col-lg-15 col-md-15 col-sm-15 m-b-15 <?php echo $cl_margen_partner;?>">
    <div class="p-20 position-relative d-flex flex-column  black--color white--background dropshadow font-weight-medium">
    <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
        <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
    <?php endif; ?>
        <div class="d-flex flex-row center-content w-100px h-100px">
            <a href="<?php echo the_permalink(); ?>" class="w-100px h-100px d-block o-hidden no-decoration">
                <img class="w-100 object-cover" src="<?php echo get_field('foto')['sizes']['medium']; ?>">
            </a>
            <div class="w-100px h-100px d-flex flex-column side-icons" style="display:none !important;">
                <?php 
                $email = get_field('expert_email');
                    echo '<a title="'.get_the_title().'" href="'.get_the_permalink().'"><i class="icofont-envelope"></i></a>';
                ?>
                <?php 
                $phone = get_field('expert_phone');
                    if($phone!=''){
                        //echo '<a title="'.$phone.'" href="tel:'.$phone.'"><i class="icofont-phone"></i></a>';
                    }
                ?>
            </div>
        </div>
<!--         <?php
                $term_obj_list = get_the_terms(get_the_ID(), 'service_cat' );
                if( $term_obj_list){ ?>
                    <span class="small text-center">
                    <?php
                        $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
                        echo $terms_string;
                    ?>
                    </span>
              <?php   }

                $location_objs = get_the_terms(get_the_ID(), 'location' );
                if( $location_objs){ ?>
                <span class="small location p-t-10 font-weight-bold text-center">
                    <i class="icon-local"></i>
                    <span class="text-uppercase">
                        <?php
                            $location_string = join(', ', wp_list_pluck($location_objs, 'name'));
                            echo $location_string;
                        ?>
                    </span>
                </span>
                <?php } ?> -->
    </div>    
</div>
