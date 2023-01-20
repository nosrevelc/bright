<?php
/**
 * The template for displaying all single listings.
 *
 * @package iDealBiz
 */

require_once(ABSPATH . 'wp-content/themes/idealbiz/library/SingleServiceRequest.php');
require_once(ABSPATH . 'wp-content/themes/idealbiz/library/SingleServiceContract.php');

SingleServiceContract::protect_service_contract();

get_header(); ?>
        <section class="single-service-request">
            <div class="container m-b-25 m-t-15">
			<a class="go-search font-weight-medium d-flex align-items-center" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id')); ?>">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back to your account', 'idealbiz'); ?></span>
                </a>
            </div>
            <div class="container d-flex flex-row flex-wrap justify-content-around">
				

                <div class="col-md-12 m-b-20">
				<div class="expert position-relative p-20 dropshadow d-flex flex-column black--color white--background">
	

		<main id="main" class="listing site-main">

			<?php
			while ( have_posts() ) :
				the_post();
				$post_id = get_the_ID();



		


			?>

			<header class="listing__header container">
				<div class="listing__header-fullrow">
					<h1 class="listing__title title">
						<?php SingleServiceContract::title(); ?>
					</h1>

					<div class="listing__meta listing__meta--info">
						<span class="listing__meta-date">
							<?php echo 'REF #' . get_the_ID(); ?>
						</span>
					</div>
				</div>

			</header>
			<section class="container">
				<div class="listing__header-fullrow">
					<div class="listing__meta listing__meta--info">
						<?php SingleServiceContract::render(); ?>
					</div>

				</div>
			</section>

		<?php endwhile; ?>


		</main><?php // .site-main ?>
			</div>
			</div>
			</div>
			</section>

<?php
get_footer();
