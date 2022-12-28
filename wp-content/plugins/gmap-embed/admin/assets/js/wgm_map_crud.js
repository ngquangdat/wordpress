(function ($) {
	$( document ).ready(
		function () {
			/**
			 * Datatable to view map list
			 *
			 * @since 1.7.5
			 */
			var wgm_map_list = $( '#wgm_map_list_dt' ).DataTable(
				{
					ajax: {
						url: ajaxurl + '?action=wgm_get_all_maps&ajax_nonce=' + wgm_l.ajax_nonce
					},
					columns: [
					{data: 'id'},
					{data: 'title'},
					{data: 'map_type'},
					{data: 'width'},
					{data: 'height'},
					{data: 'shortcode'},
					{data: 'action'}
					],
					"language": {
						"emptyTable": "<b style='color: #d36d8c'>" + wgm_l.locales.dt.no_map_created + "</b>"
					},
					responsive: true
				}
			);

			/**
			 * Datatable to view marker list
			 *
			 * @since 1.7.5
			 */
			var wgm_map_id           = typeof wgm_l.wgm_object === 'undefined' ? 0 : wgm_l.wgm_object.map_id;
			var wgm_gmap_marker_list = $( '#wgm_gmap_marker_list' ).DataTable(
				{
					ajax: {
						url: ajaxurl + '?action=wgm_get_markers_by_map_id&map_id=' + wgm_map_id + '&ajax_nonce=' + wgm_l.ajax_nonce
					},
					columns: [
					{data: 'id'},
					{data: 'marker_name'},
					{data: 'icon'},
					{data: 'action'},
					],
					"language": {
						"emptyTable": "<b style='color: #d36d8c'>" + wgm_l.locales.dt.no_marker_created + "</b>"
					},
					responsive: true
				}
			);

			$(document.body).on('click',".wpgmap-copy-to-clipboard",function(){
				var copyText = $(this).parent().parent().find(".wpgmap-shortcode");
				console.log(copyText);
				copyText.select();
				navigator.clipboard.writeText(copyText.val());
				$("#copy_to_clipboard_toaster").fadeIn(100).fadeOut(2000);
			})
		}
	);
})( jQuery );
