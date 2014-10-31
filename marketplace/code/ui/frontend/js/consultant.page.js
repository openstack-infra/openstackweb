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
    if(typeof(offices_instance)!=='undefined' && offices_instance.length > 0){
        $('#mini-map').google_map({
            places : offices_instance,
            minZoom: 1,
            minClusterZoom:2,
            getInfo:function(place){
                return '<b>'+place.owner+'</b><br>'+
                    '<b>'+place.name+'</b><br>'+
                    place.address;
            }
        });
    }

    $('.support-regions').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        $('.support-regions').removeClass('selected');
        $('.support-channels').hide();
        $(this).addClass('selected');
        var region_id =  $(this).attr('data-region');
        $('#region_channels_'+region_id).show();
        return false;
    });
});