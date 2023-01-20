<?php

/**
 * Get Countries and continent
 * 
 * @since  1.0.0
 * @return array social networks
 */
function getContinents(){
  $continent_list = array (
    'EU' => __('Europe','continents'),
    'NA' => __('North America','continents'),
    'SA' => __('South America','continents'),
    'AF' => __('Africa','continents'),
    'AS' => __('Asia','continents'),
    'OC' => __('Oceania','continents'),
    'AN' => __('Antarctica','continents'),
    'IN' => __('Global','continents')
  );
  return $continent_list;
}

/**
 * Get Countries and continent
 *
 * @since  1.0.0
 * @return array countries
 */
function getCountries(){
    $country_list = array (
    'AF' => array ( 'country' => __('Afghanistan','countries'), 'continent' => 'AS' ),
    'AL' => array ( 'country' => __('Albania','countries'), 'continent' => 'EU' ),
    'DZ' => array ( 'country' => __('Algeria','countries'), 'continent' => 'AF' ),
    'AS' => array ( 'country' => __('American Samoa','countries'), 'continent' => 'OC' ),
    'AD' => array ( 'country' => __('Andorra','countries'), 'continent' => 'EU' ),
    'AO' => array ( 'country' => __('Angola','countries'), 'continent' => 'AF' ),
    'AI' => array ( 'country' => __('Anguilla','countries'), 'continent' => 'NA' ),
    'AQ' => array ( 'country' => __('Antarctica','countries'), 'continent' => 'AN' ),
    'AG' => array ( 'country' => __('Antigua and Barbuda','countries'), 'continent' => 'NA' ),
    'AR' => array ( 'country' => __('Argentina','countries'), 'continent' => 'SA' ),
    'AM' => array ( 'country' => __('Armenia','countries'), 'continent' => 'AS' ),
    'AW' => array ( 'country' => __('Aruba','countries'), 'continent' => 'NA' ),
    'AU' => array ( 'country' => __('Australia','countries'), 'continent' => 'OC' ),
    'AT' => array ( 'country' => __('Austria','countries'), 'continent' => 'EU' ),
    'AZ' => array ( 'country' => __('Azerbaijan','countries'), 'continent' => 'AS' ),
    'BS' => array ( 'country' => __('Bahamas','countries'), 'continent' => 'NA' ),
    'BH' => array ( 'country' => __('Bahrain','countries'), 'continent' => 'AS' ),
    'BD' => array ( 'country' => __('Bangladesh','countries'), 'continent' => 'AS' ),
    'BB' => array ( 'country' => __('Barbados','countries'), 'continent' => 'NA' ),
    'BY' => array ( 'country' => __('Belarus','countries'), 'continent' => 'EU' ),
    'BE' => array ( 'country' => __('Belgium','countries'), 'continent' => 'EU' ),
    'BZ' => array ( 'country' => __('Belize','countries'), 'continent' => 'NA' ),
    'BJ' => array ( 'country' => __('Benin','countries'), 'continent' => 'AF' ),
    'BM' => array ( 'country' => __('Bermuda','countries'), 'continent' => 'NA' ),
    'BT' => array ( 'country' => __('Bhutan','countries'), 'continent' => 'AS' ),
    'BO' => array ( 'country' => __('Bolivia','countries'), 'continent' => 'SA' ),
    'BA' => array ( 'country' => __('Bosnia and Herzegovina','countries'), 'continent' => 'EU' ),
    'BW' => array ( 'country' => __('Botswana','countries'), 'continent' => 'AF' ),
    'BV' => array ( 'country' => __('Bouvet Island','countries'), 'continent' => 'AN' ),
    'BR' => array ( 'country' => __('Brazil','countries'), 'continent' => 'SA' ),
    'IO' => array ( 'country' => __('British Indian Ocean Territory','countries'), 'continent' => 'AF' ),
    'BN' => array ( 'country' => __('Brunei Darussalam','countries'), 'continent' => '' ),
    'BG' => array ( 'country' => __('Bulgaria','countries'), 'continent' => 'EU' ),
    'BF' => array ( 'country' => __('Burkina Faso','countries'), 'continent' => 'AF' ),
    'BI' => array ( 'country' => __('Burundi','countries'), 'continent' => 'AF' ),
    'KH' => array ( 'country' => __('Cambodia','countries'), 'continent' => 'AS' ),
    'CM' => array ( 'country' => __('Cameroon','countries'), 'continent' => 'AF' ),
    'CA' => array ( 'country' => __('Canada','countries'), 'continent' => 'NA' ),
    'CV' => array ( 'country' => __('Cape Verde','countries'), 'continent' => 'AF' ),
    'KY' => array ( 'country' => __('Cayman Islands','countries'), 'continent' => 'NA' ),
    'CF' => array ( 'country' => __('Central African Republic','countries'), 'continent' => 'AF' ),
    'TD' => array ( 'country' => __('Chad','countries'), 'continent' => 'AF' ),
    'CL' => array ( 'country' => __('Chile','countries'), 'continent' => 'SA' ),
    'CN' => array ( 'country' => __('China','countries'), 'continent' => 'AS' ),
    'CX' => array ( 'country' => __('Christmas Island','countries'), 'continent' => 'OC' ),
    'CC' => array ( 'country' => __('Cocos (Keeling) Islands','countries'), 'continent' => 'OC' ),
    'CO' => array ( 'country' => __('Colombia','countries'), 'continent' => 'SA' ),
    'KM' => array ( 'country' => __('Comoros','countries'), 'continent' => 'AF' ),
    'CG' => array ( 'country' => __('Congo','countries'), 'continent' => 'AF' ),
    'CD' => array ( 'country' => __('Congo, the Democratic Republic of the','countries'), 'continent' => '' ),
    'CK' => array ( 'country' => __('Cook Islands','countries'), 'continent' => 'OC' ),
    'CR' => array ( 'country' => __('Costa Rica','countries'), 'continent' => 'NA' ),
    'CI' => array ( 'country' => __('Cote D\'Ivoire','countries'), 'continent' => '' ),
    'HR' => array ( 'country' => __('Croatia','countries'), 'continent' => 'EU' ),
    'CU' => array ( 'country' => __('Cuba','countries'), 'continent' => 'NA' ),
    'CY' => array ( 'country' => __('Cyprus','countries'), 'continent' => 'AS' ),
    'CZ' => array ( 'country' => __('Czech Republic','countries'), 'continent' => 'EU' ),
    'DK' => array ( 'country' => __('Denmark','countries'), 'continent' => 'EU' ),
    'DJ' => array ( 'country' => __('Djibouti','countries'), 'continent' => 'AF' ),
    'DM' => array ( 'country' => __('Dominica','countries'), 'continent' => 'NA' ),
    'DO' => array ( 'country' => __('Dominican Republic','countries'), 'continent' => 'NA' ),
    'EC' => array ( 'country' => __('Ecuador','countries'), 'continent' => 'SA' ),
    'EG' => array ( 'country' => __('Egypt','countries'), 'continent' => 'AF' ),
    'SV' => array ( 'country' => __('El Salvador','countries'), 'continent' => 'NA' ),
    'GQ' => array ( 'country' => __('Equatorial Guinea','countries'), 'continent' => 'AF' ),
    'ER' => array ( 'country' => __('Eritrea','countries'), 'continent' => 'AF' ),
    'EE' => array ( 'country' => __('Estonia','countries'), 'continent' => 'EU' ),
    'ET' => array ( 'country' => __('Ethiopia','countries'), 'continent' => 'AF' ),
    'FK' => array ( 'country' => __('Falkland Islands (Malvinas)','countries'), 'continent' => '' ),
    'FO' => array ( 'country' => __('Faroe Islands','countries'), 'continent' => 'EU' ),
    'FJ' => array ( 'country' => __('Fiji','countries'), 'continent' => '' ),
    'FI' => array ( 'country' => __('Finland','countries'), 'continent' => 'EU' ),
    'FR' => array ( 'country' => __('France','countries'), 'continent' => 'EU' ),
    'GF' => array ( 'country' => __('French Guiana','countries'), 'continent' => 'SA' ),
    'PF' => array ( 'country' => __('French Polynesia','countries'), 'continent' => 'OC' ),
    'TF' => array ( 'country' => __('French Southern Territories','countries'), 'continent' => '' ),
    'GA' => array ( 'country' => __('Gabon','countries'), 'continent' => 'AF' ),
    'GM' => array ( 'country' => __('Gambia','countries'), 'continent' => 'AF' ),
    'GE' => array ( 'country' => __('Georgia','countries'), 'continent' => 'AS' ),
    'DE' => array ( 'country' => __('Germany','countries'), 'continent' => 'EU' ),
    'GH' => array ( 'country' => __('Ghana','countries'), 'continent' => 'AF' ),
    'GI' => array ( 'country' => __('Gibraltar','countries'), 'continent' => 'EU' ),
    'GR' => array ( 'country' => __('Greece','countries'), 'continent' => 'EU' ),
    'GL' => array ( 'country' => __('Greenland','countries'), 'continent' => 'NA' ),
    'GD' => array ( 'country' => __('Grenada','countries'), 'continent' => 'NA' ),
    'GP' => array ( 'country' => __('Guadeloupe','countries'), 'continent' => 'NA' ),
    'GU' => array ( 'country' => __('Guam','countries'), 'continent' => 'OC' ),
    'GT' => array ( 'country' => __('Guatemala','countries'), 'continent' => 'NA' ),
    'GN' => array ( 'country' => __('Guinea','countries'), 'continent' => 'AF' ),
    'GW' => array ( 'country' => __('Guinea-Bissau','countries'), 'continent' => 'AF' ),
    'GY' => array ( 'country' => __('Guyana','countries'), 'continent' => 'SA' ),
    'HT' => array ( 'country' => __('Haiti','countries'), 'continent' => 'NA' ),
    'HM' => array ( 'country' => __('Heard Island and Mcdonald Islands','countries'), 'continent' => '' ),
    'VA' => array ( 'country' => __('Holy See (Vatican City State)','countries'), 'continent' => 'EU' ),
    'HN' => array ( 'country' => __('Honduras','countries'), 'continent' => 'NA' ),
    'HK' => array ( 'country' => __('Hong Kong','countries'), 'continent' => 'AS' ),
    'HU' => array ( 'country' => __('Hungary','countries'), 'continent' => 'EU' ),
    'IS' => array ( 'country' => __('Iceland','countries'), 'continent' => 'EU' ),
    'IN' => array ( 'country' => __('India','countries'), 'continent' => 'AS' ),
    'ID' => array ( 'country' => __('Indonesia','countries'), 'continent' => 'AS' ),
    'IR' => array ( 'country' => __('Iran, Islamic Republic of','countries'), 'continent' => '' ),
    'IQ' => array ( 'country' => __('Iraq','countries'), 'continent' => 'AS' ),
    'IE' => array ( 'country' => __('Ireland','countries'), 'continent' => 'EU' ),
    'IL' => array ( 'country' => __('Israel','countries'), 'continent' => 'AS' ),
    'IT' => array ( 'country' => __('Italy','countries'), 'continent' => 'EU' ),
    'JM' => array ( 'country' => __('Jamaica','countries'), 'continent' => 'NA' ),
    'JP' => array ( 'country' => __('Japan','countries'), 'continent' => 'AS' ),
    'JO' => array ( 'country' => __('Jordan','countries'), 'continent' => 'AS' ),
    'KZ' => array ( 'country' => __('Kazakhstan','countries'), 'continent' => 'AS' ),
    'KE' => array ( 'country' => __('Kenya','countries'), 'continent' => 'AF' ),
    'KI' => array ( 'country' => __('Kiribati','countries'), 'continent' => 'OC' ),
    'KP' => array ( 'country' => __('Korea, Democratic People\'s Republic of','countries'), 'continent' => '' ),
    'KR' => array ( 'country' => __('Korea, Republic of','countries'), 'continent' => '' ),
    'KW' => array ( 'country' => __('Kuwait','countries'), 'continent' => 'AS' ),
    'KG' => array ( 'country' => __('Kyrgyzstan','countries'), 'continent' => 'AS' ),
    'LA' => array ( 'country' => __('Lao People\'s Democratic Republic','countries'), 'continent' => '' ),
    'LV' => array ( 'country' => __('Latvia','countries'), 'continent' => 'EU' ),
    'LB' => array ( 'country' => __('Lebanon','countries'), 'continent' => 'AS' ),
    'LS' => array ( 'country' => __('Lesotho','countries'), 'continent' => 'AF' ),
    'LR' => array ( 'country' => __('Liberia','countries'), 'continent' => 'AF' ),
    'LY' => array ( 'country' => __('Libyan Arab Jamahiriya','countries'), 'continent' => 'AF' ),
    'LI' => array ( 'country' => __('Liechtenstein','countries'), 'continent' => 'EU' ),
    'LT' => array ( 'country' => __('Lithuania','countries'), 'continent' => 'EU' ),
    'LU' => array ( 'country' => __('Luxembourg','countries'), 'continent' => 'EU' ),
    'MO' => array ( 'country' => __('Macao','countries'), 'continent' => 'AS' ),
    'MK' => array ( 'country' => __('Macedonia, the Former Yugoslav Republic of','countries'), 'continent' => '' ),
    'MG' => array ( 'country' => __('Madagascar','countries'), 'continent' => 'AF' ),
    'MW' => array ( 'country' => __('Malawi','countries'), 'continent' => 'AF' ),
    'MY' => array ( 'country' => __('Malaysia','countries'), 'continent' => 'AS' ),
    'MV' => array ( 'country' => __('Maldives','countries'), 'continent' => 'AS' ),
    'ML' => array ( 'country' => __('Mali','countries'), 'continent' => 'AF' ),
    'MT' => array ( 'country' => __('Malta','countries'), 'continent' => 'EU' ),
    'MH' => array ( 'country' => __('Marshall Islands','countries'), 'continent' => 'OC' ),
    'MQ' => array ( 'country' => __('Martinique','countries'), 'continent' => 'NA' ),
    'MR' => array ( 'country' => __('Mauritania','countries'), 'continent' => 'AF' ),
    'MU' => array ( 'country' => __('Mauritius','countries'), 'continent' => 'AF' ),
    'YT' => array ( 'country' => __('Mayotte','countries'), 'continent' => 'AF' ),
    'MX' => array ( 'country' => __('Mexico','countries'), 'continent' => 'NA' ),
    'FM' => array ( 'country' => __('Micronesia, Federated States of','countries'), 'continent' => 'OC' ),
    'MD' => array ( 'country' => __('Moldova, Republic of','countries'), 'continent' => '' ),
    'MC' => array ( 'country' => __('Monaco','countries'), 'continent' => 'EU' ),
    'MN' => array ( 'country' => __('Mongolia','countries'), 'continent' => 'AS' ),
    'MS' => array ( 'country' => __('Montserrat','countries'), 'continent' => 'NA' ),
    'MA' => array ( 'country' => __('Morocco','countries'), 'continent' => 'AF' ),
    'MZ' => array ( 'country' => __('Mozambique','countries'), 'continent' => 'AF' ),
    'MM' => array ( 'country' => __('Myanmar','countries'), 'continent' => 'AS' ),
    'NA' => array ( 'country' => __('Namibia','countries'), 'continent' => 'AF' ),
    'NR' => array ( 'country' => __('Nauru','countries'), 'continent' => 'OC' ),
    'NP' => array ( 'country' => __('Nepal','countries'), 'continent' => 'AS' ),
    'NL' => array ( 'country' => __('Netherlands','countries'), 'continent' => 'EU' ),
    'AN' => array ( 'country' => __('Netherlands Antilles','countries'), 'continent' => 'NA' ),
    'NC' => array ( 'country' => __('New Caledonia','countries'), 'continent' => 'OC' ),
    'NZ' => array ( 'country' => __('New Zealand','countries'), 'continent' => 'OC' ),
    'NI' => array ( 'country' => __('Nicaragua','countries'), 'continent' => 'NA' ),
    'NE' => array ( 'country' => __('Niger','countries'), 'continent' => 'AF' ),
    'NG' => array ( 'country' => __('Nigeria','countries'), 'continent' => 'AF' ),
    'NU' => array ( 'country' => __('Niue','countries'), 'continent' => 'OC' ),
    'NF' => array ( 'country' => __('Norfolk Island','countries'), 'continent' => 'OC' ),
    'MP' => array ( 'country' => __('Northern Mariana Islands','countries'), 'continent' => 'OC' ),
    'NO' => array ( 'country' => __('Norway','countries'), 'continent' => 'EU' ),
    'OM' => array ( 'country' => __('Oman','countries'), 'continent' => 'AS' ),
    'PK' => array ( 'country' => __('Pakistan','countries'), 'continent' => 'AS' ),
    'PW' => array ( 'country' => __('Palau','countries'), 'continent' => 'OC' ),
    'PS' => array ( 'country' => __('Palestinian Territory, Occupied','countries'), 'continent' => '' ),
    'PA' => array ( 'country' => __('Panama','countries'), 'continent' => 'NA' ),
    'PG' => array ( 'country' => __('Papua New Guinea','countries'), 'continent' => 'OC' ),
    'PY' => array ( 'country' => __('Paraguay','countries'), 'continent' => 'SA' ),
    'PE' => array ( 'country' => __('Peru','countries'), 'continent' => 'SA' ),
    'PH' => array ( 'country' => __('Philippines','countries'), 'continent' => 'AS' ),
    'PN' => array ( 'country' => __('Pitcairn','countries'), 'continent' => 'OC' ),
    'PL' => array ( 'country' => __('Poland','countries'), 'continent' => 'EU' ),
    'PT' => array ( 'country' => __('Portugal','countries'), 'continent' => 'EU' ),
    'PR' => array ( 'country' => __('Puerto Rico','countries'), 'continent' => 'NA' ),
    'QA' => array ( 'country' => __('Qatar','countries'), 'continent' => 'AS' ),
    'RE' => array ( 'country' => __('Reunion','countries'), 'continent' => 'AF' ),
    'RO' => array ( 'country' => __('Romania','countries'), 'continent' => 'EU' ),
    'RU' => array ( 'country' => __('Russian Federation','countries'), 'continent' => 'EU' ),
    'RW' => array ( 'country' => __('Rwanda','countries'), 'continent' => 'AF' ),
    'SH' => array ( 'country' => __('Saint Helena','countries'), 'continent' => 'AF' ),
    'KN' => array ( 'country' => __('Saint Kitts and Nevis','countries'), 'continent' => 'NA' ),
    'LC' => array ( 'country' => __('Saint Lucia','countries'), 'continent' => 'NA' ),
    'PM' => array ( 'country' => __('Saint Pierre and Miquelon','countries'), 'continent' => 'NA' ),
    'VC' => array ( 'country' => __('Saint Vincent and the Grenadines','countries'), 'continent' => 'NA' ),
    'WS' => array ( 'country' => __('Samoa','countries'), 'continent' => 'OC' ),
    'SM' => array ( 'country' => __('San Marino','countries'), 'continent' => 'EU' ),
    'ST' => array ( 'country' => __('Sao Tome and Principe','countries'), 'continent' => 'AF' ),
    'SA' => array ( 'country' => __('Saudi Arabia','countries'), 'continent' => 'AS' ),
    'SN' => array ( 'country' => __('Senegal','countries'), 'continent' => 'AF' ),
    'CS' => array ( 'country' => __('Serbia and Montenegro','countries'), 'continent' => '' ),
    'SC' => array ( 'country' => __('Seychelles','countries'), 'continent' => 'AF' ),
    'SL' => array ( 'country' => __('Sierra Leone','countries'), 'continent' => 'AF' ),
    'SG' => array ( 'country' => __('Singapore','countries'), 'continent' => 'AS' ),
    'SK' => array ( 'country' => __('Slovakia','countries'), 'continent' => 'EU' ),
    'SI' => array ( 'country' => __('Slovenia','countries'), 'continent' => 'EU' ),
    'SB' => array ( 'country' => __('Solomon Islands','countries'), 'continent' => 'OC' ),
    'SO' => array ( 'country' => __('Somalia','countries'), 'continent' => 'AF' ),
    'ZA' => array ( 'country' => __('South Africa','countries'), 'continent' => 'AF' ),
    'GS' => array ( 'country' => __('South Georgia and the South Sandwich Islands','countries'), 'continent' => 'AN' ),
    'ES' => array ( 'country' => __('Spain','countries'), 'continent' => 'EU' ),
    'LK' => array ( 'country' => __('Sri Lanka','countries'), 'continent' => 'AS' ),
    'SD' => array ( 'country' => __('Sudan','countries'), 'continent' => 'AF' ),
    'SR' => array ( 'country' => __('Suriname','countries'), 'continent' => 'SA' ),
    'SJ' => array ( 'country' => __('Svalbard and Jan Mayen','countries'), 'continent' => 'EU' ),
    'SZ' => array ( 'country' => __('Swaziland','countries'), 'continent' => 'AF' ),
    'SE' => array ( 'country' => __('Sweden','countries'), 'continent' => 'EU' ),
    'CH' => array ( 'country' => __('Switzerland','countries'), 'continent' => 'EU' ),
    'SY' => array ( 'country' => __('Syrian Arab Republic','countries'), 'continent' => '' ),
    'TW' => array ( 'country' => __('Taiwan','countries'), 'continent' => 'AS' ),
    'TJ' => array ( 'country' => __('Tajikistan','countries'), 'continent' => 'AS' ),
    'TZ' => array ( 'country' => __('Tanzania, United Republic of','countries'), 'continent' => '' ),
    'TH' => array ( 'country' => __('Thailand','countries'), 'continent' => 'AS' ),
    'TL' => array ( 'country' => __('Timor-Leste','countries'), 'continent' => '' ),
    'TG' => array ( 'country' => __('Togo','countries'), 'continent' => 'AF' ),
    'TK' => array ( 'country' => __('Tokelau','countries'), 'continent' => 'OC' ),
    'TO' => array ( 'country' => __('Tonga','countries'), 'continent' => 'OC' ),
    'TT' => array ( 'country' => __('Trinidad and Tobago','countries'), 'continent' => 'NA' ),
    'TN' => array ( 'country' => __('Tunisia','countries'), 'continent' => 'AF' ),
    'TR' => array ( 'country' => __('Turkey','countries'), 'continent' => 'AS' ),
    'TM' => array ( 'country' => __('Turkmenistan','countries'), 'continent' => 'AS' ),
    'TC' => array ( 'country' => __('Turks and Caicos Islands','countries'), 'continent' => 'NA' ),
    'TV' => array ( 'country' => __('Tuvalu','countries'), 'continent' => 'OC' ),
    'UG' => array ( 'country' => __('Uganda','countries'), 'continent' => 'AF' ),
    'UA' => array ( 'country' => __('Ukraine','countries'), 'continent' => 'EU' ),
    'AE' => array ( 'country' => __('United Arab Emirates','countries'), 'continent' => 'AS' ),
    'GB' => array ( 'country' => __('United Kingdom','countries'), 'continent' => 'EU' ),
    'US' => array ( 'country' => __('United States','countries'), 'continent' => 'NA' ),
    'UM' => array ( 'country' => __('United States Minor Outlying Islands','countries'), 'continent' => 'OC' ),
    'UY' => array ( 'country' => __('Uruguay','countries'), 'continent' => 'SA' ),
    'UZ' => array ( 'country' => __('Uzbekistan','countries'), 'continent' => 'AS' ),
    'VU' => array ( 'country' => __('Vanuatu','countries'), 'continent' => 'OC' ),
    'VE' => array ( 'country' => __('Venezuela','countries'), 'continent' => 'SA' ),
    'VN' => array ( 'country' => __('Viet Nam','countries'), 'continent' => '' ),
    'VG' => array ( 'country' => __('Virgin Islands, British','countries'), 'continent' => 'NA' ),
    'VI' => array ( 'country' => __('Virgin Islands, U.s.','countries'), 'continent' => '' ),
    'WF' => array ( 'country' => __('Wallis and Futuna','countries'), 'continent' => 'OC' ),
    'EH' => array ( 'country' => __('Western Sahara','countries'), 'continent' => 'AF' ),
    'YE' => array ( 'country' => __('Yemen','countries'), 'continent' => 'AS' ),
    'ZM' => array ( 'country' => __('Zambia','countries'), 'continent' => 'AF' ),
    'ZW' => array ( 'country' => __('Zimbabwe','countries'), 'continent' => 'AF' ),
    'XX' => array ( 'country' => __('International','countries'), 'continent' => 'IN' ),
    'XY' => array ( 'country' => __('SocialBiz','countries'), 'continent' => 'IN' )
    );
    return $country_list;
}


/**
 * Get Country
 *
 * @since  1.0.0
 * @return array country
 */
function getCountry($code){
  return getCountries()[$code];
}


function sortArrayByArray(array $array, array $orderArray) {
  $ordered = array();
  foreach ($orderArray as $key) {
      if (array_key_exists($key, $array)) {
          $ordered[$key] = $array[$key];
          unset($array[$key]);
      }
  }
  return $ordered + $array;
}


/**
 * Get Countries in Website Networks
 *
 * @since  1.0.0
 * @return array active countries
 */



function getNetworkCountries(){
  $continents = getContinents();

  // Get any existing copy of our transient data
  if ( false === ( $countries = get_transient( 'countries' ) ) || !isset($_GET['cleartransients']) ) {

    $countries=array();
    $time_start = microtime(true);
    //echo $time_start.'---';
    /* $blog_list = get_blog_list( 0, 'all' ); */// Função descontinuada  no WP estava provocando anomalia na gravação dos Paises no Menu WHERE?
    $blog_list = get_sites(); //Funçãoo atualizada de acordo com WP
      
    
      $i = 0;
      foreach($continents as $iso_continent => $continent_name){
          foreach ($blog_list as $blog){



            $cl_blog = $blog->blog_id;
            $cm =  strtoupper(str_replace('/','',$cl_blog)); // if path is in country markets

            $actCountry = getCountry($cm);
            if(!$actCountry){
              $cm = get_blog_option($blog->blog_id,'country_market');
              $actCountry = getCountry($cm);
            }
            $siteurl = get_site_url($blog->blog_id);
            if($blog->archived != '1'){
              if($blog->deleted != '1'){
            
              if($iso_continent==$actCountry['continent']){
                /* if(strpos($siteurl, 'template') 
                || strpos($siteurl, 'teste')  
                || strpos($siteurl, 'test') 
                || strpos($siteurl, 'demo')
                ){ */


                  if(strpos($siteurl, 'network') 
                ){

                }else{
                  $countries[$iso_continent][]= array(
                      'id' => $blog->blog_id,
                      'name' => $actCountry['country'],
                      'link' => $siteurl,
                      'iso' => $cm
                  );
                } 

            $i++;
          }

              }
            }



      }
      }
      //$time_end = microtime(true);
      //echo '-time-'.$execution_time = $time_end - $time_start;
      set_transient( 'countries', $countries, 12 * HOUR_IN_SECONDS );

  }

  return $countries;
}