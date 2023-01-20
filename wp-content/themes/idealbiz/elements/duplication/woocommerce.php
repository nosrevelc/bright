<tr class="form-field form-required">
    <th scope="row"><label><?php _e('Enable taxes', 'idealbiz'); ?> <span class="required">*</span></label></th>
    <td>
        <fieldset>
            <legend class="screen-reader-text"><span>Enable taxes</span></legend>
            <label for="woocommerce_calc_taxes">
                <input name="woocommerce_calc_taxes" id="woocommerce_calc_taxes" type="checkbox" class="" value="1" checked="checked"> Enable tax rates and calculations
            </label>
            <p class="description">Rates will be configurable and taxes will be calculated during checkout.</p>
        </fieldset>
    </td>
</tr>
<tr class="form-field form-required">
    <th scope="row"><label for="site-currency"><?php _e('Currency', 'idealbiz'); ?> <span class="required">*</span></label></th>
    <td>
        <select name="woocommerce_currency" id="site-currency">
            <option value="AED">United Arab Emirates dirham (د.إ)</option>
            <option value="AFN">Afghan afghani (؋)</option>
            <option value="ALL">Albanian lek (L)</option>
            <option value="AMD">Armenian dram (AMD)</option>
            <option value="ANG">Netherlands Antillean guilder (ƒ)</option>
            <option value="AOA">Angolan kwanza (Kz)</option>
            <option value="ARS">Argentine peso ($)</option>
            <option value="AUD">Australian dollar ($)</option>
            <option value="AWG">Aruban florin (Afl.)</option>
            <option value="AZN">Azerbaijani manat (AZN)</option>
            <option value="BAM">Bosnia and Herzegovina convertible mark (KM)</option>
            <option value="BBD">Barbadian dollar ($)</option>
            <option value="BDT">Bangladeshi taka (৳&nbsp;)</option>
            <option value="BGN">Bulgarian lev (лв.)</option>
            <option value="BHD">Bahraini dinar (.د.ب)</option>
            <option value="BIF">Burundian franc (Fr)</option>
            <option value="BMD">Bermudian dollar ($)</option>
            <option value="BND">Brunei dollar ($)</option>
            <option value="BOB">Bolivian boliviano (Bs.)</option>
            <option value="BRL">Brazilian real (R$)</option>
            <option value="BSD">Bahamian dollar ($)</option>
            <option value="BTC">Bitcoin (฿)</option>
            <option value="BTN">Bhutanese ngultrum (Nu.)</option>
            <option value="BWP">Botswana pula (P)</option>
            <option value="BYR">Belarusian ruble (old) (Br)</option>
            <option value="BYN">Belarusian ruble (Br)</option>
            <option value="BZD">Belize dollar ($)</option>
            <option value="CAD">Canadian dollar ($)</option>
            <option value="CDF">Congolese franc (Fr)</option>
            <option value="CHF">Swiss franc (CHF)</option>
            <option value="CLP">Chilean peso ($)</option>
            <option value="CNY">Chinese yuan (¥)</option>
            <option value="COP">Colombian peso ($)</option>
            <option value="CRC">Costa Rican colón (₡)</option>
            <option value="CUC">Cuban convertible peso ($)</option>
            <option value="CUP">Cuban peso ($)</option>
            <option value="CVE">Cape Verdean escudo ($)</option>
            <option value="CZK">Czech koruna (Kč)</option>
            <option value="DJF">Djiboutian franc (Fr)</option>
            <option value="DKK">Danish krone (DKK)</option>
            <option value="DOP">Dominican peso (RD$)</option>
            <option value="DZD">Algerian dinar (د.ج)</option>
            <option value="EGP">Egyptian pound (EGP)</option>
            <option value="ERN">Eritrean nakfa (Nfk)</option>
            <option value="ETB">Ethiopian birr (Br)</option>
            <option value="EUR">Euro (€)</option>
            <option value="FJD">Fijian dollar ($)</option>
            <option value="FKP">Falkland Islands pound (£)</option>
            <option value="GBP" selected="selected">Pound sterling (£)</option>
            <option value="GEL">Georgian lari (₾)</option>
            <option value="GGP">Guernsey pound (£)</option>
            <option value="GHS">Ghana cedi (₵)</option>
            <option value="GIP">Gibraltar pound (£)</option>
            <option value="GMD">Gambian dalasi (D)</option>
            <option value="GNF">Guinean franc (Fr)</option>
            <option value="GTQ">Guatemalan quetzal (Q)</option>
            <option value="GYD">Guyanese dollar ($)</option>
            <option value="HKD">Hong Kong dollar ($)</option>
            <option value="HNL">Honduran lempira (L)</option>
            <option value="HRK">Croatian kuna (kn)</option>
            <option value="HTG">Haitian gourde (G)</option>
            <option value="HUF">Hungarian forint (Ft)</option>
            <option value="IDR">Indonesian rupiah (Rp)</option>
            <option value="ILS">Israeli new shekel (₪)</option>
            <option value="IMP">Manx pound (£)</option>
            <option value="INR">Indian rupee (₹)</option>
            <option value="IQD">Iraqi dinar (ع.د)</option>
            <option value="IRR">Iranian rial (﷼)</option>
            <option value="IRT">Iranian toman (تومان)</option>
            <option value="ISK">Icelandic króna (kr.)</option>
            <option value="JEP">Jersey pound (£)</option>
            <option value="JMD">Jamaican dollar ($)</option>
            <option value="JOD">Jordanian dinar (د.ا)</option>
            <option value="JPY">Japanese yen (¥)</option>
            <option value="KES">Kenyan shilling (KSh)</option>
            <option value="KGS">Kyrgyzstani som (сом)</option>
            <option value="KHR">Cambodian riel (៛)</option>
            <option value="KMF">Comorian franc (Fr)</option>
            <option value="KPW">North Korean won (₩)</option>
            <option value="KRW">South Korean won (₩)</option>
            <option value="KWD">Kuwaiti dinar (د.ك)</option>
            <option value="KYD">Cayman Islands dollar ($)</option>
            <option value="KZT">Kazakhstani tenge (KZT)</option>
            <option value="LAK">Lao kip (₭)</option>
            <option value="LBP">Lebanese pound (ل.ل)</option>
            <option value="LKR">Sri Lankan rupee (රු)</option>
            <option value="LRD">Liberian dollar ($)</option>
            <option value="LSL">Lesotho loti (L)</option>
            <option value="LYD">Libyan dinar (ل.د)</option>
            <option value="MAD">Moroccan dirham (د.م.)</option>
            <option value="MDL">Moldovan leu (MDL)</option>
            <option value="MGA">Malagasy ariary (Ar)</option>
            <option value="MKD">Macedonian denar (ден)</option>
            <option value="MMK">Burmese kyat (Ks)</option>
            <option value="MNT">Mongolian tögrög (₮)</option>
            <option value="MOP">Macanese pataca (P)</option>
            <option value="MRU">Mauritanian ouguiya (UM)</option>
            <option value="MUR">Mauritian rupee (₨)</option>
            <option value="MVR">Maldivian rufiyaa (.ރ)</option>
            <option value="MWK">Malawian kwacha (MK)</option>
            <option value="MXN">Mexican peso ($)</option>
            <option value="MYR">Malaysian ringgit (RM)</option>
            <option value="MZN">Mozambican metical (MT)</option>
            <option value="NAD">Namibian dollar (N$)</option>
            <option value="NGN">Nigerian naira (₦)</option>
            <option value="NIO">Nicaraguan córdoba (C$)</option>
            <option value="NOK">Norwegian krone (kr)</option>
            <option value="NPR">Nepalese rupee (₨)</option>
            <option value="NZD">New Zealand dollar ($)</option>
            <option value="OMR">Omani rial (ر.ع.)</option>
            <option value="PAB">Panamanian balboa (B/.)</option>
            <option value="PEN">Sol (S/)</option>
            <option value="PGK">Papua New Guinean kina (K)</option>
            <option value="PHP">Philippine peso (₱)</option>
            <option value="PKR">Pakistani rupee (₨)</option>
            <option value="PLN">Polish złoty (zł)</option>
            <option value="PRB">Transnistrian ruble (р.)</option>
            <option value="PYG">Paraguayan guaraní (₲)</option>
            <option value="QAR">Qatari riyal (ر.ق)</option>
            <option value="RON">Romanian leu (lei)</option>
            <option value="RSD">Serbian dinar (дин.)</option>
            <option value="RUB">Russian ruble (₽)</option>
            <option value="RWF">Rwandan franc (Fr)</option>
            <option value="SAR">Saudi riyal (ر.س)</option>
            <option value="SBD">Solomon Islands dollar ($)</option>
            <option value="SCR">Seychellois rupee (₨)</option>
            <option value="SDG">Sudanese pound (ج.س.)</option>
            <option value="SEK">Swedish krona (kr)</option>
            <option value="SGD">Singapore dollar ($)</option>
            <option value="SHP">Saint Helena pound (£)</option>
            <option value="SLL">Sierra Leonean leone (Le)</option>
            <option value="SOS">Somali shilling (Sh)</option>
            <option value="SRD">Surinamese dollar ($)</option>
            <option value="SSP">South Sudanese pound (£)</option>
            <option value="STN">São Tomé and Príncipe dobra (Db)</option>
            <option value="SYP">Syrian pound (ل.س)</option>
            <option value="SZL">Swazi lilangeni (L)</option>
            <option value="THB">Thai baht (฿)</option>
            <option value="TJS">Tajikistani somoni (ЅМ)</option>
            <option value="TMT">Turkmenistan manat (m)</option>
            <option value="TND">Tunisian dinar (د.ت)</option>
            <option value="TOP">Tongan paʻanga (T$)</option>
            <option value="TRY">Turkish lira (₺)</option>
            <option value="TTD">Trinidad and Tobago dollar ($)</option>
            <option value="TWD">New Taiwan dollar (NT$)</option>
            <option value="TZS">Tanzanian shilling (Sh)</option>
            <option value="UAH">Ukrainian hryvnia (₴)</option>
            <option value="UGX">Ugandan shilling (UGX)</option>
            <option value="USD">United States (US) dollar ($)</option>
            <option value="UYU">Uruguayan peso ($)</option>
            <option value="UZS">Uzbekistani som (UZS)</option>
            <option value="VEF">Venezuelan bolívar (Bs F)</option>
            <option value="VES">Bolívar soberano (Bs.S)</option>
            <option value="VND">Vietnamese đồng (₫)</option>
            <option value="VUV">Vanuatu vatu (Vt)</option>
            <option value="WST">Samoan tālā (T)</option>
            <option value="XAF">Central African CFA franc (CFA)</option>
            <option value="XCD">East Caribbean dollar ($)</option>
            <option value="XOF">West African CFA franc (CFA)</option>
            <option value="XPF">CFP franc (Fr)</option>
            <option value="YER">Yemeni rial (﷼)</option>
            <option value="ZAR">South African rand (R)</option>
            <option value="ZMW">Zambian kwacha (ZK)</option>
        </select>
    </td>
</tr>
<tr class="form-field form-required">
    <th scope="row"><label><?php _e('Currency Position', 'idealbiz'); ?> <span class="required">*</span></label></th>
    <td>
        <select name="woocommerce_currency_pos">
            <option value="left" selected="selected">Left</option>
            <option value="right">Right</option>
            <option value="left_space">Left with space</option>
            <option value="right_space">Right with space</option>
        </select>
    </td>
</tr>
<tr class="form-field form-required">
    <th scope="row"><label><?php _e('Thousand separator', 'idealbiz'); ?> <span class="required">*</span></label></th>
    <td>
        <input name="woocommerce_price_thousand_sep" type="text" value="." style="width:50px;">
    </td>
</tr>
<tr class="form-field form-required">
    <th scope="row"><label><?php _e('Decimal separator', 'idealbiz'); ?> <span class="required">*</span></label></th>
    <td>
        <input name="woocommerce_price_decimal_sep" type="text" value="." style="width:50px;">
    </td>
</tr>
<tr class="form-field form-required">
    <th scope="row"><label><?php _e('Number of decimals', 'idealbiz'); ?> <span class="required">*</span></label></th>
    <td>
        <input name="woocommerce_price_num_decimals" type="number" value="2" min="0" step="1" style="width:50px;">
    </td>
</tr>