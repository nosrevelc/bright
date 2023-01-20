<?php
/**
 * Plugin Solutions & Features Page
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variables
$popup_add_link = add_query_arg( array( 'post_type' =>SP_FAQ_POST_TYPE ), admin_url( 'post-new.php' ) );
?>

<div id="wrap">
	<div class="sp-faq-sf-wrap">
		<div class="sp-faq-sf-inr">
			<!-- Start - Welcome Box -->
			<div class="sp-faq-sf-welcome-wrap">
				<div class="sp-faq-sf-welcome-inr">
					<div class="sp-faq-sf-welcome-left">
						<div class="sp-faq-sf-subtitle">Getting Started</div>
						<h2 class="sp-faq-sf-title">Welcome to FAQ</h2>
						<p class="sp-faq-sf-content">Showcase your FAQ’s, associated with your business with multiple designs in an aesthetically appealing and professional way.</p>
						<a href="<?php echo esc_url( $popup_add_link ); ?>" class="sp-faq-sf-btn">Launch FAQ</a></br> <b>OR</b> </br><a href="<?php echo SP_FAQ_PLUGIN_LINK_WELCOME; ?>"  target="_blank" class="sp-faq-sf-btn sp-faq-sf-btn-orange">Grab Now Pro Features</a>
						<div class="sp-faq-rc-wrap">
							<div class="sp-faq-rc-inr sp-faq-rc-bg-box">
								<div class="sp-faq-rc-icon">
									<img src="<?php echo esc_url( SP_FAQ_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
								</div>
								<div class="sp-faq-rc-cont">
									<h3>14 Days Refund Policy. 0 risk to you.</h3>
									<p>14-day No Question Asked Refund Guarantee</p>
								</div>
							</div>
							<div class="sp-faq-rc-inr sp-faq-rc-bg-box">
								<div class="sp-faq-rc-icon">
									<img src="<?php echo esc_url( SP_FAQ_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
								</div>
								<div class="sp-faq-rc-cont">
									<h3>Include Done-For-You FAQ Setup</h3>
									<p>Our experts team will design 1 free FAQ for you as per your need.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="sp-faq-sf-welcome-right">
						<div class="sp-faq-sf-fp-ttl">Free vs Pro</div>
						<div class="sp-faq-sf-fp-box-wrp">
							<div class="sp-faq-sf-fp-box">
								<i class="dashicons dashicons-slides"></i>
								<div class="sp-faq-sf-box-ttl">1 Designs for FAQ</div>
								<div class="sp-faq-sf-tag">Free</div>
							</div>
							<!-- <div class="sp-faq-sf-fp-box">
								<i class="dashicons dashicons-slides"></i>
								<div class="sp-faq-sf-box-ttl">1 Designs for News List</div>
								<div class="sp-faq-sf-tag">Free</div>
							</div> -->
							<div class="sp-faq-sf-fp-box">
								<i class="dashicons dashicons-category"></i>
								<div class="sp-faq-sf-box-ttl">Display FAQ for Particular Categories</div>
								<div class="sp-faq-sf-tag">Free</div>
							</div>
							<div class="sp-faq-sf-fp-box">
								<i class="dashicons dashicons-block-default"></i>
								<div class="sp-faq-sf-box-ttl">Gutenbreg Block Support</div>
								<div class="sp-faq-sf-tag">Free</div>
							</div>
							<div class="sp-faq-sf-fp-box">
								<i class="dashicons dashicons-editor-rtl"></i>
								<div class="sp-faq-sf-box-ttl">Slider RTL Support</div>
								<div class="sp-faq-sf-tag">Free</div>
							</div>
							<div class="sp-faq-sf-fp-box">
								<i class="dashicons dashicons-tagcloud"></i>
								<div class="sp-faq-sf-box-ttl">Single FAQ Open</div>
								<div class="sp-faq-sf-tag">Free</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-art"></i>
								<div class="sp-faq-sf-box-ttl">15+ Designs</div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-slides"></i>
								<div class="sp-faq-sf-box-ttl">2 (FAQ, FAQ items with Categories in Grid )</div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-html"></i>
								<div class="sp-faq-sf-box-ttl">WP Templating Features </div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-layout"></i>
								<div class="sp-faq-sf-box-ttl">Elementor, Beaver, SiteOrigin, and VC Page Builder Support</div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-admin-generic"></i>
								<div class="sp-faq-sf-box-ttl">Default Open FAQ</div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-menu-alt3"></i>
								<div class="sp-faq-sf-box-ttl">Default Open FAQ</div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-admin-generic"></i>
								<div class="sp-faq-sf-box-ttl">WooCommerce Supports</div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-move"></i>
								<div class="sp-faq-sf-box-ttl">Drag & Drop FAQ Order Change</div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-button"></i>
								<div class="sp-faq-sf-box-ttl">Show Read more/ Show Less button</div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
							<div class="sp-faq-sf-fp-box sp-faq-sf-pro-box">
								<i class="dashicons dashicons-shortcode"></i>
								<div class="sp-faq-sf-box-ttl">Shortcode Generator</div>
								<div class="sp-faq-sf-tag">Pro</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Welcome Box -->

			<!-- Start - WP News and Scrolling Widgets - Features -->
			<div class="sp-faq-features-section">
				<div class="sp-faq-center sp-faq-features-ttl">
					<h2 class="sp-faq-sf-ttl">Powerful Pro Features, Simplified</h2>
				</div>
				<div class="sp-faq-features-section-inr">
					<div class="sp-faq-features-box-wrap">
						<ul class="sp-faq-features-box-grid">
							<li>
							<div class="sp-faq-popup-icon"><img src="<?php echo SP_FAQ_URL; ?>assets/images/popup-icon/faq-accordion.png" /></div>
							FAQ View</li>
							<li>
							<div class="sp-faq-popup-icon"><img src="<?php echo SP_FAQ_URL; ?>assets/images/popup-icon/faq-cat-grid-im.png" /></div>
							FAQ Category Grid View</li>
						</ul>
					</div>
					<a href="<?php echo SP_FAQ_PLUGIN_LINK_WELCOME; ?>" target="_blank" class="sp-faq-sf-btn sp-faq-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Grab Now Pro Features</a>
					<div class="sp-faq-rc-wrap">
						<div class="sp-faq-rc-inr sp-faq-rc-bg-box">
							<div class="sp-faq-rc-icon">
								<img src="<?php echo esc_url( SP_FAQ_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="sp-faq-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="sp-faq-rc-inr sp-faq-rc-bg-box">
							<div class="sp-faq-rc-icon">
								<img src="<?php echo esc_url( SP_FAQ_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="sp-faq-rc-cont">
								<h3>Include Done-For-You FAQ Setup</h3>
								<p>Our  experts team will design 1 free FAQ for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Logo Showcase - Features -->

			<!-- Start - Testimonial Section -->
			<div class="sp-faq-sf-testimonial-wrap">
				<div class="sp-faq-center sp-faq-features-ttl">
					<h2 class="sp-faq-sf-ttl">Looking for a Reason to Use FAQ? Here are 10+...</h2>
				</div>
				<div class="sp-faq-testimonial-section-inr">
					<div class="sp-faq-testimonial-box-wrap">
						<div class="sp-faq-testimonial-box-grid">
							<h3 class="sp-faq-testimonial-title">Support</h3>
							<div class="sp-faq-testimonial-desc">Always wary of ‘free’ plugins as the support in the main, is lacking. Had a problem implementing this plugin with regard CSS issues. Contacted the author, Anoop, who within 30 minutes, visited my site, checked the code, saw a conflict with other files and fixed it! All done…thank you Anoop 😉 That’s support!!</div>
							<div class="sp-faq-testimonial-clnt">@rik0399</div>
							<div class="sp-faq-testimonial-rating"><img src="<?php echo SP_FAQ_URL; ?>assets/images/rating.png" /></div>
						</div>
						<div class="sp-faq-testimonial-box-grid">
							<h3 class="sp-faq-testimonial-title">FAQ Pro</h3>
							<div class="sp-faq-testimonial-desc">Works very smoothly and support is very helpful and perfect!</div>
							<div class="sp-faq-testimonial-clnt">@pemacviper</div>
							<div class="sp-faq-testimonial-rating"><img src="<?php echo SP_FAQ_URL; ?>assets/images/rating.png" /></div>
						</div>
						<div class="sp-faq-testimonial-box-grid">
							<h3 class="sp-faq-testimonial-title">Quick and easy to deploy</h3>
							<div class="sp-faq-testimonial-desc">It only took me a few minutes to install the plugin, read the documentation and publish a few FAQs. Beautiful result out of the box. No additional styling needed.</div>
							<div class="sp-faq-testimonial-clnt">@jwerk13040</div>
							<div class="sp-faq-testimonial-rating"><img src="<?php echo SP_FAQ_URL; ?>assets/images/rating.png" /></div>
						</div>
						<div class="sp-faq-testimonial-box-grid">
							<h3 class="sp-faq-testimonial-title">Great plugin and support</h3>
							<div class="sp-faq-testimonial-desc">Got the paid version. Great plugin and got quick help from the chat when I got stuck!</div>
							<div class="sp-faq-testimonial-clnt">@johanssonola</div>
							<div class="sp-faq-testimonial-rating"><img src="<?php echo SP_FAQ_URL; ?>assets/images/rating.png" /></div>
						</div>
						<div class="sp-faq-testimonial-box-grid">
							<h3 class="sp-faq-testimonial-title">Smooth and Easy</h3>
							<div class="sp-faq-testimonial-desc">Added, activated and set up all within 10 minutes.</div>
							<div class="sp-faq-testimonial-clnt">@nzcid</div>
							<div class="sp-faq-testimonial-rating"><img src="<?php echo SP_FAQ_URL; ?>assets/images/rating.png" /></div>
						</div>
						<div class="sp-faq-testimonial-box-grid">
							<h3 class="sp-faq-testimonial-title">Easy and fast</h3>
							<div class="sp-faq-testimonial-desc">Has really many features and works just great! Wonderful plugin.</div>
							<div class="sp-faq-testimonial-clnt">@devsteven</div>
							<div class="sp-faq-testimonial-rating"><img src="<?php echo SP_FAQ_URL; ?>assets/images/rating.png" /></div>
						</div>
					</div>
					<a href="https://wordpress.org/support/plugin/sp-faq/reviews/?filter=5" target="_blank" class="sp-faq-sf-btn"><span class="dashicons dashicons-star-filled"></span> View All Reviews</a> OR <a href="<?php echo SP_FAQ_PLUGIN_LINK_WELCOME; ?>"  target="_blank" class="sp-faq-sf-btn sp-faq-sf-btn-orange">Grab Now Pro Features</a>
					<div class="sp-faq-rc-wrap">
						<div class="sp-faq-rc-inr sp-faq-rc-bg-box">
							<div class="sp-faq-rc-icon">
								<img src="<?php echo esc_url( SP_FAQ_URL ); ?>assets/images/popup-icon/14-days-money-back-guarantee.png" alt="14-days-money-back-guarantee" title="14-days-money-back-guarantee" />
							</div>
							<div class="sp-faq-rc-cont">
								<h3>14 Days Refund Policy. 0 risk to you.</h3>
								<p>14-day No Question Asked Refund Guarantee</p>
							</div>
						</div>
						<div class="sp-faq-rc-inr sp-faq-rc-bg-box">
							<div class="sp-faq-rc-icon">
								<img src="<?php echo esc_url( SP_FAQ_URL ); ?>assets/images/popup-icon/popup-design.png" alt="popup-design" title="popup-design" />
							</div>
							<div class="sp-faq-rc-cont">
								<h3>Include Done-For-You FAQ Setup</h3>
								<p>Our  experts team will design 1 free FAQ for you as per your need.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End - Testimonial Section -->
		</div>
	</div><!-- end .sp-faq-sf-wrap -->
</div><!-- end .wrap -->