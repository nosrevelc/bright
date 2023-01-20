<?php

namespace WPGurus\Templating;

use WPGurus\Config\Config_Loader;

if (!defined('WPINC')) die;

/**
 *
 */
abstract class Renderable
{
	abstract public function render();

	public function render_template($template, $args = array())
	{
		$plugin_prefix = Config_Loader::get_config('prefix');
		if ($plugin_prefix) {
			$template = apply_filters($plugin_prefix . '_template', $template, $args);
			$args = apply_filters($plugin_prefix . '_template_args', $args, $template);
		}

		if ($args) {
			extract($args);
		}

		if (file_exists($template)) {
			echo "<!-- ", get_class($this), " -->";
			include $template;
			echo "<!-- /", get_class($this), " -->";
		} else {
			// TODO: Log
		}
	}
}