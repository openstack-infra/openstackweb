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

    var methods = {
        init : function(options) {
            form  = $(this);
            if(form.length > 0){

                //first validation method (manual validation)
                $.validator.addMethod("validate_youtube_video", function (value, element, options) {
                    var youtube_id = value;
                    if(youtube_id==='') return true;

                    var m;
                    if (m = youtube_id.match(/^(http|https):\/\/www\.youtube\.com\/.*[?&]v=([^&]+)/i) || youtube_id.match(/^(http|https):\/\/youtu\.be\/([^?]+)/i)) {
                        youtube_id = m[2];
                    }

                    if (!youtube_id.match(/^[a-z0-9_-]{11}$/i)){
                        return false;
                    }
                    return true;
                }, "Your YouTube Video is not valid!");

                //custom remote method to check duration against youtube api using jsonp
                $.validator.addMethod("validate_youtube_video_length", function (value, element, options) {
                    var max_length_in_seconds = parseInt(options);
                    var validator             = this;
                    var youtube_id            = value;
                    if(youtube_id==='') return true;

                    var m;
                    if (m = youtube_id.match(/^(http|https):\/\/www\.youtube\.com\/.*[?&]v=([^&]+)/i) || youtube_id.match(/^(http|https):\/\/youtu\.be\/([^?]+)/i)) {
                        youtube_id = m[2];
                    }


                    if ( this.optional(element) )
                        return "dependency-mismatch";

                    var previous = this.previousValue(element);

                    if ( previous.old !== value ) {
                        previous.old = value;
                        var validator = this;
                        this.startRequest(element);

                        $.jsonp({
                            url: '//gdata.youtube.com/feeds/api/videos/' + encodeURIComponent(youtube_id),
                            callbackParameter: "callback",
                            data:
                            {
                                alt: "jsonc-in-script",
                                v: "2"
                            },
                            success: function(json, textStatus){
                                //check duration (in seconds)
                                var valid = false;
                                //if(json.data.duration <= max_length_in_seconds){
                                    $(element).attr('data-youtube-id',youtube_id);
                                    $(element).attr('data-length',json.data.duration);
                                    $(element).attr('data-description',json.data.description);
                                    $(element).attr('data-title',json.data.title);

                                    var submitted = validator.formSubmitted;
                                    validator.prepareElement(element);
                                    validator.formSubmitted = submitted;
                                    validator.successList.push(element);
                                    validator.showErrors();
                                    valid = true;
                               /* }
                                else{
                                    var errors = {};
                                    var message =  validator.defaultMessage( element, "validate_youtube_video_length");
                                    errors[element.name] =  $.isFunction(message) ? message(max_length_in_seconds) : message;
                                    validator.invalid[element.name] = true;
                                    validator.showErrors(errors);
                                    valid = false;
                                }*/
                                previous.valid = valid;
                                validator.stopRequest(element, valid);
                            },
                            error: function(xOptions, textStatus){
                                //most likely error 404 (video not found!!!)
                                var errors = {};
                                var message =  "Your YouTube Video is not valid!";
                                errors[element.name] =  $.isFunction(message) ? message(value) : message;
                                validator.invalid[element.name] = true;
                                validator.showErrors(errors);
                                validator.stopRequest(element, false);
                            }
                        });
                        return "pending";
                    }
                    else if( this.pending[element.name] ) {
                        return "pending";
                    }
                    return previous.valid;
                }, jQuery.format("Your YouTube Video must be {0} seconds or less."));

                var form_validator = form.validate({
                    focusCleanup: true,
                    onkeyup: false
                });

                $(".youtube-video").each(function(){
                    var video            = $(this);
                    var video_max_length = video.attr("data-max-length");
                    $(this).rules('add',{
                        validate_youtube_video:true,
                        validate_youtube_video_length: video_max_length
                    });
                });
            }
        },
        serialize:function (){
            var is_valid = form.valid();
            if(!is_valid) return false;

            var videos = [];
            //iterate over collection
            $(".youtube-video").each(function(){
                var video                = $(this);
                if(video.val()=='') return;
                var new_video            = {};
                new_video.type_id     = video.attr("data-type-id");
                new_video.length      = video.attr("data-length");
                new_video.title       = video.attr("data-title");
                new_video.description = video.attr("data-description");
                new_video.youtube_id  = video.attr("data-youtube-id");
                videos.push(new_video);
            });
            return videos;
        },
        load: function(videos){
            for(var i in videos){
                var input = $('#video_type_'+videos[i].type_id+'_youtube_id');
                input.val('http://www.youtube.com/watch?v='+videos[i].youtube_id);
                input.attr("data-youtube-id",videos[i].youtube_id)
                input.attr('data-length',videos[i].length);
                input.attr('data-description',videos[i].description);
                input.attr('data-title',videos[i].title);
            }
        }
    }

    $.fn.videos = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.videos' );
        }
    };


    function parseresults(data) {
        alert(data);
        var title = data.entry.title.$t;
        var description = data.entry.media$group.media$description.$t;
        var viewcount = data.entry.yt$statistics.viewCount;
        var author = data.entry.author[0].name.$t;
    }
// End of closure.
}( jQuery ));