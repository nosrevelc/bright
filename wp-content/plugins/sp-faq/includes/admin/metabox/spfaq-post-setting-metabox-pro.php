<?php
/**
 * Function Custom meta box for Premium
 * 
 * @package WP FAQ
 * @since 3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="pro-notice"><?php echo sprintf( __( 'Utilize these <a href="%s" target="_blank">Premium Features</a> to get best of this plugin.', 'sp-faq'), SP_FAQ_PLUGIN_LINK_UNLOCK); ?></div>
<table class="form-table spfaq-metabox-table">
	<tbody>

		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('Layouts ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('2 (FAQ, FAQ items with Categories in Grid). In lite version only 1 layout.', 'sp-faq'); ?></span>
			</td>
		</tr>
		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('Designs ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('15+. In lite version only one design.', 'sp-faq'); ?></span>
			</td>
		</tr>
		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('Default Open FAQ ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Open number of FAQ open on page load.', 'sp-faq'); ?></span>
			</td>
		</tr>
		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('WooCommerce Supports ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Use plugin for WooCommerce product FAQ.', 'sp-faq'); ?></span>
			</td>
		</tr>
		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('Read more/ Show Less button ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('You can Show Read more/ Show Less button.', 'sp-faq'); ?></span>
			</td>
		</tr>
		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('WP Templating Features ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('You can modify plugin html/designs in your current theme.', 'sp-faq'); ?></span>
			</td>
		</tr>
		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('Shortcode Generator ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Play with all shortcode parameters with preview panel. No documentation required.' , 'sp-faq'); ?></span>
			</td>
		</tr>
		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('Drag & Drop Slide Order Change ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Arrange your desired slides with your desired order and display.' , 'sp-faq'); ?></span>
			</td>
		</tr>
		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('Page Builder Support ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Gutenberg Block, Elementor, Bevear Builder, SiteOrigin, Divi, Visual Composer and Fusion Page Builder Support', 'sp-faq'); ?></span>
			</td>
		</tr>
		<tr class="spfaq-pro-feature">
			<th>
				<?php _e('Exclude FAQ and Exclude Some Categories ', 'sp-faq'); ?><span class="spfaq-pro-tag"><?php _e('PRO','sp-faq');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Do not display the faq & Do not display the faq for particular categories.' , 'sp-faq'); ?></span>
			</td>
		</tr>
	</tbody>
</table><!-- end .spfaq-metabox-table -->

