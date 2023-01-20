
<div class="col-md-12 col-sm-12 m-b-30">
    <div class="p-20 franchise-card position-relative d-flex flex-column black--color white--background dropshadow font-weight-medium">
        <div class="d-flex flex-row m-flex-column center-content">
            <a href="<?php echo the_permalink(); ?>" style="align-items: center; justify-content: center;" class="w-200px p-20 d-flex o-hidden no-decoration">
                <img class="w-100" style="vertical-align: middle;" src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>">
            </a>
                <div class="calc-100-120 mob-w-100 h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">
                        <a href="<?php echo the_permalink(); ?>">
                            <h2 class="font-weight-semi-bold"><?php the_title(); ?></h2>
                        </a>
                        <?php
                        $term_obj_list = get_the_terms(get_the_ID(), 'franchise_cat' );
                        if( $term_obj_list){ ?>
                            
                            <?php
                                $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
                                echo $terms_string;
                            ?>
                            </span>
                        <?php }
                        $location_objs = get_the_terms(get_the_ID(), 'location' );
                        if( $location_objs){ ?>
                        <span class="small location p-t-0 l-h-28 font-weight-bold">
                            <i class="icon-local"></i>
                            <span class="text-uppercase">
                                <?php
                                    $location_string = join(', ', wp_list_pluck($location_objs, 'name'));
                                    echo $location_string;
                                ?>
                            </span>
                        </span>
                        <?php } ?>
                        <div class="clear"></div>
                        <?php
                            $text = strip_shortcodes( get_the_content() );
                            $text = apply_filters( 'the_content', $text );
                            $text = str_replace(']]>', ']]&gt;', $text);
                            $excerpt_length = apply_filters( 'excerpt_length', 55 );
                            $excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
                            $text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
                            echo $text;
                        ?>
                         <a href="<?php echo the_permalink(); ?>"><?php _e('Read more', 'idealbiz'); echo ' ›'; ?></a>
                </div>
        </div>
    </div>    
</div>
