<?php

namespace WGMSRM\Classes;

use WGMSRM\Traits\ActionLinks;
use WGMSRM\Traits\ActivationHooks;
use WGMSRM\Traits\AdminInitActions;
use WGMSRM\Traits\AssetHandler;
use WGMSRM\Traits\CommonFunctions;
use WGMSRM\Traits\Filters;
use WGMSRM\Traits\InitActions;
use WGMSRM\Traits\MapCRUD;
use WGMSRM\Traits\MarkerCRUD;
use WGMSRM\Traits\MediaButtons;
use WGMSRM\Traits\Menu;
use WGMSRM\Traits\Notice;
use WGMSRM\Traits\PluginsLoadedActions;
use WGMSRM\Traits\Settings;
use WGMSRM\Traits\SetupWizard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bootstrap {

	use Settings, MapCRUD, Notice, Menu, AssetHandler, CommonFunctions, ActionLinks, PluginsLoadedActions, ActivationHooks, InitActions, SetupWizard, Filters, MarkerCRUD, AdminInitActions, MediaButtons;

	private static $instance = null;
	private $plugin_name     = 'WP Google Map';
	private $plugin_slug     = 'gmap-embed';
	public $wpgmap_api_key   = 'AIzaSyD79uz_fsapIldhWBl0NqYHHGBWkxlabro';

	public function __construct() {
		 $this->wpgmap_api_key = esc_html( get_option( 'wpgmap_api_key' ) );
		$this->register_hooks();
		$this->load_dependencies();

	}

	/**
	 * Generating instance
	 *
	 * @return Bootstrap|null
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register all hooks
	 */
	private function register_hooks() {
		add_action( 'init', array( $this, 'do_init_actions' ) );
		add_action( 'plugins_loaded', array( $this, 'wpgmap_do_after_plugins_loaded' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_action( 'activated_plugin', array( $this, 'wpgmap_do_after_activation' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'gmap_front_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_gmap_scripts' ) );
		add_action( 'admin_menu', array( $this, 'gmap_create_menu' ) );
		add_action( 'admin_init', array( $this, 'do_admin_init_actions' ) );
		add_action( 'admin_init', array( $this, 'gmapsrm_settings' ) );
		add_action( 'admin_notices', array( $this, 'gmap_embed_notice_generate' ) );
		add_filter( 'plugin_action_links_gmap-embed/srm_gmap_embed.php', array( $this, 'gmap_srm_settings_link' ), 10, 4 );
		add_action( 'media_buttons', array( $this, 'add_wp_google_map_media_button' ) );
		add_action( 'admin_footer', array( $this, 'wp_google_map_media_button_content' ) );
		$this->ajax_hooks();

		/** To prevent others plugin loading Google Map API(with checking user consent) */
		if ( get_option( '_wgm_prevent_other_plugin_theme_api_load' ) === 'Y' ) {
			add_filter( 'script_loader_tag', array( $this, 'do_prevent_others_google_maps_tag' ), 10000000, 3 );
		}
	}

	private function ajax_hooks() {
		add_action( 'wp_ajax_wpgmapembed_save_map_data', array( $this, 'save_wpgmapembed_data' ) );
		add_action( 'wp_ajax_wpgmapembed_load_map_data', array( $this, 'load_wpgmapembed_list' ) );
		add_action( 'wp_ajax_wpgmapembed_popup_load_map_data', array( $this, 'load_popup_wpgmapembed_list' ) );
		add_action( 'wp_ajax_wpgmapembed_get_wpgmap_data', array( $this, 'get_wpgmapembed_data' ) );
		add_action( 'wp_ajax_wpgmapembed_remove_wpgmap', array( $this, 'remove_wpgmapembed_data' ) );
		add_action( 'wp_ajax_wpgmapembed_save_setup_wizard', array( $this, 'wpgmap_save_setup_wizard' ) );
		add_action( 'wp_ajax_wgm_get_all_maps', array( $this, 'wgm_get_all_maps' ) );

		// Marker related.
		add_action( 'wp_ajax_wpgmapembed_save_map_markers', array( $this, 'save_map_marker' ) );
		add_action( 'wp_ajax_wpgmapembed_update_map_markers', array( $this, 'update_map_marker' ) );
		add_action( 'wp_ajax_wpgmapembed_get_marker_icons', array( $this, 'get_marker_icons' ) );
		add_action( 'wp_ajax_wpgmapembed_save_marker_icon', array( $this, 'save_marker_icon' ) );
		add_action( 'wp_ajax_wpgmapembed_get_markers_by_map_id', array( $this, 'get_markers_by_map_id' ) );
		add_action( 'wp_ajax_wpgmapembed_p_get_markers_by_map_id', array( $this, 'p_get_markers_by_map_id' ) );
		add_action( 'wp_ajax_nopriv_wpgmapembed_p_get_markers_by_map_id', array( $this, 'p_get_markers_by_map_id' ) );
		add_action( 'wp_ajax_wgm_get_markers_by_map_id', array( $this, 'wgm_get_markers_by_map_id_for_dt' ) );
		add_action( 'wp_ajax_wpgmapembed_delete_marker', array( $this, 'delete_marker' ) );
		add_action( 'wp_ajax_wpgmapembed_get_marker_data_by_marker_id', array( $this, 'get_marker_data_by_marker_id' ) );
	}

	public function load_dependencies() {
		// Define Shortcode.
		require_once WGM_PLUGIN_PATH . '/public/includes/shortcodes.php';
	}

	public function register_widget() {
		 register_widget( 'WGMSRM\\Classes\\srmgmap_widget' );
	}
}
