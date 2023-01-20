<?php

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Fields;

class Settings {

	/**
	 * Used to merge service settings fields on idealbiz setting fields array.
	 *
	 * @param array $fields Array containing the idealbiz setting fields.
	 * @return array $fields Modified settings fields array.
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function plugin_settings_fields( $fields ) {

		$service_fields = array(
			'tab_services'                           => ServicesTab::get(),
			'adjudication_product'                   => AdjudicationProduct::get(),
			'intermediate_product'                   => IntermediateProduct::get(),
			'conclusion_product'                     => ConclusionProduct::get(),
			'service_satisfaction_page'              => SatisfactionPage::get(),
			'email_notification_template'            => EmailNotification::get(),
			'stage_completed_template'               => StageCompleted::get(),
			'payment_completed_template'             => PaymentCompleted::get(),
			'proposal_template'                      => ProposalTemplate::get(),
			'coupon_code_email'                      => CouponCodeEmail::get(),
			'service_satisfaction_email'             => ServiceSatisfactionEmail::get(),
			'consultant_email_notification_template' => ConsultantEmailNotification::get(),
			'consultant_email_notification_subject'  => ConsultantEmailSubject::get(),
			'new_user_email_notification_template'   => NewUserEmailNotification::get(),
		);

		return array_merge( $fields, $service_fields );
	}
}
