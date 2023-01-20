<?php
// Template Name: SingleFranchise

get_header();


if (have_posts()) : while (have_posts()) : the_post();
        $post_id = get_the_ID();
        $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        $permalink = get_permalink();
        $title = get_the_title();
        $is_certified = get_field('listing_certification_status') == 'certification_finished';
        ?>

        <section class="single-franchise">
            <div class="container m-b-25">
                <a class="go-search font-weight-medium d-flex align-items-center" href="javascript: history.go(-1);">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back to your seach', 'idealbiz'); ?></span>
                </a>
            </div>
            <div class="container d-flex flex-row flex-wrap justify-content-around">

                <div class="col-md-12">
                    <div class="franchise position-relative p-b-15 dropshadow d-flex flex-column black--color white--background">

                        <div class="p-y-20 p-x-40 franchise-card position-relative d-flex flex-column font-weight-medium">
                            <div class="d-flex flex-row m-flex-column center-content">
                                <div style="align-items: center; justify-content: center;" class="w-200px p-20 d-flex o-hidden no-decoration">
                                    <img class="w-100 max-w-200 m-y-20" style="vertical-align: middle;" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" />
                                </div>
                                <div class="calc-100-120 h-100 mob-w-100 d-flex justify-content-between flex-column p-y-10 p-x-17">
                                    <div>
                                        <h2 class="font-weight-semi-bold"><?php the_title(); ?></h1>
                                    </div>
                                    <?php
                                            $term_obj_list = get_the_terms(get_the_ID(), 'franchise_cat');
                                            if ($term_obj_list) { ?>
                                        <span class="franchise_cat">
                                            <?php
                                                        $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
                                                        echo $terms_string;
                                                        ?>
                                        </span>
                                    <?php }
                                            $location_objs = get_the_terms(get_the_ID(), 'location');
                                            if ($location_objs) { ?>
                                        <span class="location p-t-10 font-weight-bold">
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
                            </div>
                        </div>
                        <div class="p-x-40 p-b-20">
                            <?php the_content(); ?>
                        </div>
                        <hr class="m-0 p-b-0 w-100 clear" />
                        <div class="d-flex flex-row p-x-20 justify-content-between align-items-center m-t-15 f-franchise">
                            <div class="social d-flex flex-row">
                                <a href="http://www.facebook.com/sharer.php?u=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-facebook"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>" target="_blank" rel="nofollow" class="m-x-15"><i class="icon-linkedin"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-twitter"></i></a>
                            </div>
                            <a href="#contact_form_id" class="btn btn-light-blue contact-seller popUpForm info-modal"><?php _e('Contact this partner', 'idealbiz'); ?></a>
                        </div>
                    </div>
                </div>
                <?php //echo do_shortcode(get_post_field('post_content', getIdByTemplate('single-franchise.php'))); ?>
            </div>
            <br />


        </section>

<?php endwhile;
endif; ?>

<?php get_footer(); ?>