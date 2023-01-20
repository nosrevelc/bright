<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Functions used by plugins.
 *
 * @since  0.0.1
 * @author WidgiLabs <dev@widgilabs.com>
 */
if ( ! class_exists( 'WC_Dependencies' ) ) {
	require_once( 'class-wc-dependencies.php' );
}

/**
 * WC Detection.
 *
 * @since 0.0.1
 */
function wc_ie_is_woocommerce_active() {
	return WC_Dependencies::woocommerce_active_check();
}

/**
 * wc_ie_get_correct_country
 *
 * Because: https://invoicexpress.com/api/appendix/country-list.
 *
 * The ones commented are the ones that exist in WooCommerce but not in InvoiceXpress;
 * The ones that exist in WooCommerce with a different name in InvoiceXpress have the correction
 * on the next line.
 *
 * @since 0.0.1
 */
function wc_ie_get_correct_country( $country_code ) {

	$countries = array(
		'AF' => 'Afghanistan',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia-Herzegovina',
		'BW' => 'Botswana',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory',
		'VG' => 'Virgin Islands',
		'BN' => 'Brunei',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos (Keeling) Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CG' => 'Congo',
		'CD' => 'Congo, Democratic Republic',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CW' => 'Curaçao',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FK' => 'Falkland Islands',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard and McDonald Islands',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran',
		'IQ' => 'Iraq',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'CI' => 'Ivory Coast',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Laos',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macau',
		'MK' => 'Macedonia (Former Yug. Rep.)',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'AN' => 'Netherlands Antilles',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'KP' => 'Korea, North',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PS' => 'Palestine',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn Island',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'QA' => 'Qatar',
		'IE' => 'Ireland',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RU' => 'Russia',
		'RW' => 'Rwanda',
		'ST' => 'São Tomé and Príncipe',
		'SH' => 'St. Helena',
		'KN' => 'St. Kitts and Nevis',
		'LC' => 'St. Lucia',
		'VC' => 'St. Vincent and the Grenadines',
		'SM' => 'San Marino',
		'SA' => 'Saudi Arabia',
		'SN' => 'Sénégal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'KR' => 'Korea, South',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard and Jan Mayen Islands',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syria',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks and Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'UK',				// How stupid is this?
		'US' => 'United States',	// And this?
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VA' => 'Vatican',
		'VE' => 'Venezuela',
		'VN' => 'Vietnam',
		'WF' => 'Wallis and Futuna Islands',
		'EH' => 'Western Sahara',
		'WS' => 'Western Samoa',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe',
	);

	if ( isset( $countries[ $country_code ] ) ) {
		return $countries[ $country_code ];
	}

	return '';
}

/**
 * Enqueue scripts.
 *
 * @since 0.0.1
 */
add_action( 'admin_enqueue_scripts', 'wc_ie_plugin_scripts' );
function wc_ie_plugin_scripts() {
	wp_enqueue_script(
		'wc-ie-script',
		plugins_url() . '/woocommerce-invoicexpress-pro/woo-includes/lib/script.js',
		array(),
		'1.0.0',
		false
	);
}

/**
 * Get core info.
 *
 * @since 0.0.1
 */
function wc_ie_get_core_info() {
	global $wpdb;

	return array(
		'Wordpress' => array(
			'Multisite'          => is_multisite() ? 'Yes' : 'No',
			'SiteURL'            => site_url(),
			'HomeURL'            => home_url(),
			'Version'            => get_bloginfo( 'version' ),
			'PermalinkStructure' => get_option( 'permalink_structure' ),
			'PostTypes'          => implode( ', ', get_post_types( '', 'names' ) ),
			'PostSatus'          => implode( ', ', get_post_stati() ),
		),
		'Server' => array(
			'jQueryVersion'  => wp_script_is( 'jquery', 'registered' ) ? $GLOBALS['wp_scripts']->registered['jquery']->ver : __( 'n/a', 'bbpress' ),
			'PHPVersion'     => phpversion(),
			'MySQLVersion'   => $wpdb->db_version(),
			'ServerSoftware' => $_SERVER['SERVER_SOFTWARE'],
		),
		'PHP' => array(
			'MemoryLimit'  => ini_get( 'memory_limit' ),
			'UploadMax'    => ini_get( 'upload_max_filesize' ),
			'PostMax'      => ini_get( 'post_max_size' ),
			'TimeLimit'    => ini_get( 'max_execution_time' ),
			'MaxInputVars' => ini_get( 'max_input_vars' ),
		),
	);
}

/**
 * Get plugins info.
 *
 * @since 0.0.1
 */
function wc_ie_get_plugins_info() {

	if ( ! function_exists( 'get_plugins' ) ) {
		$admin_includes_path = str_replace( site_url( '/', 'admin' ), ABSPATH, admin_url( 'includes/', 'admin' ) );
		require_once $admin_includes_path . 'plugin.php';
	}

	$plugins             = get_plugins();
	$active_plugins      = get_option( 'active_plugins' );
	$active_plugins_info = array();

	foreach ( $active_plugins as $plugin ) {
		if ( isset( $plugins[ $plugin ] ) ) {
			unset( $plugins[ $plugin ]['Description'] );
			$active_plugins_info[ $plugin ] = $plugins[ $plugin ];
		}
	}

	return array(
		'active_plugins' => $active_plugins_info,
		'mu_plugins'     => get_mu_plugins(),
		'dropins'        => get_dropins(),
	);
}

/**
 * Get theme info.
 *
 * @since 0.0.1
 */
function wc_ie_get_theme_info() {

	if ( get_bloginfo( 'version' ) < '3.4' ) {
		$current_theme = get_theme_data( get_stylesheet_directory() . '/style.css' );
		$theme = $current_theme;
		unset( $theme['Description'] );
		unset( $theme['Satus'] );
		unset( $theme['Tags'] );
	} else {
		$current_theme = wp_get_theme();
		$theme = array(
			'Name'       => $current_theme->Name,
			'ThemeURI'   => $current_theme->ThemeURI,
			'Author'     => $current_theme->Author,
			'AuthorURI'  => $current_theme->AuthorURI,
			'Template'   => $current_theme->Template,
			'Version'    => $current_theme->Version,
			'TextDomain' => $current_theme->TextDomain,
			'DomainPath' => $current_theme->DomainPath,
		);
	}

	return $theme;
}
