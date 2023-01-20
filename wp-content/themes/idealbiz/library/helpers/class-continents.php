<?php

/**
 * Continents helper
 * 
 * 
 *
 * @author MD3 <https://www.md3.pt>
 */


class IDB_Continents
{
    private $CONTINENTS = array(
        "AS" => "Asia",
        "AN" => "Antarctica",
        "AF" => "Africa",
        "SA" => "South America",
        "EU" => "Europe",
        "OC" => "Oceania",
        "NA" => "North America"
    );

    private $COUNTRY_CONTINENTS = array(
        "AF" => "AS",
        "AX" => "EU",
        "AL" => "EU",
        "DZ" => "AF",
        "AS" => "OC",
        "AD" => "EU",
        "AO" => "AF",
        "AI" => "NA",
        "AQ" => "AN",
        "AG" => "NA",
        "AR" => "SA",
        "AM" => "AS",
        "AW" => "NA",
        "AU" => "OC",
        "AT" => "EU",
        "AZ" => "AS",
        "BS" => "NA",
        "BH" => "AS",
        "BD" => "AS",
        "BB" => "NA",
        "BY" => "EU",
        "BE" => "EU",
        "BZ" => "NA",
        "BJ" => "AF",
        "BM" => "NA",
        "BT" => "AS",
        "BO" => "SA",
        "BA" => "EU",
        "BW" => "AF",
        "BV" => "AN",
        "BR" => "SA",
        "IO" => "AS",
        "BN" => "AS",
        "BG" => "EU",
        "BF" => "AF",
        "BI" => "AF",
        "KH" => "AS",
        "CM" => "AF",
        "CA" => "NA",
        "CV" => "AF",
        "KY" => "NA",
        "CF" => "AF",
        "TD" => "AF",
        "CL" => "SA",
        "CN" => "AS",
        "CX" => "AS",
        "CC" => "AS",
        "CO" => "SA",
        "KM" => "AF",
        "CD" => "AF",
        "CG" => "AF",
        "CK" => "OC",
        "CR" => "NA",
        "CI" => "AF",
        "HR" => "EU",
        "CU" => "NA",
        "CY" => "AS",
        "CZ" => "EU",
        "DK" => "EU",
        "DJ" => "AF",
        "DM" => "NA",
        "DO" => "NA",
        "EC" => "SA",
        "EG" => "AF",
        "SV" => "NA",
        "GQ" => "AF",
        "ER" => "AF",
        "EE" => "EU",
        "ET" => "AF",
        "FO" => "EU",
        "FK" => "SA",
        "FJ" => "OC",
        "FI" => "EU",
        "FR" => "EU",
        "GF" => "SA",
        "PF" => "OC",
        "TF" => "AN",
        "GA" => "AF",
        "GM" => "AF",
        "GE" => "AS",
        "DE" => "EU",
        "GH" => "AF",
        "GI" => "EU",
        "GR" => "EU",
        "GL" => "NA",
        "GD" => "NA",
        "GP" => "NA",
        "GU" => "OC",
        "GT" => "NA",
        "GG" => "EU",
        "GN" => "AF",
        "GW" => "AF",
        "GY" => "SA",
        "HT" => "NA",
        "HM" => "AN",
        "VA" => "EU",
        "HN" => "NA",
        "HK" => "AS",
        "HU" => "EU",
        "IS" => "EU",
        "IN" => "AS",
        "ID" => "AS",
        "IR" => "AS",
        "IQ" => "AS",
        "IE" => "EU",
        "IM" => "EU",
        "IL" => "AS",
        "IT" => "EU",
        "JM" => "NA",
        "JP" => "AS",
        "JE" => "EU",
        "JO" => "AS",
        "KZ" => "AS",
        "KE" => "AF",
        "KI" => "OC",
        "KP" => "AS",
        "KR" => "AS",
        "KW" => "AS",
        "KG" => "AS",
        "LA" => "AS",
        "LV" => "EU",
        "LB" => "AS",
        "LS" => "AF",
        "LR" => "AF",
        "LY" => "AF",
        "LI" => "EU",
        "LT" => "EU",
        "LU" => "EU",
        "MO" => "AS",
        "MK" => "EU",
        "MG" => "AF",
        "MW" => "AF",
        "MY" => "AS",
        "MV" => "AS",
        "ML" => "AF",
        "MT" => "EU",
        "MH" => "OC",
        "MQ" => "NA",
        "MR" => "AF",
        "MU" => "AF",
        "YT" => "AF",
        "MX" => "NA",
        "FM" => "OC",
        "MD" => "EU",
        "MC" => "EU",
        "MN" => "AS",
        "ME" => "EU",
        "MS" => "NA",
        "MA" => "AF",
        "MZ" => "AF",
        "MM" => "AS",
        "NA" => "AF",
        "NR" => "OC",
        "NP" => "AS",
        "AN" => "NA",
        "NL" => "EU",
        "NC" => "OC",
        "NZ" => "OC",
        "NI" => "NA",
        "NE" => "AF",
        "NG" => "AF",
        "NU" => "OC",
        "NF" => "OC",
        "MP" => "OC",
        "NO" => "EU",
        "OM" => "AS",
        "PK" => "AS",
        "PW" => "OC",
        "PS" => "AS",
        "PA" => "NA",
        "PG" => "OC",
        "PY" => "SA",
        "PE" => "SA",
        "PH" => "AS",
        "PN" => "OC",
        "PL" => "EU",
        "PT" => "EU",
        "PR" => "NA",
        "QA" => "AS",
        "RE" => "AF",
        "RO" => "EU",
        "RU" => "EU",
        "RW" => "AF",
        "SH" => "AF",
        "KN" => "NA",
        "LC" => "NA",
        "PM" => "NA",
        "VC" => "NA",
        "WS" => "OC",
        "SM" => "EU",
        "ST" => "AF",
        "SA" => "AS",
        "SN" => "AF",
        "RS" => "EU",
        "SC" => "AF",
        "SL" => "AF",
        "SG" => "AS",
        "SK" => "EU",
        "SI" => "EU",
        "SB" => "OC",
        "SO" => "AF",
        "ZA" => "AF",
        "GS" => "AN",
        "ES" => "EU",
        "LK" => "AS",
        "SD" => "AF",
        "SR" => "SA",
        "SJ" => "EU",
        "SZ" => "AF",
        "SE" => "EU",
        "CH" => "EU",
        "SY" => "AS",
        "TW" => "AS",
        "TJ" => "AS",
        "TZ" => "AF",
        "TH" => "AS",
        "TL" => "AS",
        "TG" => "AF",
        "TK" => "OC",
        "TO" => "OC",
        "TT" => "NA",
        "TN" => "AF",
        "TR" => "AS",
        "TM" => "AS",
        "TC" => "NA",
        "TV" => "OC",
        "UG" => "AF",
        "UA" => "EU",
        "AE" => "AS",
        "GB" => "EU",
        "UM" => "OC",
        "US" => "NA",
        "UY" => "SA",
        "UZ" => "AS",
        "VU" => "OC",
        "VE" => "SA",
        "VN" => "AS",
        "VG" => "NA",
        "VI" => "NA",
        "WF" => "OC",
        "EH" => "AF",
        "YE" => "AS",
        "ZM" => "AF",
        "ZW" => "AF"
    );


    public function get_country_continent($country_code)
    {
        $continent_code = $this->COUNTRY_CONTINENTS[$country_code];
        $continent = __($this->CONTINENTS[$continent_code]);
        return $continent;
    }

    public function get_continent_countries()
    {
        $args = array(
            'site__not_in' => array(1)
        );

        $json = file_get_contents(get_template_directory() . '/data/countries.json');
        $all_countries = json_decode($json);

        $continent_countries = array();

        foreach (get_sites($args) as $blog) {
            switch_to_blog($blog->blog_id);

            $country_code = get_option('country_market');
            $continent_code = $this->COUNTRY_CONTINENTS[$country_code];
            $continent_name = __($this->CONTINENTS[$continent_code]);

            foreach ($all_countries as $country) {

                if ($country_code == $country->iso) {
                    $country_name = $country->country;
                    break;
                }
            }

            $continent_countries[$continent_code]['name'] = $continent_name;
            $continent_countries[$continent_code]['countries'][$country_code]['name'] = $country_name;
            $continent_countries[$continent_code]['countries'][$country_code]['url'] = get_site_url();

            restore_current_blog();
        }

        return $continent_countries;
    }
}
