<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

use WPFEPP\DB_Tables\Form_Meta;

abstract class Form_Tab extends Backend_Tab
{
	protected $form_id;

	protected $form_meta_table;

	function __construct($name, $label, $form_id)
	{
		parent::__construct($name, $label);
		$this->form_meta_table = new Form_Meta();
		$this->form_id = $form_id;
	}
}