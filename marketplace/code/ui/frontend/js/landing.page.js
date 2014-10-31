/**
 * Copyright 2014 Openstack Foundation
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
jQuery(document).ready(function($){
    //init map widget

    var places = [];

    if(typeof(dc_locations)!=='undefined' && dc_locations.length > 0){
        places = dc_locations;
    }

    $('#mini-map').google_map({
        places : places,
        minZoom: 1,
        minClusterZoom:2,
        gridSize: 20,
        minimumClusterSize:2,
        getInfo:function(place){
            return '<a href="'+place.product_url+'"><b>'+place.owner+'</b><br>'+
                place.product_name+'<br>'+
                place.city+', '+place.country+' DataCenter</a>'
        }
    });
});

