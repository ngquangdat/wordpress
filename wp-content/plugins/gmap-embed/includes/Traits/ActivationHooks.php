<?php

namespace WGMSRM\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait ActivationHooks: Do something on plugin activation
 */
trait ActivationHooks {

	/**
	 * Do after activation
	 *
	 * @param $plugin
	 * @param $network_activation
	 */
	public function wpgmap_do_after_activation( $plugin, $network_activation ) {
		// In case of existing installation
		if ( get_option( 'gmap_embed_activation_time', false ) == false ) {
			update_option( 'gmap_embed_activation_time', time() );
		}

		if ( $plugin === 'gmap-embed/srm_gmap_embed.php' ) {
			wp_redirect( admin_url( 'admin.php?page=wgm_setup_wizard' ) );
			exit;
		}
	}
}
