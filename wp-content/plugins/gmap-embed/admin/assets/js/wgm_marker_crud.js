(function ($) {
	$(
		function () {
			function generateMarkersListView() {
				$( '#wgm_gmap_marker_list' ).DataTable().ajax.reload();
			}

			/**
			 * Vanish a marker from Map view
			 *
			 * @param type string
			 */
			function wgm_vanish_marker(type = 'new') {
				if (type === 'new') {
					if (wgm_new_marker != null && typeof wgm_new_marker == 'object') {
						wgm_new_marker.setMap( null );
					}
					wgm_new_marker = null;
				} else {
					if (wgm_existing_marker != null && typeof wgm_existing_marker == 'object') {
						wgm_existing_marker.setMap( null );
					}
					wgm_existing_marker = null;
				}
			}

			/* --------------------Marker add form reset---------------------- */
			function wpgmap_reset_marker_add_form() {
				$( '#wpgmap_marker_name,#wpgmap_marker_address,#wpgmap_marker_lat_lng,#wpgmap_marker_link' ).val( '' );
				$( '#wpgmap_marker_link_new_tab' ).prop( 'checked', false );
				$( '#wpgmap_have_marker_link' ).val( "0" ).change();
				$( document.body ).find( '#marker_errors,#marker_success' ).html( '' );
				$( document.body ).find( "#wpgmap_marker_icon_preview" ).attr( 'src', 'https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png' );
				$( document.body ).find( "#wpgmap_marker_icon" ).val( 'https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png' );
				// Reset wp editor content
				tmce_setContent( '', 'wpgmap_marker_desc', 'wpgmap_marker_desc' );
			}

			// Pre-Loading markers list
			// generateMarkersListView();
			// Marker update or save
			$( document.body ).on(
				'click',
				".wpgmap_marker_add,.wpgmap_marker_update",
				function () {
					var is_update       = false, action = 'wpgmapembed_save_map_markers',
					save_update_message = 'Marker saved successfully.', marker_id = 0, map_id = 0;
					is_update           = $( this ).hasClass( 'wpgmap_marker_update' );
					if (is_update) {
						action              = 'wpgmapembed_update_map_markers';
						save_update_message = 'Marker updated successfully.';
						marker_id           = $( this ).attr( 'markerid' );
					}

					map_id = parseInt( $( this ).attr( 'mapid' ) );

					$( '#marker_errors,#marker_success' ).html( '' );
					$( this ).parent().find( '.spinner' ).css( 'visibility', 'visible' );
					var parent                     = $( 'body .wgm_gmap_markers' );
					var wpgmap_marker_link_new_tab = 0;

					// Handling checkboxes
					if (parent.find( "#wpgmap_marker_link_new_tab" ).is( ':checked' ) === true) {
						wpgmap_marker_link_new_tab = 1;
					}

					var wpgmap_marker_name            = parent.find( "#wpgmap_marker_name" ).val();
					var wpgmap_marker_desc            = tmce_getContent( 'wpgmap_marker_desc', 'wpgmap_marker_desc' );
					var wpgmap_marker_icon            = parent.find( "#wpgmap_marker_icon" ).val();
					var wpgmap_marker_lat_lng         = parent.find( "#wpgmap_marker_lat_lng" ).val();
					var wpgmap_marker_address         = parent.find( "#wpgmap_marker_address" ).val();
					var wpgmap_have_marker_link       = parent.find( "#wpgmap_have_marker_link" ).val();
					var wpgmap_marker_link            = parent.find( "#wpgmap_marker_link" ).val();
					var wpgmap_marker_infowindow_show = parent.find( "#wpgmap_marker_infowindow_show" ).val();

					// Handling front-end validation
					var has_error = false;
					var error_msg = [];
					parent.find( "#wpgmap_marker_name" ).removeClass( 'wgm_error' );
					// if (wpgmap_marker_name === '') {
					// parent.find("#wpgmap_marker_name").addClass('wgm_error');
					// has_error = true;
					// error_msg.push('Please input marker name correctly');
					// }
					if (wpgmap_marker_lat_lng === '') {
						parent.find( "#wpgmap_marker_lat_lng" ).addClass( 'wgm_error' );
						has_error = true;
						error_msg.push( 'Please input latitude, longitude correctly.' );
					}

					if (has_error) {
						$( '#marker_errors' ).html( error_msg.join( '<br/>' ) );
						parent.find( '.spinner' ).css( 'visibility', 'hidden' );
						return false;
					}
					var map_markers_data = {
						wpgmap_marker_name: wpgmap_marker_name,
						wpgmap_marker_desc: wpgmap_marker_desc,
						wpgmap_marker_icon: wpgmap_marker_icon,
						wpgmap_marker_lat_lng: wpgmap_marker_lat_lng,
						wpgmap_marker_address: wpgmap_marker_address,
						wpgmap_have_marker_link: wpgmap_have_marker_link,
						wpgmap_marker_link: wpgmap_marker_link,
						wpgmap_marker_link_new_tab: wpgmap_marker_link_new_tab,
						wpgmap_marker_infowindow_show: wpgmap_marker_infowindow_show,
						wpgmap_marker_id: marker_id,
						wpgmap_map_id: map_id
					};

					var data = {
						'action': action,
						'map_markers_data': map_markers_data,
						ajax_nonce:wgm_l.ajax_nonce
					};

					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					jQuery.post(
						ajaxurl,
						data,
						function (response) {
							response = JSON.parse( response );

							if (response.responseCode === 0) {
								error_msg.push( response.message );
								// $('#marker_errors').html(error_msg.join('<br/>'));
								Swal.fire(
									{
										icon: 'info',
										title: wgm_l.locales.sweet_alert.oops,
										html: wgm_l.locales.sweet_alert.notice_unlimited_marker + '<br><br><span style="font-size:25px;font-weight:bold;">Only $19 for Lifetime</span><br><br><a target="_blank" href="' + wgm_l.get_p_v_url + '">Upgrade to Pro Version (Only $19 for Lifetime)</a>'
									}
								);
								parent.find( '.spinner' ).css( 'visibility', 'hidden' );
								return false;
							}

							if ( ! is_update) {
								current_map_markers[parseInt( response.marker_id )]     = wgm_new_marker;
								current_map_infowindows[parseInt( response.marker_id )] = wgm_new_marker_infoindow;
							}
							wgm_new_marker                = null;
							wgm_existing_marker_infoindow = null;
							wpgmap_reset_marker_add_form();
							generateMarkersListView();
							parent.find( '.spinner' ).css( 'visibility', 'hidden' );
							$( '#marker_success' ).html( save_update_message );
							$( '.wgm_marker_cancel' ).trigger( 'click' );
							current_map_markers[response.marker_id].setDraggable( false );
							is_marker_edit           = false;
							wgm_new_marker_infoindow = null;
							if ( ! is_update) {
								wgm_no_of_marker++;
								if (wgm_no_of_marker >= 1 && wgm_l.is_premium_user !== '1') {
									jQuery( '.add_new_marker_btn_area' ).find( ".add_new_marker" ).css(
										{
											'opacity': .5
										}
									);
									jQuery( '.add_new_marker_btn_area' ).find( ".wgm-pro-label" ).show();
								}
							}
						}
					);

				}
			);

			$( document.body ).on(
				'click',
				".add_new_marker",
				function () {
					if (wgm_no_of_marker >= 1 && wgm_l.is_premium_user !== '1') {
						Swal.fire(
							{
								icon: 'info',
								title: wgm_l.locales.sweet_alert.oops,
								html: wgm_l.locales.sweet_alert.notice_unlimited_marker + '<br><br><span style="font-size:25px;font-weight:bold;">Only $19 for Lifetime</span><br><br><a target="_blank" href="' + wgm_l.get_p_v_url + '">Upgrade to Pro Version (Only $19 for Lifetime)</a>'
							}
						);
						return false;
					}
					$( document.body ).find( '.add_new_marker_form' ).addClass( 'wgm_active' ).show();
					$( '.wpgmap_marker_update' ).removeClass( 'wpgmap_marker_update' ).addClass( 'wpgmap_marker_add' ).css( 'background-color', '#2271b1' ).html( '<i class="dashicons dashicons-location" style="line-height: 1.6;"></i><b>Save Marker</b>' );
					$( document.body ).find( '.wgm_gmap_marker_list' ).hide();
					$( document.body ).find( '#marker_errors,#marker_success' ).html( '' );
					wpgmap_reset_marker_add_form();
				}
			);

			$( document.body ).on(
				'click',
				".wgm_marker_cancel",
				function () {
					$( document.body ).find( '.add_new_marker_form' ).removeClass( 'wgm_active' ).hide();
					$( document.body ).find( '.wgm_gmap_marker_list' ).show();
					wpgmap_reset_marker_add_form();
					wgm_vanish_marker( 'new' );
					is_marker_edit = false;
				}
			);

			$( document.body ).find( "#wpgmap_have_marker_link" ).on(
				'change',
				function () {
					if ($( document.body ).find( "#wpgmap_have_marker_link" ).val() === '1') {
						$( "#wpgmap_marker_link_area" ).show();
					} else {
						$( "#wpgmap_marker_link_area" ).hide();
					}
				}
			);

		}
	);
})( jQuery );
