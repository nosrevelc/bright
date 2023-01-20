<?php

/**
 * Gravity Forms Registration form to modal
 *
 * @since 2.01
 *
 * Class LRM_Pro_GravityForms
 */
class LRM_Pro_GravityForms {

	/**
	 * @return array|string[]
	 */
	static function get_forms_flat() {
		$forms_flat = [0=>'No Gravity Forms installed or No forms created!'];
		if ( class_exists('GFAPI') ) {
			$forms = GFAPI::get_forms();
				if ( $forms ) {
					$forms_flat = [];
					foreach ( $forms as $form ) {
						$forms_flat[ $form['id'] ] = sprintf('%s [#%d]', $form['title'], $form['id']);
					}
				}
		}

		return $forms_flat;
	}

	static function get_selected_form_id() {
		$form_id = lrm_setting( 'integrations/gf/form_id' );
		if ( $form_id && (int)$form_id !== 0 ) {
			$form = GFAPI::get_form( $form_id );
			if ( $form ) {
				return $form_id;
			}
		}
		return false;
	}

	static function display_selected_form() {
		$form_id = self::get_selected_form_id();

		if ( $form_id ) {
			$extras = '';
			if ( !lrm_setting( 'integrations/gf/show_title_and_desc' ) ) {
				$extras = ' title="false" description="false" ';
			}
			echo do_shortcode( sprintf('[gravityform id="%d" ajax="true" %s]', $form_id, $extras) );
			return;
		}
		echo '<h3>Error: No Gravity Forms form is selected to display!</h3>';
	}

	static function is_gf_active() {
		return class_exists('GFAPI');
	}

    /**
     * Add all necessary hooks
     */
    static function init() {

        if ( ! self::is_ultimatemember_active() || ! lrm_setting( 'integrations/gm/replace_with' ) ) {
            return;
        }

        if ( isset($_GET['lrm']) && isset($_POST['form_id']) ) {
	        add_action("um_submit_form_register", ["LRM_Pro_UltimateMember", "AJAX_signup"], 9);
	        add_action("um_registration_complete", ["LRM_Pro_UltimateMember", "AJAX_um_registration_complete"], 9, 2);

            add_filter('wp_die_handler', function ($function) {

                return ["LRM_Pro_UltimateMember", "_wp_die_handler"];

            });
        }

    }

}