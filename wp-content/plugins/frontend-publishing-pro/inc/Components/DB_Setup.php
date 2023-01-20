<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\DB_Tables\Form_Meta;
use WPFEPP\DB_Tables\Forms;
use WPGurus\Components\Component;

class DB_Setup extends Component
{
	function __construct()
	{
		parent::__construct();

		$this->register_installation_action(array($this, 'create_tables'));
	}

	private function create_forms_table()
	{
		$forms_table = new Forms();
		$forms_table->create_table();
	}

	private function create_form_meta_table()
	{
		$form_meta_table = new Form_Meta();
		$form_meta_table->create_table();
	}

	public function create_tables()
	{
		$this->create_forms_table();
		$this->create_form_meta_table();
	}
}