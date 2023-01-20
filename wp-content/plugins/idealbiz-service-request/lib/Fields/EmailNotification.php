<?php
namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Fields;

/**
 * Registers the "Active Markets" custom field.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class EmailNotification {

	/**
	 * Get field definition.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get() {
		return array(
			'key'               => 'field_5cadde80417c9',
			'label'             => _x( 'Email notification template', 'Label', 'idealbiz-service-request' ),
			'name'              => 'email_notification_template',
			'type'              => 'wysiwyg',
			'instructions'      => __( 'Don´t forget to include %SERVICE_REQUEST_LINK% to add the link back to the service request', 'idealbiz-service-request' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'default_value'     => '%SERVICE_REQUEST_LINK%',
			'tabs'              => 'all',
			'toolbar'           => 'full',
			'media_upload'      => 1,
			'delay'             => 0,
		);
	}
}
