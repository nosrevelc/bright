<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * An interface that must be implemented by all the elements that are supposed to be rendered inside a sandbox iFrame.
 *
 * Interface Sandboxed_Element
 * @package WPGurus\Forms\Elements
 */
interface Sandboxed_Element
{
	/**
	 * The render function of a sandboxed element only renders an iframe element. The real HTML of the element is supposed to be rendered by this method.
	 * Basically this method renders the inside of the sandbox iframe.
	 *
	 * @return void
	 */
	public function render_original_element();
}