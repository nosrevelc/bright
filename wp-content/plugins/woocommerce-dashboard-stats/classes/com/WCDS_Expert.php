<?php 
class WCDS_Expert
{
	public function __construct()
	{
		if(is_admin())
		{
			add_action('wp_ajax_wcds_experts_widget_get_experts_per_period', array(&$this, 'ajax_get_experts_per_period') );
			add_action('wp_ajax_wcds_categories_widget_get_categories_per_period', array(&$this, 'ajax_get_categories_per_period') );
			add_action('wp_ajax_wcds_business_detail_widget_data', array(&$this, 'ajax_get_business_details') );
			add_action('wp_ajax_wcds_referrals_widget_get_referrals_per_period', array(&$this, 'ajax_get_referrals_per_period') );

			

		}
	}


	public function ajax_get_referrals_per_period()
	{

		$experts_num = isset($_POST['expert_num']) ? $_POST['expert_num'] : 10;
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

		global $wpdb;
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == "")) 
		{ 
			$start_date = date('Y-01-01'); 
			$end_date = date('Y-m-t');
		} 			

		$datai = $start_date;
		$dataf = $end_date;
 
		$expert_service_requests= array();
		//get experts post type
		$q_experts = "
		SELECT 
		{$wpdb->posts}.* 
		FROM {$wpdb->posts} 
		WHERE ({$wpdb->posts}.post_type = 'expert'
		AND {$wpdb->posts}.post_status = 'publish')
		ORDER BY {$wpdb->posts}.menu_order ASC";

		$result_experts = $wpdb->get_results($q_experts, ARRAY_A);
		foreach($result_experts as $kq_experts => $expert){
			$array_experts= array();
			
			$user_id= get_user_by( 'email', get_field('expert_email',$expert['ID']))->ID;
			$array_experts['id'] = $expert['ID'];
			$array_experts['name'] = get_the_title($expert['ID']);
			$array_experts['link'] = get_the_permalink($expert['ID']);
			$array_experts['user_email'] = get_field('expert_email',$expert['ID']);
			$array_experts['user_id'] = $user_id;
			$array_experts['datai'] = $datai;
			$array_experts['dataf'] = $dataf;

			
			// foreach expert get service requests in dates
			$q_service_requests="
			SELECT {$wpdb->posts}.* 
			FROM {$wpdb->posts}  
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt2 ON ( {$wpdb->posts}.ID = mt2.post_id )  
			WHERE 1=1  
			AND ( 
				( {$wpdb->postmeta}.meta_key = 'is_referral' AND {$wpdb->postmeta}.meta_value = '1' ) AND
				( mt1.meta_key = 'referral' AND mt1.meta_value = '".$array_experts['user_email']."' ) AND 
				( mt2.meta_key = 'state' AND mt2.meta_value = 'Closed' ) 
			)  
			AND {$wpdb->posts}.post_type = 'service_request' 
			AND {$wpdb->posts}.post_date >= '".$datai."' 
			AND {$wpdb->posts}.post_date <= '".$dataf."' 
			ORDER BY {$wpdb->posts}.post_date ASC 
			";

/*
			SELECT ib_86_posts.* 
			FROM ib_86_posts  
			INNER JOIN ib_86_postmeta ON ( ib_86_posts.ID = ib_86_postmeta.post_id ) 
			INNER JOIN ib_86_postmeta AS mt1 ON ( ib_86_posts.ID = mt1.post_id ) 
			INNER JOIN ib_86_postmeta AS mt2 ON ( ib_86_posts.ID = mt2.post_id ) 
			WHERE 1=1  AND ( 
							( ib_86_postmeta.meta_key = 'is_referral' AND ib_86_postmeta.meta_value = '1' ) 
							AND 
							( mt1.meta_key = 'referral' AND mt1.meta_value = 'idbespecialista_io@sapo.pt' ) 
							AND 
							( mt2.meta_key = 'state' AND mt2.meta_value = 'Closed' )
						) 
					  AND ib_86_posts.post_type = 'service_request' 
					  AND ( 
						 ib_86_posts.post_status = 'publish' OR 
						 ib_86_posts.post_status = 'hidden' OR 
						 ib_86_posts.post_status = 'expired' OR 
						 ib_86_posts.post_status = 'acf-disabled' OR 
						 ib_86_posts.post_status = 'future' OR 
						 ib_86_posts.post_status = 'draft' OR 
						 ib_86_posts.post_status = 'pending' OR 
						 ib_86_posts.post_status = 'expired' OR 
						 ib_86_posts.post_status = 'private'
						 ) 
					  GROUP BY ib_86_posts.ID ORDER BY ib_86_posts.post_date ASC 
*/

			$sum_referral_value=0;
			$number_sr=0;
			$result_service_requests = $wpdb->get_results($q_service_requests, ARRAY_A);
			foreach($result_service_requests as $kq_service_requests => $service_request)
			{
					$referral_value= (int) get_field('earned',$service_request['ID']);
					//echo $array_experts['user_email'].'-'.$referral_value.'<br/>';
					$array_experts['service_request'][$service_request['ID']] = $referral_value;
					$sum_referral_value+=$referral_value;
					$number_sr++;
			}
			if($array_experts['service_request']){
				$array_experts['total_service_requests'] = $sum_referral_value;
				$array_experts['number_sr']= $number_sr;
				$expert_service_requests[] = $array_experts;
			}
		}

		//var_dump($expert_service_requests);
 
		$tsr = array();
		foreach ($expert_service_requests as $key => $row)
		{
			$tsr[$key] = $row['total_service_requests'];
		}
		array_multisort($tsr, SORT_DESC, $expert_service_requests);


		$stats = array_slice($expert_service_requests, 0, $experts_num); 

		//wcds_var_dump($stats);
		/* Format:
			array(2) {
			  [0]=>
			  array(4) {
				["order_total"]=>
				string(4) "15.6"
				["order_num"]=>
				string(1) "2"
				["name"]=>
				string(8) "Domenico"
				["last_name"]=>
				string(6) "Lagudi"
				["customer_id"] =>
				int 1234123
			  }
			 
		
		foreach($stats as $index => $customer)
		{
			$stats[$index]['total_spent'] = round($customer['total_spent'], 2);
			$stats[$index]['permalink'] = $customer['customer_id'] >0 ? get_edit_user_link($customer['customer_id']) : 'none';
		} */
		echo json_encode($stats);
		wp_die();
	}




	public function ajax_get_experts_per_period()
	{


		$experts_num = isset($_POST['expert_num']) ? $_POST['expert_num'] : 10;
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

		global $wpdb;
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == "")) 
		{ 
			$start_date = date('Y-01-01'); 
			$end_date = date('Y-m-t');
		} 			

		$datai = $start_date;
		$dataf = $end_date;
 
		$expert_service_requests= array();
		//get experts post type
		$q_experts = "
		SELECT 
		{$wpdb->posts}.* 
		FROM {$wpdb->posts} 
		WHERE ({$wpdb->posts}.post_type = 'expert'
		AND {$wpdb->posts}.post_status = 'publish')
		ORDER BY {$wpdb->posts}.menu_order ASC";

		$result_experts = $wpdb->get_results($q_experts, ARRAY_A);
		foreach($result_experts as $kq_experts => $expert){
			$array_experts= array();
			
			$user_id= get_user_by( 'email', get_field('expert_email',$expert['ID']))->ID;
			$array_experts['id'] = $expert['ID'];
			$array_experts['name'] = get_the_title($expert['ID']);
			$array_experts['link'] = get_the_permalink($expert['ID']);
			$array_experts['user_email'] = get_field('expert_email',$expert['ID']);
			$array_experts['user_id'] = $user_id;
			$array_experts['datai'] = $datai;
			$array_experts['dataf'] = $dataf;

			// foreach expert get service requests in dates
			$q_service_requests="
			SELECT {$wpdb->posts}.* 
			FROM {$wpdb->posts}  
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt2 ON ( {$wpdb->posts}.ID = mt2.post_id ) 
			WHERE 1=1  
			AND ( 
				( {$wpdb->postmeta}.meta_key = 'consultant' AND {$wpdb->postmeta}.meta_value = '".$user_id."' ) 
				AND ( mt1.meta_key = 'state' AND mt1.meta_value = 'Closed' ) 
				AND ( mt2.meta_key = 'consultant' AND mt2.meta_value != '' ) 
			) 
			AND {$wpdb->posts}.post_type = 'service_request' 
			AND {$wpdb->posts}.post_date >= '".$datai."' 
			AND {$wpdb->posts}.post_date <= '".$dataf."' 
			ORDER BY {$wpdb->posts}.post_date ASC 
			";
			$sum_proposal_value=0;
			$number_sr=0;
			$result_service_requests = $wpdb->get_results($q_service_requests, ARRAY_A);
			foreach($result_service_requests as $kq_service_requests => $service_request)
			{
				$array_service_requests = array();
				$sr_id_aux= 'service_contract_'.$service_request['ID'].'';
				$q_contract =  "
				SELECT {$wpdb->posts}.* 
				FROM {$wpdb->posts}  
				INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ) 
				WHERE 1=1  
				AND ( {$wpdb->postmeta}.meta_key = 'progress' AND {$wpdb->postmeta}.meta_value = 'Closed' )
				AND {$wpdb->posts}.post_type = 'service_contract'
				AND {$wpdb->posts}.post_title LIKE '%".$sr_id_aux."%' 
				GROUP BY {$wpdb->posts}.ID ORDER BY {$wpdb->posts}.post_date DESC";
				//echo $q_contract;
				$result_service_contracts = $wpdb->get_results($q_contract, ARRAY_A);
				foreach($result_service_contracts as $kq_service_contract => $service_contract)
				{
					$proposal_value= (int) get_field('proposal_value',$service_contract['ID']);
					$array_experts['service_request'][$service_contract['ID']] = $proposal_value;
					$sum_proposal_value+=$proposal_value;
					$number_sr++;
				}
			}
			if($array_experts['service_request']){
				$array_experts['total_service_requests'] = $sum_proposal_value;
				$array_experts['number_sr']= $number_sr;
				$expert_service_requests[] = $array_experts;
			}
		}

		$tsr = array();
		foreach ($expert_service_requests as $key => $row)
		{
			$tsr[$key] = $row['total_service_requests'];
		}
		array_multisort($tsr, SORT_DESC, $expert_service_requests);


		$stats = array_slice($expert_service_requests, 0, $experts_num); 

		//wcds_var_dump($stats);
		/* Format:
			array(2) {
			  [0]=>
			  array(4) {
				["order_total"]=>
				string(4) "15.6"
				["order_num"]=>
				string(1) "2"
				["name"]=>
				string(8) "Domenico"
				["last_name"]=>
				string(6) "Lagudi"
				["customer_id"] =>
				int 1234123
			  }
			 
		
		foreach($stats as $index => $customer)
		{
			$stats[$index]['total_spent'] = round($customer['total_spent'], 2);
			$stats[$index]['permalink'] = $customer['customer_id'] >0 ? get_edit_user_link($customer['customer_id']) : 'none';
		} */
		echo json_encode($stats);
		wp_die();
	}






	public function ajax_get_categories_per_period()
	{
		$categories_num = isset($_POST['categories_num']) ? $_POST['categories_num'] : 10;
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

		global $wpdb;
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == "")) 
		{ 
			$start_date = date('Y-01-01'); 
			$end_date = date('Y-m-t');
		} 			


		$datai = $start_date;
		$dataf = $end_date;

		$categories_service_requests= array();

	
		//get experts post type
		$q_service_requests="
		SELECT {$wpdb->posts}.*
		FROM {$wpdb->posts}  
		INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
		INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
		WHERE 1=1  
		AND mt1.meta_key = 'state' 
		AND mt1.meta_value = 'Closed'
		AND {$wpdb->posts}.post_type = 'service_request' 
		AND {$wpdb->posts}.post_date >= '".$datai."' 
		AND {$wpdb->posts}.post_date <= '".$dataf."' 
		GROUP BY {$wpdb->posts}.ID
		ORDER BY {$wpdb->posts}.post_date ASC 
		";
	
		$result_service_requests = $wpdb->get_results($q_service_requests, ARRAY_A);
		foreach($result_service_requests as $kq_service_requests => $service_request){
			$serv_cat = get_field('request_type',$service_request['ID']);
			$serv_cat_id = $serv_cat->term_id;
			$sr_aux_array = array();
			$language='en';
	
			if ( is_plugin_active( 'polylang/polylang.php' ) ) {
				$serv_cat_id = pll_get_term($serv_cat_id, 'en');
				$language = pll_get_term_language($serv_cat_id);
			} 
			
			$categories_service_requests[$serv_cat_id]['name'] = get_term( $serv_cat_id )->name;
			$categories_service_requests[$serv_cat_id]['lang'] = $language;
			
			// get closed contract
			$sc_title_aux = 'service_contract_'.$service_request['ID'];
			$q_service_contract="
			SELECT {$wpdb->posts}.*
			FROM {$wpdb->posts}  
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
			WHERE 1=1  
			AND mt1.meta_key = 'progress' 
			AND mt1.meta_value = 'Closed'
			AND {$wpdb->posts}.post_title LIKE '%".$sc_title_aux."%'
			AND {$wpdb->posts}.post_type = 'service_contract'
			GROUP BY {$wpdb->posts}.ID
			";
			//echo $q_service_contract;
			$result_service_contracts = $wpdb->get_results($q_service_contract, ARRAY_A);
			foreach($result_service_contracts as $kq_service_contract => $service_contract){
				$sr_aux_array['contract_id'] = $service_contract['ID'];
				$sr_aux_array['contract_value']=(float) get_field('proposal_value',$service_contract['ID']);
			}
			
			$i=0;
			$user_consultant = get_field('consultant',$service_request['ID']);
			$user_id = $user_consultant->ID;
			$user_email = $user_consultant->user_email;
	
			//get expert post_type
			$q_expert_post="
			SELECT {$wpdb->posts}.*
			FROM {$wpdb->posts}  
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
			WHERE 1=1  
			AND mt1.meta_key = 'expert_email' 
			AND mt1.meta_value = '".$user_email."'
			GROUP BY {$wpdb->posts}.ID
			";
			//echo  $q_expert_post;
			$result_expert_post = $wpdb->get_results($q_expert_post, ARRAY_A);
			foreach($result_expert_post as $kq_expert_post => $expert_post){
				$expert_id = $expert_post['ID'];
			}
			//get related post in lang
			if ( is_plugin_active( 'polylang/polylang.php' ) ) {
				$expert_id = pll_get_post($expert_id, $language);
			}
			$sr_aux_array['expert_id']= $expert_id;
	
			//get expert and idb earnings
			$idealbiz_part=0;
			if( have_rows('services_royalties',$expert_id) ):
				while( have_rows('services_royalties',$expert_id) ): the_row();
					$service = get_sub_field('service');
					if($serv_cat_id == $service->term_id){
						$idealbiz_part = (($sr_aux_array['contract_value'] * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
						$expert_part = $sr_aux_array['contract_value']-$idealbiz_part;
					}
					$i++;
				endwhile;
			endif; 
			$sr_aux_array['royalties_expert_part']= $expert_part;
			$sr_aux_array['royalties_idealbiz_part']= $idealbiz_part;
								
			$categories_service_requests[$serv_cat_id]['service_requests'][$service_request['ID']]=$sr_aux_array;
		}
	
	
		foreach($categories_service_requests as $k => $csr){
	
			//add number of SR
			$categories_service_requests[$k]['number_sr']=count($csr['service_requests']);
	
			//calculate total amount of SR
			$amount=0;
			foreach($categories_service_requests[$k]["service_requests"] as $j => $csr_lines){
				$amount+=$csr_lines["contract_value"];
			}
			$categories_service_requests[$k]['amount']=$amount;
			
	
			//avg per service REquest
			$categories_service_requests[$k]['avg'] = round($categories_service_requests[$k]['amount']/$categories_service_requests[$k]['number_sr'], 2);
	
	
			//calculate total amount of royalties
			$royals_experts=0;
			$royals_idealbiz=0;
			foreach($categories_service_requests[$k]["service_requests"] as $j => $csr_royals){
				$royals_experts+=$csr_royals["royalties_expert_part"];
				$royals_idealbiz+=$csr_royals["royalties_idealbiz_part"];
			}
			$categories_service_requests[$k]['total_royalties_expert']=$royals_experts;
			$categories_service_requests[$k]['total_royalties_idealbiz']=$royals_idealbiz;
	
	
			//calculate total amount of percentage
			$percentage_expert=0;
			if($royals_experts!=0){
				$percentage_expert = ($royals_experts*100)/$amount;
				$categories_service_requests[$k]['percentage_expert'] = round($percentage_expert, 2);
			}
			$percentage_idealbiz=0;
			if($royals_idealbiz!=0){
				$percentage_idealbiz = ($royals_idealbiz*100)/$amount;
				$categories_service_requests[$k]['percentage_idealbiz'] = round($percentage_idealbiz, 2);
			}
		}


		$tsr = array();
		foreach ($categories_service_requests as $key => $row)
		{
			$tsr[$key] = $row['amount'];
		}
		array_multisort($tsr, SORT_DESC, $categories_service_requests);
		
	
		$stats = array_slice($categories_service_requests, 0, $categories_num); 
	
		echo json_encode($stats);
		wp_die();
	}




	public function ajax_get_business_details()
	{
		global $wpdb;

		$start_date = date('Y-01-01'); 
		$end_date = date('Y-m-t');
		$datai = $start_date;
		$dataf = $end_date;
	
		$business_details= array();
	
	
		//get service_requests closed all time
		$q_service_requests="
		SELECT {$wpdb->posts}.*
		FROM {$wpdb->posts}  
		INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
		INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
		WHERE 1=1  
		AND mt1.meta_key = 'state' 
		AND mt1.meta_value = 'Closed'
		AND {$wpdb->posts}.post_type = 'service_request'
		GROUP BY {$wpdb->posts}.ID
		ORDER BY {$wpdb->posts}.post_date ASC 
		";
		$result_service_requests = $wpdb->get_results($q_service_requests, ARRAY_A);
		$business_details['closed_all_time'] = count($result_service_requests);
	
		// Royalties earned over time
		$idealbiz_part=0;
		foreach($result_service_requests as $kq_service_requests => $service_request){
			$serv_cat = get_field('request_type',$service_request['ID']);
			$serv_cat_id = $serv_cat->term_id;
			$contract_value = 0;
			$language='en';
			if ( is_plugin_active( 'polylang/polylang.php' ) ) {
				$serv_cat_id = pll_get_term($serv_cat_id, 'en');
				$language = pll_get_term_language($serv_cat_id);
			} 
			// get closed contract
			$sc_title_aux = 'service_contract_'.$service_request['ID'];
			$q_service_contract="
			SELECT {$wpdb->posts}.*
			FROM {$wpdb->posts}  
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
			WHERE 1=1  
			AND mt1.meta_key = 'progress' 
			AND mt1.meta_value = 'Closed'
			AND {$wpdb->posts}.post_title LIKE '%".$sc_title_aux."%'
			AND {$wpdb->posts}.post_type = 'service_contract'
			GROUP BY {$wpdb->posts}.ID";
			$result_service_contracts = $wpdb->get_results($q_service_contract, ARRAY_A);
			foreach($result_service_contracts as $kq_service_contract => $service_contract){
				$contract_value=(float) get_field('proposal_value',$service_contract['ID']);
			}
			
			$i=0;
			$user_consultant = get_field('consultant',$service_request['ID']);
			$user_id = $user_consultant->ID;
			$user_email = $user_consultant->user_email;
	
			//get expert post_type
			$q_expert_post="
			SELECT {$wpdb->posts}.*
			FROM {$wpdb->posts}  
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
			WHERE 1=1  
			AND mt1.meta_key = 'expert_email' 
			AND mt1.meta_value = '".$user_email."'
			GROUP BY {$wpdb->posts}.ID
			";
			//echo  $q_expert_post;
			$result_expert_post = $wpdb->get_results($q_expert_post, ARRAY_A);
			foreach($result_expert_post as $kq_expert_post => $expert_post){
				$expert_id = $expert_post['ID'];
			}
			//get related post in lang
			if ( is_plugin_active( 'polylang/polylang.php' ) ) {
				$expert_id = pll_get_post($expert_id, $language);
			}
			//get expert and idb earnings
			if( have_rows('services_royalties',$expert_id) ):
				while( have_rows('services_royalties',$expert_id) ): the_row();
					$service = get_sub_field('service');
					if($serv_cat_id == $service->term_id){
						$idealbiz_part += (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
						//echo $expert_id.'-'.get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0].' - '.$contract_value.' ('.$idealbiz_part .')|';
						//$expert_part = $sr_aux_array['contract_value']-$idealbiz_part;
					}
					$i++;
				endwhile;
			endif; 
		}
		$business_details['royalties_all_time'] = $idealbiz_part;
	
	
	
	
		//get service_requests closed  this year
		$q_service_requests="
		SELECT {$wpdb->posts}.*
		FROM {$wpdb->posts}  
		INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
		INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
		WHERE 1=1  
		AND mt1.meta_key = 'state' 
		AND mt1.meta_value = 'Closed'
		AND {$wpdb->posts}.post_type = 'service_request' 
		AND {$wpdb->posts}.post_date >= '".$datai."' 
		AND {$wpdb->posts}.post_date <= '".$dataf."' 
		GROUP BY {$wpdb->posts}.ID
		ORDER BY {$wpdb->posts}.post_date ASC 
		";
		$result_service_requests = $wpdb->get_results($q_service_requests, ARRAY_A);
		$business_details['closed_this_year'] = count($result_service_requests);
		// Royalties earned this year
		$idealbiz_part=0;
		foreach($result_service_requests as $kq_service_requests => $service_request){
			$serv_cat = get_field('request_type',$service_request['ID']);
			$serv_cat_id = $serv_cat->term_id;
			$contract_value = 0;
			$language='en';
			if ( is_plugin_active( 'polylang/polylang.php' ) ) {
				$serv_cat_id = pll_get_term($serv_cat_id, 'en');
				$language = pll_get_term_language($serv_cat_id);
			} 
			// get closed contract
			$sc_title_aux = 'service_contract_'.$service_request['ID'];
			$q_service_contract="
			SELECT {$wpdb->posts}.*
			FROM {$wpdb->posts}  
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
			WHERE 1=1  
			AND mt1.meta_key = 'progress' 
			AND mt1.meta_value = 'Closed'
			AND {$wpdb->posts}.post_title LIKE '%".$sc_title_aux."%'
			AND {$wpdb->posts}.post_type = 'service_contract'
			GROUP BY {$wpdb->posts}.ID";
			$result_service_contracts = $wpdb->get_results($q_service_contract, ARRAY_A);
			foreach($result_service_contracts as $kq_service_contract => $service_contract){
				$contract_value=(float) get_field('proposal_value',$service_contract['ID']);
			}
			
			$i=0;
			$user_consultant = get_field('consultant',$service_request['ID']);
			$user_id = $user_consultant->ID;
			$user_email = $user_consultant->user_email;
	
			//get expert post_type
			$q_expert_post="
			SELECT {$wpdb->posts}.*
			FROM {$wpdb->posts}  
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
			INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
			WHERE 1=1  
			AND mt1.meta_key = 'expert_email' 
			AND mt1.meta_value = '".$user_email."'
			GROUP BY {$wpdb->posts}.ID
			";
			//echo  $q_expert_post;
			$result_expert_post = $wpdb->get_results($q_expert_post, ARRAY_A);
			foreach($result_expert_post as $kq_expert_post => $expert_post){
				$expert_id = $expert_post['ID'];
			}
			//get related post in lang
			if ( is_plugin_active( 'polylang/polylang.php' ) ) {
				$expert_id = pll_get_post($expert_id, $language);
			}
			//get expert and idb earnings
			if( have_rows('services_royalties',$expert_id) ):
				while( have_rows('services_royalties',$expert_id) ): the_row();
					$service = get_sub_field('service');
					if($serv_cat_id == $service->term_id){
						$idealbiz_part += (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
						//$expert_part = $sr_aux_array['contract_value']-$idealbiz_part;
					}
					$i++;
				endwhile;
			endif; 
		}
		$business_details['royalties_this_year'] = $idealbiz_part;
	
	
	
		//get service_requests in progress
		$q_service_requests="
		SELECT {$wpdb->posts}.*
		FROM {$wpdb->posts}  
		INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
		INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
		WHERE 1=1  
		AND (
			(mt1.meta_key = 'state' AND mt1.meta_value = 'Waiting on Customer') 
			OR (mt1.meta_key = 'state' AND mt1.meta_value = 'Waiting on Consultant') 
			OR (mt1.meta_key = 'state' AND mt1.meta_value = 'Pending Proposal') 
			OR (mt1.meta_key = 'state' AND mt1.meta_value = 'Proposal Rejected') 
		)
		AND {$wpdb->posts}.post_type = 'service_request' 
		GROUP BY {$wpdb->posts}.ID
		ORDER BY {$wpdb->posts}.post_date ASC 
		";
		$result_service_requests = $wpdb->get_results($q_service_requests, ARRAY_A);
		$business_details['in_progress'] = count($result_service_requests);
	
	
	
		//get service_requests rejected over time
		$q_service_requests="
		SELECT {$wpdb->posts}.*
		FROM {$wpdb->posts}  
		INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
		INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
		WHERE 1=1  
		AND mt1.meta_key = 'state' 
		AND mt1.meta_value = 'Rejected'
		AND {$wpdb->posts}.post_type = 'service_request' 
		GROUP BY {$wpdb->posts}.ID
		ORDER BY {$wpdb->posts}.post_date ASC 
		";
		$result_service_requests = $wpdb->get_results($q_service_requests, ARRAY_A);
		$business_details['rejected_all_time'] = count($result_service_requests);
	
		// rejected this year
		$q_service_requests="
		SELECT {$wpdb->posts}.*
		FROM {$wpdb->posts}  
		INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
		INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
		WHERE 1=1  
		AND mt1.meta_key = 'state' 
		AND mt1.meta_value = 'Rejected'
		AND {$wpdb->posts}.post_type = 'service_request' 
		AND {$wpdb->posts}.post_date >= '".$datai."' 
		AND {$wpdb->posts}.post_date <= '".$dataf."' 
		GROUP BY {$wpdb->posts}.ID
		ORDER BY {$wpdb->posts}.post_date ASC 
		";
		$result_service_requests = $wpdb->get_results($q_service_requests, ARRAY_A);
		$business_details['rejected_this_year'] = count($result_service_requests);
	
	
		//get service_requests in progress
		$q_service_requests="
		SELECT {$wpdb->posts}.*
		FROM {$wpdb->posts}  
		INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
		INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
		WHERE 1=1  
		AND (
			(mt1.meta_key = 'state' AND mt1.meta_value = 'Waiting on Customer') 
			OR (mt1.meta_key = 'state' AND mt1.meta_value = 'Waiting on Consultant') 
			OR (mt1.meta_key = 'state' AND mt1.meta_value = 'Pending Proposal') 
			OR (mt1.meta_key = 'state' AND mt1.meta_value = 'Proposal Rejected') 
			OR (mt1.meta_key = 'state' AND mt1.meta_value = 'Closed') 
		)
		AND {$wpdb->posts}.post_type = 'service_request' 
		GROUP BY {$wpdb->posts}.ID
		ORDER BY {$wpdb->posts}.post_date ASC 
		";
		$result_service_requests = $wpdb->get_results($q_service_requests, ARRAY_A);
		$business_details['accepted_all_time'] = count($result_service_requests);
	
		$aceptreject= $business_details['rejected_all_time']+$business_details['accepted_all_time'];
	
		$business_details['aceptance'] = round(($business_details['accepted_all_time']* 100) /$aceptreject,0);
	
	
		echo json_encode($business_details);
		wp_die();
	}




	
}
?>