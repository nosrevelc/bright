<?php
namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Fields;

/**
 * Registers the "Consultant Email Notification" custom field.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class ConsultantEmailNotification {

	/**
	 * Get field definition.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get() {
		return array(
			'key'               => 'field_5cadde80417c8',
			'label'             => _x( 'Consultant Email notification template', 'Label', 'idealbiz-service-request' ),
			'name'              => 'consultant_email_notification_template',
			'type'              => 'wysiwyg',
			'instructions'      => __( 'Don´t forget to include %MY_SERVICE_REQUESTS% to add the link back to the service requests', 'idealbiz-service-request' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'default_value'     => '%MY_SERVICE_REQUESTS%',
			'tabs'              => 'all',
			'toolbar'           => 'full',
			'media_upload'      => 1,
			'delay'             => 0,
		);
	}
}
