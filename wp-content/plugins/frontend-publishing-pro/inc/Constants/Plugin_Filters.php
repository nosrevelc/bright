<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Plugin_Filters
{
	const BEFORE_POST_INSERTION = 'wpfepp_before_post_insertion';
	const FRONTEND_TAB_QUERY_ARGS = 'wpfepp_frontend_tab_query_args';
	const FRONTEND_FORM_SUCCESS_LINKS = 'wpfepp_frontend_form_success_links';
}