<?php

namespace ACA\ACF\Editing;

use ACA\ACF\API;
use ACA\ACF\Editing;
use acf_field_post_object;
use ACP\Editing\PaginatedOptions;
use ACP\Helper;

class PostObject extends Editing
	implements PaginatedOptions {

	public function get_edit_value( $post_id ) {
		$values = array();
		$ids = $this->column->get_raw_value( $post_id );

		if ( ! $ids ) {
			return $values;
		}

		// ACF Free
		if ( API::is_free() ) {
			foreach ( $ids as $id ) {
				$values[ $id ] = html_entity_decode( get_the_title( $id ) );
			}

			return $values;
		}

		// ACF Pro
		$acf_field = new acf_field_post_object;

		foreach ( $ids as $id ) {
			$values[ $id ] = html_entity_decode( $acf_field->get_post_title( $id, $this->column->get_acf_field(), $post_id ) );
		}

		return $values;
	}

	/**
	 * @return array
	 */
	public function get_view_settings() {
		$data = parent::get_view_settings();

		$data['type'] = 'acf_select2';
		$data['ajax_populate'] = true;
		$data['store_values'] = false;

		if ( $this->column->get_field()->get( 'multiple' ) ) {
			$data['multiple'] = true;
		} else if ( $this->column->get_field()->get( 'allow_null' ) ) {
			$data['clear_button'] = true;
		}

		return $data;
	}

	public function get_paginated_options( $s, $paged, $id = null ) {
		$entities = new Helper\Select\Entities\Post( array(
			's'         => $s,
			'paged'     => $paged,
			'post_type' => $this->get_post_type(),
			'tax_query' => $this->get_tax_query(),
		) );

		return new Helper\Select\Options\Paginated(
			$entities,
			new Helper\Select\Group\PostType(
				new Helper\Select\Formatter\PostTitle( $entities )
			)
		);
	}

	/**
	 * @return array|string
	 */
	protected function get_post_type() {
		$post_type = $this->column->get_field()->get( 'post_type' );

		if ( ! $post_type || in_array( 'all', $post_type ) || in_array( 'any', $post_type ) ) {
			$post_type = 'any';
		}

		return $post_type;
	}

	/**
	 * @return array|string
	 */
	protected function get_tax_query() {
		$terms = acf_decode_taxonomy_terms( $this->column->get_field()->get( 'taxonomy' ) );

		if ( ! $terms ) {
			return array();
		}

		$tax_query = array();

		foreach ( $terms as $k => $v ) {
			$tax_query[] = array(
				'taxonomy' => $k,
				'field'    => 'slug',
				'terms'    => $v,
			);
		}

		return $tax_query;
	}

	public function save( $id, $value ) {

		switch ( $value['save_strategy'] ) {
			case 'add':
				return parent::save( $id, $this->extend_value( $id, $value['values'] ) );

			case 'remove':
				return parent::save( $id, $this->reduce_value( $id, $value['values'] ) );

			default:
				return parent::save( $id, $value['values'] );
				
		}
	}

	private function extend_value( $id, $values ) {
		$old_values = array_keys( $this->get_edit_value( $id ) );
		$new_values = array_merge( $old_values, $values );

		return array_unique( $new_values );
	}

	private function reduce_value( $id, $remove_values ) {
		$values = array_keys( $this->get_edit_value( $id ) );

		foreach ( $values as $key => $value ) {
			if ( in_array( $value, $remove_values ) ) {
				unset( $values[ $key ] );
			}
		}

		return $values;
	}

}