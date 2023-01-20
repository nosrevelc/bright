<?php

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\WooCommerce;

class Helper {

	public function register_hooks() {
 
		add_filter( 'my_account_tabs', array( $this, 'add_my_account_tab' ), 10 );
		add_filter( 'my_account_endpoints', array( $this, 'add_my_account_endpoint' ), 10 );
	}

	public function add_my_account_tab( $tabs ) {

		$tabs[] = 'service_request';

		return $tabs;
	}

	public function add_my_account_endpoint( $endpoints ) {

		$endpoints['service_request'] = array(
			'label' => esc_html__( 'View Your Service Requests', 'idealbiz-service-request' ),
			'class' => new EndpointServiceRequest(),
		);

		return $endpoints;
	}
}
