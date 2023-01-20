<?php
/**
 * Template Name: Page Full Screen List Sites
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

get_header();

buddyx()->print_styles( 'buddyx-content' );
buddyx()->print_styles( 'buddyx-sidebar', 'buddyx-widgets' );

?>
	<main id="primary" class="site-main">
		<?php
		if ( have_posts() ) {

			while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/content/entry', 'full-width' );
            }
            
		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>

        <div class="cl_site">
        <h2>
            List of Sites Register to access this Social Media.
        </h2>

        <?php get_template_part( 'template-parts/list-sites' ); ?>

        </div>

	</main><!-- #primary -->

    <style>
        .cl_site a{
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            color:#135E96;
            font-weight: 700;
            padding-left: 30px;
        }
        .cl_site{
            background-color: #fff;
        }

    </style>

<?php
get_footer();
