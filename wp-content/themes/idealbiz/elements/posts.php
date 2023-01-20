
<div class="col-md-6 col-sm-12 m-b-30">
    <div class="p-20 blog-card position-relative d-flex flex-column black--color white--background dropshadow font-weight-medium">
        <div class="d-flex flex-row m-flex-column center-content">
            <?php if(has_post_thumbnail(get_the_ID())){ ?>
            <a href="<?php echo the_permalink(); ?>" style="align-items: center; justify-content: center;height: 100%;" class="w-250px p-10 d-flex o-hidden no-decoration">
                <img class="w-100" style="vertical-align: middle; object-fit: cover;height: 100%; width: 100%; height: 188px;" src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>">
            </a>
            <?php } ?>
                <div class="calc-100-120 mob-w-100 h-100 d-flex justify-content-around flex-column p-y-20 p-x-17">
                        <a href="<?php echo the_permalink(); ?>">
                            <h2 class="font-weight-semi-bold m-b-5"><?php the_title(); ?></h2>
                        </a>
                        <?php
                        $term_obj_list = get_the_terms(get_the_ID(), 'category' );
                        if( $term_obj_list){
                            echo '<ul class="m-b-5">';
                            foreach ($term_obj_list as $term) {
                                echo '<li><a href="'. $url =  get_permalink( get_option( 'page_for_posts' ) ) . '?type=post&category=' . $term->term_id.'">'.$term->name.'</a></li>';
                            }
                            echo '</ul>'; 
                        }
                        ?>
                        <div class="clear"></div>
                        <?php
                            $text = strip_shortcodes( get_the_content() );
                            $text = apply_filters( 'the_content', $text );
                            $text = str_replace(']]>', ']]&gt;', $text);
                            $excerpt_length = apply_filters( 'excerpt_length', 10 );
                            $excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
                            $text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
                            echo $text;
                        ?>
                         <a href="<?php echo the_permalink(); ?>"><?php _e('Read more', 'idealbiz'); echo ' ›'; ?></a>
                </div>
        </div>
    </div>    
</div>
