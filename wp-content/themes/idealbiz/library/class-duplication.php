<?php

/**
 * Handles Duplication Data
 * 
 * 
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class IDB_Duplication
{

    /**
     * Setup hooks.
     *
     * @since 1.0.0
     */
    public function ready()
    {
        add_action('mucd_after_copy_data', array($this, 'handle_duplication_data'), 10, 2);
    }

    public function handle_duplication_data($from_site_id, $to_site_id)
    {
        $lang_codes = filter_var($_POST['site_languages'], FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        $payment_methods = filter_var($_POST['payment_methods'], FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        switch_to_blog($to_site_id);

        //$this->set_languages($lang_codes);

        $this->set_woocommerce_settings();

        $this->update_setting('exchange_rate');

        $this->update_option('default_published_language');
        $this->update_option('country_market');

        $this->update_option('wc_ie_sequence_id');
        $this->update_option('wc_ie_tax_exemption_reason_options');

        $this->set_payment_methods($payment_methods);

        $this->set_contacts();

        $this->set_social_networks();

        $this->delete_old_locations();

        $this->set_languages($lang_codes);

        //$this->send_email();


        restore_current_blog();
    }

    private function set_woocommerce_settings()
    {
        update_option('woocommerce_calc_taxes', isset($_POST['woocommerce_calc_taxes']) ? 'yes' : 'no');
        $this->update_option('woocommerce_currency');
        $this->update_option('woocommerce_currency_pos');
        $this->update_option('woocommerce_price_thousand_sep');
        $this->update_option('woocommerce_price_decimal_sep');
        $this->update_option('woocommerce_price_num_decimals');
    }

    private function set_contacts()
    {
        $phone = array(
            'call_code' => $_POST['call_code'],
            'number' => $_POST['number']
        );

        update_field('phone', $phone, 'option');
        $this->update_setting('address');
        $this->update_setting('email');
    }

    private function set_social_networks()
    {
        $this->update_setting('facebook');
        $this->update_setting('instagram');
        $this->update_setting('linkedin');
        $this->update_setting('twitter');
        $this->update_setting('youtube');
    }

    private function set_payment_methods($payment_methods)
    {
        if (is_empty($payment_methods)) return;

        $payment_gateways = array(
            'paypal' => new WC_Gateway_Paypal,
            'stripe' => new WC_Gateway_Stripe,
            'multibanco' => new WC_Multibanco_IfThen_Webdados,
            'mbway' => new WC_Mbway_IfThen_Webdados
        );

        foreach ($payment_gateways as $method => $gateway) {
            $gateway->update_option('enabled', isset($payment_methods[$method]) ? 'yes' : 'no');
        }
    }

    private function delete_old_locations()
    {
        $locations_terms = get_terms(array(
            'taxonomy' => 'location',
            'hide_empty' => false,
            'lang' => ''
        ));

        foreach ($locations_terms as $term) {
            wp_delete_term($term->term_id, 'location');
        }
    }

    private function delete_language_terms($lang)
    {
        $terms = get_terms(array(
            'taxonomy' => get_taxonomies(),
            'hide_empty' => false,
            'lang' => $lang
        ));

        foreach ($terms as $term) {
            wp_delete_term($term->term_id, $term->taxonomy);
        }
    }

    private function delete_language_posts($lang)
    {
        $post_types = array('page', 'product', 'post', 'counseling');

        $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => $post_types,
            'post_status' => 'any',
            'lang' => $lang
        ));

        foreach ($posts as $post) {
            wp_delete_post($post->ID, true);
        }
    }

    private function delete_language_attachments($lang)
    {
        $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => 'attachment',
            'post_status' => null,
            'lang' => $lang
        ));

        foreach ($posts as $post) {
            wp_delete_attachment($post->ID, true);
        }
    }

    private function set_languages($lang_codes)
    {
        $args = array('fields' => '');
        $languages = pll_languages_list($args);

        if (count($languages) <= 1)
            return;

        $options = get_option('polylang');

        foreach (array_slice($languages, 0, 2) as $language) {

            if ($language->slug == 'en') {
                continue;
            }

            if (count($lang_codes) > 1) {
                $model = new PLL_Admin_Model($options);
                $links_model = $model->get_links_model();
                $settings = new PLL_Settings($links_model);
                $predefined_languages = $settings->get_predefined_languages();
                
                foreach (array_slice($lang_codes, 0, 2) as $lang_code) {
                    $lang_args = $predefined_languages[$lang_code];

                    if ($lang_args['code'] == 'en') {
                        continue;
                    }

                    $args = [
                        'lang_id' => $language->term_id,
                        'name' => $lang_args['name'],
                        'slug' => $lang_args['code'],
                        'locale' => $lang_args['locale'],
                        'rtl' => $lang_args['dir'] == 'rtl',
                        'term_group' => $language->term_group,
                        'flag' => $lang_args['flag']
                    ];

                    $model->update_language($args);
                }
            } else {
                $this->delete_language_terms($language->slug);
                $this->delete_language_posts($language->slug);
                $this->delete_language_attachments($language->slug);
                $model = new PLL_Admin_Model($options);
                $model->delete_language($language->term_id);
            }
        }
    }

    private function update_option($option)
    {
        if (!empty($_POST[$option]))
            update_option($option, $_POST[$option]);
    }

    private function update_setting($setting)
    {
        if (!empty($_POST[$setting]))
            update_field($setting, $_POST[$setting], 'option');
    }

    private function send_email()
    {
        $to      = 'nelson.sousa@md3.pt';
        $subject = 'the subject';
        $message = 'hello';
        $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        wp_mail($to, $subject, $message, $headers);
    }
}
