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

    var form     = null;
    var settings = {};

    var methods = {
        init: function(options){
            settings = $.extend({}, options );
            form = $(this);
            if(form.length>0){
                var form_validator = form.validate({
                    onfocusout: false,
                    focusCleanup: true
                });
            }
        },
        serialize:function (){
            var is_valid = form.valid();
            if(!is_valid) return false;
            var services_offered = [];
            //iterate over collection
            $('.service-offered-checkbox:checked').each(function(){
                var checkbox      = $(this);
                var service       = {};
                service.id        = parseInt(checkbox.attr('data-service-offered-id'));
                service.regions   = [];
                $(".service-offered-region-checkbox:checked").each(function(){
                    var region_checkbox = $(this);
                    service.regions.push(parseInt(region_checkbox.attr('data-service-offered-region-id')));
                });
                services_offered.push(service)
            });
            return services_offered;
        },
        load: function(services) {
            for(var i in services){
                var service = services[i];
                $('#service_offered_'+service.id,form).prop('checked',true);
                for(var j in service.regions){
                    var region_id = service.regions[j];
                    $('#service_offered_region_'+region_id,form).prop('checked',true);
                }
            }
        }
    };

    $.fn.services_offered = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.services_offered' );
        }
    };
    //helper functions
// End of closure.
}( jQuery ));
