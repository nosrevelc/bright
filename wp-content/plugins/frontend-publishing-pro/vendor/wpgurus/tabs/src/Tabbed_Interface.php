<?php

namespace WPGurus\Tabs;

use WPGurus\Utils\Array_Utils;

if (!defined('WPINC')) die;

/**
 * Contains a collection of tabs and uses them to render a tabbed interface.
 */
class Tabbed_Interface extends \WPGurus\Templating\Renderable
{
	const WRAPPER_CLASS = 'wrapper_class';
	const NAV_WRAPPER_CLASS = 'nav_wrapper_class';
	const NAV_CLASS = 'nav_class';
	const ACTIVE_CLASS = 'active_class';

	/**
	 * @var Tab[]
	 */
	private $tabs = array();

	/**
	 * @var string the CSS class of the whole container.
	 */
	private $wrapper_class;

	/**
	 * @var string The CSS class of the navigational container.
	 */
	private $nav_wrapper_class;

	/**
	 * @var string The class applied to every navigational item.
	 */
	private $nav_class;

	/**
	 * @var string The class applied to the active navigational item.
	 */
	private $active_class;

	function __construct($args)
	{
		$args = wp_parse_args(
			$args,
			array(
				self::WRAPPER_CLASS     => '',
				self::NAV_WRAPPER_CLASS => '',
				self::NAV_CLASS         => '',
				self::ACTIVE_CLASS      => ''
			)
		);

		$this->wrapper_class = $args[ self::WRAPPER_CLASS ];
		$this->nav_wrapper_class = $args[ self::NAV_WRAPPER_CLASS ];
		$this->nav_class = $args[ self::NAV_CLASS ];
		$this->active_class = $args[ self::ACTIVE_CLASS ];
	}

	/**
	 * Adds a tab to the collection.
	 *
	 * @param $tab Tab
	 */
	function add_tab($tab)
	{
		if (is_a($tab, '\WPGurus\Tabs\Tab')) {
			$this->tabs[] = $tab;
		}
	}

	/**
	 * Returns tabs.
	 * @return Tab[]
	 */
	public function get_tabs()
	{
		return $this->tabs;
	}

	/**
	 * Returns an ordered array of tabs.
	 * @return Tab[]
	 */
	public function get_ordered_tabs()
	{
		$tabs = $this->tabs;
		Array_Utils::sort_objects($tabs, 'order');
		return $tabs;
	}

	/**
	 * Traverses the tab objects and renders a tabbed interface.
	 */
	function render()
	{
		$is_first = true;
		$active_tab = null;

		?>
		<div class="<?php echo $this->wrapper_class; ?>">
			<h2 class="<?php echo $this->nav_wrapper_class; ?>">
				<?php foreach ($this->get_ordered_tabs() as $tab): ?>
					<?php
					if ($tab->is_active($is_first)) {
						$active_tab = $tab;
						$a_class = $this->nav_class . ' ' . $this->active_class;
					} else {
						$a_class = $this->nav_class;
					}
					$is_first = false;
					?>
					<a href="<?php echo $tab->make_url(); ?>" class="<?php echo $a_class; ?>">
						<?php echo $tab->get_label(); ?>
					</a>
				<?php endforeach; ?>
			</h2>

			<div>
				<?php
				if ($active_tab != null) {
					echo $active_tab->render();
				}
				?>
			</div>
		</div>
		<?php
	}
}