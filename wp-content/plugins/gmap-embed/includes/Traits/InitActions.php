<?php

namespace WGMSRM\Traits;

use WGMSRM\Classes\Migration;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait InitActions: Init action hooks defined here
 */
trait InitActions {

	/**
	 * Loading text-domain
	 *
	 * @since 3.0.0
	 */
	public function i18n() {
		load_plugin_textdomain( 'gmap-embed', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Review system action link hooks
	 *
	 * @since 1.7.1
	 */
	public function review_system_hooks() {
		// Review system hooks.
		add_action( 'gmap_embed_review_already_did', array( $this, 'review_already_did' ) );
		add_action( 'gmap_embed_review_later', array( $this, 'review_later' ) );
		if ( isset( $_GET['plugin'] ) and (isset( $_GET['dismiss'] ) or isset( $_GET['later'] )) ) {
			$plugin = sanitize_text_field( wp_unslash( $_GET['plugin'] ) );
			if ( $plugin === $this->plugin_slug ) {
				if ( isset( $_GET['dismiss'] ) && sanitize_text_field( wp_unslash( $_GET['dismiss'] ) ) == 1 ) {
					do_action( 'gmap_embed_review_already_did' );
				}
				if ( isset( $_GET['later'] ) && sanitize_text_field( wp_unslash( $_GET['later'] ) ) == 1 ) {
					do_action( 'gmap_embed_review_later' );
				}
				wp_safe_redirect( $this->redirect_to() );
				exit;
			}
		}
	}

	/**
	 * Doing some code in init hook
	 *
	 * @since 1.7.1
	 */
	public function do_init_actions() {
		$this->i18n();
		$this->review_system_hooks();
		$this->register_post_type();
		new Migration();
	}

	/**
	 * Registering wpgmapembed post type
	 *
	 * @since 1.7.1
	 */
	public function register_post_type() {
		// Register Post Types.
		register_post_type(
			'wpgmapembed',
			array(
				'labels'      => array(
					'name'          => __( 'Google Maps' ),
					'singular_name' => __( 'Map' ),
				),
				'public'      => false,
				'has_archive' => false,
			)
		);
	}
}
