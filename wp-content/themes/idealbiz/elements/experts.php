<?php
$is_certified = get_field('listing_certification_status') == 'certification_finished';
?>
<div class="col-lg-4 col-md-6 col-sm-12 m-b-30">
    <div class="p-20 expert-card position-relative d-flex flex-column black--color white--background dropshadow font-weight-medium">
    <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
        <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
    <?php endif; ?>
        <div class="d-flex flex-row center-content">
            <a href="<?php echo the_permalink(); ?>" class="w-100px h-100px b-r d-block o-hidden no-decoration">
                <img class="w-100 h-100 object-cover" src="<?php echo get_field('foto')['sizes']['medium']; ?>">
            </a>
            <div class="h-100 d-flex justify-content-between flex-column p-y-10 p-x-17" style="width: calc(100% - 96px);">
                <a href="<?php echo the_permalink(); ?>">
                    <h3 class="font-weight-semi-bold"><?php the_title(); ?></h3> 
                </a>
                <?php
                $term_obj_list = get_the_terms(get_the_ID(), 'service_cat' );
                if( $term_obj_list){ ?>
                    <span class="small">
                    <?php
                        $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
                        echo $terms_string;
                    ?>
                    </span>
                <?php }
                $location_objs = get_the_terms(get_the_ID(), 'location' );
                if( $location_objs){ ?>
                <span class="small location p-t-10 font-weight-bold">
                    <i class="icon-local"></i>
                    <span class="text-uppercase">
                        <?php
                            $location_string = join(', ', wp_list_pluck($location_objs, 'name'));
                            echo $location_string;
                        ?>
                    </span>
                </span>
                <?php } ?>
            </div>
            <div class="w-20px h-100px d-flex flex-column side-icons" style="display:none !important;">
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
    </div>    
</div>
