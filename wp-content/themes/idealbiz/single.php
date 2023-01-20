<?php
get_header();


$author_id = get_post_field ('post_author', $cause_id);
$cl_author_name = get_the_author_meta( 'display_name' , $author_id ); 



if (have_posts()) : while (have_posts()) : the_post();
        $post_id = get_the_ID();
        $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        $permalink = get_permalink();
        $title = get_the_title();
        $is_certified = get_field('listing_certification_status') == 'certification_finished';
        ?>

        <section class="single-blog">

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
                             <div class="p-x-20 p-b-20 p-t-20">
                                <?php if(has_post_thumbnail(get_the_ID())){ ?>
                                    <img class="w-100 max-w-blog m-b-20 " style="margin-right: 20px; vertical-align: middle;float: left; align-items: center; justify-content: center;" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" />
                                <?php } ?>
                                    <h1 class="font-weight-semi-bold base_color--color"><?php the_title(); ?></h1>
                                    <?php 
                                $term_obj_list = get_the_terms(get_the_ID(), 'category' );
                                if( $term_obj_list){
                                    echo '<ul class="m-b-5" style="list-style: none; padding-left: 0;">';
                                    foreach ($term_obj_list as $term) {
                                        echo '<li><a href="'. $url =  get_permalink( get_option( 'page_for_posts' ) ) . '?type=post&category=' . $term->term_id.'">&bull;  '.$term->name.'</a></li>';
                                    }
                                    echo '</ul>'; 
                                }
                                ?>
                        
                            <?php the_content(); ?>
                            <div class="cl_author"><?php _e('str_Autor','idealbiz'); echo ' : '.$cl_author_name;?></div>
                        </div>
                        <hr class="m-0 p-b-0 w-100 clear" />
                        <div class="d-flex flex-row p-x-20 justify-content-between align-items-center m-t-15 f-franchise">
                            <div class="social d-flex flex-row" style="text-align:right">
                                <a href="http://www.facebook.com/sharer.php?u=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-facebook"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>" target="_blank" rel="nofollow" class="m-x-15"><i class="icon-linkedin"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-twitter"></i></a>
                            </div>
                           </div>
                    </div>
                </div>
            </div>
            <br />


        </section>

<?php endwhile;
endif; ?>

<?php get_footer(); ?>

<style>
    .cl_author{
        font-size:smaller;
    }
    .cl_author{ 
        text-transform: uppercase;
        }
</style>