<?php
/**
 * Shortcode File
 *
 * Handles the 'sp_faq' shortcode of plugin
 *
 * @package WP FAQ
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * 'sp_faq' shortcode
 * 
 * @package WP FAQ
 * @since 1.0.0
 */
function sp_faq_shortcode( $atts, $content = null ) {
	
	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ( $_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json' ) ) {
		return "[sp_faq]";
	}

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="spfaq-builder-shrt-prev">
					<div class="spfaq-builder-shrt-title"><span>'.esc_html__('FAQ View - Shortcode', 'sp-faq').'</span></div>
					sp_faq
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="spfaq-builder-shrt-prev">
					<div class="spfaq-pro-builder-shrt-title"><span>'.esc_html__('FAQ View - Shortcode', 'sp-faq').'</span></div>
					sp_faq
				</div>';
	}

	extract(shortcode_atts(array(
		"limit"				=> -1,
		"category"			=> '',
		"single_open"		=> 'true',
		"transition_speed"	=> 300,
		'extra_class'		=> '',
		'className'			=> '',
		'align'				=> '',
		'dev_param_1'		=> '',
		'dev_param_2'		=> '',
	), $atts));

	$limit				= ! empty( $limit )				? $limit					: -1;
	$category			= ! empty( $category )			? explode(',',$category)	: '';
	$single_open		= ! empty( $single_open ) 		? $single_open 				: 'true';
	$transition_speed	= ! empty( $transition_speed ) 	? $transition_speed 		: 300;
	$align				= ! empty( $align )				? 'align'.$align			: '';
	$extra_class		= $extra_class .' '. $align .' '. $className;
	$extra_class		= sp_faq_sanitize_html_classes( $extra_class );
	$dev_param_1		= ! empty( $dev_param_1 )		? $dev_param_1				: '';
	$dev_param_2		= ! empty( $dev_param_2 )		? $dev_param_2				: '';

	// Enqueue Public js
	wp_enqueue_script( 'accordionjsfree' );
	wp_enqueue_script( 'spfaq-public-js' );

	// FAQ Configuration
	$faq_conf = compact( 'single_open', 'transition_speed' );

	// Create the Query
	$unique		= wp_faq_get_unique();
	$post_type	= 'sp_faq';
	$orderby	= 'post_date';
	$order		= 'DESC';

	// WP Query Argument
	$args = array ( 
				'post_type'			=> $post_type,
				'orderby'			=> $orderby,
				'order'				=> $order,
				'posts_per_page'	=> $limit,
			);

	// Tax Query Variable
	if( $category != "" ) {
		$args['tax_query'] = array( 
								array( 
									'taxonomy' => 'faq_cat', 
									'field' => 'term_id', 
									'terms' => $category,
								) 
		);
	}

	ob_start();

	$query = new WP_Query( $args );

	//Get post type count
	$post_count	= $query->post_count;
	$i			= 1;

	// Displays Custom post info
	if( $post_count > 0) : ?>
	<div class="faq-accordion <?php echo $extra_class; ?>" id="faq-accordion-<?php echo $unique; ?>" data-accordion-group data-conf="<?php echo htmlspecialchars(json_encode($faq_conf)); ?>">

		<?php while ($query->have_posts()) : $query->the_post(); ?>
			<div data-accordion class="faq-main">
				<div data-control class="faq-title">
					<h4> <?php the_title(); ?></h4>
				</div>
				<div data-content>
					<?php if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
						the_post_thumbnail('thumbnail');
					} ?>
					<div class="faq-content"><?php the_content(); ?></div>
				</div>
			</div>
		<?php
		$i++;
		endwhile; ?>
	</div>
	<?php endif;

	// Reset query to prevent conflicts
	wp_reset_postdata();
	return ob_get_clean();
}

// FAQ Shortcode
add_shortcode("sp_faq", "sp_faq_shortcode");