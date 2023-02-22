<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 
do_action( 'woocommerce_before_account_navigation' );
add_filter( 'woocommerce_account_menu_items', 'custom_remove_downloads_my_account', 999 );
function custom_remove_downloads_my_account( $items ) {
	unset($items['downloads']);
	return $items;
}

function cl_checaEndpoint($cl_endpoint){

	$cl_block_endpoint = array(
		'',
		'dashboard'
	);

	if(OPPORTUNITY_SYSTEM != '1'){ 
		array_push($cl_block_endpoint, 'mylistings','favorites','chat');
	}

	foreach($cl_block_endpoint as $cl_true){
		if($cl_endpoint === $cl_true){
			return 'false';
		}
	}

}
?>
<div class="woocommerce-MyAccount-navigation">
	<div class="swiper-nav p-relative">
		<div class="swiper-wrapper">
			<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
				<?php 
				if(cl_checaEndpoint($endpoint) != 'false'){?>				
				<div class="swiper-slide h-211px stroke dropshadow <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
				</div>
				<?php } ?>
			<?php endforeach; ?>
		</div>	
		<div class="swiper-nav-prev"></div>
		<div class="swiper-nav-next"></div>	
	</div>

</div>	

<?php whiteBackground() ?>

<?php do_action( 'woocommerce_after_account_navigation'); ?>
<?php do_action( 'head_lsitingRecomendaveis',true ); ?>
<div class="rb_botoes"><?php do_action('botao_lsitingRecomendaveis',null,false);?></div>

<style>
	.bota_quadrado{
		width: 210px;
		height: 210px;
		padding: 20px;
		background-color: #ffffff;
	}

	a:hover{
		text-decoration: none !important;
	}

	.bota_quadrado .dashicons{
		font-size: 70px;
		margin:35px 46px;
	}

	.bota_quadrado p{
		margin-top:50px !important;
		font-size:1em;

	}

	.rb_botoes{
		display:flex;
		justify-content:space-evenly ;
		margin-bottom:20px;

	}

	@media only screen and (max-width: 768px) {

		.rb_botoes{
		flex-direction: column;
		margin-bottom:5px;

	}

	.bota_quadrado{
		width: 100%;
		height: 70px;
		padding: 10px;
		background-color: #ffffff;
		margin-bottom:7px;
	}

	.bota_quadrado .dashicons{
		font-size: 50px;
		margin:0px 6px;
	}

	.bota_quadrado p{
		margin-top:-18px !important;
		margin-left:70px;
		font-size:1em;
	}

	}


</style>