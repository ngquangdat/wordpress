<?php

namespace WGMSRM\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait Notice
 */
trait Notice {

	/**
	 * Generate Different types of notices
	 */
	public function gmap_embed_notice_generate() {
		 // Review admin notice
		$this->gmap_embed_generate_admin_review_notice();

		// No API key admin notice
		$this->wgm_no_api_key_added_notice();
	}


	public function wgm_no_api_key_added_notice() {
		if ( get_option( 'wpgmap_api_key', 'none' ) === 'none' ) {
			?>
			<div class="notice notice-info is-dismissible" style="border: 2px #db1c1c solid;">
				<p>
				   <?php
					echo sprintf(
						__( 'You must generate your own <b>API Key</b> to use Google Map, Please go to <a href="%1$s" style="font-weight: bold;text-decoration: none;">WP Google Map</a><i class="dashicons  dashicons-arrow-right-alt2"></i><a href="%2$s" style="text-decoration: none;font-weight: bold;">Quick Setup</a> to get your API key. <a href="%3$s" target="_blank" style="text-decoration: none;font-weight: bold;"><i class="dashicons  dashicons-external"></i>Video Tutorial</a>, <i class="dashicons  dashicons-external"></i><a href="%4$s" target="_blank" style="text-decoration: none;font-weight: bold;">Documentation</a>', 'gmap-embed' ),
						esc_url( admin_url( 'admin.php?page=wpgmapembed' ) ),
						esc_url( admin_url( 'admin.php?page=wgm_setup_wizard' ) ),
						esc_url( 'https://youtu.be/9KZOUJ9Gdv8?t=22' ),
						esc_url( 'https://wpgooglemap.com/docs-category/installation?utm_source=wp_admin&utm_medium=admin_link&utm_campaign=header_notice' )
					);
					?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Do something on review already button click
	 */
	public function review_already_did() {
		update_option( 'gmap_embed_is_review_done', true );
	}

	/**
	 * Do something on review later button click
	 */
	public function review_later() {
		update_option( 'gmap_embed_is_review_snoozed', true );
		update_option( 'gmap_embed_review_snoozed_at', time() );
	}

	/**
	 * Redirect to URL generate
	 *
	 * @return string
	 */
	public function redirect_to() {
		 $request_uri = wp_parse_url( sanitize_text_field(  $_SERVER['REQUEST_URI']  ), PHP_URL_PATH );
		$query_string = wp_parse_url( sanitize_text_field(  $_SERVER['REQUEST_URI']  ), PHP_URL_QUERY );
		parse_str( $query_string, $current_url );
		$unset_array = array( 'dismiss', 'plugin', '_wpnonce', 'later' );

		foreach ( $unset_array as $value ) {
			if ( isset( $current_url[ $value ] ) ) {
				unset( $current_url[ $value ] );
			}
		}

		$current_url  = http_build_query( $current_url );
		$redirect_url = $request_uri . '?' . $current_url;
		return esc_url( $redirect_url );
	}

	/**
	 * Generate admin panel review notice for WP Google Map plugin
	 */
	public function gmap_embed_generate_admin_review_notice() {
		 $activation_time  = get_option( 'gmap_embed_activation_time', false );
		$is_review_snoozed = get_option( 'gmap_embed_is_review_snoozed' );
		$snoozed_time      = get_option( 'gmap_embed_review_snoozed_at' );

		// How may day's passed after activation
		$seconds_diff = time() - $activation_time;
		$passed_days  = ( $seconds_diff / 3600 ) / 24;

		// Snoozed how many day's before
		$seconds_diff   = time() - $snoozed_time;
		$snoozed_before = ( $seconds_diff / 3600 ) / 24;
		$is_review_done = get_option( 'gmap_embed_is_review_done' );

		/**
		 *
		 * Review section will shows based on following cases
		 * Case 1: Passed three(3) days and not snoozed
		 * Case 2: Snoozed before 7 day's
		 */
		if ( $is_review_done == false && ( ( $passed_days >= 3 && $is_review_snoozed == false ) || ( $is_review_snoozed == true && $snoozed_before >= 7 ) ) ) {
			$scheme       = ( wp_parse_url( sanitize_text_field(  $_SERVER['REQUEST_URI']  ), PHP_URL_QUERY ) ) ? '&' : '?';
			$url          = esc_url( $_SERVER['REQUEST_URI'] . $scheme );
			$dismiss_link = add_query_arg(
				array(
					'plugin'   => 'gmap-embed',
					'dismiss'  => true,
					'_wpnonce' => wp_create_nonce(),
				),
				$url
			);
			$later_link   = add_query_arg(
				array(
					'plugin'   => 'gmap-embed',
					'later'    => true,
					'_wpnonce' => wp_create_nonce(),
				),
				$url
			);
			?>
			<div class="gmap_embed_review_section notice notice-success">
				<img src="<?php echo esc_url( WGM_PLUGIN_URL . 'admin/assets/images/gmap_embed_logo.jpg' ); ?>"
					 width="60" style="float: left;margin: 9px 9px 0 5px !important"/>
				<p>
				<?php
				_e(
					"<span style='color:green;'>We hope you're" . ' enjoying of using <b style="color:#007cba">WP Google Map</b> plugin.
Could you please give us a BIG favour and give it a 5-star rating on WordPress to help us spread the word and boost our motivation!</span>
',
					'gmap-embed'
				);
				?>
					</p>
				<ul>
					<li style="display: inline;margin-right:15px;"><a style="text-decoration: none"
																	  href="<?php echo esc_url( 'https://wpgooglemap.com/wp-review-forum?utm_source=wp_admin&utm_medium=admin_link&utm_campaign=review_notice' ); ?>"
																	  target="_blank"><span
									class="dashicons dashicons-external"></span> Ok, you deserve it!</a></li>
					<li style="display: inline;margin-right:15px;"><a style="text-decoration: none"
																	  href="<?php echo esc_url( $dismiss_link ); ?>"><span
									class="dashicons dashicons-smiley"></span> I already did</a></li>
					<li style="display: inline;margin-right:15px;"><a style="text-decoration: none"
																	  href="<?php echo esc_url( $later_link ); ?>"><span
									class="dashicons dashicons-calendar-alt"></span> Maybe Later</a></li>
					<li style="display: inline;margin-right:15px;"><a style="text-decoration: none"
																	  href="<?php echo esc_url( 'https://wpgooglemap.com/wp-support-forum?utm_source=wp_admin&utm_medium=admin_link&utm_campaign=review_notice' ); ?>"
																	  target="_blank"><span
									class="dashicons dashicons-external"></span> I need help</a></li>
					<li style="display: inline;margin-right:15px;"><a style="text-decoration: none"
																	  href="<?php echo esc_url( $dismiss_link ); ?>"><span
									class="dashicons dashicons-dismiss"></span> Never show again</a></li>
				</ul>
			</div>
			<?php
		}
	}
}
