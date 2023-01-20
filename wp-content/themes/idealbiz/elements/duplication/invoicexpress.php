<?php
$duplication_invoicexpress = new Component_Duplication_Form_Invoicexpress;
?>
<tr class="form-field form-required">
    <th scope="row"><label for="site-test"><?php _e('InvoiceXpress Sequence', 'wc_invoicexpress'); ?> <span class="required">*</span></label></th>
    <td>
        <?php
        $duplication_invoicexpress->wc_ie_sequence_id();
        ?>
    </td>
</tr>
<tr class="form-field form-required">
    <th scope="row"><label for="site-test"><?php _e('Tax exemption reason', 'wc_invoicexpress'); ?> <span class="required">*</span></label></th>
    <td>
        <?php
        $duplication_invoicexpress->wc_ie_tax_exemption_reason_options();
        ?>
    </td>
</tr>