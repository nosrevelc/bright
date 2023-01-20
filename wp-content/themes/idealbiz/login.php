<?php 
// Template Name: Login iDealBiz
get_header(); ?>


<div class="container">
  <?php the_content(); ?>


  <div class="botoes">
    <a class="checkout-button btn p-y-13 p-x-40 m-t-15 lrm-register" href="#"><?php _e('Register', 'idealbiz'); ?></a>
    <a class="checkout-button btn p-y-13 p-x-40 m-t-15 lrm-login" href="#"><?php _e('Login', 'idealbiz'); ?></a>
  </div>
</div>

<?php get_footer(); ?>

<style>
.checkout-button{
	background: #005882 !important;
	line-height: 2em !important;
	font-size: 1.2em !important;
	}
	.product-remove{
		text-align: center;
	width: 20% !important;
	}
	.product-thumbnail{
		width: 30% !important;
	}

	.wc-proceed-to-checkout{
		display: <?php echo $cl_show_finalizar; ?>
	}
  .botoes{
    margin: 0 auto;
  }


</style>