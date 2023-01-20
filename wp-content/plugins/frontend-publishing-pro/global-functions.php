<?php

namespace WPFEPP;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Post_Fields;
use WPFEPP\DB_Tables\Forms;

function is_field_supported($field_type, $post_type)
{
	switch ($field_type) {
		case Post_Fields::FIELD_TITLE:
			return post_type_supports($post_type, 'title');

		case Post_Fields::FIELD_CONTENT:
			return post_type_supports($post_type, 'editor');

		case Post_Fields::FIELD_EXCERPT:
			return post_type_supports($post_type, 'excerpt');

		case Post_Fields::FIELD_THUMBNAIL:
			return (post_type_supports($post_type, 'thumbnail') && get_theme_support('post-thumbnails'));

		case Post_Fields::FIELD_POST_FORMAT:
			$formats = get_theme_support('post-formats');
			return (post_type_supports($post_type, 'post-formats') && is_array($formats) && count($formats) && is_array($formats[0]) && count($formats[0]));

		default:
			return true;
	}
}

function login_redirect()
{
	global $post;
	$permalink = $post ? get_permalink($post) : '';
	wp_redirect(
		wp_login_url($permalink)
	);
	die();
}

function current_user_can_edit($post)
{
	return $post->post_author == get_current_user_id() || \current_user_can('edit_post', $post->ID);
}

function get_post_types()
{
	$types = \get_post_types(array('show_ui' => true), 'names', 'and');
	unset($types['attachment']);
	return $types;
}

function get_roles()
{
	global $wp_roles;

	if (is_null($wp_roles))
		return array();

	$roles = $wp_roles->get_names();
	return $roles;
}

function current_user_can($roles)
{
	$current_user_can = false;
	$current_user = get_user_by('id', get_current_user_id());
	if (!is_a($current_user, '\WP_User')) {
		return $current_user_can;
	}

	$current_user_roles = $current_user->roles;
	foreach ($current_user_roles as $role) {
		if (in_array($role, $roles)) {
			$current_user_can = true;
			break;
		}
	}
	return $current_user_can;
}

/**
 * Takes a post ID and returns a boolean indicating whether the current user can delete the post or not.
 * @param $post_id int The post ID.
 * @return bool Boolean indicating whether the current user can delete the post.
 */
function current_user_can_delete($post_id)
{
	$post_author_id = get_post_field('post_author', $post_id);
	$current_user = wp_get_current_user();

	return ($post_author_id == $current_user->ID || \current_user_can('delete_post', $post_id));
}

/**
 * The WordPress function get_query_var returns everything after the rewrite endpoint. This function breaks the value down into parts and returns the required one.
 * @param $var string The query variable name.
 * @param $index int Integer indicating which part of the value to fetch. Parts are separated by '/'
 * @param $default mixed What to return if the query part doesn't contain anything.
 * @return string The required value.
 */
function get_query_var_part($var, $index, $default = '')
{
	$query_var = get_query_var( $var, false );
	$query_var_parts = explode( '/', $query_var );
	return ! empty( $query_var_parts[ $index ] ) ? $query_var_parts[ $index ] : $default;
}

function get_forms_for_post_type($post_type, $initial_choices = null)
{
	if (!$initial_choices) {
		$initial_choices = array(
			'' => sprintf('— %s —', __('No Form', 'frontend-publishing-pro'))
		);
	}

	$forms_table = new Forms();
	$forms = $forms_table->get_by_post_type($post_type);
	$choices = $initial_choices;
	foreach ($forms as $form) {
		$choices[ $form[ Forms::COLUMN_ID ] ] = $form[ Forms::COLUMN_NAME ];
	}
	return $choices;
}