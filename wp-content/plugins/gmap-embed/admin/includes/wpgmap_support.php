<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} ?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Support', 'gmap-embed' ); ?></h1>
	<?php
	if ( ! _wgm_is_premium() ) {
		echo '<a target="_blank" href="' . esc_url( 'https://wpgooglemap.com/pricing?utm_source=admin_support&utm_medium=admin_link&utm_campaign=header_menu' ) . '" class="button wgm_btn" style="float:right;width:auto;padding: 5px 7px;font-size: 11px;margin-left:5px;"><i style="line-height: 25px;" class="dashicons dashicons-star-filled"></i> Upgrade ($19 only)</a>';
	}


	echo '<a target="_blank" href="' . esc_url( 'https://tawk.to/chat/6083e29962662a09efc1acd5/1f41iqarp' ) . '" class="button wgm_btn" style="float:right;width:auto;padding: 5px 7px;font-size: 11px;margin-right:5px;background-color: #cb5757 !important;color: white !important;"><i style="line-height: 28px;" class="dashicons dashicons-format-chat"></i> ' . __( 'LIVE Chat', 'gmap-embed' ) . '</a>';
	echo '<a href="' . esc_url( admin_url( 'admin.php?page=wpgmapembed-support' ) ) . '" class="button wgm_btn" style="float:right;width:auto;padding: 5px 7px;font-size: 11px;margin-right:5px;"><i style="line-height: 25px;" class="dashicons  dashicons-editor-help"></i> ' . __( 'Documentation', 'gmap-embed' ) . '</a>';
	?>
	<hr class="wp-header-end">

	<div class="wgm_admin_support_wrapper">

		<div class="wgm_admin_block">
			<header class="wgm_admin_block_header">
				<div class="wgm_admin_block_header_icon">
					<i class="fas fa-file-alt"></i>
				</div>
				<h4 class="wgm_admin_title">Installation</h4>
			</header>
			<div class="wgm_admin_block_content">
				<div class="wgm_gmap_instructions">
					<?php
					require_once WGM_PLUGIN_PATH . 'admin/includes/wpgmap_installation_manuals.php';
					?>
				</div>
				<a href="<?php echo esc_url( 'https://wpgooglemap.com/docs-category/installation?utm_source=admin_support&utm_medium=admin_link&utm_campaign=support_installation' ); ?>"
				   class="wgm_button"
				   target="_blank">View All</a>
			</div>
		</div>

		<div class="wgm_admin_block">
			<header class="wgm_admin_block_header">
				<div class="wgm_admin_block_header_icon">
					<i class="fas fa-file-alt"></i>
				</div>
				<h4 class="wgm_admin_title">How to use</h4>
			</header>
			<div class="wgm_admin_block_content">
				<div class="wgm_gmap_instructions">
					<?php
					require WGM_PLUGIN_PATH . 'admin/includes/wpgmap_how_to_use_manuals.php';
					?>
				</div>
				<a href="<?php echo esc_url( 'https://wpgooglemap.com/docs-category/customization?utm_source=admin_support&utm_medium=admin_link&utm_campaign=support_how_to_use' ); ?>"
				   class="wgm_button"
				   target="_blank">View All</a>
			</div>
		</div>

		<div class="wgm_admin_block">
			<header class="wgm_admin_block_header">
				<div class="wgm_admin_block_header_icon">
					<i class="fas fa-file-alt"></i>
				</div>
				<h4 class="wgm_admin_title">Troubleshooting</h4>
			</header>
			<div class="wgm_admin_block_content">
				<div class="wgm_gmap_instructions">
					<?php
					require WGM_PLUGIN_PATH . 'admin/includes/wpgmap_troubleshooting_manuals.php';
					?>
				</div>
				<a href="<?php echo esc_url( 'https://wpgooglemap.com/docs-category/troubleshooting?utm_source=admin_support&utm_medium=admin_link&utm_campaign=support_troubleshooting' ); ?>"
				   class="wgm_button"
				   target="_blank">View All</a>
			</div>
		</div>

	</div>

	<div class="wgm_admin_support_wrapper" style="margin-top: 50px;">
		<div class="wgm_admin_block">
			<header class="wgm_admin_block_header">
				<div class="wgm_admin_block_header_icon">
					<i class="fas fa-user-plus"></i>
				</div>
				<h4 class="wgm_admin_title">Contribute to WP Google Map</h4>
			</header>
			<div class="wgm_admin_block_content">
				<p>You can contribute to make WP Google Map better reporting bugs, creating issues at <a
							href="<?php echo esc_url( 'https://github.com/milonfci/gmap-embed-lite/issues/new' ); ?>"
							target="_blank">Github.</a> We are looking forward for your feedback.</p>
				<a href="https://github.com/milonfci/gmap-embed-lite/issues/new"
				   class="wgm_button" target="_blank">Report an issue</a>
			</div>
		</div>
		<div class="wgm_admin_block">
			<header class="wgm_admin_block_header">
				<div class="wgm_admin_block_header_icon">
					<i class="fas fa-headset"></i>
				</div>
				<h4 class="wgm_admin_title">Need Help?</h4>
			</header>
			<div class="wgm_admin_block_content">

				<p>Stuck with something? Get help from the community on <a
							href="<?php echo esc_url( 'https://wordpress.org/support/plugin/gmap-embed/#new-topic-0' ); ?>"
							target="_blank">WordPress.org Forum</a> or <a
							href="<?php echo esc_url( 'https://www.facebook.com/Google-Map-SRM-100856491527309' ); ?>"
							target="_blank">Facebook
						Community.</a> In case of emergency, initiate a live chat at <a
							href="<?php echo esc_url( 'https://wpgooglemap.com?utm_source=admin_support&utm_medium=admin_link&utm_campaign=support_need_help' ); ?>"
							target="_blank">WP Google Map website.</a></p>
				<a href="<?php echo esc_url( 'https://wpgooglemap.com/contact-us?utm_source=admin_support&utm_medium=admin_link&utm_campaign=support_need_help' ); ?>"
				   class="wgm_button" target="_blank">Get
					Support</a>

			</div>
		</div>

		<div class="wgm_admin_block">
			<header class="wgm_admin_block_header">
				<div class="wgm_admin_block_header_icon">
					<i class="far fa-heart"></i>
				</div>
				<h4 class="wgm_admin_title">Show your Love</h4>
			</header>
			<div class="wgm_admin_block_content">
				<p>We love to have you in WP Google Map family. We are making it more awesome
					everyday. Take your 2 minutes to review the plugin and spread the love to
					encourage us to keep it going.</p>

				<a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/gmap-embed/reviews/?filter=5#new-post' ); ?>"
				   class="review-flexia wgm_button" target="_blank">Leave a Review</a>
			</div>
		</div>
	</div>
	<div class="wgm_admin_support_wrapper" style="margin-top: 50px;">
		<iframe width="1904" height="768" src="<?php echo esc_url( 'https://www.youtube.com/embed/9KZOUJ9Gdv8' ); ?>"
				title="YouTube video player"
				frameborder="0"
				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen></iframe>
	</div>
</div>
