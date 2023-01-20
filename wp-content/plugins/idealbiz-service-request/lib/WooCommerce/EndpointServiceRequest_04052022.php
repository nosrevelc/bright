<?php



use ACP\Asset\Style;

class EndpointServiceRequest {

	public function render() {
		$actions_col='';
		if(isset($_GET['referrals'])){ 
			$actions_col = '<h2 class="nobr" style="float:right;">'.__( 'Earnings', 'idealbiz-service-request' ).'</h2>';
		}
		printf(
			'
			<?php cl_voltar();?>
			<table class="
			woocommerce-orders-table woocommerce-MyAccount-orders shop_table 
			shop_table_responsive my_account_orders account-orders-table
			block stroke dropshadow p-30 m-b-25 b-r-5 white--background 
			">
				<thead>
					<tr>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><h2 class="nobr">'.__('Service Requests','idealbiz').'</h2></th>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><h2 class="nobr">'.__('Date','idealbiz').'</h2></th>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><h2 class="nobr">'.__('Status','idealbiz').'</h2></th>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions">'.$actions_col.'</th>
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
			'author'    => $current_user->ID,
			'posts_per_page' => -1
		);

		// Check if user is consultant. 
		if (!isset($_GET['referrals']) ) {
			$expert = isExpert(); 

			if ( in_array( 'consultant', $current_user->roles, true ) || $expert ) {
				//var_dump($current_user->roles); 
				//die();
				//echo 'Primeiro IF ';
				if ( in_array( 'consultant', $current_user->roles, true ) && !$expert ) {
					//echo 'SubPrimeiro IF ';
				}else{
					unset( $args['author'] );
					$args['meta_key']   = 'consultant';
					$args['meta_value'] = $current_user->ID;
					//echo 'Else do IF ';
				}
			}

		}

		if ( isset($_GET['referrals']) ) {
			unset( $args['author'] );
			$args['meta_query'] = array(               
				'relation' => 'AND',          
					array(
					'relation' =>'AND',
					'key' => 'is_referral',                
					'value' => '1',         
					'compare' => '='       
					),
					array(
					'relation' =>'AND',
					'key' => 'referral',
					'value' => $current_user->user_email,
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

	private function get_cancel_btn($label,$id = NULL){
		if(!$id){
			$id = get_the_ID();
		}
		return '
		<a href="#reject_porposal'.$id.'" 
						title="'.$label.' Service Request" 
						class=" btn-blue popUpForm  m-t-5 info-modal" style="float:right; opacity: 0.5;">'.$label.'</a>
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
			</div> ';
	}

	private function render_item() {




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


		$state = '';
		$btns = '';
		$bla='123';
		$hide_see=0;
		$current_user = wp_get_current_user();
		switch ( get_field( 'state' ) ) {
			case 'Pending Expert Acceptance':
				$state = __( 'Pending Expert Acceptance', 'idealbiz-service-request' );
					$hide_see=0; 
					if(get_field('consultant',get_the_ID())->ID == $current_user->ID){
						$btns = '
						
						'.$this->get_cancel_btn(__('Reject','idealbiz')).'
						<a href="?accept='.get_the_ID().'" 
						title="Accept Service Request" 
						class=" btn-blue m-r-5  m-t-5" style="float:right">'.__('Accept', 'idealbiz-service-request' ).'</a>
						';
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
					}
					
				break;

			case 'Pending Proposal':
				$state = __( 'Pending Proposal', 'idealbiz-service-request' );
					$btns = ' '.$this->get_cancel_btn(__('Cancel','idealbiz')).' ';
				break;
			case 'Waiting on Customer': 
				$state = __( 'Waiting on Customer', 'idealbiz-service-request' );
				if(get_field('consultant',get_the_ID())->ID == $current_user->ID){
					$btns = ' '.$this->get_cancel_btn(__('Cancel','idealbiz')).' ';
				}
				break;

			case 'Waiting on Consultant':
				$state = __( 'Waiting on Consultant', 'idealbiz-service-request' );
				if(get_field('consultant',get_the_ID())->ID == $current_user->ID){
					$btns = ' '.$this->get_cancel_btn(__('Cancel','idealbiz')).' ';
				}
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

		}
		

		$cl_is_referral = get_field('is_referral',get_the_ID());
		
		
		$aux_message = '';
		if(get_field('aux_message',get_the_ID())){
			if(get_field( 'state' )!='Canceled')
				$aux_message = ' <br/><span class="small"><b>"'.get_field('aux_message',get_the_ID()).'"</b></span>';
		}

		if(get_field('is_referral',get_the_ID())){
			$cl_descri = __('Referral').' #'.get_the_ID();
		}
		//Erro do botão Cinza Parei aqui 02/05/22
		if(!get_field('origin_sr',get_the_ID()) && !get_field('new_sr',get_the_ID()) && !get_field('is_referral',get_the_ID())){
			$cl_descri = __('New').' #'.get_the_ID();
			$cl_new_sr = get_field('new_sr',get_the_ID());
		}
		
		if(get_field('new_sr',get_the_ID())){
			$cl_descri = __('Referenced').' #'.get_the_ID();
			$cl_new_sr = '&#8618; #'.get_field('new_sr',get_the_ID());
		}
		
		
		
		$h_title='';

		$sr_id = get_the_ID();
		$sr_title = get_the_title();
		$sr_date= get_the_date( 'd M Y', $message_query->posts[0] );

		$c_field = get_field('customer',$sr_id);
		// Verifica se é uma referenciação atraves do campo ACF em Service Resques is_referral
		if(get_field('is_referral',$sr_id)){
		$sr_original =  get_field('sr_original',$sr_id);
		$sr_original_descri =  __('Original #').get_field('sr_original',$sr_id).'&#8618; #';
		}else{
			$sr_original ='';
			$sr_original_descri = '';
		}


		$sr_is_referral = get_field('is_referral',$sr_id);
		
		$current_user = wp_get_current_user();
		
			if($c_field->ID.'' != $current_user->ID.''){
				if($c_field->user_email){
					$h_title='<br/><h5 style="font-size:13px;"><span style="color: #777777;">'.$sr_original_descri.$cl_descri.$cl_new_sr .' | '.__('Customer:','idealbiz').'</span> '.$c_field->display_name.'</h5>';

					if(get_field( 'state' ) == 'Rejected'){
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
						if($new_sr!=''){
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






		
		$e_field = get_field('consultant',$sr_id); 
		$current_usere = wp_get_current_user();
		if($e_field->ID.'' != $current_usere->ID.''){
			if($e_field->user_email){
				$h_title='<br/><h5 style="font-size:13px;"><span style="color: #777777;">'.$cl_descri.$cl_origin_sr.$cl_new_sr .' | '.__('Expert:','idealbiz').'</span> '.$e_field->display_name.'</h5>';
// no cliente
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
					$btns = '';
				}
			}
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
				$aux_message= '';
				if($is_expert){


					$bugdet_max= get_field('budget_max',get_the_ID());
					$sr_ID= get_the_ID();
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
 
					$checkout_url =  wc_get_checkout_url() . '?add-to-cart=' . getProductByType('lead').'&sr-lead='.$sr_ID.'&mod=service';

/* 					session_start();
					echo 'valores '.$_SESSION['srid']. ' Modo '.$_SESSION['mod'].'<br>'; */

					$order_id= get_post_meta($sr_ID, 'orderid')[0];


					if($order_id!=''){
						$order = new WC_Order( $order_id );
						$status = $order->get_status();
					//	echo $status;
						if($status == 'trash'){
							$state=pll__('Payment Error');
							$btns= '
							<a href="'.$checkout_url.'" 
							title="'.$lead_product_title.'" 
							class=" btn-blue m-r-5  m-t-15" style="float:right">'.pll__( 'Pay:' ).' '.wc_price(getLeadSRValue($sr_ID)).'</a>
							'.$this->get_cancel_btn(__('Reject','idealbiz'),$sr_ID).'
							';
						}elseif($status=='completed'){


							if($sr_is_referral == 1){
							$state=pll__('Payment Completed');
							$btns= '
							<a href="#post-'.$sr_original.'"
							title="'.$lead_product_title.'" 
							class=" btn-blue m-r-5  m-t-15 popUpForm info-modal" style="float:right">'.pll__( 'View Lead' ).'</a>
							'.makeSRLeadModal($sr_original).' ';
							}else{
							$state=pll__('Payment Completed');
							$btns= '
							<a href="#post-'.$sr_ID.'"
							title="'.$lead_product_title.'" 
							class=" btn-blue m-r-5  m-t-15 popUpForm info-modal" style="float:right">'.pll__( 'View Lead' ).'</a>
							'.makeSRLeadModal($sr_ID).' ';
							}




						}else{
							$state=pll__('Awaiting payment validation');
							$btns= ' 
							<a
							title="" 
							style="background: #ccc !important; color:#fff;"
							class=" btn-blue m-r-5  m-t-15" style="float:right">'.__( 'View').'</a>';
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
						$state=pll__('Awaiting Purchase');
						/* var_dump('Link:'.getLinkByTemplate('single-referral.php').'?sr='.get_field('request_type',$sr_ID)->term_id.'&rid='.$sr_ID.'&refer=1'.'<br>'); */
						$btns= '
						<a href="'.$checkout_url.'" 
						title="'.$lead_product_title.'" 
						class=" btn-blue m-r-5  m-t-15" style="float:right">'.__( 'Pay:').' '.wc_price(getLeadSRValue($sr_ID)).'</a>
						
						<div class="m-t-10 m-r-5 m-b-10" style="float:right;">
						<a href="'.getLinkByTemplate('single-counseling.php').'?sr='.get_field('request_type',$sr_ID)->term_id.'&rid='.$sr_ID.'&refer=1" 
						title="'.__( 'Request to Another Member', 'idealbiz-service-request' ).'" 
						class=" btn-blue m-t-5" style="float:right">'.__( 'Request to Another Member', 'idealbiz-service-request' ).'</a>
						</div>

						
						<div class="m-t-10 m-r-5" style="float:right;">
						'.$this->get_cancel_btn(__('Reject','idealbiz'),$sr_ID).'
						</div>
						';
						}else{
							$state=pll__('Awaiting Purchase');
							/* var_dump('Link:'.getLinkByTemplate('single-referral.php').'?sr='.get_field('request_type',$sr_ID)->term_id.'&rid='.$sr_ID.'&refer=1'.'<br>'); */
							$btns= '
							<a href="'.$checkout_url.'" 
							title="'.$lead_product_title.'" 
							class=" btn-blue m-r-5  m-t-15" style="float:right">'.__( 'Pay:').' '.wc_price(getLeadSRValue($sr_ID)).'</a>
							
							
							
							<div class="m-t-10 m-r-5" style="float:right;">
							'.$this->get_cancel_btn(__('Reject','idealbiz'),$sr_ID).'
							</div>
							'; 
						}
					}
					


				}else{
					$sr_ID= get_the_ID();
					$order_id= get_post_meta($sr_ID, 'orderid')[0];
					$status='';
					if($order_id!=''){
						$order = new WC_Order( $order_id );
						$status = $order->get_status();
					}

					if($status=='completed'){
						
						$state=pll__('Expert accepted');
						$btns= '<div class=" btn-blue m-r-5  m-t-15 popUpForm info-modal" style="float:right">'.wc_price($earned).'</div>';
						
						/* $state=pll__('Expert accepted');
						$btns= '
						<a href="#post-'.$sr_ID.'"
						title="'.$lead_product_title.'" 
						class=" btn-blue m-r-5  m-t-15 popUpForm info-modal" style="float:right">'.pll__( 'View' ).'</a>
						'.wc_price($earned).' '; */

					}/* else{
						$state=pll__('Awaiting Expert Response');
						$btns= '
							<a 
							title="" 
							style="background: #ccc !important; color:#fff;"
							class=" btn-blue m-r-5  m-t-15" style="float:right">'.pll__( 'View' ).'</a>';
					} */
					
				}
				

				


						

			}
		}

		if(isset($_GET['referrals'])){
			
			return sprintf(
				'
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order sr-row">
					<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number sr-title" data-title="Order">		
						<h4 class="listing-list__title title m-t-10">%1$s</h4>
					</td>
					<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-date " data-title="Date">
						<span>%2$s</span>
					</td>	
					<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status " data-title="Status">
						<span class="state state--active">%3$s</span>
					</td>
					<td class="woocommerce-orders-table__cell min-w-290 woocommerce-orders-table__cell-order-actions sr-actions " style="text-align:right; display:block;" data-title="Actions">
						'.$btns.'
					</td>
				</li>
				',
				$sr_title.$h_title,
				$sr_date,
				$state.$aux_message
			);
		}else{

			if(!$cl_new_sr || $cl_is_referral){ 
				/* cl_alerta('1'); */
				return sprintf(
					'
					<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order sr-row">
						<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number sr-title" data-title="Order">		
							<h4 class="listing-list__title title m-t-10">%1$s</h4>
						</td>
						<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-date " data-title="Date">
							<span>%2$s</span>
						</td>	
						<td class="woocommerce-orders-table__cell min-w-155 woocommerce-orders-table__cell-order-status " data-title="Status">
							<span class="state state--active">%3$s</span>
						</td>
						<td class="woocommerce-orders-table__cell min-w-290 woocommerce-orders-table__cell-order-actions sr-actions " style="text-align:right; display:block;" data-title="Actions">
							'.$btns.'
						</td>
					</li>
					',
					$sr_title.$h_title,
					$sr_date,
					$state.$aux_message
				);
			}else{
				/* cl_alerta('2'); */
				return '';
			}
		}
			
	}
}