<?php
/**
 * Registers the `base_cpt` custom post type
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\PostType;

/**
 * Registers the `base_cpt` custom post type.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class ServiceMessage extends \WidgiLabs\WP\Plugin\IdealBiz\Service\Request\PostType {

	/**
	 * Register custom post type.
	 *
	 * @since 1.0.0
	 */
	public function register() {

		// Use johnbillion/extended-cpts
		\register_extended_post_type(
			$this->slug, array(
				'menu_icon' => 'dashicons-format-status',
				'supports'  => array( 'title', 'editor' ),
				'admin_cols' => array(
					"{$this->slug}_published" => [
						'title'       => 'Published',
						'post_field'  => 'post_date',
						'date_format' => 'd/m/Y',
						'default'     => 'DESC',
					],
					"{$this->slug}_author"    => [
						'title'    => 'Customer',
						'function' => function () {
							echo esc_html( get_the_author_meta( 'user_email' ) );
						},
					],
				),
			)
		);
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 10, 2 );
		add_filter( "manage_{$this->slug}_posts_columns", array( $this, 'manage_columns' ), 20 );
	}

	/**
	 * Filter the title field placeholder text.
	 *
	 * @since  1.0.0
	 * @param  string   $text Placeholder text. Default 'Enter title here'.
	 * @param  \WP_Post $post Post object.
	 * @return string         Possibly-modified placeholder text.
	 */
	public function enter_title_here( $text, $post ) {

		if ( empty( $post->post_type ) ) {
			return $text;
		}

		if ( $post->post_type !== $this->slug ) {
			return $text;
		}

		return __( 'Enter base cpt name here', 'wlbase' );
	}

	/**
	 * Filter posts columns.
	 *
	 * @since  1.0.0
	 * @param  array $columns An array of column names.
	 * @return array          Possibly-modified array of column names.
	 */
	public function manage_columns( $columns ) {
		unset( $columns['comments'] );
		unset( $columns['date'] );
		return $columns;
	}

	/**
	 * Register custom fields.
	 *
	 * @since 1.0.0
	 */
	public function register_fields() {}
}
