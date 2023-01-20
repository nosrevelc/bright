<tr class="form-field form-required">
    <th scope="row"><label for="site-test"><?php _e('Payment Methods', 'idealbiz'); ?></label></th>
    <td>
        <p><label><input checked="checked" name="payment_methods[paypal]" type="checkbox"><?php _e('PayPal', 'idealbiz'); ?></label></p>
        <p><label><input checked="checked" name="payment_methods[stripe]" type="checkbox"><?php _e('Stripe', 'idealbiz'); ?></label></p>
        <p><label><input name="payment_methods[multibanco]" type="checkbox"><?php _e('Pagamento de Serviços no Multibanco (IfthenPay)', 'idealbiz'); ?></label></p>
        <p><label><input name="payment_methods[mbway]" type="checkbox"><?php _e('Pagamento MB WAY no telemóvel (IfthenPay)', 'idealbiz'); ?></label></p>
    </td>
</tr>