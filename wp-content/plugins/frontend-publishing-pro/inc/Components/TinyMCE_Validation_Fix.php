<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPGurus\Components\Component;

class TinyMCE_Validation_Fix extends Component
{
	public function __construct()
	{
		parent::__construct();

		$this->register_filter('tiny_mce_before_init', array($this, 'tinymce_on_change'));
	}

	public function tinymce_on_change($initArray)
	{
		if (is_admin())
			return $initArray;

		$initArray['setup'] = <<<JS
[function(ed) {
	if(typeof tinyMCE == 'undefined' || tinyMCE.majorVersion < 4)
		return;

	ed.on('blur', function(e) {
		tinyMCE.triggerSave();
		jQuery('#'+ed.id).trigger('blur');
	});

	ed.on('keyUp', function(e) {
		tinyMCE.triggerSave();
		jQuery('#'+ed.id).trigger('keyup');
	});
}][0]
JS;
		return $initArray;
	}
}