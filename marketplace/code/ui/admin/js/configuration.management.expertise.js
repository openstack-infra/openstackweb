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
(function( $ ){

    var form     = null;
    var settings = {};
    var methods  = {
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
            var configuration_management_expertise = [];
            //iterate over collection
            $(".configuration-management-checkbox:checked",form).each(function(){
                var checkbox      = $(this);
                configuration_management_expertise.push(parseInt(checkbox.attr('data-configuration-management-id')))
            });
            return configuration_management_expertise;
        },
        load: function(configuration_management_expertise) {
            for(var i in configuration_management_expertise){
                var id = configuration_management_expertise[i];
                $('#configuration_management_'+id,form).prop('checked',true);
            }
        }
    };

    $.fn.configuration_management_expertise = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.configuration_management_expertise' );
        }
    };
    //helper functions
// End of closure.
}( jQuery ));
