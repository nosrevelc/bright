<?php
/* Member search results.
 * Presents members in card form.
 *
 * Usage: get_template_part( 'elements/member-search/member-search', null, array( 'service_category' => '', 'amount' => '', 'location' => '' ) );
 */

$query_args = array(
    'post_type' => 'expert',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'service_cat',
            'field'    => 'term_id',
            'terms'    => $args['service_category']
        ),
        array(
            'taxonomy' => 'location',
            'field'    => 'term_id',
            'terms'    => $args['location']
        )
    ),
    //'meta_key' => 'echelon_competency_factor'
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key'     => 'echelon_competency_factor_$_begin_echelon',
            'compare' => '<=',
            'value'   => (int) $args['amount']
        ),
        array(
            'key'     => 'echelon_competency_factor_$_finish_echelon',
            'compare' => '>=',
            'value'   => (int) $args['amount']
        )
    )
);
$members = get_posts($query_args);
?>

<div class="expert-preview m-t-20">
    <?php

    echo "<pre>";
    var_dump($members);
    echo "</pre>";

    foreach ($members as $member) {
        $member_name      = $member->post_title;
        $member_photo_url = get_field('foto', $member->ID)['sizes']['medium'];

        $member_service_cats_list = get_the_terms($member->ID, 'service_cat');
        $member_locations_list = get_the_terms($member->ID, 'location');

        $css_classes_service_cats = 'service_cat_' . join(' service_cat_', member_service_cat_list);
        $css_classes_locations    = 'location_' . join(' location_', member_locations_list);

        ?>
        <div data-escalao="" data-fee="" data-ppc-fixo="" data-f="" data-competencyfactor="" data-expert="" data-locations="" class="p-20 m-b-20 expert-card <?php echo "{$css_classes_service_cats} {$css_classes_locations}"?> position-relative flex-column black--color white--background dropshadow font-weight-medium">
            <div class="d-flex flex-row center-content">
                <div class="w-100px h-100px b-r d-block o-hidden no-decoration">
                    <img class="w-100 h-100 object-cover" src="<?php echo $member_photo_url ?>"/>
                    <div class="calc-100-120 h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">
                        <div>
                            <h3 class="font-weight-semi-bold base_color--color"><?php echo $member_name; ?></h3>
                        </div>
                        <span class="small"><?php echo join(', ', member_service_cats_list); ?></span>
                        <div class="cl_icon location p-t-10 font-weight-bold">
                            <span class="cl_icon-local dashicons dashicons-yes-alt"/>[Payment]</div>
                        <span class="small location p-t-10 font-weight-bold">
                            <i class="icon-local"/>
                            <span class="text-uppercase"><?php echo join(', ', member_locations_list); ?></span>
                        </span>
                    </div>
                    <a href="#" data-izimodal-open="#[TODO]" class="info-balloon info-modal">i</a>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="not-found" style="display:none;">
        <p class="not-found" style="display: none;">[Not found]</p>
    </div>
    <span id="result_D" class="cl_aviso">[Not found Message]</span>
</div>