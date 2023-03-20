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
$form_field_ids = array(
    'service_category' => -1,
    'amount'           => -1,
    'location'         => -1,
    'member_selection' => -1
);

// Definição do funcionamento Service Request a partir da variável global "WEBSITE_SYSTEM"
$USE_SR_SYSTEM_3STEP_PURCHASE = (WEBSITE_SYSTEM == '' || WEBSITE_SYSTEM == '0');
$USE_SR_SYSTEM_LEAD_PURCHASE  = (WEBSITE_SYSTEM == '1');


// Informação das tooltips
$show_tooltips = $USE_SR_SYSTEM_LEAD_PURCHASE;
$form_tooltips = array();

// Descobrir o ID e campos do Gravity Form. Usamos uma Class CSS configurada no Form.
foreach( GFAPI::get_forms() as $form ) {
    if ( $form['cssClass'] === 'service-request' ) {
        $form_id = $form['id'];

        // Descobrir campos com que queremos interagir. Usamos Classes CSS configuradas nos Fields.
        foreach( $form['fields'] as $field) {

            // Guardar ids que queremos passar para o código JavaScript
            if     ( str_contains( $field->cssClass, 'service-request-service-category' ) ) {
                $form_field_ids['service_category'] = $field->id;
            }
            elseif ( str_contains( $field->cssClass, 'valor_referencia' ) ) {
                $form_field_ids['amount'] = $field->id;
            }
            elseif ( str_contains( $field->cssClass, 'service-request-location' ) ) {
                $form_field_ids['location'] = $field->id;
            }
            elseif ( str_contains( $field->cssClass, 'service-category-member-selection' ) ) {
                $form_field_ids['member_selection'] = $field->id;
            }

            // Guardar IDs e descrições que queremos apresentar nas tooltips
            if ($show_tooltips) {
                if ( $field->type !== 'hidden' && $field->visibility === 'visible' && $field->type !== 'captcha' && $field->description ) {
                    $form_tooltips[] = array(
                        'id'          => $field->id,
                        'description' => $field->description
                    );
                }
            }
        }

        break;
    }
}

//NPMM - Resolve que tipo serviço - E o nomeia.
if($_GET["refer"]==1 && !$_GET['rid']) {
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
} elseif ($_GET["refer"]==1 && $_GET['rid']!= null) {
    $cl_sr_type_origin = 'forward_service';
    $cl_sr_Type_origin_tittle = __('_str Forward to Member', 'idealbiz');
} else {
    $cl_sr_type_origin = 'normal_service';
    $cl_sr_Type_origin_tittle = '';
}

$cl_rid = $_GET['rid'];

//EDITADO PELO CLEVERSON VIEIRA
//Iniciando Escalão sem Orçamento dia 08/07/21

// Get the Form fields

//Garante que não haja sessões criadas para iniciar novo processo se for membro.
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

// verifica se é membro para proceder a referenciação
if ($cl_membro) {
    $_SESSION['rid'] = $_GET['rid'];//ID do Service Request Original
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

if($USE_SR_SYSTEM_LEAD_PURCHASE) {
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
}

// Alterações ao formulário GravityForm $form_id
add_filter( "gform_pre_render_{$form_id}", 'service_request_form_pre_render' );
function service_request_form_pre_render( $form ) {
    global $form_field_ids;

    foreach ( $form['fields'] as &$field ) {

        // Adicionar locations à Dropdown
        if ( $field->id == $form_field_ids['location'] && $field->type === 'select' ) {
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
            <?php
                if($cl_membro && $cl_member_cat != false) {
                    ?>
                    <div>
                        <h1 style="text-align: center;">'.$cl_sr_Type_origin_tittle.' </h1>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <?php
                } else {
                    echo the_content();
                }
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

    <!-- Gravity Form: <?php echo $form_id ?> -->

    <div class="container m-b-25 form_sr_hidden " id="form_sr">
        <div class="div-to-align">
            <div class="d-flex flex-row flex-wrap justify-content-center container">
                <p class="has-text-align-center">
                    <?php echo do_shortcode("[gravityform id=\"{$form_id}\" title=\"false\" description=\"false\"]") ?>
                </p>
            </div>
        </div>

        <div id="member-search-results">
            <div class="loader" style="left: 50%; position: relative; margin-left: -15px; margin-top: 30px; display: none; ">
            </div>
            <div class="member-search-results-list">
            </div>
        </div>


        <?php
            //Tooltips: iziModal com a descrição dos campos
            if ( $show_tooltips ) {
                foreach( $form_tooltips as $tooltip ) {
                    infoModal('<h3>' . __($tooltip['description'], 'idealbiz') . '</h3>', "tooltip_{$tooltip['id']}", 'd-none');
                }
            }
        ?>
    </div>
</section>

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

    // Campos do formulário que queremos interagir:
    //   - id: ID do campo no GravityForms
    //   - selector: ID para uso com jQuery
    //   - onChange: Função a chamar quando campo for alterado
    //   - prevValue: Valor anterior do campo (para detetar alterações)
    var GF_FIELDS = {
        fieldIdMap: {},

        SERVICE_CATEGORY: {
            id: "<?php echo "{$form_field_ids['service_category']}" ?>",
            selector: "<?php echo "#input_{$form_id}_{$form_field_ids['service_category']}" ?>",
            onChange: onInputChangeMemberSearch,
            prevValue: ''
        },
        AMOUNT: {
            id: "<?php echo "{$form_field_ids['amount']}" ?>",
            selector: "<?php echo "#input_{$form_id}_{$form_field_ids['amount']}" ?>",
            onChange: onInputChangeMemberSearch,
            prevValue: ''
        },
        LOCATION: {
            id: "<?php echo "{$form_field_ids['location']}" ?>",
            selector: "<?php echo "#input_{$form_id}_{$form_field_ids['location']}" ?>",
            onChange: onInputChangeMemberSearch,
            prevValue: ''
        },

        MEMBER_SEARCH_RESULTS: {
            selector: "#member-search-results",
            loaderSelector: "#member-search-results .loader",
            cardsPlaceholderSelector: "#member-search-results .member-search-results-list",
            cardsSelector: ".expert-card",

            prevRequest: {}
        },

        MEMBER_SELECTION: {
            id: "<?php echo "{$form_field_ids['member_selection']}" ?>",
            selector: "<?php echo "#input_{$form_id}_{$form_field_ids['member_selection']}" ?>",
            resultsPlaceholderSelector: "<?php echo "#field_{$form_id}_{$form_field_ids['member_selection']}" ?>",
            prevValue: ''
        }
    };
    (function() {
        var keys = Object.keys(GF_FIELDS);
        var i, id;
        for(i in keys) {
            id = GF_FIELDS[keys[i]].id;
            if(id) {
                GF_FIELDS.fieldIdMap[id] = keys[i];
            }
        }
    })();

    function onInputChange( gfElem, gfFormId, gfFieldId ) {
        if(GF_FIELDS.fieldIdMap[gfFieldId]) {
            var field = GF_FIELDS[GF_FIELDS.fieldIdMap[gfFieldId]];
            var currValue = jQuery(`#input_${gfFormId}_${gfFieldId}`).val();

            // Apenas disparar se houver alteração de valor (evitar eventos duplicados (keyup+onchange) pelo GravityForms)
            if(field.prevValue !== currValue) {
                field.prevValue = currValue;
                if(field.onChange) {
                    field.onChange(gfElem, gfFormId, gfFieldId);
                }
            }
        }

        return;
    }

    function onInputChangeMemberSearch( gfElem, gfFormId, gfFieldId ) {
        var serviceCategoryValue = jQuery(GF_FIELDS.SERVICE_CATEGORY.selector).val();
        var amountValue          = jQuery(GF_FIELDS.AMOUNT.selector).val();
        var locationValue        = jQuery(GF_FIELDS.LOCATION.selector).val();

        if(serviceCategoryValue && amountValue && locationValue) {
            console.log(`AJAX: calling with values {"ServiceCategory":"${serviceCategoryValue}", "Amount":"${amountValue}", "Location":"${locationValue}"}`);

            jQuery(GF_FIELDS.MEMBER_SEARCH_RESULTS.cardsPlaceholderSelector).hide();
            jQuery(GF_FIELDS.MEMBER_SEARCH_RESULTS.loaderSelector).show();

            GF_FIELDS.MEMBER_SEARCH_RESULTS.prevRequest = jQuery.post({
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                data: {
                    // Campo obrigatório WP
                    action: "single_counseling_search_members",

                    // Campos de pesquisa passados à action
                    service_category: serviceCategoryValue,
                    amount: amountValue,
                    location: locationValue
                }
            }).done(function(xml, status, jqXhr) {
                if(GF_FIELDS.MEMBER_SEARCH_RESULTS.prevRequest !== jqXhr) {
                    console.log("AJAX: call skipped");
                    return;
                }

                console.log("AJAX: call successful");
                jQuery(GF_FIELDS.MEMBER_SEARCH_RESULTS.cardsPlaceholderSelector).show().html(xml);
                jQuery(GF_FIELDS.MEMBER_SEARCH_RESULTS.loaderSelector).hide();
                jQuery(GF_FIELDS.MEMBER_SEARCH_RESULTS.cardsSelector).on("click", onClickMemberCard);

                // Se um membro tiver sido selecionado na pesquisa anterior, e aparecer na nova pesquisa, pré-selecioná-lo
                // (Workaround para casos de submissão com erro)
                var memberId = jQuery(GF_FIELDS.MEMBER_SELECTION.selector).val();
                if(memberId) {
                    jQuery(`${GF_FIELDS.MEMBER_SEARCH_RESULTS.cardsSelector}[data-member-id=${memberId}]`).click();
                }
            }).fail(function() {
                if(GF_FIELDS.MEMBER_SEARCH_RESULTS.prevRequest !== jqXhr) {
                    console.log("AJAX: call failure skipped");
                    return;
                }

                console.error("AJAX: call failed");
                jQuery(GF_FIELDS.MEMBER_SEARCH_RESULTS.loaderSelector).hide();
            });
        }
    }

    function onClickMemberCard(event) {
        event.preventDefault();

        // Identificar membro selecionado a partir do campo "member-id" no card
        var memberId = jQuery(event.currentTarget).data('member-id');

        // Pintar o card selecionado
        jQuery(GF_FIELDS.MEMBER_SEARCH_RESULTS.cardsSelector).removeClass('active');
        jQuery(event.currentTarget).addClass('active');

        // Atualizar campo escondido com ID do membro, e notificar o GravityForms
        jQuery(GF_FIELDS.MEMBER_SELECTION.selector).val(memberId);
        jQuery(GF_FIELDS.MEMBER_SELECTION.selector).trigger('change');
    }

    // REFATURAÇÃO PARA REENCAMINHAMENTO E RECOMENDAÇÃO.

    let cl_id_campo_origem = '<?php echo $cl_sr_type_origin_id_field;?>'
    let cl_id_campo_Origin_SR = '<?php echo $cl_sr_origin_sr_id_of_field;?>'
    let cl_id_campo_PPC_Fixo_SR = '<?php echo $cl_input_sr_fixed_ppc_value_id_field;?>'
    let $cl_origin = document.querySelector('input[name="input_'+cl_id_campo_Origin_SR+'"]');
    let sr_type_origin = document.querySelector('input[name="input_'+cl_id_campo_origem +'"]');

    $cl_origin.value = '<?php echo $cl_rid; ?>';
    sr_type_origin.value = '<?php echo $cl_sr_type_origin; ?>';

    var e;
    var  cl_care;
    var  cl_care2 = '';
    var orcamento;
    var cl_ini_echlon = '';
    var cl_fim_echlon = '';

    jQuery(document).ready(($) => {
        gform.addAction( 'gform_input_change', onInputChange );

        $(GF_FIELDS.MEMBER_SEARCH_RESULTS.selector)
            .appendTo(GF_FIELDS.MEMBER_SELECTION.resultsPlaceholderSelector);

        gform.addFilter('gform_datepicker_options_pre_init', function(optionsObj, formId, fieldId) {
            optionsObj.minDate = 0;
            return optionsObj;
        });

        <?php
        if ($USE_SR_SYSTEM_3STEP_PURCHASE) {
            ?>

            $('.valor_referencia input[type="text"]').val(0);

            <?php
        }

        if ($USE_SR_SYSTEM_LEAD_PURCHASE) {
            ?>
            function insertTooltips() {
                $(".gfield").each(function(index, element) {
                    var gfFieldId = (element.id || '').split('_')[2];
                    if(gfFieldId && $(`#tooltip_${gfFieldId}`).length !== 0) {
                        $(element)
                            .find('label')
                            .append('<div class=gfield_label><button class="info-balloon">i</button>')           //Coloca os "i's" ao lado dos campos.
                            .find('button')
                            .click(function(event) { event.preventDefault(); $(`#tooltip_${gfFieldId}`).iziModal('open'); })    //Chama o iziModal onClick
                    }
                });
                return;
            }

            insertTooltips();

            // Campos onde se insere o valor de Refrência, Mínimo e Máximo. 
            var currencySymbolHtml = `<span class="curr_symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>`;
            $('.valor_referencia .ginput_container_text').append(currencySymbolHtml);
            $('.minimo .ginput_container_text').append(currencySymbolHtml);
            $('.maximo .ginput_container_text').append(currencySymbolHtml);

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

            // Workaround: Fazer uma nova pesquisa em caso de submissão com erro de modo a apresentar logo os resultados
            $(GF_FIELDS.AMOUNT.selector).trigger('change');
            <?php
        }
        ?>
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

    .service-category-member-selection input {
        display: none;
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

    .gfield_description:not(.gfield_validation_message) {
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

    <?php
        if ($USE_SR_SYSTEM_LEAD_PURCHASE) {
            ?>
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
            <?php
        }
    ?>
</style>

<?php
get_footer();
?>
