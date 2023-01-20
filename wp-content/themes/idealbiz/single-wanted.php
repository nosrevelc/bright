<?php 
// Template Name: SingleWanted
get_header();

if (have_posts()) : while (have_posts()) : the_post();
        $post_id = get_the_ID();
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'medium');
        $permalink = get_permalink();
        $title = get_the_title();
        $ref = get_the_ID();
        $is_certified = get_field('listing_certification_status') == 'certification_finished';
        $cl_expert = get_field('expert');
        $cl_expert_id = get_field('expert')->ID;


        $post_args = array(
            'posts_per_page' => -1,
            'post_type' => 'expert',
            'post_status' => 'publish'
        );
        $myposts = get_posts($post_args);
        foreach ($myposts as $post) {
            if (get_field('idealbiz_support_expert',$post->ID) == '1'  && $cl_expert_id == null){
                $cl_expert_id =  $post->ID;
                /* echo $cl_expert_id ; */
            }
            
        }

        

        ?>

        <section class="single-listing md-m-t-35 md-m-b-130">
            <div class="container m-b-25 md-m-b-50">
                <a class="go-search font-weight-medium d-flex align-items-center" href="javascript: history.go(-1);">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back to your seach', 'idealbiz'); ?></span>
                </a>
            </div>
            <div class="black--color white--background dropshadow p-t-20 p-b-20 container d-flex flex-row flex-wrap justify-content-around align-items-center">

                <div class="col-md-5">

                    <h2 class="listing-title"><?php echo $title; ?></h2>

                    <div class="listing-description m-t-30 m-b-50">
                        <?php echo the_content(); ?>
                    </div>
 
                   

                    <div class="listing-financial d-none font-weight-medium col-md-10 p-0">
                        <?php
                                set_query_var('post_id', $post_id);
                                get_template_part('/elements/listing/listing_financial');
                                ?>
                    </div>
                    <!-- INÍCIO COD CARTÃO MEMBER NO DESKTOP -->
                    <div class="m-t-15 hidden-mobile">
                    <?php 
                    $args = array(
                        'post_status' => 'publish',
                        'posts_per_page' => 1,
                        'post_type' => 'expert',
                        'p' => $cl_expert_id,
                    );
                    $experts = new WP_Query( $args );

                    if ($experts->have_posts()) {
                        while ($experts->have_posts()) : $experts->the_post();
                        ?>
                        <div class="expert-card-2">
                        <?php get_template_part('/elements/listing/experts_cad_listing'); ?>
                        </div>   
                        <?php
                        endwhile;
                    }
                    wp_reset_postdata();
                    
                    ?>
                    </div>
                    <!-- FINAL COD CARTÃO MEMBER NO DESKTOP -->

                </div>

                <div class="col-md-6">
                    <div class="listing position-relative p-b-15 dropshadow d-flex flex-column black--color white--background">
                        <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
                            <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
                        <?php endif; ?>
                        <div class="image" style="background-image:url(<?php echo get_field('featured_image')['sizes']['large']; ?>);"></div>
                        <div class="d-flex flex-column flex-md-row p-x-20 justify-content-between align-items-center m-t-15">
                            <div class="social d-flex flex-row">
                                <a href="http://www.facebook.com/sharer.php?u=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-facebook"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>" target="_blank" rel="nofollow" class="m-x-15"><i class="icon-linkedin"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-twitter"></i></a>
                            </div>
                            <a href="#contact_form_id"  class="btn btn-light-blue contact-seller popUpForm info-modal m-t-5 md-m-t-0"><?php _e('Contact this buyer', 'idealbiz'); ?></a>
                           </div>
                    </div>
                </div>
                <!-- INÍCIO COD CARTÃO MEMBER NO TELEMÓVEL -->
                 <div class="m-t-15 hidden-desktop">
                    <?php 
                    $args = array(
                        'post_status' => 'publish',
                        'posts_per_page' => 1,
                        'post_type' => 'expert',
                        'p' => $cl_expert_id,
                    );
                    $experts = new WP_Query( $args );

                    if ($experts->have_posts()) :
                        while ($experts->have_posts()) : $experts->the_post();
                        ?>
                        <div class="expert-card-2">
                        <?php get_template_part('/elements/listing/experts_cad_listing'); ?>
                        </div>   
                        <?php
                        endwhile;
                    else :
                    get_template_part('/elements/no_results');
                    endif;
                    wp_reset_postdata();
                    
                    ?>
                    </div>
                    <!-- FINAL COD CARTÃO MEMBER NO TELEMÓVEL -->

                <?php echo do_shortcode( get_post_field('post_content',getIdByTemplate('single-wanted.php'))); ?>
            </div>

            <?php //get_template_part('/elements/listing/contact_seller'); ?>

            <script>
                    jQuery(document).ready(($) => {
                       // $('input[name="owner"]').val('<?php echo get_field('owner')["user_email"]; ?>');

                       <?php if(LISTING_SYSTEM=='1'){ ?>
                            $('input[name="listingsystem"]').val('1');
                            var aux_system = '<?php echo get_the_ID(); ?>|' + $('select[name="owner"] option:selected').val()+'|'+<?php echo wp_get_current_user()->ID; ?>+'';
                            $('input[name="listingsystem"]').val(aux_system);
                        <?php } ?>

                        
                        $('input[name="current_user_email"]').val('<?php echo wp_get_current_user()->user_email; ?>');
                        $('input[name="post_title"]').val('<?php the_title(); ?>');
                        $('input[name="post_link"]').val('<?php the_permalink(); ?>');
                        $('input[name="costumercare"]').val('<?php echo get_field('costumer_care_email', 'options'); ?>');
                        $('input[name="owner_name"]').val($('select[name="owner"] option:selected').text());
                        $('input[name="lang"]').val('<?php echo get_user_locale(); ?>');
                        $('select[name="owner"]').on('change', function() {
                            $('input[name="owner_name"]').val($('select[name="owner"] option:selected').text());
                            <?php if(LISTING_SYSTEM=='1'){ ?>
                                aux_system = '<?php echo get_the_ID(); ?>|' + $('select[name="owner"] option:selected').val()+'|'+<?php echo wp_get_current_user()->ID; ?>+'';
                                $('input[name="listingsystem"]').val(aux_system);
                                console.log(aux_system);
                            <?php } ?> 
                        });
                    });
            </script>


        </section>

<?php endwhile;
endif; ?>

<?php get_footer(); ?>