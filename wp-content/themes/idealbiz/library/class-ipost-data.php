<?php

/**
 * Listing Data
 * 
 * 
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class IDB_Ipost_Data
{
    public function get_listing_value($listing_id, $meta_key, $broadcasted_site_id = false)
    {

        $meta_prefix = str_replace('_type', '', $meta_key);

        $fixed_values = array(
            'real'        => __('Property Included', 'idealbiz'),
            'noproperty'  => __('Without Property', 'idealbiz'),
            'lease'       => __('Lease', 'idealbiz'),
            'undisclosed' => __('Undisclosed', 'idealbiz'),
            'sale'        => __('For sale', 'idealbiz'),
            'offer'       => __('Under offer', 'idealbiz'),
            'sold'        => __('Sold (Subject to Contract)', 'idealbiz')
        );

        if ($broadcasted_site_id) {
            switch_to_blog($broadcasted_site_id);
            $currency_symbol = \get_woocommerce_currency_symbol();
            restore_current_blog();
        }
        else {
            $currency_symbol = \get_woocommerce_currency_symbol();
        }

        $ranges = array(
            '99999'           => __('Unders', 'idealbiz'). ' ' . $currency_symbol . '100k',
            '100000_249999'   => $currency_symbol . __('100k - 250k', 'idealbiz'),
            '250000_499999'   => $currency_symbol . __('250k - 500k', 'idealbiz'),
            '500000_999999'   => $currency_symbol . __('500k - 1m', 'idealbiz'),
            '1000000_4999999' => $currency_symbol . __('1m - 5m', 'idealbiz'),
            '5000000'         => __('Over ', 'idealbiz') . $currency_symbol . '5m'
        );

        $type = get_post_meta($listing_id, $meta_key, true);

        $value = '';

        if ('range' === $type) {
            $range = get_post_meta($listing_id, "{$meta_prefix}_range", true);
            $value = $ranges[$range];
        } elseif ('manual' === $type) {
            $value = get_post_meta($listing_id, "{$meta_prefix}_manual", true);
            $value = IDB_Pricing::pricing_format($value, $broadcasted_site_id);
        } elseif (isset($fixed_values[$type])) {
            $value = $fixed_values[$type];
        } elseif ('business_status' === $meta_key) {
            $value = $type;
        } elseif ('is_franchise' === $meta_key) {
            $value = ('1' === $type) ? __('Yes', 'idealbiz') : __('No', 'idealbiz');
        } elseif ('lease_value' === $meta_key) {
            $value = IDB_Pricing::pricing_format($type);
        } elseif ('lease_frequency' === $meta_key) {
            __('year', 'idealbiz');
            __('month', 'idealbiz'); // frequency options so that they can be translated
            $value = __($type, 'idealbiz');
        }

        return $value;
    }

    public function get_listing_data()
    {
        return array(
            array(
                'name' => __('Property information', 'idealbiz'),
                'items' => $this->property_information()
            ),
            array(
                'name' => __('Business operations', 'idealbiz'),
                'items' => $this->business_operations()
            ),
            array(
                'name' => __('Terms', 'idealbiz'),
                'items' => $this->business_terms()
            )
        );
    }

    public function get_listing_financial()
    {
        return array(
            array(
                'title' => __('Type', 'idealbiz'),
                'meta_key' => 'property_type',
            ),
            array(
                'title' => __('Status', 'idealbiz'),
                'meta_key' => 'business_status',
            ),
            array(
                'title' => __('Franchise', 'idealbiz'),
                'meta_key' => 'is_franchise',
            ),
            array(
                'title' => __('Price', 'idealbiz'),
                'meta_key' => 'price_type',
            ),
            array(
                'title' => __('Revenue', 'idealbiz'),
                'meta_key' => 'revenue_type',
            ),
            array(
                'title' => __('Cash Flow', 'idealbiz'),
                'meta_key' => 'cash_flow_type',
            )
        );
    }

    private function property_information() {
		$listing_id = get_the_ID();
		$property_information  = array();
		$validate   = array();
		$meta_keys  = array(
			'real_estate'                          => __( 'Real Estate Value:', 'idealbiz' ),
			'include_real_estate'                  => __( 'Included in asking price:', 'idealbiz' ),
			'size'                                 => __( 'Property size', 'idealbiz' ) . ' (<span class="measure-units">m<sup>2</sup></span>) :',
			'furniture_fixtures_equipment'         => __( 'Furniture, Fixtures & Equipment Value:', 'idealbiz' ),
			'include_furniture_fixtures_equipment' => __( 'Included in asking price', 'idealbiz' ),
			'inventory_stock'                      => __( 'Inventory Value:', 'idealbiz' ),
			'include_inventory_stock'              => __( 'Included in asking price:', 'idealbiz' ),
		);

		foreach ( $meta_keys as $meta_key => $label ) {

			$value = get_post_meta( $listing_id, $meta_key, true );

			if ( ! empty( $value ) &&
			     ( 'real_estate'                  === $meta_key ||
			       'furniture_fixtures_equipment' === $meta_key ||
			       'inventory_stock'              === $meta_key )
			) {
				$value = IDB_Pricing::pricing_format( $value );
			}

			if ( ! strpos( $meta_key, 'include' ) && ! empty( $value ) ) {
				$validate[ $meta_key ] = $value;
			}

			if( ! empty( $value ) && 'size' === $meta_key ) {
				$value = number_format( $value, 2, ',', '.' );
			}

			if ( preg_match( '/^include_/', $meta_key ) ) {
				$ref = str_replace( 'include_', '', $meta_key );

				$value = $value ? __( 'Yes', 'idealbiz' ) : __( 'No', 'idealbiz' );

				if ( ! isset( $validate[ $ref ] ) ) {
					$value = '';
				}
			}

			if ( empty( $value ) ) {
				continue;
            }
            
            $property_information[] = array(
                'title' => $label,
                'value' => $value
            );

		}

        return $property_information;
		
	}

    private static function business_operations()
    {
        $listing_id = get_the_ID();
        $business_operations = array();
        $meta_keys  = array(
            'years_established'     => __('Years established', 'idealbiz'),
            'employees'             => __('Employees', 'idealbiz'),
            'trading_hours'         => __('Trading hours', 'idealbiz'),
            'support_training'      => __('Support & training', 'idealbiz'),
            'website'               => __('Website', 'idealbiz'),
            'business_location'     => __('Business Location', 'idealbiz'),
            'premise_details'       => __('Premise Details', 'idealbiz'),
            'competition'           => __('Competition', 'idealbiz'),
            'expansion_potential'   => __('Expansion Potential', 'idealbiz'),
            'living_accomodations'  => __('Living Accomodations', 'idealbiz'),
            'accommodation_summary' => __('Accomodation Summary', 'idealbiz'),
            'planning_consent'      => __('Planning Consent', 'idealbiz'),
        );

        foreach ($meta_keys as $meta_key => $label) {

            $value = get_post_meta($listing_id, $meta_key, true);

            if (empty($value)) {
                continue;
            }

            if ('living_accomodations' === $meta_key) {
                $value = ('1' === $value) ? __('Yes', 'idealbiz') : __('No', 'idealbiz');
            }

            switch ($meta_key) {
                case 'website':
                    $is_link = true;
                    break;
                
                default:
                    $is_link = false;
                    break;
            }

            $business_operations[] = array(
                'title' => $label,
                'value' => $value,
                'is_link' => $is_link
            );
        }

        return $business_operations;
    }

    private function business_terms() {
		$listing_id = get_the_ID();
		$business_terms = array();
		$meta_keys  = array(
			'relocatable'     => __( 'Relocatable:', 'idealbiz' ),
			'owner_financing' => __( 'Owner Financing Available:', 'idealbiz' ),
			'financing'       => __( 'Financing Available:', 'idealbiz' ),
			'reasons'         => __( 'Reasons for Selling:', 'idealbiz' ),
		);

		foreach ( $meta_keys as $meta_key => $label ) {

			$value = get_post_meta( $listing_id, $meta_key, true );

			if ( 'relocatable' === $meta_key || 'owner_financing' === $meta_key ) {
				$value = ( '1' === $value ) ? __( 'Yes', 'idealbiz' ) : __( 'No', 'idealbiz' );
			}

			if ( empty( $value ) ) {
				continue;
			}

			$business_terms[] = array(
                'title' => $label,
                'value' => $value,
                
            );
        }
        
        return $business_terms;

	}
}
