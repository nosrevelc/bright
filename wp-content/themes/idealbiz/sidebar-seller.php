<?php if ( is_active_sidebar( 'sidebar-seller' ) ) { ?>
	<div class="sidebar-seller">
		<div class="container">
			<div id="contact-this-seller" class="form-content form-content--center">
				<div class="box box--decorated">
					<header class="box__header">
						<h1 class="box__title title">
							<?php esc_html_e( 'Contact this Seller', 'idealbiz' ); ?>
						</h1>
					</header>
					<p class="text-intro"><?php esc_html_e( 'Unless signaled (*), all fields are optional.', 'idealbiz' ); ?></p>
					<?php dynamic_sidebar( 'sidebar-seller' ); ?>
				</div> <?php //.box ?>
			</div><?php //.account-content ?>
		</div>
	</div>
<?php }
