<?php
namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\DB_Tables\Form_Meta;

abstract class Backend_DB_Form extends Backend_Form
{
	protected $form_db_id;
	protected $db_table;
	protected $meta_key;

	/**
	 * @param int|string $form_db_id
	 * @param string $meta_key
	 * @param string $form_id
	 */
	public function __construct($form_db_id, $meta_key, $form_id)
	{
		parent::__construct($form_id);

		$this->form_db_id = $form_db_id;
		$this->db_table = new Form_Meta();
		$this->meta_key = $meta_key;
	}

	function process_data($cleaned_data)
	{
		$result = $this->db_table->update_meta_value($this->form_db_id, $this->meta_key, $cleaned_data);

		if ($result === false) {
			$this->add_form_errors('Your changes could not be saved. The following DB error occured: <br/><br/><pre>' . $this->db_table->get_error() . '</pre>');
			return false;
		} else {
			$this->set_success_message(__('Your changes have been saved.', 'frontend-publishing-pro'));
			return true;
		}
	}
}