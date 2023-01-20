<?php
// Template Name: SingleExperts

get_header();


if (have_posts()) : while (have_posts()) : the_post();
        $post_id = get_the_ID();
        $image = get_field('foto')['sizes']['full'];
        $permalink = get_permalink();
        $title = get_the_title();
        $is_certified = get_field('listing_certification_status') == 'certification_finished';
        $cl_youtube = get_field('youtube_of_member');

?>

        <section class="single-expert">
            <div class="container m-b-25">
                <a class="go-search font-weight-medium d-flex align-items-center" href="javascript: history.go(-1);">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back to your seach', 'idealbiz'); ?></span>
                </a>
            </div>

            <div class="container">
               <h1 class="text-center"> <?php _e('_str Professional','idealbiz');?> </h1>
                <?php /* the_content() */; ?>
            </div>

            <div class="container d-flex flex-row flex-wrap justify-content-around">

                <div class="col-md-12">
                    <div class="expert position-relative p-b-15 dropshadow d-flex flex-column black--color white--background">
                        <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
                            <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
                        <?php endif; ?>


                        <div class="p-40 expert-card position-relative d-flex flex-column font-weight-medium">
                            <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
                                <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
                            <?php endif; ?>
                            <div class="d-flex flex-row center-content">
                                <div class="w-100px h-100px b-r d-block o-hidden no-decoration">
                                    <img class="w-100 h-100 object-cover" src="<?php echo get_field('foto')['sizes']['medium']; ?>">
                                </div>
                                <div class="calc-100-120 h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">
                                    <div>
                                        <h3 class="font-weight-semi-bold"><?php the_title(); ?></h3>
                                    </div>
                                    <?php
                                    $term_obj_list = get_the_terms(get_the_ID(), 'service_cat');
                                    if ($term_obj_list) { ?>
                                        <span class="service_cat">
                                            <?php
                                            $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
                                            echo $terms_string;
                                            ?>
                                        </span>
                                    <?php }
                                    $location_objs = get_the_terms(get_the_ID(), 'location');
                                    if ($location_objs) { ?>
                                        <span class="location p-t-10 font-weight-bold">
                                            <i class="icon-local"></i>
                                            <span class="text-uppercase">
                                                <?php
                                                $location_string = join(', ', wp_list_pluck($location_objs, 'name'));
                                                echo $location_string;
                                                ?>
                                            </span>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="w-20px h-100px d-flex flex-column side-icons">
                                    <?php
                                    $email = get_field('expert_email');
                                    if ($email != '') {
                                        echo '<a title="email" class=" popUpForm info-modal" style="display:none;" href="#contact_form_id"><i class="icofont-envelope"></i></a>';
                                    }
                                    ?>
                                    <?php
                                    $phone = get_field('expert_phone');
                                    if ($phone != '') {
                                        //echo '<a title="' . $phone . '" href="tel:' . $phone . '"><i class="icofont-phone"></i></a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php
                        if (get_field('allow_pitch')) { ?>
                            <div class="d-flex flex-row p-x-40 justify-content-between align-items-center m-t-0">

                                <div class="accordion w-100 m-b-30" id="accordion_<?php echo $post_id; ?>">
                                    <div class="card">
                                        <div class="card-header" id="heading_pitch_<?php echo $post_id; ?>">
                                            <h3 class="mb-0">
                                                <a class="btn btn-link" data-toggle="collapse" data-target="#pitch_<?php echo $post_id; ?>" aria-expanded="true" aria-controls="pitch_<?php echo $post_id; ?>">
                                                    <?php _e('Pitch', 'idealbiz'); ?>
                                                </a>
                                            </h3>
                                        </div>

                                        <div id="pitch_<?php echo $post_id; ?>" class="collapse show" aria-labelledby="heading_pitch_<?php echo $post_id; ?>" data-parent="#accordion_<?php echo $post_id; ?>">
                                            <div class="card-body">
                                                <?php //the_content(); 
                                                echo get_field('pitch', $post_id);
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($expert_schedule_available = get_field('expert_schedule_available', $post_id)) : ?>

                                        <div class="card">
                                            <div class="card-header" id="heading_availability_<?php echo $post_id; ?>">
                                                <h3 class="mb-0">
                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#availability_<?php echo $post_id; ?>" aria-expanded="false" aria-controls="availability_<?php echo $post_id; ?>">
                                                        <?php _e('Availability', 'idealbiz'); ?>
                                                    </a>
                                                </h3>
                                            </div>

                                            <div id="availability_<?php echo $post_id; ?>" class="collapse" aria-labelledby="heading_availability_<?php echo $post_id; ?>" data-parent="#accordion_<?php echo $post_id; ?>">
                                                <div class="card-body">
                                                    <?php echo $expert_schedule_available; ?>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                    <?php if ($expert_schedule_available = get_field('youtube_of_member', $post_id)) : ?>

                                    <div class="card">
                                        <div class="card-header" id="heading_availability_<?php echo $post_id; ?>">
                                            <h3 class="mb-0">
                                                <a class="btn btn-link" data-toggle="collapse" data-target="#availability_<?php echo $post_id; ?>" aria-expanded="false" aria-controls="availability_<?php echo $post_id; ?>">
                                                <?php  _e('Picht_Video', 'idealbiz'); ?>
                                                </a>
                                            </h3>
                                        </div>

                                        <div id="availability_<?php echo $post_id; ?>" class="collapse" aria-labelledby="heading_availability_<?php echo $post_id; ?>" data-parent="#accordion_<?php echo $post_id; ?>">
                                            <div class="cl_video">
                                                <iframe class="cl_video_iframe" src="https://www.youtube.com/embed/<?php echo $expert_schedule_available ?>?rel=0&enablejsapi=1" title="<?php echo $title?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>

                                    <?php endif; ?>

                                    <?php
                                    $expert_looking_for_projects_on = get_field('expert_looking_for_projects_on', $post_id);
                                    $expert_has_expertise = get_field('expert_has_expertise', $post_id);
                                    if ($expert_looking_for_projects_on || $expert_has_expertise) : ?>

                                        <div class="card">
                                            <div class="card-header" id="heading_business_sectors_<?php echo $post_id; ?>">
                                                <h3 class="mb-0">
                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#business_sectors_<?php echo $post_id; ?>" aria-expanded="false" aria-controls="business_sectors_<?php echo $post_id; ?>">
                                                        <?php _e('Business Sectors and Expertise Areas', 'idealbiz'); ?>
                                                    </a>
                                                </h3>
                                            </div>

                                            <div id="business_sectors_<?php echo $post_id; ?>" class="collapse" aria-labelledby="heading_business_sectors_<?php echo $post_id; ?>" data-parent="#accordion_<?php echo $post_id; ?>">
                                                <div class="card-body">
                                                    <?php if ($expert_looking_for_projects_on) : ?>
                                                        <h4><?php _e('Looking for Projects on:', 'idealbiz'); ?></h4>
                                                        <div class="d-flex flex-column m-b-10">
                                                            <?php foreach ($expert_looking_for_projects_on as $term) : ?>
                                                                <span><?php echo $term->name; ?></span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($expert_has_expertise) : ?>
                                                        <h4><?php _e('Have expertise on the following areas:', 'idealbiz'); ?></h4>
                                                        <div class="d-flex flex-column">
                                                            <?php foreach ($expert_has_expertise as $term) : ?>
                                                                <span><?php echo $term->name; ?></span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                    <?php if ($experts_professional_experience = get_field('experts_professional_experience', $post_id)) : ?>

                                        <div class="card">
                                            <div class="card-header" id="heading_professional_experience_<?php echo $post_id; ?>">
                                                <h3 class="mb-0">
                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#professional_experience_<?php echo $post_id; ?>" aria-expanded="false" aria-controls="professional_experience_<?php echo $post_id; ?>">
                                                        <?php _e('Professional Experience', 'idealbiz'); ?>
                                                    </a>
                                                </h3>
                                            </div>

                                            <div id="professional_experience_<?php echo $post_id; ?>" class="collapse" aria-labelledby="heading_professional_experience_<?php echo $post_id; ?>" data-parent="#accordion_<?php echo $post_id; ?>">
                                                <div class="card-body">
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($experts_professional_experience as $experience) : ?>
                                                        <h4 class="<?php echo $i > 1 ? 'm-t-10' : ''; ?>"><strong><?php echo $experience['company']; ?> - </strong></h4>
                                                        <?php if ($experience['start_date'] || $experience['end_date']) : ?>
                                                            <div class="d-flex flex-row m-b-5">
                                                                <strong><?php _e('Duration:', 'idealbiz'); ?>&nbsp;</strong>
                                                                <span><?php echo $experience['start_date']; ?> - <?php echo $experience['end_date']; ?></span>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if ($experience['field']) : ?>
                                                            <div class="d-flex flex-row m-b-5">
                                                                <strong><?php _e('Field:', 'idealbiz'); ?>&nbsp;</strong>
                                                                <span><?php echo $experience['field']->name; ?></span>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if ($experience['role']) : ?>
                                                            <div class="d-flex flex-row m-b-5">
                                                                <strong><?php _e('Role:', 'idealbiz'); ?>&nbsp;</strong>
                                                                <span><?php echo $experience['role']; ?></span>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if ($experience['description']) : ?>
                                                            <div class="d-flex flex-row m-b-5">
                                                                <strong><?php _e('Description:', 'idealbiz'); ?>&nbsp;</strong>
                                                                <span><?php echo $experience['description']; ?></span>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php $i++; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                    <?php if ($experts_studies = get_field('experts_studies', $post_id)) : ?>

                                        <div class="card">
                                            <div class="card-header" id="heading_studies_<?php echo $post_id; ?>">
                                                <h3 class="mb-0">
                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#studies_<?php echo $post_id; ?>" aria-expanded="false" aria-controls="studies_<?php echo $post_id; ?>">
                                                        <?php _e('Studies', 'idealbiz'); ?>
                                                    </a>
                                                </h3>
                                            </div>

                                            <div id="studies_<?php echo $post_id; ?>" class="collapse" aria-labelledby="heading_studies_<?php echo $post_id; ?>" data-parent="#accordion_<?php echo $post_id; ?>">
                                                <div class="card-body">
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($experts_studies as $study) : ?>
                                                        <h4 class="<?php echo $i > 1 ? 'm-t-10' : ''; ?>"><strong><?php echo $study['course']; ?> - </strong></h4>
                                                        <?php if ($study['start_date'] || $study['end_date']) : ?>
                                                            <div class="d-flex flex-row m-b-5">
                                                                <strong><?php _e('Duration:', 'idealbiz'); ?>&nbsp;</strong>
                                                                <span><?php echo $study['start_date']; ?> - <?php echo $study['end_date']; ?></span>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if ($study['school']) : ?>
                                                            <div class="d-flex flex-row m-b-5">
                                                                <strong><?php _e('School:', 'idealbiz'); ?>&nbsp;</strong>
                                                                <span><?php echo $study['school']; ?></span>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php $i++; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                    <?php
                                    $mother_tongue = get_field('expert_mother_tongue', $post_id);
                                    $other_languages = get_field('other_languages', $post_id);

                                    ?>

                                    <?php if ($mother_tongue || $other_languages) : ?>


                                        <div class="card">
                                            <div class="card-header" id="heading_languages_<?php echo $post_id; ?>">
                                                <h3 class="mb-0">
                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#languages_<?php echo $post_id; ?>" aria-expanded="false" aria-controls="languages_<?php echo $post_id; ?>">
                                                        <?php _e('Languages', 'idealbiz'); ?>
                                                    </a>
                                                </h3>
                                            </div>

                                            <div id="languages_<?php echo $post_id; ?>" class="collapse" aria-labelledby="heading_languages_<?php echo $post_id; ?>" data-parent="#accordion_<?php echo $post_id; ?>">
                                                <div class="card-body">
                                                    <?php if ($mother_tongue) : ?>
                                                        <h4><?php _e('Mother tongue:', 'idealbiz'); ?></h4>
                                                        <div class="d-flex flex-column m-b-10">
                                                            <strong><span><?php echo $mother_tongue; ?></span></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($other_languages) : ?>
                                                        <h4><?php _e('Other languages:', 'idealbiz'); ?></h4>
                                                        <div class="d-flex flex-column">
                                                            <?php foreach ($other_languages as $other_language) : ?>

                                                                <div><strong><?php echo $other_language['language']; ?> - </strong><span><?php 
                                                                    
                                                                    
                                                                    switch ($other_language['level']) {
                                                                        case 'basic':
                                                                            $other_languages_trasnale = _e('_str Basic');
                                                                            break;
                                                                        case 'intermediate':
                                                                            $other_languages_trasnale = _e('_str Intermediate');
                                                                            break;
                                                                        case 'good':
                                                                            $other_languages_trasnale = _e('_str Good');
                                                                            break;
                                                                        case 'excellent':
                                                                            $other_languages_trasnale = _e('_str Excellent');
                                                                            break;    
    
                                                                    }  
        
                                                                    ?></span></div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                    <?php if ($expert_projects = get_field('expert_projects', $post_id)) : ?>

                                        <div class="card">
                                            <div class="card-header" id="heading_other_projects_<?php echo $post_id; ?>">
                                                <h3 class="mb-0">
                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#other_projects_<?php echo $post_id; ?>" aria-expanded="false" aria-controls="other_projects_<?php echo $post_id; ?>">
                                                        <?php _e('Other Projects', 'idealbiz'); ?>
                                                    </a>
                                                </h3>
                                            </div>

                                            <div id="other_projects_<?php echo $post_id; ?>" class="collapse" aria-labelledby="heading_other_projects_<?php echo $post_id; ?>" data-parent="#accordion_<?php echo $post_id; ?>">
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

                                    <?php endif; ?>

                                    <?php if ($expert_it_knowledge = get_field('expert_it_knowledge', $post_id)) : ?>

                                        <div class="card">
                                            <div class="card-header" id="heading_it_knowledge_<?php echo $post_id; ?>">
                                                <h3 class="mb-0">
                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#it_knowledge_<?php echo $post_id; ?>" aria-expanded="false" aria-controls="it_knowledge_<?php echo $post_id; ?>">
                                                        <?php _e('IT Knowledge', 'idealbiz'); ?>
                                                    </a>
                                                </h3>
                                            </div>

                                            <div id="it_knowledge_<?php echo $post_id; ?>" class="collapse" aria-labelledby="heading_it_knowledge_<?php echo $post_id; ?>" data-parent="#accordion_<?php echo $post_id; ?>">
                                                <div class="card-body">

                                                    <div class="d-flex flex-column">
                                                        <?php foreach ($expert_it_knowledge as $it_knowledge) : ?>
                                                            <div><strong><?php echo $it_knowledge['name']; ?> - </strong><span><?php _e($it_knowledge['level'], 'idealbiz'); ?></span></div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>


                                </div>
                            </div>
                        <?php
                        }
                        ?>

                        <hr class="m-0 p-b-0 w-100 clear" />
                        <div class="d-flex flex-row p-x-20 justify-content-between align-items-center m-t-15 f-expert">
                            <div class="social d-flex flex-row">
                                <a href="http://www.facebook.com/sharer.php?u=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-facebook"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>" target="_blank" rel="nofollow" class="m-x-15"><i class="icon-linkedin"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-twitter"></i></a>
                            </div>
                            <a href="#contact_form_id" class="btn btn-light-blue contact-seller popUpForm info-modal" style="display:none;"><?php _e('Contact this expert', 'idealbiz'); ?></a>
                        </div>
                    </div>
                </div>
                <?php echo do_shortcode(get_post_field('post_content', getIdByTemplate('single-expert.php'))); ?>
            </div>
            <br />



            <script>
                <?php
                $php_array = wp_list_pluck($term_obj_list, 'name');
                $js_array = json_encode($php_array);
                echo "var serv_arr = " . $js_array . ";\n";
                ?>
                var list = jQuery('.support-services > select');
                list.html('');
                var i;
                for (i = 0; i < serv_arr.length; ++i) {
                    list.append('<option value="' + serv_arr[i] + '">' + serv_arr[i] + '</option>');
                }
                <?php
                foreach ($term_obj_list as $i) {
                    echo "jQuery('.gfield_select option[value=\"" . $i->term_id . "\"]').addClass(\"in-expert\");";
                }
                ?>
                jQuery('.gfield_select option').each(function(e) {
                    if (!jQuery(this).hasClass('in-expert'))
                        jQuery(this).remove();
                });

                jQuery('[name=input_6]').val('<?php the_title(); ?>');
                <?php /*jQuery('[name=input_12]').val('<?php echo get_field('expert_email', $post_id); ?>'); */ ?>
                jQuery('[name=input_13]').val('<?php echo get_field('costumer_care_email', 'options'); ?>');

                jQuery(document).ready(($) => {
                    if ($('.acf-form').find('div.gform_confirmation_message').length != 0) {
                        $('#contact_form_id').css('display', 'block');
                        $('#contact_form_id').css('z-index', '99999999999');
                    }
                });
            </script>
        </section>

<?php endwhile;
endif; ?>

<?php get_footer(); ?>

<style>
    .cl_video{
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        height: 0;
    }
    .cl_video_iframe{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>