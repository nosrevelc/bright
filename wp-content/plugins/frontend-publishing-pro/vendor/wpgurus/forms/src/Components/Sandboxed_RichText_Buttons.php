<?php
namespace WPGurus\Forms\Components;

if (!defined('WPINC')) die;

use WPGurus\Components\Component;

class Sandboxed_RichText_Buttons extends Component
{
	public function __construct()
	{
		parent::__construct();
		if (isset($_GET[ Element_Sandbox::IS_SANDBOX ]) && $_GET[ Element_Sandbox::IS_SANDBOX ]) {
			$this->register_filter('mce_buttons', array($this, 'mce_buttons'), 999);
			$this->register_filter('mce_buttons_2', array($this, 'mce_buttons_2'), 999);
			$this->register_filter('mce_buttons_3', array($this, 'mce_buttons_3'), 999);
			$this->register_filter('mce_buttons_4', array($this, 'mce_buttons_4'), 999);
		}
	}

	public function mce_buttons()
	{
		return array(
			'bold',
			'italic',
			'strikethrough',
			'bullist',
			'numlist',
			'blockquote',
			'hr',
			'alignleft',
			'aligncenter',
			'alignright',
			'link',
			'unlink',
			'wp_more',
			'spellchecker',
			'fullscreen',
			'wp_adv'
		);
	}

	public function mce_buttons_2()
	{
		return array(
			'formatselect',
			'underline',
			'alignjustify',
			'forecolor',
			'pastetext',
			'removeformat',
			'charmap',
			'outdent',
			'indent',
			'undo',
			'redo',
			'wp_help'
		);
	}

	public function mce_buttons_3()
	{
		return array();
	}

	public function mce_buttons_4()
	{
		return array();
	}
}