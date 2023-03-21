<?php
/**
 * Email template class.
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Email;


class EmailTemplate {

	public static function get_email_body( $body_content ) {


		$body = ' 
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f7f7f7;">
<tbody>  
<tr>
<td align="center" valign="top">
<p style="padding-top: 40px;"></p>
</td>
</tr>

<tr>
<td align="center" valign="top">


<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>  
<tr>
<td align="center" valign="top">
<div id="template_header_image">
<p style="margin-top: 0;" class="teste"><img style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;" src="%LOGO_IDEALBIZ%" alt="iDealBiz" />«flag»</p>

</div>
<table id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #ffffff; border: 1px solid #dedede; border-radius: 3px !important;" border="0" width="600" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td align="center" valign="top"><!-- Header -->
<table id="template_header" style="background-color: #F7984F; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" border="0" width="600" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td id="header_wrapper" style="padding: 36px 48px; display: block;">
<h1 style="color: #ffffff; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: center; text-shadow: 0 1px 0 #353535; -webkit-font-smoothing: antialiased;">
%%HEAD_MESSAGE%%
</h1>
</td>
</tr>
</tbody>
</table>
<!-- End Header --></td>
</tr>
<tr>
<td align="center" valign="top"><!-- Body -->
<table id="template_body" border="0" width="600" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td id="body_content" style="background-color: #ffffff;" valign="top"><!-- Content -->
<table border="0" width="100%" cellspacing="0" cellpadding="20">
<tbody>
<tr>
<td style="padding: 48px;" valign="top">
<div id="body_content_inner" style="color: #636363; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left; white-space:pre-wrap;">%%USER_COMPLIMENT%%%%BODY_CONTENT%%</div></td></tr>
</tbody>
</table>
<!-- End Content --></td>
</tr>
</tbody>
</table>
<!-- End Body --></td>
</tr>
<tr>
<td align="center" valign="top"><!-- Footer -->
<table id="template_footer" border="0" width="600" cellspacing="0" cellpadding="10">
<tbody>
<tr>
<td style="padding: 0; -webkit-border-radius: 6px;" valign="top">

</td>
</tr>
</tbody>
</table>
<!-- End Footer --></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table> 
</td>
</tr>


<tr>
<td align="center" valign="top" style="text-align:center;">
<p style="padding-top: 2px;"></p>
'.get_social_links().' 
<p style="padding-top: 0px;"></p>
<a style="font-size:10px;" href="'.get_site_url().'">'.pll__('iDealBiz - A marketplace for businesses').'</a>
</td>
</tr>

<tr>
<td align="center" valign="top">
<p style="padding-top: 40px;"></p>
</td>
</tr>
 
</tbody>
</table> 

';
		//'.pll__('iDealBiz - A marketplace for businesses').'
		$idealbiz_logo   = get_option( 'woocommerce_email_header_image' );
		$site_title      = get_bloginfo( 'name' );
		$site_url        = get_home_url();
		$footer_logo_uri = get_option( 'woocommerce_email_header_image' );
		$social_links    = '';
		$asset_uri       = get_stylesheet_directory_uri() . '/assets/img/email/';

/*
		$footer = sprintf(
			'<footer style="background-color: #fff;margin-top: 70px;padding: 25px 30px 30px;">
				<a href="%1$s" style="color: #14307b;text-decoration: none;font-size: 20px;">
					<img src="%2$s" alt="%3$s" class="brand" style="width: 130px;">
				</a>
				<span class="rights" style="color: #84898F;display: inline-block;margin-left: 30px;vertical-align: middle;margin-bottom: 30px;">%4$s</span>
				<ul class="social" style="list-style: none;padding: 0;margin: 0 0 0 -20px;">
					%5$s
				</ul>
			</footer>',
			esc_url( $site_url ),
			esc_url( $footer_logo_uri ),
			esc_attr__( 'IdealBiz Logo', 'idealbiz' ),
			esc_html( date( 'Y' ) )
		);
		*/

		$body = str_replace( '%%FOOTER%%', $footer, $body );
		$body = str_replace( '%SITE_TITLE%', $site_title, $body );
		$body = str_replace( '%LOGO_IDEALBIZ%', $idealbiz_logo, $body );
		$body = str_replace( '%%BODY_CONTENT%%', $body_content, $body );

		$body = str_replace( '%%FOOTER%%', $footer, $body );

		$body = str_replace( '<p>', '<p style="margin-bottom:0; margin-top:0;">', $body );

		return $body;
	}
}
