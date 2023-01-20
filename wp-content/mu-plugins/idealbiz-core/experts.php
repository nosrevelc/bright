<?php

add_theme_support('post-thumbnails');
add_post_type_support( 'expert', 'thumbnail' );  

// Register Custom Post Type - Expert
function expert() {
	$labels = array(
		'name'                  => _x( 'Experts', 'Post Type General Name', 'idealbiz' ),
		'singular_name'         => _x( 'Members', 'Post Type Singular Name', 'idealbiz' ),
		'menu_name'             => __( 'Members', 'idealbiz' ),
		'name_admin_bar'        => __( 'Members', 'idealbiz' ),
		'archives'              => __( 'Item Archives', 'idealbiz' ),
		'attributes'            => __( 'Item Attributes', 'idealbiz' ),
		'parent_item_colon'     => __( 'Parent Item:', 'idealbiz' ),
		'all_items'             => __( 'All Items', 'idealbiz' ),
		'add_new_item'          => __( 'Add New Item', 'idealbiz' ),
		'add_new'               => __( 'Add New Member', 'idealbiz' ),
		'new_item'              => __( 'New Item', 'idealbiz' ),
		'edit_item'             => __( 'Edit Item', 'idealbiz' ),
		'update_item'           => __( 'Update Item', 'idealbiz' ),
		'view_item'             => __( 'View Item', 'idealbiz' ),
		'view_items'            => __( 'View Items', 'idealbiz' ),
		'search_items'          => __( 'Search Item', 'idealbiz' ),
		'not_found'             => __( 'Not found', 'idealbiz' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'idealbiz' ),
		'featured_image'        => __( 'Featured Image', 'idealbiz' ),
		'set_featured_image'    => __( 'Set featured image', 'idealbiz' ),
		'remove_featured_image' => __( 'Remove featured image', 'idealbiz' ),
		'use_featured_image'    => __( 'Use as featured image', 'idealbiz' ),
		'insert_into_item'      => __( 'Insert into item', 'idealbiz' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'idealbiz' ),
		'items_list'            => __( 'Items list', 'idealbiz' ),
		'items_list_navigation' => __( 'Items list navigation', 'idealbiz' ),
		'filter_items_list'     => __( 'Filter items list', 'idealbiz' ),
	);
	$args = array(
		'label'                 => __( 'Expert', 'idealbiz' ),
		'description'           => __( 'Expert Post type', 'idealbiz' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail','comments' ),
		'taxonomies'            => array( 'location', 'service_cat' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-media-text',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'expert', $args );

}

/* init post types and taxonomies */
add_action( 'init', 'expert', 0 );
add_action( 'init', 'service_cat_taxonomy', 2 );



add_filter( 'rest_prepare_taxonomy', function( $response, $taxonomy ){
	if ( 'service_cat' === $taxonomy->name ||  'location' === $taxonomy->name) {
		$response->data['visibility']['show_ui'] = false;
	}
	return $response;
}, 10, 2 );


/* images in post type */
add_theme_support('post-thumbnails');
add_post_type_support( 'expert', 'thumbnail' );

add_filter( 'wp_dropdown_users_args', 'add_subscribers_to_dropdown', 10, 2 );
function add_subscribers_to_dropdown( $query_args, $r ) {

    $query_args['who'] = '';
    return $query_args;

}




if(isset($_GET['dashboard'])){
	if($_GET['dashboard']=='royalties'){



	add_filter('manage_expert_posts_columns', 'hs_expert_table_head');
	function hs_expert_table_head( $columns ) {
		$columns['completed_sr']  = __('Completed SR','idealbiz');
		$columns['total_invoices']  = __('Total Invoices','idealbiz');
		$columns['total_royalties']  = __('Expert Payment','idealbiz');
		$columns['total_idealbiz']  = __('IdealBiz Royalty','idealbiz');
		return $columns;
	}


	add_filter( 'manage_edit-expert_sortable_columns', 'my_sortable_expert_column' );
	function my_sortable_expert_column( $columns ) {
		$columns['completed_sr'] = 'o_completed_sr';
		//$columns['total_invoices'] = 'o_total_invoices';
		//$columns['total_royalties'] = 'o_total_royalties';
	
		//To make a column 'un-sortable' remove it from the array
		//unset($columns['date']);
	
		return $columns;
	} 


	function filter_authors($groupby) {
		global $wpdb;
		$groupby = " {$wpdb->posts}.post_author";
		return $groupby;
	}
	add_filter('posts_groupby','filter_authors');

	add_filter( 'posts_where', 'title_like_posts_where', 10, 2 );
	function title_like_posts_where( $where, $wp_query ) {
		global $wpdb;
		if ( $post_title_like = $wp_query->get( 'post_title_like' ) ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $post_title_like ) ) . '%\'';
		}
		return $where;
	}



	add_action( 'pre_get_posts', 'completed_sr_orderby' );
	function completed_sr_orderby( $query ) {
		if( ! is_admin() )
			return;
	
		$orderby = $query->get( 'orderby');
	
		if( 'o_completed_sr' == $orderby ) {
			$experts_totalsr = array();

			$experts_args = array ( 
				'posts_per_page' => -1,
				'post_type' => 'expert'
			);
			$experts = new WP_Query( $experts_args );
			remove_filter('posts_groupby','filter_authors');
			if( $experts->have_posts() ):
				while ( $experts->have_posts() ) : $experts->the_post();

					$eid = get_the_ID();
					$expert_email = get_field('expert_email');
					$user = get_user_by( 'email', $expert_email );
					$user_id = $user->ID;
					$crs_args = array(
						'posts_per_page'	=> -1,
						'post_type'		=> 'service_request',
						'order' => 'ASC',
							'meta_query'	=> array(
													'relation' =>'OR',
														array(  
															'relation'		=> 'AND',
															array(
																'key'		=> 'consultant',
																'value'		=> $user_id,
																'compare'	=> '='
															),
															array(
																'key'		=> 'state', 
																'value'		=> 'Closed',
																'compare'	=> '='
															)
														),
														array(               
															'relation' => 'AND',          
															array(
																'key' => 'is_referral',                
																'value' => '1',         
																'compare' => '='       
															),
															array(
																'key' => 'referral',
																'value' => $expert_email,
																'compare' => '='
															),
															array(
																'key'		=> 'state', 
																'value'		=> 'Closed',
																'compare'	=> '='
															)
														)
												) 
											);
					$num_paid_sr=0;
					$num_not_paid_sr=0;
					$csr = new WP_Query( $crs_args );
					if( $csr->have_posts() ):
						while ( $csr->have_posts() ) : $csr->the_post();
						$sr_id = get_the_ID();
						if(get_field('is_referral')){
							if(get_field('referral_paid')){
								$num_paid_sr++;
							}else{
								$num_not_paid_sr++;
							}
						}
						if(get_field('royalty_paid')){
							$num_paid_sr++;
						}else{
							$num_not_paid_sr++;
						}
						endwhile;
					endif;
					
				
					//echo "Last SQL-Query: {$csr->request} ......";
						$experts_totalsr[$eid] = $num_not_paid_sr;

						
					wp_reset_query();	

				endwhile;
			endif;
			wp_reset_query();	
			add_filter('posts_groupby','filter_authors');

			//sort by number of Closed SR
			if($_GET['order']=='desc'){
				arsort($experts_totalsr);
			}else if($_GET['order']=='asc'){
				asort($experts_totalsr);
			}else{
				arsort($experts_totalsr);
			}
			
			$pin_arr = array();
			foreach($experts_totalsr as $k => $val){
				$pin_arr[] = $k;
			}
			//print_r($experts_totalsr);
			$query->set('post__in', $pin_arr);
			$query->set('orderby', 'post__in');
			
		}
	}

	function title_filter( $where, &$wp_query )
	{
		global $wpdb;
		if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $search_term ) ) . '%\'';
		}
		return $where;
	}
	


	add_action( 'manage_expert_posts_custom_column', 'hs_expert_table_content', 10, 2 );
	function hs_expert_table_content( $column_name, $post_id ) {

		$expert_id = $post_id;
		$expert_email = get_field('expert_email',$post_id);

		$user = get_user_by( 'email', $expert_email );
		$user_id = $user->ID;





		// total de service requests closed *******************************************
		if( $column_name == 'completed_sr' ) {

			remove_filter('posts_groupby','filter_authors');

				$args_completed_sr = array(
				'posts_per_page'	=> -1,
				'post_type'		=> 'service_request',
				'orderby'   => 'post_id',
				'order' => 'ASC',
				'meta_query'	=> array(
										'relation' =>'OR',
											array(  
												'relation'		=> 'AND',
												array(
													'key'		=> 'consultant',
													'value'		=> $user_id,
													'compare'	=> '='
												),
												array(
													'key'		=> 'state', 
													'value'		=> 'Closed',
													'compare'	=> '='
												)
											)
									) 
								);

								
				$csr1= new WP_Query($args_completed_sr);
			//echo $csr1->request;

/*
			global $wpdb;

			$sql = "SELECT   ib_86_posts.* FROM ib_86_posts  INNER JOIN ib_86_postmeta ON ( ib_86_posts.ID = ib_86_postmeta.post_id )  INNER JOIN ib_86_postmeta AS mt1 ON ( ib_86_posts.ID = mt1.post_id ) WHERE 1=1  AND ( 
				( 
				  ( ib_86_postmeta.meta_key = 'consultant' AND ib_86_postmeta.meta_value = '2310' ) 
				  AND 
				  ( mt1.meta_key = 'state' AND mt1.meta_value = 'Closed' )
				)
			  ) AND ib_86_posts.post_type = 'service_request' AND (ib_86_posts.post_status = 'publish' OR ib_86_posts.post_status = 'hidden' OR ib_86_posts.post_status = 'expired' OR ib_86_posts.post_status = 'acf-disabled' OR ib_86_posts.post_status = 'future' OR ib_86_posts.post_status = 'draft' OR ib_86_posts.post_status = 'pending' OR ib_86_posts.post_status = 'expired' OR ib_86_posts.post_status = 'private') GROUP BY ib_86_posts.ID ORDER BY ib_86_posts.post_date ASC 
			  ";

			var_dump($wpdb->get_results($sql));

*/

				$args_completed_sr2 = array(
					'posts_per_page'	=> -1,
					'post_type'		=> 'service_request',
					'orderby'   => 'post_id',
					'order' => 'ASC',
					'meta_query'	=>
					array(               
								'relation' => 'AND',          
								array(
									'key' => 'is_referral',                
									'value' => '1',         
									'compare' => '='       
								),
								array(
									'key' => 'referral',
									'value' => $expert_email,
									'compare' => '='
								),
								array(
									'key'		=> 'state', 
									'value'		=> 'Closed',
									'compare'	=> '='
								)
							)
						);
				$csr2= new WP_Query($args_completed_sr2);
				//echo $csr2->request;




		$completed_sr_posts = new WP_Query();
		$completed_sr_posts->posts = array_merge( $csr1->posts, $csr2->posts );
		$completed_sr_posts->post_count = $csr1->post_count + $csr2->post_count;

		//$completed_sr_posts = get_transient( 'completed_sr_posts_user_id_'.$user_id );

		//echo get_field('referral',70963).'- '.$expert_email ;

		/*while ( $completed_sr_posts->have_posts() ) : $completed_sr_posts->the_post();
			echo get_the_ID().'<br/>';
		endwhile;*/


		add_filter('posts_groupby','filter_authors');

		$tse_content= '<div class="sr-table sr-bt sr-bb sr-bl" style="display:none;">';
		$paid_sr = '';
		$not_paid_sr = '';
		$not_paid_total = 0;
		$paid_total = 0;

		if( $completed_sr_posts->have_posts() ):
			while ( $completed_sr_posts->have_posts() ) : $completed_sr_posts->the_post();
				$sr_id = get_the_ID();
				$args_completed_sc = array(
					'post_title_like' => ''.$sr_id,
					'posts_per_page'	=> -1,
					'post_type'		=> 'service_contract',
					'meta_query'	=>  array(
										'relation'		=> 'AND',
										array(
											'key'		=> 'progress',
											'value'		=> 'Closed',
											'compare'	=> '='
										)
					)
				);
				remove_filter('posts_groupby','filter_authors');
				$completed_sc = new WP_Query( $args_completed_sc );
				add_filter('posts_groupby','filter_authors');
				if(get_field('is_referral',$sr_id)){
					//echo get_field('referral',$sr_id).' == '.$expert_email;
					if(get_field('referral',$sr_id) == $expert_email){ // if service has referral and this expert is the referral
						if(get_field('referral_paid',$sr_id)){
							$paid_sr.= '<a href="'.get_the_permalink($sr_id).'" class="tentry" target="_blank">REF# '.$sr_id.' (referral)</a>';
							$paid_total++;
						}else{
							$not_paid_sr.= '<a href="'.get_the_permalink($sr_id).'" class="tentry" target="_blank">REF# '.$sr_id.' (referral)</a>';
							$not_paid_total++;
						}
					}else{// if service has referral and this expert is the referred
						if(get_field('royalty_paid',$sr_id)){
							$paid_sr.= '<a href="'.get_the_permalink($sr_id).'" class="tentry" target="_blank">REF# '.$sr_id.'</a>';
							$paid_total++;
						}else{
							$not_paid_sr.= '<a href="'.get_the_permalink($sr_id).'" class="tentry" target="_blank">REF# '.$sr_id.'</a>';
							$not_paid_total++;
						}	
					}
				}else{
					//echo 'b';
					if ( $completed_sc->have_posts() ) {
						if(get_field('royalty_paid',$sr_id)){
							$paid_sr.= '<a href="'.get_the_permalink($sr_id).'" class="tentry" target="_blank">REF# '.$sr_id.'</a>';
							$paid_total++;
						}else{
							$not_paid_sr.= '<a href="'.get_the_permalink($sr_id).'" class="tentry" target="_blank">REF# '.$sr_id.'</a>';
							$not_paid_total++;
						}
					}else{
						$paid_sr.= '<a href="'.get_the_permalink($sr_id).'" class="tentry" target="_blank">REF# '.$sr_id.'</a>';
					}
				}
				wp_reset_query();

			endwhile;
		endif;
		
		$tse_content.= $not_paid_sr.'</div>';
		if($paid_total > 0){
			$tse_content.= '<span class="show-paid" style="display:none;"></span>';
		}
		$tse_content.= '<div class="paid-lists sr-bt sr-bb sr-bl" style="display:none;">
		'.$paid_sr.'
		</div>';

		echo '<div class="tse">';
		echo '<div class="tse-head"><span class="sr_total '.( $not_paid_total > 0 ? 'active' : '' ).'">'.$not_paid_total.'</span>
			 </div>';
		echo $tse_content;
		echo '</div>';

		set_transient( 'completed_sr_posts_user_id_'.$user_id, $completed_sr_posts, 10 );

		wp_reset_query();	 
		}


		// total de faturação do expert *******************************************
		if( $column_name == 'total_invoices' ) {
			$completed_sr_posts = get_transient( 'completed_sr_posts_user_id_'.$user_id );
			/*
			$args_completed_sr = array(
			'posts_per_page'	=> -1,
			'post_type'		=> 'service_request',
			'orderby'   => 'post_id',
        	'order' => 'ASC',
			'meta_query'	=> array(
				'relation' =>'OR',
					array(  
						'relation'		=> 'AND',
						array(
							'key'		=> 'consultant',
							'value'		=> $user_id,
							'compare'	=> '='
						),
						array(
							'key'		=> 'state',
							'value'		=> 'Closed',
							'compare'	=> '='
						),
						array(
							'key'		=> 'consultant',
							'value'		=> '',
							'compare'	=> '!='
						)
					),
					array(               
						'relation' => 'AND',          
						array(
							'key' => 'is_referral',                
							'value' => '1',         
							'compare' => '='       
						),
						array(
							'key' => 'referral',
							'value' => $expert_email,
							'compare' => '='
						),
						array(
							'key'		=> 'state', 
							'value'		=> 'Closed',
							'compare'	=> '='
						)
					)
			) 
		);
		*/

		$paid_sr = '';
		$not_paid_total_invoice = 0;
		$not_paid_sr = '';
		$num_paid_sr = 0;

	//	remove_filter('posts_groupby','filter_authors');
		//$completed_sr_posts = new WP_Query( $args_completed_sr );
		//echo "Last SQL-Query: {$completed_sr_posts->request}";
	//	add_filter('posts_groupby','filter_authors');

		$tse_content= '<div class="sr-table sr-bt sr-bb invoice-table" style="display:none;">';
		if( $completed_sr_posts->have_posts() ):
			while ( $completed_sr_posts->have_posts() ) : $completed_sr_posts->the_post();
			$sr_id = get_the_ID();
				if(get_field('royalty_paid',$sr_id)){
					$num_paid_sr++;
				}
				if(get_field('referral_paid',$sr_id)){
					$num_paid_sr++;
				}
				
				$args_completed_sc = array(
					'post_title_like' => ''.$sr_id,
					'posts_per_page'	=> -1,
					'post_type'		=> 'service_contract',
					'meta_query'	=>  array(
										'relation'		=> 'AND',
										array(
											'key'		=> 'progress',
											'value'		=> 'Closed',
											'compare'	=> '='
										)
					)
				);
				remove_filter('posts_groupby','filter_authors');
				$completed_sc = new WP_Query( $args_completed_sc );
				add_filter('posts_groupby','filter_authors');


					if ( $completed_sc->have_posts() ) {
						while ( $completed_sc->have_posts() ) {
							$completed_sc->the_post();

							if(get_field('is_referral',$sr_id)){
				
								if(get_field('referral',$sr_id) == $expert_email){
									if(get_field('referral_paid',$sr_id)){
										$paid_sr.= '<a href="'.get_the_permalink().'" target="_blank" class="sr-bdl tentry">'.wc_price(get_field('proposal_value')).'</a>';
									}else{
										$not_paid_sr.= '<a href="'.get_the_permalink().'" target="_blank" class="sr-bdl tentry">'.wc_price(get_field('proposal_value')).'</a>';
										$not_paid_total_invoice+= get_field('proposal_value');
									}
								}else{
									if(get_field('royalty_paid',$sr_id)){
										$paid_sr.= '<a href="'.get_the_permalink().'" target="_blank" class="sr-bdl tentry">'.wc_price(get_field('proposal_value')).'</a>';
									}else{
										$not_paid_sr.= '<a href="'.get_the_permalink().'" target="_blank" class="sr-bdl tentry">'.wc_price(get_field('proposal_value')).'</a>';
										$not_paid_total_invoice+= get_field('proposal_value');
									}
								}
			
							}else{

								if(get_field('royalty_paid',$sr_id)){
									$paid_sr.= '<a href="'.get_the_permalink().'" target="_blank" class="sr-bdl tentry">'.wc_price(get_field('proposal_value')).'</a>';
								}else{
									$not_paid_sr.= '<a href="'.get_the_permalink().'" target="_blank" class="sr-bdl tentry">'.wc_price(get_field('proposal_value')).'</a>';
									$not_paid_total_invoice+= get_field('proposal_value');
								}
							}

						}
					}else{
						$paid_sr.='<span class="sr-bdl tentry">(contract not found)</span>';
					}
				wp_reset_query();	 

			endwhile;
		endif;
		$tse_content.= $not_paid_sr.'</div>';
		if($num_paid_sr > 0){
			$tse_content.= '<span class="show-paid" style="display:none;">'.__('Show Paid SR').' >></span>';
		}
		$tse_content.= '<div class="paid-lists sr-bt sr-bb" style="display:none;">
		'.$paid_sr.'
		</div>';

		echo '<div class="tse">';
		echo '<div class="tse-head">';
				echo '<span class="sr_total '.( $not_paid_total_invoice > 0 ? 'active' : '' ).'">'.wc_price($not_paid_total_invoice).'</span>
			 </div>';
		echo $tse_content;
		echo '</div>';

		wp_reset_query();	 
		}






		// total de royalities da idealbiz *******************************************
		/*
		if( $column_name == 'total_idealbiz' ) {

		remove_filter('posts_groupby','filter_authors');
		$completed_sr_posts = get_transient( 'completed_sr_posts_user_id_'.$user_id );

		add_filter('posts_groupby','filter_authors');
		$tse_content= '<div class="sr-table sr-bt sr-bb" style="display:none;">';

		$paid_sr = '';
		$paid_total_royal = 0;
		$expert_paid_total_royal = 0;
		$paid_total_invoice = 0;
		$not_paid_sr = '';
		$paid_total=0;
		$not_paid_total = 0;
		if( $completed_sr_posts->have_posts() ):
			while ( $completed_sr_posts->have_posts() ) : $completed_sr_posts->the_post();
				$sr_id= get_the_ID();
				$args_completed_sc = array(
					'post_title_like' => ''.get_the_ID(),
					'posts_per_page'	=> -1,
					'post_type'		=> 'service_contract',
					'meta_query'	=>  array(
										'relation'		=> 'AND',
										array(
											'key'		=> 'progress',
											'value'		=> 'Closed',
											'compare'	=> '='
										)
					)
				);
				remove_filter('posts_groupby','filter_authors');
				$completed_sc = new WP_Query( $args_completed_sc );
				add_filter('posts_groupby','filter_authors');
				echo 'asd';  
				if(get_field('is_referral',$sr_id)){
				
					if(get_field('referral',$sr_id) == $expert_email){
						if(get_field('referral_paid',$sr_id)){
							$paid_total_royal+=$idealbiz_earn_part;
						}else{
							$not_paid_sr.='<div class="tse-royal sr-bdl tentry">
										'.wc_price($idealbiz_earn_part).'
										<label class="switch" style="width: 0;margin: 0;"></label>';
							$not_paid_sr.='</div>';
							$not_paid_total++;
						}
					}
				}else{
					if ( $completed_sc->have_posts() ) {
						while ( $completed_sc->have_posts() ) {
							$completed_sc->the_post();

							$is_paid = '';
							if(get_field('royalty_paid',$sr_id)){
								$is_paid = 'checked';
								$paid_total++;

								$idealbiz_earn_part = 0;
								$contract_value = get_field('proposal_value');
								$serv_cat_id = get_field('request_type',$sr_id)->term_id;
								$i=0;
								if( have_rows('services_royalties',$expert_id) ):
									while( have_rows('services_royalties',$expert_id) ): the_row();
										$service = get_sub_field('service');
										if($serv_cat_id == $service->term_id){
											$idealbiz_earn_part = (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
											//$paid_total_royal+=$idealbiz_earn_part;
										}
										$i++;
									endwhile;
								endif; 

								$paid_sr.='<div class="tse-royal sr-bdl tentry">
											'.wc_price($idealbiz_earn_part).'</div>';
							}else{
								$idealbiz_earn_part = 0;
								$contract_value = get_field('proposal_value');
								$serv_cat_id = get_field('request_type',$sr_id)->term_id;
								$i=0;
								if( have_rows('services_royalties',$expert_id) ):
									while( have_rows('services_royalties',$expert_id) ): the_row();
										$service = get_sub_field('service');
										if($serv_cat_id == $service->term_id){
											$idealbiz_earn_part = (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
											$paid_total_royal+=$idealbiz_earn_part;
										}
										$i++;
									endwhile;
								endif; 
								$not_paid_sr.='<div class="tse-royal sr-bdl tentry">
											'.wc_price($idealbiz_earn_part).'
											<label class="switch" style="width: 0;margin: 0;"></label>';
								$not_paid_sr.='</div>';
								$not_paid_total++;
							}
							
						}
					}else{
						$paid_sr.= '<span class="sr-bdl tentry">&nbsp;</span>';
					}
				}

				wp_reset_query();	 

			endwhile;
		endif;
		$tse_content.= $not_paid_sr.'</div>';
		if($paid_total > 0){
			$tse_content.= '<span class="show-paid" style="display:none;"></span>';
		}
		$tse_content.= '<div class="paid-lists sr-bt sr-bb" style="display:none;">
		'.$paid_sr.'
		</div>';

		echo '<div class="tse">';
			echo '<div class="tse-head">
					<span class="sr_total '.( $not_paid_total > 0 ? 'active' : '' ).'">'.wc_price($paid_total_royal).'</span>';
			echo '</div>';
			echo $tse_content;
		echo '</div>';
		wp_reset_query();	 
		}

		*/








		// total de royalities do expert *******************************************
		if( $column_name == 'total_royalties' ) {

		remove_filter('posts_groupby','filter_authors');
		$completed_sr_posts = get_transient( 'completed_sr_posts_user_id_'.$user_id );
		add_filter('posts_groupby','filter_authors');
		$tse_content= '<div class="sr-table sr-bt sr-bb sr-br" style="display:none;">';

		$paid_sr = '';
		$paid_total_royal = 0;
		$expert_paid_total_royal = 0;
		$paid_total_invoice = 0;
		$not_paid_sr = '';
		$paid_total=0;
		$not_paid_total = 0;
		if( $completed_sr_posts->have_posts() ):
			while ( $completed_sr_posts->have_posts() ) : $completed_sr_posts->the_post();
				$sr_id= get_the_ID();

				if(get_field('is_referral',$sr_id)){
					if(get_field('referral',$sr_id) == $expert_email){
							if(get_field('referral_paid',$sr_id)){
								$paid_sr.='<div class="tse-royal sr-bdl tentry">'.wc_price(get_field('earned',$sr_id)).'</div>';
								$paid_total++;
							}else{
								$expert_paid_total_royal+=get_field('earned',$sr_id);
								$not_paid_sr.='<div class="tse-royal sr-bdl tentry">
										'.wc_price(get_field('earned',$sr_id)).'';
								$not_paid_sr.= '<label class="switch">
													<input type="checkbox" class="pay-referral"  data-sr="'.$sr_id.'" id="togBtn-'.$sr_id.'">
													<div class="slider round">
													</div>
													</label>';
								$not_paid_sr.='</div>';
								$not_paid_total++;
							}
							
					}else{
							$args_completed_sc = array(
								'post_title_like' => ''.get_the_ID(),
								'posts_per_page'	=> -1,
								'post_type'		=> 'service_contract',
								'meta_query'	=>  array(
													'relation'		=> 'AND',
													array(
														'key'		=> 'progress',
														'value'		=> 'Closed',
														'compare'	=> '='
													)
								)
							);
							remove_filter('posts_groupby','filter_authors');
							$completed_sc = new WP_Query( $args_completed_sc );
							add_filter('posts_groupby','filter_authors');
							if ( $completed_sc->have_posts() ) {
								while ( $completed_sc->have_posts() ) {
									$completed_sc->the_post();
		
									$is_paid = '';
									if(get_field('royalty_paid',$sr_id)){
										$is_paid = 'checked';
										$paid_total++;
		
										$idealbiz_earn_part = 0;
										$expert_part= 0;
										$contract_value = get_field('proposal_value');
										$serv_cat_id = get_field('request_type',$sr_id)->term_id;
										$i=0;
										if( have_rows('services_royalties',$expert_id) ):
											while( have_rows('services_royalties',$expert_id) ): the_row();
												$service = get_sub_field('service');
												if($serv_cat_id == $service->term_id){
													$idealbiz_earn_part = (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
													$expert_part = $contract_value - $idealbiz_earn_part;
													//$paid_total_royal+=$idealbiz_earn_part;
													//$expert_paid_total_royal+=$expert_part;
												}
												$i++;
											endwhile;
										endif; 
		
										$paid_sr.='<div class="tse-royal sr-bdl tentry">
													'.wc_price($expert_part).'</div>';
									}else{
										$idealbiz_earn_part = 0;
										$expert_part= 0;
										$contract_value = get_field('proposal_value');
										$serv_cat_id = get_field('request_type',$sr_id)->term_id;
										$i=0;
										if( have_rows('services_royalties',$expert_id) ):
											while( have_rows('services_royalties',$expert_id) ): the_row();
												$service = get_sub_field('service');
												if($serv_cat_id == $service->term_id){
													$idealbiz_earn_part = (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
													$expert_part = $contract_value - $idealbiz_earn_part;
													$paid_total_royal+=$idealbiz_earn_part;
													$expert_paid_total_royal+=$expert_part;
												}
												$i++;
											endwhile;
										endif; 
										$not_paid_sr.='<div class="tse-royal sr-bdl tentry">
													'.wc_price($expert_part).'';
										$not_paid_sr.= '<label class="switch">
															<input type="checkbox" '.$is_paid.' class="pay-royalty"  data-sr="'.$sr_id.'" id="togBtn-'.$sr_id.'">
															<div class="slider round">
															</div>
															</label>';
										$not_paid_sr.='</div>';
										$not_paid_total++;
									}
									
								}
							}else{
								$paid_sr.= '<span class="sr-bdl tentry">&nbsp;</span>';
							}
						
					}
				}else{
					$args_completed_sc = array(
						'post_title_like' => ''.get_the_ID(),
						'posts_per_page'	=> -1,
						'post_type'		=> 'service_contract',
						'meta_query'	=>  array(
											'relation'		=> 'AND',
											array(
												'key'		=> 'progress',
												'value'		=> 'Closed',
												'compare'	=> '='
											)
						)
					);
					remove_filter('posts_groupby','filter_authors');
					$completed_sc = new WP_Query( $args_completed_sc );
					add_filter('posts_groupby','filter_authors');
					if ( $completed_sc->have_posts() ) {
						while ( $completed_sc->have_posts() ) {
							$completed_sc->the_post();

							$is_paid = '';
							if(get_field('royalty_paid',$sr_id)){
								$is_paid = 'checked';
								$paid_total++;

								$idealbiz_earn_part = 0;
								$expert_part= 0;
								$contract_value = get_field('proposal_value');
								$serv_cat_id = get_field('request_type',$sr_id)->term_id;
								$i=0;
								if( have_rows('services_royalties',$expert_id) ):
									while( have_rows('services_royalties',$expert_id) ): the_row();
										$service = get_sub_field('service');
										if($serv_cat_id == $service->term_id){
											$idealbiz_earn_part = (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
											$expert_part = $contract_value - $idealbiz_earn_part;
											//$paid_total_royal+=$idealbiz_earn_part;
											//$expert_paid_total_royal+=$expert_part;
										}
										$i++;
									endwhile;
								endif; 

								$paid_sr.='<div class="tse-royal sr-bdl tentry">
											'.wc_price($expert_part).'</div>';
							}else{
								$idealbiz_earn_part = 0;
								$expert_part= 0;
								$contract_value = get_field('proposal_value');
								$serv_cat_id = get_field('request_type',$sr_id)->term_id;
								$i=0;
								if( have_rows('services_royalties',$expert_id) ):
									while( have_rows('services_royalties',$expert_id) ): the_row();
										$service = get_sub_field('service');
										if($serv_cat_id == $service->term_id){
											$idealbiz_earn_part = (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
											$expert_part = $contract_value - $idealbiz_earn_part;
											$paid_total_royal+=$idealbiz_earn_part;
											$expert_paid_total_royal+=$expert_part;
										}
										$i++;
									endwhile;
								endif; 
								$not_paid_sr.='<div class="tse-royal sr-bdl tentry">
											'.wc_price($expert_part).'';
								$not_paid_sr.= '<label class="switch">
													<input type="checkbox" '.$is_paid.' class="pay-royalty"  data-sr="'.$sr_id.'" id="togBtn-'.$sr_id.'">
													<div class="slider round">
													</div>
													</label>';
								$not_paid_sr.='</div>';
								$not_paid_total++;
							}
							
						}
					}else{
						$paid_sr.= '<span class="sr-bdl tentry">&nbsp;</span>';
					}
				}
				wp_reset_query();	 

			endwhile;
		endif;
		$tse_content.= $not_paid_sr.'</div>';
		if($paid_total > 0){
			$tse_content.= '<span class="show-paid" style="display:none;"></span>';
		}
		$tse_content.= '<div class="paid-lists sr-bt sr-bb sr-br" style="display:none;">
		'.$paid_sr.'
		</div>';

		echo '<div class="tse">';
		echo '<div class="tse-head">
				<span class="sr_total '.( $not_paid_total > 0 ? 'active' : '' ).'">'.wc_price($expert_paid_total_royal).'</span>';
				//if( $not_paid_total > 0 || $paid_total){ remove
					echo '<span class="sr_plus">+</span>';
				//} remove
			echo '</div>';

				
		echo $tse_content;
		echo '</div>';

		wp_reset_query();	 
		}
	}
	}
}


add_action('wp_ajax_set_pay', 'set_pay');
add_action('wp_ajax_nopriv_set_pay', 'set_pay');
function set_pay()
{
	update_field('royalty_paid',1,$_POST['id']);    
}

add_action('wp_ajax_set_unpay', 'set_unpay');
add_action('wp_ajax_nopriv_set_unpay', 'set_unpay');
function set_unpay()
{
	update_field('royalty_paid',0,$_POST['id']);    
}


add_action('wp_ajax_set_referral_pay', 'set_referral_pay');
add_action('wp_ajax_nopriv_set_referral_pay', 'set_referral_pay');
function set_referral_pay()
{
	update_field('referral_paid',1,$_POST['id']);    
}

add_action('wp_ajax_set_referral_unpay', 'set_referral_unpay');
add_action('wp_ajax_nopriv_set_referral_unpay', 'set_referral_unpay');
function set_referral_unpay()
{
	update_field('referral_paid',0,$_POST['id']);    
}




add_action('admin_footer', 'experts_royalties_page', 100);
function experts_royalties_page() {
	?>
	<script>
	jQuery(document).ready(($) => {
		$('#menu-posts-expert').find('.wp-submenu').append('<li class="royalty-dashboard"><a href="edit.php?post_type=expert&dashboard=royalties" class="wp-first-item royalties-dashboard-click">Royalties Dashboard</a></li>');
		$('#menu-posts-expert').find('.wp-submenu').append('<li class="royalty-dashboard"><a href="users.php?page=export-users-to-csv" class="wp-first-item royalties-dashboard-click">Export Referrals to CSV</a></li>')
	});
	</script>
	<?php
	if(isset($_GET['dashboard'])){
	?>
	<script>

	if (document.referrer.indexOf('dashboard=royalties') >= 0) { 

	}else{
		location.reload();
	}

	jQuery(document).ready(($) => {
		$('.pay-royalty').change(function() {
			if(this.checked) {
				$.ajax({ 
					data: {action: 'set_pay', 'id': $(this).data('sr')},
					type: 'post',
					url: ajaxurl,
					success: function(data) {
					}
				});
			}else{
				$.ajax({ 
					data: {action: 'set_unpay', 'id': $(this).data('sr')},
					type: 'post',
					url: ajaxurl,
					success: function(data) {
					}
				});
			}
		});
		$('.pay-referral').change(function() {
			if(this.checked) {
				$.ajax({ 
					data: {action: 'set_referral_pay', 'id': $(this).data('sr')},
					type: 'post',
					url: ajaxurl,
					success: function(data) {
					}
				});
			}else{
				$.ajax({ 
					data: {action: 'set_referral_unpay', 'id': $(this).data('sr')},
					type: 'post',
					url: ajaxurl,
					success: function(data) {
					}
				});
			}
		});
		$('.tse-head > .sr_plus, .tse-head > span.active').click(function(){
			var t = $(this).closest('tr');
			t.find('.sr-table').toggle();
			t.find('.show-paid').toggle();
			if(t.find('.paid-lists').css('display')=='flex'){
				t.find('.paid-lists').css('display','none');
			}
			if(t.find('.sr_plus').html()=='+'){
				t.find('.sr_plus').html('-');
			}else{
				t.find('.sr_plus').html('+');
			}
		});
		$('.show-paid').click(function(){
			$(this).closest('tr').find('.paid-lists').toggle();
		});
	});
	</script>
	<style>
		.tentry{
			display: flex;
			justify-content: center;
			align-items: center; 
			text-align: center;
		}
		.completed_sr, .total_invoices, .total_royalties, .total_idealbiz{
			padding-left: 0 !important;
			padding-right: 0 !important;
		}
		.completed_sr .tse, .total_invoices .tse, .total_royalties .tse, .total_idealbiz .tse{
			display: flex;
  			flex-direction: column;
		}
  
		.wp-submenu .current{
			font-weight:400 !important;
			color: rgba(240,245,250,.7) !important;
		}
		.royalty-dashboard a{
			font-weight: 600 !important;
			color: #fff !important;
		}
		.sr_plus{
			padding: 10px 15px; 
			border-radius: 30px;
			background: none;
			color: #14307B;
			white-space: nowrap;
			position: absolute;
			font-size: 37px;
			top: 2px;
    		right: -25px;
			cursor: pointer
		}
		.sr_total{
			padding: 10px 15px; 
			border-radius: 30px;
			background: #eaeaea;
			position: relative;
			top: 5px;
			left: 50%;
			transform: translateX(-50%);
			white-space: nowrap;
		}
		.sr_total.active{
			background: #14307B;
			color: #fff;
		}
		.show-paid{
			color: #14307B;
			background: none;
			text-align: center;
			font-size: 11px;
			text-decoration: underline;
			cursor:pointer;
			height:19px;
			margin-bottom: 5px;
		}
		.tse-head{
			display: flex;
			position: relative;
		}
		.tse-head > span.active{
			cursor: pointer;
		}
		.sr-table{
			float: right;
			margin-top: 20px;
			width: 100%;
			display: flex;
  			flex-direction: column;
			background: #fff;
			margin-bottom:10px;
		}
		.paid-lists{
			float: right;
			margin-top: 10px;
			width: 100%;
			display: flex;
  			flex-direction: column;
			background: #f1f1f1;
			margin-bottom:20px;
		}
		.sr-bt{ border-top: 1px solid #ccc; }
		.sr-bb{ border-bottom: 1px solid #ccc; } 
		.sr-br{ border-right: 1px solid #ccc; } .sr-bdr{ border-right: 1px dotted #c9c9c9; }
		.sr-bl{ border-left: 1px solid #ccc; } .sr-bdl{ border-left: 1px dotted #c9c9c9; }
		.sr-table > a, .sr-table > span, .sr-table > .tse-royal, .paid-lists > a, .paid-lists > span, .paid-lists > .tse-royal{
			line-height: 14px;
			border-bottom: 1px dotted #c9c9c9;
			font-size: 11px;
			padding: 3px 10px;
		}
		.sr-table > a:last-child, .sr-table > span:last-child,  .sr-table > .tse-royal:last-child{
			border-bottom: 0px !important;
		}
		.paid-lists > a:last-child, .paid-lists > span:last-child,  .paid-lists > .tse-royal:last-child{
			border-bottom: 0px !important;
		}
		.pll_icon_tick, .pll_icon_add, .pll_icon_edit{
			display: none !important;
		}
		.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 16px;
		margin-left: 10px;
		}
		.tentry{
			height: 25px;
		}

		.switch input {display:none;}

		.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
		border-radius: 14px;
		}

		.slider:before {
		position: absolute;
		content: "";
		height: 12px;
		width: 12px;
		left: 2px;
		bottom: 2px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
		border-radius: 50%;
		}

		input:checked + .slider {
		background-color: #2ab934;
		}

		input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
		}

		input:checked + .slider:before {
		-webkit-transform: translateX(43px);
		-ms-transform: translateX(43px);
		transform: translateX(43px);
		}

		/*------ ADDED CSS ---------*/
		.slider:after
		{
		content:'<?php _e('Unpaid','idealbiz'); ?>';
		color: #000;
		display: block;
		position: absolute;
		transform: translate(-50%,-50%);
		top: 50%;
		left: 31px;
		font-size: 9px;
		font-family: Verdana, sans-serif;
		}

		input:checked + .slider:after
		{  
		content:'<?php _e('Paid','idealbiz'); ?>';
		left: 16px;
		}
		

	</style>
	<?php	
	}else{
		?>
	<style>
		.column-completed_sr, .column-total_invoices, .column-total_royalties{
			display:none;
		}
	</style>

		<?php
	}
}
