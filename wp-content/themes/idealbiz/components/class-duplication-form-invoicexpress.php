<?php

/**
 * Prints InvoiceXpress duplication data
 * 
 *
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class Component_Duplication_Form_Invoicexpress
{

    function __construct()
    {
        $invoicexpress_plugin_dir = WP_PLUGIN_DIR . '/woocommerce-invoicexpress-pro/';
        require_once($invoicexpress_plugin_dir . 'InvoiceXpressRequest-PHP-API/lib/InvoiceXpressRequest.php');
        $this->subdomain = get_blog_option(86, 'wc_ie_subdomain');
        $this->token     = get_blog_option(86, 'wc_ie_api_token');
    }

    public function wc_ie_sequence_id()
    {
        $response  = array();
        $sequences = array();
        $options   = array();

        if (!$this->subdomain || !$this->token) {
            echo __('You should be able to choose the sequence for your invoices once you establish a successful connection.', 'wc_invoicexpress');
            return;
        }

        InvoiceXpressRequest::init($this->subdomain, $this->token);

        $invoice = new InvoiceXpressRequest('sequences.get');

        $invoice->request();

        if ($invoice->success()) {
            $response = $invoice->getResponse();

            if (isset($response['sequence'])) {
                $sequences = $response['sequence'];
            }

            if (count($sequences) > 0) {
                foreach ($sequences as $key => $val) {

                    if (is_int($key)) {
                        // assume multiple sequences available
                        $seq_id = $val['id'];
                        $options[$seq_id] = $val['serie'];
                    } else {
                        // assume only one sequence
                        if ($key === 'id') {
                            $seq_id = $val;
                        }
                        if ($key === 'serie') {
                            $options[$seq_id] = $val;
                        }
                    }
                }
            }
            $sequences = $options;

            echo '<select name="wc_ie_sequence_id" >';
            foreach ($sequences as $key => $value) {
                $selected = (get_option('wc_ie_sequence_id') == $key) ? 'selected' : '';
                echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
            }

            echo '</select>';
            echo ' <label for="wc_ie_wc_ie_sequence_id">' . __('The sequence to use for the invoices.', 'wc_invoicexpress') . '</label>';
        } else {
            echo __('You will be able to choose the sequence for your invoices once you establish a successful connection.', 'wc_invoicexpress');
        }

        return;
    }

    public function wc_ie_tax_exemption_reason_options()
    {

        $tax_exemption_reason_options = array(
            'M00' => __('Sem Isenção.', 'wc_invoicexpress'),
            'M16' => __('Isento Artigo 14º do RITI.', 'wc_invoicexpress'),
            'M15' => __('Regime da margem de lucro–Objetos de coleção e antiguidades.', 'wc_invoicexpress'),
            'M14' => __('Regime da margem de lucro – Objetos de arte.', 'wc_invoicexpress'),
            'M13' => __('Regime da margem de lucro – Bens em segunda mão.', 'wc_invoicexpress'),
            'M12' => __('Regime da margem de lucro – Agências de viagens. ', 'wc_invoicexpress'),
            'M11' => __('Regime particular do tabaco.', 'wc_invoicexpress'),
            'M10' => __('IVA – Regime de isenção.', 'wc_invoicexpress'),
            'M99' => __('Não sujeito; não tributado (ou similar).', 'wc_invoicexpress'),
            'M09' => __('IVA ‐ não confere direito a dedução.', 'wc_invoicexpress'),
            'M08' => __('IVA – autoliquidação.', 'wc_invoicexpress'),
            'M07' => __('Isento Artigo 9º do CIVA. ', 'wc_invoicexpress'),
            'M06' => __('Isento Artigo 15º do CIVA.', 'wc_invoicexpress'),
            'M05' => __('Isento Artigo 14º do CIVA.', 'wc_invoicexpress'),
            'M04' => __('Isento Artigo 13º do CIVA.', 'wc_invoicexpress'),
            'M03' => __('Exigibilidade de caixa.', 'wc_invoicexpress'),
            'M02' => __('Artigo 6º do Decreto‐Lei nº 198/90, de 19 de Junho.', 'wc_invoicexpress'),
            'M01' => __('Artigo 16º nº 6 do CIVA.', 'wc_invoicexpress'),
        );

        echo '<select name="wc_ie_tax_exemption_reason_options" >';
        foreach ($tax_exemption_reason_options as $key => $value) {
            $selected = (get_option('wc_ie_tax_exemption_reason_options') === $key) ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }
    }
}
