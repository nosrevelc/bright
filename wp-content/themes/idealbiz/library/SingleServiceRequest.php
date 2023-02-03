<?php
/**
 * iDealBiz
 *
 * WARNING: This file is part of the iDealBiz theme. DO NOT edit this file
 * under any circumstances, as the changes will be lost in the case of a theme update.
 * Please do all modifications in the form of a child theme.
 *
 * @since   1.0.0
 * @package iDealBiz\Templates
 * @author  WidgiLabs
 * @license GPL-2.0+
 * @link    http://widgilabs.com/
 */


/**
 * Single listing template.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class SingleServiceRequest {

	/**
	 * Render the title.
	 *
	 * @since 1.0.0
	 */
	public static function title() {
		printf(
			'%s',
			get_the_title()
		);
	}

	/**
	 * Used to render the message list html.
	 *
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public static function render_messages() {
		$args = array(
			'post_type'      => 'service_message',
			'order_by'       => 'date',
			'order'          => 'ASC',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'   => 'service_request',
					'value' => get_the_ID(),
				),
			),
		);

		// If buyer add visibility meta query array.
		$current_user = wp_get_current_user();
		$message_query = new \WP_Query( $args );
		$messages = '';
		$fm= '
			<p style="font-size:13px;"><span class="small m-r-10">'.__('Nome:','idealbiz').'</span>'.get_field('customer')->display_name.'</p>';
			//<p style="font-size:13px;"><span class="small m-r-10">'.__('Email:','idealbiz').'</span>'.get_field('customer')->user_email.'</p>
			//<p style="font-size:13px;"><span class="small m-r-10">'.__('Phone:','idealbiz').'</span>'.get_field('service_request_phone').'</p>
		$fm .= '<p style="font-size:13px;"><span class="small m-r-10">'.__('Delivery date:','idealbiz').'</span>'.get_field('delivery_date').'</p>
			<p style="font-size:13px;"><span class="small m-r-10">'.__('Message:','idealbiz').'</span>'.get_field('message').'</p>
			';
		$messages .= sprintf(
			'<li id="message_id_%7$s" class="service-request__message %6$s">
				<img src="%4$s"/>
				<div class="service-request__message-text">
					<ul class="service-request__meta">
						<li class="service-request__user">%1$s</li>
						<li class="service-request__date">%2$s</li>
					</ul>
					%3$s
					%5$s
				</div>
			</li>', 
			get_field('customer')->display_name,
			get_the_date( 'Y-m-d H:i'),
			$fm,
			get_avatar_url( get_field('customer')->user_email ),
			$message_file_url,
			$current_user->ID === (int) get_field('customer')->ID ? 'is-user' : '',
			get_the_ID()
		);



			while ( $message_query->have_posts() ) {
				$message_query->the_post();

				$message_file_url = get_field( 'message_file_url' );
				if ( $message_file_url ) {
					$file_name = basename( $message_file_url );

					$message_file_url = '<a href="' . $message_file_url . '" class="small" target="_empty" download>' . $file_name . '</a>';
				}

				$message_html = sprintf(
					'<li id="message_id_%7$s" class="service-request__message %6$s">
						<img src="%4$s"/>
						<div class="service-request__message-text">
							<ul class="service-request__meta">
								<li class="service-request__user">%1$s</li>
								<li class="service-request__date">%2$s</li>
							</ul>
							%3$s
							%5$s
						</div>
					</li>', 
					get_the_author_meta( 'display_name' ),
					get_the_date( 'Y-m-d H:i' ),
					\wpautop( get_the_content() ),
					get_avatar_url( get_the_author_meta( 'user_email' ) ),
					$message_file_url,
					$current_user->ID === (int) get_the_author_meta( 'ID' ) ? 'is-user' : '',
					get_the_ID()
				);

				$messages .= $message_html;
			}
			wp_reset_postdata();

			printf(
				'<ul class="service-request__message-list">
				%1$s
			</ul>',
				wp_kses_post( $messages )
			);
	}

	/**
	 * Render the contracts list in the service request view, if current user is
	 * consultant.
	 *
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public static function render_contracts() {
		$args = array(
			'post_type'      => 'service_contract',
			'order_by'       => 'date',
			'order'          => 'ASC',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'   => 'service_request',
					'value' => get_the_ID(),
				),
			),
		);

		// The buyer shouldn't be here in the first place.
		$current_user = wp_get_current_user();
		if ( in_array( 'buyer', $current_user->roles, true ) ) {
			return;
		}

		$contract_query = new \WP_Query( $args );

		$contracts = '';

		if ( $contract_query->have_posts() ) {
			while ( $contract_query->have_posts() ) {
				$contract_query->the_post();

				$message_file_url = get_field( 'message_file_url' );
				if ( $message_file_url ) {
					$message_file_url = '<a href="' . $message_file_url . '" target="_empty" download>file name</a>';
				}

				$stage_value = get_field( 'stage' );
				switch ( $stage_value ) {
					case 'Adjudication':
						$stage = __( 'Adjudication', 'idealbiz' );
						break;

					case 'Intermediate':
						$stage = __( 'Intermediate', 'idealbiz' );
						break;

					case 'Conclusion':
						$stage = __( 'Conclusion', 'idealbiz' );
						break;
					default:
						$stage = __( 'Proposal sent', 'idealbiz' );
						break;
				}
				$disable_btn='';
				$progress_value = get_field( 'progress' );
				switch ( $progress_value ) {
					case 'Pending Approval':
						$progress = __( 'Pending Approval', 'idealbiz' );
						break;

					case 'Pending Payment':
						$progress = __( 'Pending Payment', 'idealbiz' );
						$disable_btn="<style>
							.contract-link{
								cursor:default;
								background: #777777 !important;
								opacity:0.6;
							}
						</style>
						<script>
						jQuery(document).ready(($) => {
							$('.contract-link').click(function(event){
								event.preventDefault();
								return false;
							});
						});
						</script>";
						break;

					case 'In Progress':
						$progress = __( 'In Progress', 'idealbiz' );
						break;

					case 'Closed':
						$progress = __( 'Closed', 'idealbiz' );
						break;

					case 'Rejected':
						$progress = __( 'Rejected', 'idealbiz' );
						break;	
				}

				$message_html = sprintf(
					'
					<div class="contract-info__meta">
						<span>%1$s</span>
						<a href="%4$s" title="%5$s" class="button button--alt">%6$s</a>
					</div>
					<div class="contract-info__phase">
						<span>%9$s %3$s</span>
						<span>%8$s %2$s</span>
					</div>%7$s',
					get_the_date( 'Y-m-d H:i' ), //1
					$progress, //2
					$stage, //3
					get_post_permalink(), //4
					__( 'See Service Contract', 'idealbiz' ), //5
					__( 'See the contract', 'idealbiz' ), //6
					self::get_stage_action( $stage_value, get_the_ID() ), //7
					__( 'Status:', 'idealbiz' ), // 8
					__( 'Phase:', 'idealbiz' ) // 9
				);

				$contracts .= $message_html;
			}
			wp_reset_postdata();
		}

		printf(
			'<div class="contract-info">
				%1$s
			</div>'.$disable_btn,
			wp_kses_post( $contracts )
		);
	}

	/**
	 * Used to output the action button for the consultant to move the contract
	 * forward. It only output if the stage is Intermediate/Conclusion.
	 *
	 * @param string $stage Current contract stage:
	 *                      ( Adjudication, Intermediate, Conclusion ).
	 * @return string
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public static function get_stage_action( $stage, $post_id ) {
		// If contract is closed don't show any action.
		$contract_progress = get_field( 'progress', $post_id );
		if ( 'closed' === strtolower( $contract_progress ) ) {
			$stage = 'closed';
		}

		$lang = '';
		if ( function_exists('icl_object_id') ) {
			$lang = 'lang='.ICL_LANGUAGE_CODE.'&';
		}

		switch ( strtolower( $stage ) ) {
			case 'intermediate':
				$action_href  = get_admin_url() . 'admin-post.php?'.$lang.'action=stage_completed&stage=' . strtolower( $stage ) . '&id=' . $post_id;
				$action_title = __( 'Intermediate completed action', 'idealbiz' );
				$action_text  = __( 'Finish Intermediate Phase', 'idealbiz' );
				break;

			case 'conclusion':
				$action_href  = get_admin_url() . 'admin-post.php?'.$lang.'action=stage_completed&stage=' . strtolower( $stage ) . '&id=' . $post_id;
				$action_title = __( 'Conclusion action', 'idealbiz' );
				$action_text  = __( 'Finish contract', 'idealbiz' );
				break;

			default:
				return '';
		}

		return sprintf( 
			'<a href="%1$s" title="%2$s" class="btn btn-blue contract-link">%3$s</a>',
			$action_href,
			$action_title,
			$action_text 
		);
	}

	/**
	 * Used to prevent access to the service request single page.
	 *
	 * Redirects to home page if user not authorized.
	 *
	 * @return bool Returns true if is admin, author or consultant
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public static function protect_service_request() {
		$user = wp_get_current_user();

		if ( $user->ID === 0 ) {
			wp_redirect( get_bloginfo( 'url' ) . '/login/' );
		}

		$service_request = get_queried_object();

		$is_admin      = in_array( 'administrator', $user->roles, true );
		$is_author     = $user->ID === (int) $service_request->post_author;
		$is_consultant = in_array( 'consultant', $user->roles, true );

		// If consultant validates if is the consultant assigned to service request.
		if ( $is_consultant ) {
			$consultant = get_field( 'consultant', $service_request->ID );

			// Re-validate is consultant.
			$is_consultant = $user->ID === $consultant->ID;
		}

		// If one of this is true, move on.
		if ( $is_admin || $is_author || $is_consultant ) {
			return true;
		}

		wp_redirect( get_bloginfo( 'url' ) . '/login/' );
	}
}