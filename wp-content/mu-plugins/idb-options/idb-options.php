<?php

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'IdealBiz Settings',
		'menu_title'	=> 'IdealBiz Settings',
		'menu_slug' 	=> 'idealbizsettings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Social Networks',
		'menu_title'	=> 'Social Networks',
		'parent_slug'	=> 'idealbizsettings',
		'menu_slug'     => 'socialmanagement'
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Contacts',
		'menu_title'	=> 'Contacts',
		'parent_slug'	=> 'idealbizsettings',
		'menu_slug'     => 'contacts'
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'iDealBiz Coin Management',
		'menu_title'	=> 'iDealBiz Coin',
		'parent_slug'	=> 'idealbizsettings',
		'menu_slug'     => 'ibzcoinmanagement'
	));

	
}


