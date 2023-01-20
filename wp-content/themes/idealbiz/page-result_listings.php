<?php
// Template Name: Result Search Listings

/**
 * Criada pelo Cleverson
 * Esta página tem a finalidade de exibir os resultados
 * da busca de listings no sistema e não pode hover outra página
 * com mesmo template no sistema
 */
get_header();

$cl_stamp = get_field('stamp');
$img_ico = get_field('icon_stamp');
$cl_icon_stamp = $img_ico['image']['url'];

echo $img_ico['image']['url'];


$postads = get_post(get_the_ID()); 
$pcontent = $postads->post_content;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = (new IDB_Listings)->posts_per_page;


$cl_listing_cat = get_field('listing_category');
/* var_dump($cl_listing_cat); */


$current_blod_id = get_current_blog_id();
$broadcasted_children = array();
$q_broadcasted="
SELECT *
FROM ib__3wp_broadcast_broadcastdata
WHERE blog_id = {$current_blod_id}
";
$countries = array();
$bposts= array();
$r_breadcasted = $wpdb->get_results($q_broadcasted, ARRAY_A);
    foreach($r_breadcasted as $kr_breadcasted => $b){
        $b_aux= array();
        $b_aux['post_id'] = $b['post_id'];
        $b_aux['blog_id'] = $b['blog_id'];
        $b_aux['data'] = unserialize(base64_decode($b['data']));
        if($b_aux['data']["linked_parent"]){
            $broadcasted_children[] = $b_aux;
            $countries[$b_aux['data']["linked_parent"]['blog_id']][] = $b['post_id'];
        }

    }

    if ($cl_listing_cat = get_field('listing_category')){

        $arrIds= array();
        $args1 = array(
            'posts_per_page' => -1,
            'post_type' => 'listing',
            'post_status' => 'publish',
            'boost' => 'highlight',
            'tax_query' => array(
                array(
                    'taxonomy' => 'listing_cat',        // taxonomy name
                    'field' => 'cat',           // term_id, slug or name
                    'terms' => $cl_listing_cat // term id, term slug or term name
                ) 
        ));

        $listings1 = new WP_Query($args1);
        if ( $listings1->have_posts() ) {
            while ( $listings1->have_posts() ) {
                $listings1->the_post();
                // echo the_title();
                array_push($arrIds, get_the_ID());

            }
        }


        // var_dump($arrIds);
        $args2 = array(
            'posts_per_page' => -1,
            'post_type' => 'listing',
            'post_status' => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'boost',
                    'operator' => 'NOT EXISTS'
                ),
                array(
                    'taxonomy' => 'listing_cat',        // taxonomy name
                    'field' => 'cat',           // term_id, slug or name
                    'terms' => $cl_listing_cat // term id, term slug or term name
                ) 
                )
        );
        $listings2 = new WP_Query($args2);
        if ( $listings2->have_posts() ) {
            while ( $listings2->have_posts() ) {
                $listings2->the_post();
            
                array_push($arrIds, get_the_ID());
                
            }
        }

        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type' => 'listing',
            'post_status' => 'publish',
            'paged' => $paged,
            'post__in' => $arrIds,
            'tax_query' => array(
                array(
                    'taxonomy' => 'listing_cat',        // taxonomy name
                    'field' => 'cat',           // term_id, slug or name
                    'terms' => $cl_listing_cat // term id, term slug or term name
                ) 
        )
        );
    }else{

            $arrIds= array();
        $args1 = array(
            'posts_per_page' => -1,
            'post_type' => 'listing',
            'post_status' => 'publish',
            'boost' => 'highlight',
        );

        $listings1 = new WP_Query($args1);
        if ( $listings1->have_posts() ) {
            while ( $listings1->have_posts() ) {
                $listings1->the_post();
                // echo the_title();
                array_push($arrIds, get_the_ID());
            }
        }


        // var_dump($arrIds);
        $args2 = array(
            'posts_per_page' => -1,
            'post_type' => 'listing',
            'post_status' => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'boost',
                    'operator' => 'NOT EXISTS'
                ) 
                )
        );
        $listings2 = new WP_Query($args2);
        if ( $listings2->have_posts() ) {
            while ( $listings2->have_posts() ) {
                $listings2->the_post();
            
                array_push($arrIds, get_the_ID());
            }
        }

        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type' => 'listing',
            'post_status' => 'publish',
            'paged' => $paged,
            'post__in' => $arrIds
        );

}


$listings = new WP_Query($args);
$total = $listings->found_posts;


?>


<section class="container">
<div>
<?php echo $pcontent; ?> 
</div>
</section>


<nav class="sidebar-filters">
    <input name="post__in" id="post__in" type="hidden" value="<?php echo implode(',', $arrIds); ?>">
    <input type="hidden" name="org_post_in" id="org_post_in" value="<?php echo implode(',', $arrIds); ?>"/>
    <form class="filters d-block d-md-none">
        <div class="filters-container col-md-10">
            <h2 class="font-weight-bold"><?php _e('Filter your search', 'idealbiz'); ?></h2>
            <div class="filter m-t-15">
                <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Age of Listing', 'idealbiz'); ?></h3>
                <?php get_template_part('/elements/filters/dates'); ?>
            </div>
            <div class="filter m-y-15">
                <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Price', 'idealbiz'); ?></h3>
                <?php get_template_part('/elements/filters/price'); ?>
            </div>

            <div class="filter m-t-15">
                <h3 class="d-inline-block font-weight-bold m-b-25"><?php _e('Certification', 'idealbiz'); ?></h3>
                <?php get_template_part('/elements/filters/certification'); ?>
            </div>
            <div class="filter m-t-15">
                <h3 class="d-inline-block font-weight-bold m-b-25 m-t-25"><?php _e('Golden Visa', 'idealbiz'); ?></h3>
                <?php get_template_part('/elements/filters/golden'); ?>
            </div>
                <?php
                $cn='';
                foreach($countries as $country){

                    $country_iso = get_blog_option($country,'country_market');
                    if($country_iso){
                        $country_name = getCountry($country_iso)['country'];
                        $country_flag= DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.strtolower($country_iso).'.png';
                        $cn.='<label class="custom-control custom-checkbox idealbiz-checkbox broadcasted m-b-15">
                                <input type="checkbox" class="custom-control-input" name="broadcasted-country[]">
                                <span class="custom-control-label" for="broadcasted-country">
                                    <img style="width: 55px;" src="'.$country_flag.'" alt="'.$country_name.'"> '.$country_name.'
                                </span>
                            </label>';
                    }
                } 
                if($cn!=''){
                    echo '<div class="filter m-t-15">
                                <h3 class="d-inline-block font-weight-bold m-b-15">'.__('Broadcasted Listings', 'idealbiz').'</h3>';
                    echo $cn.'</div>';
                }
                ?>
             <?php echo '<a class="btn-blue text-center" href="'.getLinkByTemplate('page-result_listings.php').'" title="'.__('Reset Filter','idealbiz').'">'.__('Reset Filter','idealbiz').'</a>'; ?>
            <div class="d-none flex-row justify-content-between m-t-50">
                <a class="btn-blue normal-line-height" href="#">Aplicar Filtros</a>
            </div>
        </div>
    </form>

</nav>

<section class="listing-page position-relative container medium-width">
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
                    <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Age of Listing', 'idealbiz'); ?></h3>
                    <?php get_template_part('/elements/filters/dates'); ?>
                </div>
                <div class="filter m-y-35">
                    <h3 class="d-inline-block font-weight-bold m-b-15"><?php _e('Price', 'idealbiz'); ?></h3>
                    <?php get_template_part('/elements/filters/price'); ?>
                </div>


                <div class="filter">
                    <h3 class="d-inline-block font-weight-bold m-b-25"><?php _e('Certification', 'idealbiz'); ?></h3>
                    <?php get_template_part('/elements/filters/certification'); ?>
                </div>
                <div class="filter">
                    <h3 class="d-inline-block font-weight-bold m-b-25 m-t-25"><?php _e('Golden Visa', 'idealbiz'); ?></h3>
                    <?php get_template_part('/elements/filters/golden'); ?>
                </div>

                  <?php
                    $cn='';
                    foreach($countries as $k => $country){

                        $country_iso = get_blog_option($k,'country_market');
                        if($country_iso){
                            $country_name = getCountry($country_iso)['country'];
                            $country_flag= DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.strtolower($country_iso).'.png';
                            $cn.='<label class="custom-control custom-checkbox idealbiz-checkbox broadcasted m-b-15">
                                    <input type="checkbox" value="'.implode(',', $country).'" class="custom-control-input" name="broadcasted-country[]">
                                    <span class="custom-control-label" for="broadcasted-country">
                                        <img style="width: 38px; height: 25px;" src="'.$country_flag.'" alt="'.$country_name.'"> '.$country_name.'
                                    </span>
                                </label>';
                        }
                    }
                    if($cn!=''){
                        echo '<div class="filter m-t-15">
                                    <h3 class="d-inline-block font-weight-bold m-b-15 m-t-15">'.__('Broadcasted Listings', 'idealbiz').'</h3>';
                        echo $cn.'</div>';
                    }
                    ?>
                 <?php echo '<a class="btn-blue text-center" href="'.getLinkByTemplate('page-result_listings.php').'" title="'.__('Reset Filter','idealbiz').'">'.__('Reset Filter','idealbiz').'</a>'; ?>
                <div class="d-none flex-row justify-content-between m-t-50">
                    <a class="btn-blue normal-line-height" href="#">Aplicar Filtros</a>
                </div>
            </div>
           
        </form>
        <div class="col-md-8 listings">
            <div class="row count-order">
                <div class="m-r-50">
                    <p class="results-count base_color--color font-weight-bold"><?php Component_Listings::results_count($paged, $posts_per_page, $total); ?></p>
                </div>
                <div class="order-by row">
                    <p class="base_color--color font-weight-bold"><?php _e('Order by:', 'idealbiz'); ?></p>
                    <div class="dropdown m-l-10" style="z-index: 99999;">
                        <a class="dropdown-toggle light-blue--color" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <h3 class="d-inline-block"></h3>
                            <i class="icon-dropdown"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" data-order="" href="#"><?php _e('Insertion Date', 'idealbiz'); ?></a>
                            <a class="dropdown-item" data-order="price-asc" href="#"><?php _e('Price: low to high', 'idealbiz'); ?></a>
                            <a class="dropdown-item" data-order="price-desc" href="#"><?php _e('Price: high to low', 'idealbiz'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="m-t-0 m-l-40 d-none">
                    <?php _e('Search in all Idealbiz sites:') ?>
                    <label class="switch">
                        <input id="search-all-sites" type="checkbox"> 
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <h3 class="d-block d-md-none m-l-10 m-t-10 filter-sidebar-collapse light-blue--color"><?php _e('Filters', 'idealbiz'); ?></h3>
            <div class="">
                <div class="row listings-container">
                    <?php
                    if ($listings->have_posts()) :
                        while ($listings->have_posts()) : $listings->the_post();
                            set_query_var('countries', $countries);
                            get_template_part('/elements/listings');
                        endwhile;
                    else :
                        get_template_part('/elements/no_results');
                    endif;
                    ?>
                </div>
                <div class="pagination d-flex justify-content-center m-t-30">
                    <?php
                    $total_pages = $listings->max_num_pages;
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

<style>

.price{
    display: none;
}

</style>