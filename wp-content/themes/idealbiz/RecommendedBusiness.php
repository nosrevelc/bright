<?php

// Template Name: RecommendedBusiness
get_header();

$expert = isExpert();

//Var_dump($expert);


if (!$_GET['sent']) {
	$cl_mod = 'sent';
}
if (isset($_GET['sent'])) {
	$cl_mod = 'sent';
}
if (isset($_GET['received'])) {
	$cl_mod = 'received';
}

$current_user = wp_get_current_user();
$id_expert = isExpert($current_user->ID);
$check_member = get_field('rb_member_of_recommended_business', $id_expert[0]->ID);

/* var_dump($id_expert); */



$cl_expertDsplayName = $expert[0]->post_title;
$cl_rb_pay_lead_mode = get_field('rb_pay_lead_mode', $id_expert[0]->ID);



if ($cl_rb_pay_lead_mode === NULL) {
	$cl_rb_pay_lead_mode = ['value' => 'rb_pay_before', 'label' => 'Pay Before'];
}
$cl_rb_pay_lead_mode_value = $cl_rb_pay_lead_mode['value'];




if ($check_member !== true) {
	echo '<div class="container">EXIBIR AQUI CONTEUDOI PUBLICITÁRIO CONVIDANDO A SE TORNAR MEMBRO PARA RECOMENDAR NEGÓCIOS</div>';
}


//BOTÕES GERAIS

$cl_viewLead = '<a href="#post-' . $cl_PostId . '" title="' . get_the_title($cl_PostId) . '" class=" btn-blue m-r-5 popUpForm info-modal rounded">' . __('View Lead', 'idealbiz') . '</a>' . makeSRLeadModalRecommended($cl_PostId);

$cl_viewLead_disable = '<div class=" m-r-5  btn-blue rounded btn_disable" >' . __('View Lead', 'idealbiz') . '</div>';

$cl_wait = '<a href="#" class=" btn-blue m-r-5 popUpForm info-modal rounded">' . __('Wait', 'idealbiz') . '</a>';

function confirmDataView($cl_n_view, $cl_magrTop, $view)
{
	$title_saw = '';

	if ($cl_n_view >= 1) {
		$title_saw = '<div class="' . $cl_magrTop . ' m-r-5"><h5 class="cl_h5"><b>' . __('_STR DATE SAW', 'idealbiz') . '</b><span style="font-size:1.4em;" class="dashicons dashicons-welcome-view-site"></span>' . $view . '</h5></div>';
	}

	return $title_saw;
}

function checkout_url($rb_id_porduct_coin, $coins_charge, $cl_PostId)
{
	$checkout_url =  wc_get_checkout_url() . '?add-to-cart=' . $rb_id_porduct_coin . '&quantity=' . $coins_charge . '&listing-recommended=' . $cl_PostId;
	return $checkout_url;
}


function get_reject_btn($label, $id = NULL)
{
	if (!$id) {
		$id = get_the_ID();
	}
	return '
	<a href="#reject_porposal' . $id . '" 
					title="' . $label . ' Recommend opportunity" 
					class=" btn-blue popUpForm info-modal rounded" style="float:right; opacity: 0.5;">' . $label . '</a>
	<div class="popWrapper" id="reject_porposal' . $id . '">
			<div class="popWrapper_screen"></div>
			<div class="iziModal formPopUp">
				<div class="iziModal-wrap" style="height: auto;">
					<div class="iziModal-content" style="padding: 0px;">
						<div class="content generic-form p-b-20 p-r-20 p-l-20"> 
							<button data-izimodal-close="" class="icon-close popUpForm" href="#reject_porposal' . $id . '"></button>
							<div class="clear"></div>
								<form class="gform_wrapper" id="reject_proposal_id' . $id . '" method="post" action="#">
									<input type="hidden" name="proposal_id" value="' . $id . '">
									<input type="hidden" name="reject_opportunity" value="1">
									<div class="acf-label" style="text-align:left;">
									<label for="acf-_post_content"  style="text-align:left;">' . __('Type in your reason', 'idealbiz') . ' <span class="acf-required">*</span></label></div>
									<textarea name="reason" required></textarea>
									<button class="btn-blue rounded" type="submit" value="Submit">' . __('Submit') . '</button><br/>
								</form>
						</div>    
					</div>
				</div>    
			</div>
		</div>';
}

function confirmLeadOpportunity($label, $checkout_url, $id)
{

	return '
	<a href="#confirmLeadOpportunity' . $id . '" 
					title="' . $label . ' Service Request" 
					class=" btn-blue popUpForm info-modal m-t-5 m-r-5 m-b-5" style="float:right; opacity: 0.8;">' . $label . '</a>
	<div class="popWrapper" id="confirmLeadOpportunity' . $id . '">
			<div class="popWrapper_screen"></div>
			<div class="iziModal formPopUp">
				<div class="iziModal-wrap" style="height: auto;">
					<div class="iziModal-content" style="padding: 0px;">
						<div class="content generic-form p-b-20 p-r-20 p-l-20"> 
							<button data-izimodal-close="" class="icon-close popUpForm" href="#confirmLeadOpportunity' . $id . '"></button>
							<div class="clear"></div>
								<form class="gform_wrapper" id="confirm_confirmLeadOpportunity_id' . $id . '" method="post" action="#">
									<input type="hidden" name="confirmLeadOpportunity_id" value="' . $id . '">
									<input type="hidden" name="confirmOpportunity" value="1">
									<input type="hidden" name="checkout_url" value="' . $checkout_url . '">
									<div class="acf-label" style="text-align:left;">
									<label for="acf-_post_content"  style="text-align:left;">' . __('_str Type in your Comment', 'idealbiz') . ' <span class="acf-required">*</span></label></div>
									<textarea name="comment" required></textarea>
									<button class=" btn-blue" type="submit" value="Submit">' . __('_str Confirm Lead') . '</button><br/>
								</form>
						</div>    
					</div>
				</div>    
			</div>
		</div>';
}



if ($cl_mod === 'sent' && $check_member === true) {



	//NPMM - A Cosnulta Abixo estava gerando erro na exibição pois estava buscando o Autor que em algum momento mudava.
	/* 	$current_user = wp_get_current_user();
		$args_rb = array(
			'post_type' => 'recommended_business',
			'author'    => $current_user->ID,
			'posts_per_page' => -1


		);

		$loop = New Wp_Query($args_rb); */


	$current_user = wp_get_current_user();
	$email_membro = $current_user->user_email;
	$id_expert = isExpert($current_user->ID);

	$expert = isExpert();
	$cl_id_membro = $expert[0]->ID;

	$args = (array(
		'post_type' => 'recommended_business',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => 'rb_id_member_indicate',
				'value' => $id_expert[0]->ID,
				'compare' => '='   // or if you want like then use 'compare' => 'LIKE'
			)
		)
	)
	);

	$postData = new WP_Query($args);

	$dados = (array) $postData;

	$loop = new Wp_Query($args);





	//var_dump($loop);

?>

	<div class="container titulo_pagina base_color--color pull-left p-b-15 text-primary font-weight-bold">
		<?php do_action('goBack', -1, '_str Back'); ?>
		<?php echo  '<span class="container text-center m-b-30"><h3>' . __('_str Recommendations Business-Sent', 'idealbiz') . '</h3></span>'; ?>

	</div>



	<div class="container">


		<div class="rb_botoes">

			<?php do_action('botao_lsitingRecomendaveis', 'received', true); ?>

		</div>

		<?php $cl_consultLeadModeRecomendation = consultLeadModeRecomendation($expert[0]->ID); ?>

		<div class="woocommerce w-100">
			<section>
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table 
					shop_table_responsive my_account_orders account-orders-table
					block stroke dropshadow p-30 m-b-25 b-r-5 white--background">
					<thead>
					<tr>
						<th>
							<h2 class="nobr"><?php echo __('strDate', 'idealbiz') ?></h2>
						</th>
						<!-- <th ><h2 class="nobr"><?php echo __('strOwner', 'idealbiz') ?></h2></th> -->
						<th>
							<h2 class="nobr"><?php echo __('strTitle', 'idealbiz') ?></h2>
						</th>

						<th>
							<h2 class="nobr"><?php echo __('strListingPrice', 'idealbiz') ?></h2>
						</th>
						<!-- <th ><h2 class="nobr"><?php echo __('strNumberOrder', 'idealbiz') ?></h2></th> -->
						<th>
							<h2 class="nobr"><?php echo __('strStatusOrder', 'idealbiz') ?></h2>
						</th>
						<th>
							<h2 class="nobr"><?php echo __('strComission', 'idealbiz') ?></h2>
						</th>
						<!-- <th ><h2 class="nobr"><?php echo __('strAction', 'idealbiz') ?></h2></th> -->

					</tr>
					</thead>
					<tbody>
					<?php



					while ($loop->have_posts()) {

						$loop->the_post();

						$cl_PostId = $post->ID;
						$cl_date = get_the_date('d M Y', $post->ID);
						$cl_time = get_post_time("G:i");
						$cl_symbol = get_woocommerce_currency_symbol(get_field('rb_currency', $cl_PostId));
						$cl_listingPrice = get_field('rb_listing_price', $cl_PostId);
						$cl_rb_name_owner_of_listng = get_field('rb_name_owner_of_listng', $cl_PostId);
						$cl_rb_commission_calculated = (float)get_field('rb_commission_calculated');
						$cl_comisOk = '';
						$cl_rb_number_order = get_field('rb_number_order', $cl_PostId);
						$cl_rb_link_of_listing = get_field('rb_link_of_listing', $cl_PostId);
						$cl_get_Title = get_the_title($cl_PostId);
						$rb_status_order =  get_field('rb_status_order', $cl_PostId);
						$state = ''.__( 'Rejected', 'idealbiz' ).''.get_field('rejected').''; 



						
						$rb_rejectReason = get_field('rb_reject_reason');
						$rb_rejectDate =  get_field('rb_reject_date', $cl_PostId);
						$rb_statusReject =  '<div><h5 class="cl_h5 m-r-5">' . __('_STR REJECT DATE', 'idealbiz') . ': ' . $rb_rejectDate .$rb_rejectReason. '</h5></div>';
						
						$cl_recipient = '<div><h5 class="cl_h5 m-r-5"><span style="font-size:1.4em;" class="dashicons dashicons-upload"></span>' . __('_STR RECIPENT', 'idealbiz') . ': ' . $cl_rb_name_owner_of_listng . '</h5></div>';

						$cl_orderID = '';
						if ($rb_status_order != "") {
							$cl_orderID = '<div><h5 class="cl_h5 m-r-5">' . __('_STR ORDER ID', 'idealbiz') . ' ' . $cl_rb_number_order . '</h5></div>';
						}

						//var_dump($cl_rb_pay_lead_mode_value);

						if ($cl_rb_pay_lead_mode_value === 'rb_pay_before') {
							if ($rb_status_order == 'on-hold') {
								$cl_PrintStatus = __('str Wait Confirmation', 'idealbizio');
								$cl_color_font = 'text-warning text-uppercase font-weight-bold';
								$botao = $cl_wait;
							}
							if ($rb_status_order == 'pending' || $rb_status_order == Null) {
								$cl_PrintStatus = __('str Waiting For Member to Pay', 'idealbizio');
								$cl_color_font = 'text-danger text-uppercase font-weight-bold';
								$botao = '';
							}
							if ($rb_status_order == 'completed') {
								$cl_PrintStatus = __('str Available Commission', 'idealbizio');
								$cl_color_font = 'text-success text-uppercase font-weight-bold';
								$botao = __('Lead Released', 'idealbiz');
								$cl_comisOk = 'cl_status';
							}

							if ($rb_rejectDate != NULL || $rb_rejectDate != "") {

								$cl_PrintStatus  = __('_str Reject', 'idealbizio');
								$cl_PrintStatus .= $rb_statusReject;
								$cl_color_font   = 'text-danger text-uppercase font-weight-bold';
								$botao = __('Lead Released', 'idealbiz');
							}
						}




						if ($cl_rb_pay_lead_mode_value === 'rb_pay_later') {

							$cl_comisOk = '';
							$cl_Confirmed_Lead 	=  '<span style="color:#005882">' . __('_str Indication of Completed Business', 'idealbiz-service-request') . '</span>';
							$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5 m-b-0">' . __('_STR IN', 'idealbiz') . ': ' . get_field('rb_confirmation_date', $cl_PostId) . '</h5></div>';
							$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5">' . get_field('rb_confirmed', $cl_PostId) . ':</h5></div>';


							if ($rb_status_order == 'on-hold') {
								$cl_color_font = 'text-warning text-uppercase font-weight-bold'; //STATUS COLOR
								$cl_PrintStatus  =  $cl_Confirmed_Lead;
								$cl_PrintStatus .= __('str Wait Confirmation', 'idealbizio');

								$cl_magrTop = 'm-t-5';
							}


							if ($rb_status_order == 'completed') {
								$cl_PrintStatus = __('_str Completed Payment', 'idealbiz-service-request');
								$cl_color_font = 'text-success text-uppercase font-weight-bold'; //STATUS COLOR

								$cl_magrTop = 'm-t-20';
								$cl_comisOk = 'cl_status';
								$botao .= confirmDataView($cl_n_view, $cl_magrTop, $view);
							}

							if ($rb_status_order == '' || $rb_status_order == null) {
								if ($cl_rb_confirmation_date == '' || $cl_rb_confirmation_date == null) {
									$cl_color_font   = 'text-success text-uppercase font-weight-bold';
									$cl_PrintStatus  =  __('_str View of the Released Lead', 'idealbiz');
								} else {
									$cl_color_font   = 'text-info text-uppercase font-weight-bold';
									$cl_PrintStatus  =  $cl_Confirmed_Lead;

									if ($cl_rb_number_order == '' || $cl_rb_number_order == null) {
										$cl_color_font   = 'text-warning text-uppercase font-weight-bold';
										$cl_PrintStatus .= __('_str Awaiting payment', 'idealbiz-service-request');
									}
								}
							}
						}





						if ($cl_rb_pay_lead_mode_value === 'rb_not_pay') {
							if ($rb_status_order == 'on-hold') {
								//FALTA BOTÕES E STATUS
							}
							if ($rb_status_order == 'pending' or $rb_status_order == Null) {
								//FALTA BOTÕES E STATUS
							}
							if ($rb_status_order == 'completed') {
								//FALTA BOTÕES E STATUS
							}
							$cl_orderID = '';
							$cl_PrintStatus  =  __('_str View of the Released Lead', 'idealbiz');
							$cl_color_font   = 'text-success text-uppercase font-weight-bold';
						}


					?>
						<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order sr-row">
						<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number cl_center" data-title="Date"><span class="cl_time"><?php echo $cl_date . ' ' . $cl_time ?></span></td>
							<!-- <td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number sr-title"><?php echo $cl_rb_name_owner_of_listng; ?></td> -->
							<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number rb-title-list" data-title="Opportunity"><?php echo '<a href="' . $cl_rb_link_of_listing . '">' . $cl_get_Title . $cl_recipient . '</a>' ?></td>


							<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number cl_center" data-title="Price"><?php echo $cl_listingPrice . $cl_symbol; ?></td>
							<!-- <td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number sr-title"><?php echo $cl_rb_number_order; ?></td> -->
							<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number cl_center <?php echo $cl_color_font ?>;" data-title="Status"><?php echo $cl_PrintStatus . $cl_orderID; ?></td>
							<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number cl_center <?php echo $cl_comisOk ?>" data-title="To Receive"><?php echo $cl_rb_commission_calculated . $cl_symbol; ?></td>



							<!-- <td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number sr-title"><?php echo $botao; ?></td> -->

						</tr>
					<?php

					} ?>
					</tbody>
				</table>
			</section>
		</div>
	</div>
	</div>


<?php
}
?>
<?php

if ($cl_mod === 'received' && $check_member === true) {

	$current_user = wp_get_current_user();
	$email_membro = $current_user->user_email;
	$id_expert = isExpert($current_user->ID);

	$expert = isExpert();
	$cl_id_membro = $expert[0]->ID;

	$args = (array(
		'post_type' => 'recommended_business',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => 'rb_id_owner_of_listing',
				'value' => $id_expert[0]->ID,
				'compare' => '='   // or if you want like then use 'compare' => 'LIKE'
			)
		)
	)
	);

	$postData = new WP_Query($args);

	$dados = (array) $postData;

	$loop = new Wp_Query($args);



	//var_dump($cl_rb_pay_lead_mode_value);



 ?>

	<div class="container titulo_pagina base_color--color pull-left p-b-15 text-success font-weight-bold">
		<?php do_action('goBack', -1, '_str Back'); ?>
		<?php echo  '<span class="container text-center m-b-30"><h3>' . __('_str Recommendations Business-Received', 'idealbiz') . '</h3></span>'; ?>

	</div>
	<div class="container">


		<div class="rb_botoes">

			<?php do_action('botao_lsitingRecomendaveis', 'sent', true); ?>

		</div>
		<?php $cl_consultLeadModeRecomendation = consultLeadModeRecomendation($expert[0]->ID); ?>
		<div class="woocommerce w-100">
			<section>
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table 
					shop_table_responsive my_account_orders account-orders-table
					block stroke dropshadow p-30 m-b-25 b-r-5 white--background">
					<thead>
					<tr>
						<th>
							<h2 class="nobr"><?php echo __('strDate', 'idealbiz') ?></h2>
						</th>
						<!-- <th ><h2 class="nobr"><?php echo __('strOwner', 'idealbiz') ?></h2></th> -->
						<th>
							<h2 class="nobr"><?php echo __('strTitle', 'idealbiz') ?></h2>
						</th>

						<!-- <th ><h2 class="nobr"><?php echo __('strNumberOrder', 'idealbiz') ?></h2></th> -->

						<th>
							<h2 class="nobr"><?php echo __('strStatusOrder', 'idealbiz') ?></h2>
						</th>

						<th>
							<h2 class="nobr"><?php echo __('strListingPrice', 'idealbiz') ?></h2>
						</th>
						<?php 
							if($cl_rb_pay_lead_mode_value != 'rb_not_pay') { 
						?>
							<th>
								<h2 class="nobr"><?php echo __('strValueCharge', 'idealbiz') ?></h2>
							</th>
						<?php } ?>
						<th>
							<h2 class="nobr"><?php echo __('strAction', 'idealbiz') ?></h2>
						</th>
					</tr>
					<thead>
					<tbody>	
					<?php
					while ($loop->have_posts()) {

						$loop->the_post();

						$coins_charge = (int)get_field('rb_value_charge_coins');
						$rb_id_porduct_coin = get_field('rb_id_porduct_coin', 'option');
						$cl_PostId = $post->ID;
						$checkout_url =  checkout_url($rb_id_porduct_coin, $coins_charge, $cl_PostId);
						$cl_date = get_the_date('d M Y', $post->ID);
						$cl_time = get_post_time("G:i");
						$cl_symbol = get_woocommerce_currency_symbol(get_field('rb_currency', $cl_PostId));
						$cl_separator_decimal = wc_get_price_decimal_separator();
						$cl_decimal = wc_get_price_decimals();
						$cl_priceSeparator = wc_get_price_thousand_separator();
						$cl_rb_gross_commission = get_field('rb_gross_commission', $cl_PostId);
						$cl_listingPrice = get_field('rb_listing_price', $cl_PostId);
						$cl_rb_name_owner_of_listng = get_field('rb_name_owner_of_listng', $cl_PostId);
						$cl_rb_name_member_indicate = get_field('rb_name_member_indicate', $cl_PostId);
						$cl_rb_number_order = get_field('rb_number_order', $cl_PostId);
						$rb_status_order =  get_field('rb_status_order');
						$cl_titleopportunitye = get_the_title($cl_PostId);
						$cl_url_part = 'received=1';
						$cl_rb_confirmation_date = get_field('rb_confirmation_date', $cl_PostId);



						$view = '';
						$cl_n_view = 0;
						$cl_sr_view_lead = get_field('rb_view_lead', $cl_PostId);
						foreach ($cl_sr_view_lead as $viewLead) {
							if ($viewLead['rb_id_member_saw_lead'] === $cl_id_membro) {
								$cl_n_view++;
								$view .= '<p class="cl_p"><span><b>' . $cl_n_view . '</b></span>→' . $viewLead['rb_date_saw_lead'] . '<span style="font-size:1.4em;" class="dashicons dashicons-visibility"></span></p>';
							}
						}

						if ($_GET['view_lead']) {

							$cl_id_view_lead_opportunity = $_GET['view_lead'];
							opportunityRegisterViewLead($cl_id_view_lead_opportunity);
						}

						$cl_orderID = '';
						if ($rb_status_order != "") {
							$cl_orderID = '<div><h5 class="cl_h5 m-r-5">' . __('_STR ORDER ID', 'idealbiz') . ' ' . $cl_rb_number_order . '</h5></div>';
						}

						$cl_source = '<div><h5 class="cl_h5 m-r-5"><span style="font-size:1.4em;" class="dashicons dashicons-migrate"></span>' . __('_STR SOURCE', 'idealbiz') . ': ' . $cl_rb_name_member_indicate . '</h5></div>';

						//BOTÕES RECEVED

						$cl_Pay =  '<a href="' . $checkout_url . '" class=" btn-blue m-r-5 m-t-15 rounded">' . __('_str Pay', 'idealbiz') . '</a>';
						$cl_Pay_Wait_Pay =  '<a href="' . $checkout_url . '" class=" btn-blue m-r-5 m-t-15 rounded">' . __('_str Awaiting payment') . '</a>';
						$cl_viewLead = '<a href="#post-' . $cl_PostId . '" title="' . get_the_title($cl_PostId) . '" class=" btn-blue m-r-5  m-t-15 popUpForm info-modal rounded">' . __('View Lead', 'idealbiz') . '</a>' . makeSRLeadModalRecommended($cl_PostId);

						$cl_viewLeadPayLater = '
						<a href="#post-' . $cl_PostId . '"
						title="' . __('_str Utilize this Buttom to View Lead', 'idealbiz-service-request') . '" 
						class=" btn-blue m-r-5  m-t-5 popUpForm info-modal" style="float:right" onclick="window.location=\'' . '?' . $cl_url_part . '&view_lead=' . $cl_PostId . '\';">' . pll__('_str View Lead Pay Later') . '</a>						
						' . makeSRLeadModalRecommended($cl_PostId);

						$cl_btn_Reject_ID = '<div class=" m-r-5 m-b-5" style="float:right;">' . get_reject_btn(__('Reject', 'idealbiz'), $cl_PostId) . '</div>';

						$cl_btn_confirmLeadOpportunity = ' ' . confirmLeadOpportunity(__('_str Confirm Deal', 'idealbiz'), $checkout_url, $cl_PostId) . ' ';

						//var_dump($cl_rb_pay_lead_mode_value);

						if ($cl_rb_pay_lead_mode_value === 'rb_pay_before') {
							if ($rb_status_order == 'on-hold') {
								$cl_PrintStatus = __('str Wait Confirmation', 'idealbizio');
								$cl_color_font = 'text-warning text-uppercase font-weight-bold'; //STATUS COLOR
								$botao = $cl_viewLead_disable;
							}
							if ($rb_status_order == 'pending' || $rb_status_order == Null) {
								$cl_PrintStatus = __('str Pending Payment', 'idealbizio');
								$cl_color_font = 'text-danger text-uppercase font-weight-bold';
								$botao = $cl_Pay . $cl_btn_Reject_ID;
								$cl_comisOk = '';
							}
							if ($rb_status_order == 'completed') {
								$cl_PrintStatus = __('str Order Completed', 'idealbizio');
								$cl_color_font = 'text-success text-uppercase font-weight-bold'; //STATUS COLOR
								$botao = $cl_viewLead;
								$cl_comisOk = 'cl_status';
							}

							if ($rb_status_order == '' || $rb_status_order == null) {
								$botao = $cl_Pay . $cl_btn_Reject_ID;
							}
						}

						if ($cl_rb_pay_lead_mode_value === 'rb_pay_later') {

							$cl_comisOk = '';
							$cl_Confirmed_Lead 	=  '<span style="color:#005882">' . __('_str Indication of Completed Business', 'idealbiz-service-request') . '</span>';
							$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5 m-b-0">' . __('_STR IN', 'idealbiz') . ': ' . get_field('rb_confirmation_date', $cl_PostId) . '</h5></div>';
							$cl_Confirmed_Lead .= '<div><h5 class="cl_h5 m-r-5">' . get_field('rb_confirmed', $cl_PostId) . ':</h5></div>';


							if ($rb_status_order == 'on-hold') {
								$cl_color_font = 'text-warning text-uppercase font-weight-bold'; //STATUS COLOR
								$cl_PrintStatus  =  $cl_Confirmed_Lead;
								$cl_PrintStatus .= __('str Wait Confirmation', 'idealbizio');
								$botao = $cl_viewLead;
								$cl_magrTop = 'm-t-5';
								$botao .= confirmDataView($cl_n_view, $cl_magrTop, $view);
							}


							if ($rb_status_order == 'completed') {
								$cl_PrintStatus ='<span style="color:#08A008"><b>' . __('_str Completed Payment', 'idealbiz-service-request').'</b></span>';
								$cl_color_font = 'text-success text-uppercase font-weight-bold'; //STATUS COLOR
								$botao = $cl_viewLead;
								$cl_magrTop = 'm-t-20';
								$cl_comisOk = 'cl_status';
								$botao .= confirmDataView($cl_n_view, $cl_magrTop, $view);
							}

							if ($rb_status_order == '' || $rb_status_order == null) {
								if ($cl_rb_confirmation_date == '' || $cl_rb_confirmation_date == null) {
									$cl_color_font   = 'text-success text-uppercase font-weight-bold';
									$cl_PrintStatus  =  '<span style="color:#0868A0">' .__('_str View of the Released Lead', 'idealbiz').'</span>';
									$botao = $cl_viewLeadPayLater . $cl_btn_confirmLeadOpportunity . $cl_btn_Reject_ID;
									$botao .= $title_saw;
									$cl_magrTop = 'm-t-110';
									$botao .= confirmDataView($cl_n_view, $cl_magrTop, $view);
								} else {

									$cl_color_font   = 'text-info text-uppercase font-weight-bold';
									$cl_PrintStatus  =  $cl_Confirmed_Lead;
									$cl_magrTop = 'm-t-5';
									$botao = $cl_Pay_Wait_Pay;
									if ($cl_rb_number_order == '' || $cl_rb_number_order == null) {
										$cl_color_font   = 'text-warning text-uppercase font-weight-bold';
										$cl_PrintStatus .= __('_str Awaiting payment', 'idealbiz-service-request');
									}

									$botao .= confirmDataView($cl_n_view, $cl_magrTop, $view);
								}
							}
						}
						if ($cl_rb_pay_lead_mode_value === 'rb_not_pay') {
							if ($rb_status_order == 'on-hold') {
								//FALTA BOTÕES E STATUS
							}
							if ($rb_status_order == 'pending' or $rb_status_order == Null) {
								//FALTA BOTÕES E STATUS
							}
							if ($rb_status_order == 'completed') {
								//FALTA BOTÕES E STATUS
							}
							$cl_orderID = '';
							$cl_PrintStatus  =  __('_str View of the Released Lead', 'idealbiz');
							$cl_color_font   = 'text-success text-uppercase font-weight-bold';
							$botao = $cl_viewLead;
							$botao .= $cl_btn_Reject_ID;
						}

					 ?>

						<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order sr-row">
							<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number cl_center" data-title="Date"><?php echo $cl_date . ' ' . $cl_time ?></td>
							<!-- <td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number sr-title"><?php echo $cl_rb_name_owner_of_listng ?></td> -->
							<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number rb-title-list" data-title="Opportunity"><?php echo '<a href="' . get_field('rb_link_of_listing', $cl_PostId) . '">' . $cl_titleopportunitye . $cl_source . '</a>' ?></td>

							<!-- <td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number sr-title"><?php echo $cl_rb_number_order; ?></td> -->

							<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number cl_center" data-title="Status"><?php echo $cl_PrintStatus . $cl_orderID; ?></td>

							<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number cl_center" data-title="Value"><?php echo $cl_listingPrice . $cl_symbol; ?></td>
							<?php if ($cl_rb_pay_lead_mode_value != 'rb_not_pay') { ?>
								<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number cl_center <?php echo  $cl_comisOk; ?>" data-title="Price">
								<?php echo $cl_rb_gross_commission . $cl_symbol; ?></td>
							<?php } ?>

							<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-order-number sr-title text-right cl_botao"><?php echo $botao; ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</section>
		</div>
	</div>
	</div>


<?php

}
?>


<?php get_footer(); ?>

<style>
	.woocommerce table.shop_table {

    border-collapse: collapse;

}
	.woocommerce table.shop_table td, .woocommerce table.shop_table th{
		width: 0% !important;
		margin:0 auto;

	}
	.cl_center{
		text-align:center;
	}
	.cl_p {
		margin-bottom: 0em;
	}

	.rb-title-list {
		text-align: left !important;
	}

	.cl_h5 {
		padding-top: 3px;

		font-size: 0.770em;
		text-transform: uppercase;
		color: #777777;
	}

	.btn_disable {

		background-color: #cccccc;
		color: #ffffff;
		padding: 7px 15px;
	}

	.btn_disable :hover {
		color: red;
	}

	.cl_status {
		color: #28A746;
		font-size: 1.2em;
		font-weight: 600;
		background-color: #28A7461C;
		border-radius: 0px;
	}


	h3 {
		font-size: 3em;
		font-family: var(--font-default), sans-serif;
		color: #005882;
	}

	.nobr {
		font-weight: 500;
		font-size: 1.2em;
		text-align:center;
		margin: 30px;
		font-weight: bold;
		color:#777777;
	}

	.rb_conteudo {
		border: 1px solid hsla(0, 0%, 86%, .5);
		width: 100%;

	}

	.cl_time {
		font-size: 0.95em;
	}

	.botoes a {
		margin-right: 3px;
	}

	.ativo {

		background-color: #005882;
		border-radius: 15px;
		color: #fbfbfb;
		padding: 5px 20px 5px 20px;
	}

	.titulo_pagina h2 {

		font-size: 1.5em;
		padding-top: 20px;
	}

	.conteudo {
		margin: 0 auto;
	}

	th {
		padding: 20px 15px 15px 15px;
	}

	tr {

		margin-bottom: 3px;
	}

	td {
		/* padding: 10px 10px 10px 5px; */
		border:  1px solid rgba(0, 0, 0, .1);
		padding: 7px;
	}

	tr:hover {
		color: #005882;
		background-color: #fbfbfb;
	}

	.btn-blue {
		margin-top: 3px !important;
		border-radius: 5px;
	}

	/*botões quadrados*/

	/**ATENÇÃO CSS MOBILE */

	@media only screen and (max-width: 768px) {
		td {
		/* padding: 10px 10px 10px 5px; */
		border:  0px solid rgba(0, 0, 0, .1);
		padding: 7px;
	}
		.cl_botao{
			padding-bottom: 85px !important;
		}
		.woocommerce table.shop_table td, .woocommerce table.shop_table th{
		width: 100% !important;
	}
		.rb_botoes {
			margin-bottom: 5px;
			flex-direction: column;

		}
	}

	a:hover {
		text-decoration: none !important;
	}

	.rb_botoes a {
		width: 100%;
		padding: 3px;
	}

	.rb_botoes {
		margin-bottom: 5px;
		display: flex;
		justify-content: space-between;
	}

	.bota_quadrado {

		height: 70px;
		padding: 10px;
		background-color: #ffffff;
		margin-bottom: 7px;
	}

	.bota_quadrado .dashicons {
		font-size: 50px;
		margin: 0px 6px;
	}

	.bota_quadrado p {
		margin-top: -18px !important;
		margin-left: 70px;
		font-size: 1.15em;
	}
</style>