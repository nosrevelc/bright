<?php
/**
 * Plugin Name:       iDealBiz - Recommended Business
 * Plugin URI:        https://idealbiz.io
 * Description:       Add Recommended Business feature to platform.
 * Version:           1.0.0
 * Author:            Cleverson Vieira
 * Author URI:        https://idealbiz.io
 * Text Domain:       idealbiz-recommended-business
 */




defined('ABSPATH') or die('No entry');

$rb_id_form_reco_business = get_field('id_gform_recommended_business', 'option');





add_filter( 'gform_confirmation_'.$rb_id_form_reco_business, 'redirectQualifyOpportunuty', 10, 4 );


function redirectQualifyOpportunuty($confirmation, $form, $entry, $ajax){
            
            //	NPMM - REDIRECT TO FORM QUALIFICATION LEAD          
            $cl_id_member_chosen= $entry[20];
            $cl_emailMember= $entry[19];
            $cl_page = get_the_guid(getIdByTemplate('page-qualificatio-lead_oport.php'));
            $cl_id_Opportunity = $_SESSION['rb_post_id'];
            $cl_Opportunitydasboard =getLinkByTemplate('RecommendedBusiness.php').'?received=1';  
            $cl_urlQualificatioLead = $cl_page.'&membro_e_mail='.$cl_emailMember .'&id_Opportunity='.$cl_id_Opportunity.'&id_member_chosen='.$cl_id_member_chosen.'&myOpportunityDashboard='.$cl_Opportunitydasboard;
            $confirmation = array( 'redirect' => $cl_urlQualificatioLead );
            //wp_redirect($cl_urlQualificatioLead );

            $message = 'Debug Entrei no Else IF';

            /* echo "<div class='alert'>";
            echo "<script>alert('$message');</script>";
            echo "</div>";  */

            return $confirmation;

}

add_action( 'gform_after_submission_'.$rb_id_form_reco_business .'', 'get_values_after_submission', 11, 2 );

function get_values_after_submission( $entry, $form) {
   
    error_log(print_r($entry,true));
    
/*     [id] => 1178
    [status] => active
    [form_id] => 65
    [ip] => 95.92.116.156
    [source_url] => https://idealbiz.eu/pt/pt/listing/teste-de-anuncio-para-recomendacao/
    [currency] => EUR
    [post_id] => 85913
    [date_created] => 2022-09-22 10:40:15
    [date_updated] => 2022-09-22 10:40:15
    [is_starred] => 0
    [is_read] => 0
    [user_agent] => Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36
    [payment_status] => 
    [payment_date] => 
    [payment_amount] => 
    [payment_method] => 
    [transaction_id] => 
    [is_fulfilled] => 
    [created_by] => 3639
    [transaction_type] => 
    [25] => 85905
    [6] => Teste de Anuncio Para recomendação
    [10] => https://idealbiz.eu/pt/pt/listing/teste-de-anuncio-para-recomendacao/
    [24] => 1000
    [20] => 85897
    [21] => Titular Membro
    [19] => membro.titular@idealbiz.io
    [7] => 85899
    [23] => Recomenda Membro
    [4] => membro.recomenda@idealbiz.io
    [22] => percentage_value
    [12] => 2
    [27] => 20
    [26] => 18
    [13] => Cleverson Vieira
    [16] => 939302819
    [17] => cleverson.vieira@idealbiz.io
    [18] => MENSSAGEM TESTE INFORÇAO CAIXA DE TESTO */
    
    //NPMM - Vardampu abaixo exibe no momento da gração todos os dados capturado no form.
	//var_dump($entry);

        $coins_charge = cl_calcula_coins($entry[22], $entry[12], $entry[24]);
        $id_gform_recommended_business = get_field('id_gform_recommended_business', 'option');

        $costumer_care_email = get_field('costumer_care_email', 'options');
        if($entry['form_id'] == $id_gform_recommended_business){
            
            $title_concat = 'Draft-'.$entry['post_id'].'-'.$entry[6];
            $dados =([
                'ID' => '',
                'post_type' => 'recommended_business',
                'post_status' => 'draft',
                'post_title'=>$title_concat,
                'meta_input' =>[
                    'rb_currency'=>  $entry['currency'],
                    'rb_id_listing'=> $entry[25],
                    'rb_titlle_of_listing'=> $entry[6],
                    'rb_link_of_listing' => $entry[10],
                    'rb_id_owner_of_listing' =>$entry[20],
                    'rb_name_owner_of_listng' => $entry[21],
                    'rb_email_owner_of_listing' => $entry[19],
                    'rb_id_member_indicate' => $entry[7],
                    'rb_name_member_indicate' => $entry[23],
                    'rb_email_member_indicate'=> $entry[4],
                    'rb_comission_type' => $entry[22],
                    'rb_listing_price' => $entry[24],
                    'rb_comission_value' => $entry[12],
                    'rb_recommended_name'=> $entry[13],
                    'rb_recommended_phone' => $entry[16],
                    'rb_recommended_email' => $entry[17],
                    'rb_recommended_information' => $entry[18],
                    'rb_commission_calculated' => $entry[26],
                    'rb_gross_commission' => $entry[27],
                    'rb_value_charge_coins'=> $coins_charge,
                    
                ]

            ]);
            
            $rb_post_id=wp_insert_post($dados);

            $_SESSION = array();
            session_destroy();

            session_start();
            $_SESSION['rb_post_id'] = $rb_post_id;

            /*echo '<script type ="text/JavaScript">';  
            echo 'alert("Resdirect '.$_SESSION['rb_post_id'].'")';  
            echo '</script>';*/ 



            //NPMM - SESSÃO PARA PASSAR ID DA OPORTUNIDADE PARA FUNÇÃO redirectQualifyOpportunuty

            //var_dump($dados);
            $title_concat = $rb_post_id.'-'.$entry[6];
            $updated_post = array(
                'ID'            =>      $rb_post_id,
                'post_title'    =>      $title_concat,
                'post_status'   =>      'publish', // Now it's public
                'post_type'     =>      'recommended_business',
                    'meta_input' =>[
                    'rb_post_origin'=> $rb_post_id
                    ]
            );
            wp_update_post($updated_post);

        }

        $cl_symbol = get_woocommerce_currency_symbol($entry['currency']);

        //NPMM - Email vai para quem recebeu indicação para fazer o pagamento
        $customer_care = get_field('costumer_care_email', 'option');  //Customer care do sisterma
        // $customer_care = 'customercare.pt@idealbiz.io'.','.$entry[19]; // Envia também para o criador do anuncio
        //$customer_care = 'customercare.pt@idealbiz.io'; //Somente Customercare
        //$cl_membro = 'customercare.teste@idealbiz.io';  //TEMPORÁRIO COLOCAR EMAIL DO MEMBRO SOMENTE TESTE
        $cl_membro = $entry[19];
        $rb_id_porduct_coin = get_field('rb_id_porduct_coin', 'option');
        $checkout_url =  wc_get_checkout_url() . '?add-to-cart=' . $rb_id_porduct_coin.'&quantity='.$coins_charge.'&listing-recommended='.$rb_post_id;
        $to = $cl_membro;
        $subject = __('_str You Received Recomemded Business','idealbizio');
        $headers = array('Content-Type: text/html; charset=UTF-8');
    
        
        $hi = '<sapn style="font-size: 25px !important;">'.$subject.'</span>';
        $m = __('Hello','idealbizio').' '.$entry[21].'<br/><br/>';
        $m .= __('_str A new business recommendation has been created','idealbizio').'<br/><br/>';
        $m .= '<a href='.$entry[10].'>'.$entry[10].'</a><br/><br/>';
        $m .= __('_str Opportunity Proposal','idealbizio').': '.$rb_post_id.'<br/><br/>';
        $m .= __('_str Follow the link below to make the payment, and after confirmation of payment the contact will be available in the recommendation area in your dashboard','idealbizio').'.';
        $m .= '<br/><br/>';
        $m .= __('_str Information','idealbiz').'<br/><br/>';
        $m .= '<a href="'.$checkout_url.'">'.__('Buy Lead','idealbizio').' : '.$entry[27].$cl_symbol.'</a>'.'.'.'<br/><br/>';
        $m .= __('The iDealBiz Team','idealbizio');
        $m .= '<br/><span style="color:#ffffff;font-size:0.5em;">IRB01</span>';
        
    
    
        $emailHtml  = get_email_header();
        $emailHtml .= get_email_intro('', $m, $hi);
        $emailHtml .= get_email_footer();
        wp_mail($to,$subject,$emailHtml,$headers);



        //NPMM - Email que vai para Customercare do Pais informando que Houve uma Recomendação de negócio.
        // $customer_care = 'customercare.pt@idealbiz.io'.','.$entry[19]; // Envia também para o criador do anuncio
        //$customer_care = 'customercare.teste@idealbiz.io'; //Somente Customercare TESTE
        $customer_care = get_field('costumer_care_email', 'option');  //Customer care do sisterma
        $rb_id_porduct_coin = get_field('rb_id_porduct_coin', 'option');
        $checkout_url =  wc_get_checkout_url() . '?add-to-cart=' . $rb_id_porduct_coin.'&quantity='.$coins_charge.'&listing-recommended='.$rb_post_id;
        $to = $customer_care;
        $subject = __('_str A new recommendation has been created among members','idealbizio');
        $headers = array('Content-Type: text/html; charset=UTF-8');
    
        
        $hi = '<sapn style="font-size: 25px !important;">'.$subject.'</span>';
        $m = __('Hello','idealbizio').' '.__('_str Customer','idealbizio').'<br/><br/>';
        $m .= __('_str A new business recommendation has been created between members','idealbizio').'<br/><br/>';
        $m .= '<a href='.$entry[10].'>'.$entry[10].'</a><br/><br/>';
        $m .= '<b>'.__('_str Data of who made the recommendation','idealbizio').':</b><br/>';
        $m .= $entry[7].' →  '.$entry[23].' →  '.$entry[4].'<br/>';
        $m .= '<b>'.__('_str Data of those who received recommendation','idealbizio').':</b><br/>';
        $m .= $entry[20].' →  '.$entry[21].' →  '.$entry[19].'<br/><br/>';
        $m .= '<b>'.__('_str Recommendation data','idealbizio').':</b><br/>';
        $m .= '<b>'.__('_str Name','idealbizio').'</b>'.' → '.$entry[13].'<br/>';
        $m .= '<b>'.__('_str Phone','idealbizio').'</b>'.' → '.$entry[16].'<br/>';
        $m .= '<b>'.__('_str Email','idealbizio').'</b>'.' → '.$entry[17].'<br/>';
        $m .= '<b>'.__('_str Informatio','idealbizio').'</b>:<br/>'.$entry[18].'<br/>';
        $m .= '<br/><br/>'.__('The iDealBiz Team','idealbizio');
        $m .= '<br/><span style="color:#ffffff;font-size:0.5em;">IRB02</span>';

        $emailHtml  = get_email_header_recomemded();
        $emailHtml .= get_email_intro('', $m, $hi);
        $emailHtml .= get_email_footer();
        wp_mail($to,$subject,$emailHtml,$headers);


}




function cl_calcula_coins($typo_comiss ,$valor_comiss, $valor_base=null ){

    if($typo_comiss == 'Money Value'){ 
        $coins = ((int)$valor_comiss)*100;
        return $coins;
    }

    if($typo_comiss != 'Money Value'){
        $coins = ($valor_base * $valor_comiss);
        return $coins;
    }

}





?>