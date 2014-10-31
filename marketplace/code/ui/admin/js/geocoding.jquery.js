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

    var geo_coder     = null;
    var settings        = {};
    var request_counter = 0;
    var errors          = [];
    // delay between geocode requests - at the time of writing, 100 milliseconds seems to work well
    var delay           = 100;
    // to remind us what to do next
    var next_request    = 0;

    var methods = {
        init : function(options) {
            errors       = []
            next_request = 0;
            delay        = 100;
            settings = $.extend({}, options );
            geo_coder = new google.maps.Geocoder();
            var requests = settings.requests;
            request_counter = requests.length;
            if(request_counter>0){
                theNext();
            }
            else{
                //just call the processFinished callback
                var processFinished = settings.processFinished;
                if ($.isFunction(processFinished))
                    processFinished();
            }
        }
    };

    $.fn.geocoding= function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.geocoding' );
        }
    };

    function theNext() {
        if (next_request < request_counter) {
            var request = settings.requests[next_request];
            window.setTimeout(function(){
                doGeoQuery(request,theNext);
            }, delay);
            next_request++;
        } else {
            // We're done.
            if(errors.length > 0){
                var cancelProcess = settings.cancelProcess;
                if ($.isFunction(cancelProcess))
                    cancelProcess();
                var error_message = '';
                for(var i in errors){
                    error_message +='* '+errors[i]+'\n';
                }
                displayErrorMessage('validation error',error_message);
            }
            else{
                var processFinished = settings.processFinished;
                if ($.isFunction(processFinished))
                    processFinished();
            }
        }
    }

    //helper functions
    function doGeoQuery(request, next){
        var buildGeoRequest = settings.buildGeoRequest;
        if (!$.isFunction(buildGeoRequest))
            $.error('buildGeoRequest is not a function!');

        geo_coder.geocode(buildGeoRequest(request),
                function(results,status){
                    if(status == google.maps.GeocoderStatus.OK){
                        //post process ...
                        var location = results[0].geometry.location;
                        var postProcessRequest = settings.postProcessRequest;
                        if (!$.isFunction(postProcessRequest))
                            $.error('postProcessRequest is not a function!');
                        postProcessRequest(request, location.lat(), location.lng());
                    }
                    else {
                        // === if we were sending the requests to fast, try this one again and increase the delay
                        if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                            next_request--;
                            delay++;
                            //console.log('OVER_QUERY_LIMIT request '+next_request+' delay '+delay+' ms');
                        } else {
                            var msg = '';
                            var errorMessage = settings.errorMessage;
                            if ($.isFunction(errorMessage))
                                msg = errorMessage(request);
                            var reason="Code "+status;
                            msg += ' STATUS ' + status;
                            errors.push(msg);
                        }
                    }
                    next();
                }
        );
    }

// End of closure.
}( jQuery ));