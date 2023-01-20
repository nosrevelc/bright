<?php
namespace WPFEPP;

if (!defined('WPINC')) die;

abstract class Ajax extends \WPGurus\Components\Component
{
	const ACTION_PREFIX = 'wp_ajax_';
	const NOPRIV_ACTION_PREFIX = 'wp_ajax_nopriv_';
}