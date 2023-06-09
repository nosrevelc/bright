<?php
/*
Plugin Name: WP All Import - User Import Add-On
Plugin URI: http://www.wpallimport.com/
Description: Import Users & User's metadata to WordPress. Requires WP All Import.
Version: 1.0.7
Author: Soflyy
*/
/**
 * Plugin root dir with forward slashes as directory separator regardless of actuall DIRECTORY_SEPARATOR value
 * @var string
 */
define('PMUI_ROOT_DIR', str_replace('\\', '/', dirname(__FILE__)));
/**
 * Plugin root url for referencing static content
 * @var string
 */
define('PMUI_ROOT_URL', rtrim(plugin_dir_url(__FILE__), '/'));
/**
 * Plugin prefix for making names unique (be aware that this variable is used in conjuction with naming convention,
 * i.e. in order to change it one must not only modify this constant but also rename all constants, classes and functions which
 * names composed using this prefix)
 * @var string
 */
define('PMUI_PREFIX', 'pmui_');

define('PMUI_VERSION', '1.0.7');

if ( class_exists('PMUI_Plugin') and PMUI_EDITION == "free"){

	function pmui_notice(){
		
		?>
		<div class="error"><p>
			<?php printf(__('Please de-activate and remove the free version of the User add-on before activating the paid version.', 'pmxi_plugin'));
			?>
		</p></div>
		<?php				

		deactivate_plugins(PMUI_ROOT_DIR . '/plugin.php');

	}

	add_action('admin_notices', 'pmui_notice');	

}
else {

	define('PMUI_EDITION', 'paid');

	/**
	 * Main plugin file, Introduces MVC pattern
	 *
	 * @singletone
	 * @author Max Tsiplyakov <makstsiplyakov@gmail.com>
	 */

	final class PMUI_Plugin {
		/**
		 * Singletone instance
		 * @var PMUI_Plugin
		 */
		protected static $instance;

		/**
		 * Plugin options
		 * @var array
		 */
		protected $options = array();

		/**
		 * Plugin root dir
		 * @var string
		 */
		const ROOT_DIR = PMUI_ROOT_DIR;
		/**
		 * Plugin root URL
		 * @var string
		 */
		const ROOT_URL = PMUI_ROOT_URL;
		/**
		 * Prefix used for names of shortcodes, action handlers, filter functions etc.
		 * @var string
		 */
		const PREFIX = PMUI_PREFIX;
		/**
		 * Plugin file path
		 * @var string
		 */
		const FILE = __FILE__;	

		/**
		 * Return singletone instance
		 * @return PMUI_Plugin
		 */
		static public function getInstance() {
			if (self::$instance == NULL) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		static public function getEddName(){
			return 'User Import Add-On';
		}

		/**
		 * Common logic for requestin plugin info fields
		 */
		public function __call($method, $args) {
			if (preg_match('%^get(.+)%i', $method, $mtch)) {
				$info = get_plugin_data(self::FILE);
				if (isset($info[$mtch[1]])) {
					return $info[$mtch[1]];
				}
			}
			throw new Exception("Requested method " . get_class($this) . "::$method doesn't exist.");
		}

		/**
		 * Get path to plagin dir relative to wordpress root
		 * @param bool[optional] $noForwardSlash Whether path should be returned withot forwarding slash
		 * @return string
		 */
		public function getRelativePath($noForwardSlash = false) {
			$wp_root = str_replace('\\', '/', ABSPATH);
			return ($noForwardSlash ? '' : '/') . str_replace($wp_root, '', self::ROOT_DIR);
		}

		/**
		 * Check whether plugin is activated as network one
		 * @return bool
		 */
		public function isNetwork() {
			if ( !is_multisite() )
			return false;

			$plugins = get_site_option('active_sitewide_plugins');
			if (isset($plugins[plugin_basename(self::FILE)]))
				return true;

			return false;
		}

		/**
		 * Check whether permalinks is enabled
		 * @return bool
		 */
		public function isPermalinks() {
			global $wp_rewrite;

			return $wp_rewrite->using_permalinks();
		}

		/**
		 * Return prefix for plugin database tables
		 * @return string
		 */
		public function getTablePrefix() {
			global $wpdb;
			return ($this->isNetwork() ? $wpdb->base_prefix : $wpdb->prefix) . self::PREFIX;
		}

		/**
		 * Return prefix for wordpress database tables
		 * @return string
		 */
		public function getWPPrefix() {
			global $wpdb;
			return ($this->isNetwork() ? $wpdb->base_prefix : $wpdb->prefix);
		}

		/**
		 * Class constructor containing dispatching logic
		 * @param string $rootDir Plugin root dir
		 * @param string $pluginFilePath Plugin main file
		 */
		protected function __construct() {

			// create/update required database tables

			// regirster autoloading method
			if (function_exists('__autoload') and ! in_array('__autoload', spl_autoload_functions())) { // make sure old way of autoloading classes is not broken
				spl_autoload_register('__autoload');
			}
			spl_autoload_register(array($this, '__autoload'));		

			// register helpers
			if (is_dir(self::ROOT_DIR . '/helpers')) foreach (PMUI_Helper::safe_glob(self::ROOT_DIR . '/helpers/*.php', PMUI_Helper::GLOB_RECURSE | PMUI_Helper::GLOB_PATH) as $filePath) {
				require_once $filePath;
			}

			// init plugin options
			$option_name = get_class($this) . '_Options';
			$options_default = PMUI_Config::createFromFile(self::ROOT_DIR . '/config/options.php')->toArray();
			$this->options = array_intersect_key(get_option($option_name, array()), $options_default) + $options_default;
			$this->options = array_intersect_key($options_default, array_flip(array('info_api_url'))) + $this->options; // make sure hidden options apply upon plugin reactivation		

			update_option($option_name, $this->options);
			$this->options = get_option(get_class($this) . '_Options');

			register_activation_hook(self::FILE, array($this, '__activation'));

			// register action handlers
			if (is_dir(self::ROOT_DIR . '/actions')) if (is_dir(self::ROOT_DIR . '/actions')) foreach (PMUI_Helper::safe_glob(self::ROOT_DIR . '/actions/*.php', PMUI_Helper::GLOB_RECURSE | PMUI_Helper::GLOB_PATH) as $filePath) {
				require_once $filePath;
				$function = $actionName = basename($filePath, '.php');
				if (preg_match('%^(.+?)[_-](\d+)$%', $actionName, $m)) {
					$actionName = $m[1];
					$priority = intval($m[2]);
				} else {
					$priority = 10;
				}
				add_action($actionName, self::PREFIX . str_replace('-', '_', $function), $priority, 99); // since we don't know at this point how many parameters each plugin expects, we make sure they will be provided with all of them (it's unlikely any developer will specify more than 99 parameters in a function)
			}

			// register filter handlers
			if (is_dir(self::ROOT_DIR . '/filters')) foreach (PMUI_Helper::safe_glob(self::ROOT_DIR . '/filters/*.php', PMUI_Helper::GLOB_RECURSE | PMUI_Helper::GLOB_PATH) as $filePath) {
				require_once $filePath;
				$function = $actionName = basename($filePath, '.php');
				if (preg_match('%^(.+?)[_-](\d+)$%', $actionName, $m)) {
					$actionName = $m[1];
					$priority = intval($m[2]);
				} else {
					$priority = 10;
				}
				add_filter($actionName, self::PREFIX . str_replace('-', '_', $function), $priority, 99); // since we don't know at this point how many parameters each plugin expects, we make sure they will be provided with all of them (it's unlikely any developer will specify more than 99 parameters in a function)
			}

			// register shortcodes handlers
			if (is_dir(self::ROOT_DIR . '/shortcodes')) foreach (PMUI_Helper::safe_glob(self::ROOT_DIR . '/shortcodes/*.php', PMUI_Helper::GLOB_RECURSE | PMUI_Helper::GLOB_PATH) as $filePath) {
				$tag = strtolower(str_replace('/', '_', preg_replace('%^' . preg_quote(self::ROOT_DIR . '/shortcodes/', '%') . '|\.php$%', '', $filePath)));
				add_shortcode($tag, array($this, 'shortcodeDispatcher'));
			}

			// register admin page pre-dispatcher
			add_action('admin_init', array($this, '__adminInit'));				

		}		

		/**
		 * pre-dispatching logic for admin page controllers
		 */
		public function __adminInit() {
			$input = new PMUI_Input();
			$page = strtolower($input->getpost('page', ''));
			if (preg_match('%^' . preg_quote(str_replace('_', '-', self::PREFIX), '%') . '([\w-]+)$%', $page)) {
				$this->adminDispatcher($page, strtolower($input->getpost('action', 'index')));
			}
		}

		/**
		 * Dispatch shorttag: create corresponding controller instance and call its index method
		 * @param array $args Shortcode tag attributes
		 * @param string $content Shortcode tag content
		 * @param string $tag Shortcode tag name which is being dispatched
		 * @return string
		 */
		public function shortcodeDispatcher($args, $content, $tag) {

			$controllerName = self::PREFIX . preg_replace('%(^|_).%e', 'strtoupper("$0")', $tag); // capitalize first letters of class name parts and add prefix
			$controller = new $controllerName();
			if ( ! $controller instanceof PMUI_Controller) {
				throw new Exception("Shortcode `$tag` matches to a wrong controller type.");
			}
			ob_start();
			$controller->index($args, $content);
			return ob_get_clean();
		}

		/**
		 * Dispatch admin page: call corresponding controller based on get parameter `page`
		 * The method is called twice: 1st time as handler `parse_header` action and then as admin menu item handler
		 * @param string[optional] $page When $page set to empty string ealier buffered content is outputted, otherwise controller is called based on $page value
		 */
		public function adminDispatcher($page = '', $action = 'index') {
			static $buffer = NULL;
			static $buffer_callback = NULL;
			if ('' === $page) {
				if ( ! is_null($buffer)) {
					echo '<div class="wrap">';
					echo $buffer;
					do_action('PMUI_action_after');
					echo '</div>';
				} elseif ( ! is_null($buffer_callback)) {
					echo '<div class="wrap">';
					call_user_func($buffer_callback);
					do_action('PMUI_action_after');
					echo '</div>';
				} else {
					throw new Exception('There is no previousely buffered content to display.');
				}
			} else {
				$controllerName =  preg_replace('%(^' . preg_quote(self::PREFIX, '%') . '|_).%e', 'strtoupper("$0")', str_replace('-', '_', $page)); // capitalize prefix and first letters of class name parts
				$actionName = str_replace('-', '_', $action);
				if (method_exists($controllerName, $actionName)) {

					if ( ! get_current_user_id() or ! current_user_can('manage_options')) {
					    // This nonce is not valid.
					    die( 'Security check' ); 

					} else {

						$this->_admin_current_screen = (object)array(
							'id' => $controllerName,
							'base' => $controllerName,
							'action' => $actionName,
							'is_ajax' => isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest',
							'is_network' => is_network_admin(),
							'is_user' => is_user_admin(),
						);
						add_filter('current_screen', array($this, 'getAdminCurrentScreen'));
						add_filter('admin_body_class', create_function('', 'return "' . PMUI_Plugin::PREFIX . 'plugin";'));

						$controller = new $controllerName();
						if ( ! $controller instanceof PMUI_Controller_Admin) {
							throw new Exception("Administration page `$page` matches to a wrong controller type.");
						}

						if ($this->_admin_current_screen->is_ajax) { // ajax request
							$controller->$action();
							do_action('PMUI_action_after');
							die(); // stop processing since we want to output only what controller is randered, nothing in addition
						} elseif ( ! $controller->isInline) {
							ob_start();
							$controller->$action();
							$buffer = ob_get_clean();
						} else {
							$buffer_callback = array($controller, $action);
						}
					}
					
				} else { // redirect to dashboard if requested page and/or action don't exist
					wp_redirect(admin_url()); die();
				}
			}
		}

		protected $_admin_current_screen = NULL;
		public function getAdminCurrentScreen()
		{
			return $this->_admin_current_screen;
		}

		/**
		 * Autoloader
		 * It's assumed class name consists of prefix folloed by its name which in turn corresponds to location of source file
		 * if `_` symbols replaced by directory path separator. File name consists of prefix folloed by last part in class name (i.e.
		 * symbols after last `_` in class name)
		 * When class has prefix it's source is looked in `models`, `controllers`, `shortcodes` folders, otherwise it looked in `core` or `library` folder
		 *
		 * @param string $className
		 * @return bool
		 */
		public function __autoload($className) {
			$is_prefix = false;
			$filePath = str_replace('_', '/', preg_replace('%^' . preg_quote(self::PREFIX, '%') . '%', '', strtolower($className), 1, $is_prefix)) . '.php';
			if ( ! $is_prefix) { // also check file with original letter case
				$filePathAlt = $className . '.php';
			}
			foreach ($is_prefix ? array('models', 'controllers', 'shortcodes', 'classes') : array() as $subdir) {
				$path = self::ROOT_DIR . '/' . $subdir . '/' . $filePath;
				if (is_file($path)) {
					require $path;
					return TRUE;
				}
				if ( ! $is_prefix) {
					$pathAlt = self::ROOT_DIR . '/' . $subdir . '/' . $filePathAlt;
					if (is_file($pathAlt)) {
						require $pathAlt;
						return TRUE;
					}
				}
			}

			return FALSE;
		}

		/**
		 * Get plugin option
		 * @param string[optional] $option Parameter to return, all array of options is returned if not set
		 * @return mixed
		 */
		public function getOption($option = NULL) {
			if (is_null($option)) {
				return $this->options;
			} else if (isset($this->options[$option])) {
				return $this->options[$option];
			} else {
				throw new Exception("Specified option is not defined for the plugin");
			}
		}
		/**
		 * Update plugin option value
		 * @param string $option Parameter name or array of name => value pairs
		 * @param mixed[optional] $value New value for the option, if not set than 1st parameter is supposed to be array of name => value pairs
		 * @return array
		 */
		public function updateOption($option, $value = NULL) {
			is_null($value) or $option = array($option => $value);
			if (array_diff_key($option, $this->options)) {
				throw new Exception("Specified option is not defined for the plugin");
			}
			$this->options = $option + $this->options;
			update_option(get_class($this) . '_Options', $this->options);

			return $this->options;
		}

		/**
		 * Plugin activation logic
		 */
		public function __activation() {		

			// uncaught exception doesn't prevent plugin from being activated, therefore replace it with fatal error so it does
			set_exception_handler(create_function('$e', 'trigger_error($e->getMessage(), E_USER_ERROR);'));

			// create plugin options
			$option_name = get_class($this) . '_Options';
			$options_default = PMUI_Config::createFromFile(self::ROOT_DIR . '/config/options.php')->toArray();
			update_option($option_name, $options_default);
			
		}

		/**
		 * Method returns default import options, main utility of the method is to avoid warnings when new
		 * option is introduced but already registered imports don't have it
		 */
		public static function get_default_import_options() {
			return array(
				'pmui' => array(
					'import_users' => 0,
					'first_name' => '',
					'last_name' => '',
					'role' => '',
					'nickname' => '',
					'description' => '',
					'login' => '',
					'pass' => '',
					'nicename' => '',
					'email' => '',
					'registered' => '',
					'display_name' => '',
					'url' => ''									
				),
				'is_update_first_name' => 1,
				'is_update_last_name' => 1,
				'is_update_role' => 1,
				'is_update_nickname' => 1,
				'is_update_description' => 1,
				'is_update_login' => 1,
				'is_update_password' => 1,
				'is_update_nicename' => 1,
				'is_update_email' => 1,
				'is_update_registered' => 1,
				'is_update_display_name' => 1,
				'is_update_url' => 1,
				'do_not_send_password_notification' => 1				
			);
		}
	}

	PMUI_Plugin::getInstance();

	// retrieve our license key from the DB
	$wpai_user_addon_options = get_option('PMXI_Plugin_Options');
	
	if (!empty($wpai_user_addon_options['info_api_url'])){
		// setup the updater
		$updater = new PMUI_Updater( $wpai_user_addon_options['info_api_url'], __FILE__, array( 
				'version' 	=> PMUI_VERSION,		// current version number
				'license' 	=> false, // license key (used get_option above to retrieve from DB)
				'item_name' => PMUI_Plugin::getEddName(), 	// name of this plugin
				'author' 	=> 'Soflyy'  // author of this plugin
			)
		);
	}
		
}

