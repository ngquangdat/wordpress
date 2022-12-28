"use strict";
var wgm_map, wgm_marker1, wgm_infowindow,
    wgm_icon = 'https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png', info_content,
    current_map_markers = [],
    current_map_infowindows = [],

    // new marker
    wgm_new_marker = null,
    wgm_new_marker_infoindow = null,

    wgm_new_marker_name = '', wgm_new_marker_desc = '',

    // existing marker
    wgm_existing_marker,
    wgm_existing_marker_infoindow = null,
    wgm_existing_marker_name = '', wgm_existing_marker_desc = '',

    wgm_custom_marker,
    custom_marker_infowindow,
    is_marker_edit = false,
    wgm_no_of_marker = 0;

/**
 * Select element by ID
 *
 * @param id
 * @return html_element
 * @since 1.0.0
 */
function _wgm_e(id) {
    return document.getElementById(id);
}


// This function allows the script to run from both locations (visual and text)
function wgm_generate_infowindow() {

    if (is_marker_edit === true) {
        // current_map_infowindows
        var marker_id = parseInt(jQuery('.wpgmap_marker_update').attr('markerid'));
        // info window object for New Marker creating if not initiated yet
        wgm_existing_marker_infoindow = current_map_infowindows[marker_id];

        // Info window contents generating from input and editor
        wgm_existing_marker_name = '<span class="info_content_title" style="font-size:18px;font-weight: bold;font-family: Arial;">' + jQuery("#wpgmap_marker_name").val() + '</span>';
        wgm_existing_marker_desc = tmce_getContent('wpgmap_marker_desc', 'wpgmap_marker_desc');

        // Set info window content if info window object initiated
        if (wgm_existing_marker_infoindow !== null) {
            wgm_existing_marker_infoindow.setContent(wgm_existing_marker_name + wgm_existing_marker_desc);
        }

        // existing marker, when editing
        if (typeof wgm_existing_marker !== 'undefined' && jQuery("#wpgmap_marker_infowindow_show").val() === '1') {
            wgm_existing_marker_infoindow.open({anchor: wgm_existing_marker, shouldFocus: false});
        }
    } else {
        // info window object for New Marker creating if not initiated yet
        if (wgm_new_marker_infoindow === null) {
            wgm_new_marker_infoindow = new google.maps.InfoWindow(
                {
                    content: ''
                }
            );
        }

        // Info window contents generating from input and editor
        wgm_new_marker_name = '<span class="info_content_title" style="font-size:18px;font-weight: bold;font-family: Arial;">' + jQuery("#wpgmap_marker_name").val() + '</span>';
        wgm_new_marker_desc = tmce_getContent('wpgmap_marker_desc', 'wpgmap_marker_desc');

        // Set info window content if info window object initiated
        if (wgm_new_marker_infoindow !== null) {
            wgm_new_marker_infoindow.setContent(wgm_new_marker_name + wgm_new_marker_desc);
        }

        // mew marker, when creating new one
        if (typeof wgm_new_marker !== 'undefined' && jQuery("#wpgmap_marker_infowindow_show").val() === '1') {
            wgm_new_marker_infoindow.open({anchor: wgm_new_marker, shouldFocus: false});
        }
    }
}

/**
 * Defining Map event listeners
 *
 * @param wgm_map object
 * @since 1.0.0
 */
function wgm_addMapListeners(wgm_map) {

    // On map center changed
    wgm_map.addListener(
        "center_changed",
        function () {
            jQuery('#wpgmap_center_lat_lng').val(wgm_map.center.lat() + ',' + wgm_map.center.lng());
        }
    );

    // On map zoom level changed
    wgm_map.addListener(
        "zoom_changed",
        function () {
            jQuery('#wpgmap_map_zoom').val(wgm_map.zoom);
        }
    );
}

/**
 * In case of already initialized map
 *
 * @param map_type
 * @param center_lat
 * @param center_lng
 * @since 1.0.0
 */
function wgm_generateAlreadyInitializedMap(map_type, center_lat, center_lng) {
    if (map_type == 'ROADMAP') {
        wgm_map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
    } else if (map_type == 'SATELLITE') {
        wgm_map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
    } else if (map_type == 'HYBRID') {
        wgm_map.setMapTypeId(google.maps.MapTypeId.HYBRID);
    } else if (map_type == 'TERRAIN') {
        wgm_map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
    }

    wgm_map.setCenter({lat: center_lat, lng: center_lng});

    // Add Map listeners
    wgm_addMapListeners(wgm_map);

    // Adding dragend Event Listener
    // wgm_addMarkerDragendListener(wgm_marker1);
}

/**
 * Update map settings
 *
 * @param map_type
 * @param center_lat
 * @param center_lng
 * @param zoom
 * @returns {{mapTypeId: *, center: {lng, lat}, zoom}}
 * @since 1.0.0
 */
function wgm_setMapSettingsByMapType(map_type, center_lat, center_lng, zoom) {
    var wgm_gmap_settings = {
        center: {lat: center_lat, lng: center_lng},
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    if (map_type == 'ROADMAP') {
        wgm_gmap_settings.mapTypeId = google.maps.MapTypeId.ROADMAP;
    } else if (map_type == 'SATELLITE') {
        wgm_gmap_settings.mapTypeId = google.maps.MapTypeId.SATELLITE;
    } else if (map_type == 'HYBRID') {
        wgm_gmap_settings.mapTypeId = google.maps.MapTypeId.HYBRID;
    } else if (map_type == 'TERRAIN') {
        wgm_gmap_settings.mapTypeId = google.maps.MapTypeId.TERRAIN;
    }
    return wgm_gmap_settings;
}

/**
 * Defining Marker listener
 *
 * @param marker
 * @since 1.0.0
 */
function wgm_addMarkerDragendListener(marker) {
    marker.addListener(
        'dragend',
        function (markerLocation) {
            _wgm_e("wpgmap_marker_lat_lng").value = markerLocation.latLng.lat() + "," + markerLocation.latLng.lng();
        }
    );
}


/**
 * Map autocomplete implementation
 *
 * @param id
 * @param input
 * @param center_lat
 * @param center_lng
 * @param map_type
 * @param zoom
 * @since 1.0.0
 */
function wgm_initAutocomplete(id, input, center_lat, center_lng, map_type, zoom) {
    // In case of already initiated map
    if (typeof wgm_map === 'object') {
        wgm_generateAlreadyInitializedMap(map_type, center_lat, center_lng);
        return false;
    }

    // Set Map Settings by Map Type
    var wgm_gmap_settings = wgm_setMapSettingsByMapType(map_type, center_lat, center_lng, zoom);

    wgm_map = new google.maps.Map(_wgm_e(id), wgm_gmap_settings);
    if (wgm_theme_json.length > 0) {
        wgm_map.setOptions({styles: JSON.parse(wgm_theme_json)});
    }

    google.maps.event.addListener(
        wgm_map,
        "rightclick",
        function (event) {
            generateMarkerInfoByRightClick(event);
        }
    );

    // // Create the search box and link it to the UI element.
    var wgm_input = document.getElementById(input);
    var wgm_searchBox = new google.maps.places.SearchBox(wgm_input);

    // Place input search box
    wgm_map.controls[google.maps.ControlPosition.TOP_LEFT].push(wgm_input);
    // =====================showing multiple marker=============
    var data = {
        'action': 'wpgmapembed_get_markers_by_map_id',
        'data': {
            map_id: wgm_l.wgm_object.map_id,
            ajax_nonce: wgm_l.ajax_nonce
        }
    };

    jQuery.post(
        ajaxurl,
        data,
        function (response) {
            response = JSON.parse(response);
            wgm_no_of_marker = response.markers.length;

            // Show hints for Marker creation
            if (wgm_no_of_marker === 0) {
                jQuery(document.body).find(".wgm_marker_create_hints").show();
            }

            if (wgm_no_of_marker >= 1 && wgm_l.is_premium_user !== '1') {
                jQuery('.add_new_marker_btn_area').find(".add_new_marker").css(
                    {
                        'opacity': .5
                    }
                );
                jQuery('.add_new_marker_btn_area').find(".wgm-pro-label").show();
            }
            if (response.markers.length > 0) {
                response.markers.forEach(
                    function (marker) {
                        var marker_lat_lng = marker.lat_lng.split(',');

                        var wgm_custom_marker_options = {
                            position: new google.maps.LatLng(marker_lat_lng[0], marker_lat_lng[1]),
                            title: marker.marker_name,
                            animation: google.maps.Animation.DROP,
                        };

                        // Set Icon
                        if (marker.icon !== '') {
                            wgm_custom_marker_options.icon = marker.icon;

                        }

                        // Set marker URL
                        if (marker.have_marker_link === '1') {
                            wgm_custom_marker_options.url = marker.marker_link;
                        }
                        wgm_custom_marker = new google.maps.Marker(wgm_custom_marker_options);
                        if (marker.have_marker_link === '1') {
                            google.maps.event.addListener(
                                wgm_custom_marker,
                                'click',
                                function () {
                                    var wgm_target = '_self';
                                    if (marker.marker_link_new_tab === '1') {
                                        wgm_target = '_blank';
                                    }
                                    window.open(this.url, wgm_target);
                                }
                            );
                        }

                        wgm_custom_marker.setMap(wgm_map);
                        marker.marker_desc = marker.marker_desc.replace(/&gt;/g, '>').replace(/&lt;/g, '<');
                        var marker_name = (marker.marker_name !== null) ? ('<span class="info_content_title" style="font-size:18px;font-weight: bold;font-family: Arial;">'
                            + marker.marker_name +
                            '</span><br/>') : '';
                        custom_marker_infowindow = new google.maps.InfoWindow(
                            {
                                content: marker_name + marker.marker_desc
                            }
                        );
                        if (marker.show_desc_by_default === '1') {
                            custom_marker_infowindow.open({anchor: wgm_custom_marker, shouldFocus: false});
                        }
                        current_map_markers[parseInt(marker.id)] = wgm_custom_marker;
                        current_map_infowindows[parseInt(marker.id)] = custom_marker_infowindow;
                    }
                );
            }
        }
    );

    // multiple marker showing end

    // Invoking Map listeners
    wgm_addMapListeners(wgm_map);

    // Bias the SearchBox results towards current map's viewport.
    wgm_map.addListener(
        'bounds_changed',
        function () {
            wgm_searchBox.setBounds(wgm_map.getBounds());
        }
    );

    var wgm_markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    wgm_searchBox.addListener(
        'places_changed',
        function () {
            // wgm_marker1.setMap(null);
            var wgm_places = wgm_searchBox.getPlaces();

            if (wgm_places.length === 0) {
                return;
            }
            // wgm_marker1.setMap(null);
            // Clear out the old markers.
            wgm_markers.forEach(
                function (marker) {
                    marker.setMap(null);
                }
            );
            wgm_markers = [];

            // For each place, get the icon, name and location.
            var wgm_bounds = new google.maps.LatLngBounds();
            wgm_places.forEach(
                function (place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    _wgm_e("wpgmap_latlng").value = place.geometry.location.lat() + "," + place.geometry.location.lng();

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        wgm_bounds.union(place.geometry.viewport);
                    } else {
                        wgm_bounds.extend(place.geometry.location);
                    }
                }
            );
            wgm_map.fitBounds(wgm_bounds);
            // Add Marker event listener
            // wgm_addMarkerDragendListener(wgm_markers[0]);
        }
    );
}

/**
 * Initialize Google Map
 *
 * @param lat
 * @param lng
 * @param map_type
 * @since 1.0.0
 */
function wgm_initWpGmap(lat, lng, map_type) {
    wgm_initAutocomplete('wgm_map', 'wgm_pac_input', lat, lng, map_type, parseInt(_wgm_e('wpgmap_map_zoom').value));
}


/**
 * On zoom level change, render map with new zoom level LIVE
 *
 * @since 1.0.0
 */
jQuery(document.body).find('#wpgmap_map_zoom').on(
    'keyup',
    function (element) {
        // var point = wgm_marker1.getPosition(); // Get marker position
        wgm_map.panTo(wgm_map.center); // Pan map to that position
        var current_zoom = parseInt(document.getElementById('wpgmap_map_zoom').value);
        setTimeout("wgm_map.setZoom(" + current_zoom + ")", 800); // Zoom in after 500 m second
    }
);

/**
 * On title field text change, update map title LIVE
 *
 * @since 1.0.0
 */
jQuery(document.body).find('#wpgmap_title').on(
    'keyup',
    function (element) {
        jQuery('#wpgmap_heading_preview').css({'display': 'block'}).html(jQuery('#wpgmap_title').val());
    }
);

/**
 * On map type change, render different types of map LIVE
 *
 * @since 1.0.0
 */
jQuery(document.body).find('#wpgmap_map_type').on(
    'change',
    function (element) {
        // wgm_marker1.setMap(null);
        var map_type = jQuery(this).val();
        wgm_map.setMapTypeId(map_type.toLowerCase());
    }
);


/**
 * On map theme presets change, render different types of map based on theme
 *
 * @since 1.8.6
 */
jQuery(document.body).find('#wpgmap_map_theme').on(
    'change',
    function (element) {
        var wgm_theme_json = JSON.parse(jQuery(this).val());
        wgm_map.setOptions({styles: wgm_theme_json});
        jQuery(document.body).find("#wgm_theme_json").val(jQuery(this).val());
    }
);

/**
 * On map theme presets change, render different types of map based on theme
 *
 * @since 1.8.6
 */
jQuery(document.body).find('#wgm_theme_json').on(
    'blur',
    function (element) {
        var wgm_theme_json = JSON.parse(jQuery(this).val());
        wgm_map.setOptions({styles: wgm_theme_json});
    }
);

/**
 * Rendering tab contents
 *
 * @since 1.0.0
 */
jQuery(document.body).find('.wgm_wpgmap_tab li').on(
    'click',
    function (e) {
        e.preventDefault();
        jQuery('.wgm_wpgmap_tab li').removeClass('active');
        jQuery(this).addClass('active');

        jQuery('.wp-gmap-tab-contents').addClass('hidden');
        var wpgmap_id = jQuery(this).attr('id');
        jQuery('.' + wpgmap_id).removeClass('hidden');
        if (wpgmap_id === 'wgm_gmap_markers') {
            jQuery('.wgm_gmap_marker_list').css('display', 'block');
            jQuery('.add_new_marker_form').css('display', 'none');
        } else {
            jQuery('.wgm_gmap_marker_list').css('display', 'none');
        }
    }
);

// ========================================For Media Upload in Marker===================================
jQuery(document).ready(
    function ($) {

        $('#wpgmap_upload_marker_icon').click(
            function () {
                var custom_uploader;
                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }

                custom_uploader = wp.media.frames.file_frame = wp.media(
                    {
                        title: 'Choose Image'
                        , button: {
                            text: 'Choose Image'
                        }
                        , multiple: false
                    }
                );

                custom_uploader.on(
                    'select',
                    function () {
                        var attachment = custom_uploader.state().get('selection').first().toJSON();

                        var data = {
                            'action': 'wpgmapembed_save_marker_icon',
                            'data': {
                                icon_url: attachment.url,
                                ajax_nonce: wgm_l.ajax_nonce
                            }
                        };
                        jQuery.post(
                            ajaxurl,
                            data,
                            function (response) {
                                response = JSON.parse(response);
                                $(document.body).find("#wpgmap_marker_icon").val(response.icon_url);
                                $(document.body).find("#wpgmap_marker_icon_preview").attr('src', response.icon_url);
                                var elm = {};
                                elm.src = response.icon_url;
                                wpgmapChangeCurrentMarkerIcon(elm);
                            }
                        );
                    }
                );

                // Open the uploader dialog
                custom_uploader.open();
            }
        );
    }
);

function generateMarkerInfoByRightClick(event) {
    // Is markers tab active
    if (!jQuery('.add_new_marker_form').hasClass('wgm_active')) {
        return false;
    }

    if (wgm_new_marker != null) {
        alert('Please save current marker at first!');
        return false;
    }
    var lat = event.latLng.lat();
    var lng = event.latLng.lng();
    wgm_new_marker = new google.maps.Marker(
        {
            title: "",
            animation: google.maps.Animation.DROP,
            position: event.latLng,
            draggable: true,
            map: wgm_map
        }
    );

    if (is_marker_edit) {

        wgm_existing_marker.setMap(null);
        wgm_existing_marker = new google.maps.Marker(
            {
                title: "",
                animation: google.maps.Animation.DROP,
                position: event.latLng,
                draggable: true,
                map: wgm_map
            }
        );

        wgm_generate_infowindow();

        wgm_existing_marker_infoindow.open({anchor: wgm_existing_marker, shouldFocus: false});
        wgm_addMarkerDragendListener(wgm_existing_marker);

    } else {
        wgm_generate_infowindow();
        wgm_new_marker_infoindow.open({anchor: wgm_new_marker, shouldFocus: false});
        wgm_addMarkerDragendListener(wgm_new_marker);

    }

    // populate yor box/field with lat, lng
    jQuery('#wpgmap_marker_lat_lng').val(lat + ',' + lng);

}

function tmce_setContent(content, editor_id, textarea_id) {
    if (typeof editor_id == 'undefined') {
        editor_id = wpActiveEditor;
    }
    if (typeof textarea_id == 'undefined') {
        textarea_id = editor_id;
    }

    if (jQuery('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
        content = content.replace(/&gt;/g, '>').replace(/&lt;/g, '<');
        return tinyMCE.get(editor_id).setContent(content);
    } else {
        return jQuery('#' + textarea_id).val(content);
    }
}

function tmce_getContent(editor_id, textarea_id) {
    if (typeof editor_id == 'undefined') {
        editor_id = wpActiveEditor;
    }
    if (typeof textarea_id == 'undefined') {
        textarea_id = editor_id;
    }

    if (jQuery('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
        return tinyMCE.get(editor_id).getContent();
    } else {
        return jQuery('#' + textarea_id).val();
    }
}

var marker_name_info_content = '', marker_desc_info_content = '';

function populateMarkerInfowindow() {
    var final_content = marker_name_info_content + marker_desc_info_content;
    if (wgm_existing_marker_infoindow !== null) {
        wgm_existing_marker_infoindow.setContent(final_content);
    }
}


jQuery(document).ready(
    function ($) {

        // ==============================
        // Create 'keyup_event' tinymce plugin
        tinymce.PluginManager.add(
            'keyup_event',
            function (editor, url) {
                if (editor.id === 'wpgmap_marker_desc') {

                    // Create keyup event
                    editor.on(
                        'keyup',
                        function (e) {
                            wgm_generate_infowindow();
                        }
                    );
                }
            }
        );

        jQuery('#wpgmap_marker_name,#wpgmap_marker_desc').on(
            'keyup',
            function (element) {
                wgm_generate_infowindow();
            }
        );

        jQuery(document.body).find('#wpgmap_marker_link').on(
            'blur',
            function (event) {
                var wgm_marker_url = jQuery(this).val();
                if (is_marker_edit === true) {
                    wgm_existing_marker.url = wgm_marker_url;
                    google.maps.event.addListener(
                        wgm_existing_marker,
                        'click',
                        function () {
                            var wgm_target = '_self';
                            if ($('#wpgmap_marker_link_new_tab').is(':checked')) {
                                wgm_target = '_blank';
                            }
                            window.open(this.url, wgm_target);
                        }
                    );
                } else {
                    if (wgm_new_marker !== null) {
                        wgm_new_marker.url = wgm_marker_url;
                        google.maps.event.addListener(
                            wgm_new_marker,
                            'click',
                            function () {
                                var wgm_target = '_self';
                                if (jQuery('#wpgmap_marker_link_new_tab').is(':checked')) {
                                    wgm_target = '_blank';
                                }
                                window.open(this.url, wgm_target);
                            }
                        );
                    }
                }
            }
        );

        function generateMarkersListView() {
            $('#wgm_gmap_marker_list').DataTable().ajax.reload();
        }

        // Marker delete
        jQuery(document.body).on(
            'click',
            '.wpgmap_marker_trash',
            function (event) {
                event.preventDefault();
                var parent = $(this).parents().eq(4);
                parent.find('.spinner').css('visibility', 'visible');
                if (confirm('Are you sure to delete?')) {
                    var marker_id = jQuery(this).attr('map_marker_id');
                    var data = {
                        'action': 'wpgmapembed_delete_marker',
                        'data': {
                            marker_id: marker_id,
                            ajax_nonce: wgm_l.ajax_nonce
                        }
                    };
                    jQuery.post(
                        ajaxurl,
                        data,
                        function (response) {
                            response = JSON.parse(response);
                            generateMarkersListView();
                            parent.find('.spinner').css('visibility', 'hidden');
                            current_map_markers[parseInt(marker_id)].setMap(null);
                            $(document.body).find('#marker_success').html('Marker removed successfully.');
                            wgm_no_of_marker--;
                            if (wgm_no_of_marker === 0 && wgm_l.is_premium_user !== '1') {
                                jQuery('.add_new_marker_btn_area').find(".add_new_marker").css(
                                    {
                                        'opacity': 1
                                    }
                                );
                                jQuery('.add_new_marker_btn_area').find(".wgm-pro-label").hide();
                            }
                        }
                    );
                }
            }
        );

        // Marker delete
        jQuery(document.body).on(
            'click',
            '.wpgmap_marker_view',
            function (event) {
                event.preventDefault();
                var parent = $(this).parents().eq(4);
                parent.find('.spinner').css('visibility', 'visible');
                var marker_id = jQuery(this).attr('map_marker_id');
                wgm_existing_marker = current_map_markers[marker_id];
                wgm_map.panTo(wgm_existing_marker.getPosition());
            }
        );

        // Marker Edit
        jQuery(document.body).on(
            'click',
            '.wpgmap_marker_edit',
            function (event) {
                event.preventDefault();
                is_marker_edit = true;
                var parent = $(this).parents().eq(4);
                parent.find('.spinner').css('visibility', 'visible');
                var marker_id = jQuery(this).attr('map_marker_id');
                wgm_existing_marker = current_map_markers[marker_id];
                var data = {
                    'action': 'wpgmapembed_get_marker_data_by_marker_id',
                    'data': {
                        marker_id: marker_id,
                        ajax_nonce: wgm_l.ajax_nonce
                    }
                };
                jQuery.post(
                    ajaxurl,
                    data,
                    function (response) {
                        response = JSON.parse(response);
                        $('#wpgmap_marker_name').val(response.marker_name);
                        $('#wpgmap_marker_address').val(response.address);
                        var wgm_marker_lat_lng = response.lat_lng.split(',');
                        $('#wpgmap_marker_lat_lng').val(wgm_marker_lat_lng[0] + ',' + wgm_marker_lat_lng[1]);
                        $('#wpgmap_marker_link').val(response.marker_link);
                        $('#wpgmap_marker_icon').val(response.icon);
                        if (response.have_marker_link === '1') {
                            $("#wpgmap_marker_link_area").show();
                        } else {
                            $("#wpgmap_marker_link_area").hide();
                        }
                        $('#wpgmap_marker_link_new_tab').prop('checked', response.marker_link_new_tab === "1");
                        $('#wpgmap_marker_infowindow_show').val(response.show_desc_by_default).change();
                        $('#wpgmap_have_marker_link').val(response.have_marker_link).change();
                        $('.wpgmap_marker_add,.wpgmap_marker_update').attr('markerid', marker_id);
                        $('.wpgmap_marker_add').removeClass('wpgmap_marker_add').addClass('wpgmap_marker_update').css('background-color', '#00a2f3').html('<i class="dashicons dashicons-location" style="line-height: 1.6;"></i><b>Update Marker</b>');
                        // Reset wp editor content
                        tmce_setContent(response.marker_desc, 'wpgmap_marker_desc', 'wpgmap_marker_desc');
                        parent.find('.spinner').css('visibility', 'hidden');

                        $(document.body).find('.add_new_marker_form').show();
                        $(document.body).find('.wgm_gmap_marker_list').hide();
                        $(document.body).find('#marker_errors,#marker_success').html('');
                        $(document.body).find("#wpgmap_marker_icon_preview").attr('src', response.icon);
                        wgm_map.panTo(wgm_existing_marker.getPosition());
                        current_map_markers[marker_id].setDraggable(true);
                        wgm_addMarkerDragendListener(current_map_markers[marker_id]);
                        wgm_existing_marker_infoindow = new google.maps.InfoWindow(
                            {
                                content: '<span class="info_content_title" style="font-size:18px;font-weight: bold;font-family: Arial;">' + response.marker_name + '</span>' + response.address
                            }
                        );
                    }
                );
            }
        );

    }
);


function wpgmapChangeCurrentMarkerIcon(elem) {
    var icon_url = elem.src;
    document.getElementById('wpgmap_marker_icon').value = icon_url;
    document.getElementById('wpgmap_marker_icon_preview').src = icon_url;
    jQuery('#TB_closeWindowButton').click();
    if (is_marker_edit) {
        if (wgm_existing_marker !== null) {
            wgm_existing_marker.setIcon(icon_url);
        }
    } else {
        if (wgm_new_marker !== null) {
            wgm_new_marker.setIcon(icon_url);
        }
    }
}
