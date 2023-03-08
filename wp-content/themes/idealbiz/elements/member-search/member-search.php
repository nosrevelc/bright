<?php
/* Member search results.
 * Presents members in card form.
 *
 * Usage:
 *  get_template_part(
 *      'elements/member-search/member-search',
 *      null,
 *      array( 'service_category' => '', 'amount' => '', 'location' => '' ) 
 *  );
 */

function cl_repeater_field( $where ) {
    $where = str_replace( "meta_key = 'echelon_competency_factor_$", "meta_key LIKE 'echelon_competency_factor_%", $where );
    return $where;
}
add_filter( 'posts_where', 'cl_repeater_field' );

$query_args = array(
    'post_type' => 'expert',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'service_cat',
            'field'    => 'term_id',
            'terms'    => $args['service_category']
        )
    ),
    'meta_query' => array(
        array(
            'key'     => 'echelon_competency_factor_$_begin_echelon',
            'compare' => '<=',
            'value'   => $args['amount'],
            'type' => 'NUMERIC'
        ),
        array(
            'key'     => 'echelon_competency_factor_$_finish_echelon',
            'compare' => '>=',
            'value'   => $args['amount'],
            'type' => 'NUMERIC'
        )
    )
);

if($args['location'] !== '') {
    $query_args['tax_query'][] = array(
        'taxonomy' => 'location',
        'field'    => 'term_id',
        'terms'    => $args['location']
    );
}

$members = new WP_Query( $query_args );

// If no members are found, return the default expert
if(empty($members)) {
    $query_args = array(
        'post_type'      => 'expert',
        'posts_per_page' => 1,

        'meta_key'   => 'idealbiz_support_expert',
        'meta_value' => '1'
    );

    $members = new WP_Query( $query_args );
}

/* echo "<pre>";
var_dump($members);
echo "</pre>"; */
?>

<div class="expert-preview m-t-20">
    <?php
    foreach ($members as $member) {

        if(isset($member->ID)) {
            // INÍCIO: Member Card

            $member_name      = $member->post_title;
            $member_photo_url = get_field('foto', $member->ID)['sizes']['medium'];
            $member_lead_mode = consultLeadModeServiceRequest($member->ID, true);

            $member_service_cats_list = get_the_terms($member->ID, 'service_cat');
            $member_locations_list = get_the_terms($member->ID, 'location');

            $css_classes_service_cats = 'service_cat_' . join(' service_cat_', wp_list_pluck($member_service_cats_list, 'term_id'));
            $css_classes_locations    = 'location_' . join(' location_', wp_list_pluck($member_locations_list, 'slug'));

            $modal_id = "modal_{$member->ID}";

            ?>
            <div data-member-id="<?php echo $member->ID; ?>" class="p-20 m-b-20 expert-card <?php echo "{$css_classes_service_cats} {$css_classes_locations}"?> position-relative flex-column black--color white--background dropshadow font-weight-medium">
                <div class="d-flex flex-row center-content">
                    <div class="w-100px h-100px b-r d-block o-hidden no-decoration">
                        <img class="w-100 h-100 object-cover" src="<?php echo $member_photo_url ?>"/>
                    </div>
                    <div class="calc-100-120 h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">
                        <div>
                            <h3 class="font-weight-semi-bold base_color--color"><?php echo $member_name; ?></h3>
                        </div>
                        <span class="small"><?php echo join(', ', wp_list_pluck($member_service_cats_list, 'name')); ?></span>
                        <div class="cl_icon location p-t-10 font-weight-bold">
                            <span class="cl_icon-local dashicons dashicons-yes-alt"></span>
                            <?php echo $member_lead_mode ?>
                        </div>
                        <span class="small location p-t-10 font-weight-bold">
                            <i class="icon-local"></i>
                            <span class="text-uppercase"><?php echo join(', ', wp_list_pluck($member_locations_list, 'name')); ?></span>
                        </span>
                    </div>
                    <a href="#" onclick='jQuery("#<?php echo $modal_id ?>").iziModal().iziModal("open"); return false; ' class="info-balloon info-modal">i</a>
                </div>
            </div>

            <?php
            // FIM: Member Card



            // INÍCIO: Member Modal

            $expert_schedule_available = get_field('expert_schedule_available', $member->ID);
            $expert_looking_for_projects_on = get_post_meta($member->ID, 'expert_looking_for_projects_on')[0];
            $expert_has_expertise = get_post_meta($member->ID, 'expert_has_expertise')[0];
            $experts_professional_experience = get_field('experts_professional_experience', $member->ID);

            $experts_studies = get_field('experts_studies', $member->ID);
            $expert_projects = get_field('expert_projects', $member->ID);

            $mother_tongue   = get_field('expert_mother_tongue', $member->ID);
            $other_languages = get_field('other_languages', $member->ID);

            $expert_it_knowledge = get_field('expert_it_knowledge', $member->ID);

            ?>
            <div id="<?php echo $modal_id ?>" class="iziModal expert-details-modal">
                <div class="content p-x-20">
                    <button data-izimodal-close="" class="icon-close"></button>
                    <h1 class="text-center m-b-20"><?php echo $member_name ?></h1>

                    <div class="accordion" id="accordion_<?php echo $member->ID; ?>">
                        <div class="card">
                            <div class="card-header" id="heading_pitch_<?php echo $member->ID; ?>">
                                <h3 class="mb-0">
                                    <a class="btn btn-link" data-toggle="collapse" data-target="#pitch_<?php echo $member->ID; ?>" aria-expanded="true" aria-controls="pitch_<?php echo $member->ID; ?>">
                                        <?php echo _e('Pitch', 'idealbiz'); ?>
                                    </a>
                                </h3>
                            </div>

                            <div id="pitch_<?php echo $member->ID; ?>" class="collapse show" aria-labelledby="heading_pitch_<?php echo $member->ID; ?>" data-parent="#accordion_<?php echo $member->ID; ?>">
                                <div class="card-body">
                                    <?php echo $pcontent; ?>
                                </div>
                            </div>
                        </div>

                        <?php
                        if ($expert_schedule_available) {
                            ?>
                            <div class="card">
                                <div class="card-header" id="heading_availability_<?php echo $member->ID; ?>">
                                    <h3 class="mb-0">
                                        <a class="btn btn-link" data-toggle="collapse" data-target="#availability_<?php echo $member->ID; ?>" aria-expanded="false" aria-controls="availability_<?php echo $member->ID; ?>">
                                            <?php _e('Availability', 'idealbiz'); ?>
                                        </a>
                                    </h3>
                                </div>

                                <div id="availability_<?php echo $member->ID; ?>" class="collapse" aria-labelledby="heading_availability_<?php echo $member->ID; ?>" data-parent="#accordion_<?php echo $member->ID; ?>">
                                    <div class="card-body">
                                        <?php echo $expert_schedule_available; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }

                        if ($expert_looking_for_projects_on || $expert_has_expertise) {
                            ?>
                            <div class="card">
                                <div class="card-header" id="heading_business_sectors_<?php echo $member->ID; ?>">
                                    <h3 class="mb-0">
                                        <a class="btn btn-link" data-toggle="collapse" data-target="#business_sectors_<?php echo $member->ID; ?>" aria-expanded="false" aria-controls="business_sectors_<?php echo $member->ID; ?>">
                                            <?php _e('Business Sectors and Expertise Areas', 'idealbiz'); ?>
                                        </a>
                                    </h3>
                                </div>

                                <div id="business_sectors_<?php echo $member->ID; ?>" class="collapse" aria-labelledby="heading_business_sectors_<?php echo $member->ID; ?>" data-parent="#accordion_<?php echo $member->ID; ?>">
                                    <div class="card-body">
                                        <?php
                                        if ($expert_looking_for_projects_on) {
                                            ?>
                                            <h4><?php _e('Looking for Projects on:', 'idealbiz'); ?></h4>
                                            <div class="d-flex flex-column m-b-10">
                                                <?php foreach ($expert_looking_for_projects_on as $term) : ?>
                                                    <span><?php echo get_term_by('id', $term, 'service_cat')->name; ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php
                                        }

                                        if ($expert_has_expertise) {
                                            ?>
                                            <h4><?php _e('Have expertise on the following areas:', 'idealbiz'); ?></h4>
                                            <div class="d-flex flex-column">
                                                <?php 
                                                foreach ($expert_has_expertise as $term) {
                                                    ?>
                                                    <span><?php echo get_term_by('id', $term, 'service_cat')->name; ?></span>
                                                    <?php 
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }

                        if ($experts_professional_experience) {
                            ?>
                            <div class="card">
                                <div class="card-header" id="heading_professional_experience_<?php echo $member->ID; ?>">
                                    <h3 class="mb-0">
                                        <a class="btn btn-link" data-toggle="collapse" data-target="#professional_experience_<?php echo $member->ID; ?>" aria-expanded="false" aria-controls="professional_experience_<?php echo $member->ID; ?>">
                                            <?php _e('Professional Experience', 'idealbiz'); ?>
                                        </a>
                                    </h3>
                                </div>

                                <div id="professional_experience_<?php echo $member->ID; ?>" class="collapse" aria-labelledby="heading_professional_experience_<?php echo $member->ID; ?>" data-parent="#accordion_<?php echo $member->ID; ?>">
                                    <div class="card-body">
                                        <?php
                                        $i = 1;

                                        foreach ($experts_professional_experience as $experience) {
                                            ?>
                                            <h4 class="<?php echo $i > 1 ? 'm-t-10' : ''; ?>">
                                                <strong><?php echo $experience['company']; ?> - </strong>
                                            </h4>

                                            <?php
                                            if ($experience['start_date'] || $experience['end_date']) {
                                                ?>
                                                <div class="d-flex flex-row m-b-5">
                                                    <strong><?php _e('Duration:', 'idealbiz'); ?>&nbsp;</strong>
                                                    <span><?php echo $experience['start_date']; ?> - <?php echo $experience['end_date']; ?></span>
                                                </div>
                                                <?php
                                            }
                                            if ($experience['field']) {
                                                ?>
                                                <div class="d-flex flex-row m-b-5">
                                                    <strong><?php _e('Field:', 'idealbiz'); ?>&nbsp;</strong>
                                                    <span><?php echo $experience['field']->name; ?></span>
                                                </div>
                                                <?php
                                            }
                                            if ($experience['role']) {
                                                ?>
                                                <div class="d-flex flex-row m-b-5">
                                                    <strong><?php _e('Role:', 'idealbiz'); ?>&nbsp;</strong>
                                                    <span><?php echo $experience['role']; ?></span>
                                                </div>
                                                <?php
                                            }
                                            if ($experience['description']) {
                                                ?>
                                                <div class="d-flex flex-row m-b-5">
                                                    <strong><?php _e('Description:', 'idealbiz'); ?>&nbsp;</strong>
                                                    <span><?php echo $experience['description']; ?></span>
                                                </div>
                                                <?php
                                            }

                                            $i++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }

                        if ($experts_studies) {
                            ?>
                            <div class="card">
                                <div class="card-header" id="heading_studies_<?php echo $member->ID; ?>">
                                    <h3 class="mb-0">
                                        <a class="btn btn-link" data-toggle="collapse" data-target="#studies_<?php echo $member->ID; ?>" aria-expanded="false" aria-controls="studies_<?php echo $member->ID; ?>">
                                            <?php _e('Studies', 'idealbiz'); ?>
                                        </a>
                                    </h3>
                                </div>

                                <div id="studies_<?php echo $member->ID; ?>" class="collapse" aria-labelledby="heading_studies_<?php echo $member->ID; ?>" data-parent="#accordion_<?php echo $member->ID; ?>">
                                    <div class="card-body">
                                        <?php 
                                        $i = 1;
                                        foreach ($experts_studies as $study) {
                                            ?>
                                            <h4 class="<?php echo $i > 1 ? 'm-t-10' : ''; ?>">
                                                <strong><?php echo $study['course']; ?> - </strong>
                                            </h4>
                                            <?php
                                            if ($study['start_date'] || $study['end_date']) {
                                                ?>
                                                <div class="d-flex flex-row m-b-5">
                                                    <strong><?php _e('Duration:', 'idealbiz'); ?>&nbsp;</strong>
                                                    <span><?php echo $study['start_date']; ?> - <?php echo $study['end_date']; ?></span>
                                                </div>
                                                <?php
                                            }

                                            if ($study['school']) {
                                                ?>
                                                <div class="d-flex flex-row m-b-5">
                                                    <strong><?php _e('School:', 'idealbiz'); ?>&nbsp;</strong>
                                                    <span><?php echo $study['school']; ?></span>
                                                </div>
                                                <?php
                                            }

                                            $i++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }

                        if ($mother_tongue || $other_languages) {
                            ?>
                            <div class="card">
                                <div class="card-header" id="heading_languages_<?php echo $member->ID; ?>">
                                    <h3 class="mb-0">
                                        <a class="btn btn-link" data-toggle="collapse" data-target="#languages_<?php echo $member->ID; ?>" aria-expanded="false" aria-controls="languages_<?php echo $member->ID; ?>">
                                            <?php _e('Languages', 'idealbiz'); ?>
                                        </a>
                                    </h3>
                                </div>

                                <div id="languages_<?php echo $member->ID; ?>" class="collapse" aria-labelledby="heading_languages_<?php echo $member->ID; ?>" data-parent="#accordion_<?php echo $member->ID; ?>">
                                    <div class="card-body">
                                        <?php 
                                        if ($mother_tongue) {
                                            ?>
                                            <h4><?php _e('Mother tongue:', 'idealbiz'); ?></h4>
                                            <div class="d-flex flex-column m-b-10">
                                                <strong><span><?php echo $mother_tongue; ?></span></strong>
                                            </div>
                                            <?php
                                        }
                                        if ($other_languages) {
                                            ?>
                                            <h4><?php _e('Other languages:', 'idealbiz'); ?></h4>
                                            <div class="d-flex flex-column">
                                                <?php 
                                                foreach ($other_languages as $other_language) {
                                                    ?>
                                                    <div><strong><?php echo $other_language['language']; ?> -
                                                        </strong><span><?php _e($other_language['level'], 'idealbiz'); ?></span>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }

                        if ($expert_projects) {
                            $showprojects = 0;
                            foreach ($expert_projects as $project) {
                                if ($project['project'] != '') {
                                    $showprojects = 1;
                                }
                            }

                            if ($showprojects) {
                                ?>
                                <div class="card">
                                    <div class="card-header" id="heading_other_projects_<?php echo $member->ID; ?>">
                                        <h3 class="mb-0">
                                            <a class="btn btn-link" data-toggle="collapse" data-target="#other_projects_<?php echo $member->ID; ?>" aria-expanded="false" aria-controls="other_projects_<?php echo $member->ID; ?>">
                                                <?php _e('Other Projects', 'idealbiz'); ?>
                                            </a>
                                        </h3>
                                    </div>

                                    <div id="other_projects_<?php echo $member->ID; ?>" class="collapse" aria-labelledby="heading_other_projects_<?php echo $member->ID; ?>" data-parent="#accordion_<?php echo $member->ID; ?>">
                                        <div class="card-body">
                                            <?php $i = 1; ?>
                                            <?php foreach ($expert_projects as $project) : ?>
                                                <h4 class="<?php echo $i > 1 ? 'm-t-10' : ''; ?>"><strong><?php echo $project['project']; ?> - </strong></h4>

                                                <?php if ($project['start_date'] || $project['end_date']) : ?>
                                                <div class="d-flex flex-row m-b-5">
                                                    <strong><?php _e('Duration:', 'idealbiz'); ?>&nbsp;</strong>
                                                    <span><?php echo $project['start_date']; ?> - <?php echo $project['end_date']; ?></span>
                                                </div>
                                                <?php endif; ?>

                                                <?php if ($project['small_description']) : ?>
                                                    <div class="d-flex flex-row m-b-5">
                                                        <strong><?php _e('Small Description:', 'idealbiz'); ?>&nbsp;</strong>
                                                        <span><?php echo $project['small_description']; ?></span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }

                        if ($expert_it_knowledge) {
                            $showit = 0;
                            foreach ($expert_it_knowledge as $it_knowledge) {
                                if ($it_knowledge['name'] != '') {
                                    $showit = 1;
                                }
                            }

                            if ($showit) {
                                ?>
                                <div class="card">
                                    <div class="card-header" id="heading_it_knowledge_<?php echo $member->ID; ?>">
                                        <h3 class="mb-0">
                                            <a class="btn btn-link" data-toggle="collapse" data-target="#it_knowledge_<?php echo $member->ID; ?>" aria-expanded="false" aria-controls="it_knowledge_<?php echo $member->ID; ?>">
                                                <?php _e('IT Knowledge', 'idealbiz'); ?>
                                            </a>
                                        </h3>
                                    </div>
                                    <div id="it_knowledge_<?php echo $member->ID; ?>" class="collapse" aria-labelledby="heading_it_knowledge_<?php echo $member->ID; ?>" data-parent="#accordion_<?php echo $member->ID; ?>">
                                        <div class="card-body">
                                            <div class="d-flex flex-column">
                                                <?php 
                                                foreach ($expert_it_knowledge as $it_knowledge) {
                                                    ?>
                                                    <div>
                                                        <strong><?php echo $it_knowledge['name']; ?> - </strong>
                                                        <span><?php _e($it_knowledge['level'], 'idealbiz'); ?></span>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <?php

            // FIM: Member Modal
        }
    }
    ?>
    <span id="result_D" class="cl_aviso">[Not found Message]</span>
</div>