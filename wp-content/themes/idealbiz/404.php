<?php get_header(); ?>
<div class="container-fluid">
<div class="row">
	<div class="col-md-12">
		<?php get_template_part( 'elements/navbar' ); ?>
	</div>
</div>
</div>

<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 no-pad">
        <div id="carousel-page" class="carousel slide" data-ride="carousel">

          <div class="bar-container">
            <div class="bar bar1"></div><div class="bar bar2"></div><div class="bar bar3"></div><div class="bar bar4"></div><div class="bar bar5"></div>
          </div>

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">

            <div class="item active">
              <div class="carousel-caption">
              <header>
                <h1>404 Page not found</h1>
              </header>
              </div>
            </div>

          </div>
        </div>
    </div>
</div>
</div>

<div class="container">
	<div class="row">
	    <div class="col-md-12">
        <article class="page">
            <div class="page__content">

                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <?php the_content(); ?>
                    <?php endwhile; ?>
                <?php endif; ?>

            </div>
        </article>
        </div>
    </div>
</div>

<?php get_footer(); ?>