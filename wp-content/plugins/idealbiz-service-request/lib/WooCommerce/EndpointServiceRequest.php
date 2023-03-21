<?php

use ACA\ACF\Editing\TrueFalse;
use ACP\Asset\Style;

class EndpointServiceRequest {

	public function render() {

		$current_user = wp_get_current_user();
		$expert = isExpert();


		if ($_GET['home']==1 ) {
			$expert = isExpert();

			if ( in_array( 'consultant', $current_user->roles, true ) || $expert ) {
				consultLeadModeServiceRequest($expert[0]->ID);
			}
		}	

		
		

		//echo $cl_consultOperatingModeSiteAndMember ;


		$cl_sr_pay_lead_mode = get_field('sr_pay_lead_mode',$expert[0]->ID);

		if($cl_sr_pay_lead_mode === NULL){
			$cl_sr_pay_lead_mode = ['value'=>'sr_pay_before','label'=>'Pay Before'];
		}
	


		if ($_GET['home']==1){
			$cl_tituloPagina = __('_str Service Resquest','idealbiz');
			$cl_icon = '<span class="dashicons dashicons-feedback"></span>';
			$cl_valueTitle = __('_str sr_Value','idealbiz');
		}

		if ($_GET['referrals']==1){
			$cl_tituloPagina = __('_str Forwarding Received','idealbiz');
			$cl_icon = '<span class="dashicons dashicons-download"></span>';
			$cl_valueTitle = __('_str sr_Value','idealbiz');
		}
		if ($_GET['referrals']==2){
			$cl_tituloPagina = __('_str Forwarding Sent','idealbiz');
			$cl_icon = '<span class="dashicons dashicons-upload"></span>';
			$cl_valueTitle = __('_str sr_Value','idealbiz');
		}
		if ($_GET['recommended_service']=='sent'){
			$cl_tituloPagina = __('_str Recommended Sent','idealbiz');
			$cl_icon = '<span class="dashicons dashicons-upload"></span>';
			$cl_valueTitle = __('_str sr_Value','idealbiz');
		}
		
		if ($_GET['recommended_service']=='received'){
			$cl_tituloPagina = __('_str Recommended Received','idealbiz');
			$cl_icon = '<span class="dashicons dashicons-download"></span>';
			$cl_valueTitle = __('_str sr_Value','idealbiz');
		}




				//NPMM - Gera cabeçalho de Pedidos de serviços
				printf(
					'
					<style>
					.nobr{
						font-weight: bold;
						font-size: 1.1em;
						color:#ffffff;
						
					}
					.cl_cab{
					
						background-color:#F58026;
					}
					.cl_cab th h2{
						margin-top: 0px;
						text-align: center;
						margin-bottom: 0px;
					}
					</style>
					<table class="
					woocommerce-orders-table woocommerce-MyAccount-orders shop_table 
					shop_table_responsive my_account_orders account-orders-table
					block stroke dropshadow p-30 m-b-25 b-r-5 white--background 
					">
						<thead>
							<tr class="cl_cab">
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><h2 class="nobr">'.$cl_tituloPagina.'</h2></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><h2 class="nobr">'.__('Date','idealbiz').'</h2></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><h2 class="nobr">'.__('Status','idealbiz').'</h2></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><h2 class="nobr">'.__('_str Reference Value','idealbiz').'</h2></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions"><h2 class="nobr text-center">'.$cl_valueTitle.'</h2></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions text-center"><h2 class="nobr">'.__('_str Buttons','idealbiz').'</h2></th>
							</tr>
						</thead>
				
						<tbody> 


						%1$s
					</tbody>	</table>',
					$this->get_items()
				);



	}

	private function get_items() {

		
		$current_user = wp_get_current_user();
		$args = array(
			'post_type' => 'service_request',
			/* 'author'    => $current_user->ID, */
			'posts_per_page' => -1
		);

		// Check if user is consultant. 
/* 		if ($_GET['home']==1 ) {
			$expert = isExpert(); 

			if ( in_array( 'consultant', $current_user->roles, true ) || $expert ) {
				//var_dump($current_user->roles); 
				//die();
				//echo 'Primeiro IF ';
				if ( in_array( 'consultant', $current_user->roles, true ) && !$expert ) {
					//echo 'SubPrimeiro IF ';
				}else{
					//var_dump($current_user->ID);  //Prestador Serviço ID 3559
					unset( $args['author'] );
					$args['meta_key']   = 'consultant';
					$args['meta_value'] = $current_user->ID;
					$args['meta_query'] = array(               
						'relation' => 'AND',          
							array(
							'relation' =>'AND',
							'key' => 'referral',
							'value' => NULL,      
							'compare' => '='       
							));
				}
			}

		} */



		if ($_GET['home']==1 ) {
			$expert = isExpert();

			if ( in_array( 'consultant', $current_user->roles, true ) || $expert ) {
				$cl_consultant = 'consultant';
			}else{
				$cl_consultant = 'customer';
			}
		}		

		//var_dump($current_user);
		//var_dump($cl_consultant);




		if ($_GET['home']==1 ) {
			$args['meta_query'] = array(               
				
				'relation' => 'AND',          
				array(
					'key' => 'sr_type_origin',                
					'value' => 'normal_service',         
					'compare' => '='     
				),				
				'relation' => 'AND',
				array(
					'key' => $cl_consultant,
					'value' => $current_user->ID,    
					'compare' => '='       
				)
			);

		}

		//CRIADO CLEVERSON 10/10/22 - Buscar apenas Recomendações enviadas
		
		if ($_GET['referrals']=='2' ) {
			unset( $args['author'] );
			$args['meta_query'] = array(               
				'relation' => 'AND',          
					array(
						'key' => 'sr_type_origin',                
						'value' => 'forward_service ',         
						'compare' => '='
					),
					'relation' => 'AND',
					array(
						'key' => 'consultant',
						'value' => $current_user->ID,
						'compare' => '=' 
					)
				);

		}


		if ($_GET['referrals']=='1' ) {
			unset( $args['author'] );
			$args['meta_query'] = array(               
				'relation' => 'AND',          
					array(
						'key' => 'sr_type_origin',                
						'value' => 'forward_service',         
						'compare' => '='         
					),
					'relation' => 'AND',
					array(
						'key' => 'referral',
						'value' => $current_user->user_email,
						'compare' => '=' 
					)
				);

		}

		//CRIADO CLEVERSON 06/10/22 - Buscar apenas Recomendação de Serviço Recebidas

		if ($_GET['recommended_service']=='sent' ) {
			//cl_alerta('Received');
			unset( $args['author'] );
			$args['meta_query'] = array(               
				'relation' => 'AND',          
					array(
						'key' => 'sr_type_origin',                
						'value' => 'recommende_service',         
						'compare' => '='      
					),
					array(
						'key' => 'customer',
						'value' => $current_user->ID,
						'compare' => '=' 
					)
				);
		}

		//CRIADO CLEVERSON 06/10/22 - Buscar apenas Recomendação de Serviço Enviados

		if ( $_GET['recommended_service']=='received' ) {
			//cl_alerta('Sent');
			unset( $args['author'] );
			$args['meta_query'] = array(               
				'relation' => 'AND',          
					array(
						'key' => 'sr_type_origin',                
						'value' => 'recommende_service',         
						'compare' => '='       
					),
					array(
					'key' => 'consultant',
					'value' => $current_user->ID,
					'compare' => '=' 
					)
				);	
		}

		//var_dump($current_user->roles);

		$query = new \WP_Query( $args );

		/* var_dump($query->ID); */
		

		//echo "Last SQL-Query: {$query->request}";



		if ( ! $query->have_posts() ) {
			return '<h4 class="m-b-20">' . __( 'No Service requests found.', 'idealbiz' ) . '</h4>';
		}

		$service_requests = '';

		while ( $query->have_posts() ) {
			$query->the_post();
			
			$service_requests .= $this->render_item();
		}
		wp_reset_postdata();

		return $service_requests;
	}


	//NPMM - BUSCAR NO function.php  POR COMENTÁRIO "Dados que vem da caixa de rejeição".
	private function get_cancel_btn($label,$id = NULL){
		if(!$id){
			$id = get_the_ID();
		}
		return '
		<a href="#reject_porposal'.$id.'" 
						title="'.$label.' Service Request" 
						class=" btn-blue popUpForm info-modal" style="float:right; opacity: 0.5;">'.$label.'</a>
		<div class="popWrapper" id="reject_porposal'.$id.'">
				<div class="popWrapper_screen"></div>
				<div class="iziModal formPopUp">
					<div class="iziModal-wrap" style="height: auto;">
						<div class="iziModal-content" style="padding: 0px;">
							<div class="content generic-form p-b-20 p-r-20 p-l-20"> 
								<button data-izimodal-close="" class="icon-close popUpForm" href="#reject_porposal'.$id.'"></button>
								<div class="clear"></div>
									<form class="gform_wrapper" id="reject_proposal_id'.$id.'" method="post" action="#">
										<input type="hidden" name="proposal_id" value="'.$id.'">
										<input type="hidden" name="reject" value="1">
										<div class="acf-label" style="text-align:left;">
										<label for="acf-_post_content"  style="text-align:left;">'.__('Type in your reason','idealbiz').' <span class="acf-required">*</span></label></div>
										<textarea name="reason" required></textarea>
										<button class=" btn-blue m-t-15" type="submit" value="Submit">'.__('Submit').'</button><br/>
									</form>
							</div>    
						</div>
					</div>    
				</div>
			</div>';
	}


	private function confirmLead($label,$id = NULL){
		if(!$id){
			$id = get_the_ID();
		}
		$checkout_url =  wc_get_checkout_url() . '?add-to-cart=' . getProductByType('lead').'&sr-lead='.$id.'&mod=service';
		return '
		<a href="#confirmLead'.$id.'" 
						title="'.$label.' Service Request" 
						class=" btn-blue popUpForm info-modal m-t-5 m-r-5 m-b-5" style="float:right; opacity: 0.8;">'.$label.'</a>
		<div class="popWrapper" id="confirmLead'.$id.'">
				<div class="popWrapper_screen"></div>
				<div class="iziModal formPopUp">
					<div class="iziModal-wrap" style="height: auto;">
						<div class="iziModal-content" style="padding: 0px;">
							<div class="content generic-form p-b-20 p-r-20 p-l-20"> 
								<button data-izimodal-close="" class="icon-close popUpForm" href="#confirmLead'.$id.'"></button>
								<div class="clear"></div>
									<form class="gform_wrapper" id="confirm_confirmLead_id'.$id.'" method="post" action="#">
										<input type="hidden" name="confirmLead_id" value="'.$id.'">
										<input type="hidden" name="confirm" value="1">
										<input type="hidden" name="checkout_url" value="'.$checkout_url.'">
										<div class="acf-label" style="text-align:left;">
										<label for="acf-_post_content"  style="text-align:left;">'.__('_str Type in your Comment','idealbiz').' <span class="acf-required">*</span></label></div>
										<textarea name="comment" required></textarea>
										<button class=" btn-blue" type="submit" value="Submit">'.__('_str Confirm Lead').'</button><br/>
									</form>
							</div>    
						</div>
					</div>    
				</div>
			</div>';
	}

	private function render_item() {



		$expert = isExpert();
		$cl_id_membro = $expert[0]->ID;
		$sr_id = get_the_ID();
		$checkout_url =  wc_get_checkout_url() . '?add-to-cart=' . getProductByType('lead').'&sr-lead='.$sr_id.'&mod=service';
		$cl_statusOrder = get_field('rs_status_order',$sr_id);
		$cl_value = wc_price(getLeadSRValue($sr_id));

		//


		if ($_GET['home']==1){
			$cl_url_part = 'home='.$_GET['home'];
			$cl_value_Lead = $cl_value;
			
		}

		if ($_GET['referrals']==1){
			$cl_url_part = 'referrals='.$_GET['referrals'];
			$cl_value_Lead = $cl_value;
			$cl_magrTop = 'm-t-50';
		}
		if ($_GET['referrals']==2){
			$cl_url_part = 'referrals='.$_GET['referrals'];
			$cl_value_Lead = $cl_value;
			$cl_magrTop = 'm-t-50';
		}
		if ($_GET['recommended_service']=='sent'){
			$cl_url_part = 'recommended_service='.$_GET['recommended_service'];
			$cl_value_Lead = $cl_value;
			$cl_magrTop = 'm-t-50';
		}
		
		if ($_GET['recommended_service']=='received'){
			$cl_url_part = 'recommended_service='.$_GET['recommended_service'];
			$cl_value_Lead =$cl_value;
			$cl_magrTop = 'm-t-45';
		}

		

		$cl_sr_pay_lead_mode = get_field('sr_pay_lead_mode',$expert[0]->ID);

		if($cl_sr_pay_lead_mode === NULL){
			$cl_sr_pay_lead_mode = ['value'=>'sr_pay_before','label'=>'Pay Before'];
		}







		

			// used to get the most recent message to this service request.
			$message_query = new \WP_Query(
				array(
					'post_type'      => 'service_message',
					'order_by'       => 'date',
					'order'          => 'DESC',
					'posts_per_page' => 1,
					'meta_query'     => array(
						array(
							'key'   => 'service_request',
							'value' => get_the_ID(),
						),
					),

				)
			);

			//NPMM - Verificar se é área que mostra os botões.
			$state = '';
			$btns = '';
			$hide_see=0;
			$current_user = wp_get_current_user();
			$cl_statusOrder = get_field('rs_status_order',$sr_id);

			

			if($cl_statusOrder != ""){
			$cl_orderID = '<div><h5 class="cl_h5 m-r-5">'.__('_STR ORDER ID','idealbiz').' ' . get_field('rs_order_id',$sr_id).'</h5></div>';
			}


			//BOTÕES

			//var_dump(get_field('consultant', $sr_id));

			echo 
			'<style>
			.btn-blue{
				border-radius:5px;
			}
			.cl_no_action{
				text-aling:center !important; 
			}
			.cl_status, .cl_al_dir{
				color:#28A746;
				font-size:1.2em;
				font-weight: 600;
				/* background-color:#28A7461C !important; */
				border-radius: 5px;
			}

			.cl_list_value{
				color:#353535;
				font-size:1.2em;
				font-weight: 600;
				/* background-color:#285FA75F !important; */
				border-radius: 5px;
				border: 1px solid #ccc;
				
			}

			.cl_list_value_border{
				border-radius: 5px;
				border: 1px solid #ccc;
				
			}

			.cl_al_dir {
				text-align: center !important;
				border: 1px solid #ccc;
			}

			</style>';

			$cl_btn_Accept = '
			<a href="?accept='.get_the_ID().'" 
			title="Accept Service Request" 
			class=" btn-blue m-r-5  m-t-5" style="float:right">'.__('Accept', 'idealbiz-service-request' ).'</a>1111
			';

			$cl_btn_Reject = $this->get_cancel_btn(__('11Reject','idealbiz'));

			$cl_btn_Cancel = ' '.$this->get_cancel_btn(__('11Cancel','idealbiz')).' ';

			$cl_btn_confirmLead = ' '.$this->confirmLead(__('_str Confirm Deal','idealbiz')).' ';

			$cl_btn_View_Lead = '<div><a href="#post-'.$sr_id.'"
			title="'.__( '_str Utilize this Buttom to View Lead', 'idealbiz-service-request' ).'" 
			style="background: #353535 !important;color:#ffffff;" class=" btn-blue m-r-5  m-t-5 m-b-5 popUpForm info-modal" style="float:right">'.__( 'View Lead' ).'</a></div>
			'.makeSRLeadModal($sr_id);	

			$cl_btn_View_Lead_disable = '<div><a href="#post-'.$sr_id.'"
			title="'.__( '_str Utilize this Buttom to View Lead', 'idealbiz-service-request' ).'" 
			style="background: #ccc !important; color:#fff;" class=" btn-blue m-r-5  m-t-5 m-b-5 popUpForm info-modal" style="float:right">'.__( 'View Lead' ).'</a></div>
			';	

			//BOTÃO ABAIXO DESATIVADO CONFORME CONVERSA COM DR ALBERTO DIA 20/10/22 - CONFORME BRAFING DO EXCEL
			$cl_btn_Request_Another_Member = '<div class="m-t-5 m-r-5" style="float:right;">
			<a href="'.getLinkByTemplate('single-counseling.php').'?sr='.get_field('request_type',$sr_id)->term_id.'&rid='.$sr_id.'&refer=1" 
			title="'.__( 'Request to Another Member', 'idealbiz-service-request' ).'" 
			class=" btn-blue">'.__( '11Request to Another Member', 'idealbiz-service-request' ).'</a>
			</div>';	


			$cl_btn_Reject_ID = '<div class="m-t-5 m-r-5" style="float:right;">'.$this->get_cancel_btn(__('Reject','idealbiz'),$sr_id).'</div>';


			$cl_btn_Pay = '
			<div><a href="'.$checkout_url.'" 
			title=""class=" btn-blue m-r-5  m-t-5" style="float:right">'.__( '_str Pay' ).'</a></div>';

			$cl_btn_Wait_Pay = '
			<div><a href="'.$checkout_url.'" 
			title="" style="background: #353535 !important;color:#ffffff;" class=" btn-blue m-r-5  m-t-5" style="float:right">'.__( '_str Awaiting payment' ).'</a></div>';


			$cl_btn_View_Lead_Pay_Later ='
						<a href="#post-'.$sr_id.'"
						title="'.__( '_str Utilize this Buttom to View Lead', 'idealbiz-service-request' ).'" 
						class=" btn-blue m-r-5  m-t-5 popUpForm info-modal" style="float:right" onclick="window.location=\''.'?'.$cl_url_part.'&view_lead='.$sr_id.'\';">'.pll__( '_str View Lead Pay Later' ).'</a>						
						'.makeSRLeadModal($sr_id);

			$cl_btn_no_action ='<p class="cl_no_action">'. __('_str No Action Available','').'</p>';			


			switch ( get_field( 'state' ,$sr_id)) {
				case 'Pending Expert Acceptance':

/* 					if($cl_statusOrder === "" || $cl_statusOrder === NULL){
						$btns = 'Pay11'.$cl_btn_Pay;
						$btns .= $cl_btn_View_Lead_disable;
					}

					$state = $cl_state_PendingExpertAcceptance;
						$hide_see=0;
						
						if ($cl_sr_pay_lead_mode['value']==='sr_pay_before' && $cl_statusOrder === "" 
						 || $cl_sr_pay_lead_mode['value']==='sr_pay_before' && $cl_statusOrder === NUll){
				
							
							if ($_GET['recommended_service']!=='sent'){
								
								$btns = 'Pay12'.$cl_btn_Pay;

								if($cl_statusOrder === "" || $cl_statusOrder === NULL){
									$btns .= '1'.$cl_btn_Reject_ID;
								}

							}else{
								
							}	
						}
						if ($cl_sr_pay_lead_mode['value']==='sr_pay_before' && $cl_statusOrder === 'on-hold'){
							$btns = $cl_btn_View_Lead_disable;
						}

						if(
							$cl_sr_pay_lead_mode['value']==='sr_pay_later' && $_GET['recommended_service']==='sent' 
							|| $cl_sr_pay_lead_mode['value']==='sr_not_pay' && $_GET['recommended_service']==='sent'
						){
							
							if($cl_sr_pay_lead_mode['value']==='sr_pay_later'){
							
							}
							if($cl_sr_pay_lead_mode['value']==='sr_not_pay'){
								$btns  = $cl_btn_View_Lead;	
								}
						}else{
							
							if($cl_sr_pay_lead_mode['value']==='sr_not_pay' && $_GET['recommended_service']==='sent'){
								$btns  = $cl_btn_View_Lead;							
							}else{
								if($cl_sr_pay_lead_mode['value']==='sr_pay_later'){
								$btns  = $cl_btn_View_Lead_Pay_Later;
								
								}else{
									if($cl_statusOrder !== "" || $cl_statusOrder !== NULL){
									$btns  = '2'.$cl_btn_View_Lead;
									}
									$btns .= '2'.$cl_btn_Reject_ID;
								}
							}
							
						}

						if ($cl_sr_pay_lead_mode['value']==='sr_pay_before' && $cl_statusOrder === 'completed'){
							$btns = $cl_btn_View_Lead.'<br/><br/>';
						}

							if(get_field('consultant',get_the_ID())->ID == $current_user->ID){
								$btns = 'Conf33'.$cl_btn_Accept;
							}else{
								$Date = date('Y-m-d');
								$DateExp = date('Y-m-d', strtotime(get_the_date( 'Y-m-d', $message_query->posts[0] ). ' + 90 days'));
								// ticket #123
								if( $DateExp < $Date || 1==1 ) {
									if(WEBSITE_SYSTEM!='1'){
									$btns = ' '.$this->get_cancel_btn(__('Cancel','idealbiz')).' ';
									}
								}else{
									//echo 'not cancel';
								}
							} */




						
					break;
					

				case 'Pending Proposal':
					$state = $cl_state_PendingProposal;
						
					
					if ($cl_sr_pay_lead_mode['value']==='sr_pay_before'){
				
						$btns = 'Conf44'.$cl_btn_Pay;		
							if($_GET['home']==1){						
								$btns .= 'REJ111'.$cl_btn_Reject_ID;	
							}
					}	

					if($cl_sr_pay_lead_mode['value']==='sr_pay_later' && $_GET['recommended_service']=='sent' ){
						$btns  = $cl_btn_View_Lead_Pay_Later;	
					}else{
						$btns  = $cl_btn_View_Lead_Pay_Later;
						$btns .= 'Conf22'.$cl_btn_confirmLead;
					}




						break;
				case 'Waiting on Customer': 
					$state = __( 'Waiting on Customer', 'idealbiz-service-request' );
					if(get_field('consultant',get_the_ID())->ID == $current_user->ID){
						$btns =' '.$this->get_cancel_btn(__('22Cancel','idealbiz')).' ';
					}
					break;

				case 'Waiting on Consultant':
					$state = __( 'Waiting on Consultant', 'idealbiz-service-request' );
					if(get_field('consultant',get_the_ID())->ID == $current_user->ID){
						$btns =' '.$this->get_cancel_btn(__('33Cancel','idealbiz')).' ';
					}
					break;

				case 'completed':
					$state=pll__('Payment Completed');
										
					
					if(get_field('consultant',get_the_ID())->ID == $current_user->ID){
						$btns .= 'btn2'.$cl_btn_View_Lead;
					}
					break;

				case 'Confirmed Lead':
					$state = $cl_Confirmed_Lead;
					break;


				case 'Closed':
					$state = __( 'Closed', 'idealbiz-service-request' );
					break;
				case 'Rejected':
					$hide_see=1;
					if(get_field('consultant',get_the_ID())->ID == $current_user->ID){
						$state = ''.__( 'Rejected', 'idealbiz' ).''.get_field('rejected').''; // reason message left
					}else{
						//client, hide message #228v
						$state = ''.__( 'Rejected', 'idealbiz' ); // reason message left
					}
					
					break;
				case 'Canceled':
					$hide_see=1; 
					$state = ''.__( 'Canceled', 'idealbiz' ).''.get_field('rejected').''; // reason message left 
					break;	
					
				//Implementado por Cleverson 28/04/2022
				case 'Other Referenced Member':
					$state = __( '_str Definir XXXX', 'idealbiz-service-request' );
					$btns = ' ';
					break;


			}


		//CONFUIGURAÇÕES DE ACORDO COM MODO DE PAGAMENTO DA LEAD
		if ($cl_sr_pay_lead_mode['value']==='sr_pay_before' && $cl_statusOrder === null){

			$cl_state_PendingProposal = '<span style="color:#353535">'.__( 'Pending Proposal', 'idealbiz-service-request' ).'</span>';
		}

		if ($cl_sr_pay_lead_mode['value']==='sr_pay_before' && $cl_statusOrder === 'on-hold'){
			
			$cl_state_PendingProposal = __( 'Pending Proposal', 'idealbiz-service-request' );
		}
		if ($cl_sr_pay_lead_mode['value']==='sr_pay_before' && $cl_statusOrder === 'completed'){
			
			$cl_state_PendingProposal = __( 'Pending Proposal', 'idealbiz-service-request' );
		}

		
		if($cl_sr_pay_lead_mode['value']==='sr_pay_later' && $_GET['recommended_service']==='sent' ){
			$cl_state_PendingExpertAcceptance ='<span style="color:#353535">'.__( 'Pending Expert Acceptance', 'idealbiz-service-request' ).'</span>';
			
		}else{

			


				
		}

		if ($cl_sr_pay_lead_mode['value']==='sr_no_pay'){
			$cl_state_PendingExpertAcceptance = __( 'Pending Expert Acceptance', 'idealbiz-service-request' );
		}



		
			$h_title='';
			$sr_title = get_field('request_type',$sr_id)->name;	
			$sr_date= get_the_date( 'd-M-y H:i', $sr_id);
			$c_field = get_field('customer',$sr_id);//NPMM - Apenas sever apra exibir a segunda linha, com Origen e Referral
			$cl_referral = get_user_by('email',get_field('referral',$sr_id))->display_name; //Quem Foi referenciado
			$cl_consultant = get_field('consultant',$sr_id)->display_name; // Consultor Selecionado na geração do serviço
			$cl_customer = get_field('customer',$sr_id)->display_name; // Quem Gerou o serviço
			$cl_reference_value = wc_price(get_field('reference_value',$sr_id));
			$cl_rejected = '<h5 class="cl_h5"><span style="font-size:1.4em;">'.get_field('rejected',$sr_id).'</span></h5>';
			





			echo 
			'<style>
			.cl_h5
				{
					font-size:0.7em;
					text-transform:uppercase;
					color:#777777;
				}
			</style>';

			
			if ($_GET['home']==1){

				$h_title='<br/><h5 class="cl_h5"><span style="font-size:1.4em;" class="dashicons dashicons-migrate"></span>'.__('_STR SOURCE','idealbiz').': '.$cl_customer.'</h5>';
				
				if ($cl_sr_pay_lead_mode['value']==='sr_pay_before'){
					$cl_state_PendingExpertAcceptance = '<span style="color:#353535">'.__( 'Pending Expert Acceptance', 'idealbiz-service-request' ).'</span>';
					
					if($cl_statusOrder === "" || $cl_statusOrder === NULL){
						$btns = $cl_btn_Pay;
						$btns .= $cl_btn_Reject_ID;
					}
					if($cl_statusOrder === 'on-hold'){
						$cl_state_PendingExpertAcceptance ='<span style="color:#DF8F04">'. __( '_str Waiting for Payment Confirmation','idealbiz-service-request' ).'</span>';
						$btns  = $cl_btn_View_Lead_disable;
					}

					if($cl_statusOrder === 'completed'){
						$cl_state_PendingExpertAcceptance ='<span style="color:#40C50B">'. __( '_str Completed Payment', 'idealbiz-service-request' ).'</span>';
						$btns  = $cl_btn_View_Lead;
					}

				}
				
				if ($cl_sr_pay_lead_mode['value']==='sr_pay_later'){
					$cl_state_PendingExpertAcceptance = '<span style="color:#353535">'.__( 'Pending Expert Acceptance', 'idealbiz-service-request' ).'</span>';					
					$btns ='<p class="cl_no_action">'. __('_str No Action Available','').'</p>';	

										
					if (get_field('sr_confirmation_date',$sr_id) !="" || get_field('sr_confirmation_date',$sr_id) != NULL){
						$cl_Confirmed_Lead 	=  '<span style="color:#353535">'.__( '_str Indication of Completed Business', 'idealbiz-service-request' ).'</span>';
						$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5">'.__('_STR IN','idealbiz').': '.get_field('sr_confirmation_date',$sr_id).'</h5></div>';
						$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5">'.get_field('sr_confirmed',$sr_id).':</h5></div>';
						$cl_state_PendingExpertAcceptance = $cl_Confirmed_Lead ;
						$cl_magrTop = 'm-t-5'; //MARGEM TOPO VISUALIZAÇÃO.	
						
						
						if($cl_statusOrder == "" || $cl_statusOrder == NULL){
						$btns = $cl_btn_Wait_Pay;
						$btns  .= $cl_btn_View_Lead;
						}
						
						if($cl_statusOrder == "on-hold"){
							
							$cl_state_PendingExpertAcceptance .='<span style="color:#DF8F04">'. __( '_str Waiting for Payment Confirmation','idealbiz' ).'</span>';
							$btns  = $cl_btn_View_Lead;
						}
						if($cl_statusOrder == "completed"){

							$btns = '';
							$cl_state_PendingExpertAcceptance .='<span style="color:#40C50B">'. __( '_str Completed Payment', 'idealbiz-service-request' ).'</span>';
						}


					}else{			
						$cl_magrTop = 'm-t-85'; //MARGEM TOPO VISUALIZAÇÃO.					
						$btns  = $cl_btn_View_Lead_Pay_Later;
						$btns .= $cl_btn_confirmLead;
						$btns .= $cl_btn_Reject_ID;
					}

				}

				if ($cl_sr_pay_lead_mode['value']==='sr_not_pay'){
					$cl_state_PendingExpertAcceptance = '<span style="color:#13D505">'.__( '_str View of the Released Lead', 'idealbiz-service-request' ).'</span>';
					$btns  = $cl_btn_View_Lead;
					$btns .= $cl_btn_Reject_ID;
				}

				$state = $cl_state_PendingExpertAcceptance;
			
			}
//SEBD SECTION
			if ($_GET['recommended_service']==='sent'){
				$h_title = '<h5 class="cl_h5"><span style="font-size:1.4em;" class="dashicons dashicons-upload"></span>'.__('_STR SENT TO','idealbiz').': '.$cl_consultant.'</h5>';
			
				if ($cl_sr_pay_lead_mode['value']==='sr_pay_before'){
					$cl_state_PendingExpertAcceptance = '<span style="color:#353535">'.__( 'Pending Expert Acceptance', 'idealbiz-service-request' ).'</span>';					
					$btns ='<p class="cl_no_action">'. __('_str No Action Available','').'</p>';
				
					if(get_field( 'state' ,$sr_id) === "Rejected"){
						$cl_state_PendingExpertAcceptance ='<span style="color:#F31906">'. __( '_str Reject', 'idealbiz-service-request' ).'</span>';
						$btns = $cl_btn_no_action;
					}
					
					if($cl_statusOrder === 'on-hold'){
						$cl_state_PendingExpertAcceptance ='<span style="color:#DF8F04">'. __( '_str Waiting for Payment Confirmation','idealbiz-service-request' ).'</span>';	
					}

					if($cl_statusOrder === 'completed'){
						$cl_state_PendingExpertAcceptance ='<span style="color:#40C50B">'. __( '_str Completed Payment', 'idealbiz-service-request' ).'</span>';
						
					}

					if (get_field('sr_confirmation_date',$sr_id) !="" || get_field('sr_confirmation_date',$sr_id) != NULL){
						$cl_Confirmed_Lead 	=  '<span style="color:#353535">'.__( '_str Indication of Completed Business', 'idealbiz-service-request' ).'</span>';
						$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5">'.__('_STR IN','idealbiz').': '.get_field('sr_confirmation_date',$sr_id).'</h5></div>';
						$cl_Confirmed_Lead .= '<div class="text-secondary">'.get_field('sr_confirmed',$sr_id).'</div>';
						$cl_state_PendingExpertAcceptance = $cl_Confirmed_Lead ;
						$cl_magrTop = 'm-t-5'; //MARGEM TOPO VISUALIZAÇÃO.	
						
						if($cl_statusOrder == "" || $cl_statusOrder == NULL){
						$btns = $cl_btn_Wait_Pay;
						}
						
						if($cl_statusOrder == "on-hold"){
							$btns = $cl_btn_no_action;
							$cl_state_PendingExpertAcceptance .='<span style="color:#DF8F04">'. __( '_str Waiting for Payment Confirmation','idealbiz' ).'</span>';
						}
						if($cl_statusOrder == "completed"){

							$btns = $cl_btn_no_action;
							$cl_state_PendingExpertAcceptance .='<span style="color:#40C50B">'. __( '_str Completed Payment', 'idealbiz-service-request' ).'</span>';
						}
					}



				}	
				
				
				if ($cl_sr_pay_lead_mode['value']==='sr_pay_later'){
					$cl_magrTop = 'm-t-5'; //MARGEM TOPO VISUALIZAÇÃO.					
					$btns  = $cl_btn_View_Lead;
					if($cl_statusOrder === 'on-hold'){
						$cl_state_PendingExpertAcceptance ='<span style="color:#DF8F04">'. __( '_str Waiting for Payment Confirmation','idealbiz-service-request' ).'</span>';
					}

				}

				if ($cl_sr_pay_lead_mode['value']==='sr_not_pay'){
					
					$cl_state_PendingExpertAcceptance = '<span style="color:#353535">'.__( 'Pending Expert Acceptance', 'idealbiz-service-request' ).'</span>';					
					$btns ='<p class="cl_no_action">'. __('_str No Action Available','').'</p>';
					$btns  = $cl_btn_View_Lead;
					
					if(get_field( 'state' ,$sr_id) === "Rejected"){
						$cl_state_PendingExpertAcceptance ='<span style="color:#F31906">'. __( '_str Reject', 'idealbiz-service-request' ).'</span>';
						$btns = $cl_btn_no_action;
					}


					if (get_field('sr_confirmation_date',$sr_id) !="" || get_field('sr_confirmation_date',$sr_id) != NULL){
						$cl_Confirmed_Lead 	=  '<span style="color:#353535">'.__( '_str Indication of Completed Business', 'idealbiz-service-request' ).'</span>';
						$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5">'.__('_STR IN','idealbiz').': '.get_field('sr_confirmation_date',$sr_id).'</h5></div>';
						$cl_Confirmed_Lead .= '<div class="text-secondary">'.get_field('sr_confirmed',$sr_id).'</div>';
						$cl_state_PendingExpertAcceptance = $cl_Confirmed_Lead ;
						$cl_magrTop = 'm-t-5'; //MARGEM TOPO VISUALIZAÇÃO.	
						
						if($cl_statusOrder == "" || $cl_statusOrder == NULL){
						$btns = $cl_btn_Wait_Pay;
						}
						
						if($cl_statusOrder == "on-hold"){
							$btns = $cl_btn_no_action;
							$cl_state_PendingExpertAcceptance .='<span style="color:#DF8F04">'. __( '_str Waiting for Payment Confirmation','idealbiz' ).'</span>';
						}
						if($cl_statusOrder == "completed"){

							$btns = $cl_btn_no_action;
							$cl_state_PendingExpertAcceptance .='<span style="color:#40C50B">'. __( '_str Completed Payment', 'idealbiz-service-request' ).'</span>';
						}
					}


				}

				$state = $cl_state_PendingExpertAcceptance;
			
			}
//RECEIVED SECTION
			if ($_GET['recommended_service']==='received'){
				$h_title = '<h5 class="cl_h5"><span style="font-size:1.4em;" class="dashicons dashicons-download"></span>'.__('_STR SOURCE','idealbiz').': '.$cl_customer.'</h5>';
			
				if ($cl_sr_pay_lead_mode['value']==='sr_pay_before'){
					$cl_state_PendingExpertAcceptance = '<span style="color:#353535">'.__( 'Pending Expert Acceptance', 'idealbiz-service-request' ).'</span>';
					
					if($cl_statusOrder === "" || $cl_statusOrder === NULL){
						$btns = $cl_btn_Pay;
						$btns .= $cl_btn_Reject_ID;
					}
					if($cl_statusOrder === 'on-hold'){
						$cl_state_PendingExpertAcceptance ='<span style="color:#DF8F04">'. __( '_str Waiting for Payment Confirmation','idealbiz-service-request' ).'</span>';
						$btns  = $cl_btn_View_Lead_disable;
					}

					if($cl_statusOrder === 'completed'){
						$cl_state_PendingExpertAcceptance ='<span style="color:#40C50B">'. __( '_str Completed Payment', 'idealbiz-service-request' ).'</span>';
						$btns  = $cl_btn_View_Lead;
					}
				}	
				
				if ($cl_sr_pay_lead_mode['value']==='sr_pay_later'){

					$cl_state_PendingExpertAcceptance = '<span style="color:#353535">'.__( 'Pending Expert Acceptance', 'idealbiz-service-request' ).'</span>';					
					$btns ='<p class="cl_no_action">'. __('_str No Action Available','').'</p>';			
										
					if (get_field('sr_confirmation_date',$sr_id) !="" || get_field('sr_confirmation_date',$sr_id) != NULL){
						$cl_Confirmed_Lead 	=  '<span style="color:#353535">'.__( '_str Indication of Completed Business', 'idealbiz-service-request' ).'</span>';
						$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5">'.__('_STR IN','idealbiz').': '.get_field('sr_confirmation_date',$sr_id).'</h5></div>';
						$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5">'.get_field('sr_confirmed',$sr_id).':</h5></div>';
						$cl_state_PendingExpertAcceptance = $cl_Confirmed_Lead ;
						$cl_magrTop = 'm-t-5'; //MARGEM TOPO VISUALIZAÇÃO.	
						
						if($cl_statusOrder == "" || $cl_statusOrder == NULL){
						$btns = $cl_btn_Wait_Pay;
						}
						
						if($cl_statusOrder == "on-hold"){
							$btns = $cl_btn_View_Lead;
							$cl_state_PendingExpertAcceptance .='<span style="color:#DF8F04">'. __( '_str Waiting for Payment Confirmation','idealbiz' ).'</span>';
						}
						if($cl_statusOrder == "completed"){

							$btns = $cl_btn_View_Lead;
							$cl_state_PendingExpertAcceptance .='<span style="color:#40C50B">'. __( '_str Completed Payment', 'idealbiz-service-request' ).'</span>';
						}


					}else{			
						$cl_magrTop = 'm-t-85'; //MARGEM TOPO VISUALIZAÇÃO.					
						$btns  = $cl_btn_View_Lead_Pay_Later;
						$btns .= $cl_btn_confirmLead;
						$btns .= $cl_btn_Reject_ID;
					}
				}

				if ($cl_sr_pay_lead_mode['value']==='sr_not_pay'){
					$cl_state_PendingExpertAcceptance = '<span style="color:#353535">'.__( 'Pending Expert Acceptance', 'idealbiz-service-request' ).'</span>';					
					$btns ='<p class="cl_no_action">'. __('_str No Action Available','').'</p>';
					$btns  = $cl_btn_View_Lead;
					$btns .= $cl_btn_Reject_ID;

					if($cl_statusOrder === 'on-hold'){
						$cl_state_PendingExpertAcceptance ='<span style="color:#DF8F04">'. __( '_str Waiting for Payment Confirmation','idealbiz-service-request' ).'</span>';
					}
				}




				
				$state = $cl_state_PendingExpertAcceptance;
			
			}
			
			if ($_GET['referrals']==2){
				$h_title ='<h5 class="cl_h5"><span style="font-size:1.4em; class="dashicons dashicons-migrate"></span>'.__('_STR SOURCE','idealbiz').': '.$cl_customer.'</h5>';
				$h_title.='<h5 class="cl_h5">'.__('_str Sent to','idealbiz').': '.$cl_referral.'</h5>';

			}

			if ($_GET['referrals']==1){
				
				$h_title ='<h5 class="cl_h5"><span style="font-size:1.4em;" class="dashicons dashicons-migrate"></span>'.__('_STR SOURCE','idealbiz').': '.$cl_customer.'</h5>';
				$h_title.= '<h5 class="cl_h5">'.__('_str forwarded by','idealbiz').': '.$cl_consultant.'</h5>';
			}

			// Verifica se é uma referenciação atraves do campo ACF em Service Resques is_referral
			if(get_field('is_referral',$sr_id)!=1){
				$sr_original =  get_field('origin_sr',$sr_id);

			}else{
				$sr_original = $sr_id;
			}


			$e_field = get_field('consultant',$sr_id); 
			$current_usere = wp_get_current_user();
				/* if($e_field->ID.'' != $current_usere->ID.''){
				if($e_field->user_email){
					$h_title='<br/><h5 style="font-size:13px;"><span style="color: #777777;">'.$cl_nomeExpert.'</h5>';
					// no cliente
				}
			} */

			$sr_is_referral = get_field('is_referral',$sr_id);
			$current_user = wp_get_current_user();
			//NPMM Coluna Onde Aparece ID e Nome do Serviço Solicitado Segunda Linha.
			if($c_field->ID.'' != $current_user->ID.''){
				
				if($c_field->user_email){

					
					if(get_field( 'state',$sr_id) == 'Rejected'){
						$post_list = get_posts(array(
							'numberposts'	=> 1,
							'post_type'		=> 'counseling'
						));
						$def_support_post_id= '';
						foreach ( $post_list as $post ) {
							$def_support_post_id= $post->ID;
						}
						wp_reset_postdata();

						$new_sr = get_field('new_sr',$sr_id);
						/* var_dump('$sr_id:'.$sr_id);
						var_dump('$new_sr:'.$new_sr); */
						if($new_sr == ''){
							$btns .= '<a style="background: #999 !important; color: #fff !important; opacity: 0.5;"
							title="'.__( 'Sent to Another Expert').'" 
							class=" btn-blue m-r-5  m-t-5" style="float:right">'.__( 'Sent to Another Expert').'</a>';
						}else{

							$btns .= '<a href="'.getLinkByTemplate('single-referral.php').'?sr='.get_field('request_type',$sr_id)->term_id.'&rid='.$sr_id.'&refer=1" 
							title="'.__( 'Request to Another Expert', 'idealbiz-service-request' ).'" 
							class=" btn-blue m-r-5  m-t-5" style="float:right">'.__( 'Request to Another Expert', 'idealbiz-service-request' ).'</a>';
						

						
						}
					} 
					
				}
			}


		$l= get_post_permalink();
		$t= __( 'See Service Request', 'idealbiz-service-request' );
		if(WEBSITE_SYSTEM!='1'){
		$s= __( 'See', 'idealbiz-service-request' );
		$btns = '<a href="'.$l.'" title="'.$t.'" class="'.($hide_see ? 'd-none': '').' btn-blue m-t-5 m-l-5" style="float:right">'.$s.'</a>'.$btns;
		}else{
			//Não mostra Botão
		}
		
			//NPMM - Service Resquest - Comissões - Estudar esta parte para conta corrente.
			if(isset($_GET['referrals'])){
				if($current_user->user_email == get_field('referral',get_the_ID()) && get_field('is_referral')==1){
					$earned = get_field('earned');
					if($earned){
						$btns = '<span style="border: 2px solid #14307b;
											line-height: 13px;
											font-weight: bold;
											padding: 5px 20px;
											margin-top: 18px;
											float: right;">'.wc_price($earned).'</span>';
					}else{
						$btns = 'cl_1234';
					}
				}
			}


		$view ='';
		$cl_n_view = 0;
		$cl_sr_view_lead = get_field('sr_view_lead',$sr_id);
		foreach($cl_sr_view_lead as $viewLead){
			
			if ($viewLead['sr_id_member_saw_lead']===$cl_id_membro){
				$cl_n_view++;				
				$view .= '<p><span><b>'.$cl_n_view.'</b></span>→'.$viewLead['sr_date_saw_lead'].'<span style="font-size:1.4em;" class="dashicons dashicons-visibility"></span></p>';
			}
			
		}


			if($_GET['view_lead']){

				$cl_id_view_lead = $_GET['view_lead'];
				registerViewLead($cl_id_view_lead);

			}




		/* WEBSITE_SYSTEM 1 */

		$is_expert=false;
		if(get_field('consultant',get_the_ID())->ID == $current_user->ID){
			$is_expert=true;
		}

		if(get_post_meta(get_the_ID(),'website_system')[0]=='1'){

	
			
			if(get_post_meta(get_the_ID(),'system1_order')[0]!=''){
				// encontrou encomenda da lead

			}else{
				// ainda não comprou a lead
				//NPMM - AQUI É UM EXPERT
				if($is_expert){


					$bugdet_max= get_field('budget_max',get_the_ID());
					$expert_idb_fee_percentage = get_field('budget_max',get_the_ID());



					//Get Lead Product
					$wc_query = new WP_Query(array('post_type' => 'product', 'posts_per_page' => -1));
					global $post, $product;
					$lead_product_title='';
					if( $wc_query->have_posts() ) {
						while( $wc_query->have_posts() ) {
						$wc_query->the_post();
							if(get_post_meta(get_the_ID(),'_product_type_meta_key')[0]=='lead'){
								$lead_product_title=get_the_title();
							}
						}
					}wp_reset_postdata();
 
					



					$order_id= get_post_meta($sr_id, 'orderid')[0];


					if($order_id!=''){
						$order = new WC_Order( $order_id );
						$status = $order->get_status();
						//	echo $status;
						if($status == 'trash'){

							$state=pll__('Payment Error');
							$btns= $cl_btn_Pay;

						}elseif($status=='completed'){


							if($sr_is_referral == 1){
							$state=pll__('Payment Completed');
							$btns .= 'btn3'.$cl_btn_View_Lead;
								

							}else{
							$state=pll__('Payment Completed');
							$btns= 'btn4'.'
							<a href="#post-'.$sr_id.'"
							title="'.$lead_product_title.'" 
							class=" btn-blue m-r-5  m-t-15 popUpForm info-modal" style="float:right">'.pll__( '33View Lead' ).'</a>
							'.makeSRLeadModal($sr_id);
							}




						}else{
							/* if(PAY_LEAD_old === 'yes'){
							$state=pll__('Awaiting payment validation');
							$btns= ' 
							<a
							title="" 
							style="background: #ccc !important; color:#fff;"
							class=" btn-blue m-r-5  m-t-15" style="float:right">'.__( 'xxxxView').'</a>';
							} */
						}
					}else{
						
						$user_id = get_current_user_id(); 
						$user_info = get_userdata($user_id);
						$mailadresje = $user_info->user_email;
					
					   
						$args = array(
							'numberposts'	=> 1,
							'post_type'		=> 'expert',
							'meta_query'	=> array(
								'relation'		=> 'AND',
								array(
									'key'	 	=> 'expert_email',
									'value'	  	=> $mailadresje,
								),
					
							),
						);
					
						$query = new WP_Query($args);
					
						$cl_user = $query->posts[0]->ID;
						$cl_member_cat = get_field('member_category_store',$cl_user);

					
						if($cl_member_cat != false){
							
							if ($cl_sr_pay_lead_mode['value']==='sr_pay_before'){
								$state=pll__('Awaiting Purchase');
								/* var_dump('Link:'.getLinkByTemplate('single-referral.php').'?sr='.get_field('request_type',$sr_id)->term_id.'&rid='.$sr_id.'&refer=1'.'<br>'); */
								
								$btns = $cl_btn_Pay;

								$btns .= '
								
								<div class="m-t-10 m-r-5 m-b-10" style="float:right;">
								<a href="'.getLinkByTemplate('single-counseling.php').'?sr='.get_field('request_type',$sr_id)->term_id.'&rid='.$sr_id.'&refer=1" 
								title="'.__( 'Request to Another Member', 'idealbiz-service-request' ).'" 
								class=" btn-blue m-t-5" style="float:right">'.__( '22Request to Another Member', 'idealbiz-service-request' ).'</a>
								</div>

								
								<div class="m-t-10 m-r-5" style="float:right;">
								'.$this->get_cancel_btn(__('44Reject','idealbiz'),$sr_id).'
								</div>
								';

							}
							
							if($cl_sr_pay_lead_mode['value']==='sr_pay_later'){

								$btns = '
								<a href="#post-'.$sr_id.'"
								title="'.__( '_str Utilize this Buttom to View Lead', 'idealbiz-service-request' ).'" 
								class=" btn-blue m-r-5  m-t-5 popUpForm info-modal" style="float:right" onclick="window.location=\''.'?'.$cl_url_part.'&view_lead='.$sr_id.'\';">'.pll__( '_str View Lead Pay After' ).'</a>						
								'.makeSRLeadModal($sr_id);
								
							}

							if($cl_sr_pay_lead_mode['value']==='sr_no_pay'){

								$btns= '
								<a href="#post-'.$sr_id.'"
								title="'.$lead_product_title.'" 
								class=" btn-blue m-r-5  m-t-15 popUpForm info-modal" style="float:right">'.pll__( '33View Lead' ).'</a>
								'.makeSRLeadModal($sr_id);
								
								
							}

						}else{

								$state=pll__('Awaiting Purchase');
								
								$btns= $cl_btn_Pay;
	
							'<div class="m-t-10 m-r-5" style="float:right;">4444
							'.$this->get_cancel_btn(__('55Reject','idealbiz'),$sr_id).'
							</div>
							'; 
						}
					}
					

				}else{

					
					

					
					$order_id= get_post_meta($sr_id, 'orderid')[0];
					$status='';
					if($order_id!=''){
						$order = new WC_Order( $order_id );
						$status = $order->get_status();
					}

					

					if($status=='completed'){
						
						/* $state=pll__('Expert accepted');
						$btns= '<div class=" btn-blue m-r-5  m-t-15 popUpForm info-modal" style="float:right">'.wc_price($earned).'</div>';
						//NPMM CLEVERSON VERIFICAR ESTE CODIGO COMENTADO - Em um pedido de serviço directo após pago a lead exibe o botão de visualizar.
						$state=pll__('Expert accepted');
						$btns= '
						<a href="#post-'.$sr_id.'"
						title="'.$lead_product_title.'" 
						class=" btn-blue m-r-5  m-t-15 popUpForm info-modal" style="float:right">'.pll__( 'View' ).'</a>
						'.makeSRLeadModal($sr_id); */
						
					}else{
						//cl_alerta($state);


					}
					
				}
				

				


						

			}
		}
		
		

		//NPMM - Anula os botões se não for membro.
		if ($_GET['home']==1 ) {
			$expert = isExpert();
			if ( in_array( 'consultant', $current_user->roles, true ) || $expert ) {
			}else{
				$btns = $cl_btn_no_action;
			}
		}	


		$title_saw = '';

		if ($cl_n_view >= 1){
			$title_saw = '<div class="'.$cl_magrTop.' m-r-5"><h5 class="cl_h5"><b>'.__('_STR DATE SAW','idealbiz').'</b><span style="font-size:1.4em;" class="dashicons dashicons-welcome-view-site"></span>'.$view.'</h5></div>';
		}


		if($cl_sr_pay_lead_mode['value']==='sr_pay_later'){

			return sprintf(
				'
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order sr-row">

					<td class="woocommerce-orders-table__cell min-w-100 woocommerce-orders-table__cell-order-number sr-title cl_list_value_border" data-title="Order">		
						<h4 class="listing-list__title title m-t-10">%1$s</h4>	
					</td>

					<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-date cl_list_value_border" data-title="Date">
						<span>%2$s</span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status text-uppercase cl_list_value_border" data-title="Status">
						<span class="state state--active">%3$s</span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status text-center cl_list_value" data-title="Status">
					<span class="state state--active cl_status">%4$s </span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status cl_al_dir" data-title="Status">
					<span class="state state--active cl_status">%5$s </span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-250 woocommerce-orders-table__cell-order-actions sr-actions " style="text-align:right; display:block;" data-title="Actions"><sapn> %6$s </span>
					</td>

				</tr>

				',
				$sr_id.'-'.$sr_title.$h_title,
				//$sr_id.'-'.$sr_title,
				$sr_date,
				//$state.$aux_message,
				$state.$cl_orderID.$cl_rejected,
				$cl_reference_value ,
				$cl_value_Lead,
				$btns.$title_saw
			);


		}else if ($cl_sr_pay_lead_mode['value']==='sr_pay_before'){
			
			return sprintf(
				'
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order sr-row">

					<td class="woocommerce-orders-table__cell min-w-100 woocommerce-orders-table__cell-order-number sr-title cl_list_value_border" data-title="Order">		
						<h4 class="listing-list__title title m-t-10">%1$s</h4>		
					</td>

					<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-date cl_list_value_border" data-title="Date">
						<span>%2$s</span>
					</td>	

					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status text-uppercase cl_list_value_border" data-title="Status">
						<span class="state state--active">%3$s</span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status text-center cl_list_value" data-title="Status">
					<span class="state state--active">%4$s </span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status cl_al_dir" data-title="Status">
					<span class="state state--active ">%5$s </span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-250 woocommerce-orders-table__cell-order-actions sr-actions " style="text-align:right; display:block;" data-title="Actions"> %6$s
					</td>

				</tr>
				',
				$sr_id.'-'.$sr_title.$h_title,
				//$sr_id.'-'.$sr_title,
				$sr_date,
				//$state.$aux_message,
				$state.$cl_orderID.$cl_rejected,
				$cl_reference_value ,
				$cl_value_Lead,
				$btns.$title_saw
			);

		}else{

			//echo get_field('request_type',$sr_id);
			return sprintf(
				'
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order sr-row">

					<td class="woocommerce-orders-table__cell min-w-100 woocommerce-orders-table__cell-order-number sr-title cl_list_value_border" data-title="Order">		
						<h4 class="listing-list__title title m-t-10">%1$s</h4>	
					</td>

					<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-date cl_list_value_border" data-title="Date">
						<span>%2$s</span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status text-uppercase cl_list_value_border" data-title="Status">
						<span class="state state--active ">%3$s </span>
					</td>


					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status text-center cl_list_value" data-title="Status">
					<span class="state state--active">%4$s </span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status cl_al_dir" data-title="Status">
					<span class="state state--active cl_status">%5$s </span>
					</td>

					<td class="woocommerce-orders-table__cell min-w-150 woocommerce-orders-table__cell-order-actions sr-actions " style="text-align:right; display:block;" data-title="Actions"><sapn> %6$s </span>
					</td>

				</tr>

				',
				$sr_id.'-'.$sr_title.$h_title,
				//$sr_id.'-'.$sr_title,
				$sr_date,
				//$state.$aux_message,
				$state.$cl_orderID.$cl_rejected,
				$cl_reference_value ,
				$cl_value_Lead,
				$btns.$title_saw
			);


		}

		

		}


}