<?php

namespace WGMSRM\Classes;

class Database {

	private $_version = '1.0';

	public function __construct() {
		 $existing_db_version = esc_html( get_option( '_wgm_db_version', 'none' ) );
		if ( $existing_db_version === 'none' ) {
			$this->install();
		}
	}

	public function install() {
		 $this->createMarkersTable();
		$this->createMarkersIconTable();
		update_option( '_wgm_db_version', $this->_version );
	}

	public function createMarkersTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}
		$schema = "CREATE TABLE `{$wpdb->prefix}wgm_markers` (
					  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `map_id` int(11) NOT NULL,
					  `marker_name` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
					  `marker_desc` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
					  `icon` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
					  `address` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
					  `lat_lng` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
					  `have_marker_link` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
					  `marker_link` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
					  `marker_link_new_tab` tinyint(4) DEFAULT NULL,
					  `show_desc_by_default` tinyint(4) DEFAULT NULL,
					  `created_at` datetime DEFAULT NULL,
					  `created_by` bigint(20) unsigned NOT NULL,
					  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					  `updated_by` bigint(20) unsigned NOT NULL,
					  PRIMARY KEY (`id`)
					) $charset_collate";
		maybe_create_table( $wpdb->prefix . 'wgm_markers', $schema );
	}

	public function createMarkersIconTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}
		$schema = "CREATE TABLE `{$wpdb->prefix}wgm_icons` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `type` varchar(100) DEFAULT NULL,
                      `title` varchar(500) DEFAULT NULL,
                      `desc` varchar(500) DEFAULT NULL,
                      `file_name` varchar(500) DEFAULT NULL,
                      PRIMARY KEY (`id`)
                    ) $charset_collate";
		maybe_create_table( $wpdb->prefix . 'wgm_icons', $schema );
	}
}
