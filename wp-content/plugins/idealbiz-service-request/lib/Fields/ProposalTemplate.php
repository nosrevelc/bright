<?php
namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Fields;

/**
 * Registers the "Active Markets" custom field.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class ProposalTemplate {

	/**
	 * Get field definition.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get() {
		return array(
			'key'               => 'field_5cade11daedc5',
			'label'             => _x( 'Proposal template', 'Label', 'idealbiz-service-request' ),
			'name'              => 'proposal_template',
			'type'              => 'wysiwyg',
			'instructions'      => __( 'You can use the following placeholders.
%ORDER_LINK% - represent the link to accept and pay for the order.
%PROPOSAL_LINK% - represent the proposal file download link.', 'idealbiz-service-request' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'default_value'     => __( 'Click the link below to open the proposal: %PROPOSAL_LINK%

To accept the proposal simply click the link below and you will be redirected to the checkout page. %ORDER_LINK%.', 'idealbiz-service-request' ),
			'tabs'              => 'all',
			'toolbar'           => 'full',
			'media_upload'      => 1,
			'delay'             => 0,
		);
	}
}
