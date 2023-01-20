<?php 
// Template Name: ResumesPage
get_header();
?>

<section class="page sell">
    <div class="container text-center m-t-30">
        <h1 class="m-b-15"><?php the_title(); ?></h1>
        <?php the_content(); ?>
    </div>
    <div class="container site-blocks m-b-15">
        <a class="go-search font-weight-medium d-flex align-items-center" href="javascript: history.go(-1);">
            <i class="icon-dropdown"></i>
            <span class="font-weight-bold m-l-5"><?php _e('Go back', 'idealbiz'); ?></span>
        </a>
    </div>
    <div class="site-blocks d-flex flex-row flex-wrap justify-content-start container m-t-25 m-b-25">
        <?php
        $sell_options = get_field('jobs_options');
        $so = 0;
        foreach ($sell_options as $option) {
            $color_box = 'none';
            if ($option['icon_color'] != '') {
                $color_box = $option['icon_color'];
            }
            ?>
            <div class="b-opts">
                <div class="b-opts-inner m-y-5">
                    <a href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>" data-sso="<?php echo $so; ?>" style="background-color: <?php echo $color_box; ?> ; border: 1px solid rgba(219, 219, 219, 0.1)" class="d-block w-200px h-200px block p-t-25 m-x-5 b-r-5 m-appicon <?php if ($option['required_login']) echo 'lrm-login'; ?>">
                        <?php if ($option['icon'] == '') { ?>
                            <img src="<?php echo $option['image']['url']; ?>" class="bo-svg" alt="<?php echo $option['title_desktop']; ?>" title="<?php echo $option['title_desktop']; ?>" />
                        <?php } else { ?>
                            <i class="white--color m-l-35 <?php echo $option['icon']; ?>"></i>
                        <?php } ?>
                        <h2 class="m-l-35 white--color d-none d-md-block first-line center-inblock"><?php echo str_replace_first(' ', '<br/>', $option['title_desktop']); ?></h2>
                    </a>
                    <div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke so-<?php echo $so; ?>">
                        <div class="b-opts-d-open-inner white--background">
                            <a href="#" class="b-opts-close"><i class="icon icon-close"></i></a>
                            <div class="mappoicon dropshadow p-t-25 m-x-10">
                                <div style="background-color:<?php echo $color_box; ?>" class="stroke dropshadow p-t-45 m-x-10 innerico">
                                    <?php
                                        if($option['icon']==''){ ?>
                                            <img src="<?php echo $option['image']['url']; ?>" class="bo-svg" alt="<?php echo $option['title_desktop']; ?>" title="<?php echo $option['title_desktop']; ?>"> <?php
                                        }else{ ?>
                                            <i class="white--color m-l-35 <?php echo $option['icon']; ?>"></i> <?php
                                        }
                                        echo '<h2 class="m-l-35 white--color d-none d-md-block">'.str_replace_first(' ', '<br/>', $option['title_desktop']).'</h2>';
                                    ?>
                                </div>
                            </div>
                            <div class="b-opts-body">
                                <h3 class="font-weight-semi-bold m-b-20"><?php echo $option['title_desktop']; ?></h3>
                                <p><?php echo $option['text']; ?></p>
                                <a class="btn btn-blue m-t-5 white--background h-36px l-h-18 <?php if ($option['required_login']) echo 'lrm-login'; ?>" href="<?php echo ($option['external_link'] ? $option['external_link'] : $option['button_link']); ?>"><?php _e('Selecionar', 'idealbiz'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="d-block d-md-none"><?php echo $option['title']; ?></span>
            </div>

        <?php $so++;
        } ?>
    </div>

</section>

<?php get_footer(); 

/*
?>
get_header(); 
 ?>

<?php

 if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $meta = get_post_meta( $post->ID, 'post_fields', true ); 
    ?>



<?php endwhile; endif; ?>

<section class="page page-jobs">
    <div class="container text-center m-t-30">
        <?php
                 if(isset($_GET['job_bm_job_archive_search_hidden'])){
                    echo '<h1>'.__("Job Search Results:",'idealbiz').'<br/>';
                    echo '<span class="extra_small-font">'.'(<span class="s-results"></span> '.__('Results found','idealbiz').')</span></h1>';
                }else{
                    echo '<h1>'.get_the_title().'</h1>'; 
                }

        ?>
    </div>

    <div class="d-flex flex-column flex-wrap justify-content-start container m-t-30">
        <?php
        wp_nav_menu(
            array(
                'theme_location'    => 'jobs-menu',
                'depth'             => 1,
                'container'         => 'ul',
                'container_class'   => 'nav navbar-nav navbar-right',
                'menu_class'        => 'jobs-menu',
                'fallback_cb'       => 'false'
            )
        );
        ?>
    </div>


    <div class="d-flex flex-column flex-wrap justify-content-start container m-t-5">
        <div class="generic-form">
            <div class="acf-form">
                <?php the_content(); ?>
            </div>
        </div>
    </div>



</section>

<?php get_footer(); ?>