<?php

namespace WGMSRM\Classes;

use WP_Query;

class Migration {

	private $_multiple_marker_migration;
	private $_p_v_m;

	public function __construct() {
		 $this->_multiple_marker_migration = esc_html( get_option( '_wgm_migration_multiple_marker' ) );
		$this->_p_v_m                      = esc_html( get_option( '_wgm_p_v_migration' ) );
		$this->run_migration();
	}

	public function run_migration() {
		/*Multiple marker migration*/
		if ( $this->_multiple_marker_migration !== 'Y' ) {
			$this->do_multiple_marker_migration();
			update_option( '_wgm_migration_multiple_marker', 'Y' );
		}
		if ( $this->_p_v_m !== 'Y' ) {
			$this->do_p_v_m();
			update_option( '_wgm_p_v_migration', 'Y' );
		}
	}

	public function do_multiple_marker_migration() {
		global $wpdb;
		$args = array(
			'post_type'      => 'wpgmapembed',
			'posts_per_page' => -1,
			'post_status'    => 'draft',
		);

		$maps_list = new WP_Query( $args );
		while ( $maps_list->have_posts() ) {
			$maps_list->the_post();
			$map_id          = intval( get_the_ID() );
			$map_marker_data = array(
				'map_id'               => $map_id,
				'marker_desc'          => get_post_meta( $map_id, 'wpgmap_map_address', true ),
				'icon'                 => strlen( trim( get_post_meta( $map_id, 'wpgmap_marker_icon', true ) ) ) > 0 ? get_post_meta( $map_id, 'wpgmap_marker_icon', true ) : esc_url( 'https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png' ),
				'address'              => wp_strip_all_tags( html_entity_decode( get_post_meta( $map_id, 'wpgmap_map_address', true ) ) ),
				'lat_lng'              => get_post_meta( $map_id, 'wpgmap_latlng', true ),
				'show_desc_by_default' => get_post_meta( $map_id, 'wpgmap_show_infowindow', true ),
			);

			$defaults                = array(
				'map_id'               => 0,
				'marker_name'          => null,
				'marker_desc'          => null,
				'icon'                 => null,
				'address'              => null,
				'lat_lng'              => null,
				'have_marker_link'     => 0,
				'marker_link'          => null,
				'marker_link_new_tab'  => 0,
				'show_desc_by_default' => 0,
				'created_at'           => current_time( 'mysql' ),
				'created_by'           => get_current_user_id(),
				'updated_at'           => current_time( 'mysql' ),
				'updated_by'           => get_current_user_id(),
			);
			$wp_gmap_marker_data     = wp_parse_args( $map_marker_data, $defaults );
			$is_marker_already_exist = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$wpdb->prefix}wgm_markers WHERE map_id='%d'", intval( $map_id ) ) );
			if ( $is_marker_already_exist == 0 ) {
				$wpdb->insert(
					$wpdb->prefix . 'wgm_markers',
					$wp_gmap_marker_data,
					array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
						'%s',
						'%d',
						'%s',
						'%d',
					)
				);
			}

			// Migrate corresponding marker icons
			$map_icon_data = array(
				'file_name' => esc_url( $map_marker_data['icon'] ),
			);

			$is_marker_icon_already_exist = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$wpdb->prefix}wgm_icons WHERE file_name='%s'", esc_url( $map_marker_data['icon'] ) ) );
			if ( $is_marker_icon_already_exist == 0 ) {
				$defaults            = array(
					'type'      => 'uploaded_marker_icon',
					'title'     => '',
					'desc'      => '',
					'file_name' => '',
				);
				$wp_gmap_marker_icon = wp_parse_args( $map_icon_data, $defaults );
				$wpdb->insert(
					$wpdb->prefix . 'wgm_icons',
					$wp_gmap_marker_icon,
					array(
						'%s',
						'%s',
						'%s',
						'%s',
					)
				);
			}
		}
	}

	public function do_p_v_m() {
		$status = ( gmap_embed_no_of_post() > 1 || strlen( trim( esc_html( get_option( 'wpgmapembed_license' ) ) ) ) === 32 );
		if ( $status ) {
			update_option( '_wgm_is_p_v', 'Y' );
		} else {
			update_option( '_wgm_is_p_v', 'N' );
		}
	}
}
