<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Assets;

class Asset_Manager extends \WPGurus\Components\Component
{
	function __construct()
	{
		parent::__construct();

		$this->register_action('init', array($this, 'register_assets'));
	}

	function register_assets()
	{
		wp_register_script(
			Assets::JQUERY_FORM_JS,
			plugins_url('assets/libs/jquery-form/jquery.form.js', dirname(dirname(__FILE__))),
			array('jquery'),
			$this->plugin_version
		);

		wp_register_script(
			Assets::JQUERY_VALIDATION_JS,
			plugins_url('assets/libs/jquery-validation/dist/jquery.validate.js', dirname(dirname(__FILE__))),
			array('jquery'),
			$this->plugin_version
		);

		wp_register_style(
			Assets::QTIP_CSS,
			plugins_url('assets/libs/qtip2/jquery.qtip.min.css', dirname(dirname(__FILE__))),
			false,
			$this->plugin_version,
			'all'
		);

		wp_register_script(
			Assets::QTIP_JS,
			plugins_url('assets/libs/qtip2/jquery.qtip.min.js', dirname(dirname(__FILE__))),
			array('jquery'),
			$this->plugin_version
		);

		wp_register_style(
			Assets::FRONTEND_FORM_CSS,
			plugins_url('assets/css/frontend-form.css', dirname(dirname(__FILE__))),
			array(),
			$this->plugin_version,
			'all'
		);

		wp_register_script(
			Assets::FRONTEND_FORM_JS,
			plugins_url('assets/js/frontend-form.js', dirname(dirname(__FILE__))),
			array(
				'jquery',
				Assets::JQUERY_FORM_JS
			),
			$this->plugin_version,
			true
		);

		wp_localize_script(
			Assets::FRONTEND_FORM_JS,
			'frontend_post_form_ajax',
			admin_url('admin-ajax.php')
		);

		wp_localize_script(
			Assets::FRONTEND_FORM_JS,
			'frontend_post_form_error',
			__('An error occurred.', 'frontend-publishing-pro')
		);

		wp_register_style(
			Assets::POST_LIST_STYLE,
			plugins_url('/assets/css/post-list.css', dirname(dirname(__FILE__))),
			false,
			$this->plugin_version,
			'all'
		);

		wp_register_script(
			Assets::POST_LIST_SCRIPT,
			plugins_url('/assets/js/post-list.js', dirname(dirname(__FILE__))),
			false,
			$this->plugin_version,
			'all'
		);

		wp_localize_script(
			Assets::POST_LIST_SCRIPT,
			'post_list_deletion_confirmation',
			__('Are you sure you want to delete this post permanently?', 'frontend-publishing-pro')
		);

		// Resources common to the admin pages.

		wp_register_style(
			Assets::ADMIN_PAGE_CSS,
			plugins_url('assets/css/admin.css', dirname(dirname(__FILE__))),
			false,
			$this->plugin_version,
			'all'
		);

		wp_register_script(
			Assets::ADMIN_PAGE_JS,
			plugins_url('assets/js/admin.js', dirname(dirname(__FILE__))),
			array(
				'jquery',
				'jquery-ui-sortable',
				Assets::CONDITIONIZE_JS
			),
			$this->plugin_version,
			false
		);

		// Conditionize JS

		wp_register_script(
			Assets::CONDITIONIZE_JS,
			plugins_url('assets/js/conditionize.jquery.js', dirname(dirname(__FILE__))),
			array('jquery'),
			$this->plugin_version
		);

		// Form manager resources

		wp_register_style(
			Assets::FORM_MANAGER_CSS,
			plugins_url('assets/css/form-manager.css', dirname(dirname(__FILE__))),
			array(
				Assets::ADMIN_PAGE_CSS,
				Assets::JQUERYUI_CSS,
			),
			$this->plugin_version,
			'all'
		);

		wp_register_script(
			Assets::FORM_MANAGER_JS,
			plugins_url('assets/js/form-manager.js', dirname(dirname(__FILE__))),
			array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-sortable',
				'jquery-ui-draggable',
				'jquery-ui-droppable',
				'jquery-ui-autocomplete',
				'jquery-effects-core',
				'jquery-effects-shake',
				Assets::ADMIN_PAGE_JS,
				Assets::JQUERYUI_TOUCH_PUNCH
			),
			$this->plugin_version
		);

		wp_localize_script(
			Assets::FORM_MANAGER_JS,
			'form_manager_confirmation',
			__('Are you sure?', 'frontend-publishing-pro')
		);

		wp_register_style(
			Assets::RICH_EDITOR_FIX,
			plugins_url('assets/css/rich-editor-fix.css', dirname(dirname(__FILE__))),
			false,
			$this->plugin_version,
			'all'
		);

		// Font Awesome
		wp_register_style(
			Assets::FONT_AWESOME_CSS,
			plugins_url('assets/libs/font-awesome/css/font-awesome.min.css', dirname(dirname(__FILE__))),
			array(),
			$this->plugin_version
		);

		wp_register_script(
			Assets::GLOBAL_ADMIN_JS,
			plugins_url('assets/js/global-admin.js', dirname(dirname(__FILE__))),
			array(),
			$this->plugin_version
		);

		// Quick edit assets

		wp_register_style(
			Assets::QUICK_EDIT_CSS,
			plugins_url('assets/css/quick-edit.css', dirname(dirname(__FILE__))),
			array(),
			$this->plugin_version,
			'all'
		);

		wp_register_script(
			Assets::QUICK_EDIT_JS,
			plugins_url('assets/js/quick-edit.js', dirname(dirname(__FILE__))),
			array('jquery'),
			$this->plugin_version,
			true
		);

		wp_register_script(
			Assets::JQUERYUI_TOUCH_PUNCH,
			plugins_url('assets/libs/jqueryui-touch-punch/jquery.ui.touch-punch.min.js', dirname(dirname(__FILE__))),
			array('jquery'),
			$this->plugin_version
		);

		wp_register_style(
			Assets::CUSTOM_FIELDS_CSS,
			plugins_url('assets/css/custom-fields.css', dirname(dirname(__FILE__))),
			array(),
			$this->plugin_version
		);

		wp_register_style(
			Assets::CUSTOM_FIELDS_META_BOX_CSS,
			plugins_url('assets/css/custom-fields-meta-box.css', dirname(dirname(__FILE__))),
			array(),
			$this->plugin_version
		);

		wp_register_style(
			Assets::JQUERYUI_CSS,
			plugins_url( 'assets/libs/jquery-ui/themes/base/jquery-ui.min.css', dirname( dirname( __FILE__ ) ) ),
			array(),
			$this->plugin_version
		);
	}
}