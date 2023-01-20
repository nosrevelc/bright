<?php
// Template Name: SingleIpost
get_header();


if (have_posts()) : while (have_posts()) : the_post();
        $post_id = get_the_ID();
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'medium');
        $permalink = get_permalink();
        $title = get_the_title();
        $is_certified = get_field('listing_certification_status') == 'certification_finished';

        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'read_post_' . $post_id, true);
        }

        ?>

        <section class="single-listing md-m-t-35 md-m-b-130">
            <div class="container m-b-25 md-m-b-50">
                <a class="go-search font-weight-medium d-flex align-items-center" href="<?php echo get_permalink( pll_get_post(get_page_by_path( 'listings' )->ID )); ?>">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back to your seach', 'idealbiz'); ?></span>
                </a>
            </div>
            <div class="container d-flex flex-row flex-wrap justify-content-around align-items-center">

                <div class="col-12 col-lg-6">

                    <h2 class="listing-title"><?php echo $title; ?></h2>

                    <div class="d-flex flex-row flex-wrap">
                        <span class="m-r-20"><?php echo esc_html(get_field('category')->name);  ?></span>
                        <div class="location p-x-0 font-weight-bold">
                            <i class="icon-local"></i>
                            <span class="text-uppercase"><?php echo esc_html(get_field('location')->name);  ?></span>
                        </div>
                    </div>

                    <div class="listing-description m-t-30 m-b-50">
                        <?php echo the_content(); ?>
                    </div>

                    <div class="price m-b-50">
                        <h2 class="font-weight-bold light-blue--color"><?php _e('Price', 'idealbiz'); ?>: <?php echo IDB_Listing_Data::get_listing_value($post_id, 'price_type'); ?></h2>
                    </div>

                    <div class="listing-financial d-none font-weight-medium col-md-10 p-0">
                        <?php
                                set_query_var('post_id', $post_id);
                                get_template_part('/elements/listing/listing_financial');
                                ?>
                    </div>

                </div>

                <div class="col-12 col-lg-6">
                    <div class="listing position-relative p-b-15 dropshadow d-flex flex-column black--color white--background">
                        <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
                            <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
                        <?php endif; ?>
                        <div class="image" style="background-image:url(<?php echo get_field('featured_image')['sizes']['large']; ?>);"></div>
                        <div class="d-flex flex-column flex-md-row p-x-20 justify-content-between align-items-center m-t-15">
                            <div class="social d-flex flex-row m-t-5 m-b-5">
                                <a href="http://www.facebook.com/sharer.php?u=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-facebook"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>" target="_blank" rel="nofollow" class="m-x-15"><i class="icon-linkedin"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-twitter"></i></a>
                            </div> 
                            <div class="pull-right">
                                 <?php echo do_shortcode('[favorite_button post_id="'.$post_id.'" site_id="'.get_current_blog_id().'"]'); ?>
                                 <a href="#contact_form_id" class="btn btn-light-blue contact-seller popUpForm info-modal "><?php _e('Contact this seller', 'idealbiz'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php echo do_shortcode(get_post_field('post_content', getIdByTemplate('single-listing.php'))); ?>
            </div>

            <?php
                    $images = get_field('gallery');
                    if ($images) : ?>

                <div class="swiper-container gallery container m-t-80">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) : ?>
                            <div class="swiper-slide"> <img class="w-100 h-100" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" /></div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="swiper-container gallery-thumbs container m-t-20">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) : ?>
                            <div class="swiper-slide w-150px h-150px"> <img class="w-100 h-100" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" /></div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php endif; ?>


            <div class="container d-none flex-row justify-content-around m-t-100">
                <div class="d-flex flex-column col-md-9">
                    <?php get_template_part('/elements/listing/listing_data'); ?>
                </div>
                <div class="col-md-2"></div>
            </div>

            <?php //get_template_part('/elements/listing/contact_seller'); 
                    ?>




            <?php
                    /* contact expert or responsible */
                    /*
            $expert = get_field( "expert" );
            if($expert){
                ?>
                <br/>
                <br/>
                <br/>
                <div class="container d-flex flex-row flex-wrap justify-content-around">
                <div class="col-md-12">
                <div class="expert position-relative p-b-15 dropshadow d-flex flex-column black--color white--background">
                <div class="container text-center">
                    <h2 class="base_color--color m-t-30"><?php _e('Contact this Expert', 'idealbiz'); ?></h2>
                    <br>
                    <?php 

                    if(is_user_logged_in()){
                        /*
                        $post_author_id = get_post_field( 'post_author', $expert->ID );
                        $author_obj = get_user_by('id', $post_author_id);
                        echo '<div class="generic-form">
                                <div class="acf-form">';
                            echo do_shortcode('[fep_shortcode_new_message_form to="'.$author_obj->user_nicename.'" subject="'.__('Listing# ','idealbiz').$post_id.' - '.get_the_title().'" heading=""]');
                            echo '</div>
                            </div><style>.fep-form-field-fep_upload, .fep-form-field-message_title, .fep-form-field-message_content label {display: none;}</style>';
                        }else{
                        ?>
                        <p class="has-text-align-center"><?php _e('Get in touch with this expert, login now!', 'idealbiz'); ?></p>
                        <p class="has-text-align-center"><a class="login-register p-y-6 pointer register lrm-login btn-blue" href=""><?php _e('Login','idealbiz'); ?></a></p>
                        </div>
                        </div>
                        </div>
                        <?php
                    }
              
                    //comments_template();
                    ?> 
                </div>
                </div>
                </div>
                <?php
                //var_dump( get_field('owner'));
            }
                  */
            ?>  
            
            <script>
                    jQuery(document).ready(($) => {
                        <?php if(LISTING_SYSTEM=='1'){ ?>
                            $('input[name="listingsystem"]').val('1');
                            var aux_system = '<?php echo get_the_ID(); ?>|' + $('select[name="owner"] option:selected').val()+'|'+<?php echo wp_get_current_user()->ID; ?>+'';
                            $('input[name="listingsystem"]').val(aux_system);
                        <?php } ?>

                       // $('input[name="owner"]').val('<?php echo get_field('owner')["user_email"]; ?>');
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