<?php
namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Fields;

/**
 * Registers the "Active Markets" custom field.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class ServicesTab {

	/**
	 * Get field definition.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get() {

		$lang = ( 'pt-pt' === ICL_LANGUAGE_CODE ) ? 'pt' : ICL_LANGUAGE_CODE;

		return array(
			'key'               => 'field_5cadcb05cd01f',
			'label'             => _x( 'Services', 'Tab', 'idealbiz-service-request' ) . ' ( ' . $lang . ' )',
			'name'              => '',
			'type'              => 'tab',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'placement'         => 'top',
			'endpoint'          => 0,
		);
	}
}
