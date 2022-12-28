<?php
namespace WGMSRM\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Trait CommonFunctions
 */
trait CommonFunctions {

	/**
	 * Update post meta
	 *
	 * @param $post_id
	 * @param $field_name
	 * @param string     $value
	 */
	public function wgm_update_post_meta( $post_id, $field_name, $value = '' ) {
		if ( ! get_post_meta( $post_id, $field_name ) ) {
			add_post_meta( $post_id, $field_name, $value );
		} else {
			update_post_meta( $post_id, $field_name, $value );
		}
	}
}
