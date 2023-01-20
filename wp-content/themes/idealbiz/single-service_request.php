<?php
/**
 * The template for displaying all single listings.
 *
 * @package iDealBiz
 */

require_once(ABSPATH . 'wp-content/themes/idealbiz/library/SingleServiceRequest.php');
require_once(ABSPATH . 'wp-content/themes/idealbiz/library/SingleServiceContract.php');
SingleServiceRequest::protect_service_request();

// Reset form data.
if ( isset( $_POST['gform_submit'] ) ) {
	wp_redirect( get_the_permalink( get_queried_object() ) );
}

get_header(); ?>
        <section class="single-service-request">
            <div class="container m-b-25 m-t-15">
                <a class="go-search font-weight-medium d-flex align-items-center" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id')); ?>">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back to your account', 'idealbiz'); ?></span>
                </a>
            </div>
            <div class="container d-flex flex-row flex-wrap justify-content-around">
				

                <div class="col-md-8 m-b-20">
				<div class="expert position-relative p-20 dropshadow d-flex flex-column black--color white--background">
				<main id="main" class="service-request site-main">

					<?php
					while ( have_posts() ) : 
						the_post();
						$post_id = get_the_ID();

						$x=1;
						$s2o = ''; $s3o = ''; $s4o = '';
						$s1 = ''; $s2 = ''; $s3 = ''; $s4 = ''; $s5 = '';
						$ep1=0; $ep2=0; $ep3=0;

						$orderslist = get_field('orders');
						$customer = get_field('customer');
						$stotal = 0;
						foreach($orderslist as $order){
							$o = new WC_Order( $order->ID );
							if($x==1){
								if ( $customer->ID == wp_get_current_user()->ID ){
									$s2o = '<a href="'.get_permalink( wc_get_page_id( 'myaccount' )).'view-order/'.$order->ID.'" target="_blank">
									'.__('Order: ','idealbiz').'#'.$order->ID.'
									</a><br/>
									<span class="d-none">'.wc_price($o->get_total()).'</span>';
									$ep1=wc_price($o->get_total());
									if($o->get_status()!='pending'){
										echo '<style>.stage_h_adjudication{display:none !important;}</style>';
									}	
								}
								if($o->get_status()!='pending'){
									$s2 = 'active';
								}
								$stotal+=$o->get_total();
								
								
							}
							if($x==2){
								if ( $customer->ID == wp_get_current_user()->ID ){
									$s3o = '<a href="'.get_permalink( wc_get_page_id( 'myaccount' )).'view-order/'.$order->ID.'" target="_blank">
									'.__('Order: ','idealbiz').'#'.$order->ID.'
									</a><br/>
									<span class="d-none">'.wc_price($o->get_total()).'</span>';
									$ep2=wc_price($o->get_total());
									if($o->get_status()!='pending'){
										echo '<style>.stage_h_intermediate{display:none !important;}</style>';
									}
								}
								if($o->get_status()!='pending'){
									$s3 = 'active';
								}
								$stotal+=$o->get_total();
								
								
							}
							if($x==3){
								if ( $customer->ID == wp_get_current_user()->ID ){
									$s4o = '<a href="'.get_permalink( wc_get_page_id( 'myaccount' )).'view-order/'.$order->ID.'" target="_blank">
									'.__('Order: ','idealbiz').'#'.$order->ID.'
									</a><br/>
									<span class="d-none">'.wc_price($o->get_total()).'</span>';
									$ep3=wc_price($o->get_total());
									if($o->get_status()!='pending'){
										echo '<style>.stage_h_conclusion{display:none !important;}</style>';
									}
								}
								if($o->get_status()!='pending'){
									$s4 = 'active';
								}
								$stotal+=$o->get_total();
								
								
							}
							$x++;
						}

				
						$state1 = __( 'Acceptance', 'idealbiz-service-request' );
						$state2 = __( 'Adjudication', 'idealbiz-service-request' );
						$state3 = __( 'Intermediate', 'idealbiz-service-request' );
						$state4 = __( 'Conclusion', 'idealbiz-service-request' );
						$state5 = __( 'Closed', 'idealbiz-service-request' );

						switch ( get_field( 'state' ) ) {
							case 'Closed':
								$s5 = 'active';
							break;
						}

						//retireve last contact id
						$contracts = get_field('contracts',get_the_ID());
						$contract_id=0;
						foreach($contracts as $contract){
							$contract_id = $contract->ID;
							if(get_field('progress',$contract_id) =='Rejected'){
								echo '<style> .proposal_'.$contract_id.'{display: none !important;}</style>';
								$contract_id=0;
							}
						}

						$contract_value   = (int) get_field( 'proposal_value', $contract_id );

						?>
						<style>
							.st1:before{
								content:"<?php echo '0%'; ?>" !important;
							}
							.st2:before{
								content:"<?php echo get_field('percentage_adjudication',$contract_id).'%'; ?>" !important;
							}
							.st3:before{
								content:"<?php echo get_field('percentage_intermediate',$contract_id).'%'; ?>" !important;
							}
							.st4:before{
								content:"<?php echo get_field('percentage_conclusion',$contract_id).'%'; ?>" !important;
							}
							.st5:before{
								content:"<?php echo '100%'; ?>" !important;
							}
						
							.service-request__message-text{width:100%;}
						</style>
						<?php  if(get_field('percentage_adjudication',$contract_id)!=''){ ?>
						<div class="progressbar-wrapper">
							<ul class="progressbar">
								<li class="st1 active">
									<?php echo $state1; 
									if ( in_array( 'consultant', $current_user->roles, true ) ){
										echo '
										<p><span>'.wc_price(get_field('proposal_value',$contract_id)).'</span></p>';
									}else{
										echo '
										<p>'.__('Proposal:','idealbiz').'<br/><span>'.wc_price(get_field('proposal_value',$contract_id)).'</span></p>';
									}
									?>
								</li>
								<li class="st2 <?php echo $s2; ?>">
									<?php echo $state2; ?>
									<p><?php echo $s2o; 
									//if ( in_array( 'consultant', $current_user->roles, true ) ){
										$stage_percentage = (int) get_field( "percentage_adjudication", $contract_id );
										echo '<span>'.($ep1 ? $ep1 : wc_price(( $contract_value * $stage_percentage ) / 100 )).'</span>';
									//}
									?>
								</p>
								</li>
								<li class="st3 <?php echo $s3; ?>">
									<?php echo $state3; ?>
									<p><?php echo $s3o; 
									//if ( in_array( 'consultant', $current_user->roles, true ) ){
										$stage_percentage = (int) get_field( "percentage_intermediate", $contract_id );
										echo '<span>'.($ep2 ? $ep2 : wc_price(( $contract_value * $stage_percentage ) / 100 )).'</span>';
									//}
									?></p>
								</li>
								<li class="st4 <?php echo $s4; ?>">
									<?php echo $state4; ?>
									<p><?php echo $s4o; 
									//if ( in_array( 'consultant', $current_user->roles, true ) ){
										$stage_percentage = (int) get_field( "percentage_conclusion", $contract_id );
										echo '<span>'.($ep3 ? $ep3 : wc_price(( $contract_value * $stage_percentage ) / 100 )).'</span>';
									//}
									?></p>
								</li>
								<li class="st5 <?php echo $s5; ?>">
									<?php echo $state5; 
										echo '<p>';
										if($s4o != ''){
											echo ''.__('Total:','idealbiz').'<br/><span>'.wc_price($stotal).'</span>';
										}
										//if ( in_array( 'consultant', $current_user->roles, true ) ){
										//	echo '
										//	<span>'.wc_price(get_field('proposal_value',$contract_id)).'</span>';
										//}
										echo '</p>';
									?>
								</li>
							</ul>
						</div>
						<div style="clear: both;"></div>
						<hr/>
						<br/>
						<?php  } ?>
						
					<div class="container container--grid p-0">
						<div class="service-request__info">
							<h1 class="listing__title title">
								<?php SingleServiceRequest::title(); ?>
							</h1>

							<div class="listing__meta listing__meta--info">
								<span class="listing__meta-date">
									<?php echo 'REF #' . get_the_ID(); ?>
								</span>
							</div>
						</div>
	
						
						<?php SingleServiceRequest::render_messages(); ?>

					

					

				<?php endwhile; ?>

				</main><?php // .site-main ?>
				</div>
				</div>

				<div class="col-md-4 m-b-20">
				<div class="expert position-relative p-20 dropshadow d-flex flex-column black--color white--background">

				<?php get_sidebar( 'service-message' ); ?>

					<?php
						$current_user = wp_get_current_user();

						if ( in_array( 'consultant', $current_user->roles, true ) ) :
						?>
						<style> 
						.h-expert{
							display:none;
						}
						</style>
						<div class="service-request__contract">

							<?php SingleServiceRequest::render_contracts(); ?>

							<?php get_sidebar( 'service-request-proposal' );  ?>

						</div>
						<?php endif; 
			
						if(get_field( 'state' ) != 'Pending Proposal')	{
							echo '<p style="display: none;">'.get_field( 'state' ).'</p>';
							echo '<style>.sidebar-service-request-proposal {display: none !important; }</style>';
						}
							
						?>
				
				</div>
			
			
			</div></div></section>
<?php
get_footer();
