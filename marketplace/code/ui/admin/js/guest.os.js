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

    var form  = null;
    var table = null;

    var methods = {
        init : function(options) {
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
            var guest_os = [];
            //iterate over collection
            $(".guest-os-type:checked").each(function(){
                var checkbox      = $(this);
                var guest_id  = parseInt(checkbox.attr('value'));
                guest_os.push(guest_id)
            });
            return guest_os;
        },
        load: function(guest_os){
            for(var i in guest_os){
                $('#guest_os_type_'+guest_os[i],form).prop('checked',true);
            }
        }
    };

    $.fn.guest_os = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.guest_os' );
        }
    };

    //helper functions

// End of closure.
}( jQuery ));
