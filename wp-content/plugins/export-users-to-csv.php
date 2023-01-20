<?php
/**
 * @package Export_Users_to_CSV
 * @version 1.0.0
 */
/*
Plugin Name: Export Referrals to CSV

*/

load_plugin_textdomain( 'export-users-to-csv', false, basename( dirname( __FILE__ ) ) . '/languages' );

/**
 * Main plugin class
 *
 * @since 0.1
 **/
class PP_EU_Export_Users {

	/**
	 * Class contructor
	 *
	 * @since 0.1
	 **/
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
		add_action( 'init', array( $this, 'generate_csv' ) );
		add_filter( 'pp_eu_exclude_data', array( $this, 'exclude_data' ) );
	}

	/**
	 * Add administration menus
	 *
	 * @since 0.1
	 **/
	public function add_admin_pages() {
		add_users_page( __( 'Export to CSV', 'export-users-to-csv' ), __( 'Export to CSV', 'export-users-to-csv' ), 'list_users', 'export-users-to-csv', array( $this, 'users_page' ) );
	}

	/**
	 * Process content of CSV file
	 *
	 * @since 0.1
	 **/
	public function generate_csv() {
		if ( isset( $_POST['_wpnonce-pp-eu-export-users-users-page_export'] ) ) {
			check_admin_referer( 'pp-eu-export-users-users-page_export', '_wpnonce-pp-eu-export-users-users-page_export' );


			$sitename = sanitize_key( get_bloginfo( 'name' ) );
			if ( ! empty( $sitename ) )
				$sitename .= '.';
			$filename = $sitename . 'users.' . date( 'Y-m-d-H-i-s' ) .'_'.$_POST['role']. '.csv';

			header('Content-Encoding: UTF-8');
			header('Content-type: text/csv; charset=UTF-8');
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			echo "\xEF\xBB\xBF"; // UTF-8 BOM


			$exclude_data = apply_filters( 'pp_eu_exclude_data', array() );

			global $wpdb;

			//Exporta os referrals earned
			if (strpos($_POST['role'], 'referrals') !== false) {

				echo "Expert,Email,Service Request,Earned ".html_entity_decode(get_woocommerce_currency_symbol()). "\n";
				$experts_args = array ( 
					'posts_per_page' => -1,
					'post_type' => 'expert'
				);
				$experts = new WP_Query( $experts_args );
				
				if( $experts->have_posts() ):
					while ( $experts->have_posts() ) : $experts->the_post();
						$eid = get_the_ID();
						$expert_email = get_field('expert_email');
						$expert_name = get_the_title();
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
						if( $csr2->have_posts() ):
							while ( $csr2->have_posts() ) : $csr2->the_post();
								if (strpos($_POST['role'], 'unpaid') !== false) {
									if(!get_field('referral_paid')){
										echo $expert_name.';';
										echo $expert_email.';';
										echo 'REF #'.get_the_ID().';';
										echo get_field('earned').';';
										echo "\n";
									}
								}else if (strpos($_POST['role'], 'paid') !== false) {
									if(get_field('referral_paid')){
										echo $expert_name.';';
										echo $expert_email.';';
										echo 'REF #'.get_the_ID().';';
										echo get_field('earned').';';
										echo "\n";
									}
								}
							endwhile;
						endif;
						wp_reset_query();	 


					endwhile;
				endif;
				wp_reset_query();	 



			//Exporta os royalties
			}else if (strpos($_POST['role'], 'royalties') !== false) {

				add_filter( 'posts_where', 'title_like_posts_where', 10, 2 );
				function title_like_posts_where( $where, $wp_query ) {
					global $wpdb;
					if ( $post_title_like = $wp_query->get( 'post_title_like' ) ) {
						$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $post_title_like ) ) . '%\'';
					}
					return $where;
				}

				echo "Service Request;Expert;Email;Total Service ".html_entity_decode(get_woocommerce_currency_symbol()). "; Royalty ".html_entity_decode(get_woocommerce_currency_symbol()). "\n";
				$experts_args = array ( 
					'posts_per_page' => -1,
					'post_type' => 'expert'
				);
				$experts = new WP_Query( $experts_args );
				
				if( $experts->have_posts() ):
					while ( $experts->have_posts() ) : $experts->the_post();
						$eid = get_the_ID();
						$expert_email = get_field('expert_email');
						$expert_name = get_the_title();
						
						$user = get_user_by( 'email', $expert_email );
						$user_id = $user->ID;

						$args_completed_sr2 = array(
							'posts_per_page'	=> -1,
							'post_type'		=> 'service_request',
							'orderby'   => 'post_id',
							'order' => 'ASC',
							'meta_query'	=>
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
								);
						$csr2= new WP_Query($args_completed_sr2);
					

						if( $csr2->have_posts() ):
							while ( $csr2->have_posts() ) : $csr2->the_post();
									$sr_id = get_the_ID();
								//	echo '--'.$sr_id.'--<br/>';

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
									$completed_sc = new WP_Query( $args_completed_sc );
								//	echo $completed_sc->request;
									$pval = 0;
									$expert_part=0;

									if ( $completed_sc->have_posts() ) {
										while ( $completed_sc->have_posts() ) {
											$completed_sc->the_post();
											$contract_id= get_the_ID();
											$pval = get_field('proposal_value',$contract_id);
											//echo get_the_title().'<br/>';

											$serv_cat_id = get_field('request_type',$sr_id)->term_id;
											$i=0;
											if( have_rows('services_royalties',$eid) ):
												while( have_rows('services_royalties',$eid) ): the_row();
													$service = get_sub_field('service');
													if($serv_cat_id == $service->term_id){
														$idealbiz_earn_part = (($pval * get_post_meta($eid, 'services_royalties_'.$i.'_royalty' )[0])/100);
														$expert_part = $pval - $idealbiz_earn_part;
													}$i++;
												endwhile;
											endif; 

										}
									}
									wp_reset_query();	 

									
									if (strpos($_POST['role'], 'unpaid') !== false) {
										if(!get_field('royalty_paid',$sr_id)){
											echo 'REF #'.$sr_id.';';
											echo $expert_name.';';
											echo $expert_email.';';
											echo $pval.';';
											echo $expert_part;
											echo "\n";
											//echo "<br/>";
										}
									}else if (strpos($_POST['role'], 'paid') !== false) {
										if(get_field('royalty_paid',$sr_id)){
											echo 'REF #'.$sr_id.';';
											echo $expert_name.';';
											echo $expert_email.';';
											echo $pval.';';
											echo $expert_part;
											echo "\n";
											//echo "<br/>";
										}
									}

							endwhile;
						endif;
						wp_reset_query();	 

					endwhile;
				endif;



			}else{


				$args = array(
					'fields' => 'all_with_meta',
					'role' => stripslashes( $_POST['role'] )
				);
	
				add_action( 'pre_user_query', array( $this, 'pre_user_query' ) );
				$users = get_users( $args );
				remove_action( 'pre_user_query', array( $this, 'pre_user_query' ) );
	
				if ( ! $users ) {
					$referer = add_query_arg( 'error', 'empty', wp_get_referer() );
					wp_redirect( $referer );
					exit;
				}
				

				$data_keys = array(
					'ID', 'user_login', 'user_pass',
					'user_nicename', 'user_email', 'user_url',
					'user_registered', 'user_activation_key', 'user_status',
					'display_name'
				);
				$meta_keys = $wpdb->get_results( "SELECT distinct(meta_key) FROM $wpdb->usermeta" );
				$meta_keys = wp_list_pluck( $meta_keys, 'meta_key' );
				$fields = array_merge( $data_keys, $meta_keys );
	
				$headers = array();
				foreach ( $fields as $key => $field ) {
					if ( in_array( $field, $exclude_data ) )
						unset( $fields[$key] );
					else
						$headers[] = '"' . strtolower( $field ) . '"';
				}
				echo implode( ';', $headers ) . "\n";
	
				foreach ( $users as $user ) {
					$data = array();
					foreach ( $fields as $field ) {
						$value = isset( $user->{$field} ) ? $user->{$field} : '';
						$value = is_array( $value ) ? serialize( $value ) : $value;
						$data[] = '"' . str_replace( '"', '""', $value ) . '"';
					}
					echo implode( ';', $data ) . "\n";
				}	


			}


			exit;
		}
	}

	/**
	 * Content of the settings page
	 *
	 * @since 0.1
	 **/
	public function users_page() {
		if ( ! current_user_can( 'list_users' ) )
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'export-users-to-csv' ) );
?>

<div class="wrap">
	<h2><?php _e( 'Export users to a CSV file', 'export-users-to-csv' ); ?></h2>
	<?php
	if ( isset( $_GET['error'] ) ) {
		echo '<div class="updated"><p><strong>' . __( 'No user found.', 'export-users-to-csv' ) . '</strong></p></div>';
	}
	?>
	<form method="post" action="" enctype="multipart/form-data">
		<?php wp_nonce_field( 'pp-eu-export-users-users-page_export', '_wpnonce-pp-eu-export-users-users-page_export' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for"pp_eu_users_role"><?php _e( 'Role', 'export-users-to-csv' ); ?></label></th>
				<td>
					<select name="role" id="pp_eu_users_role">
						<?php
						echo '<option value="referrals-unpaid">' . __( 'Expert Referrals (unpaid)', 'export-users-to-csv' ) . '</option>';
						echo '<option value="referrals-paid">' . __( 'Expert Referrals (paid)', 'export-users-to-csv' ) . '</option>';

						echo '<option value="royalties-unpaid">' . __( 'Expert Royalties (unpaid)', 'export-users-to-csv' ) . '</option>';
						echo '<option value="royalties-paid">' . __( 'Expert Royalties (paid)', 'export-users-to-csv' ) . '</option>';


						echo '<option value="">' . __( 'Every Role', 'export-users-to-csv' ) . '</option>';
						global $wp_roles;
						foreach ( $wp_roles->role_names as $role => $name ) {
							echo "\n\t<option value='" . esc_attr( $role ) . "'>$name</option>";
						}
						?>
					</select>
				</td>
			</tr>
			<tr valign="top" style="display: none;">
				<th scope="row"><label><?php _e( 'Date range', 'export-users-to-csv' ); ?></label></th>
				<td>
					<select name="start_date" id="pp_eu_users_start_date">
						<option value="0"><?php _e( 'Start Date', 'export-users-to-csv' ); ?></option>
						<?php $this->export_date_options(); ?>
					</select>
					<select name="end_date" id="pp_eu_users_end_date">
						<option value="0"><?php _e( 'End Date', 'export-users-to-csv' ); ?></option>
						<?php $this->export_date_options(); ?>
					</select>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
			<input type="submit" class="button-primary" value="<?php _e( 'Export', 'export-users-to-csv' ); ?>" />
		</p>
	</form>
<?php
	}

	public function exclude_data() {
		$exclude = array( 'user_pass', 'user_activation_key' );

		return $exclude;
	}

	public function pre_user_query( $user_search ) {
		global $wpdb;

		$where = '';

		if ( ! empty( $_POST['start_date'] ) )
			$where .= $wpdb->prepare( " AND $wpdb->users.user_registered >= %s", date( 'Y-m-d', strtotime( $_POST['start_date'] ) ) );

		if ( ! empty( $_POST['end_date'] ) )
			$where .= $wpdb->prepare( " AND $wpdb->users.user_registered < %s", date( 'Y-m-d', strtotime( '+1 month', strtotime( $_POST['end_date'] ) ) ) );

		if ( ! empty( $where ) )
			$user_search->query_where = str_replace( 'WHERE 1=1', "WHERE 1=1$where", $user_search->query_where );

		return $user_search;
	}

	private function export_date_options() {
		global $wpdb, $wp_locale;

		$months = $wpdb->get_results( "
			SELECT DISTINCT YEAR( user_registered ) AS year, MONTH( user_registered ) AS month
			FROM $wpdb->users
			ORDER BY user_registered DESC
		" );

		$month_count = count( $months );
		if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
			return;

		foreach ( $months as $date ) {
			if ( 0 == $date->year )
				continue;

			$month = zeroise( $date->month, 2 );
			echo '<option value="' . $date->year . '-' . $month . '">' . $wp_locale->get_month( $month ) . ' ' . $date->year . '</option>';
		}
	}
}

new PP_EU_Export_Users;
