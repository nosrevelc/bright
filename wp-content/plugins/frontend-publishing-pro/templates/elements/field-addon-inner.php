<?php if (!defined('WPINC')) die; ?>
<table class="form-table">
	<tr>
		<th scope="row"><?php echo $label; ?></th>
		<td>
			<?php echo $field_html; ?>
			<p class="description"><?php echo $postfix_text; ?></p>
		</td>
		<td>
			<span class="field-addon-remove"></span>
		</td>
	</tr>
</table>