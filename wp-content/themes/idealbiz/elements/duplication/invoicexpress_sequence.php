<tr class="form-field form-required">
    <th scope="row"><label for="site-test"><?php _e('InvoiceXpress Sequence', 'idealbiz'); ?> <span class="required">*</span></label></th>
    <td>
        <?php
            $duplication_invoicexpress = new Component_Duplication_Form_Invoicexpress;
            $duplication_invoicexpress->wc_ie_sequence_id();
        ?>
    </td>
</tr>