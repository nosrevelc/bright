<?php
/**
 * The template for displaying all single listings.
 *
 * @package iDealBiz
 */

require_once(ABSPATH . 'wp-content/themes/idealbiz/library/SingleServiceRequest.php');
require_once(ABSPATH . 'wp-content/themes/idealbiz/library/SingleServiceContract.php');
SingleServiceRequest::protect_service_request();

// Reset form data.
if ( isset( $_POST['gform_submit'] ) ) {
	wp_redirect( get_the_permalink( get_queried_object() ) );
}

get_header(); ?>
        <section class="single-expert">
            <div class="container m-b-25">
                <a class="go-search font-weight-medium d-flex align-items-center" href="javascript: history.go(-1);">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back to your seach', 'idealbiz'); ?></span>
                </a>
            </div>
            <div class="container d-flex flex-row flex-wrap justify-content-around">

                <div class="col-md-12">
				<main id="main" class="service-request site-main">

					<?php
					while ( have_posts() ) : 
						the_post();
						$post_id = get_the_ID();

					?>

					<div class="container container--grid">
						<div class="service-request__info">
							<h1 class="listing__title title">
								<?php SingleServiceRequest::title(); ?>
							</h1>

							<div class="listing__meta listing__meta--info">
								<span class="listing__meta-date">
									<?php echo 'REF #' . get_the_ID(); ?>
								</span>
							</div>
						</div>
						<div class="service-request__container">

							<?php SingleServiceRequest::render_messages(); ?>

							<?php get_sidebar( 'service-message' ); ?>

						</div>

						<?php
						$current_user = wp_get_current_user();

						if ( in_array( 'consultant', $current_user->roles, true ) ) :
						?>
						<div class="service-request__contract">

							<?php SingleServiceRequest::render_contracts(); ?>

							<?php get_sidebar( 'service-request-proposal' );  ?>

						</div>
						<?php endif; ?>

					</div>

				<?php endwhile; ?>

				</main><?php // .site-main ?>
				</div></div></section>
<?php
get_footer();
