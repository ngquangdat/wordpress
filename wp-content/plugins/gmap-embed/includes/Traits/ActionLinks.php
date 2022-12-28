<?php

namespace WGMSRM\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait ActionLinks: for plugins list action links viewing
 */
trait ActionLinks {

	/**
	 * Adding Upgrade to pro, rate us and support link
	 *
	 * @param $actions
	 * @return mixed
	 */
	public function gmap_srm_settings_link( $actions ) {
		$links = array();
		if ( ! _wgm_is_premium() ) {
			$links['upgrade-to-pro'] = '<a target="_blank" style="color: #11967A;font-weight:bold;" href="' . esc_url( 'https://wpgooglemap.com/pricing?utm_source=admin_plugins&utm_medium=admin_link&utm_campaign=plugin_action_link' ) . '">' . __( 'Upgrade To Pro ($19 only)' ) . '</a>';
		}
		$links['rate-us']  = '<a target="_blank" href="' . esc_url( 'https://wordpress.org/support/plugin/gmap-embed/reviews/#new-post' ) . '">' . __( 'Rate Us' ) . '</a>';
		$links['support']  = '<a target="_blank" href="' . esc_url( 'https://wordpress.org/support/plugin/gmap-embed/#new-topic-0' ) . '">' . __( 'Support' ) . '</a>';
		$links['settings'] = '<a href="' .
			esc_url( admin_url( 'admin.php?page=wpgmapembed-settings' ) ) .
			'">' . __( 'Settings' ) . '</a>';
		return array_merge( $links, $actions );
	}
}
