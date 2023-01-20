<?php
namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Fields;

/**
 * Registers the "Active Markets" custom field.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class PaymentCompleted {

	/**
	 * Get field definition.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get() {
		return array(
			'key'               => 'field_5cade0a4417cb',
			'label'             => _x( 'Payment completed template', 'Label', 'idealbiz-service-request' ),
			'name'              => 'payment_completed_template',
			'type'              => 'wysiwyg',
			'instructions'      => __( 'You can use the following placeholders. %STAGE% - represent the completed stage.', 'idealbiz-service-request' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'default_value'     => __( 'Payment for the', 'idealbiz-service-request' ).' %STAGE% '.__('completed.', 'idealbiz-service-request' ),
			'tabs'              => 'all',
			'toolbar'           => 'full',
			'media_upload'      => 1,
			'delay'             => 0,
		);
	}
}
