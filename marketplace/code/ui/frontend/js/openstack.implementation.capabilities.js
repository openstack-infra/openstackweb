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

    var table = null;
    var settings = {};

    var methods = {
        init: function(options){
            settings = $.extend({}, options );
            table = $(this);
            if(table.length > 0){
                $('td.coverage',table).each(function(index, value){
                    var coverage = parseInt(settings.coverages[index]);
                    var td = $(this);
                    var level = 'full';
                    if(coverage==0)
                        level = 'none';
                    else if(coverage>0 && coverage <= 50)
                        level = 'partial';
                    td.append('<span class="level-'+level+'">'+level+'</span>');
                });
            }
        }
    };

    $.fn.capabilities_meter = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.capabilities_meter' );
        }
    };
    // End of closure.
}( jQuery ));

