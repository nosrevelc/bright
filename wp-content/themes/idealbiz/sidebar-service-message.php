

<?php if ( is_active_sidebar( 'sidebar-service-message' ) ) { ?>
	<div class="sidebar-service-message">
		<div id="contact-this-seller" class="form-content">
			<h2 class="box__title title">
				<?php esc_html_e( 'Contact', 'idealbiz' ); ?>
			</h2>
			<p class="text-intro"><?php esc_html_e( 'Unless signaled (*), all fields are optional.', 'idealbiz' ); ?></p>
			<?php dynamic_sidebar( 'sidebar-service-message' ); ?>
		</div><?php //.account-content ?>
	</div>
<?php }
