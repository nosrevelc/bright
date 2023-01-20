<?php
// Template Name: Wanted Businesses
get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = (new IDB_Wanted)->posts_per_page;

$args = array(
    'posts_per_page' => $posts_per_page,
    'post_type' => 'wanted',
    'post_status' => 'publish',
    'paged' => $paged
);

$wanted = new WP_Query($args);
$total = $wanted->found_posts;

?>
<section class="container">
<div>
<?php the_content(); ?>
</div>
</section>

<nav class="sidebar-filters">
    <form class="filters d-block d-md-none">
        <div class="filters-container col-md-10">
            <h2 class="font-weight-bold"><?php _e('Filter your search', 'idealbiz'); ?></h2>
            <div class="filter m-t-15">
                <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Category', 'idealbiz'); ?></h3>
                <?php get_template_part('/elements/filters/category'); ?>
            </div>
            <div class="filter m-y-15">
                <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Location', 'idealbiz'); ?></h3>
                <?php get_template_part('/elements/filters/location'); ?>
            </div>
            <div class="d-none flex-row justify-content-between m-t-50">
                <a class="btn-blue normal-line-height" href="#"><?php _e('Apply Filters', 'idealbiz'); ?></a>
            </div>
        </div>
    </form>
</nav>

<section class="page wanted-businesses listing-page position-relative container medium-width">
    <div class="loading d-none w-100 h-100">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="row m-t-30">
        <form class="d-none d-md-block col-md-4 filters">
            <div class="filters-container col-md-12 col-lg-10 dropshadow box">
                <h2 class="font-weight-bold"><?php _e('Filter your search', 'idealbiz'); ?></h2>
        
                <div class="filter m-t-25">
                    <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Category', 'idealbiz'); ?></h3>
                    <?php get_template_part('/elements/filters/category'); ?>
                </div>
                <div class="filter m-y-35">
                    <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Location', 'idealbiz'); ?></h3>
                    <?php get_template_part('/elements/filters/location'); ?>
                </div>

                <div class="d-none flex-row justify-content-between m-t-50">
                    <a class="btn-blue normal-line-height" href="#">Aplicar Filtros</a>
                </div>
            </div>
        </form>
        <div class="col-md-8 listings">
            <div class="row count-order">
                <div class="m-r-50">
                    <h3 class="results-count base_color--color font-weight-bold"><?php Component_Listings::results_count($paged, $posts_per_page, $total); ?></h3>
                </div>
            </div>
            <h3 class="d-block d-md-none m-l-10 m-t-10 filter-sidebar-collapse light-blue--color"><?php _e('Filters', 'idealbiz'); ?></h3>
            <div class="">
                <div class="row listings-container">
                    <?php
                    if ($wanted->have_posts()) :
                        while ($wanted->have_posts()) : $wanted->the_post();
                            get_template_part('/elements/wanted');
                        endwhile;
                    else :
                        get_template_part('/elements/no_results');
                    endif;
                    ?>
                </div>
                <div class="pagination d-flex justify-content-center m-t-30">
                    <?php
                    $total_pages = $wanted->max_num_pages;
                    Component_Listings::pagination($total_pages);
                    ?>
                </div>
            </div>
        </div>
        <div class="d-none ajax-data" data-ajax-url="<?php echo site_url() . '/wp-admin/admin-ajax.php?lang=' . ICL_LANGUAGE_CODE;?>"></div>
    </div>
</section>

<div class="sidebar-overlay"></div>

<?php get_footer(); ?>