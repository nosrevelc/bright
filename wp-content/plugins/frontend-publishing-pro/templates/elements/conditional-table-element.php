<?php if (!defined('WPINC')) die; ?>
<tr
	<?php if(isset($cond_option) && isset($cond_value)): ?>
		class="conditionized"
		data-cond-option="<?php echo $cond_option; ?>"
		data-cond-value="<?php echo $cond_value; ?>"
	<?php endif; ?>
>
	<th scope="row"><?php echo $label; ?></th>
	<td>
		<?php echo $field_html; ?>
		<p class="description"><?php echo $postfix_text; ?></p>
	</td>
</tr>