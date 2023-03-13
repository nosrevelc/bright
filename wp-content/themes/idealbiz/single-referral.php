<?php
// Template Name: SingleReferral

get_header();


$expert = isExpert(); 
$expert_email = get_field( "expert_email", $expert[0]->ID );
$user = get_user_by( 'email', $expert_email );
//id do user no wordpress
$userId = $user->ID;
$e_email = $user->user_email;
$cl_first_name = $user->first_name ;
$cl_last_name = $user->last_name;

$site_original = cl_troca_de_site('site_original',get_current_blog_id());


$country = $_GET['country'];
$after = $_GET['after'];

if ($country){
    $aviso = 'block';
    switch_to_blog($country);
    /* cl_alerta ('alerta1 - '.get_current_blog_id()); */
    /* restore_current_blog(); */
}else{
    $aviso = 'none';
    /* restore_current_blog(); */
    /* cl_alerta ('alerta2 - '.get_current_blog_id()); */
}









// in Gravit Form30=1"; */

/* add_action( 'gform_form_actions', 'add_mergedoc_link', 10, 4 );
function add_mergedoc_link( $actions, $form_id ) {
        $actions['mergedoc_settings'] = "<a href="" . get_admin_url() . "admin.php?page=gf_mergedoc&id={$form_id}">" . __( 'MergeDoc', 'gravityformsmergedoc' ) . "</a>";
        return $actions;
} */





add_action( 'gform_form_actions', 'remove_duplicate_link', 10, 4 );
function remove_duplicate_link( $actions, $form_id ) {
        // Remove Duplicate action link
        unset ( $actions['duplicate'] );        
        return $actions;
}



add_filter("gform_form_tag", "form_tag", 10, 2);
function form_tag($form_tag, $form){
$form_tag = preg_replace("|action='(.*?)'|", "action='".getLinkByTemplate('single-referral.php')."?after=1'", $form_tag);
return $form_tag;
}


//out Gravity Form 

function cl_sanitize_url($parametro=null){
    
    $url_atual= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
        if (strpos($url_atual,'country')){
            $remover =  current(array_reverse(explode('/', $url_atual)));
            $cl_url_limpa = str_replace($remover,'',$url_atual);                           
        }else{
            $cl_url_limpa = $url_atual;
        }     
        return $cl_url_limpa;
    }  



if (have_posts()) : while (have_posts()) : the_post();
        $post_id = get_the_ID();
        $title = get_the_title();
        $description = get_field('text'); 
        $cl_paises= cl_troca_de_site('paises');
        $cl_idPais = cl_troca_de_site('id_pais');
        $cl_nome_pais = cl_troca_de_site('nome_pais');
        $cl_id_site_atual =  cl_troca_de_site('site_atual');
        $id_form_referral = cl_troca_de_site('id_form_referral',$country);
        $cl_id_form = cl_troca_de_site('cl_id_form');
        

        /* var_dump($cl_nome_pais[0].' - '.$id_form_referral[0]); */
        /* var_dump($site_original[0]); */
        ?>




        <section class="single-counceling">
            <div class="container m-b-25">
                <a class="go-search font-weight-medium d-flex align-items-center" href="javascript: history.go(-1);">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back', 'idealbiz'); ?></span>
                </a>
            </div>
            <div class="container d-flex flex-row flex-wrap justify-content-around">
                <div>
                    <h3 class="font-weight-semi-bold"><?php echo $title ?></h3>
                </div>
            </div>
            <div class="container d-flex flex-row flex-wrap justify-content-around">
                <div>
                    <p><?php echo $description; ?></p>
                </div>
            </div>

            <div class="container container d-flex flex-row flex-wrap justify-content-around">
                <div class="text-center">
               
                    <?php echo __('iDealBiz supports the development of its clients, through an international network of skills.','idealbiz'); ?>
                    <br/>
                    <?php echo __('Refer an expert friend and earn commission on his service!','idealbiz');

                    ?><br/><br/>
                    <?php 
                    if(WEBSITE_SYSTEM == '1'){
                        echo '<h3>'.pll__('You will earn: ').get_field('referral_points', 'option').' '.__('points').'<h3/>';
                    }
                    ?>
                </div> 
                <div id="aviso_faixa"> <?php _e('ATTENTION YOU HAVE SELECTED ANOTHER COUNTRY')?><br/>
                    <?php echo _e('You have chosen the following country').' - <span style="text-shadow: 0 0 3px #ffff00, 0 0 5px #ffff00;;font-weight: 700!important;color: #ffffff!important; opacity: 100%!important;">'.$cl_nome_pais[0].'</span> - '.$country; ?>
                </div> 
                
            </div>
            <div class="container container d-flex flex-row flex-wrap justify-content-start">
                <div class="expert-field-message d-flex flex-wrap justify-content-between">
                    <h4 class="lable_country_expert_search">
                        <?php esc_html_e('Choose Country', 'idealbiz').$field['instructions'] = __('This summary will appear associated with your profile and can be viewed by the other Associated Consultants.','idealbiz'); ?><span style="color: #790000;margin-left: 4px;" class="gfield_required">*</span>
                    </h4><br/>
                    
                    <select id="selec_pais" class="country_expert_search">
                    <?php 
                            echo '<option value=""><b>'.__('To Referral a Member Other Country', 'idealbiz').'</b></option>';
                            $i=0;
                            foreach ($cl_paises as $k){
                                echo '<option value='.$cl_idPais[$i].'>'.$cl_paises[$i].'</option>';
                                $i++;
                                /* var_dump($cl_paises[$i]); */
                            }

                    ?>
                    </select>
                    <span class="validation_message_expert"></span>
                </div>
            </div>
            <div class="container m-b-25">
                <div class="row">
                    <div class="col-md-8 col-xs-12 stretch-100 form-play" style="opacity:0;">
                        <?php

                            if(WEBSITE_SYSTEM == '1'){

                                add_filter( 'gform_field_value_valor_referencia', 'valor_referencia_population_function' );
                                function valor_referencia_population_function( $value ) {
                                    return get_field('reference_value',$_GET['rid']);
                                }
                                add_filter( 'gform_field_value_minimo', 'minimo_population_function' );
                                function minimo_population_function( $value ) {
                                    return get_field('budget_min',$_GET['rid']);
                                }
                                add_filter( 'gform_field_value_maximo', 'maximo_population_function' );
                                function maximo_population_function( $value ) {
                                    return get_field('budget_max',$_GET['rid']);
                                }
                            }
                            echo do_shortcode('[gravityform id="'.$cl_id_form[0].'" title="false" description="false"]');                    
                            /* echo do_shortcode(get_post_field('post_content', getIdByTemplate('single-referral.php'))); */
                                ?>
                                
                        <br />
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <div class="sidebar-service-message m-t-28">
                            <div id="contact-this-seller" class="form-content">
                                <div class="expert-field-message d-flex flex-wrap justify-content-between">
                                    <h4 class="box__title title" style="font-size: 13px;">
                                        <?php esc_html_e('Choose your expert', 'idealbiz'); ?><span style="color: #790000;margin-left: 4px;" class="gfield_required">*</span>
                                    </h4>
                                    <select class="location_expert_search"></select>
                                    <span class="validation_message_expert"></span>
                                </div>

                                <div class="loader" style="left: 50%; position: relative; display: none; margin-left: -15px; margin-top: 30px;"></div>
                                <div class="expert-preview m-t-20">
                                </div>
                            </div><?php //.account-content 
                                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <?php
                $terms =  wp_get_object_terms($post_id, 'service_cat', array('fields' => 'ids'));

                $post_args = array(
                    'posts_per_page' => -1,
                    'post_type' => 'expert',
                    'post_status' => 'publish'
                );
                $opts = '';
                $p = '';
                $myposts = get_posts($post_args);
                $opts .= '<option value=""></option>';
                $location_aux = '<option value="all">' . __('All Locations', 'idealbiz') . '</option>';
                if ($myposts) {
                    foreach ($myposts as $post) {

                        $term_obj_list = get_the_terms($post->ID, 'service_cat');
                        $classes = '';
                        foreach ($term_obj_list as $t) {
                            $classes .= ' service_cat_' . $t->term_id;
                        }
                        $location_objs = get_the_terms($post->ID, 'location');
                        $location_as_classes = '';
                        foreach ($location_objs as $l) {
                            $location_as_classes = ' location_' . $l->slug;
                            if (strpos($location_aux, $l->slug) !== false) {
                                //echo 'true';
                            } else {
                                $location_aux .= '<option style="display: none;" value="' . $l->slug . '">' . $l->name . '</option>';
                            }
                        }

                        $pcontent =  get_field('pitch', $post->ID); ?>
                <div id="modal_<?php echo $post->ID; ?>" class="iziModal expert-details-modal">
                    <div class="content p-x-20">
                        <button data-izimodal-close="" class="icon-close"></button>
                        <h1 class="text-center m-b-20"><?php echo get_the_title($post->ID); ?></h1>
                        <div class="accordion" id="accordion_<?php echo $post->ID; ?>">
                            <div class="card">
                                <div class="card-header" id="heading_pitch_<?php echo $post->ID; ?>">
                                    <h3 class="mb-0">
                                        <a class="btn btn-link" data-toggle="collapse" data-target="#pitch_<?php echo $post->ID; ?>" aria-expanded="true" aria-controls="pitch_<?php echo $post->ID; ?>">
                                            <?php echo _e('Pitch', 'idealbiz'); ?>
                                        </a>
                                    </h3>
                                </div>

                                <div id="pitch_<?php echo $post->ID; ?>" class="collapse show" aria-labelledby="heading_pitch_<?php echo $post->ID; ?>" data-parent="#accordion_<?php echo $post->ID; ?>">
                                    <div class="card-body">
                                        <?php echo $pcontent; ?>
                                    </div>My
                                </div>
                            </div>

                            <?php if ($expert_schedule_available = get_field('expert_schedule_available', $post->ID)) : ?>

                                <div class="card">
                                    <div class="card-header" id="heading_availability_<?php echo $post->ID; ?>">
                                        <h3 class="mb-0">
                                            <a class="btn btn-link" data-toggle="collapse" data-target="#availability_<?php echo $post->ID; ?>" aria-expanded="false" aria-controls="availability_<?php echo $post->ID; ?>">
                                                <?php _e('Availability', 'idealbiz'); ?>
                                            </a>
                                        </h3>
                                    </div>

                                    <div id="availability_<?php echo $post->ID; ?>" class="collapse" aria-labelledby="heading_availability_<?php echo $post->ID; ?>" data-parent="#accordion_<?php echo $post->ID; ?>">
                                        <div class="card-body">
                                            <?php echo $expert_schedule_available; ?>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <?php

                                            $expert_looking_for_projects_on = get_post_meta( $post->ID, 'expert_looking_for_projects_on' )[0];
                                            $expert_has_expertise = get_post_meta( $post->ID, 'expert_has_expertise' )[0];
                                            if ($expert_looking_for_projects_on || $expert_has_expertise) : 
                                            ?>

                                <div class="card">
                                    <div class="card-header" id="heading_business_sectors_<?php echo $post->ID; ?>">
                                        <h3 class="mb-0">
                                            <a class="btn btn-link" data-toggle="collapse" data-target="#business_sectors_<?php echo $post->ID; ?>" aria-expanded="false" aria-controls="business_sectors_<?php echo $post->ID; ?>">
                                                <?php _e('Business Sectors and Expertise Areas', 'idealbiz'); ?>
                                            </a>
                                        </h3>
                                    </div>

                                    <div id="business_sectors_<?php echo $post->ID; ?>" class="collapse" aria-labelledby="heading_business_sectors_<?php echo $post->ID; ?>" data-parent="#accordion_<?php echo $post->ID; ?>">
                                        <div class="card-body">
                                            <?php if ($expert_looking_for_projects_on) : ?>
                                                <h4><?php _e('Looking for Projects on:', 'idealbiz'); ?></h4>
                                                <div class="d-flex flex-column m-b-10">
                                                    <?php 
                                                    foreach ($expert_looking_for_projects_on as $term) : 
                                                    ?>
                                                        <span><?php echo get_term_by( 'id', $term, 'service_cat' )->name; ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($expert_has_expertise) : ?>
                                                <h4><?php _e('Have expertise on the following areas:', 'idealbiz'); ?></h4>
                                                <div class="d-flex flex-column">
                                                    <?php foreach ($expert_has_expertise as $term) : ?>
                                                        <span><?php echo get_term_by( 'id', $term, 'service_cat' )->name; ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <?php if ($experts_professional_experience = get_field('experts_professional_experience', $post->ID)) : ?>

                                <div class="card">
                                    <div class="card-header" id="heading_professional_experience_<?php echo $post->ID; ?>">
                                        <h3 class="mb-0">
                                            <a class="btn btn-link" data-toggle="collapse" data-target="#professional_experience_<?php echo $post->ID; ?>" aria-expanded="false" aria-controls="professional_experience_<?php echo $post->ID; ?>">
                                                <?php _e('Professional Experience', 'idealbiz'); ?>
                                            </a>
                                        </h3>
                                    </div>

                                    <div id="professional_experience_<?php echo $post->ID; ?>" class="collapse" aria-labelledby="heading_professional_experience_<?php echo $post->ID; ?>" data-parent="#accordion_<?php echo $post->ID; ?>">
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

                            <?php if ($experts_studies = get_field('experts_studies', $post->ID)) : ?>

                                <div class="card">
                                    <div class="card-header" id="heading_studies_<?php echo $post->ID; ?>">
                                        <h3 class="mb-0">
                                            <a class="btn btn-link" data-toggle="collapse" data-target="#studies_<?php echo $post->ID; ?>" aria-expanded="false" aria-controls="studies_<?php echo $post->ID; ?>">
                                                <?php _e('Studies', 'idealbiz'); ?>
                                            </a>
                                        </h3>
                                    </div>

                                    <div id="studies_<?php echo $post->ID; ?>" class="collapse" aria-labelledby="heading_studies_<?php echo $post->ID; ?>" data-parent="#accordion_<?php echo $post->ID; ?>">
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
                                            $mother_tongue = get_field('expert_mother_tongue', $post->ID);
                                            $other_languages = get_field('other_languages', $post->ID);
                                            ?>

                            <?php if ($mother_tongue || $other_languages) : ?>

                                <div class="card">
                                    <div class="card-header" id="heading_languages_<?php echo $post->ID; ?>">
                                        <h3 class="mb-0">
                                            <a class="btn btn-link" data-toggle="collapse" data-target="#languages_<?php echo $post->ID; ?>" aria-expanded="false" aria-controls="languages_<?php echo $post->ID; ?>">
                                                <?php _e('Languages', 'idealbiz'); ?>
                                            </a>
                                        </h3>
                                    </div>

                                    <div id="languages_<?php echo $post->ID; ?>" class="collapse" aria-labelledby="heading_languages_<?php echo $post->ID; ?>" data-parent="#accordion_<?php echo $post->ID; ?>">
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
                                                        <div><strong><?php echo $other_language['language']; ?> - </strong><span><?php _e($other_language['level'], 'idealbiz'); ?></span></div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <?php if ($expert_projects = get_field('expert_projects', $post->ID)) : 
                                $showprojects=0;
                                foreach ($expert_projects as $project) :
                                    if($project['project']!=''){
                                        $showprojects=1;
                                    }
                                endforeach;
                                if($showprojects){
                                ?>
                                <div class="card">
                                    <div class="card-header" id="heading_other_projects_<?php echo $post->ID; ?>">
                                        <h3 class="mb-0">
                                            <a class="btn btn-link" data-toggle="collapse" data-target="#other_projects_<?php echo $post->ID; ?>" aria-expanded="false" aria-controls="other_projects_<?php echo $post->ID; ?>">
                                                <?php _e('Other Projects', 'idealbiz'); ?>
                                            </a>
                                        </h3>
                                    </div>

                                    <div id="other_projects_<?php echo $post->ID; ?>" class="collapse" aria-labelledby="heading_other_projects_<?php echo $post->ID; ?>" data-parent="#accordion_<?php echo $post->ID; ?>">
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
                            endif; ?>

                            <?php 
                                if ($expert_it_knowledge = get_field('expert_it_knowledge', $post->ID)) : 
                                    $showit=0;
                                    foreach ($expert_it_knowledge as $it_knowledge) :
                                        if($it_knowledge['name']!=''){
                                            $showit=1;
                                        }
                                    endforeach;
                                    if($showit){ ?>
                                    <div class="card">
                                        <div class="card-header" id="heading_it_knowledge_<?php echo $post->ID; ?>">
                                            <h3 class="mb-0">
                                                <a class="btn btn-link" data-toggle="collapse" data-target="#it_knowledge_<?php echo $post->ID; ?>" aria-expanded="false" aria-controls="it_knowledge_<?php echo $post->ID; ?>">
                                                    <?php _e('IT Knowledge', 'idealbiz'); ?>
                                                </a>
                                            </h3>
                                        </div>
                                        <div id="it_knowledge_<?php echo $post->ID; ?>" class="collapse" aria-labelledby="heading_it_knowledge_<?php echo $post->ID; ?>" data-parent="#accordion_<?php echo $post->ID; ?>">
                                            <div class="card-body">
                                                <div class="d-flex flex-column">
                                                    <?php foreach ($expert_it_knowledge as $it_knowledge) : ?>
                                                        <div><strong><?php echo $it_knowledge['name']; ?> - </strong><span><?php _e($it_knowledge['level'], 'idealbiz'); ?></span></div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    } 
                                endif; 
                            ?>
                        </div>

                    </div>
                </div> <?php

            $show_expert=1;
            if(isset($_GET['sr'])){ 
                $sexpert= get_field('consultant',$_GET['rid']); 
                if($sexpert->ID == get_user_by( 'email', get_field('expert_email',$post->ID))->ID){
                    $show_expert=0;
                }
            }

                                if($show_expert){

                                        $fee=1;
                                        if(WEBSITE_SYSTEM == '1'){
                                            $userIDFee= get_user_by( 'email', get_field('expert_email',$post->ID))->ID;
                                            if(!userHasActiveExpertFeeSubscription($userIDFee)){
                                                $fee=0;
                                            }
                                        }

                                        $aux_class='';
                                        if (get_field('idealbiz_support_expert',$post->ID) == '1'){
                                            $aux_class=' customer_care ';
                                            $fee=1;
                                        }

                                        

                                        if($fee==1){
                                            $p .= '<div data-fee="'.$fee.'" data-f="" data-competencyfactor="' . get_field('competency_factor',$post->ID) . '" data-expert="' . $post->ID . '" data-locations="' . join(',', wp_list_pluck($location_objs, 'slug')) . '" style="display: none;" class="p-20 m-b-20 ' . $classes . $aux_class .' ' . $location_as_classes . ' expert-card position-relative flex-column black--color white--background dropshadow font-weight-medium"  >';
                                            $p .= '<div class="d-flex flex-row center-content">';
                                            $p .= '<div class="w-100px h-100px b-r d-block o-hidden no-decoration">';
                                            $p .= '<img class="w-100 h-100 object-cover" src="' . get_field('foto', $post->ID)['sizes']['medium'] . '">';
                                            $p .= '</div>';
                                            $p .= '<div class="calc-100-120 h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">';
                                            $p .= '<div>';
                                            $p .= '<h3 class="font-weight-semi-bold base_color--color">' . get_the_title() . '</h3> ';
                                            $p .= '</div>';
                                            $p .= '<span class="small">' . join(', ', wp_list_pluck($term_obj_list, 'name')) . '</span>';
                                            $p .= '' . ($location_objs ? '<span class="small location p-t-10 font-weight-bold"><i class="icon-local"></i><span class="text-uppercase">' . join(', ', wp_list_pluck($location_objs, 'name')) . '</span></span>' : '') . '';
                                            $p .= '</div>';
                                            $p .= '<a href="#" data-izimodal-open="#modal_' . $post->ID . '" class="info-balloon info-modal">i</a>' . '</div>';
                                            $p .= '</div>';
                                            $opts .= '<option value="' . $post->ID . '">' . $post->post_title . '</option>';
                                        }
                                }
                                    }
                                    wp_reset_postdata();
                                }

                                $p .= '<div class="not-found" style="display:none;"><p class="not-found">' . __('Experts not found.', 'idealbiz') . '</p></div>';
                                ?>

        <style>
            .experts_by_service_cat,
            .experts_by_service_cat>.gfield_label,
            .experts_by_service_cat>.ginput_container {
                display: none;
            }
        </style>
        <script>
            jQuery(document).ready(($) => {
                var cl_id_form = '<?php echo $cl_id_form;?>';
                var cl_nome_pais = '<?php echo $site_original[0];?>';
                /* alert("Slelcionou um novo pais"+cl_nome_pais ); */

                $('.from_location input[type="text"]').val('From: '+cl_nome_pais);
                $('.from_location').css('display','none');
            
                
                /* var cl_pais = "<?php echo $cl_nome_pais[0];?>"; */

                //in Codigo troca Pais
                    $("#selec_pais").on("change", function(event) {  
                    var mensagem = "<?php echo '?country=';?>";
                    var url = "<?php cl_sanitize_url()?>"+mensagem+this.value;
                    var origin   = window.location.origin;  //Ex.: idealbiz.eu
                    /* alert("Slelcionou um novo pais"+url ); */                   
                    window.location.replace(url);
                } );

                
                //out Codigo troca pais

                $('.ginput_container_custom_taxonomy').find('select option:first').text('<?php echo __('Select Option','idealbiz'); ?>');
                $('.referral').find('input').val('<?php echo $expert_email; ?>');
                $('.name_first').find('input').val('<?php echo $cl_first_name; ?>');
                $('.name_last').find('input').val('<?php echo $cl_last_name; ?>');
                $('.client-email').find('input').val('');

                $('.single-counceling .ginput_container_custom_taxonomy select').val(<?php echo $terms[0]; ?>); // Select the option with a value of '1'
                // Notify any JS components that the value changed
                $('.experts_by_service_cat select').html('<?php echo $opts; ?>');
                $('.expert-preview').html('<?php echo $p; ?>');


                var sel = $('.location_expert_search');
                sel.html('<?php echo $location_aux; ?>');
                var selected = sel.val();
                var opts_list = sel.find('option');
                opts_list.sort(function(a, b) {
                    return $(a).text() > $(b).text() ? 1 : -1;
                });
                sel.html('').append(opts_list);
                sel.val(selected);

                $('.expert-card').on('click', function() {
                    $('.expert-card').each(function() {
                        $(this).removeClass('active');
                    });
                    $(this).addClass('active');
                    $('.experts_by_service_cat .gfield_select').val($(this).data('expert'));
                    $('.experts_by_service_cat .gfield_select').trigger('change');
                });
                $('.single-counceling .ginput_container_custom_taxonomy select').on('change', function(event) {
                    event.preventDefault();
                    var id = $(this).val();
                    $('.expert-card').css('display', 'none');
                    $('.service_cat_' + id).css('display', 'flex');


                    var selected_location = $('.location_expert_search').val();

                    $('.location_expert_search option').css('display', 'none');
                    $('.service_cat_' + id).each(function() {
                        var locs = $(this).data('locations').split(",");
                        for (var i = 0; i < locs.length; i++) {
                            $('.location_expert_search option[value="' + locs[i] + '"]').css('display', 'block');
                        }
                    });
                    $('.location_expert_search option[value="all"]').css('display', 'block');
                    //if($('.location_expert_search option[value="'+selected_location+'"]').css('display') === 'block'){
                    //  $('.location_expert_search option[value='+selected_location+']').attr('selected','selected');
                    /*}else{
                        $('.location_expert_search option[value=all]').attr('selected','selected');
                    }*/
                    $('.location_expert_search').trigger('change');

                });
                $('.single-counceling .ginput_container_custom_taxonomy select').trigger('change');

                $('.location_expert_search').on('change', function(event) {
                    event.preventDefault();
                    $('.expert-preview .not-found').css('display', 'none');
                    var val = $(this).val();
                    var found = 0;
                    var cat = $('.single-counceling .ginput_container_custom_taxonomy select').val();
                    if (val != 'all') {
                        $('.expert-preview .expert-card').each(function() {
                            var l = $(this).data('locations');
                            if (l.indexOf(val) >= 0 && $(this).hasClass('service_cat_' + cat)) {
                                $(this).css('display', 'flex');
                                found++;
                            } else {
                                $(this).css('display', 'none');
                            }
                        });
                    } else {
                        $('.expert-preview .expert-card').each(function() {
                            if ($(this).hasClass('service_cat_' + cat)) {
                                $(this).css('display', 'flex');
                                found++;
                            } else {
                                $(this).css('display', 'none');
                            }
                        });
                    }
                    if (found == 0) {
                        $('.expert-preview .not-found').css('display', 'flex');
                    }
                });

                var gform_expert_validation_message = $('.experts_by_service_cat .validation_message');

                if (gform_expert_validation_message.length > 0) {
                    var validation_message_expert = $('.validation_message_expert');
                    $('#contact-this-seller').addClass('error-expert-field');
                    validation_message_expert.css('color', gform_expert_validation_message.css('color'));
                    validation_message_expert.css('font-weight', gform_expert_validation_message.css('font-weight'));
                    validation_message_expert.text(gform_expert_validation_message.text());
                    validation_message_expert.css('display', 'block');
                }

                if ($(window).width() < 768) {
                    $('#contact-this-seller').appendTo(".experts_by_service_cat");
                    $('.experts_by_service_cat').css('display', 'block');
                    $('.experts_by_service_cat > .gfield_label').css('display', 'none');
                    $('.experts_by_service_cat > .ginput_container').css('display', 'none');
                }

                $(window).on('resize', function() {
                    if ($(window).width() < 768) {
                        $('#contact-this-seller').appendTo(".experts_by_service_cat");
                        $('.experts_by_service_cat').css('display', 'block');
                        $('.experts_by_service_cat > .gfield_label').css('display', 'none');
                        $('.experts_by_service_cat > .ginput_container').css('display', 'none');
                    } else {
                        $('#contact-this-seller').appendTo(".sidebar-service-message");
                        $('.experts_by_service_cat').css('display', 'none');
                        $('.experts_by_service_cat > .gfield_label').css('display', 'none');
                        $('.experts_by_service_cat > .ginput_container').css('display', 'none');
                    }
                });
                gform.addFilter( 'gform_datepicker_options_pre_init', function( optionsObj, formId, fieldId ) {
                    optionsObj.minDate = 0;
                    return optionsObj;
                }); 



                $('.location input').on('change keyup paste', function () {
                    var v = $(this).val().toLowerCase(); 
                    $(".location_expert_search > option").each(function() {
                        var s = this.value.toLowerCase();
                        if(s.includes(v)){
                            $('.location_expert_search option[value='+s+']').attr('selected','selected');
                            $('.location_expert_search').trigger('change');
                            return;
                        }
                    });
                });
                

                

                <?php if(isset($_GET['sr'])){ 
                $rid = $_GET['rid'];
                $suser= get_field('customer',$_GET['rid']);
                $sexpert= get_field('consultant',$_GET['rid']); // $sexpert->ID
                    ?>
                    var form_id= $('.gform_wrapper form').attr('id').split("_")[1];
                    alert("ID do Formulário: "+form_id );
                    $('#field_'+form_id+'_9').css('display','none');
                    $('#field_'+form_id+'_1').css('display','none');
                    $('#field_'+form_id+'_2').css('display','none');
                    $('#field_'+form_id+'_3').css('display','none');
                    $('#field_'+form_id+'_4').css('display','none');
                    $('#field_'+form_id+'_13').css('display','none');
                    $('#field_'+form_id+'_26').css('display','none');
                    $('#field_'+form_id+'_28').css('width','100%');


                    $('.single-counceling .ginput_container_custom_taxonomy select').val(<?php echo $_GET['sr']; ?>).trigger('change');
                    $('.single-counceling .name_first input').val('<?php echo $suser->first_name; ?>');
                    $('.single-counceling .name_last input').val('<?php echo $suser->last_name; ?>');
                    $('.single-counceling .ginput_container_email input').val('<?php echo $suser->user_email; ?>');
                    $('.single-counceling .ginput_container_textarea textarea').val(`<?php echo get_field('message',$rid); ?>`);
                    $('.single-counceling .ginput_container_phone input').val('<?php echo get_field('service_request_phone',$rid); ?>');
                    $('.single-counceling .ginput_container_date input').val('<?php echo cl_formatDateByWordpress(get_field('delivery_date',$rid)); ?>');
                    $('.referral').find('input').val('<?php echo $expert_email; ?>');
                    $('.origin_sr').find('input').val('<?php echo $_GET['rid']; ?>');

                    console.log('<?php echo get_field('reference_value',$rid); ?>');



                <?php } ?>


    <?php if(WEBSITE_SYSTEM == '' || WEBSITE_SYSTEM == '0'){ ?>
        $('.valor_referencia input[type="text"]').val(0);
    <?php } ?>

    <?php if(WEBSITE_SYSTEM == '1'){ ?>

        $('.form-selector').find('form').append('<input type="hidden" name="idb_tax" value="" />');

        $('.valor_referencia .ginput_container_text').append(' <span class="curr_symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>');
        $('.minimo .ginput_container_text').append(' <span class="curr_symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>');
        $('.maximo .ginput_container_text').append(' <span class="curr_symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>');

        $('.valor_referencia .gfield_label').append('<span class="gfield_required">*</span>');
        $('.minimo .gfield_label').append('<span class="gfield_required">*</span>');
        $('.maximo .gfield_label').append('<span class="gfield_required">*</span>');


        var vr  = $('.valor_referencia input[type="text"]');
        var min = $('.minimo input[type="text"]');
        var max = $('.maximo input[type="text"]');


        vr.on('change', function() {
            var vr_val= $(this).val(); 
            if(vr_val<=0 || isNaN(vr_val)){
                $(this).val(1); 
            }else{ 
                $(this).val(parseFloat($(this).val()).toFixed(2));
            }
            calc_F_G();
        });

        min.on('change', function() {
            var minv= $(this).val(); 
            if(minv<0 || isNaN(minv)){
                $(this).val(0); 
            }else{
                $(this).val(parseFloat($(this).val()).toFixed(2));
                if(parseFloat(max.val()) < parseFloat(minv)){
                    max.val(parseFloat(minv).toFixed(2));
                }
            }
            
        });

        max.on('change', function() {
            var maxv= $(this).val(); 
            if(maxv<=0 || isNaN(maxv)){
                $(this).val(1); 
            }else{
                $(this).val(parseFloat($(this).val()).toFixed(2));
                if(parseFloat(min.val()) > parseFloat(maxv)){
                    min.val(parseFloat(maxv).toFixed(2));
                }
            }
            calc_F_G();
        });

        //calculo fator de competencia -> f
        function calc_F_G(){
            var count_competents = 0;
            if(vr.val() != '' && !isNaN(vr.val()) && !isNaN(max.val()) && max.val() != '' ){
                $('.expert-preview .customer_care').attr('style','');
                $('.expert-preview').find('#result_D').html('');
                $(".expert-preview .expert-card").each(function() {
                    var e = $(this).data('competencyfactor');

                    if(!$(this).hasClass('.customer_care')){
                        $(this).addClass('non-competent');
                        var fc = (parseFloat(e) / 100) * vr.val();
                        $(this).attr('data-f',fc);

                        //calculo valor de aceitaçao -> g
                        if( fc <= max.val() ){ // f <= c
                            // calculo d 
                            var d = (parseFloat(max.val()) / parseFloat(vr.val())) * 100;
                            if(e < d){ // e < d
                                count_competents++;
                                $(this).removeClass('non-competent');
                            }
                        }
                            
                        var g = max.val();
                        //$(this).find('.competencyfactor_test').html(g);
                    }

                });
                if (count_competents == 0) {
                    $('.expert-preview .customer_care').attr('style','display:flex !important;');
                    $('.expert-preview').find('#result_D').html('<?php echo pll_e('None of our specialists have these characteristics. We will look for a Specialist to contact the user.') ?>'); 
                }
            }else{
                $('.expert-preview .expert-card').each(function() {
                    $(this).addClass('non-competent');
                });
            }
        }
        calc_F_G();
        $('.form-play').css('opacity','1');
    <?php } ?>

});



        </script>
        <style>
            .select2-results__option {
                line-height: 32px;
                padding: 0 6px;
            } 

.gform_wrapper textarea.medium{
    height: 78px!important;
}
.no-margin{
    margin-right: 0 !important;
}
.f-label{
    font-size: 11px;
    padding-top: 37px !important;
    padding-right: 10px !important;
    border-color: rgba(255,255,255,0) !important;
    min-width: 160px;
}
.inline_label{
    display: none !important;
    flex-direction: row;
    align-items: center;
    margin-top: 32px !important;
}
.inline_label .gfield_label{
    margin-bottom:0;
    padding-right: 10px;
    min-width: 160px;
}
.inline_label .ginput_container{
    margin-top: 0 !important;
}
       
.valor_referencia,
.minimo,
.maximo{
    position:relative;
}

.valor_referencia{
    max-width:100% !important;
    width: 100% !important;
   
}

.valor_referencia .ginput_container,
.minimo .ginput_container,
.maximo .ginput_container{
    position:relative;
    margin-right: 15px;
}

.valor_referencia input[type="text"],
.minimo input[type="text"],
.maximo input[type="text"]{
    padding-right: 35px !important;
    min-width: 202px;
}

.curr_symbol{
    margin-left: 5px;
    position: absolute;
    top: 30%;
    right: 30px;
}

.country_expert_search {
    display: block;
    width: 70%!important;
    height: auto!important;
    padding: 11px!important;
    font-weight: 700;
    line-height: 26px!important;
    background-color: #14307b;
    color :#fff!important;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s;
    margin-left: 0px;
    opacity: 90%;
}
.lable_country_expert_search{
    margin-top: 15px;
    margin-left: 0px;
    font-size: 15px;
} 

#aviso_faixa > #aviso_nome_pais{
    font-weight: 700!important;background-color: #14307b!important;
}
#aviso_faixa{
vertical-align: middle;
display: <?php echo $aviso?>;
border-radius: 15px;
width: 100%;
margin: 10px;
padding: 5px;
background-color: #14307b;
color :#fff;
opacity: 100%;
z-index: 999;
text-align: center;
border: solid 1px  #14307b;


/* animation: blink-animation 3s steps(5, start) infinite;
        -webkit-animation: blink-animation 3s steps(5, start) infinite;
      }
      @keyframes blink-animation {
        to {
          visibility: hidden;
        }
      }
      @-webkit-keyframes blink-animation {
        to {
          visibility: hidden;
        } */
      


}


/* .row{
    margin-left: 0px!important;
    margin-right: 0px!important;
} */

<?php 
if(WEBSITE_SYSTEM == '1'){
    echo '
    .system1{ 
        display: block !important; 
    }
    .gf_inline.system1{
        display: inline-block !important; 
    }
    .inline_label.system1{
        display: flex !important; 
    }
    .customer_care{
        display: none !important;
    }
    .non-competent{
        display: none !important;
    }
    div[data-fee="0"]{
        display: none !important;
    }
    ';

    if(isset($_GET['sr'])){  ?>
            .single-counceling .valor_referencia input, .single-counceling .minimo input, .single-counceling .maximo input{
                pointer-events: none;
            }
        <?php    
    } 
        
}
?>

<?php endwhile;

endif; ?>

<?php get_footer(); ?>