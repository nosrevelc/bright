<?php
namespace WPGurus\Forms\Components;

if (!defined('WPINC')) die;

use WPGurus\Components\Component;

/**
 * Some elements like the rich text editor use CSS and JS which conflicts with the CSS and JS on the front-end. To avoid such conflicts these elements are rendered inside iframes. This class facilitates this process.
 *
 * Class Element_Sandbox
 * @package WPGurus\Forms\Components
 */
class Element_Sandbox extends Component
{
	const IS_SANDBOX = 'is_element_sandbox';
	const SANDBOXED_ELEMENT = 'sandboxed_element';

	function __construct()
	{
		parent::__construct();

		$this->register_action('wp', array($this, 'sandbox'));
	}

	/**
	 * This method is hooked to the 'wp' hook. If certain flags are set in a request, this method prints the iframe content and exits. Otherwise the page renders normally.
	 */
	function sandbox()
	{
		if (!isset($_GET[ self::IS_SANDBOX ]) || !$_GET[ self::IS_SANDBOX ] || !isset($_GET[ self::SANDBOXED_ELEMENT ])) {
			return;
		}

		/**
		 * @var \WPGurus\Forms\Elements\Sandboxed_Element|\WPGurus\Forms\Element $sandboxed_element
		 */
		$sandboxed_element = unserialize(base64_decode($_GET[ self::SANDBOXED_ELEMENT ]));
		?>
		<html>
			<head></head>
			<body>
				<div data-iframe-height="true">
					<?php
						$sandboxed_element->render_original_element();
					?>
				</div>
				<?php wp_footer(); ?>
			</body>
		</html>
		<?php
		exit();
	}

	/**
	 * Renders a simple iframe element. The source URL of the IFrame is created by adding the necessary flags to the home URL as query variables.
	 *
	 * @param $object \WPGurus\Forms\Element The element that is supposed to be rendered inside the sandbox.
	 * @param string $class string Class for the iFrame element.
	 * @param string $width string Width of the iFrame element.
	 * @param string $height string Height of the iFrame element.
	 */
	public static function render_iframe($object, $class = '', $width = "100%", $height = 'auto')
	{
		$query = array(
			self::IS_SANDBOX        => 1,
			self::SANDBOXED_ELEMENT => base64_encode(serialize($object))
		);

		$query_string = build_query($query);
		$iframe_style = sprintf('width:%s;height:%s;', $width, $height);
		?>
		<iframe class="element-sandbox-iframe <?php echo $class; ?>" style="<?php echo $iframe_style; ?>" src="<?php echo home_url('?' . $query_string); ?>"></iframe>
		<?php
	}
}