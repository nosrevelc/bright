<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Assets;
use WPFEPP\Constants\Option_Ids;
use WPGurus\Components\Component;

class Admin_Messages extends Component
{
	function __construct()
	{
		parent::__construct();

		$this->register_action('admin_enqueue_scripts', array($this, 'enqueue'));
		$this->register_action('admin_notices', array($this, 'admin_notices'));
		$this->register_installation_action(array($this, 'check_if_incompatible_changes_present'));
	}

	public function check_if_incompatible_changes_present()
	{
		update_option(
			Option_Ids::OPTION_HAS_INCOMPATIBLE_CHANGES,
			$this->get_last_version() != '0' && version_compare($this->get_version(), '3.0.0', '>=') && version_compare($this->get_last_version(), '3.0.0', '<')
		);
	}

	public function enqueue()
	{
		wp_enqueue_script(Assets::GLOBAL_ADMIN_JS);
	}

	public function admin_notices()
	{
		$last_nag_dismissed = get_option(Option_Ids::OPTION_NAG_DISMISSED, '0');
		$incompatible_changes = get_option(Option_Ids::OPTION_HAS_INCOMPATIBLE_CHANGES);

		if (version_compare($last_nag_dismissed, $this->get_version(), '>=') || !current_user_can('manage_options'))
			return;

		?>
		<div class="<?php echo $incompatible_changes ? 'error' : 'updated'; ?>">
			<p>
				<?php if ($incompatible_changes): ?>
					<strong><?php _e('Important', 'frontend-publishing-pro'); ?>:</strong>
					<?php _e("You are upgrading from an older version of FEP. You might have to make some changes on your site to keep things running smoothly. Please click the 'What's New' link below and read the latest release notes to find out exactly what changes are required.", 'frontend-publishing-pro'); ?>
					<br/>
				<?php else: ?>
					<?php _e('Thank you for installing the latest version of Frontend Publishing Pro! Please review your settings to make sure everything is in order.', 'frontend-publishing-pro'); ?>
				<?php endif; ?>
				<br/>
				<a target="_blank"
				   href="http://wpfrontendpublishing.com/category/release-notes/"><?php _e("What's New", 'frontend-publishing-pro'); ?></a>
				|
				<a target="_blank" href="http://codecanyon.net/downloads"><?php _e('Leave a Review', 'frontend-publishing-pro'); ?></a> |
				<a href="<?php echo admin_url('admin.php?page=wpfepp_form_manager'); ?>"><?php _e('Forms', 'frontend-publishing-pro'); ?></a> |
				<a href="<?php echo admin_url('admin.php?page=wpfepp_settings') ?>"><?php _e('Settings', 'frontend-publishing-pro'); ?></a>
				<a style="float:right;" id="wpfepp-dismiss-nag" href="#"><?php _e('Dismiss This Message', 'frontend-publishing-pro'); ?></a>
			</p>
		</div>
		<?php
	}
}