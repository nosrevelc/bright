(function( $ ) {

	if ( ifthenpay.gateway != '' ) {

		var hide_extra_fields = true;

		switch( ifthenpay.gateway ) {
			case 'multibanco':
				ifthen_toogle_mb_api_mode();
				$( '#woocommerce_multibanco_ifthen_for_woocommerce_api_mode' ).change( function() {
					ifthen_toogle_mb_api_mode();
				});
				if (
					(
						$( '#woocommerce_multibanco_ifthen_for_woocommerce_api_mode' ).val() == ''
						&&
						$( '#woocommerce_multibanco_ifthen_for_woocommerce_ent' ).val().trim().length == 5
						&&
						$( '#woocommerce_multibanco_ifthen_for_woocommerce_subent' ).val().trim().length <= 3
						&&
						parseInt( $( '#woocommerce_multibanco_ifthen_for_woocommerce_ent' ).val() ) > 0
						&&
						parseInt( $( '#woocommerce_multibanco_ifthen_for_woocommerce_subent' ).val() ) > 0
						&&
						$( '#woocommerce_multibanco_ifthen_for_woocommerce_secret_key' ).val().trim() != ''
					)
					||
					(
						$( '#woocommerce_multibanco_ifthen_for_woocommerce_api_mode' ).val() == 'yes'
						&&
						$( '#woocommerce_multibanco_ifthen_for_woocommerce_mbkey' ).val().trim().length == 10
						&&
						$( '#woocommerce_multibanco_ifthen_for_woocommerce_secret_key' ).val().trim() != ''
					)
				) {
					hide_extra_fields = false;
				}
				break;
			case 'mbway':
				if (
					$( '#woocommerce_mbway_ifthen_for_woocommerce_mbwaykey' ).val().trim().length == 10
					&&
					$( '#woocommerce_mbway_ifthen_for_woocommerce_secret_key' ).val().trim() != ''
				) {
					hide_extra_fields = false;
				}
				break;
			case 'creditcard':
				if (
					$( '#woocommerce_creditcard_ifthen_for_woocommerce_creditcardkey' ).val().trim().length == 10
					&&
					$( '#woocommerce_creditcard_ifthen_for_woocommerce_creditcardkey' ).val().trim() != ''
				) {
					hide_extra_fields = false;
				}
				break;
			case 'payshop':
				if (
					$( '#woocommerce_payshop_ifthen_for_woocommerce_payshopkey' ).val().trim().length == 10
					&&
					$( '#woocommerce_payshop_ifthen_for_woocommerce_secret_key' ).val().trim() != ''
				) {
					hide_extra_fields = false;
				}
				break;
			default:
				// code block
				break;
		}

		//Hide extra fields if there are errors on required fields
		if ( hide_extra_fields ) {
			switch( ifthenpay.gateway ) {
				case 'multibanco':
					var number_fields = 6;
					if ( $( '#wc_ifthen_mb_mode' ).length ) {
						number_fields++;
					}
					$( '#wc_ifthen_settings table.form-table tr:nth-child(n+'+number_fields+')' ).hide();
					$( '#wc_ifthen_settings .mb_hide_extra_fields' ).hide();
					break;
				case 'mbway':
				case 'creditcard':
				case 'payshop':
					$( '#wc_ifthen_settings table.form-table tr:nth-child(n+3)' ).hide();
					$( '#wc_ifthen_settings .mb_hide_extra_fields' ).hide();
					break;
				default:
					// code block
					break;
			}
		}

		//Settings saved (??)
		$( '#woocommerce_'+ifthenpay.gateway+'_ifthen_for_woocommerce_settings_saved' ).val( '1' );

		//Callback activation
		$( '#wc_ifthen_callback_open' ).click( function() {
			ifthen_callback_open();
			return false;
		});
		$( '#wc_ifthen_callback_cancel' ).click( function() {
			$( '#wc_ifthen_callback_div' ).toggle();
			$( '#wc_ifthen_callback_open_p' ).toggle();
			return false;
		});
		//Callback send
		$( '#wc_ifthen_callback_submit' ).click( function() {
			if ( confirm( ifthenpay.callback_confirm ) ) {
				$( '#wc_ifthen_callback_send' ).val( 1 );
				$( '#mainform' ).submit()
				return true;
			} else {
				return false;
			}
		});
		//Callback webservice
		$( '#wc_ifthen_callback_submit_webservice' ).click( function() {
			var bo_key = prompt( ifthenpay.callback_bo_key, '' );
			if ( bo_key ) {
				$( '#wc_ifthen_callback_bo_key' ).val( $.trim( bo_key ) );
				$( '#wc_ifthen_callback_send' ).val( 2 );
				$( '#mainform' ).submit()
				return true;
			} else {
				return false;
			}
		});
		setTimeout( function() {
			if ( ifthenpay.callback_email_sent == 'no' ) {
				$( '#wc_ifthen_callback_open' ).addClass('button-link-delete');
				ifthen_callback_open();
				if ( ifthenpay.callback_auto_open == '1' ) { 
					setTimeout( function() {
						$( '#wc_ifthen_callback_div' ).addClass('focus' );
					}, 250 );
					setTimeout( function() {
						$( '#wc_ifthen_callback_div' ).removeClass('focus' );
					}, 1500 );
				}
			}
		}, 500 );

	}

	function ifthen_callback_open() {
		$( '#wc_ifthen_callback_div' ).toggle();
		$( '#wc_ifthen_callback_open_p' ).toggle();
	}

	function ifthen_toogle_mb_api_mode() {
		if ( $( '#woocommerce_multibanco_ifthen_for_woocommerce_api_mode' ).val() == 'yes' ) {
			$( '#woocommerce_multibanco_ifthen_for_woocommerce_ent' ).closest( 'tr' ).hide();
			$( '#woocommerce_multibanco_ifthen_for_woocommerce_subent' ).closest( 'tr' ).hide();
			$( '#woocommerce_multibanco_ifthen_for_woocommerce_mbkey' ).closest( 'tr' ).show();
			$( '#woocommerce_multibanco_ifthen_for_woocommerce_api_expiry' ).closest( 'tr' ).show();
		} else {
			$( '#woocommerce_multibanco_ifthen_for_woocommerce_ent' ).closest( 'tr' ).show();
			$( '#woocommerce_multibanco_ifthen_for_woocommerce_subent' ).closest( 'tr' ).show();
			$( '#woocommerce_multibanco_ifthen_for_woocommerce_mbkey' ).closest( 'tr' ).hide();
			$( '#woocommerce_multibanco_ifthen_for_woocommerce_api_expiry' ).closest( 'tr' ).hide();
		}
	}

})( jQuery );
