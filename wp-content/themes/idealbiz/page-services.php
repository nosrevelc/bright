<?php
// Template Name: Services
get_header();
?>

<section class="page services">
    <div class="container text-center m-t-30">
        <h1 class="m-h3 m-b-15"><?php the_title(); ?></h1>
        <?php the_content(); ?>
    </div>
    <div class="site-blocks d-flex flex-row flex-wrap justify-content-start container m-t-25 m-b-25">
        <?php

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'counseling',
            'post_status' => 'publish'
        );

        $counselings = new WP_Query($args);
        $total = $counselings->found_posts;

        // $counselings = get_field('support_services');
        if ($counselings->have_posts()) :
            while ($counselings->have_posts()) : 
            $counselings->the_post();
            $counseling = get_fields(get_the_ID());

            $permLink= get_permalink();
            if($counseling['page_link'] != ''){
                $permLink = $counseling['page_link'];
            }
            if($counseling['button_link'] != ''){
                $permLink = $counseling['button_link'];
            }

            $color_box = 'none';
                if($counseling['icon_color'] != ''){
                    $color_box = $counseling['icon_color'];
                }
                ?>
                <div class="b-opts">
                    <div class="b-opts-inner m-y-5">
                        <a href="<?php echo $permLink; ?>" style="background-color: <?php echo $color_box; ?> ;" class="d-block w-200px h-200px block stroke p-t-25 m-x-5 b-r-5 m-appicon">
                            <?php if($counseling['image']['url']!=''){ ?>
                                <img src="<?php echo $counseling['image']['url']; ?>" class="bo-svg" alt="<?php echo $counseling['title_desktop']; ?>" title="<?php echo $counseling['title_desktop']; ?>" />
                            <?php }else if($counseling['icon']!=''){ ?>
                                <i class="white--color m-l-35 <?php echo $counseling['icon']; ?>"></i>
                                <?php }else{  ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/information.png" class="bo-svg" alt="<?php echo $counseling['title_desktop']; ?>" title="<?php echo $counseling['title_desktop']; ?>" /> 
                                <?php } ?>
                            <h2 class="m-l-35 white--color d-none d-md-block first-line center-inblock"><?php echo $counseling['title_desktop']; ?></h2>
                        </a>
 
                        <div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke" >
                            <div class="b-opts-d-open-inner white--background">
                                <a href="#" class="b-opts-close"><i class="icon icon-close"></i></a>
                                <div class="mappoicon dropshadow p-t-25 m-x-10">
                                    <div style="background-color: <?php echo $color_box; ?> ;" class="stroke dropshadow p-t-45 m-x-10 innerico">
                                    <?php if($counseling['image']['url']!=''){ ?>
                                            <img src="<?php echo $counseling['image']['url']; ?>" class="bo-svg" alt="<?php echo $counseling['title_desktop']; ?>" title="<?php echo $counseling['title_desktop']; ?>" />
                                        <?php }else if($counseling['icon']!=''){ ?>
                                            <i class="white--color m-l-35 <?php echo $counseling['icon']; ?>"></i>
                                        <?php }else{  ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/information.png" class="bo-svg" alt="<?php echo $counseling['title_desktop']; ?>" title="<?php echo $counseling['title_desktop']; ?>" /> 
                                        <?php } ?>
                                        <h2 class="m-l-35 white--color d-none d-md-block"><?php echo $counseling['title_desktop']; ?></h2>
                                    </div>
                                </div>
                                <div class="b-opts-body">
                                    <h3 class="font-weight-semi-bold m-b-20"><?php echo $counseling['title_desktop']; ?></h3>
                                    <p><?php echo $counseling['text']; ?></p>
                                    <a class="btn btn-blue m-t-5 white--background l-h-30" href="<?php echo $permLink; ?>"><?php _e('Selecionar','idealbiz'); ?></a>
                                </div>
                            </div>    
                        </div>
                    </div>    
                    <span class="d-block d-md-none"><?php echo $counseling['title']; ?></span>    
                </div>    
            <?php
            endwhile;
        else :
            get_template_part('/elements/no_results');
        endif;
        ?>
    </div>

</section>

<?php get_footer(); ?>