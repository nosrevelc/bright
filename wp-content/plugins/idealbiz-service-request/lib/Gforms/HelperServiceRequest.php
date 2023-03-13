<?php
/**
 * Gravity form processing helper class.
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Gforms;

use WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Email\EmailTemplate;


class HelperServiceRequest {

    public function register_hooks() {
        add_filter( 'gform_post_data', array( $this, 'create_service_request' ), 10, 3 );
        add_action( 'gform_after_create_post', array( $this, 'save_service_request_data' ), 10, 3 );
        add_action( 'gform_after_create_post', array( $this, 'create_message_thread' ), 20, 3 );
    }

    /**
     * Creates a new service request on form submission.
     *
     * @param array $post_data
     * @param array $form
     * @param array $entry
     * @return array $post_data
     * @since 1.0.0
     * @author Telmo Teixeira <telmo@widgilabs.com>
     */
    public function create_service_request( $post_data, $form, $entry ) {
        $cl_sr_origin_sr_id_of_field = get_field('sr_origin_sr_id_of_field', 'options');

        if(($entry[$cl_sr_origin_sr_id_of_field])== "") {
            if ( $post_data['post_title'] !== 'service_request' ) {
                return $post_data;
            }
            $post_data['post_type'] = 'service_request';

            foreach ( $form['fields'] as $field ) {
                if ( $field->type === 'custom_taxonomy' && $field->idealbizCustomTaxonomy === 'service_cat' && $field->visibility === 'visible' ) {
                    $service_cat_term = get_term_by( 'id', $entry[ $field->id ], 'service_cat' );
                    if ( $service_cat_term ) {
                        $post_data['post_title'] = $service_cat_term->name;
                    }
                }
            }

            $this->calculate_service_request_fields($post_data, $form, $entry);

            return $post_data;
        }
    }

    function calculate_service_request_fields( &$post_data, $form, $entry ) {
        $form_field_ids = array(
            'member_selection' => -1,
            'amount'           => -1
        );

        // Procurar o campo onde está guardado o ID do membro selecionado. Usamos Classes CSS configuradas nos Fields.
        foreach ( $form['fields'] as $field ) {
            if     ( str_contains( $field->cssClass, 'service-category-member-selection' ) ) {
                $form_field_ids['member_selection'] = $field->id;
            }
            elseif ( str_contains( $field->cssClass, 'valor_referencia' ) ) {
                $form_field_ids['amount'] = $field->id;
            }
        }

        // Buscar valores preenchidos no formulário
        $member_id       = $entry[$form_field_ids['member_selection']];
        $reference_value = $entry[$form_field_ids['amount']];


        // Buscar informação do membro para uso no cálculo
        $member_meta = array(
            'fixed_ppc'       =>  '0',
            'fixed_ppc_value' => 0.0,
            'idb_tax'         => 0.0,
            'echelon_competency_factor' => array()
        );
        foreach ( $member_meta as $f ) {
            $member_meta[$f] = get_field($f, $member_id);
        }


        // Campos que iremos calcular para o Service Request
        $sr_meta = array(
            'reference_value'    => $reference_value,  // Montante envolvido no negócio
            'sr_fixed_ppc_value' => 0.0,               // Comissão paga entre membros
            'rs_comission'       => 0.0                // Comissão paga à plataforma (IDB Tax)
        );

        // Buscar definição ACF do Service Request
        $sr_acf_parent = acf_get_field_groups( array('post_type' => 'service_request') )[0]['key'];
        $sr_acf_fields = acf_get_fields($sr_acf_parent);

        // echo "<div><p>member_meta 2</p>";
        // echo var_dump($member_meta);
        // echo "</div><div><p>sr_acf_parent</p>";
        // echo var_dump($sr_acf_parent);
        // echo "</div><div><p>sr_acf_fields</p>";
        // echo var_dump($sr_acf_fields);
        // echo "</div>";


        // Cálculo de preços

        if( $member_meta['fixed_ppc'] == '1' ) {
            // Membro usa taxa fixa

            $sr_meta['sr_fixed_ppc_value'] = $member_meta['fixed_ppc_value'];
            $sr_meta['rs_comission']       = $member_meta['fixed_ppc_value'] * $member_meta['idb_tax'];
        } else {
            // Membro usa taxas variáveis por escalão

            foreach ( $member_meta['echelon_competency_factor'] as $e ) {
                if ( $e['begin_echelon'] <= $reference_value && $reference_value <= $e['finish_echelon'] ) {
                    $sr_value = $reference_value * $e['percentage'];

                    $sr_meta['sr_fixed_ppc_value'] = $sr_value;
                    $sr_meta['rs_comission']       = $sr_value * $idb_tax;

                    break;
                }
            }
        }


        // Colocar valores calculados no Service Request


        if ( ! isset($post_data['meta_input']) ) {
            $post_data['meta_input'] = array();
        }

        foreach ( $sr_meta as $key => $value ) {
            if( ! isset($post_data['meta_input'][$key]) ) {
                $acf_key = '';

                // Buscar chave ACF para associar corretamente o campo
                foreach ( $sr_acf_fields as $f ) {
                    if ( $f['name'] == $key ) {
                        $acf_key = $f['key'];
                        break;
                    }
                }

                // https://support.advancedcustomfields.com/forums/topic/meta_input-wp_insert_post-acf-gallery/
                $post_data['meta_input']["{$key}"]  = $value;
                $post_data['meta_input']["_{$key}"] = $acf_key;
            }
        }

        echo "<div><p>post_data </p>";
        echo var_dump($post_data);
        echo "</div>";

        return $post_data;
    }

    public function gt($toTranslate, $domain= ''){
        global $wpdb;

        $translations = $wpdb->get_results( 
            "SELECT *
            FROM $wpdb->postmeta
            WHERE meta_key='_pll_strings_translations'"
        );

        foreach ( $translations as $t ) {
            $posts_arr= $wpdb->get_results( 
                "SELECT post_title
                FROM $wpdb->posts
                WHERE ID=$t->post_id");
                $langterm = str_replace('polylang_mo_','',$posts_arr[0]->post_title
            );
            $lang_test= $wpdb->get_results(
                "SELECT slug
                FROM $wpdb->terms
                WHERE term_id=$langterm"
            );
            $lang = $lang_test[0]->slug;

            $test= 'en';
            if(pll_current_language()){
                $test = pll_current_language();
            }
            if(isset($_GET['lang'])){
                $test = $_GET['lang'];
            }

            if($test==$lang){
                $strings = maybe_unserialize( $t->meta_value );
                foreach($strings as $k => $str){
                    if($str[0]==$toTranslate){
                        return $str[1];
                    }
                }
            }
        }
        return $toTranslate;
    }

    /**
     * Used to update the custom fields for the service request, with the form data.
     *
     * @param int $post_id ID for the newly created service request.
     * @param array $entry
     * @param array $form
     * @return void
     * @since 1.0.0
     * @author Telmo Teixeira <telmo@widgilabs.com>
     */
    public function save_service_request_data( $post_id, $entry, $form ) {
        $cl_sr_origin_sr_id_of_field = get_field('sr_origin_sr_id_of_field', 'options');

        if($entry[$cl_sr_origin_sr_id_of_field] !=""){
            $post_id = $entry[$cl_sr_origin_sr_id_of_field];
        }

        //error_log(print_r($entry,true));

        /*
            [id] => 1332
            [status] => active
            [form_id] => 12
            [ip] => 95.92.116.156
            [source_url] => https://idealbiz.eu/pt/pt/counseling/geral/
            [currency] => EUR
            [post_id] => 86654
            [date_created] => 2023-01-27 09:36:10
            [date_updated] => 2023-01-27 09:36:10
            [is_starred] => 0
            [is_read] => 0
            [user_agent] => Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36
            [payment_status] =>
            [payment_date] =>
            [payment_amount] =>
            [payment_method] =>
            [transaction_id] =>
            [is_fulfilled] =>
            [created_by] => 3126
            [transaction_type] =>
            [10] => service_request
            [6] =>
            [1.2] =>
            [1.3] => CLEVERSON
            [1.4] =>
            [1.6] => VIEIRA
            [1.8] =>
            [2] => cleverson.vieira@idealbiz.io
            [3] => 999999999
            [9] => 3073
            [4] => Teste de registo dos parametros.
            [24] => 3.5
            [25] =>
            [15] =>
            [17] =>
            [22] => 140
            [23] =>
            [13] => 2023-01-28
            [12] => 83203
            [27] => normal_service
            [28] => 81.90
            [29] => 30  //Parametro 1
            [30] => 110  // Parametro 2
        */

        $cl_sr_type_origin_id_field = get_field('sr_type_origin_id_field', 'options');
        //$cl_input_sr_fixed_ppc_value_id_field = get_field('sr_input_sr_fixed_ppc_value_id_field', 'options');
        $cl_comp_par1 = $entry[get_field('sr_company_parameter_1', 'options')];
        $cl_comp_par2 = $entry[get_field('sr_company_parameter_2', 'options')];

        $cl_sr_type_origin = $entry[$cl_sr_type_origin_id_field];
        //$cl_sr_ppc_fixed = $entry[$cl_input_sr_fixed_ppc_value_id_field];

        $post = get_post( $post_id );
        global $wpdb;

        if ( $post->post_type !== 'service_request' ) {
            return;
        }

        //NPMM - Vardampu abaixo exibe no momento da gração todos os dados capturado no form.
        //var_dump($entry);
        update_field( 'form_registry_id', $entry['id'], $post_id );
        update_field( 'rs_id_request_type' ,$post_id, $post_id );
        update_field( 'sr_type_origin' ,$cl_sr_type_origin, $post_id );
        //update_field( 'sr_fixed_ppc_value' ,$cl_sr_ppc_fixed, $post_id );
        update_field( 'sr_company_par_1' ,$cl_comp_par1, $post_id );
        update_field( 'sr_company_par_2' ,$cl_comp_par2, $post_id );

        if($cl_sr_type_origin==='recommende_service' || $cl_sr_type_origin==='normal_service'){			
            $cl_idExpert= $entry[12];
            $user= get_user_by( 'email', get_field('expert_email',$cl_idExpert) );
            $userId = $user->ID;
            //NPMM - Campos a Atualizar caso seja uma nova recomendação
            update_field( 'consultant', $userId , $post_id );
            $cl_Criador = $entry["created_by"];
            update_field( 'customer', $cl_Criador , $post_id );
        }

        foreach ( $form['fields'] as $field ) {
            error_log(print_r($field,true));

            if ( $field->type === 'custom_taxonomy' && $field->idealbizCustomTaxonomy === 'service_cat' ) {
                if ( ! empty( $entry[ $field->id ] ) ) {
                    update_field( 'request_type', $entry[ $field->id ], $post_id );
                }
            }

            // Set default value for customer.
            if($field->id == 2){
                $userexists = get_user_by( 'email', $entry[ $field->id ] );
                if(!$userexists){
                    $pass = wp_generate_password( 8, false );
                    $newuser = wp_create_user($entry[ $field->id ], $pass, $entry[ $field->id ]);
                    if(is_wp_error($newuser)){
                        $error = $newuser->get_error_message();
                    }else{
                        $nuser = get_user_by('id', $newuser);

                        $subject = $this->gt( '[idealBiz] You have a new account in our website', 'irs' ) . ' ' . $service_request_post->post_title;
                        $x=0;
                        foreach ($_POST as $a){
                            if($x==10){
                                //get expert post_type
                                $q_expert_post="
                                SELECT {$wpdb->posts}.*
                                FROM {$wpdb->posts}  
                                INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
                                INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
                                WHERE 1=1  
                                AND mt1.meta_key = 'expert_email' 
                                AND mt1.meta_value = '".$a."'
                                GROUP BY {$wpdb->posts}.ID
                                ";
                                //echo  $q_expert_post;
                                $result_expert_post = $wpdb->get_results($q_expert_post, ARRAY_A);
                                foreach($result_expert_post as $q_expert_post => $expert_post){
                                    $expert_id = $expert_post['ID'];
                                }
                                //Desabilitei esta variavel pois esvá envaindo conteudo estranho feito no teste 
                                /* $consultant_name = get_the_title($expert_id); */
                                $consultant_name = '';
                            }
                            $x++;
                        }

                        // Estava a criar dados para enviar por email para user não registado porem no dia 07/07/21 as 17:30 Sr. Alberto mandou suspender e passsar para a criação de escalões sem valor orçamento
                        $body    = '<div style="text-align:left;"<br/>'.$this->gt('You received this email because your service request to','irs').' '.get_the_title($post_id).', '.$this->gt('was nominated for','irs').' '.$consultant_name.'.
                                    <br/><br/>'.$this->gt('Login:','irs').' '.$entry[ $field->id ].'<br/>'.$this->gt('Password:','irs').' '.$pass.'
                                    <br/><br/>'.$this->gt('You can follow and contact your expert in "My Account" area.','irs').'<br/>'.$this->gt('You have been assigned a user account on our portal to access','irs').' <u>'.$this->gt('click','irs').'</u> '.$this->gt('on the link below, or through this link:','irs').' <a href="'.get_site_url().'">'.get_site_url().'</a> '.$this->gt('entering the user information assigned to it.','irs').'<br/></div>'.$this->gt('Thank you,').'<br/><br/>';

                        $body_template = EmailTemplate::get_email_body( wpautop( $body ) );
                        $body_template = str_replace( '%%HEAD_MESSAGE%%', $this->gt('Your New Account Details','irs'), $body_template );
                        $body_template = str_replace( '%%USER_COMPLIMENT%%', '', $body_template );
                        $headers = array( 'Content-Type: text/html; charset=UTF-8' ); 
                        // -> Cliente
                        wp_mail( $entry[ $field->id ], $subject, $body_template, $headers );

                    }
                }else{
                    $nuser = $userexists; 
                }
            }
        }

        $x=0;
        $e_email = '';
        $e_name = '';


        $rv=0; // valor de refenrecia
        $min=0; // minimo
        $max=0; // maximo

        foreach ($_POST as $a){
            /* echo 'Clico x - '.$x.' Valor de A -'.$a.'<br>'; */
            if ($x==0){
                $i=0;
                foreach ($_POST as $b){
                    if($i==15){
                        //echo 'Clico i  - '.$i.' Valor de b -'.$b.'<br>';
                        $user = get_user_by( 'email', get_field('expert_email',$b) );
                        $idb_tax = get_field('idb_tax',$b);
                        $userId = $user->ID;
                        $e_email = $user->user_email;
                        $e_name = $user->first_name . ' ' . $user->last_name;
                        //update_field( 'consultant', $userId , $post_id );
                        //echo ('fator_de_compet'.$idb_tax);
                    }
                    $i++;
                }
            }

            //Alteração Cleverson  criar dados de contado para news service request.
            $current_user = wp_get_current_user();
            if (!session_id()) {
                session_start();
            }

            $_SESSION['referral_system']=
            '<h3>
                - '.$current_user->first_name . ' ' . $current_user->last_name.' ('.$current_user->user_email.')
                </h3>'.$this->gt('refered the expert:').'


            <h3>- '.$e_name.' ('.$e_email.')</h3><br/>
            <h5>'.$this->gt('To this Service Request:').'</h5>';
            //Terminna aqui

            if($x==14){
                $dob_str = $a;
                $date = \DateTime::createFromFormat('d/m/Y', $dob_str);
                $date = $date->format('Ymd');
                update_field('delivery_date', $date, $post_id);

            }
            if($x==5){
                $email_user = update_field( 'service_request_email', $a , $post_id );
                //echo '???????email????????'.$a;
            }
            if($x==6){
                update_field( 'service_request_phone', $a , $post_id );
            }
            if($x==9){
                update_field( 'location', $a , $post_id );
            }

            if($_SESSION['membro']){
                /* echo ('Passei no 10-> '.$post_id. 'referenciado xxx'.$_SESSION['rid']); */
                if($_SESSION['membro']){
                    if($cl_sr_type_origin==='forward_service'){
                        update_field( 'origin_sr', $_SESSION['membro'],$_SESSION['rid'] );
                        update_field( 'new_sr' , $post_id, $_SESSION['rid'] );
                        update_field('state',__('Other Referenced Member'),$_SESSION['rid']);
                        update_field( 'referral', $e_email , $post_id );
                        update_field( 'is_referral' ,1 , $_SESSION['rid'] );
                    }

                    $current_user = wp_get_current_user();
                    if (!session_id()) {
                        session_start();
                    }

                    $_SESSION['referral_system']=
                                '<h3>
                                    - '.$current_user->first_name . ' ' . $current_user->last_name.' ('.$current_user->user_email.')
                                    </h3>'.$this->gt('refered the expert:').'
                                <h3>- '.$e_name.' ('.$e_email.')</h3><br/>
                                <h5>'.$this->gt('To this Service Request:').'</h5>';

                    $_SESSION['referral_system_client']=
                                '<h3>
                                    '.$this->gt('A Service Request was created for you').'
                                    </h3>
                                    '.$this->gt('refered by expert:').'
                                    <h4>
                                    - '.$current_user->first_name . ' ' . $current_user->last_name.' ('.$current_user->user_email.')
                                    </h4>'.$this->gt('to expert:').'
                                    <h4>- '.$e_name.' ('.$e_email.')</h4><br/>

                                    <h5>'.$this->gt('Service Request details:').'</h5>';
                }
                /* var_dump('REFERAL SISTEM'.$_SESSION['referral_system']);
                var_dump('REFERAL SISTEM CLIENTE'.$_SESSION['referral_system_client']) */;
            }

            /* if($x==11){ */
                /* if($_SESSION['membro']){
                    echo ('Passei no 11 é o $a-> '.$a);

                update_field( 'origin_sr', $_SESSION['membro'], $_SESSION['rid'] );
                update_field( 'new_sr' , $post_id, $_SESSION['rid'] );
                update_field('state',__('Other Referenced Member'),$_SESSION['rid']);

                $cl_member_id = get_field('consultant', $a,'');
                $cl_member_data = get_userdata($cl_member_id);
                $cl_member_f_name = $cl_member_data->first_name;
                $cl_member_l_name = $cl_member_data->last_name;

            }  */

            if($x==7){
                update_field( 'message', $a , $post_id );
            }

            if(WEBSITE_SYSTEM == '1'){
                if($x==12){ //valor de referencia
                    //update_field( 'reference_value', $a , $post_id );

                } 
                if($x==13){ //minimo
                    update_field( 'budget_min', $a , $post_id );

                } 
                if($x==8){ //máximo
                    $max= $a;
                    update_field( 'budget_max', $a , $post_id );

                } 
            }

            $x++;
        }

        if(WEBSITE_SYSTEM == '1'){
            //inicio enviar email a quem solicita o serviço

            if (get_field('reference_value', $post_id)){
            $current_user = wp_get_current_user();
            $cl_prev_ref = get_field('origin_sr', $post_id);
            $cl_msg = get_field('message', $post_id);
            $cl_member_id = get_field('consultant', $post_id,'');
            $cl_member_data = get_userdata($cl_member_id);
            $cl_member_f_name = $cl_member_data->first_name;
            $cl_member_l_name = $cl_member_data->last_name;
            $cl_valor_referencia = wc_price(get_field('reference_value', $post_id));
            $cl_date = cl_formatDateByWordpress(get_field('delivery_date', $post_id)) ;
            $new_user_email = $current_user->user_email;
            $cl_servico = get_the_title($post_id);
            $service_requests_url = get_permalink(get_option('woocommerce_myaccount_page_id')) . 'service_request'.'/?home=1';

            $cl_nameCurrenteUser = $current_user->display_name;
            $to = $new_user_email;
            $cl_headers = array( 'Content-Type: text/html; charset=UTF-8' ); 

            $subject = __('[idealBiz] You have a new service request in your account', 'idealbiz-service-request');
            $hi = $subject;		
            $user_compliment  = __('Hello', 'idealbiz-service-request');
            $user_compliment .= ' ' . $cl_nameCurrenteUser. ',';	
            $user_compliment .= '<br /><br />'.__('_str Your order has been successfully submitted');
            $user_compliment .= '<br /><br /><b>'.__('Details data').':</b>';
            $user_compliment .= '<br />'.__('Reference:').' #'.$post_id;
            $user_compliment .= '<br />' . __('Conclusion Date: ') .' '. $cl_date;
            $user_compliment .= '<br />' . __('Reference Value:') .' '. $cl_valor_referencia;
            $user_compliment .= '<br />' . __('Member:') .' '. $cl_member_f_name.' '.$cl_member_l_name;
            $user_compliment .='<br /><br /><b>' . __('Message:').'</b><br/>'. $cl_msg.'<br/><br />';
            $user_compliment .= sprintf(
                '<a href="%1$s" title="%2$s">%3$s</a>',
            /** %1$s -> */	$service_requests_url,
            /** %2$s -> */	__('_str View Request', 'idealbiz-service-request'),
            /** %3$s -> */	__('_str View Request', 'idealbiz-service-request')
            );
            $user_compliment .='<br /><br />' . __('_str Thank you for using iDealBiz','idealbiz').'.'.'<br/><br />';
            $user_compliment .= '<br/><br/>'.__('The iDealBiz Team','idealbizio');
            $user_compliment .= '<br/><span style="color:#ffffff;font-size:0.5em;">HSR05</span>';

            $emailHtml  = get_email_header();
            $emailHtml .= get_email_intro('', $user_compliment, $hi);
            $emailHtml .= get_email_footer();
            wp_mail($to,$subject,$emailHtml,$cl_headers);


            $subject2 = __('_str Service Proposal Request','idealbiz');			
            $to2 = get_field('costumer_care_email', 'option');
            $hi2 = __('_str Service Proposal Request','idealbiz').' '.$cl_servico;

            $emailHtml  = get_email_header();
            $emailHtml .= get_email_intro('', $user_compliment, $hi2);
            $emailHtml .= get_email_footer();
            //wp_mail($to2,$subject2,$emailHtml,$cl_headers);
        }

        //fim enviar email a quem solicita o serviço

        //Alteção Cleverson - Buscar email do costumercare para informar do novo serviço do expert.
        $customer_care = get_field('costumer_care_email', 'option');

        $cl_reference_value = wc_price(get_field('reference_value',$post_id,''));

        $cl_servico_id = get_field('request_type',$post_id,'');
        //Cacula valor do serviço
        $cl_orcamento = get_field('budget_max',$post_id,'');


        $cl_sr_fixed_ppc_value = get_field('sr_fixed_ppc_value',$post_id,'');

        if ($cl_sr_fixed_ppc_value == Null){
            $cl_v_para_member = ((Int)$cl_orcamento*(int)$idb_tax)/100;
        }else{
            $cl_v_para_member = $cl_sr_fixed_ppc_value;
        }
        $cl_v_para_member = wc_price($cl_v_para_member);

        function get_product_category_by_id( $category_id ) {
            $term = get_term_by( 'id', $category_id, 'service_cat', 'ARRAY_A' );
            return $term['name'];
        }
        $cl_servico = get_product_category_by_id( $cl_servico_id );


        $cl_new_member_id = get_field('consultant', $post_id,'');
        $cl_new_member_data = get_userdata($cl_new_member_id);

        if ( ! add_post_meta( $post_id, 'website_system', '1', true ) ) { 
            update_post_meta ( $post_id, 'website_system', '1' );
        }
        //Não estava Funcionando, 12/07/21
        /* $userexists = get_user_by( 'email', $entry[$field->id]); */
        $userexists = $e_email;
        if($userexists){
            if (!get_field('is_referral', $post_id)){
                //CHECANDO MODO DA LEAD DO MEMBRO.
                $consultant = get_field( 'consultant', $post_id );

                $current_user = $consultant;
                $id_expert = isExpert($current_user->ID); 

                $cl_rb_pay_lead_mode = get_field('sr_pay_lead_mode',$id_expert[0]->ID);

                if($cl_rb_pay_lead_mode === NULL){
                    $cl_rb_pay_lead_mode = ['value'=>'sr_pay_before','label'=>'Pay Before'];

                }
                $cl_rb_pay_lead_mode_value = $cl_rb_pay_lead_mode['value'];


                if($cl_rb_pay_lead_mode === NULL){
                    $cl_rb_pay_lead_mode = ['value'=>'sr_pay_before','label'=>'Pay Before'];
                }

                if($cl_rb_pay_lead_mode['value']==='sr_pay_before'){
                    $mode = __('_str Pay Before','idealbiz');
                }
                if($cl_rb_pay_lead_mode['value']==='sr_pay_later'){
                    $mode = __('_str Pay Later','idealbiz');
                }

                if($cl_rb_pay_lead_mode['value']==='sr_not_pay'){
                    $mode = __('_str No Pay','idealbiz');
                }

                if ($cl_rb_pay_lead_mode_value !='sr_not_pay'){
                    // to expert
                    // Contact Lead Purchase Codigo do email - 
                    $cl_date = cl_formatDateByWordpress(get_field('delivery_date', $post_id));

                    $cl_dateSendThis = get_the_date( 'd M Y', $post_id);
                    $subject = pll__('New service request in your account').' '.$cl_servico;
                    $hi = $subject;
                    $to = $e_email.','.$customer_care; //Sr Alberto pediu para retirar a copia enviada para o Custumercare dia 30/06/2021 - Ativado dia 28/03/2022 Junto com Sofia.
                    $headers = array('Content-Type: text/html; charset=UTF-8');

                    $message  = __('Hello').' ' .' {{expert}}';
                    $message .= '<br />'.'<br />'.__('It received a new Service Request for its Area of Expertise').' '.$cl_servico.' ';
                    $message .= '<br /><br /><b>'.__('Details data').':</b>';
                    $message .= '<br />'.__('Reference:').' #'.$post_id;
                    $message .= '<br />' . __('Conclusion Date: ') .' '. $cl_date;
                    $message .=  __('with the Reference Value').' '.$cl_reference_value;
                    $message .= '<br />'.__('Lead purchase amount').':'.$cl_v_para_member;
                    $message .= '<br/>'.__('_str Your profile','idealbiz').' '.__('_str Mode','idealbiz').' '.$mode;
                    $message .= '<br /><br />'.__('To accept, decline or reference the Service Request go to your Orders Dashboard at:{{service_requests_page}}');
                    $message .= '<br /><br />'.__('Thank you');
                    $message .= '<br />'.__('The iDealBiz Team');
                    $message .= '<br/><span style="color:#ffffff;font-size:0.5em;">HSR01</span>';
                }

                if ($cl_rb_pay_lead_mode_value =='sr_not_pay'){
                    $subject = pll__('New service request in your account').' '.$cl_servico;
                    $hi = $subject;
                    $to = $e_email.','.$customer_care; //Sr Alberto pediu para retirar a copia enviada para o Custumercare dia 30/06/2021 - Ativado dia 28/03/2022 Junto com Sofia.
                    $headers = array('Content-Type: text/html; charset=UTF-8');

                    $message  = __('Hello').' ' .' {{expert}}';
                    $message .= '<br/>'.__('_str Your profile','idealbiz').' '.__('_str Mode','idealbiz').' '.$mode;
                    $message .= '<br />'.'<br />'.__('It received a new Service Request for its Area of Expertise').' '.$cl_servico;
                    $message .= '<br />'.__('Reference:').' #'.$post_id;
                    $message .= '<br />'.__('_str To accept, decline or reference the Service Request go to your Orders {{service_requests_page}}');

                    $message .= '<br /><br />'.__('Thank you');
                    $message .= '<br />'.__('The iDealBiz Team');
                    $message .= '<br/><span style="color:#ffffff;font-size:0.5em;">HSR02</span>';
                }
            }

            if (get_field('is_referral', $post_id)){
                if($_SESSION['membro']){
                    //BUSCAR QUEM REFERENCIOU
                    $user_id = get_current_user_id(); 
                    $user_info = get_userdata($user_id);
                    $mailadresje = $user_info->user_email;
                    $cl_display_name = $user_info->display_name;
                    //FIM BUSCAR QUEM REFERENCIOU

                    // to expert
                    // Contact Lead Purchase Codigo do email - 
                    $subject = pll__('_str New reference service request').' '.$cl_servico;
                    $hi = $subject;
                    $to = $e_email.','.$customer_care; 
                    $headers = array('Content-Type: text/html; charset=UTF-8');

                    $message  = __('Hello').' ' .' {{expert}}';
                    $message .= '<br/>'.__('_str Your profile','idealbiz').' '.__('_str Mode','idealbiz').' '.$mode;
                    $message .= '<br />'.'<br />'.__('It received a new Service Request for its Area of Expertise').' '.$cl_servico.' '. __('with the Reference Value').' '.$cl_reference_value;
                    $message .= '<br />'.__('Lead purchase amount').':'.$cl_v_para_member;
                    $message .= '<br /><br />'.__('_str You have been referred by the member').' : '.$cl_display_name;
                    $message .= '<br />'.__('_str Email of the member who referred you').' : '.'<a href=mailto:"'.$mailadresje.'">'.$mailadresje.'</a>';
                    $message .= '<br /><br />'.__('To accept, decline or reference the Service Request go to your Orders Dashboard at:{{service_requests_page}}');
                    $message .= '<br /><br />'.__('Thank you');
                    $message .= '<br />'.__('The iDealBiz Team');
                    $message .= '<br/><span style="color:#ffffff;font-size:0.5em;">HSR03</span>';
                }else{
                    // to expert
                    // Contact Lead Purchase Codigo do email - 
                    $subject =__('_str Received a new referral from the Member').' '.$cl_member_f_name.' '.$cl_member_l_name;
                    $hi = __('New referencing between members');
                    $to = $e_email.','.$customer_care; //Sr Alberto pediu para retirar a copia enviada para o Custumercare dia 30/06/2021
                    $headers = array('Content-Type: text/html; charset=UTF-8');

                    $message  = __('_str Hello').' ' .' {{expert}}';
                    $message .= '<br />'.'<br />'.__('_str Received a Member Service Request Reference for its Area of Expertise').' '.$cl_servico.' '. __('with the Reference Value').' '.$cl_reference_value.'.<br />'.__('made by the Member').' <b><i>'.$cl_member_f_name.' '.$cl_member_l_name.'</b></i>.';
                    $message .= '<br />'.__('_str Lead purchase amount').':'.$cl_v_para_member;
                    $message .= '<br /><br />'.__('_str To accept, decline or reference the Service Request go to your Orders Dashboard at:{{service_requests_page}}');
                    $message .= '<br /><br />'.__('_str Thank you');
                    $message .= '<br />'.__('_str The iDealBiz Team');
                    $message .= '<br/><span style="color:#ffffff;font-size:0.5em;">HSR04</span>';
                }
            }

            $message = str_replace('{{expert}}', $e_name ,$message);
            $message = str_replace('{{budget_max}}', $max.' '.get_woocommerce_currency_symbol() ,$message);
            $message = str_replace('{{service_requests_page}}', '<a href="'.wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'/?home=1">'.__('My Service Requests','idealbiz').'</a>' ,$message);

            $emailHtml  = get_email_header();
            $emailHtml .= get_email_intro('', $message, $hi);
            $emailHtml .= get_email_footer();

            wp_mail($to,$subject,$emailHtml,$headers);

            // NPMM - REDIRECT TO FORM QUALIFICATION LEAD

            $cl_id_member_chosen= $entry[12];
            $cl_emailMember= get_field('expert_email',$cl_id_member_chosen);
            $cl_page = get_the_guid(getIdByTemplate('page-qualificatio-lead.php'));
            $cl_service_request = $post_id;
            $cl_myservicedasboard_http = wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?home=1';

            $cl_myservicedasboard = str_replace('https://','',$cl_myservicedasboard_http);

            $cl_urlQualificatioLead = $cl_page.'&membro_e_mail='.$cl_emailMember .'&id_service_request='.$cl_service_request.'&id_member_chosen='.$cl_id_member_chosen.'&myservicedasboard='.$cl_myservicedasboard;

            //var_dump($cl_urlQualificatioLead);
            header('Location: '.$cl_urlQualificatioLead);

            //ATENÇÃO AS SEÇÇÕES ESTÃO SENDO DESTRUDAS NO FICHEIRO ServiceRequest.php
            /* session_start();
            unset($_SESSION['membro']);
            unset($_SESSION['rid']);
            unset($_SESSION['sr']);
            unset($_SESSION['email_referenciado']); */

            update_field( 'idb_competency_factor_percentage', $fc , $post_id );
        }
        }else{
            if ( ! add_post_meta( $post_id, 'website_system', '0', true ) ) { 
                update_post_meta ( $post_id, 'website_system', '0' );
            }
        }

        // Set default value for progress.
        update_field( 'state', 'Pending Expert Acceptance', $post_id );

        //update_field( 'customer', $nuser, $post_id );
    }

    /**
     * Creates the first message to the service request message thread.
     *
     * @param int $post_id ID for the newly created service request.
     * @param array $entry
     * @param array $form
     * @return void
     * @since 1.0.0
     * @author Telmo Teixeira <telmo@widgilabs.com>
     */
    public function create_message_thread( $post_id, $entry, $form ) {
        $post = get_post( $post_id );

        //var_dump('2');

        if ( $post->post_type !== 'service_request' ) {
            return;
        }

        $post_data = array(
            'post_type'   => 'service_message',
            'post_status' => 'publish',
            'post_title'  => time(),
            'post_author' => $post->post_author,
        );

        foreach ( $form['fields'] as $field ) {
            if ( $field->type === 'textarea' ) {
                if ( ! empty( $entry[ $field->id ] ) ) {
                    $post_data['post_content'] = wp_kses_post( $entry[ $field->id ] );
                }
            }
        }

        //$message_id = wp_insert_post( $post_data );

        if ( $message_id ) {
            update_field( 'service_request', $post_id, $message_id );
        }
    }
}