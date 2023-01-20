<?php
namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Fields;

/**
 * Registers the "Active Markets" custom field.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class IntermediateProduct {

	/**
	 * Get field definition.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get() {
		return array(
			'key'               => 'field_5cadcba4cd021',
			'label'             => _x( 'Intermediate product', 'Label', 'idealbiz-service-request' ),
			'name'              => 'intermediate_product',
			'type'              => 'post_object',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'post_type'         => array(
				0 => 'product',
			),
			'taxonomy'          => '',
			'allow_null'        => 0,
			'multiple'          => 0,
			'return_format'     => 'id',
			'ui'                => 1,
		);
	}
}
