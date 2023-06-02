<?php

/**
 * Header file common to all
 * templates
 *
 */
ob_start();

?>
<!doctype html>
<html class="cl_cl" <?php language_attributes(); ?>>

<head>


    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	

    <title><?php wp_title(); ?></title>

	
    <script>
    document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/, 'js')
    </script>


    <?php // load the core js polyfills 
	?>
    


    <?php wp_head(); ?>

<!-- NPMM - Add pelo Cleverson em 18/02/22 -->	
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-6JJEW7J97G"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'G-6JJEW7J97G');
</script>

<script data-ad-client="ca-pub-3905104260766388" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<style>
<?php if (is_singular('listing')) {
    $bg='#fbfbfc';
}

else {
    $bg=get_field('background');
    if ($bg=='') $bg='#fbfbfc';
}

?>
body {
    background-color: <?php echo $bg;?> !important;
}

.whiteTriangle:after {
    background-color: <?php echo $bg;
    ?> !important;
}
</style>
<?php 
	global $post;
	global $toggle_services;
    $post_slug = $post->post_name;
?>

	<body data-page-slug="<?php echo $post_slug; ?>" data-simplebar>


    <?php function_post_counter(get_the_ID()); ?>

    <?php // <body> closes in footer.php 

		$country_iso = get_option('country_market');
		$country_name = getCountry($country_iso)['country'];
		$country_flag= DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.strtolower($country_iso).'.png';
		$filename = get_page_template();
		$auxTp = explode('/',strrev($filename));
		$tp = strrev($auxTp[0]);
	?>
    <section class="header">
        <div class="container medium-width p-t-5">

            <div class="row">
                <div class="col-md-2 col-sm-12 col-xs-12 text-xs-center d-flex menu-container">
				<div class=" my-account-header">
					<a href="#">
    				<!-- <i class="hamburg bi bi-list"></i> -->
					</a>
				</div>	

                    <div class="d-flex">
                        <!-- <a class="c-search toggle-search collapse" id="site-menu-b" href="#"><i
                                class="icon icon-close"></i></a> -->
                        <?php
								/* the_custom_logo(); */
								logoHeaderSite();
							?>
                        <?php country_market_only_flag() ?>
						<button class="m-header-btn o-menu" type="button" data-toggle="collapse"
                            data-target=".toggle-menu" aria-expanded="false"
                            aria-controls="site-main-menu site-search-b">
                            <span class="ham">
                                <span class="burg"></span>
                                <span class="burg"></span>
                                <span class="burg"></span>
                            </span>
                        </button>
                    </div>
                    <div class="d-flex">
                        <div class="wpml-ls hidden-desktop hidden-mobile">
                            <ul>
                                <?php
									//do_action('wpml_add_language_selector'); 
									pll_the_languages(array('show_flags' => 1, 'show_names' => 0));
									?>
                            </ul>
                        </div>
                        <!-- <button class="m-header-btn o-search" type="button" data-toggle="collapse"
                            data-target=".toggle-search" aria-expanded="false" aria-controls="site-search site-menu-b">
                            <i class="icon icon-search"></i>
                        </button> -->
                    </div>
                    <a class="c-menu toggle-menu collapse" id="site-search-b" href="#"><i
                            class="icon icon-close"></i></a>
                </div>
                <div class="col-md-10 col-sm-12 col-xs-12 collapse dont-collapse-sm toggle-menu p-t-10"
                    id="site-main-menu">
                    <div
                        class="col-md-6 col-sm-12 col-xs-12 text-right d-block d-md-none justify-content-end h-35px m-t-5 filter-blue m-my-account ">
                        <?php include("includes/my-account-widget.php"); ?>
                    </div>
                    <div class="row m-m-0 site-main-menu-inner-wrapper">
                        <div id="select_country" class="col-md-6 col-sm-12 col-xs-12">
						<img style="width:41px!important;border-radius:3px;" src="<?php echo $country_flag; ?>" alt="<?php echo $country_name; ?>">
						<span class="m-l-5"><?php echo $country_name; ?></span>
                            <?php
							wp_nav_menu(
								array(
									'theme_location'    => 'header-menu',
									'depth'             => 5,
									'container'         => 'ul',
									'container_class'   => 'nav navbar-nav navbar-right',
									'menu_class'        => 'header-menu',
									'fallback_cb'       => 'false'
								)
							);
							
							$business_options = get_field('business_options',getIdByTemplate('homepage-country.php'));

							if($business_options){
								echo '
									<ul class="header-menu services-menu">
									<li class="menu-item menu-bold menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-2625 current_page_item menu-item-has-children menu-item-3017">
										<a aria-current="page" class="menu-image-title-after pointer toggle-next">
											<span class="img-icon"></span><span class="menu-image-title">'.__('Activity Areas','idealbiz').'</span>
										</a>
									<ul class="sub-menu site-blocks">';
									$bo=0;
									foreach ($business_options as $option) {
										
										$color_box = 'none';
										if($option['icon_color'] != ''){
											$color_box = $option['icon_color'];
										}
										if($option['external_link'] == '' && $option['button_link'] == '' ){
                
										}else{
											if ($option['detach'] == false){	
										echo'
										<li class="b-opts menu-item menu-item-type-post_type menu-item-object-page menu-item-privacy-policy menu-item-3018">
											<div class="appicon-menu-inner b-opts-inner o-visible-desktop">
												<a href="'.($option['external_link'] ? $option['external_link'] : $option['button_link']).'" data-sbo="'.$bo.'" class="m-appicon '.($option['required_login'] ? 'lrm-login': ' ').' menu-image-title-after">';
												if($option['icon']==''){
													echo'<img src="'.$option['image_ico']['url'].'" class="bo-svg" alt="'.$option['title_desktop'].'" title="'.$option['title_desktop'].'" />';
												}else{
													echo'<i class="white--color '.$option['icon'].'"></i>';
												}
											echo'<span class="img-icon" style="background: '.$color_box.'"></span><span class="menu-image-title">'.$option['title'].'</span>
												</a>';

												$toggle_services .= '
												<div class="b-opts-inner">
												<div class="b-opts-d-open p-15 m-y-0 m-x-7 stroke bo-'.$bo.'">
													<div class="b-opts-d-open-inner white--background">
														<a href="#" class="b-opts-close"><i class="icon icon-close"></i></a>
														<div class="mappoicon dropshadow p-t-25 m-x-10">
															<div style="background-color:'.$color_box.'" class="stroke dropshadow p-t-45 m-x-10 innerico">';
																if($option['icon']==''){ 
																	$toggle_services .= '<img src="'.$option['image_ico']['url'].'" class="bo-svg" alt="'.$option['title_desktop'].'" title="'.$option['title_desktop'].'" />';
																}else{
																	$toggle_services .= '<i class="white--color m-l-35 '.$option['icon'].'"></i>';
																}
																$toggle_services .= '<h2 class="m-l-35 white--color d-none d-md-block">'.str_replace_first(' ', '<br/>', $option['title_desktop']).'</h2>
															</div>
														</div>
														<div class="b-opts-body">
															<h3 class="font-weight-semi-bold m-b-20">'.$option['title_desktop'].'</h3>
															<p>'.$option['text'].'</p>
															<a class="btn btn-blue m-t-5 white--background h-36px l-h-18 '.($option['required_login'] ? 'lrm-login': ' ').'" href="'.($option['external_link'] ? $option['external_link'] : $option['button_link']).'">'.__('Selecionar','idealbiz').'</a>
														</div>
													</div>    
												</div>
												</div>
												';

										echo '</div>	
											<span class="d-block d-md-none m-span">'.$option['title'].'</span>
											</li> 
										';
											}
										$bo++;
										}
									}
							echo '</ul>
								</li>
								</ul>'; 
								
							}

							echo'
							<ul class="header-menu where-menu">
								<li class="menu-bold menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-2608">
									<a class="menu-image-title-after pointer toggle-next">
										<span class="img-icon"></span>
										<span id="menu-image-title" class="menu-image-title">'.__('Where?','idealbiz').'</span>
									</a>
									<ul class="sub-menu">';
										$continents = getContinents();
										
										$countries = getNetworkCountries();

										
										$sl_continents='';
										foreach($countries as $kcontinent => $countries){

											echo '<li id="menu-item-2611" class="hex-a55df1 menu-item menu-item-type-custom menu-item-object-custom menu-item-2611">
													<h4>'.$continents[$kcontinent].'</h4>';
													foreach ($countries as $country){

														
														$country_iso2 = $country["iso"];
														$country_flag2= DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.strtolower($country_iso2).'.png';
														
														$cl_flag = '<img style="width:37px!important;border-radius:3px; margin-right:3px;" src="'. $country_flag2.'" alt="<?php echo $country_name; ?>">';
														if ($country_iso != $country["iso"] ){
														$cl_flag2 .= '<a alt="'.$country['name'].'" href="'.$country['link'].'" style="margin-left:15px;">'.$cl_flag.'</a>';
														}

													
														echo '<a alt="'.$country['name'].'" href="'.$country['link'].'">'.$cl_flag.''.$country['name'].'</a>';
													
													}
											echo '</li>';

										} 
							  echo '</ul>
								</li>
							</ul>
							';
							switch ($tp){
								case 'homepage_v2.php':
								case 'homepage_v1.php':								
							?>				        
										<span class="flagBlog"><?php echo $cl_flag2; ?></span>
										

                            <?php
							break;
							}	
							wp_nav_menu(
								array(
									'theme_location'    => 'footer-menu',
									'depth'             => 1,
									'container'         => 'ul',
									'container_class'   => 'nav navbar-nav navbar-right',
									'menu_class'        => 'footer-menu d-none d-xs-block',
									'fallback_cb'       => 'false'
								)
							);
							?>
                        </div>
                        <div
                            class="col-md-6 col-sm-12 col-xs-12 text-right d-md-flex d-none justify-content-end h-35px m-t-5 filter-blue m-my-account font-weight-bold">
                            <?php
							$args = [ 
								'post_type' => 'page', 'fields' => 'ids', 'nopaging' => true, 'meta_key' => '_wp_page_template',
								'meta_value' => 'submit-listing.php', 'suppress_filters' => false
							];
							$submitListingPage = get_posts($args);
							$submitPageLink = get_page_link($submitListingPage[0]);
							$args = array(
								'post_type' => 'listing',
								'post_status' => 'draft',
								'posts_per_page' => 1,
								'author' => get_current_user_id()
							);
							$draftPost = get_posts($args);
							if (count($draftPost)) {
								$submitPageLink = $submitPageLink . '?listing_id=' . $draftPost[0]->ID;
								$template = get_page_template_slug();
									if($template === 'submit-listing.php' && !$_GET['listing_id']){
										//echo $submitPageLink;
										//wp_redirect( $submitPageLink );
										//exit;
									}
							}
							?>
							
                            <!-- <a  href="<?php echo $submitPageLink; ?>"
                                class="btn-pill m-r-0 f-bold lrm-login blue--hover"><?php _e('str_Sell a Business', 'idealbiz') ?></a> -->
								<!-- <a href="https://idealbiz.io/network/en/"><img class="img_socail_biz" src="https://idealbiz.io/pt/wp-content/uploads/sites/86/2022/01/MicrosoftTeams-image-1.png" alt="SocialBiz"></a> -->
								<div class="wpml-ls hidden-mobile hidden-desktop">
									<!-- <a class="lrm-login" href="">teste</a> -->
                                <ul>


                                    <?php

									$translations = pll_the_languages(array('raw'=>1));
									foreach($translations as $t){
										?>
										<li class="<?php echo implode(' ',$t['classes']); ?>" style="position:relative;">
											<a href="<?php echo $t['url']; ?>"><img style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; object-fit: cover;" src="<?php echo DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.$t['slug'].'.png'; ?>"/></a>
										</li>
										<?php
									}
									//var_dump($translations);
									//pll_the_languages(array('show_flags' => 1, 'show_names' => 0));
									?>
                                </ul>
                            </div>
                            <!-- <div class="separator-vertical d-none d-lg-block"></div> -->
                            <?php include("includes/my-account-widget.php"); ?>
							
                        </div>
						
                    </div>
                </div>
            </div>
        </div>

        <!-- <?php include('includes/search-bar.php'); ?> -->

    </section>
    <?php

//do_this_daily_expired_listings();
function country_market_only_flag() {
    $country_iso = get_option('country_market');
    $country_name = getCountry($country_iso)['country'];
    $country_flag= DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.strtolower($country_iso).'.png';

    ?>
    <!-- <div class="m-3 country-market-flag">
        <img src="<?php echo $country_flag; ?>" alt="<?php echo $country_name; ?>">
    </div> -->
    <?php
}


?>
<style>
#site-main-menu{
	position: relative;
	z-index: 100;
}
.img_socail_biz{
	width: 130px;
	height: 100%;
	margin-right: 55px;
}

/** Esconde o quadro Nova Conta no Login */
/* .lrm-user-modal-container .lrm-switcher.-is-not-login-only li:last-child a{
			display:none !important;
} */

	@media (max-width: 767px){
				.services-menu{
				display:none;
			}

			.d-md-none{
				display:none;
			}

			body .header .container #site-main-menu .m-my-account .my-account-header {
    		height: 370px!important;
			}
		}
		/** Esconde o quadro Nova Conta no Login */
		/* .lrm-user-modal-container .lrm-switcher.-is-not-login-only li:last-child a{
			display:none !important;
		} */

</style>