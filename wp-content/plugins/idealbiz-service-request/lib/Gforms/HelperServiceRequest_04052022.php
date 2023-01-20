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
		return $post_data;
	}


	public function gt($toTranslate, $domain= ''){


		global $wpdb;
		$translations = $wpdb->get_results( 
			"SELECT *
			FROM $wpdb->postmeta
			WHERE meta_key='_pll_strings_translations'");
		foreach ( $translations as $t ) 
		{
			$posts_arr= $wpdb->get_results( 
				"SELECT post_title
				FROM $wpdb->posts
				WHERE ID=$t->post_id");
				$langterm = str_replace('polylang_mo_','',$posts_arr[0]->post_title);

			$lang_test= $wpdb->get_results( 
				"SELECT slug
				FROM $wpdb->terms
				WHERE term_id=$langterm");
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
		$post = get_post( $post_id );
		global $wpdb;
		

		if ( $post->post_type !== 'service_request' ) {
			return;
		}

		update_field( 'form_registry_id', $entry['id'], $post_id );
		

		foreach ( $form['fields'] as $field ) {

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
					/* echo 'Clico i  - '.$i.' Valor de b -'.$b.'<br>'; */
					$user = get_user_by( 'email', get_field('expert_email',$b) );
					$idb_tax = get_field('idb_tax',$b);
					$userId = $user->ID;
					$e_email = $user->user_email;
					$e_name = $user->first_name . ' ' . $user->last_name;
					update_field( 'consultant', $userId , $post_id );
					/* echo ('fator_de_compet'.$idb_tax); */
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

			/* if($x==10){  */
			if($_SESSION['membro']){ 	
				/* echo ('Passei no 10-> '.$post_id. 'referenciado xxx'.$_SESSION['rid']); */
				if($_SESSION['membro']){
					update_field( 'is_referral' ,1 , $post_id ); 
					update_field( 'referral', $e_email , $post_id);
					update_field( 'sr_original',  $_SESSION['rid'] ,$post_id);

					update_field( 'origin_sr', $_SESSION['membro'],$_SESSION['rid'] );
					update_field( 'new_sr' , $post_id, $_SESSION['rid'] );
					update_field('state',__('Other Referenced Member'),$_SESSION['rid']);
			
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
					update_field( 'reference_value', $a , $post_id );
					
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

			//Alteção Cleverson - Buscar email do costumercare para informar do novo serviço do expert.
			$customer_care = get_field('costumer_care_email', 'option');

			$cl_reference_value = get_field('reference_value',$post_id,'');
			$cl_servico_id = get_field('request_type',$post_id,'');
			//Cacula valor do serviço
			$cl_orcamento = get_field('budget_max',$post_id,'');

			$cl_v_para_member = ((Int)$cl_orcamento*(int)$idb_tax)/100;
			

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
				
						
						
					

					// to expert
					// Contact Lead Purchase Codigo do email - 
						$subject = pll__('New service request in your account').' '.$cl_servico;
						$hi = $subject;
						$to = $e_email.','.$customer_care; //Sr Alberto pediu para retirar a copia enviada para o Custumercare dia 30/06/2021 - Ativado dia 28/03/2022 Junto com Sofia.
						$headers = array('Content-Type: text/html; charset=UTF-8');
					
						$message  = __('Hello').' ' .' {{expert}}';
						$message .= '<br />'.'<br />'.__('It received a new Service Request for its Area of Expertise').' '.$cl_servico.' '. __('with the Reference Value').' '.number_format((float)$cl_reference_value, 2, '.', '').__('Money Simbol');
						$message .= '<br />'.__('Lead purchase amount').':'.number_format((float)$cl_v_para_member, 2, '.', '').__('Money Simbol');
						$message .= '<br />'.__('To accept, decline or reference the Service Request go to your Orders Dashboard at:{{service_requests_page}}');
						$message .= '<br /><br />'.__('Thank you');
						$message .= '<br />'.__('The iDealBiz Team');

					
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
							$message .= '<br />'.'<br />'.__('It received a new Service Request for its Area of Expertise').' '.$cl_servico.' '. __('with the Reference Value').' '.number_format((float)$cl_reference_value, 2, '.', '').__('Money Simbol');
							$message .= '<br />'.__('Lead purchase amount').':'.number_format((float)$cl_v_para_member, 2, '.', '').__('Money Simbol');
							$message .= '<br /><br />'.__('_str You have been referred by the member').' : '.$cl_display_name;
							$message .= '<br />'.__('_str Email of the member who referred you').' : '.'<a href=mailto:"'.$mailadresje.'">'.$mailadresje.'</a>';
							$message .= '<br /><br />'.__('To accept, decline or reference the Service Request go to your Orders Dashboard at:{{service_requests_page}}');
							$message .= '<br /><br />'.__('Thank you');
							$message .= '<br />'.__('The iDealBiz Team');
							
							
						}else{

							// to expert
							// Contact Lead Purchase Codigo do email - 
							$subject =__('_str Received a new referral from the Member').' '.$cl_member_f_name.' '.$cl_member_l_name;
							$hi = __('New referencing between members');
							$to = $e_email.','.$customer_care; //Sr Alberto pediu para retirar a copia enviada para o Custumercare dia 30/06/2021
							$headers = array('Content-Type: text/html; charset=UTF-8');
						
							$message  = __('_str Hello').' ' .' {{expert}}';
							$message .= '<br />'.'<br />'.__('_str Received a Member Service Request Reference for its Area of Expertise').' '.$cl_servico.' '. __('with the Reference Value').' '.(double)$cl_reference_value.__('Money Simbol').'.<br />'.__('made by the Member').' <b><i>'.$cl_member_f_name.' '.$cl_member_l_name.'</b></i>.';
							$message .= '<br />'.__('_str Lead purchase amount').':'.(double)$cl_v_para_member.__('Money Simbol');
							$message .= '<br /><br />'.__('_str To accept, decline or reference the Service Request go to your Orders Dashboard at:{{service_requests_page}}');
							$message .= '<br /><br />'.__('_str Thank you');
							$message .= '<br />'.__('_str The iDealBiz Team');

						}


				}
				
					$message = str_replace('{{expert}}', $e_name ,$message);
					$message = str_replace('{{budget_max}}', $max.' '.get_woocommerce_currency_symbol() ,$message);
					$message = str_replace('{{service_requests_page}}', '<a href="'.wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'">'.__('My Service Requests','idealbiz').'</a>' ,$message);
				
					$emailHtml  = get_email_header();
					$emailHtml .= get_email_intro('', $message, $hi);
					$emailHtml .= get_email_footer();
				
					wp_mail($to,$subject,$emailHtml,$headers);
					
				
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
 

		update_field( 'customer', $nuser, $post_id );
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
