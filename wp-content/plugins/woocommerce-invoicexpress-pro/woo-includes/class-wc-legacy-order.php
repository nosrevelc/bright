<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Legacy Order.
 *
 * @since  0.0.1
 * @author WidgiLabs <dev@widgilabs.com>
 */
class WC_Legacy_Order extends \WC_Order {

	public function get_id() {
		return $this->id;
	}

	public function get_billing_first_name() {
		return $this->billing_first_name;
	}

	public function get_billing_last_name() {
		return $this->billing_last_name;
	}

	public function get_billing_country() {
		return $this->billing_country;
	}

	public function get_billing_email() {
		return $this->billing_email;
	}

	public function get_billing_company() {
		return $this->billing_company;
	}

	public function get_billing_phone() {
		return $this->billing_phone;
	}

	public function get_billing_address_1() {
		return $this->billing_address_1;
	}

	public function get_billing_address_2() {
		return $this->billing_address_2;
	}

	public function get_billing_postcode() {
		return $this->billing_postcode;
	}

	public function get_billing_city() {
		return $this->billing_city;
	}

	public function get_date_completed() {
		return $this->completed_date;
	}
}
