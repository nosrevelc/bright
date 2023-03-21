<?php

function get_email_header($user_id = NULL, $title = NULL){
	$asset_uri = get_stylesheet_directory_uri() . '/assets/img/email';
	
	if(!$title){ 
		$title=get_bloginfo( 'name' );
	}

	$url=get_bloginfo( 'url' );

	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
	

	$site_logo='
	<img src="'.$image[0].'" alt="logo" style="width: 301px; height: 121px; border: none;
    display: inline-block;
    font-size: 14px;
    font-weight: bold;
    height: auto;
    outline: none;
    text-decoration: none;
    text-transform: capitalize;
    max-width: 100%;
    margin-left: 0;
    margin-right: 0;
    vertical-align: bottom;" />
	';

	$country_iso = get_option('country_market');
    $country_flag= DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.strtolower($country_iso).'.png';
    $flag = '<img style="width:40px; height:27px;vertical-align: super;" width="40" height="27" src="'.$country_flag.'" />';

	$site_menu='';
	$menuLocations = get_nav_menu_locations(); // Get our nav locations 
	$menuID = $menuLocations['header-menu']; // Get the menu ID
	$primaryNav = wp_get_nav_menu_items($menuID); 
	$x=0;
	foreach ( $primaryNav as $navItem ) {
		if ( $navItem->menu_item_parent == 0 ){
			//$site_menu.= '<a style="text-decoration: none;font-size:12px; color:#F58026; '.($x>=1 ? 'font-weight: 400;' : 'font-weight: 700;') .'" href="'.$navItem->url.'" target="_blank" title="'.$navItem->title.'">'.$navItem->title.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
			$x++;
		}
	}

	$username = '<a style="font-size:12px; color:#F58026; text-decoration: none;" href="'.$url.'">'.__('Login / Register','idealbiz').'</a>';
	if($user_id == 'customer_care'){

	}else{
		$avatar =  '<img src="'.get_avatar_url($user_id, array("size"=>30)).'" style="border-radius: 50%; width: 50px height:50px;"/>';
	}
	
	
	if($user_id == 'customer_care'){
		$username = '<a style="text-decoration: none;font-size:12px; color:#F58026;" href="'.$url.'">'.__('Hi-1! Customer Care','idealbiz').'</a>';
	}elseif($user_id){
		$user_info = get_userdata($user_id);
		$username = '<a style="text-decoration: none;font-size:12px; color:#F58026;" href="'.$url.'">'.__('Hi-2!','idealbiz').' '.$user_info->first_name.'</a>';
		$avatar =  '<img src="'.get_avatar_url($user_id, array("size"=>30)).'" style="border-radius: 50%; width: 50px height:50px;"/>';
	}
	


	$head='';
	$head.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
				<head>
					<!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
					<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
					<meta content="width=device-width" name="viewport" />
					<!--[if !mso]><!-->
					<meta content="IE=edge" http-equiv="X-UA-Compatible" />
					<!--<![endif]-->
					<title>'.$title.'</title>
					<!--[if !mso]><!-->
					<!--<![endif]-->
					<style type="text/css">
						body {
							margin: 0;
							padding: 0;
							color:#636363;
						}
						
						table,
						td,
						tr {
							vertical-align: top;
							border-collapse: collapse;
						}
						
						* {
							line-height: inherit;
						}
						
						a[x-apple-data-detectors=true] {
							color: inherit !important;
							text-decoration: none !important;
						}
					</style>
					<style id="media-query" type="text/css">
						@media (max-width: 620px) {
							.col-p-0{
								padding: 0 !important;
							}
							.block-grid,
							.col {
								min-width: 320px !important;
								max-width: 100% !important;
								display: block !important;
							}
							.block-grid {
								width: 100% !important;
							}
							.col {
								width: 100% !important;
							}
							.col>div {
								margin: 0 auto;
							}
							img.fullwidth,
							img.fullwidthOnMobile {
								max-width: 100% !important;
							}
							.no-stack .col {
								min-width: 0 !important;
								display: table-cell !important;
							}
							.no-stack.two-up .col {
								width: 50% !important;
							}
							.no-stack .col.num4 {
								width: 33% !important;
							}
							.no-stack .col.num8 {
								width: 66% !important;
							}
							.no-stack .col.num4 {
								width: 33% !important;
							}
							.no-stack .col.num3 {
								width: 25% !important;
							}
							.no-stack .col.num6 {
								width: 50% !important;
							}
							.no-stack .col.num9 {
								width: 75% !important;
							}
							.video-block {
								max-width: none !important;
							}
							.mobile_hide {
								min-height: 0px;
								max-height: 0px;
								max-width: 0px;
								display: none;
								overflow: hidden;
								font-size: 0px;
							}
							.desktop_hide {
								display: block !important;
								max-height: none !important;
							}
						}
					</style>
				</head>

				<body class="clean-body" style="font-family:\'Raleway\', \'Arial\', sans-serif; margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f7f7f7;">
					<br/><br/><!--[if IE]><div class="ie-browser"><![endif]-->
					<table bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="font-family:\'Raleway\', \'Arial\', sans-serif; table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f7f7; width: 100%;" valign="top" width="100%">
						<tbody>
							<tr style="vertical-align: top;" valign="top">
								<td style="word-break: break-word; vertical-align: top;" valign="top">
									<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#f7f7f7"><![endif]-->
									<div style="background-position:top center;background-repeat:no-repeat;background-color:transparent;">
										<div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
											<div style="border-collapse: collapse;display: table;width: 100%;background-color:#f7f7f7;">
												<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-position:top center;background-repeat:no-repeat;background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px"><tr class="layout-full-width" style="background-color:#f7f7f7"><![endif]-->
												<!--[if (mso)|(IE)]><td align="center" width="600" style="background-color:#f7f7f7;width:600px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:60px; padding-bottom:60px;"><![endif]-->
												<div class="col num12" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top; width: 600px;">
													<div style="width:100% !important;">
														<!--[if (!mso)&(!IE)]><!-->
														<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:40px; padding-bottom:60px; padding-right: 0px; padding-left: 0px;">
															<!--<![endif]-->
															<div align="center" class="img-container center autowidth" style="padding-right: 0px;padding-left: 0px;">
																<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]-->
																<div style="font-size:1px;line-height:35px"> </div>
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tr valign="middle">
																		<td valign="middle" align="center" style="text-align:center; padding-right: 0px; padding-left: 30px; padding-top: 0px; padding-bottom: 0px; vertical-align: middle;" >
																		'.$site_logo. $flag .'
																		</td>
																	</tr>
																</table>
																	<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
																		<tbody>
																			<tr style="vertical-align: top;" valign="top">
																				<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 15px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
																					<table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" valign="top" width="100%">
																						<tbody>
																							<tr style="vertical-align: top;" valign="top">
																								<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
																							</tr>
																						</tbody>
																					</table>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																													
																	<!--[if mso]></td></tr></table><![endif]-->
															</div>';

		return $head;
}


function get_social_links(){
	$asset_uri = 'https://idealbiz.io/wp-content/themes/idealbiz/assets/img/email/';
	$social_links = '
	<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation"
	text-align: center; table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
		<tr><td style="text-align:center;" align="center">
				<table align="center" border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="text-align: center; table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top">
					<tr style="vertical-align: top;" valign="top" align="center">';
    foreach ( get_social_networks() as $network => $social_network ) {
        if ( empty( $social_network['url'] ) ) {
            continue;
        }
        $social_links .= sprintf(
            '<td style="width: 20px; word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
                <a href="%1$s" class="social__link" style="color: #F58026;text-decoration: none;font-size: 20px;padding: 8px 10px;">
                    <img src="%2$s" alt="%3$s">
                </a>
            </td>',
            esc_url( $social_network['url'] ),
            esc_url( sprintf( '%1$s/%2$s.png', $asset_uri, $network ) ),
            esc_attr( $social_network['title'] )
		);
	}
	$social_links .= '</tr>
		</table>
		</td></tr>
		</table>';
	return $social_links;
}



function get_email_footer($title = NULL){
	$footer ='';
	$asset_uri = 'https://idealbiz.io/wp-content/themes/idealbiz/assets/img/email/';

	if(!$title){ 
		$title=get_bloginfo( 'name' );
	}

    

	$footer .= '<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
				<tbody>
					<tr style="vertical-align: top;" valign="top">
						<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 15px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" valign="top" width="100%">
								<tbody>
									<tr style="vertical-align: top;" valign="top">
										<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>

			'.get_social_links().'

			<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; ><![endif]-->
			<div style="color:#000000;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
				<div style="font-size: 14px; line-height: 1.5; color: #000000; mso-line-height-alt: 21px;">
					<p style="line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 18px; margin: 0;">
					<a style="font-size:10px;" href="'.get_site_url().'">'.pll__('iDealBiz - A marketplace for businesses').'</a>
					</p>
				</div> 
			</div>
			<!--[if mso]></td></tr></table><![endif]-->
			<!--[if (!mso)&(!IE)]><!-->
			</div>
			<!--<![endif]-->
			</div>
			</div>
			<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
			<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
			</div>
			</div>
			</div>
			<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
			</td>
			</tr>
			</tbody>
			</table>
			<!--[if (IE)]></div><![endif]-->
			<br/><br/>
			</body>
			</html>';

			return $footer;
} 




function get_email_intro($user_id = NULL, $message = NULL, $hi = NULL){
	$user_intro ='';
	$asset_uri = get_stylesheet_directory_uri() . '/assets/img/email';

	if(!$hi){
		if($user_id == 'Customer Care'){
			$hi = '<h1 style="text-align: center;color: #ffffff;font-weight:400; ">'.$hi.',</h1>';
		}elseif($user_id){
			$user_info = get_userdata($user_id);
			$hi = '<h1 style="text-align: center;color: #ffffff;font-weight:400; ">'.$hi.',</h1>';
		}
	}else{
		$hi= $hi;
	}
	if($message){
		$message = '<p>'.$message.'<p/>';
	}
    $user_intro = '

	<table border="0" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f7f7f7;">
    <tbody>   
    <tr>
    <td align="center" valign="top">
    
    
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tbody>  
    <tr>
    <td align="center" valign="top">
    <div id="template_header_image">
   
    
    </div>
    <table id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #ffffff; border: 1px solid #dedede; border-radius: 3px !important;" border="0" width="600" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
    <td align="center" valign="top"><!-- Header -->
    <table id="template_header" style="background-color: #F58026; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" border="0" width="600" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
    <td id="header_wrapper" style="padding: 36px 48px; display: block; text-align: center;">
    <h1 style="color: #ffffff; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 30px ; font-weight: 400; line-height: 1.5; margin: 0; text-align: center; text-shadow: 0 1px 0 #353535; -webkit-font-smoothing: antialiased;">
    '.$hi.'
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
    <div id="body_content_inner" style="color: #636363; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left; white-space:pre-wrap;">'.$message.'</div></td></tr>
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
	return $user_intro;
}


function get_email_listings($posts, $max_listings = 3){
	$html='
	<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="margin-top:15px; text-align: left; table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
	<tr>
		<td style="font-size: 13px; font-weight: 700; text-align: center; width: 100%; padding-left: 30px; padding-right: 30px; color:#F58026; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
			<p style="margin-bottom:25px;">'.__('Your newly listings with less than 7 days','idealbiz').'</p>
		</td>
	</tr>
	<tr>
		<td style="margin-bottom: 15px; width: 100%; padding-left: 15px; padding-right: 15px; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style=" padding-left: 30px; padding-right: 30px; text-align: left; table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
			<tr>';
			$x = 0;
			while ( $posts->have_posts() ) {
				$x++;
				if($x > $max_listings){
					break;
				}
				$posts->the_post();
				$html.='
				<td class="col col-p-0" style="padding-left: 15px;margin-bottom: 15px; padding-right: 15px;width: 100%; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="text-align: left; table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
					<tr>
						<td style=" border:2px solid #f7f7f7; padding:15px; text-align: center; width: 100%;  vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="text-align: left; table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
								<tr>
									<td style="font-weight: 700; font-size: 13px; color:#F58026; border-radius:3px; text-align: center; width: 100%;  vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
										<a href="'.get_the_permalink().'" style="text-decoration:none;color:#F58026; " class="">	
											<img class="" style="width: 100%; padding-bottom:10px; " src="'.get_field('featured_image')['sizes']['medium'].'">	
											'.get_the_title().' <span style="background: #1EB3C8;padding: 2px;border: 1px solid #1EB3C8;border-radius: 5px;margin-left: 5px;color: #fff; font-size:8px;">'.__('New','idealbiz').'</span>
											<p style="margin-top: 10px; margin-bottom:0px;">
												<span style="font-size:10px; margin-top:5px; font-weight:700; color: #8BA7BD;">'.get_field('category')->name.'</span>
												<span style="font-size:10px; margin-top:5px; font-weight:700; background-color: #F3F5FA; padding: 2px 5px;border-radius: 5px;margin-left: 5px;">'.IDB_Listing_Data::get_listing_value(get_the_ID(), 'price_type').'</span>
											</p>
										</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					</table>
				</td>';
			}
			$html.=' 
			</tr>
			</table>
		</td>
	</tr>
	</table>
	<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="text-align: left; table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
	<tr>
		<td style="text-align: center; width: 100%; padding-left: 30px; padding-right: 30px; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
			<br/>&nbsp;<br/>&nbsp;
		</td>
	</tr>
	<tr>
		<td style="text-align: center; width: 100%; padding-left: 30px; padding-right: 30px; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
			<a style="padding: 10px 20px; background: #F58026; color: #fff; font-weight: 700; text-decoration: none;" href="'.getLinkByTemplate('premium-buyer.php').'" target="_blank">'.__('View all Listings', 'idealbiz').'</a>
		</td>
	</tr>
	<tr>
		<td style="text-align: center; width: 100%; padding-left: 30px; padding-right: 30px; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
			<br/>&nbsp;
		</td>
	</tr>
	</table>';
	
	return $html;
}

 

function get_email_myaccount($message = NULL){
	$h ='
	<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="text-align: left; table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
		<tr>
			<td style="text-align: center; width: 100%; padding-left: 30px; padding-right: 30px; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top">
				'.$message.'
				<br/><br/><br/>
				<a style="padding: 10px 20px; background: #408EFC; border-radius: 4px; color:#fff;" 
				href="'.wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'"
				title="'.__('My Account','idealbiz').'">'.__('My Account','idealbiz').'
				</a>
				<br/>
				<br/>
			</td>
		</tr>
	</table>';
	return $h;
}

function get_email_header_recomemded($user_id = NULL, $title = NULL){
	$asset_uri = get_stylesheet_directory_uri() . '/assets/img/email';
	
	if(!$title){ 
		$title=get_bloginfo( 'name' );
	}

	$url=get_bloginfo( 'url' );

	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
	

	$site_logo='
	<img src="'.$image[0].'" alt="logo" style="width: 301px; height: 121px; border: none;
    display: inline-block;
    font-size: 14px;
    font-weight: bold;
    height: auto;
    outline: none;
    text-decoration: none;
    text-transform: capitalize;
    max-width: 100%;
    margin-left: 0;
    margin-right: 0;
    vertical-align: bottom;" />
	';

	$country_iso = get_option('country_market');
    $country_flag= DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.strtolower($country_iso).'.png';
    $flag = '<img style="width:40px; height:27px;vertical-align: super;" width="40" height="27" src="'.$country_flag.'" />';

	$site_menu='';
	$menuLocations = get_nav_menu_locations(); // Get our nav locations 
	$menuID = $menuLocations['header-menu']; // Get the menu ID
	$primaryNav = wp_get_nav_menu_items($menuID); 
	$x=0;
	foreach ( $primaryNav as $navItem ) {
		if ( $navItem->menu_item_parent == 0 ){
			//$site_menu.= '<a style="text-decoration: none;font-size:12px; color:#F58026; '.($x>=1 ? 'font-weight: 400;' : 'font-weight: 700;') .'" href="'.$navItem->url.'" target="_blank" title="'.$navItem->title.'">'.$navItem->title.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
			$x++;
		}
	}

	$username = '<a style="font-size:12px; color:#F58026; text-decoration: none;" href="'.$url.'">'.__('Login / Register','idealbiz').'</a>';
	if($user_id == 'customer_care'){

	}else{
		$avatar =  '<img src="'.get_avatar_url($user_id, array("size"=>30)).'" style="border-radius: 50%; width: 50px height:50px;"/>';
	}
	
	
	if($user_id == 'customer_care'){
		$username = '<a style="text-decoration: none;font-size:12px; color:#F58026;" href="'.$url.'">'.__('Hi-1! Customer Care','idealbiz').'</a>';
	}elseif($user_id){
		$user_info = get_userdata($user_id);
		$username = '<a style="text-decoration: none;font-size:12px; color:#F58026;" href="'.$url.'">'.__('Hi-2!','idealbiz').' '.$user_info->first_name.'</a>';
		$avatar =  '<img src="'.get_avatar_url($user_id, array("size"=>30)).'" style="border-radius: 50%; width: 50px height:50px;"/>';
	}
	


	$head='';
	$head.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
				<head>
					<!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
					<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
					<meta content="width=device-width" name="viewport" />
					<!--[if !mso]><!-->
					<meta content="IE=edge" http-equiv="X-UA-Compatible" />
					<!--<![endif]-->
					<title>'.$title.'</title>
					<!--[if !mso]><!-->
					<!--<![endif]-->
					<style type="text/css">
						body {
							margin: 0;
							padding: 0;
							color:#636363;
						}
						
						table,
						td,
						tr {
							vertical-align: top;
							border-collapse: collapse;
						}
						
						* {
							line-height: inherit;
						}
						
						a[x-apple-data-detectors=true] {
							color: inherit !important;
							text-decoration: none !important;
						}
					</style>
					<style id="media-query" type="text/css">
						@media (max-width: 620px) {
							.col-p-0{
								padding: 0 !important;
							}
							.block-grid,
							.col {
								min-width: 320px !important;
								max-width: 100% !important;
								display: block !important;
							}
							.block-grid {
								width: 100% !important;
							}
							.col {
								width: 100% !important;
							}
							.col>div {
								margin: 0 auto;
							}
							img.fullwidth,
							img.fullwidthOnMobile {
								max-width: 100% !important;
							}
							.no-stack .col {
								min-width: 0 !important;
								display: table-cell !important;
							}
							.no-stack.two-up .col {
								width: 50% !important;
							}
							.no-stack .col.num4 {
								width: 33% !important;
							}
							.no-stack .col.num8 {
								width: 66% !important;
							}
							.no-stack .col.num4 {
								width: 33% !important;
							}
							.no-stack .col.num3 {
								width: 25% !important;
							}
							.no-stack .col.num6 {
								width: 50% !important;
							}
							.no-stack .col.num9 {
								width: 75% !important;
							}
							.video-block {
								max-width: none !important;
							}
							.mobile_hide {
								min-height: 0px;
								max-height: 0px;
								max-width: 0px;
								display: none;
								overflow: hidden;
								font-size: 0px;
							}
							.desktop_hide {
								display: block !important;
								max-height: none !important;
							}
						}
					</style>
				</head>

				<body class="clean-body" style="font-family:\'Raleway\', \'Arial\', sans-serif; margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f7f7f7;">
					<br/><br/><!--[if IE]><div class="ie-browser"><![endif]-->
					<table bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="font-family:\'Raleway\', \'Arial\', sans-serif; table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f7f7; width: 100%;" valign="top" width="100%">
						<tbody>
							<tr style="vertical-align: top;" valign="top">
								<td style="word-break: break-word; vertical-align: top;" valign="top">
									<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#f7f7f7"><![endif]-->
									<div style="background-position:top center;background-repeat:no-repeat;background-color:transparent;">
										<div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
											<div style="border-collapse: collapse;display: table;width: 100%;background-color:#f7f7f7;">
												<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-position:top center;background-repeat:no-repeat;background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px"><tr class="layout-full-width" style="background-color:#f7f7f7"><![endif]-->
												<!--[if (mso)|(IE)]><td align="center" width="600" style="background-color:#f7f7f7;width:600px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:60px; padding-bottom:60px;"><![endif]-->
												<div class="col num12" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top; width: 600px;">
													<div style="width:100% !important;">
														<!--[if (!mso)&(!IE)]><!-->
														<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:40px; padding-bottom:60px; padding-right: 0px; padding-left: 0px;">
															<!--<![endif]-->
															<div align="center" class="img-container center autowidth" style="padding-right: 0px;padding-left: 0px;">
																<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]-->
																<div style="font-size:1px;line-height:35px"> </div>
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tr valign="middle">
																		<td valign="middle" align="center" style="text-align:center; padding-right: 0px; padding-left: 30px; padding-top: 0px; padding-bottom: 0px; vertical-align: middle;" >
																		'.$site_logo.$flag.'
																		</td>
																	</tr>
																</table>
																	<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
																		<tbody>
																			<tr style="vertical-align: top;" valign="top">
																				<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 15px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
																					<table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" valign="top" width="100%">
																						<tbody>
																							<tr style="vertical-align: top;" valign="top">
																								<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
																							</tr>
																						</tbody>
																					</table>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																													
																	<!--[if mso]></td></tr></table><![endif]-->
															</div>';

		return $head;
}
