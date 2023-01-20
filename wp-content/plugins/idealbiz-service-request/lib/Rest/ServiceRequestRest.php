<?php
/**
 * Registers the Service request rest api routes
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Rest;

class ServiceRequestRest extends \WP_REST_Controller {

	/**
	 * All per page.
	 *
	 * @var integer Default value for 'post_per_page' when all items are requested.
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	protected $all_per_page = 1000;

	/**
	 * Register new Rest routes with servicerequest/v1 namespace.
	 *
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function register_routes() {
		$namespace = 'servicerequest/v1';

		register_rest_route(
			$namespace, '/messages/last/(?P<id>[\d]+)', array(
				array(
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_last_item' ),
				),
			)
		);
	}

	public function get_last_item( $request ) {
		$service_request_id = (int) $request['id'];
		$current_user_id    = (int) $request['user_id'];

		$query = new \WP_Query(
			array(
				'post_type'      => 'service_message',
				'posts_per_page' => 1,
				'meta_key'       => 'service_request',
				'meta_value'     => $service_request_id,
			)
		);

		$last_message = '';
		if ( $query->have_posts() ) {
			$query->the_post();

			$message_file_url = get_field( 'message_file_url' );
			if ( $message_file_url ) {
				$message_file_url = '<a href="' . $message_file_url . '" target="_empty" download>file name</a>';
			}

			$last_message = sprintf(
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
				get_the_content(),
				get_avatar_url( get_the_author_meta( 'user_email' ) ),
				$message_file_url,
				$current_user_id === (int) get_the_author_meta( 'ID' ) ? 'is-user' : '',
				get_the_ID()
			);
		}
		wp_reset_postdata();

		return new \WP_REST_Response( $last_message, 200 );
	}
}
