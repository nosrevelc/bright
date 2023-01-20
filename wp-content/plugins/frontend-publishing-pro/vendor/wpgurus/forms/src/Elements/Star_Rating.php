<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use \WPGurus\Forms\Constants\Assets;
use \WPGurus\Forms\Sanitizers\Typecast;
use \WPGurus\Forms\Components\Asset_Manager;

/**
 * Allows users to enter input in the form of a rating. Uses a jQuery plugin.
 *
 * Class Star_Rating
 * @package WPGurus\Forms\Elements
 */
class Star_Rating extends \WPGurus\Forms\Element
{
	const MAIN_SELECTOR = 'element-star-rating';
	/**
	 * Additional arguments accepted by the plugin.
	 */
	const NUMBER = 'star_rating_number';

	/**
	 * The number of stars to display
	 * @var int
	 */
	private $number;

	function __construct($args){
		parent::__construct($args);

		$args = wp_parse_args(
			$args,
			array(
				self::NUMBER => 5
			)
		);

		$this->add_sanitizer(
			new Typecast(Typecast::TYPE_FLOAT)
		);

		$this->number = $args[self::NUMBER];
	}

	/**
	 * Renders the markup required by the jQuery plugin and enqueues the scripts.
	 */
	function render_field_html(){
		$this->add_html_attributes();

		wp_enqueue_script(Assets::RATY_JS);
		wp_enqueue_style(Assets::RATY_CSS);

		?>
			<div <?php $this->print_attributes(); ?>></div>
		<?php
	}

	/**
	 * Returns the number of stars to display
	 * @return int
	 */
	public function get_number()
	{
		return $this->number;
	}

	/**
	 * Sets the number of stars to display
	 * @param int $number The number of stars to display
	 */
	public function set_number($number)
	{
		$this->number = $number;
	}

	/**
	 * Adds the necessary attributes to the HTML container.
	 */
	private function add_html_attributes()
	{
		$this->add_class(self::MAIN_SELECTOR);
		$this->set_attribute('data-score', $this->get_value());
		$this->set_attribute('data-score-name', $this->get_field_name());
		$this->set_attribute('data-number', $this->number);
		$this->set_attribute('data-path', Asset_Manager::get_libs_url() . 'raty/lib/images/');
		$this->set_attribute('data-hints', 'false');

		$this->remove_attribute('name');
	}
}