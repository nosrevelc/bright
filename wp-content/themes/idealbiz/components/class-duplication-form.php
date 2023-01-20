<?php

/**
 * Prints duplication site form
 * 
 *
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class Component_Duplication_Form
{

    /**
     * Setup hooks.
     *
     * @since 1.0.0
     */
    public function ready()
    {
        add_action('duplicate_site_new_form', array($this, 'duplicate_site_form'));
    }

    public function duplicate_site_form()
    {
        $options = get_option('polylang');
        $model = new PLL_Admin_Model($options);
        $links_model = $model->get_links_model();
        $settings = new PLL_Settings($links_model);

        $json = file_get_contents(get_template_directory() . '/data/countries.json');
        $countries = json_decode($json);
        $languages = $settings->get_predefined_languages();

        ?>
        <table class="form-table" role="presentation">
            <tbody>
                <?php
                        $this->country_market($countries);
                        $this->woocommerce();
                        $this->exchange_rate();
                        $this->languages($languages);
                        $this->published_language();
                        $this->invoicexpress();
                        $this->payment_methods();
                        $this->contacts();
                        $this->social_networks();
                        ?>
            </tbody>
        </table>
<?php
    }

    private function country_market($countries)
    {
        set_query_var('countries', $countries);
        get_template_part('elements/duplication/country-market');
    }

    private function woocommerce()
    {
        get_template_part('elements/duplication/woocommerce');
    }

    private function exchange_rate()
    {
        get_template_part('elements/duplication/exchange-rate');
    }

    private function languages($languages)
    {
        set_query_var('languages', $languages);
        get_template_part('elements/duplication/languages');
    }

    private function published_language()
    {
        get_template_part('elements/duplication/published-language');
    }

    private function invoicexpress()
    {
        get_template_part('elements/duplication/invoicexpress');
    }

    private function payment_methods()
    {
        get_template_part('elements/duplication/payment-methods');
    }

    private function contacts()
    {
        get_template_part('elements/duplication/contacts');
    }

    private function social_networks()
    {
        get_template_part('elements/duplication/social-networks');
    }
}
