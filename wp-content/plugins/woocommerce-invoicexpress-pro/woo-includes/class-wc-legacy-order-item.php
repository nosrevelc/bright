<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Legacy Order Item.
 *
 * @since  0.0.1
 * @author WidgiLabs <dev@widgilabs.com>
 */
class WC_Legacy_Order_Item {

	protected $item;
	protected $order;
	protected $meta_data;

	public function __construct( $item, $order ) {
		$this->item      = $item;
		$this->order     = $order;
		$this->meta_data = $item['item_meta_array'];
	}

	public function get_data() {

		$taxes        = $this->get_taxes();
		$total_tax    = array_sum( $taxes['total'] );
		$subtotal_tax = array_sum( $taxes['subtotal'] );

		return array(
			'id'           => $this->item['name'],
			'order_id'     => $this->order->get_id(),
			'name'         => $this->item['name'],
			'product_id'   => $this->item['product_id'],
			'variation_id' => $this->item['variation_id'],
			'quantity'     => $this->item['qty'],
			'tax_class'    => $this->item['tax_class'],
			'subtotal'     => $this->item['line_subtotal'],
			'subtotal_tax' => $subtotal_tax,
			'total'        => $this->item['line_total'],
			'total_tax'    => $total_tax,
			'taxes'        => $taxes,
			'meta_data'    => array_filter( $this->meta_data, array( $this, 'filter_null_meta' ) ),
		);
	}

	protected function filter_null_meta( $meta ) {
		return ! is_null( $meta->value );
	}

	private function get_taxes() {
		$raw_tax_data = maybe_unserialize( $this->item['item_meta']['_line_tax_data'] );
		$tax_data     = array(
			'total'    => array(),
			'subtotal' => array(),
		);

		if ( ! empty( $raw_tax_data['total'] ) && ! empty( $raw_tax_data['subtotal'] ) ) {
			$tax_data['subtotal'] = array_map( 'wc_format_decimal', $raw_tax_data['subtotal'] );
			$tax_data['total']    = array_map( 'wc_format_decimal', $raw_tax_data['total'] );
			if ( array_sum( $tax_data['subtotal'] ) < array_sum( $tax_data['total'] ) ) {
				$tax_data['subtotal'] = $tax_data['total'];
			}
		}

		return $tax_data;
	}
}
