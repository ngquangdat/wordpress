<?php
namespace WGMSRM\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filters related to the plugin will be listed here
 */
trait Filters {

	/**
	 * In case of Google Map API loaded by other plugins or themes, it will prevent and load a blank script (Only removes by user consent)
	 *
	 * @param $tag
	 * @param $handle
	 * @param $src
	 * @return mixed|string
	 * @since 1.7.5
	 */
	public function do_prevent_others_google_maps_tag( $tag, $handle, $src ) {
		if ( preg_match( '/maps\.google/i', $src ) ) {
			if ( $handle !== 'wp-gmap-api' ) {
				return '';
			}
		}
		return $tag;
	}
}
