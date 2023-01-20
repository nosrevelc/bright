<?php if (!defined('WPINC')) die; ?>
<?php
	foreach ($elements as $element) {
		$element->render();
	}
?>
<span class="frontend-form-icon frontend-icon-loading"><i class="fa fa-refresh fa-spin"></i></span>
<span class="frontend-form-icon frontend-icon-success"><i class="fa fa-check"></i></span>
<span class="frontend-form-icon frontend-icon-error"><i class="fa fa-times"></i></span>