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

    var filetypes = /[\./](zip|exe|pdf|doc*|xls*|ppt*|mp3)$/i;

    var methods = {
        init : function(options) {
            var baseHref = '';
            if ($('base').attr('href') != undefined)
                baseHref = $('base').attr('href');
                $('a').each(function() {
                    var href = $(this).attr('href');
                    if (href && href.match(filetypes)) {
                            $(this).click(function() {
                            var extension = (/[\./]/.exec(href)) ? /[^\./]+$/.exec(href) : undefined;
                            var filePath = href;
                            _gaq.push(['_trackEvent', 'Download', 'Click-' + extension, filePath]);
                            if ($(this).attr('target') != undefined && $(this).attr('target').toLowerCase() != '_blank') {
                                setTimeout(function() { location.href = baseHref + href; }, 200);
                                return false;
                            }
                        });
                    }
             });
        }
    };

    $.fn.filetracking = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.filetracking' );
        }
    };

    //helper functions

// End of closure.
}( jQuery));