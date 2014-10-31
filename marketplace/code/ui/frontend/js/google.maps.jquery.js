/**
 * Copyright 2014 Openstack.org
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/
(function( $ ){

    var map_canvas       = null;
    var map              = null;
    var marker_clusterer = null;
    var markers          = [];
    var markers_images   = [];
    var settings         = {};
    var oms = null;

    var methods = {
        init: function(options){
            map_canvas = $(this);

            if(map_canvas.length>0){

                settings = $.extend({
                    // These are the defaults.
                    minZoom: 2,
                    minClusterZoom:4,
                    //The grid size of a cluster in pixels. The grid is a square. The default value is 60.
                    gridSize: 60,
                    //The minimum number of markers needed in a cluster before the markers are hidden and a cluster marker appears. The default value is 2.
                    minimumClusterSize: 2
                }, options );

                var info_window = new InfoBubble({maxWidth: 400,borderRadius:15});

                var mapOptions = {
                    zoom: 1,
                    center: new google.maps.LatLng(29.424198, -10.493488),
                    disableDefaultUI: false,
                    streetViewControl: false,
                    zoomControl: true,
                    mapTypeControl: false,
                    minZoom: settings.minZoom,
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.SMALL
                    },
                    styles: [
                        {
                            "featureType": "landscape.man_made",
                            "stylers": [ { "weight": 4.8 }, { "visibility": "simplified" } ] }
                    ]
                };

                map = new google.maps.Map(map_canvas[0],mapOptions);

                var oms_options = {
                    markersWontMove: true,
                    markersWontHide: true,
                    keepSpiderfied:  true
                };

                oms = new OverlappingMarkerSpiderfier(map,oms_options);
                var places = settings.places;

                var shadow = new google.maps.MarkerImage(
                    'https://www.google.com/intl/en_ALL/mapfiles/shadow50.png',
                    new google.maps.Size(37, 34), // size - for sprite clipping
                    new google.maps.Point(0, 0), // origin - ditto
                    new google.maps.Point(10, 34) // anchor - where to meet map location
                );

                oms.addListener('spiderfy', function(markers) {
                    console.log('spiderfy');
                });

                oms.addListener('unspiderfy', function(markers) {
                    console.log('unspiderfy');
                });

                oms.addListener('click', function(marker) {
                    info_window.setContent(marker.desc);
                    info_window.open(map, marker);
                });

                google.maps.event.addListener(map, 'click', function() {
                    info_window.close();
                });

                if(typeof(places)!=='undefined' && places.length > 0){
                    var width       = 32;
                    var height      = 32
                    var coords = [
                        width / 2, height,
                        (7 / 16) * width, (5 / 8) * height,
                        (5 / 16) * width, (7 / 16) * height,
                        (7 / 32) * width, (5 / 16) * height,
                        (5 / 16) * width, (1 / 8) * height,
                        (1 / 2) * width, 0,
                        (11 / 16) * width, (1 / 8) * height,
                        (25 / 32) * width, (5 / 16) * height,
                        (11 / 16) * width, (7 / 16) * height,
                        (9 / 16) * width, (5 / 8) * height
                    ];
                    for (var i = 0; i < coords.length; i++) {
                        coords[i] = parseInt(coords[i]);
                    }
                    // Shapes define the clickable region of the icon.
                    var shape  = { type: "poly", coords: coords };

                    for(var i in places){
                        var place = places[i];
                        var color = place.color;
                        if(!(color in markers_images)){
                            var primaryColor = color;
                            var strokeColor  = '000000';
                            var cornerColor  = 'FFFFFF';
                            var image_url = 'http://chart.apis.google.com/chart?cht=mm&chs='+width+'x'+height+'&chco='+cornerColor+','+primaryColor+','+strokeColor+'&ext=.png';
                            var icon      = new google.maps.MarkerImage(image_url,new google.maps.Size(width, height));
                            icon.anchor   = new google.maps.Point(width / 2, height);
                            markers_images[color] = icon;
                        }
                        var marker_image = markers_images[color];
                        var lat_lng      = new google.maps.LatLng(place.lat,place.lng);
                        var marker       = new google.maps.Marker({
                            position: lat_lng,
                            icon: marker_image,
                            shape: shape
                        });
                        marker.setShadow(shadow);
                        markers.push(marker);
                        oms.addMarker(marker);
                        var callback = settings.getInfo;
                        var info = '';
                        if ($.isFunction(callback)) info = callback(place);
                        marker.desc = info;
                    }
                }
                //http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/docs/reference.html#MarkerClustererOptions
                var mc_options = {
                    gridSize: settings.gridSize,
                    minimumClusterSize: settings.minimumClusterSize
                };
                marker_clusterer  = new MarkerClusterer(map, markers, mc_options);
                // Limit the cluster zoom level
                marker_clusterer.setMaxZoom(settings.minClusterZoom);

                google.maps.event.addListener(marker_clusterer, 'clusterclick', function(cluster) {
                    map.fitBounds(cluster.getBounds()); // Fit the bounds of the cluster clicked on
                    if( map.getZoom() > settings.minClusterZoom+1 ) // If zoomed in past 15 (first level without clustering), zoom out to 15
                        map.setZoom(settings.minClusterZoom+1);
                });
            }
        }
    };

    $.fn.google_map = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.google_map' );
        }
    };
    // End of closure.
}( jQuery ));

