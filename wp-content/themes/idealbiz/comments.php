<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
    return;
?>
<br/>
<br/>
<br/>
<div class="container d-flex flex-row flex-wrap justify-content-around">
<div class="col-md-12">
<div class="expert position-relative p-b-15 dropshadow d-flex flex-column black--color white--background">
 
	<div class="row">

        <div class="col-md-12 text-center">
            <div class="title-wrap">
            <h2 class="base_color--color m-t-30"><?php _e('Leave a message to the Seller', 'idealbiz')?></h2>
            </div>
        </div>

<div id="comments" class="comments-area">
    <?php if ( have_comments() ) : ?>
        <ul class="comment-list">
            <?php
                wp_list_comments( array(
                    'style'       => 'li',
                    'short_ping'  => true,
                ) );
            ?>
        </ul><!-- .comment-list -->

        <?php
            // Are there comments to navigate through?
            if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
        ?>
        <nav class="navigation comment-navigation" role="navigation">
            <h1 class="screen-reader-text section-heading"><?php _e( 'Comment navigation', 'twentythirteen' ); ?></h1>
            <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentythirteen' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentythirteen' ) ); ?></div>
        </nav><!-- .comment-navigation -->
        <?php endif; // Check for comment navigation ?>

        <?php if ( ! comments_open() && get_comments_number() ) : ?>
        <p class="no-comments"><?php _e( 'Comments are closed.' , 'twentythirteen' ); ?></p>
        <?php endif; ?>

    <?php endif; // have_comments() ?>


    </div>
</div>

<div class="container-fluid white-container">
<div class="container">

    <div class="row">

        <div class="col-md-12 col-sm-12 text-left generic-form">
            <div class="comment-form acf-form">
            <?php comment_form(); ?>
            </div>
        </div>

    </div>

</div>
</div>

</div><!-- #comments -->
</div>