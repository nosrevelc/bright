<?php
/**
 * iDealBiz
 *
 * WARNING: This file is part of the iDealBiz theme. DO NOT edit this file
 * under any circumstances, as the changes will be lost in the case of a theme update.
 * Please do all modifications in the form of a child theme.
 *
 * @since   1.0.0
 * @package iDealBiz\Base
 * @author  WidgiLabs
 * @license GPL-2.0+
 * @link    http://widgilabs.com/
 */


/**
 * Theme widgets.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class Widgets {

	/**
	 * Setup hooks.
	 *
	 * @since 1.0.0
	 */
	public function ready() {
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	/**
	 * Register sidebars.
	 *
	 * @since 1.0.0
	 */
	public function register_sidebars() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar', 'idealbiz' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget__title">',
				'after_title'   => '</h3>',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Stripe Sidebar', 'idealbiz' ),
				'id'            => 'sidebar-stripe',
				'description'   => __( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<div class="info-group__block">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="title-intro">',
				'after_title'   => '</div>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar Newsletter', 'idealbiz' ),
				'id'            => 'sidebar-newsletter',
				'description'   => esc_html__( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '',
				'after_title'   => '',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar Seller', 'idealbiz' ),
				'id'            => 'sidebar-seller',
				'description'   => esc_html__( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '',
				'after_title'   => '',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Pricing page - Generic stripe', 'idealbiz' ),
				'id'            => 'sidebar-pricing',
				'description'   => __( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<div class="info-group__block">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="title-intro">',
				'after_title'   => '</div>',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Pricing page - Buyer stripe', 'idealbiz' ),
				'id'            => 'sidebar-pricing-buyer',
				'description'   => __( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<div class="info-group__block">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="title-intro">',
				'after_title'   => '</div>',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Pricing page - Seller stripe', 'idealbiz' ),
				'id'            => 'sidebar-pricing-seller',
				'description'   => __( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<div class="info-group__block">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="title-intro">',
				'after_title'   => '</div>',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Pricing page - Broker stripe', 'idealbiz' ),
				'id'            => 'sidebar-pricing-broker',
				'description'   => __( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<div class="info-group__block">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="title-intro">',
				'after_title'   => '</div>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Service & Franchise - Single Contact', 'idealbiz' ),
				'id'            => 'sidebar-service',
				'description'   => esc_html__( 'Add contact form here', 'idealbiz' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '',
				'after_title'   => '',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Services & Franchises - Single Right', 'idealbiz' ),
				'id'            => 'sidebar-service-right',
				'description'   => __( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '<div class="title-intro">',
				'after_title'   => '</div>',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Edit Broker', 'idealbiz' ),
				'id'            => 'sidebar-editbroker',
				'description'   => __( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '',
				'after_title'   => '',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Service Message', 'idealbiz' ),
				'id'            => 'sidebar-service-message',
				'description'   => __( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '',
				'after_title'   => '',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Service Request Proposal', 'idealbiz' ),
				'id'            => 'sidebar-service-request-proposal',
				'description'   => __( 'Add widgets here.', 'idealbiz' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '',
				'after_title'   => '',
			)
		);
	}


}