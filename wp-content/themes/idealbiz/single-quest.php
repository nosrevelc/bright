<?php
// Template Name: SatisfactionForm

get_header();

if (have_posts()) : while (have_posts()) : the_post();
        $post_id = get_the_ID();
        $permalink = get_permalink();
        $title = get_the_title();
        ?>
        <section class="single-quest">
            <div class="container d-flex flex-row flex-wrap justify-content-around m-t-25 m-b-10">
                <h1><?php echo $title; ?></h1>
                <div class="col-md-12 m-t-20">
                    <div class="m-w-760 position-relative dropshadow d-flex white--background">
                            <div class="d-flex flex-row p-40 justify-content-between align-items-center m-t-0">
                                <div class="listing-description m-t-0 m-b-20">
                                    <?php the_content(); ?>
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