<?php
// Template Name: SingleCounseling

//NPMM - wp-content/plugins/idealbiz-service-request/lib/Gforms  -  Content Files Service Request é paraa onde os dados são enviados após Submeter

//NPMM - Código que checa e força a logar
if (!is_user_logged_in()) {
    $redirect = ( strpos( $_SERVER['REQUEST_URI'], '/options.php' ) && wp_get_referer() ) ? wp_get_referer() : set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    wp_redirect(get_site_url().'/'.pll_languages_list()[0].'/login-register/?redirect_to='.$redirect );
}

get_header();


$form_id = -1;
$form_fields = array(
    'service_category' => array( 'id' => -1 ),
    'amount'           => array( 'id' => -1 ),
    'location'         => array( 'id' => -1 ),

    'member_search_results' => array( 'id' => -1 ),
    'member_selection'      => array( 'id' => -1 )
);

// Find the correct Gravity Form via CSS Class (configured on the form):
foreach( GFAPI::get_forms() as $form ) {
    if ( $form['cssClass'] === 'service-request' ) {
        // Store the Form ID
        $form_id = $form['id'];

        // Find fields via CSS Class (configured on the fields):
        foreach( $form['fields'] as $field) {
            $field_to_update  = '';

            if     ( str_contains( $field->cssClass, 'service-request-service-category' ) ) {
                $field_to_update = 'service_category';
            }
            elseif ( str_contains( $field->cssClass, 'valor_referencia' ) ) {
                $field_to_update = 'amount';
            }
            elseif ( str_contains( $field->cssClass, 'service-request-location' ) ) {
                $field_to_update = 'location';
            }
            elseif ( str_contains( $field->cssClass, 'service-category-member-search-results' ) ) {
                $field_to_update = 'member_search_results';
            }
            elseif ( str_contains( $field->cssClass, 'service-category-member-selection' ) ) {
                $field_to_update = 'member_selection';
            }

            if ( $field_to_update !== '' ) {
                $form_fields[$field_to_update] = array( 'id' => $field['id'] );
            }
        }

        break;
    }
}
?>

<?php
//NPMM - Resolve que tipo serviço - E o nomeia.
if($_GET["refer"]==1 && !$_GET['rid']) :
    $cl_sr_type_origin = 'recommende_service';
    $cl_sr_Type_origin_tittle = __('_str_REFERENCE BETWEEN MEMBERS','idealbiz'); 
?>
    <style>
        input[name=input_2],input[name="input_1.3"],input[name="input_1.6"] {
            pointer-events: none;
            background-color:#f5f5f5;
        }
    </style>
<?php 
elseif ($_GET["refer"]==1 && $_GET['rid']!= null) :
    $cl_sr_type_origin = 'forward_service';
    $cl_sr_Type_origin_tittle = __('_str Forward to Member', 'idealbiz');
else:
    $cl_sr_type_origin = 'normal_service';
    $cl_sr_Type_origin_tittle = '';
endif;
?>

<?php
$cl_rid = $_GET['rid'];

//EDITADO PELO CLEVERSON VIEIRA
//Iniciando Escalão sem Orçamento dia 08/07/21

// Get the Form fields

//Garante que não haja sessões criadas para isiciar novo processo se for membro.
session_start();
unset($_SESSION['membro']);
unset($_SESSION['rid']);
unset($_SESSION['sr']);
unset($_SESSION['email_referenciado']);

$cl_membro = $_GET['refer'];

$cl_sr_type_origin_id_field = get_field('sr_type_origin_id_field', 'options');
$cl_input_sr_fixed_ppc_value_id_field = get_field('sr_input_sr_fixed_ppc_value_id_field', 'options');
$cl_sr_origin_sr_id_of_field = get_field('sr_origin_sr_id_of_field', 'options');
$cl_sr_company_parameter_1 = get_field('sr_company_parameter_1', 'options');
$cl_sr_company_parameter_2 = get_field('sr_company_parameter_2', 'options');

// verifica se é membro para proceder a refereianção
if ($cl_membro) {
    $_SESSION['rid'] = $_GET['rid'];//ID do Serviçe Resquest Original
    $_SESSION['sr'] =  $_GET['sr'];

    $cl_user = get_current_user_id();

    $user_id = get_current_user_id(); 
    $user_info = get_userdata($user_id);
    $mailadresje = $user_info->user_email;
    $cl_display_name = $user_info->display_name;

    $args = array(
        'numberposts'	=> 1,
        'post_type'		=> 'expert',
        'meta_query'	=> array(
            'relation'		=> 'AND',
            array(
                'key'	 	=> 'expert_email',
                'value'	  	=> $mailadresje,
            )
        )
    );

    $query = new WP_Query($args);
    $cl_user = $query->posts[0]->ID;
    $cl_member_cat = get_field('member_category_store',$cl_user);

    if ($cl_member_cat != false) {
        session_start();
        if (!isset($_SESSION['membro'])) {
            $_SESSION['membro'] = $cl_user;
            $_SESSION['email_referenciado'] = $mailadresje;
        }
    }
}

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $post_id = get_the_ID();
    endwhile;
endif;
?>

<?php
    //Inicio preenchar os izModal co a descrição dos campos
    //id do Formulário
    $form = RGFormsModel::get_form_meta($form_id);

    // Run through the fields to grab an object of the desired field
    $i = 0;
    for ($i = 1; ; $i++) {
        if ($i > 30) {
            break;
        }
        $field = RGFormsModel::get_field($form, $i);
        if($field) {
            $descicao[$i]= array($field->id => $field->description);
        }
    }

    infoModal('<h3>' . __($descicao[1][1], 'idealbiz') . '</h3>', 'campo1', 'd-none');
    infoModal('<h3>' . __($descicao[2][2], 'idealbiz') . '</h3>', 'campo2', 'd-none');
    infoModal('<h3>' . __($descicao[3][3], 'idealbiz') . '</h3>', 'campo3', 'd-none');
    infoModal('<h3>' . __($descicao[4][4], 'idealbiz') . '</h3>', 'campo4', 'd-none');
    infoModal('<h3>' . __($descicao[5][5], 'idealbiz') . '</h3>', 'campo5', 'd-none');
    infoModal('<h3>' . __($descicao[6][6], 'idealbiz') . '</h3>', 'campo6', 'd-none');
    infoModal('<h3>' . __($descicao[7][7], 'idealbiz') . '</h3>', 'campo7', 'd-none');
    infoModal('<h3>' . __($descicao[8][8], 'idealbiz') . '</h3>', 'campo8', 'd-none');
    infoModal('<h3>' . __($descicao[9][9], 'idealbiz') . '</h3>', 'campo9', 'd-none');
    infoModal('<h3>' . __($descicao[10][10], 'idealbiz') . '</h3>', 'campo10', 'd-none');
    infoModal('<h3>' . __($descicao[11][11], 'idealbiz') . '</h3>', 'campo11', 'd-none');
    infoModal('<h3>' . __($descicao[12][12], 'idealbiz') . '</h3>', 'campo12', 'd-none');
    infoModal('<h3>' . __($descicao[13][13], 'idealbiz') . '</h3>', 'campo13', 'd-none');
    infoModal('<h3>' . __($descicao[14][14], 'idealbiz') . '</h3>', 'campo14', 'd-none');
    infoModal('<h3>' . __($descicao[15][15], 'idealbiz') . '</h3>', 'campo15', 'd-none');
    infoModal('<h3>' . __($descicao[16][16], 'idealbiz') . '</h3>', 'campo16', 'd-none');
    infoModal('<h3>' . __($descicao[17][17], 'idealbiz') . '</h3>', 'campo17', 'd-none');
    infoModal('<h3>' . __($descicao[18][18], 'idealbiz') . '</h3>', 'campo18', 'd-none');
    infoModal('<h3>' . __($descicao[19][19], 'idealbiz') . '</h3>', 'campo19', 'd-none');
    infoModal('<h3>' . __($descicao[20][20], 'idealbiz') . '</h3>', 'campo20', 'd-none');
    infoModal('<h3>' . __($descicao[21][21], 'idealbiz') . '</h3>', 'campo21', 'd-none');
    infoModal('<h3>' . __($descicao[22][22], 'idealbiz') . '</h3>', 'campo22', 'd-none');
    infoModal('<h3>' . __($descicao[23][23], 'idealbiz') . '</h3>', 'campo23', 'd-none');
    infoModal('<h3>' . __($descicao[24][24], 'idealbiz') . '</h3>', 'campo24', 'd-none');
    infoModal('<h3>' . __($descicao[25][25], 'idealbiz') . '</h3>', 'campo25', 'd-none');
    infoModal('<h3>' . __($descicao[26][26], 'idealbiz') . '</h3>', 'campo26', 'd-none');
    infoModal('<h3>' . __($descicao[27][27], 'idealbiz') . '</h3>', 'campo27', 'd-none');
    infoModal('<h3>' . __($descicao[28][28], 'idealbiz') . '</h3>', 'campo28', 'd-none');
    infoModal('<h3>' . __($descicao[29][29], 'idealbiz') . '</h3>', 'campo29', 'd-none');
    infoModal('<h3>' . __($descicao[30][30], 'idealbiz') . '</h3>', 'campo30', 'd-none');


    $cl_descicao12='teste'.$descicao[12][12];
    //Fim preenchar os izModal co a descrição dos campos
?>

<section class="single-counceling">
    <div class="container m-b-25">
        <a class="go-search font-weight-medium d-flex align-items-center" href="javascript: history.go(-1);">
            <i class="icon-dropdown"></i>
            <span class="font-weight-bold m-l-5"><?php _e('Go back', 'idealbiz'); ?></span>
        </a>
    </div>

    <div class="container container d-flex flex-row flex-wrap justify-content-around">
        <div>
            <?php if($cl_membro && $cl_member_cat != false) : ?>
                <div>
                    <h1 style="text-align: center;">'.$cl_sr_Type_origin_tittle.' </h1>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            <?php else :
                echo the_content();
            endif;
            ?>
        </div>
    </div>

    <!-- NPMM  - início form parametros -->    

    <div id="form_par" class="container gf_browser_chrome gform_wrapper gform_legacy_markup_wrapper form_par">
        <form onsubmit="event.preventDefault();">
            <p><label class="gfield_label" for="selector"><?php _e('_str Are you a company?','idealbiz').':' ?></label></p>
            <select class=" m-b-25 gfield_select select_par" id="selector" onchange="hideMessage()">
                <option value="no company"><?php _e("_str I'm not a company","idealbiz").':' ?></option>
                <option value="company"><?php _e('_str Yes I am a company','idealbiz').':' ?></option>
            </select>
            <div id="camposAdicionais1" style="display:none;">
                <div class="m-t-30 m-b-30"style="text-align: center;">
                    <h3><?php _e('_str Explanatory text.','idealbiz');?></h3>
                </div>
                <label class="m-t-20 gfield_label" for="campo1"><?php _e('_str Parameter 1','idealbiz').':' ?></label>
                <div class="error_par" id="error_par1" style="display:none;"><?php _e('_str Parameter 1','idealbiz').' '._e('_str Mandatory','idealbiz'); ?></div>
                <input class="ginput_container" type="text" id="parametro1">

                <label class="m-t-20 gfield_label" for="campo2"><?php _e('_str Parameter 2','idealbiz').':' ?></label>
                <div class="error_par" id="error_par2" style="display:none;"><?php _e('_str Parameter 2','idealbiz').' '._e('_str Mandatory','idealbiz'); ?></div>
                <input class="m-b-25 ginput_container" type="text" id="parametro2">
            </div>
            <button class="btn btn-blue m-l-10 font-btn-plus blue--hover" onclick="showMessage()"><?php _e('_str Continue...','idealbiz').':' ?></button>
        </form>
    </div>

    <div class="container m-b-25 form_sr_hidden " id="form_sr">
        <div class="div-to-align">
            <?php 
                if(WEBSITE_SYSTEM == '1') {
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

                    add_filter( 'gform_field_value_data_entrega', 'data_entrega_population_function' );
                    function data_entrega_population_function( $value ) {
                        return get_field('delivery_date',$_GET['rid']);
                    }

                    add_filter( 'gform_field_value_mensagem', 'mensagem_population_function' );
                    function mensagem_population_function( $value ) {
                        return get_field('message',$_GET['rid']);
                    }

                    add_filter( "gform_pre_render_{$form_id}", 'service_request_form_pre_render' );
                    function service_request_form_pre_render( $form ) {
                        global $form_fields;

                        foreach ( $form['fields'] as &$field ) {
                            if ( $field->id == $form_fields['location']['id'] && $field->type === 'select' ) {
                                $terms = get_terms(
                                    array(
                                        'taxonomy'   => 'location',
                                        'parent'     => 0,
                                        'orderby'    => 'name',
                                        'order'      => 'ASC'
                                    )
                                );

                                foreach ( $terms as $term ) {
                                    $field->choices[] = array( 'text' => $term->name, 'value' => $term->term_id );
                                }
                            }
                        }

                        return $form;
                    }
                }
            ?>
            <!-- Gravity Form: <?php echo $form_id ?> -->
            <div class="d-flex flex-row flex-wrap justify-content-center container">
                <p class="has-text-align-center">
                    <?php echo do_shortcode("[gravityform id=\"{$form_id}\" title=\"false\" description=\"false\"]") ?>
                </p>
            </div>
        </div>

        <?php 
        /*
         * GS: Remove additional dropdown not added via Gravity Forms.
         * This dropdown is moved inside the Gravity Form via jQuery appendTo
         *
        <div class="sidebar-service-message m-t-28">
            <div id="contact-this-seller" class="form-content">
                <div class="expert-field-message d-flex flex-wrap justify-content-between">
                    <h4 class="box__title title" >
                        <?php esc_html_e('Profissionais disponiveis', 'idealbiz'); ?>
                        <span style="color: #790000;margin-left: 4px;" class="gfield_required">*</span>
                    </h4>
                    <select id="seleciona_expert" class="location_expert_search"></select>
                    <span class="validation_message_expert"></span>
                </div>
                <div class="loader" style="left: 50%; position: relative; display: none; margin-left: -15px; margin-top: 30px;">
                </div>
                <div class="expert-preview m-t-20">
                </div>
            </div>
        </div>
        */ 
        ?>

        <div class="loader" style="left: 50%; position: relative; display: none; margin-left: -15px; margin-top: 30px;"></div>
        <div class="expert-preview m-t-20"></div>
    </div>
</section>

<?php
//$terms =  wp_get_object_terms($post_id, 'service_cat', array('fields' => 'ids'));

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

        $pcontent = get_field('pitch', $post->ID); 
        ?>

        <!-- Begin Modal Template -->
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
                            </div>
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
                        $expert_looking_for_projects_on = get_post_meta($post->ID, 'expert_looking_for_projects_on')[0];
                        $expert_has_expertise = get_post_meta($post->ID, 'expert_has_expertise')[0];

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
                                                    <?php foreach ($expert_looking_for_projects_on as $term) : ?>
                                                        <span><?php echo get_term_by('id', $term, 'service_cat')->name; ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($expert_has_expertise) : ?>
                                                <h4><?php _e('Have expertise on the following areas:', 'idealbiz'); ?></h4>
                                                <div class="d-flex flex-column">
                                                    <?php foreach ($expert_has_expertise as $term) : ?>
                                                        <span><?php echo get_term_by('id', $term, 'service_cat')->name; ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                        endif; 
                    ?>

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
                                        <h4 class="<?php echo $i > 1 ? 'm-t-10' : ''; ?>">
                                            <strong><?php echo $experience['company']; ?> - </strong>
                                        </h4>
                                        
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
                                        <div><strong><?php echo $other_language['language']; ?> -
                                            </strong><span><?php _e($other_language['level'], 'idealbiz'); ?></span></div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <?php
                        if ($expert_projects = get_field('expert_projects', $post->ID)) :
                            $showprojects = 0;
                            foreach ($expert_projects as $project) :
                                if ($project['project'] != '') {
                                    $showprojects = 1;
                                }
                            endforeach;

                            if ($showprojects) :
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
                            endif;
                        endif;
                    ?>

                    <?php 
                        if ($expert_it_knowledge = get_field('expert_it_knowledge', $post->ID)) :
                            $showit = 0;
                            foreach ($expert_it_knowledge as $it_knowledge) :
                                if ($it_knowledge['name'] != '') {
                                    $showit = 1;
                                }
                            endforeach;

                            if ($showit) :
                                ?>
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
                                                    <div>
                                                        <strong><?php echo $it_knowledge['name']; ?> - </strong>
                                                        <span><?php _e($it_knowledge['level'], 'idealbiz'); ?></span>
                                                    </div>
                                                <?php endforeach; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endif;
                        endif;
                    ?>
                </div>
            </div>
        </div>
        <!-- End Modal Template -->

        <?php
        $show_expert = 1;

        if (isset($_GET['sr'])) { 
            $sexpert = get_field('consultant', $_GET['rid']); 
            if ($sexpert->ID == get_user_by('email', get_field('expert_email', $post->ID))->ID) {
                $show_expert = 0;
            }
        }

        if ($show_expert) {
            $fee = 1;
            if (WEBSITE_SYSTEM == '1') {
                $userIDFee = get_user_by('email', get_field('expert_email', $post->ID))->ID;
                if (!userHasActiveExpertFeeSubscription($userIDFee)) {

                    $fee = 0;
                }
            }

            $aux_class = '';
            if (get_field('idealbiz_support_expert', $post->ID) == '1') {
                $aux_class = ' customer_care ';
                $fee = 1;
            }

            $escalao = get_field('echelon_competency_factor', $post->ID);
            $cl_fixed_ppc_value = get_field('fixed_ppc_value',$post->ID);
            /* var_dump($cl_fixed_ppc_value); */
            $cl_sr_pay_lead_mode = '<span class="cl_icon-local dashicons dashicons-yes-alt"></span>'.consultLeadModeServieceRequest($post->ID,true);
            
            $array_ppc_fixo =json_encode($cl_fixed_ppc_value); 
            $arry_escalao = json_encode($escalao);

            //Este pedaço de codigo exibe na tela de forma legivel os escalões.
            
            if (isset($_GET['escalao'])){
                foreach ($escalao as $key => $vazio) {
                    $vazio['begin_echelon'];

                    if ($vazio) {
                        echo ' ' . get_the_title() . ' = ';
                        foreach ($escalao as $key => $nivel) {
                            //print_r($nivel);

                            echo $nivel['begin_echelon'] . ' <> ';
                            echo $nivel['finish_echelon'];
                            echo ' - ' . $nivel['percentage'] . '% |';
                        }
                        echo '<br>';
                        $vazio = '';
                        break;
                    }
                }
            }
            
            if ($fee == 1) {
                $p .= '<div data-escalao=' . $arry_escalao . ' data-fee="' . $fee . '" data-ppc-fixo='.$array_ppc_fixo.'  data-f="" data-competencyfactor="" data-expert="' . $post->ID . '" data-locations="' . join(',', wp_list_pluck($location_objs, 'slug')) . '" style="display: none;" class="p-20 m-b-20 ' . $classes . $aux_class . ' ' . $location_as_classes . ' expert-card position-relative flex-column black--color white--background dropshadow font-weight-medium"  >';
                $p .= '<div class="d-flex flex-row center-content">';
                $p .= '<div class="w-100px h-100px b-r d-block o-hidden no-decoration">';
                $p .= '<img class="w-100 h-100 object-cover" src="' . get_field('foto', $post->ID)['sizes']['medium'] . '">';
                $p .= '</div>'; 
                $p .= '<div class="calc-100-120 h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">';
                $p .= '<div>';
                $p .= '<h3 class="font-weight-semi-bold base_color--color">' . get_the_title() . '</h3> ';
                $p .= '</div>';
                $p .= '<span class="small">' . join(', ', wp_list_pluck($term_obj_list, 'name')) . '</span>';
                $p .= '<div class="cl_icon location p-t-10 font-weight-bold">'.$cl_sr_pay_lead_mode.'</div>';
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

$p .= '<span id="result_D" class="cl_aviso" ></span>';
?>

<style>
    .experts_by_service_cat,
    .experts_by_service_cat>.gfield_label,
    .experts_by_service_cat>.ginput_container {
        display: none;
    }
</style>

<script>
    //NPMM - Funções referente ao form de parametros

    function showMessage() {
        let par1 = document.getElementById("parametro1").value;
        let par2 = document.getElementById("parametro2").value;
        let amount_involved = document.querySelector('input[name="input_22"]');
        let cl_sr_company_parameter_1 = document.querySelector('input[name="input_'+<?php echo $cl_sr_company_parameter_1;?>+'"]');
        let cl_sr_company_parameter_2 = document.querySelector('input[name="input_'+<?php echo $cl_sr_company_parameter_2;?>+'"]');
        let sum = parseFloat(par1) + parseFloat(par2);

        if (document.getElementById("selector").value === "company") {
            if (par1 == "") {
                document.getElementById("error_par1").style.display = "block";
                return false;
            }else{
                document.getElementById("error_par1").style.display = "none";
            }

            if (par2 == "") {
                document.getElementById("error_par2").style.display = "block";
                return false;
            }else{
                document.getElementById("error_par2").style.display = "none";
            }

            /* amount_involved.disabled = true; */
            amount_involved.style.backgroundColor = "#f1f1f1";
            /* document.getElementById("field_"+<?php echo $form_id; ?>+"_22").style.display = "none !important"; */
            document.getElementById("field_"+<?php echo $form_id; ?>+"_26").style.display = "none";
            amount_involved.value = sum;
            cl_sr_company_parameter_1.value = par1;
            cl_sr_company_parameter_2.value = par2;
        }

        document.getElementById("form_sr").style.display = "block";
        document.getElementById("form_par").style.display = "none";
    }

    function hideMessage() {
        document.getElementById("form_sr").style.display = "none";
        if (document.getElementById("selector").value === "company") {
            document.getElementById("camposAdicionais1").style.display = "block";
        } else {
            document.getElementById("camposAdicionais1").style.display = "none";
        }
    }

    // Fields we want to track "onChange"
    var GF_FIELDS = {
        "<?php echo "{$form_fields['service_category']['id']}" ?>": { prevValue: '', onChange: onInputChangeMemberSearch },
        "<?php echo "{$form_fields['amount']['id']}" ?>":           { prevValue: '', onChange: onInputChangeMemberSearch },
        "<?php echo "{$form_fields['location']['id']}" ?>":         { prevValue: '', onChange: onInputChangeMemberSearch },

        "<?php echo "{$form_fields['member_search_results']['id']}" ?>":   { prevValue: '', onChange: onInputChangeMemberSelection },
        "<?php echo "{$form_fields['member_search_selection']['id']}" ?>": { prevValue: '', onChange: onInputChangeMemberSelection }
    };

    function onInputChange( gfElem, gfFormId, gfFieldId ) {
        console.log('Change detected for ', gfElem, ', Form Id: "', gfFormId, '", Field Id: "', gfFieldId, '"');

        if(GF_FIELDS[`${gfFieldId}`]) {
            var field = FIELD_ON_CHANGE[`${gfFieldId}`];
            var currValue = jQuery(`#input_${gfFormId}_${gfFieldId}`).val();

            // Only fire if value actually changes (GravityForms fires onChange for keyup+onchange events)
            if(field.prevValue !== currValue) {
                field.prevValue = currValue;
                field.onChange(gfElem, gfFormId, gfFieldId);
            }
        }

        return;
    }

    function onInputChangeMemberSearch( gfElem, gfFormId, gfFieldId ) {
        var serviceCategoryValue = jQuery('<?php echo "#input_{$form_id}_{$form_fields['service_category']['id']}" ?>').val();
        var amountValue          = jQuery('<?php echo "#input_{$form_id}_{$form_fields['amount']['id']}" ?>').val();
        var locationValue        = jQuery('<?php echo "#input_{$form_id}_{$form_fields['location']['id']}" ?>').val();

        console.log('Values: ServiceCategory ', serviceCategoryValue, ', Amount: "', amountValue, '", Location: "', locationValue, '"');

        jQuery.post({
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                /* WP Fields */
                //_ajax_nonce: "<?php wp_create_nonce('single_counseling_search_members') ?>",
                action: "single_counseling_search_members",

                /* Our data fields */
                serviceCategoryValue: serviceCategoryValue,
                amountValue: amountValue,
                locationValue: locationValue
            },
            success: function(data) {
                console.log("AJAX call successful");
                jQuery("body").append(data);
            }
        }).fail(function() {
            console.error("AJAX call failed");
        });
    }

    function onInputChangeMemberSelection( gfElem, gfFormId, gfFieldId ) {
        return;
    }

    // REFATURAÇÃO PARA REENCAMINHAMENTO E RECOMENDAÇÃO.

    let cl_id_campo_origem = '<?php echo $cl_sr_type_origin_id_field;?>'
    let cl_id_campo_Origin_SR = '<?php echo $cl_sr_origin_sr_id_of_field;?>'
    let cl_id_campo_PPC_Fixo_SR = '<?php echo $cl_input_sr_fixed_ppc_value_id_field;?>'
    let $cl_origin = document.querySelector('input[name="input_'+cl_id_campo_Origin_SR+'"]');
    let sr_type_origin = document.querySelector('input[name="input_'+cl_id_campo_origem +'"]');
    /* let input_sr_fixed_ppc_value = document.querySelector('input[name="input_'+cl_id_campo_PPC_Fixo_SR +'"]'); */

    $cl_origin.value = '<?php echo $cl_rid; ?>';
    sr_type_origin.value = '<?php echo $cl_sr_type_origin; ?>';
    /* input_sr_fixed_ppc_value.value = '<?php echo $cl_sr_type_origin; ?>'; */

    var e;
    var  cl_care;
    var  cl_care2 = '';
    var orcamento;
    var cl_ini_echlon = '';
    var cl_fim_echlon = '';

    jQuery(document).ready(($) => {
        gform.addAction( 'gform_input_change', onInputChange );

        // GS: Disable entire jQuery code
        if(true) {
            return;
        }

        // Select the option with a value of '1'
        // $('.single-counceling .ginput_container_custom_taxonomy select').val(< ?php echo $terms[0]; ? >);
        
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

            //MODULO SELEÇÃO DE ESCALÃO CRIADO PELO CLEVERSON
            //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
            var cont = 0;
            var cl_vr = $('.valor_referencia input[type="text"]');
            $(".expert-preview .expert-card").each(function() {  //Arry com os dados do escalao     
                var cl_pre_escalao = $(this).data('escalao');
                var cl_expert = $(this).data('expert');
                var cl_ppc_fixo = $(this).data('ppc-fixo');

                if (cl_pre_escalao!=null && $(this).hasClass('active')) {
                    $(cl_pre_escalao).each(function(key, value) { 
                        var cl_begin_echelon = parseInt(cl_pre_escalao[cont].begin_echelon);
                        var cl_finish_echelon = parseInt(cl_pre_escalao[cont].finish_echelon);
                        var cl_percentage = cl_pre_escalao[cont].percentage;

                        //alert('cl_begin_echelon '+cl_begin_echelon+' cl_finish_echelon '+cl_finish_echelon+' cl_percentage ');
                        
                        if (cl_vr.val() >= cl_begin_echelon && cl_vr.val() <= cl_finish_echelon) {    
                            var cl_orcamento = (cl_vr.val()*cl_percentage)/100;
                            //console.log(cont);
                            //console.log(cl_pre_escalao);
                            //console.log(cl_begin_echelon);
                            //console.log(cl_orcamento);
                            //console.log(cl_expert );
                            //console.log(cl_ppc_fixo);
                            $('.maximo input[type="text"]').val(cl_orcamento);
                            $('input[name="input_'+cl_id_campo_PPC_Fixo_SR+'"]').val(cl_ppc_fixo); 
                            return false;
                        }
                        cont++;
                    });
                }
            })
            //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        });

        $('.single-counceling .ginput_container_custom_taxonomy select').on('change', function(event) {
            event.preventDefault();
            var id = $(this).val();
            $('.expert-card').css('display', 'none');
            $('.service_cat_' + id).css('display', 'flex');

            var selected_location = $('.location_expert_search').val();

            $('.location_expert_search option').css('display', 'block');
            $('.service_cat_' + id).each(function() {
                var locs = $(this).data('locations');

                if (locs.indexOf(',') >= 0) {
                    locs = $(this).data('locations').split(",");
                }

                for (var i = 0; i < locs.length; i++) {
                    $('.location_expert_search option[value="' + locs[i] + '"]').css('display','block');
                }
            });

            $('.location_expert_search option[value="all"]').css('display', 'block');

            if($('.location_expert_search option[value="'+selected_location+'"]').css('display') === 'block'){
                $('.location_expert_search option[value='+selected_location+']').attr('selected','selected');
            } else {
                $('.location_expert_search option[value=all]').attr('selected','selected');
            }

            $('.location_expert_search').trigger('change');
        });
        $('.single-counceling .ginput_container_custom_taxonomy select').trigger('change');

        $('.location_expert_search').on('change', function(event) {
            event.preventDefault();
            $('.expert-preview').find('#result_D').html('<?php /* echo __('Enter the reference value and Budget available in the fields above.','idealbiz') */ ?>');
            
            var val = $(this).val();
            var found = 0;
            var cat = $('.single-counceling .ginput_container_custom_taxonomy select').val();
            if (val != 'all') {
                $('.expert-preview .expert-card').each(function() {
                    var l = $(this).data('locations');
                    if (l.indexOf(val) >= 0 && $(this).hasClass('service_cat_' + cat)) {
                        $(this).css('display', 'flex');
                        found++;
                        window.cl_care2 = 1
                    } else {
                        $(this).css('display', 'none');
                        window.cl_care = 1
                        /* $('.expert-preview .customer_care').attr('style', 'display:block !important;'); */
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
                /* $('.expert-preview .not-found').css('display', 'flex'); */
                /* $('.expert-preview .customer_care').attr('style','display:block !important;'); */
            }
            calc_F_G();
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

        if ($(window).width() < 3000) {
            $('#contact-this-seller').appendTo(".experts_by_service_cat");
            $('.experts_by_service_cat').css('display', 'block');
            $('.experts_by_service_cat > .gfield_label').css('display', 'none');
            $('.experts_by_service_cat > .ginput_container').css('display', 'none');
        }

        $(window).on('resize', function() {
            if ($(window).width() < 3000) {
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

        gform.addFilter('gform_datepicker_options_pre_init', function(optionsObj, formId, fieldId) {
            optionsObj.minDate = 0;
            return optionsObj;
        });

        <?php if (isset($_GET['sr'])) { 
                    $rid = $_GET['rid'];
                    $user = get_field('customer', $_GET['rid']);
                    $sexpert = get_field('consultant', $_GET['rid']); // $sexpert->ID
            ?>
            $('.single-counceling .ginput_container_custom_taxonomy select').val(<?php echo $_GET['sr']; ?>).trigger('change');

            $('.single-counceling .name_first input').val('<?php echo $user->first_name; ?>');
            $('.single-counceling .name_last input').val('<?php echo $user->last_name; ?>');

            $('.single-counceling .ginput_container_textarea textarea').val('<?php echo get_field('message', $rid); ?>').prop('disabled', true);
            $('.single-counceling .ginput_container_phone input').val('<?php echo get_field('service_request_phone', $rid); ?>');
            $('.single-counceling .ginput_container_date input').val('<?php echo get_field('delivery_date', $rid); ?>');

            $('.single-counceling .valor_referencia input').val('<?php echo get_field('reference_value', $rid); ?>').prop('disabled', true);
            $('.single-counceling .minimo input').val('<?php echo get_field('budget_min', $rid); ?>').prop('disabled', true);
            $('.single-counceling .maximo input').val('<?php echo get_field('budget_max', $rid); ?>').prop('disabled', true);
        <?php } ?>

        <?php if (WEBSITE_SYSTEM == '' || WEBSITE_SYSTEM == '0') { ?>
            $('.valor_referencia input[type="text"]').val(0);
        <?php } ?>

        <?php if (WEBSITE_SYSTEM == '1') { ?>
            $('.form-selector').find('form').append('<input type="hidden" name="idb_tax" value="" />');

            // Campos onde se insere o valor de Refrência, Mínimo e Máximo. 
            $('.valor_referencia .ginput_container_text').append(' <span class="curr_symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>');
            $('.minimo .ginput_container_text').append(' <span class="curr_symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>');
            $('.maximo .ginput_container_text').append(' <span class="curr_symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>');                    
            
            $('.valor_referencia .gfield_label').append('<span class="gfield_required">*</span>');

            //Coloca os "i's" ao lado dos campos.
            $('label[for=input_'+<?php echo $form_id;?>+'_1]').append('<div class=gfield_label><button id="cl_input1" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_2]').append('<div class=gfield_label><button id="cl_input2" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_3]').append('<div class=gfield_label><button id="cl_input3" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_4]').append('<div class=gfield_label><button id="cl_input4" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_5]').append('<div class=gfield_label><button id="cl_input5" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_6]').append('<div class=gfield_label><button id="cl_input6" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_7]').append('<div class=gfield_label><button id="cl_input7" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_8]').append('<div class=gfield_label><button id="cl_input8" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_9]').append('<div class=gfield_label><button id="cl_input9" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_10]').append('<div class=gfield_label><button id="cl_input10" class="info-balloon">i</button>');
            /* $('label[for=input_'+<?php echo $form_id;?>+'_11]').append('<div class=gfield_label><button id="cl_input11" class="info-balloon">i</button>'); */
            $('label[for=input_'+<?php echo $form_id;?>+'_12]').append('<div class=gfield_label><button id="cl_input12" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_13]').append('<div class=gfield_label><button id="cl_input13" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_14]').append('<div class=gfield_label><button id="cl_input14" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_15]').append('<div class=gfield_label><button id="cl_input15" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_16]').append('<div class=gfield_label><button id="cl_input16" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_17]').append('<div class=gfield_label><button id="cl_input17" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_18]').append('<div class=gfield_label><button id="cl_input18" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_19]').append('<div class=gfield_label><button id="cl_input19" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_20]').append('<div class=gfield_label><button id="cl_input20" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_21]').append('<div class=gfield_label><button id="cl_input21" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_22]').append('<div class=gfield_label><button id="cl_input22" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_23]').append('<div class=gfield_label><button id="cl_input23" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_24]').append('<div class=gfield_label><button id="cl_input24" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_25]').append('<div class=gfield_label><button id="cl_input25" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_26]').append('<div class=gfield_label><button id="cl_input26" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_27]').append('<div class=gfield_label><button id="cl_input27" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_28]').append('<div class=gfield_label><button id="cl_input28" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_29]').append('<div class=gfield_label><button id="cl_input29" class="info-balloon">i</button>');
            $('label[for=input_'+<?php echo $form_id;?>+'_30]').append('<div class=gfield_label><button id="cl_input30" class="info-balloon">i</button>');

            //Chama o iziModal.
            $('#cl_input1').click(function() { $('#campo1').iziModal('open'); return false;});
            $('#cl_input2').click(function() { $('#campo2').iziModal('open'); return false;});
            $('#cl_input3').click(function() { $('#campo3').iziModal('open'); return false;});
            $('#cl_input4').click(function() { $('#campo4').iziModal('open'); return false;});
            $('#cl_input5').click(function() { $('#campo5').iziModal('open'); return false;});
            $('#cl_input6').click(function() { $('#campo6').iziModal('open'); return false;});
            $('#cl_input7').click(function() { $('#campo7').iziModal('open'); return false;});
            $('#cl_input8').click(function() { $('#campo8').iziModal('open'); return false;});
            $('#cl_input9').click(function() { $('#campo9').iziModal('open'); return false;});
            $('#cl_input10').click(function() { $('#campo10').iziModal('open'); return false;});
            $('#cl_input11').click(function() { $('#campo11').iziModal('open'); return false;});
            $('#cl_input12').click(function() { $('#campo12').iziModal('open'); return false;});
            $('#cl_input13').click(function() { $('#campo13').iziModal('open'); return false;});
            $('#cl_input14').click(function() { $('#campo14').iziModal('open'); return false;});
            $('#cl_input15').click(function() { $('#campo15').iziModal('open'); return false;});
            $('#cl_input16').click(function() { $('#campo16').iziModal('open'); return false;});
            $('#cl_input17').click(function() { $('#campo17').iziModal('open'); return false;});
            $('#cl_input18').click(function() { $('#campo18').iziModal('open'); return false;});
            $('#cl_input19').click(function() { $('#campo19').iziModal('open'); return false;});
            $('#cl_input20').click(function() { $('#campo20').iziModal('open'); return false;});
            $('#cl_input21').click(function() { $('#campo21').iziModal('open'); return false;});
            $('#cl_input22').click(function() { $('#campo22').iziModal('open'); return false;});
            $('#cl_input23').click(function() { $('#campo23').iziModal('open'); return false;});
            $('#cl_input24').click(function() { $('#campo24').iziModal('open'); return false;});
            $('#cl_input25').click(function() { $('#campo25').iziModal('open'); return false;});
            $('#cl_input26').click(function() { $('#campo26').iziModal('open'); return false;});
            $('#cl_input27').click(function() { $('#campo27').iziModal('open'); return false;});
            $('#cl_input28').click(function() { $('#campo28').iziModal('open'); return false;});
            $('#cl_input29').click(function() { $('#campo29').iziModal('open'); return false;});
            $('#cl_input30').click(function() { $('#campo30').iziModal('open'); return false;});


            //Bloquear autocomplete
            $(document).ready(function() {
                $(document).on('focus', ':input', function() {
                    $(this).attr('autocomplete', 'off');
                });
            });

            $('input, :input').attr('autocomplete', 'off');

            $("#autocmpldisablechk").click(function () {
                if (!$(this).is(":checked")) { 
                    // enable autocomplete
                    $("#autocomplete").autocomplete({
                        disabled: false
                    });
                }
                else { 
                    // disable autocomplete
                    $("#autocomplete").autocomplete({
                        disabled: true
                    });
                }
            });
            
            var vr = $('.valor_referencia input[type="text"]'); //Referência
            var min = $('.minimo input[type="text"]'); //Mínimo
            /* var max = $('.maximo input[type="text"]'); //Máximo */
            var max = $('.valor_referencia input[type="text"]'); //Referência
            
            //ON CHANGE - Valida Campo Máximo, verifica se é nulo 0 ou String CL
            vr.on('change', function() {
            var max = vr.val();
                $('.expert-preview .not-found').css('display', 'none');
                //Aqui o Orçamento passa a ter o mesmo valor do vr
                calc_F_G();
                /* max = vr.val(); */
            });

            //NPMM - ANULA ENTER DO MONTANTE ENVOLVIDO
            $('#input_12_22').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                    //e.preventDefault();
                    /* alert('Select Expert');
                    $("#seleciona_expert").focus(); */
                    return false;
                }

            });                                 

            //calculo fator de competencia -> f
            
            function calc_F_G() {
                var count_competents = 0;
                var v_ref = parseInt(vr.val());

                //Valida valor de referência e máximo não são Nulos e String.
                var ciclo_pai = 0; // Apagar depois
                if (vr.val() != '' && !isNaN(vr.val())) {
                    $('.expert-preview .customer_care').attr('style', '');
                    /* $('.expert-preview').find('#result_D').html(''); */

                    $(".expert-preview .expert-card").each(function() {
                        //var e = $(this).data('competencyfactor'); // Codigo antigo 
                        //console.log(ciclo_pai + "-> Entrei ciclo Pai a variavel e é :" + e); // Apagar depois 
                        var mostra_card = 0;
                        var tx = '';
                        var pre_escalao = $(this).data('escalao'); //Arry com os dados do escalao
                        var i = 0;

                        $(pre_escalao).each(function(key, value) { 
                            var begin_echelon = parseInt(pre_escalao[i].begin_echelon);
                            var finish_echelon = parseInt(pre_escalao[i].finish_echelon);
                            var percentage = pre_escalao[i].percentage;
                            window.cl_ini_echlon = begin_echelon;
                            window.cl_fim_echlon = finish_echelon;
                            //console.log(typeof v_ref); //Verifica o Typo do v_ref Ex: Saída -> number
                            
                            //console.log(" O Valor do percentagem que vou utlizar é : " + percentage); // Apagar depois
                            //console.log(i + " ----> Estou TENTADO entrar no IF com VR= " + v_ref + " begin_echelon = " + begin_echelon + " finish_echelon = " + finish_echelon); // Apagar depois
                            if (v_ref >= begin_echelon && v_ref <= finish_echelon) {                           
                                //console.log(" CONSEGUI ENTRAR!!! no IF " + i + " com valor de ref : " + v_ref); // Apagar depois
                                window.e = percentage;
                                var calc_max = (v_ref/100)*e;
                                window.orcamento = calc_max;
                                var max = orcamento;
                                //console.log('Valor do Orcamento dentro do ciclo: '+orcamento);
                                //console.log(" Guardei " + e + " na variavel  e e já sai do IF " + i); // Apagar depois
                                //console.log(' ---->Sai do ciclo Filho com valor de e Filho : ' + e); // Apagar depois 
                                
                                return false;  
                            }

                            i++;
                        });
    
                        //console.log(ciclo_pai + " -> Sai do ciclo Pai variavel e é :" + e); // Apagar depois
                        ciclo_pai = ciclo_pai + 1;

                        if (pre_escalao==null){
                            $(this).addClass('non-competent');
                        }   

                        if (vr.val() >= cl_ini_echlon && vr.val() <= cl_fim_echlon) {
                            $(this).removeClass('non-competent');
                            window.cl_ini_echlon = '';
                            window.cl_fim_echlon = '';
                            count_competents++;
                        } else {
                            $(this).addClass('non-competent');  
                        }
                    });
                    
                    if (count_competents == 0 || cl_care == 1 && cl_care2 == '' ) {
                            $('.expert-preview .customer_care').attr('style', 'display:flex !important;');
                            $('.expert-preview').find('#result_D').html('<?php echo __($descicao[12][12]);?>');
                    } else {
                        $('.expert-preview .customer_care').attr('style', 'display:none !important;');
                        cl_care2 = '';
                    }
                } else {
                    $('.expert-preview .expert-card').each(function() {
                        $(this).addClass('non-competent');
                    });
                }
            }

            calc_F_G();

        <?php } ?>    
    });
</script>

<style>
    .error_par{
        color:red;
        font-style: italic;
    }
    .select_par{
        min-width: 250px;
    }
    .form_par{
        border: #ced4da 1px solid; 
        border-radius: 5px;
        padding: 20px;
    }
    .form_sr_hidden{
        display: none;
    }

    .samll{
        margin-top: -6px;
    }
    .cl_icon-local{
        font-size:1.3em;
        text-transform:uppercase;

    }
    .cl_icon{
        font-size:0.7em;
        text-transform:uppercase;
        margin-left: -5px;
    }
    
    .select2-results__option {
        line-height: 32px;
        padding: 0 6px;
    }

    .gform_wrapper textarea.medium {
        height: 78px !important;
    }

    .no-margin {
        margin-right: 0 !important;
    }

    .f-label {
        font-size: 11px;
        padding-top: 37px !important;
        padding-right: 10px !important;
        border-color: rgba(255, 255, 255, 0) !important;
        min-width: 160px;
    }

    .inline_label {
        display: none !important;
        flex-direction: row;
        align-items: center;
        margin-top: 32px !important;
    }

    .inline_label .gfield_label {
        margin-bottom: 0;
        padding-right: 10px;
        min-width: 160px;
    }

    .inline_label .ginput_container {
        margin-top: 0 !important;
    }

    .valor_referencia,
    .minimo,
    .maximo {
        position: relative;
    }

    .valor_referencia .ginput_container,
    .minimo .ginput_container,
    .maximo .ginput_container {
        position: relative;
        margin-right: 0px;
    }

    .valor_referencia input[type="text"],
    .minimo input[type="text"],
    .maximo input[type="text"] {
        padding: .375em 1.8em !important;
        min-width: 50px;
        width: 10px;
    }

    .curr_symbol {
        margin-left: 1px;
        position: absolute;
        top: 25%;
        left: 1px;
    }

    .info-balloon {
        margin-right: 0px;
        /* position: absolute; */
        top: 1%;
        right: -10px;
        background-color: #ffffff;
    }

    .gfield_description {
        display: none;
    }

    .gform_wrapper {
        width: 660px;
    }

    .box__title{
        font-weight: 700;
        font-size: 1.25em;
    }

    .cl_aviso{
        font-weight: 700;
        font-size: 1.0em;
    }

    <?php if (WEBSITE_SYSTEM == '1') : ?>
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
    <?php endif; ?>
</style>

<?php
//$terms = get_terms(array('taxonomy' => 'service_cat', 'hide_empty' => false, 'parent' => 0));
//$terms = get_terms(array('taxonomy' => 'location', 'hide_empty' => false, 'parent' => 0));
//get_template_part('elements/member-search/member', 'search');

get_footer();
?>
