<?php
namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Fields;

/**
 * Registers the "Active Markets" custom field.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class CouponCodeEmail {

	/**
	 * Get field definition.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get() {
		return array(
			'key'               => 'field_5cd0bcc82d5e6',
			'label'             => 'Coupon code email',
			'name'              => 'coupon_code_email',
			'type'              => 'wysiwyg',
			'instructions'      => 'You can use the following placeholders.
%COUPON_CODE% - represent the coupon code.',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'default_value'     => '%COUPON_CODE%',
			'tabs'              => 'all',
			'toolbar'           => 'full',
			'media_upload'      => 1,
			'delay'             => 0,
		);
	}
}
