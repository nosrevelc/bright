<?php
namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\DB_Tables\Form_Meta;
use WPGurus\Forms\Element;
use WPGurus\Forms\Element_Container;
use WPGurus\Forms\Elements\Submit_Button;
use WPGurus\Forms\Form;

/**
 * A form that inserts form meta values into the DB on submission.
 * @package WPFEPP\Forms
 */
class Splitter_Form extends Form
{
	/**
	 * DB id of the form.
	 * @var int
	 */
	private $form_id;

	/**
	 * Meta key against which this form will insert a value on submission.
	 * @var string
	 */
	private $meta_key;

	/**
	 * The meta value to insert into the form meta table.
	 * @var array|string
	 */
	private $meta_value;

	/**
	 * The message displayed to the user.
	 * @var string
	 */
	private $message;

	/**
	 * The text displayed on the submission button.
	 * @var string
	 */
	private $button_text;

	/**
	 * Splitter_Form constructor.
	 * @param string|int $form_id DB id of the form.
	 * @param string $meta_key Value for the data member of the same name.
	 * @param array $meta_value Value for the data member of the same name.
	 * @param string $message Value for the data member of the same name.
	 * @param string $button_text Value for the data member of the same name.
	 */
	function __construct($form_id, $meta_key, $meta_value, $message = '', $button_text = '')
	{
		parent::__construct('splitter-form');
		$this->form_id = $form_id;
		$this->meta_key = $meta_key;
		$this->meta_value = $meta_value;
		$this->message = $message;
		$this->button_text = $button_text;

		$element_container = new Element_Container();
		$element_container->add_element(
			new Submit_Button(
				array(
					Element::KEY        => 'submit',
					Element::VALUE      => $this->button_text,
					Element::PREFIX     => $this->message,
					Element::TEMPLATE   => WPFEPP_ELEMENT_TEMPLATES_DIR . 'splitter-button.php',
					Element::ATTRIBUTES => array(
						'class' => 'button button-primary'
					)
				)
			)
		);
		$this->set_element_container($element_container);
	}

	/**
	 * Inserts the meta value into the DB.
	 * @param array $data User submitted data.
	 * @return bool Indicates success or failure.
	 */
	function process_data($data)
	{
		$form_meta_table = new Form_Meta();
		$result = $form_meta_table->add_meta_value($this->form_id, $this->meta_key, $this->meta_value);

		if ($result === false) {
			$this->add_form_errors('The action could not be completed because of this error: <br/><br/><pre>' . $form_meta_table->get_error() . '</pre>');
			return false;
		} else {
			// If we don't redirect to the current page, the user will see the splitter form. We want them to see the form containing meta values instead.
			wp_redirect(
				esc_url_raw(add_query_arg(array()))
			);
			exit;
		}
	}
}