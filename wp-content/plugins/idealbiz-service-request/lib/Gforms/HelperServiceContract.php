<?php
/**
 * Gravity form processing helper class.
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Gforms;

class HelperServiceContract {

	public function register_hooks() {

		add_filter( 'gform_post_data', array( $this, 'create_service_contract' ), 10, 3 );
		add_action( 'gform_after_create_post', array( $this, 'save_service_contract_data' ), 10, 3 );
		add_action( 'gform_after_create_post', array( $this, 'modify_service_request_data' ), 20, 3 );
		add_action( 'gform_after_create_post', array( $this, 'create_message_thread' ), 30, 3 );
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
	public function create_service_contract( $post_data, $form, $entry ) {

		if ( $post_data['post_title'] !== 'service_contract' ) {
			return $post_data;
		}

		$post_data['post_type'] = 'service_contract';

		foreach ( $form['fields'] as $field ) {

			if ( $field->type === 'hidden' && $field->label === 'service_request_id' ) {

				$post_data['post_title'] = 'service_contract_' . $entry[ $field->id ] . ':' . time();
			}
		}

		return $post_data;
	}

	/**
	 * Used to update the custom fields for the service contract, with the form data.
	 *
	 * @param int   $post_id ID for the newly created service contract.
	 * @param array $entry
	 * @param array $form
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function save_service_contract_data( $post_id, $entry, $form ) {
		$post = get_post( $post_id );

		if ( $post->post_type !== 'service_contract' ) {
			return;
		}

		foreach ( $form['fields'] as $field ) {

			// Set description.
			if ( $field->type === 'textarea' ) {

				update_field( 'description', $entry[ $field->id ], $post_id );
			}

			// Set service request bindind.
			if ( $field->type === 'hidden' && $field->label === 'service_request_id' ) {

				update_field( 'service_request', $entry[ $field->id ], $post_id );
			}

			/**
			 * Set the contract values by matching the adminLabel value with
			 * the custom field key.
			 *
			 * Expected adminLabel values:
			 * - proposal_value;
			 * - percentage_adjudication;
			 * - percentage_intermediate;
			 * - percentage_conclusion;
			 */
			if ( $field->type === 'number' ) {

				update_field( $field->adminLabel, $entry[ $field->id ], $post_id );
			}

			// Set file.
			if ( $field->type === 'fileupload' ) {

				update_field( 'proposal_file', $entry[ $field->id ], $post_id );
			}
		}

		// Set default value for progress.
		update_field( 'progress', 'Pending Approval', $post_id );
	}

	/**
	 * Used to update the custom fields for the service request, with the form data.
	 *
	 * @param int   $post_id ID for the newly created service request.
	 * @param array $entry
	 * @param array $form
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function modify_service_request_data( $post_id, $entry, $form ) {
		$post = get_post( $post_id );

		if ( $post->post_type !== 'service_contract' ) {
			return;
		}

		foreach ( $form['fields'] as $field ) {
 
			// Set service request bindind.
			if ( $field->type === 'hidden' && $field->label === 'service_request_id' ) {

				$service_request_contracts   = get_field( 'contracts', $entry[ $field->id ] );
				$service_request_contracts[] = $post_id;

				// Add the contract to service request metadata.
				update_field( 'contracts', $service_request_contracts, $entry[ $field->id ] );

				// Update the service request state.
				update_field( 'state', 'Waiting on Customer', $entry[ $field->id ] );
			}
		}
	}

	/**
	 * Creates the first message to the service request message thread.
	 *
	 * @param int   $post_id ID for the newly created service request.
	 * @param array $entry
	 * @param array $form
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function create_message_thread( $post_id, $entry, $form ) {
		$post = get_post( $post_id );

		//var_dump('1');


		if ( $post->post_type !== 'service_contract' ) {
			return;
		}

		$post_data = array(
			'post_type'   => 'service_message',
			'post_status' => 'publish',
			'post_title'  => time(),
			'post_author' => $post->post_author,
		);

		$service_request_id = 0;

		foreach ( $form['fields'] as $field ) {

			if ( $field->type === 'textarea' ) {

				if ( ! empty( $entry[ $field->id ] ) ) {

					$post_data['post_content'] = wp_kses_post( $entry[ $field->id ] );
				}
			}

			// Set service request bindind.
			if ( $field->type === 'hidden' && $field->label === 'service_request_id' ) {

				$service_request_id = $entry[ $field->id ];
			}
		}

		/**
		 * Append the template after the consultant message.
		 */
		//$post_data['post_content'] .= get_field( 'proposal_template', 'option' );
		

		$post_data['post_content'] .= '<br/><br/><div class="small m-t-10"><div class="col-xs-12">'.__( 'Click the link below to open the proposal:','idealbiz').'</div> %PROPOSAL_LINK% </div><br/>
		<br/>';
		$saux = strtolower(get_field('stage',$post_id));
		if($saux==''){
			$saux='adjudication';
		}		
		$post_data['post_content'] .= '
		<div class="small m-t-10 h-expert stage_h_'.$saux.' proposal_'.$post_id.'"><div class="col-xs-12">'.__( 'To accept the proposal simply click the link below and you will be redirected to the checkout page.', 'idealbiz-service-request' ).'</div> %ORDER_LINK% </div><br/>';

		if ( false !== strpos( $post_data['post_content'], '%ORDER_LINK%' ) ) {

			$lang = '';
			if ( function_exists('icl_object_id') ) {
				$lang = 'lang='.ICL_LANGUAGE_CODE.'&';
			}

			$order_link = sprintf(
				'<div class="d-table m-auto m-t-10 propose proposal_'.$post_id.'"><a class="btn btn-blue blue--hover h-expert" href="%1$s" title="%2$s">%3$s</a> <a class="btn btn-blue blue--hover h-expert m-l-10" style="background: #777777 !important;" href="?reject_contract_id='.$post_id.'" title="'.__( 'Reject', 'idealbiz-service-request').'">'.__( 'Reject', 'idealbiz-service-request').'</a></div>',
				get_admin_url() . 'admin-post.php?'.$lang.'action=accept_contract&id=' . $post_id,
				__( 'Accept and pay link', 'idealbiz-service-request' ),
				__( 'Accept and pay', 'idealbiz-service-request' )
			);

			$post_data['post_content'] = str_replace( '%ORDER_LINK%', $order_link, $post_data['post_content'] );



		}

		if ( false !== strpos( $post_data['post_content'], '%PROPOSAL_LINK%' ) ) {

			$proposal_link = sprintf(
				'<div class="d-table m-auto m-t-10"><a href="%1$s" title="%2$s" download>%3$s</a></div>',
				get_field( 'proposal_file', $post_id ),
				__( 'Download proposal link.', 'idealbiz-service-request' ),
				__( 'Download proposal', 'idealbiz-service-request' )
			);

			$post_data['post_content'] = str_replace( '%PROPOSAL_LINK%', $proposal_link, $post_data['post_content'] );


		}




		$message_id = wp_insert_post( $post_data );

		if ( $message_id ) {
			update_field( 'service_request', $service_request_id, $message_id );

			$service_message = new HelperServiceMessage();
			$service_message->send_service_message_notification( $message_id );
		}
	}
}
