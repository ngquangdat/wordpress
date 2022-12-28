<?php

namespace WGMSRM\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait AssetHandler: enqueue, dequeue assets
 */
trait AssetHandler {


	/**
	 * @return array
	 */
	public function getLocalizedScripts() {
		 // Setup wizard validation data
		$wpgmap_setup_validation_data = array(
			'apikey'           => __( 'Please enter a valid API key', 'gmap-embed' ),
			'language'         => __( 'Please select a language', 'gmap-embed' ),
			'regionalarea'     => __( 'Please select a regional area', 'gmap-embed' ),
			'licencekey'       => __( 'Invalid license key', 'gmap-embed' ),
			'success'          => __( 'Successfully installed.', 'gmap-embed' ),
			'failed_to_finish' => __( 'Something went wrong, please reload and try again', 'gmap-embed' ),
		);

		// Setup wizard get api key modal content
		$wpgmap_setup_api_key_modal_data = array(
			'faq_title'      => __( 'Help Manual', 'gmap-embed' ),
			'faq_item_title' => __( ' Click here to see Help Manual on how to get API key', 'gmap-embed' ),
			'faq_item_url'   => esc_url( 'https://wpgooglemap.com/documentation/wp-google-map-quick-installation?utm_source=admin_setup_wizard&utm_medium=admin_link&utm_campaign=setup_wizard' ),
			'video_title'    => __( 'Video Tutorial', 'gmap-embed' ),
			'video_url'      => esc_url( '//www.youtube.com/embed/m-jAsxG0zuk' ),
			'api_key_title'  => __( 'Get API Key', 'gmap-embed' ),
			'api_key_url'    => esc_url( '//console.developers.google.com/flows/enableapi?apiid=maps_backend,places_backend,geolocation,geocoding_backend,directions_backend&keyType=CLIENT_SIDE&reusekey=true' ),
		);

		// what we collect modal content
		$wgm_wwc_msg = array(
			'title' => __( 'What we collect?', 'gmap-embed' ),
			'desc'  => __( 'We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress & PHP version, plugins & themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, we promise.', 'gmap-embed' ),
		);
		$locales     = array(
			'dt'          => array(
				'no_map_created'    => __( 'No map created yet, please click on Add New to create your map.', 'gmap-embed' ),
				'no_marker_created' => __( 'No marker created yet.', 'gmap-embed' ),
			),
			'sweet_alert' => array(
				'oops'                    => __( 'Opps...', 'gmap-embed' ),
				'notice_unlimited_maps'   => sprintf( __( 'You need to upgrade to the <a target="_blank" href="%s">Premium</a> Version to create <b>Unlimited Maps</b>.', 'gmap-embed' ), esc_url( 'https://wpgooglemap.com/pricing?utm_source=admin_map_crud&utm_medium=admin_link&utm_campaign=add_new_map' ) ),
				'notice_unlimited_marker' => sprintf( __( 'You need to upgrade to the <a target="_blank" href="%s">Premium</a> Version to create <b>Unlimited Markers</b>.', 'gmap-embed' ), esc_url( 'https://wpgooglemap.com/pricing?utm_source=admin_map_crud&utm_medium=admin_link&utm_campaign=add_new_marker' ) ),
				'notice_to_use_feature'   => sprintf( __( 'You need to upgrade to the <a target="_blank" href="%s">Premium</a> Version to use <b>this feature</b>.', 'gmap-embed' ), esc_url( 'https://wpgooglemap.com/pricing?utm_source=admin_map_crud&utm_medium=admin_link&utm_campaign=new_feature_popup' ) ),
			),
		);

		$wgm_localized = array(
			// Common data
			'is_premium_user' => _wgm_is_premium(),
			'get_p_v_url'     => esc_url( 'https://wpgooglemap.com/pricing?utm_source=admin_setup_wizard&utm_medium=admin_link&utm_campaign=get_license' ),
			'site_url'        => site_url(),
			'setup_wizard'    => array(
				'setup_validation_msg' => $wpgmap_setup_validation_data,
				'setup_api_key_modal'  => $wpgmap_setup_api_key_modal_data,
				'wgm_wwc_msg'          => $wgm_wwc_msg,
			),
			'locales'         => $locales,
			'ajax_nonce'      => wp_create_nonce( 'ajax_nonce' ),
			'c_s_nonce'       => wp_create_nonce( 'c_s_nonce' ),
		);

		if ( isset( $_GET['tag'] ) and sanitize_text_field( wp_unslash( $_GET['tag'] ) ) == 'edit' ) {

			$map_id                      = isset( $_GET['id'] ) ? intval( sanitize_text_field( wp_unslash( $_GET['id'] ) ) ) : 0;
			$current_map_marker_lat_lng  = explode( ',', get_post_meta( $map_id, 'wpgmap_latlng', true ) );
			$current_map_marker_lat      = isset( $current_map_marker_lat_lng[0] ) ? $current_map_marker_lat_lng[0] : 40.73359922990751;
			$current_map_marker_lng      = isset( $current_map_marker_lat_lng[1] ) ? $current_map_marker_lat_lng[1] : -74.02791395625002;
			$wgm_object                  = array(
				'current_map_marker_lat' => esc_html( $current_map_marker_lat ),
				'current_map_marker_lng' => esc_html( $current_map_marker_lng ),
				'map_id'                 => intval( $map_id ),
			);
			$wgm_localized['wgm_object'] = $wgm_object;
		}
		return $wgm_localized;
	}

	public function getPluginStatus() {
		 // API status variable ->debugging purpose
		return wp_json_encode(
			array(
				'l_api'     => esc_html( get_option( '_wgm_load_map_api_condition', 'always' ) ),
				'p_api'     => esc_html( get_option( '_wgm_prevent_other_plugin_theme_api_load', 'N' ) ),
				'i_p'       => _wgm_is_premium(),
				'd_f_s_c'   => esc_html( get_option( '_wgm_disable_full_screen_control', 'N' ) ),
				'd_s_v'     => esc_html( get_option( '_wgm_disable_street_view', 'N' ) ),
				'd_z_c'     => esc_html( get_option( '_wgm_disable_zoom_control', 'N' ) ),
				'd_p_c'     => esc_html( get_option( '_wgm_disable_pan_control', 'N' ) ),
				'd_m_t_c'   => esc_html( get_option( '_wgm_disable_map_type_control', 'N' ) ),
				'd_m_w_z'   => esc_html( get_option( '_wgm_disable_mouse_wheel_zoom', 'N' ) ),
				'd_m_d'     => esc_html( get_option( '_wgm_disable_mouse_dragging', 'N' ) ),
				'd_m_d_c_z' => esc_html( get_option( '_wgm_disable_mouse_double_click_zooming', 'N' ) ),
				'e_d_f_a_c' => esc_html( get_option( '_wgm_enable_direction_form_auto_complete', 'N' ) ),
			)
		);
	}

	/**
	 * Register common scripts
	 */
	private function registerCommonScripts() {
		$srm_gmap_lng    = esc_html( get_option( 'srm_gmap_lng', 'en' ) );
		$srm_gmap_region = esc_html( get_option( 'srm_gmap_region', 'US' ) );
		wp_register_script( 'wp-gmap-api', 'https://maps.google.com/maps/api/js?key=' . $this->wpgmap_api_key . '&libraries=places&language=' . $srm_gmap_lng . '&region=' . $srm_gmap_region, array( 'jquery' ) );
	}

	/**
	 * To enqueue CSS & JS for frontend
	 */
	public function gmap_front_enqueue_scripts() {
		// Register common scripts (includes: google maps api)
		$this->registerCommonScripts();

		// Based on user defined condition, enqueue Google Map API script
		if ( in_array( esc_html( get_option( '_wgm_load_map_api_condition', 'always' ) ), array( 'where-required', 'always', 'only-front-end' ) ) ) {
			wp_enqueue_script( 'wp-gmap-api' );
		}

		// Custom JS script and Plugin status including
		$wgm_settings_status = $this->getPluginStatus();
		$custom_js_scripts   = get_option( 'wpgmap_s_custom_js' );
		$custom_js_scripts  .= "\nvar wgm_status = $wgm_settings_status;";
		$custom_js_ca_data_enclosed = "/* <![CDATA[ */\n".$custom_js_scripts."\n/* ]]> */";
		wp_add_inline_script( 'wp-gmap-api', $custom_js_ca_data_enclosed );

		// Custom CSS style including
		wp_enqueue_style( 'wp-gmap-embed-front-css', WGM_PLUGIN_URL . 'public/assets/css/front_custom_style.css', array(), filemtime( WGM_PLUGIN_PATH . 'public/assets/css/front_custom_style.css' ) );
		$custom_css_styles = get_option( 'wpgmap_s_custom_css' );
		if ( strlen( $custom_css_styles ) !== 0 ) {
			wp_add_inline_style( 'wp-gmap-embed-front-css', "$custom_css_styles" );
		}
	}

	/**
	 * To enqueue scripts for admin-panel
	 */
	function enqueue_admin_gmap_scripts() {
		 $page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		global $pagenow;
		if ( $pagenow === 'post.php' || $pagenow === 'post-new.php' || ( $page == 'wpgmapembed' || $page === 'wpgmapembed-settings' || $page === 'wpgmapembed-new' || $page === 'wgm_setup_wizard' || $page === 'wpgmapembed-support' ) ) {

			// Registering common scripts (Included: Google API)
			$this->registerCommonScripts();

			// Including Google Map API for only New Map and Edit Map page
			if ( in_array( esc_html( get_option( '_wgm_load_map_api_condition', 'always' ) ), array( 'where-required', 'always', 'only-backend-end' ) ) && ( $page === 'wpgmapembed' or $page === 'wpgmapembed-new' ) ) {
				wp_enqueue_script( 'wp-gmap-api' );
			}

			/** Common assets */
			wp_enqueue_script( 'wp-gmap-common-js', WGM_PLUGIN_URL . 'admin/assets/js/common.js', array(), filemtime( WGM_PLUGIN_PATH . 'admin/assets/js/common.js' ), false );
			$wgm_localized = $this->getLocalizedScripts();
			wp_localize_script( 'wp-gmap-common-js', 'wgm_l', $wgm_localized );
			wp_enqueue_style( 'wp-gmap-embed-css', WGM_PLUGIN_URL . 'admin/assets/css/wp-gmap-style.css', array(), filemtime( WGM_PLUGIN_PATH . 'admin/assets/css/wp-gmap-style.css' ) );
			// Font awesome
			wp_enqueue_style( 'wp-gmap-fontawasome-css', WGM_PLUGIN_URL . 'admin/assets/third-party/font-awesome/5/css/font-awesome.css', array(), filemtime( WGM_PLUGIN_PATH . 'admin/assets/third-party/font-awesome/5/css/font-awesome.css' ) );
			// Sweet alert related
			wp_enqueue_style( 'wp-gmap-sweetalert2-css', WGM_PLUGIN_URL . 'admin/assets/third-party/sweetalert2/css/sweetalert2.min.css', array(), filemtime( WGM_PLUGIN_PATH . 'admin/assets/third-party/sweetalert2/css/sweetalert2.min.css' ) );
			wp_enqueue_script( 'wp-gmap-sweetalert2-js', WGM_PLUGIN_URL . 'admin/assets/third-party/sweetalert2/js/sweetalert2.min.js', array(), filemtime( WGM_PLUGIN_PATH . 'admin/assets/third-party/sweetalert2/js/sweetalert2.min.js' ), true );
			// Media upload
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_script( 'wpgmap-media-upload' );
			wp_enqueue_media();
			wp_enqueue_style( 'thickbox' );

			/** Edit and Add Map page */
			if ( $pagenow === 'post.php' || $pagenow === 'post-new.php' || ( $page === 'wpgmapembed' or $page === 'wpgmapembed-new' ) ) {
				wp_enqueue_script( 'wgm-map-curd-js', WGM_PLUGIN_URL . 'admin/assets/js/wgm_map_crud.js', array( 'wp-gmap-common-js' ), filemtime( WGM_PLUGIN_PATH . 'admin/assets/js/wgm_map_crud.js' ), true );
				wp_enqueue_script( 'wp-gmap-markers-js', WGM_PLUGIN_URL . 'admin/assets/js/wgm_marker_crud.js', array( 'wp-gmap-common-js' ), filemtime( WGM_PLUGIN_PATH . 'admin/assets/js/wgm_marker_crud.js' ), true );
				// Datatable
				wp_enqueue_style( 'wgm-datatable-css', WGM_PLUGIN_URL . 'admin/assets/third-party/datatables/css/jquery.dataTables.min.css', array(), filemtime( WGM_PLUGIN_PATH . 'admin/assets/third-party/datatables/css/jquery.dataTables.min.css' ) );
				wp_enqueue_script( 'wgm-datatable-js', WGM_PLUGIN_URL . 'admin/assets/third-party/datatables/js/jquery.dataTables.min.js', array(), filemtime( WGM_PLUGIN_PATH . 'admin/assets/third-party/datatables/js/jquery.dataTables.min.js' ), true );
			}

			/** Setup Wizard */
			if ( $page === 'wgm_setup_wizard' ) {
				wp_enqueue_style( 'wp-gmap-setup-wizard-css', WGM_PLUGIN_URL . 'admin/assets/css/setup_wizard.css', array(), filemtime( WGM_PLUGIN_PATH . '/admin/assets/css/setup_wizard.css' ) );
				wp_enqueue_script( 'wp-gmap-setup-wizard-js', WGM_PLUGIN_URL . 'admin/assets/js/setup_wizard.js', array(), filemtime( WGM_PLUGIN_PATH . 'admin/assets/js/setup_wizard.js' ), true );
			}
		}
	}
}
