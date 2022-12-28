<?php

namespace WGMSRM\Traits;

/**
 * Trait SetupWizard
 */
trait SetupWizard {

	/**
	 * Setup Wizard view
	 *
	 * @since 1.7.5
	 */
	public function wpgmap_setup_wizard() {
		 require WGM_PLUGIN_PATH . 'admin/includes/wpgmap_setup_wizard.php';
	}

	/**
	 * Save setup wizard information
	 *
	 * @since 1.7.5
	 */
	public function wpgmap_save_setup_wizard() {
		if ( ! current_user_can( 'administrator' ) ) {
			echo wp_json_encode(
				array(
					'responseCode' => 403,
				)
			);
			wp_die();
		}
		if ( ! isset( $_POST['ajax_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ), 'ajax_nonce' ) ) {
			die( 'Busted!' );
		}
		$api_key       = isset( $_POST['wgm_api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['wgm_api_key'] ) ) : '';
		$language      = isset( $_POST['wgm_language'] ) ? sanitize_text_field( wp_unslash( $_POST['wgm_language'] ) ) : '';
		$regional_area = isset( $_POST['wgm_regional_area'] ) ? sanitize_text_field( wp_unslash( $_POST['wgm_regional_area'] ) ) : '';
		if ( empty( $api_key ) ) {
			$response = array( 'responseCode' => 101 );
			echo wp_json_encode( $response );
			die();
		}
		if ( empty( $language ) ) {
			$response = array( 'responseCode' => 102 );
			echo wp_json_encode( $response );
			die();
		}
		if ( empty( $regional_area ) ) {
			$response = array( 'responseCode' => 103 );
			echo wp_json_encode( $response );
			die();
		}
		update_option( 'wpgmap_api_key', $api_key, 'yes' );
		update_option( 'srm_gmap_lng', $language, 'yes' );
		update_option( 'srm_gmap_region', $regional_area, 'yes' );
		update_option( 'wgm_is_quick_setup_done', 'Y', 'yes' );
		$response = array( 'responseCode' => 200 );
		echo wp_json_encode( $response );
		die();
	}
}
