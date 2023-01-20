<?php
/*
Plugin Name: WooCommerce InvoiceXpress Pro
Plugin URI: http://widgilabs.pt/plugin-woocommerce-invoicexpress-pro/
Description: Automatically create InvoiceXpress invoices when sales are made.
Version: 0.2.3
Author: WidgiLabs
Author URI: http://www.widgilabs.pt
License: GPLv2
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Aelia\WC\EU_VAT_Assistant;
//use WidgiLabs\WP\Theme\iDealBiz\Options;

/**
 * Class woocommerce_invoicexpress
 */

/**
 * Required functions
 **/
if ( ! function_exists( 'wc_ie_is_woocommerce_active' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}
require_once( 'class-wl-license-manager-client.php' );

if ( wc_ie_is_woocommerce_active() ) {

	if ( is_admin() ) {
		$license_manager = new WooCommerceInvoiceXpress_License_Manager_Client(
			'woocommerce-invoicexpress-pro',
			'WooCommerce Invoicexpress Pro',
			'wc_invoicexpress',
			'http://widgilabs.pt/api/license-manager/v1',
			'plugin',
			__FILE__
		);
	}

	add_action( 'plugins_loaded', 'woocommerce_invoicexpress_init', 0 );
	function woocommerce_invoicexpress_init() {

		if ( WooCommerceInvoiceXpress_License_Manager_Client::activated() !== 'Activated' )
		{
			return;
		}

		new WooCommerceInvoiceXpress();
		require_once( 'woo-includes/contact-mail-setup.php' );

		if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
			require_once( 'woo-includes/class-wc-legacy-order.php' );
			require_once( 'woo-includes/class-wc-legacy-order-item.php' );
		}
	}

	add_action( 'init', 'localization_init' );
	function localization_init() {
		load_plugin_textdomain(
			'wc_invoicexpress',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}

	class WooCommerceInvoiceXpress {

		function __construct() {
			require_once( 'InvoiceXpressRequest-PHP-API/lib/InvoiceXpressRequest.php' );

			$this->subdomain = get_option( 'wc_ie_subdomain' );
			$this->token     = get_option( 'wc_ie_api_token' );

			add_action( 'admin_init', array( &$this, 'settings_init' ) );
			add_action( 'admin_menu', array( &$this, 'menu' ) );

			switch ( get_option( 'wc_ie_order_state' ) ) {
				case 'processing':
					add_action( 'woocommerce_order_status_processing',array( &$this, 'process' ) );
					break;
				case 'completed':
					add_action( 'woocommerce_order_status_completed',array( &$this, 'process' ) );
					break;
				case 'on-hold':
					add_action( 'woocommerce_order_status_on-hold',array( &$this, 'process' ) );
					break;
			}

			add_action( 'woocommerce_order_actions',          array( &$this, 'my_woocommerce_order_actions' ), 10, 1 );
			add_action( 'woocommerce_order_action_my_action', array( &$this, 'do_my_action' ), 10, 1 );

			/* add NIF field enabled */
			if ( intval( get_option( 'wc_ie_add_nif_field' ) ) === 1 ) {
				add_filter( 'woocommerce_checkout_fields' ,      array( &$this, 'wc_ie_nif_checkout' ) );
				add_filter( 'woocommerce_address_to_edit',       array( &$this, 'wc_ie_nif_my_account' ) );
				add_action( 'woocommerce_customer_save_address', array( &$this, 'wc_ie_my_account_save' ), 10, 2 );
				add_action(
					'woocommerce_admin_order_data_after_billing_address',
					array( &$this, 'wc_ie_nif_admin' ),
				10, 1 );
				add_action( 'woocommerce_checkout_process',      array( &$this, 'wc_ie_nif_validation' ) );
			}
		}

		function my_woocommerce_order_actions( $actions ) {
			$actions['my_action'] = 'Create Invoice (InvoiceXpress)';
			return $actions;
		}

		function do_my_action( $order ) {

			if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
				$order = new WC_Legacy_Order( $order );
			}

			// Do something here with the WooCommerce $order object
			$this->process( $order->get_id() );

		}

		function menu() {
			add_submenu_page(
				'woocommerce',
				__( 'InvoiceXpress Pro', 'wc_invoicexpress' ),
				__( 'InvoiceXpress Pro', 'wc_invoicexpress' ) ,
				'manage_woocommerce',
				'woocommerce_invoicexpress',
				array( &$this, 'options_page' )
			);
		}

		function settings_init() {
			global $woocommerce;

			wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css' );

			$general_settings = array(
				array(
					'name'		=> 'wc_ie_settings',
					'title' 	=> __( 'InvoiceXpress for WooCommerce General Settings', 'wc_invoicexpress' ),
					'page'		=> 'woocommerce_invoicexpress_general',
					'settings'	=> array(
						array(
							'name'  => 'wc_ie_subdomain',
							'title' => __( 'Subdomain', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_api_token',
							'title' => __( 'API Token', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_create_invoice',
							'title' => __( 'Create Invoice', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_invoice_draft',
							'title' => __( 'Invoice as Draft', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_send_invoice',
							'title' => __( 'Send Invoice', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_create_simplified_invoice',
							'title' => __( 'Create Simplified Invoice', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_order_state',
							'title' => __( 'Order State', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_sequence_id',
							'title' => __( 'Sequence', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_send_options',
							'title' => __( 'Send Options', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_tax_exemption_reason_options',
							'title' => __( 'Tax exemption reason', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_observations',
							'title' => __( 'Invoice observations', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_add_nif_field',
							'title' => __( 'Add NIF field', 'wc_invoicexpress' ),
						),
					),
				),
			);

			foreach ( $general_settings as $sections => $section ) {

				add_settings_section(
					$section['name'],
					$section['title'],
					array( &$this, $section['name'] ),
					$section['page']
				);

				foreach ( $section['settings'] as $setting => $option ) {

					add_settings_field(
						$option['name'],
						$option['title'],
						array( &$this, $option['name'] ),
						$section['page'],
						$section['name']
					);

					register_setting( $section['page'], $option['name'] );
					$option['name'] = get_option( $option['name'] );
				}
			}

			$email_settings = array(
				array(
					'name'		=> 'wc_ie_settings',
					'title' 	=> __( 'InvoiceXpress for WooCommerce Email Settings', 'wc_invoicexpress' ),
					'page'		=> 'woocommerce_invoicexpress_email',
					'settings'	=> array(
						array(
							'name'  => 'wc_ie_email_subject',
							'title' => __( 'Email Subject', 'wc_invoicexpress' ),
						),
						array(
							'name'  => 'wc_ie_email_body',
							'title' => __( 'Email Body', 'wc_invoicexpress' ),
						),
					),
				),
			);

			foreach ( $email_settings as $sections => $section ) {

				add_settings_section(
					$section['name'],
					$section['title'],
					array( &$this, $section['name'] ),
					$section['page']
				);

				foreach ( $section['settings'] as $setting => $option ) {

					add_settings_field(
						$option['name'],
						$option['title'],
						array( &$this, $option['name'] ),
						$section['page'],
						$section['name']
					);

					register_setting( $section['page'], $option['name'] );
					$option['name'] = get_option( $option['name'] );
				}
			}
		}

		function wc_ie_tabs( $current = 'general' ) {

			$tabs = array(
				'general' => __( 'General', 'wc_invoicexpress' ),
				'email'   => __( 'E-Mail', 'wc_invoicexpress' ),
				'support' => __( 'Support', 'wc_invoicexpress' ),
			);

			echo '<div id="icon-themes" class="icon32"><br></div>';
			echo '<h2 class="nav-tab-wrapper">';

			foreach ( $tabs as $tab => $name ) {
				$class = ( $tab === $current ) ? ' nav-tab-active' : '';

				echo '<a class="nav-tab' . $class . '" href="?page=woocommerce_invoicexpress&tab=' . $tab . '">' . $name . '</a>';
			}

			echo '</h2>';
		}

		function options_page() {
			global $pagenow;

			if ( $pagenow === 'admin.php' && $_GET['page'] === 'woocommerce_invoicexpress' ) {
			?>
			<div class="wrap woocommerce">
				<form method="post" id="mainform" action="options.php">
				<?php
				if ( isset( $_GET['tab'] ) ) {
					$this->wc_ie_tabs( $_GET['tab'] );
				} else {
					$this->wc_ie_tabs( 'general' );
				}

				$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

				switch ( $tab ) {
					case 'general':
						?>
							<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
							<h2><?php _e( 'InvoiceXpress for WooCommerce', 'wc_invoicexpress' ); ?></h2>
							<?php settings_fields( 'woocommerce_invoicexpress_general' ); ?>
							<?php do_settings_sections( 'woocommerce_invoicexpress_general' ); ?>
							<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'wc_invoicexpress' ) ?>" /></p>
							</form>
						</div>
						<?php
						break;
					case 'email':
						?>
							<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
							<h2><?php _e( 'InvoiceXpress for WooCommerce', 'wc_invoicexpress' ); ?></h2>
							<?php settings_fields( 'woocommerce_invoicexpress_email' ); ?>
							<?php do_settings_sections( 'woocommerce_invoicexpress_email' ); ?>
							<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'wc_invoicexpress' ) ?>" /></p>
							</form>
						</div>
						<?php
						break;
					case 'support':
						$this->wc_ie_support_form();
						break;
				}
			}
		}

		function wc_ie_settings() {
			echo '<p>'.__( 'Please fill in the necessary settings below. InvoiceXpress for WooCommerce works by creating an invoice when order status is updated to processing.', 'wc_invoicexpress' ).'</p>';
		}

		function wc_ie_subdomain() {
			echo '<input type="text" name="wc_ie_subdomain" id="wc_ie_subdomain" value="' . get_option( 'wc_ie_subdomain' ) . '" />';
			echo ' <label for="wc_ie_subdomain">' . __( 'When you access InvoiceXpress you use https://<b>subdomain.app</b>.invoicexpress.com ( <a href="http://widgilabs.bitbucket.org/static/invoicexpress_subdomain_faq.html" target="_blank">Help me find my subdomain</a> )', 'wc_invoicexpress' ) . '</label>';
		}

		function wc_ie_api_token() {
			echo '<input type="password" name="wc_ie_api_token" id="wc_ie_api_token" value="' . get_option( 'wc_ie_api_token' ) . '" />';
			echo ' <label for="wc_ie_api_token">' . __( 'Go to Settings >> API in InvoiceXpress to get one.', 'wc_invoicexpress' ) . '</label>';
		}

		function wc_ie_create_invoice() {
			$checked = intval( get_option( 'wc_ie_create_invoice' ) ) === 1 ? 'checked="checked"' : '';
			echo '<input type="hidden" name="wc_ie_create_invoice" value="0" />';
			echo '<input type="checkbox" name="wc_ie_create_invoice" id="wc_ie_create_invoice" value="1" ' . $checked . ' />';
			echo ' <label for="wc_ie_create_invoice">' . __( 'Create invoices for orders that come in, otherwise only the client is created (<i>recommended</i>).', 'wc_invoicexpress' ) . '</label>';
		}

		function wc_ie_send_invoice() {
			$checked = intval( get_option( 'wc_ie_send_invoice' ) ) === 1 ? 'checked="checked"' : '';
			echo '<input type="hidden" name="wc_ie_send_invoice" value="0" />';
			echo '<input type="checkbox" name="wc_ie_send_invoice" id="wc_ie_send_invoice" value="1" ' . $checked . ' />';
			echo ' <label for="wc_ie_send_invoice">' . __( 'Send the client an e-mail with the order invoice attached (<i>recommended</i>).', 'wc_invoicexpress' ) . '</label>';
		}

		function wc_ie_add_nif_field() {
			$checked = intval( get_option( 'wc_ie_add_nif_field' ) ) === 1 ? 'checked="checked"' : '';
			echo '<input type="hidden" name="wc_ie_add_nif_field" value="0" />';
			echo '<input type="checkbox" name="wc_ie_add_nif_field" id="wc_ie_add_nif_field" value="1" ' . $checked . ' />';
			echo ' <label for="wc_ie_add_nif_field">' . __( 'Add a client NIF field to the checkout form (<i>recommended</i>).', 'wc_invoicexpress' ) . '</label>';
		}

		function wc_ie_create_simplified_invoice() {
			$checked = intval( get_option( 'wc_ie_create_simplified_invoice' ) ) === 1 ? 'checked="checked"' : '';
			echo '<input type="hidden" name="wc_ie_create_simplified_invoice" value="0" />';
			echo '<input type="checkbox" name="wc_ie_create_simplified_invoice" id="wc_ie_create_simplified_invoice" value="1"' . $checked . ' />';
			echo ' <label for="wc_ie_create_simplified_invoice">' . __( 'Create simplified invoices. Only available for Portuguese accounts.', 'wc_invoicexpress' ) . '</label>';
		}

		function wc_ie_sequence_id() {

			$response  = array();
			$sequences = array();
			$options   = array();

			if ( ! $this->subdomain || ! $this->token ) {
				echo __( 'You should be able to choose the sequence for your invoices once you establish a successful connection.', 'wc_invoicexpress' );
				return;
			}

			InvoiceXpressRequest::init( $this->subdomain, $this->token );

			$invoice = new InvoiceXpressRequest( 'sequences.get' );

			$invoice->request();

			if ( $invoice->success() ) {
				$response = $invoice->getResponse();

				if ( isset( $response['sequence'] ) ) {
					$sequences = $response['sequence'];
				}

				if ( count( $sequences ) > 0 ) {
					foreach ( $sequences as $key => $val ) {

						if ( is_int( $key ) ) {
							// assume multiple sequences available
							$seq_id = $val['id'];
							$options[ $seq_id ] = $val['serie'];
						} else {
							// assume only one sequence
							if ( $key === 'id' ) {
								$seq_id = $val;
							}
							if ( $key === 'serie' ) {
								$options[ $seq_id ] = $val;
							}
						}
					}
				}
				$sequences = $options;

				echo '<select name="wc_ie_sequence_id" >';
				foreach ( $sequences as $key => $value ) {
					$selected = ( get_option( 'wc_ie_sequence_id' ) == $key ) ? 'selected' : '';
					echo '<option value="' . $key . '" ' . $selected.'>' . $value . '</option>';
				}

				echo '</select>';
				echo ' <label for="wc_ie_wc_ie_sequence_id">' . __( 'The sequence to use for the invoices.', 'wc_invoicexpress' ) . '</label>';
			} else {
				echo __( 'You will be able to choose the sequence for your invoices once you establish a successful connection.', 'wc_invoicexpress' );
			}

			return;
		}

		function wc_ie_send_options() {

			$send_options = array(
				'1' => __( 'send only the original', 'wc_invoicexpress' ),
				'2' => __( 'send the original and duplicate', 'wc_invoicexpress' ),
				'3' => __( 'send the original, duplicate and triplicate', 'wc_invoicexpress' ),
			);

			echo '<select name="wc_ie_send_options" >';
			foreach ( $send_options as $key => $value ) {
				$selected = ( get_option( 'wc_ie_send_options' ) === $key ) ? 'selected' : '';
				echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
			}
		}

		function wc_ie_tax_exemption_reason_options() {

			$tax_exemption_reason_options = array(
				'M00' => __( 'Sem Isenção.', 'wc_invoicexpress' ),
				'M16' => __( 'Isento Artigo 14º do RITI.', 'wc_invoicexpress' ),
				'M15' => __( 'Regime da margem de lucro–Objetos de coleção e antiguidades.', 'wc_invoicexpress' ),
				'M14' => __( 'Regime da margem de lucro – Objetos de arte.', 'wc_invoicexpress' ),
				'M13' => __( 'Regime da margem de lucro – Bens em segunda mão.', 'wc_invoicexpress' ),
				'M12' => __( 'Regime da margem de lucro – Agências de viagens. ', 'wc_invoicexpress' ),
				'M11' => __( 'Regime particular do tabaco.', 'wc_invoicexpress' ),
				'M10' => __( 'IVA – Regime de isenção.', 'wc_invoicexpress' ),
				'M99' => __( 'Não sujeito; não tributado (ou similar).', 'wc_invoicexpress' ),
				'M09' => __( 'IVA ‐ não confere direito a dedução.', 'wc_invoicexpress' ),
				'M08' => __( 'IVA – autoliquidação.', 'wc_invoicexpress' ),
				'M07' => __( 'Isento Artigo 9º do CIVA. ', 'wc_invoicexpress' ),
				'M06' => __( 'Isento Artigo 15º do CIVA.', 'wc_invoicexpress' ),
				'M05' => __( 'Isento Artigo 14º do CIVA.', 'wc_invoicexpress' ),
				'M04' => __( 'Isento Artigo 13º do CIVA.', 'wc_invoicexpress' ),
				'M03' => __( 'Exigibilidade de caixa.', 'wc_invoicexpress' ),
				'M02' => __( 'Artigo 6º do Decreto‐Lei nº 198/90, de 19 de Junho.', 'wc_invoicexpress' ),
				'M01' => __( 'Artigo 16º nº 6 do CIVA.', 'wc_invoicexpress' ),
			);

			echo '<select name="wc_ie_tax_exemption_reason_options" >';
			foreach ( $tax_exemption_reason_options as $key => $value ) {
				$selected = ( get_option( 'wc_ie_tax_exemption_reason_options' ) === $key ) ? 'selected' : '';
				echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
			}
		}

		function wc_ie_observations() {
			echo '<textarea name="wc_ie_observations" cols="40" rows="5">';
			echo get_option( 'wc_ie_observations' );
			echo '</textarea><br/>';
			echo ' <label for="wc_ie_observations">' . __( 'This will overwrite InvoiceXpress > Settings > Customize Document > Observations.', 'wc_invoicexpress' ) . '</label>';
		}

		function wc_ie_invoice_draft() {
			$checked = intval( get_option( 'wc_ie_invoice_draft' ) ) === 1 ? 'checked="checked"' : '';
			echo '<input type="hidden" name="wc_ie_invoice_draft" value="0" />';
			echo '<input type="checkbox" name="wc_ie_invoice_draft" id="wc_ie_invoice_draft" value="1" ' . $checked . ' />';
			echo ' <label for="wc_ie_invoice_draft">' . __( 'Create invoice as draft.', 'wc_invoicexpress' ) . '</label>';
		}

		function wc_ie_order_state() {
			$states = array(
				'processing' => 'Processing',
				'completed'  => 'Completed',
				'on-hold'    => 'On Hold',
			);

			echo '<input type="hidden" name="wc_ie_order_state" value="0" />';
			echo '<select name="wc_ie_order_state" >';

			foreach ( $states as $key => $value ) {
				$selected = ( get_option( 'wc_ie_order_state' ) === $key ) ? 'selected' : '';
				echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
			}

			echo '</select>';
			echo ' <label for="wc_ie_invoice_draft">' . __( 'The state in which the order must be to send the invoice.', 'wc_invoicexpress' ) . '</label>';
		}

		/**
		* Return the shipping tax status for an order (props @aaires)
		*
		* @param  WC_Order
		* @return string|bool - status if exists, false otherwise
		*/
		function wc_ie_get_order_shipping_tax_status( $order ) {
			WC()->shipping->load_shipping_methods();

			$shipping_tax_status = false;
			$active_methods      = array();
			$shipping_methods    = WC()->shipping->get_shipping_methods();

			foreach ( $shipping_methods as $id => $shipping_method ) {

				if ( isset( $shipping_method->enabled ) && $shipping_method->enabled === 'yes' ) {
					$active_methods[ $shipping_method->title ] = $shipping_method->tax_status;
				}
			}

			$shipping_method     = $order->get_shipping_method();
			$shipping_tax_status = $active_methods[ $shipping_method ];

			return $shipping_tax_status;
		}

		// email tab
		function wc_ie_email_subject() {
			echo '<input type="text" placeholder="Order Invoice" name="wc_ie_email_subject" id="wc_ie_email_subject" value="' . get_option( 'wc_ie_email_subject' ) . '" />';
			echo ' <label for="wc_ie_email_subject">' . _e( ' The email subject.', 'wc_invoicexpress' ) . '</label></br>';
		}

		function wc_ie_email_body() {
			echo '<textarea style="resize:none" placeholder="Please find your invoice in attach. Archive this e-mail as proof of payment." rows="7" cols="60" name="wc_ie_email_body" id="wc_ie_email_textarea" >' . get_option( 'wc_ie_email_body' ).'</textarea>';
			echo ' <label for="wc_ie_email_body">' . _e( ' The email body.', 'wc_invoicexpress' ) . '</label>';
		}

		// support tab
		function wc_ie_support_form() {
			$user_name  = wp_get_current_user()->display_name;
			$user_email = wp_get_current_user()->user_email;
		?>
			<div class="wrap woocommerce">
				<?php
				if ( isset( $_GET['status'] ) ) {
					switch ( $_GET['status'] ) {
						case 'success':
							echo '<div id="message" class="updated"><p><strong>';
							echo __( 'Message successfully sent! We will get back to you soon ;)', 'wc_invoicexpress' );
							echo '</strong></p></div>';
							break;
						case 'failure':
							echo '<div id="message" class="error"><p><strong>';
							echo __( 'Oops! Something went wrong :(', 'wc_invoicexpress' );
							echo '</strong></p></div>';
							break;
						case 'error':
							echo '<p>' . __( 'Please fill in all the fields bellow', 'wc_invoicexpress' ) . '<p>';
							break;
					}
				}
				?>
				<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
				<h2><?php _e( 'InvoiceXpress for WooCommerce', 'wc_invoicexpress' ) ?></h2>
				<h3><?php _e( 'Contact Us', 'wc_invoicexpress' ) ?></h3>
				<p><?php _e( 'This will open a ticket with WidgiLabs Support.<br> Our team will review your request and will send you a response to the e-mail "<b>' . $user_email . '</b>". (usually within 24 hours).', 'wc_invoicexpress' ) ?></p>

				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="wc-ie-contact-form" id="wc-ie-contact-form" method="post">
					<ul>
						<li>
							<label for="contactSubject">Subject:</label><br>
							<input type="text" name="contactSubject" id="contactSubject" value="" placeholder="Subject" />
							<span class="error-msg" id="sub-error-msg" style="color:red"></span>
						</li>
						<li>
							<label for="commentsText">Message Body:</label><br>
							<textarea placeholder="Your comments" name="commentsText" id="commentsText" rows="7" cols="60" style="resize:none"></textarea>
							<span class="com-error-msg" id="com-error-msg" style="color:red"></span>
						</li>
						<li>
							<button type="submit" id="form-submit"><?php _e( 'Send Message', 'wc_invoicexpress' ) ?></button>
						</li>
					</ul>
					<input type="hidden" name="submitted" id="submitted" value="true" />
					<input type="hidden" name="user_name" id="form_user_name" value="<?php echo $user_name ?>" />
					<input type="hidden" name="user_email" id="form_user_email" value="<?php echo $user_email ?>" />
				</form>
			</div>
		<?php
		}

		function process( $order_id ) {

			if ( WooCommerceInvoiceXpress_License_Manager_Client::activated() !== 'Activated' )
			{
				return;
			}

			$order = null;
			if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
				$order = new WC_Legacy_Order( $order_id );
			} else {
				$order = new \WC_Order( $order_id );
			}

			if ( ! $order ) {
				return;
			}

			$currency_code   = \get_woocommerce_currency();
			$currency_rate   = get_field('exchange_rate', 'option');
			$is_vat_exempt   = false;
			$order_ae        = new \Aelia\WC\EU_VAT_Assistant\Order( $order_id );
			$eu_vat_evidence = $order_ae->get_vat_evidence();

			$vat_number_validated = !empty($eu_vat_evidence['exemption']['vat_number_validated']) ?
										($eu_vat_evidence['exemption']['vat_number_validated'] == 'valid' ) : false;

			if ( isset( $eu_vat_evidence['location']['billing_country'] ) &&  $eu_vat_evidence['location']['billing_country'] != 'PT' ) {
					$is_vat_exempt = true;
			}

			\InvoiceXpressRequest::init( $this->subdomain, $this->token );

			$total = $order->get_total();

			if ( ! $total || $total <= 0  ) {
				$order->add_order_note( __( 'Warning: Order total is zero, invoice not created!', 'wc_invoicexpress' ) );
				return;
			}

			$client_name = sprintf(
				'%1$s %2$s',
				$order->get_billing_first_name(),
				$order->get_billing_last_name()
			);

			$country = wc_ie_get_correct_country( $order->get_billing_country() );

			$vat      = apply_filters( 'wc_ie_change_billing_nif', get_post_meta( $order->get_id(), 'vat_number', true ) );
			$vat_text = '';
			if ( $vat ) {
				$vat_text = sprintf( ' - NIF: %s', $vat );
			}

			// if the user is not using EU VAT he may still have a fiscal number.
			$billing_nif = get_post_meta( $order->get_id(), '_billing_nif', true );
			if ( ! $vat ) {
				$vat = $billing_nif;
			}

			$invoice_name = $client_name;
			if ( $order->get_billing_company() ) {
				$invoice_name = $order->get_billing_company();
			}

			$send_options = 3;
			if ( get_option( 'wc_ie_send_options' ) ) {
				$send_options = get_option( 'wc_ie_send_options' );
			}

			$client_email = $order->get_billing_email();

			$client_data = array(
				'name'    => $invoice_name,
				'code'    => $client_email,
				'email'   => $client_email,
				'phone'   => $order->get_billing_phone(),
				'address' => sprintf(
					"%s\n%s\n",
					$order->get_billing_address_1(),
					$order->get_billing_address_2()
				),
				'postal_code' => sprintf(
					'%1$s - %2$s',
					$order->get_billing_postcode(),
					$order->get_billing_city()
				),
				'country'      => $country,
				'fiscal_id'    => $vat,
				'send_options' => $send_options,
			);

			// check if client exists
			$client = new \InvoiceXpressRequest( 'clients.find-by-code' );
			$client->request( $client_email );
			if ( $client->success() ) {

				// client exists let's get the data
				$response = $client->getResponse();
				$client_id = $response['id'];

				//update client
				$client = new InvoiceXpressRequest( 'clients.update' );
				$client_data_to_update = array(
					'client' => array(
						'name'    => $invoice_name,
						'code'    => $client_email,
						'email'   => $client_email,
						'phone'   => $order->get_billing_phone(),
						'address' => sprintf(
							"%s\n%s\n",
							$order->get_billing_address_1(),
							$order->get_billing_address_2()
						),
						'postal_code' => sprintf(
							'%1$s - %2$s',
							$order->get_billing_postcode(),
							$order->get_billing_city()
						),
						'country'      => $country,
						'fiscal_id'    => $vat,
						'send_options' => $send_options,
					),
				);

				$client->post( $client_data_to_update );
				$client->request( $client_id );
			}

			if ( intval( get_option( 'wc_ie_create_invoice' ) ) === 1 ) {

				$iva_name = 'IVA23';
				if ( $this->wc_ie_is_tax_exempt() ) {
					$iva_name = 'IVA0';
				}

				foreach ( $order->get_items() as $item ) {

					if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
						$item = new WC_Legacy_Order_Item( $item, $order );
					}

					$item_data = $item->get_data();

					if ( $is_vat_exempt ) {
						$iva_name = 'Isento';
					}

					$product_id = $item_data['product_id'];
					$quantity   = $item_data['quantity'];
					$total      = floatval( $item_data['total'] );
					$title      = html_entity_decode( get_the_title( $product_id ) );

					// sku
					$product = wc_get_product( $product_id );
					$sku     = $product->get_sku();

					if ( $sku === '' ) {
						// if empty sku use product_id as sku
						$sku = sprintf( '#%s', $product_id );
					} else {
						$sku = sprintf( 'SKU %s', $product->get_sku() );
					}

					/*
					  If we have an exchange rate and the currency is not EUR
					  apply that.
					*/
					if ( ! empty( $currency_rate ) && $currency_code != 'EUR' ) {
						$total = $total / $currency_rate;
						$total = number_format( $total, 4, '.', '' );
					}

					$items[] = array(
						'name'        => $sku,
						'description' => sprintf( '%1$sx %2$s', $quantity, $title ),
						'unit_price'  => $total,
						'quantity'    => 1,
						'unit'        => 'unit',
						'tax'         => array(
							'name' => $iva_name,
						),
					);
				}

				/*
				FEES
				 */
				foreach ( $order->get_fees() as $item ) {

					$fee_name = $item['name'];

					$final_price = floatval( $item['line_total'] );

					$items[] = array(
						'name'        => $fee_name,
						'description' => $fee_name,
						'unit_price'  => $final_price,
						'quantity'    => 1,
						'unit'        => 'unit',
						'tax'         => array(
							'name'	=> $iva_name,
						),
					);
				}

				/*
				 SHIPPING
				 */
				$shipping_unit_price = $order->get_total_shipping();
				$shipping_tax_name   = 'IVA23';
				$shipping_tax_status = $this->wc_ie_get_order_shipping_tax_status( $order );

				if ( 'none' === $shipping_tax_status ) {
					$shipping_tax_name = 'IVA0';
				}

				if ( $shipping_unit_price > 0 ) {
					$items[] = array(
						'name'        => 'Envio',
						'description' => 'Custos de Envio',
						'unit_price'  => $shipping_unit_price,
						'quantity'    => 1,
						'tax'         => array(
							'name' => $shipping_tax_name,
						),
					);
				}

				$date_completed = $order->get_date_completed();
				if ( empty( $date_completed ) ) {
					$date_completed = current_time( 'timestamp', true );
					$date_completed = date( 'd/m/Y', $date_completed );
				}

				/*
				Create Simplified Invoice
				 */
				$create_simplified_invoice = intval( get_option( 'wc_ie_create_simplified_invoice' ) ) === 1;
				if ( $create_simplified_invoice ) {

					$data = array(
						'simplified_invoice' => array(
							'date'      => $date_completed,
							'due_date'  => $date_completed,
							'client'    => $client_data,
							'reference' => $order_id,
							'items'     => array(
								'item' => $items,
							),
						),
					);

					/* OBSERVATIONS */
					$data['simplified_invoice']['observations'] = get_option( 'wc_ie_observations', '' );

					/*
					 TAX EXEMPTION
					 */
					if ( $tax_exemption = $this->wc_ie_is_tax_exempt() ) {
						$data['simplified_invoice']['tax_exemption'] = $tax_exemption;
					}

					if ( 'none' === $shipping_tax_status ) {
						$data['simplified_invoice']['tax_exemption'] = 'M99';
					}
				} else {

					/*
					Create invoice_receipt 
					 */
					$data = array(
						'invoice_receipt' => array(
							'date'      => $date_completed,
							'due_date'  => $date_completed,
							'client'    => $client_data,
							'reference' => $order_id,
							'items'     => array(
								'item' => $items,
							),
						),
					);

					/*
					If we have an exchange rate and the currency is not EUR
					apply that.
					*/
					if ( ! empty( $currency_rate ) && $currency_code != 'EUR' ) {
						$data['invoice_receipt']['currency_code'] = $currency_code;
						$data['invoice_receipt']['rate']          = $currency_rate;
					}


					/* SEQUENCE */
					// must ask InvoiceXpress for the current seq number 
					$seq = new InvoiceXpressRequest( 'sequences.get' );
					$seq->request();

					$preferred_sequence_id = get_option( 'wc_ie_sequence_id' );

					if ( $seq->success() ) {
						$response = $seq->getResponse();

						if ( isset( $response['sequence'] ) ) {

							$sequences = $response['sequence'];

							if ( count( $sequences ) > 0 ) {
								foreach ( $sequences as $key => $val ) {
			
									if ( is_int( $key ) ) {
										// assume multiple sequences available
										if ( $val['id'] == $preferred_sequence_id ) {
											$current_invoice_receipt_sequence_id    = $val['current_invoice_receipt_sequence_id'];
											$data['invoice_receipt']['sequence_id'] = $current_invoice_receipt_sequence_id;
										}

									} else {
										// assume only one sequence
										if ( $key === 'current_invoice_receipt_sequence_id' ) {
											$current_invoice_receipt_sequence_id    = $val;
											$data['invoice_receipt']['sequence_id'] = $current_invoice_receipt_sequence_id;
										}
									}
								}
							}

						}
					}


					/* OBSERVATIONS */
					$data['invoice_receipt']['observations'] = get_option( 'wc_ie_observations', '' );

					/*
					TAX EXEMPTION
					 */
					if ( $is_vat_exempt ) {
						$data['invoice_receipt']['tax_exemption'] = 'M99';
					}
				}

				//if($data['invoice_receipt']['tax_exemption']==''){
				//	$data['invoice_receipt']['tax_exemption'] = 'M99';
				//}

				if ( $create_simplified_invoice ) {
					$invoice = new InvoiceXpressRequest( 'simplified_invoices.create' );
				} else {
					$invoice = new InvoiceXpressRequest( 'invoice_receipts.create' );
				}

				$invoice->post( $data );
				$invoice->request();

				//var_dump($invoice);
				//die();
				if ( $invoice->success() ) {

					$response   = $invoice->getResponse();
					$invoice_id = $response['id'];

					$order->add_order_note( sprintf(
						'%1$s #%2$s',
						__( 'Client invoice in InvoiceXpress', 'wc_invoicexpress' ),
						$invoice_id
					) );
					add_post_meta( $order_id, 'wc_ie_inv_num', $invoice_id, true );

					// extra request to change status to final
					if ( get_option( 'wc_ie_invoice_draft' ) === '0' ) {

						if ( $create_simplified_invoice ) {
							$invoice = new InvoiceXpressRequest( 'simplified_invoices.change-state' );
						} else {
							$invoice = new InvoiceXpressRequest( 'invoice_receipts.change-state' );
						}

						$data = array( 'invoice_receipt' => array( 'state' => 'finalized' ) );
						$invoice->post( $data );
						$invoice->request( $invoice_id );

						if ( $invoice->success() ) { // keep the invoice sequence number in a meta
							$response       = $invoice->getResponse();
							$inv_seq_number = $response['sequence_number'];
							add_post_meta( $order_id, 'wc_ie_inv_seq_num', $inv_seq_number, true );
						}

						$data = array( 'invoice_receipt' => array( 'state' => 'settled' ) );
						$invoice->post( $data );
						$invoice->request( $invoice_id );
					}
				} else {
					$error = $invoice->getError();
					if ( is_array( $error ) ) {
						$order->add_order_note( __( 'InvoiceXpress Invoice API Error:', 'wc_invoicexpress' ) . ': ' . print_r( $error, true ) );
					} else {
						$order->add_order_note( __( 'InvoiceXpress Invoice API Error:', 'wc_invoicexpress' ) . ': ' . $error );
					}
				}
			}

			/*
			Send Invoice via e-mail to client
			 */
			if ( intval( get_option( 'wc_ie_send_invoice' ) ) === 1 && isset( $invoice_id ) ) {

				$subject = get_option( 'wc_ie_email_subject' ) ? get_option( 'wc_ie_email_subject' ) : __( 'Order Invoice', 'wc_invoicexpress' );
				$body    = get_option( 'wc_ie_email_body' ) ? get_option( 'wc_ie_email_body' ) : __( 'Please find your invoice in attach. Archive this e-mail as proof of payment.', 'wc_invoicexpress' );

				$data = array(
					'message' => array(
						'client' => array(
							'email' => $order->get_billing_email(),
							'save'  => 1,
						),
						'subject' => $subject,
						'body'    => $body,
					),
				);

				if ( $create_simplified_invoice ) {
					$send_invoice = new InvoiceXpressRequest( 'simplified_invoices.email-invoice' );
				} else {
					$send_invoice = new InvoiceXpressRequest( 'invoice_receipts.email-document' );
				}
				$send_invoice->post( $data );
				$send_invoice->request( $invoice_id );

				if ( $send_invoice->success() ) {
					$response = $send_invoice->getResponse();
					$order->add_order_note( __( 'Client invoice sent from InvoiceXpress', 'wc_invoicexpress' ) );
				} else {
					$order->add_order_note( __( 'InvoiceXpress Send Invoice API Error', 'wc_invoicexpress' ) . ': ' . $send_invoice->getError() );
				}
			}

		}

		function wc_ie_is_tax_exempt() {
			$tax_exemption = get_option( 'wc_ie_tax_exemption_reason_options' );
			if ( $tax_exemption && 'M00' !== $tax_exemption ) {
				return $tax_exemption;
			}
			return false;
		}

		/**
		 * Add field to checkout.
		 *
		 * @since 0.0.1
		 */
		function wc_ie_nif_checkout( $fields ) {

			$current_user = wp_get_current_user();

			$fields['billing']['billing_nif'] = array(
				'type'        => 'text',
				'label'       => __( 'VAT', 'wc_invoicexpress' ),
				'placeholder' => _x( 'VAT identification number', 'placeholder', 'wc_invoicexpress' ),
				'class'       => array( 'form-row-last' ),
				'required'    => false,
				'default'     => $current_user->billing_nif ? trim( $current_user->billing_nif ) : '',
			);

			return $fields;
		}

		/**
		 * Add NIF to My Account / Billing Address form.
		 *
		 * @since 0.0.1
		 */
		function wc_ie_nif_my_account( $fields ) {
			global $wp_query;

			if ( isset( $wp_query->query_vars['edit-address'] ) && $wp_query->query_vars['edit-address'] !== 'billing' ) {
				return $fields;
			} else {
				$current_user = wp_get_current_user();

				if ( $current_user->billing_country !== 'PT' ) {
					return $fields;
				}

				$fields['billing_nif'] = array(
					'type'        => 'text',
					'label'       => __( 'TAX Number', 'wc_invoicexpress' ),
					'placeholder' => _x( 'Portuguese VAT identification number', 'placeholder', 'wc_invoicexpress' ),
					'class'       => array( 'form-row-last' ),
					'required'    => false,
					'default'     => $current_user->billing_nif ? trim( $current_user->billing_nif ) : '',
				);

				return $fields;
			}
		}

		/**
		 * Save NIF to customer Billing Address.
		 *
		 * @since 0.0.1
		 */
		function wc_ie_my_account_save( $user_id, $load_address ) {
			if ( $load_address === 'billing' ) {
				if ( isset( $_POST['billing_nif'] ) ) {
					update_user_meta( $user_id, 'billing_nif', trim( $_POST['billing_nif'] ) );
				}
			}
		}

		/**
		 * Add field to order admin panel.
		 *
		 * @since 0.0.1
		 */
		function wc_ie_nif_admin( $order ) {

			$billing_nif = '';
			if (
				! empty( $order->order_custom_fields['_billing_country'] ) &&
				is_array( $order->order_custom_fields['_billing_country'] ) &&
				in_array( 'PT', $order->order_custom_fields['_billing_country'], true )
			) {
				$billing_nif = $order->order_custom_fields['vat_number'][0];
			} else {

				if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
					$order = new WC_Legacy_Order( $order );
				}

				$billing_country = get_post_meta( $order->get_id(), '_billing_country', true );
				if ( $billing_country === 'PT' ) {
					$billing_nif = get_post_meta( $order->get_id(), 'vat_number', true );
				}
			}

			echo '<p><strong>' . __( 'TAX Number', 'wc_invoicexpress' ) . ':</strong> ' . $billing_nif . '</p>';
		}

		function wc_ie_nif_validation() {
			// Check if set, if its not set add an error.
			if ( isset( $_POST['billing_nif'] ) && ! empty( $_POST['billing_nif'] ) && isset( $_POST['billing_country'] ) && $_POST['billing_country'] === 'PT' ) {
				if ( ! $this->wc_ie_validate_portuguese_vat( $_POST['billing_nif'] ) ) {
					wc_add_notice( __( 'Invalid NIF / NIPC', 'wc_invoicexpress' ), 'error' );
				}
			}
		}

		function wc_ie_validate_portuguese_vat( $vat ) {

			$valid_first_digits     = array( 1, 2, 3, 5, 6, 8 );
			$valid_first_two_digits = array( 45, 70, 71, 72, 77, 79, 90, 91, 98, 99 );

			// if first digit is valid
			$first_digit      = (int) substr( $vat, 0, 1 );
			$first_two_digits = (int) substr( $vat, 0, 2 );

			if ( ! in_array( $first_digit, $valid_first_digits, true ) &&
				 ! in_array( $first_two_digits, $valid_first_two_digits, true ) ) {
				return false;
			}

			$check1 = substr( $vat, 0, 1 ) * 9;
			$check2 = substr( $vat, 1, 1 ) * 8;
			$check3 = substr( $vat, 2, 1 ) * 7;
			$check4 = substr( $vat, 3, 1 ) * 6;
			$check5 = substr( $vat, 4, 1 ) * 5;
			$check6 = substr( $vat, 5, 1 ) * 4;
			$check7 = substr( $vat, 6, 1 ) * 3;
			$check8 = substr( $vat, 7, 1 ) * 2;

			$total = $check1 + $check2 + $check3 + $check4 + $check5 + $check6 + $check7 + $check8;

			$total_div11 = $total / 11;
			$modulus_11  = $total - intval( $total_div11 ) * 11;

			if ( $modulus_11 === 1 || $modulus_11 === 0 ) {
				$check = 0;
			} else {
				$check = 11 - $modulus_11;
			}

			$last_digit = substr( $vat, 8, 1 ) * 1;
			if ( $last_digit !== $check ) {
				return false;
			}

			return true;
		}
	}
}
