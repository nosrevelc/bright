<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="d-flex flex-row flex-wrap justify-content-center container">
	<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
		<legend class="text-left font-weight-bold"><?php esc_html_e( 'Account details', 'woocommerce' ); ?></legend>

		<div class="form-row">
			<div class="form-group col p-0">
				<span class="wpcf7-form-control-wrap">
					<label class="text-left font-weight-bold" for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="form-control" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" placeholder="<?php esc_html_e( 'First name', 'woocommerce' ); ?>"/>
				</span>
			</div>
			<div class="form-group col p-0">
				<span class="wpcf7-form-control-wrap">
					<label class="text-left font-weight-bold" for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="form-control" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" placeholder="<?php esc_html_e( 'Last name', 'woocommerce' ); ?>"/>
				</span>
			</div>
		</div>

		<div class="form-row">
			<div class="form-group col p-0">
				<span class="wpcf7-form-control-wrap">
					<label class="text-left font-weight-bold" for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="form-control" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" placeholder="<?php esc_html_e( 'Display name', 'woocommerce' ); ?>" /> <span><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
				</span>
			</div>
		</div>

		<div class="form-row">
			<div class="form-group col p-0">
				<span class="wpcf7-form-control-wrap">
					<label class="text-left font-weight-bold" for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="email" class="form-control" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" placeholder="<?php esc_html_e( 'Email address', 'woocommerce' ); ?>"/>
				</span>
			</div>
		</div>

		<fieldset>
			<legend class="text-left font-weight-bold"><?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

			<div class="form-row">
				<div class="form-group col p-0">
					<span class="wpcf7-form-control-wrap">
						<label class="text-left font-weight-bold" for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--password input-text form-control" name="password_current" id="password_current" autocomplete="off" placeholder="<?php esc_html_e( 'Current password', 'woocommerce' ); ?>"/>
					</span>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col p-0">
					<span class="wpcf7-form-control-wrap">
						<label class="text-left font-weight-bold" for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--password input-text form-control" name="password_1" id="password_1" autocomplete="off" placeholder="<?php esc_html_e( 'New password', 'woocommerce' ); ?>"/>
					</span>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col p-0">
					<span class="wpcf7-form-control-wrap">
						<label class="text-left font-weight-bold" for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--password input-text form-control" name="password_2" id="password_2" autocomplete="off" placeholder="<?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?>"/>
					</span>
				</div>
			</div>
		</fieldset>
		<div class="clear"></div>

		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<p class="text-right">
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<button type="submit" class="btn btn-blue blue--hover " name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
			<input type="hidden" name="action" value="save_account_details" />
		</p>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
		<?php echo do_shortcode( '[plugin_delete_me /]' ); ?>
	</form>
</div>


<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
