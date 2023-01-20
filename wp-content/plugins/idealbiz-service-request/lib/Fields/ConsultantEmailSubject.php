<?php
namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Fields;

/**
 * Registers the "Consultant Email Subject" custom field.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class ConsultantEmailSubject {

	/**
	 * Get field definition.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get() {
		return array(
			'key' => 'field_5a467e8740e7c',
			'label' => 'Consultant Email notification subject',
			'name' => 'consultant_email_notification_subject',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		);
	}
}
