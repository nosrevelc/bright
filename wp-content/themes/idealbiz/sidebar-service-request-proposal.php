<?php if ( is_active_sidebar( 'sidebar-service-request-proposal' ) ) { ?>
	<hr/>
	<div class="sidebar-service-request-proposal">
		<div class="container p-0">
			<div id="contact-this-seller" class="form-content">
				<div class="box">
					<header class="box__header">
						<h2 class="box__title title">
							<?php esc_html_e( 'Create proposal', 'idealbiz' ); ?>
						</h2>
					</header>
					<p class="text-intro"><?php esc_html_e( 'Unless signaled (*), all fields are optional.', 'idealbiz' ); ?></p>
					<?php dynamic_sidebar( 'sidebar-service-request-proposal' ); ?>
				</div> <?php // .box ?>
			</div><?php // .account-content ?>
		</div>
	</div>
<?php
}
