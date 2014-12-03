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

    var settings = {};
    var downgrade_btn = null;
    var upgrade_btn   = null;
    var confirm_downgrade_dialog = null;
    var confirm_upgrade_dialog = null;

    var methods = {
        init: function(options){

            settings      = $.extend({}, options );
            downgrade_btn = $('.downgrade-2-community-member');
            upgrade_btn   = $('.upgrade-2-foundation-member');

            downgrade_btn.click(function(event){
                event.preventDefault();
                event.stopPropagation();
                confirm_downgrade_dialog.dialog( "open");
                return false;
            });

            upgrade_btn.click(function(event){
                event.preventDefault();
                event.stopPropagation();
                confirm_upgrade_dialog.dialog( "open");
                return false;
            });

            confirm_upgrade_dialog =  $('#dialog-confirm-upgrade').dialog({
                resizable: false,
                autoOpen: false,
                height:520,
                width:620,
                modal: true,
                buttons: {
                    "Agree": function() {
                        $.ajax(
                            {
                                type: "GET",
                                url: 'userprofile/Upgrade2FoundationMember',
                                dataType: "json",
                                timeout:5000,
                                retryMax: 2,
                                complete: function (jqXHR,textStatus) {
                                    window.location.reload();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    alert( "Request failed: " + textStatus );
                                }
                            }
                        );
                        $(this).dialog( "close" );
                    },
                    "Disagree": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });

            confirm_downgrade_dialog  = $('#dialog-confirm-downgrade').dialog({
                resizable: false,
                autoOpen: false,
                height:220,
                width:520,
                modal: true,
                buttons: {
                    "Agree": function() {
                        $.ajax(
                            {
                                type: "GET",
                                url: 'userprofile/Downgrade2CommunityMember',
                                dataType: "json",
                                timeout:5000,
                                retryMax: 2,
                                complete: function (jqXHR,textStatus) {
                                    window.location.reload();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    alert( "Request failed: " + textStatus );
                                }
                            }
                        );
                        $(this).dialog( "close" );
                    },
                    "Disagree": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });



        }

    };


    $.fn.user_infobox = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.user_infobox' );
        }
    };
    // End of closure.
}( jQuery ));

