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
jQuery(document).ready(function($) {
    var start_date_id = '.date_filter_date-from';
    var end_date_id = '.date_filter_date-to';
    var submit_id = '.date_filter_submit';
    var action_id = '.date_filter_action';

    if($(start_date_id).length > 0 && $(end_date_id).length > 0){

        $(start_date_id).datetimepicker({
            format:'Y/m/d H:i',
            onShow:function( ct ){
                this.setOptions({
                    maxDate:$(end_date_id,$(this).parents('fieldset')).val()?$(end_date_id,$(this).parents('fieldset')).val():false
                })
            },
            timepicker:false
        });

        $(end_date_id).datetimepicker({
            format:'Y/m/d H:i',
            onShow:function( ct ){
                this.setOptions({
                    minDate:$(start_date_id,$(this).parents('fieldset')).val()?$(start_date_id,$(this).parents('fieldset')).val():false
                })
            },
            timepicker:false
        });

        $(submit_id).click(function(){
            var parent = $(this).parents('fieldset');
            var from = $(start_date_id,parent).val();
            var to = $(end_date_id,parent).val();
            var action = $(action_id,parent).val();

            if (action) {
                document.location = "/sangria/" + action + "?From="+from+"&To="+to;
            } else {
                var queryParameters = {};
                var queryString = document.location.search.substring(1);
                var re = /([^&=]+)=([^&]*)/g;
                var m;

                // Creates a map with the query string parameters
                while (m = re.exec(queryString)) {
                    queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
                }

                queryParameters['From'] = from;
                queryParameters['To'] = to;

                document.location.search = $.param(queryParameters); // Causes page to reload
            }

        });
    }

    $('.cvs_download_link').click(function(){
        var filter_box = $(this).siblings('.export_filters');
        $('.export_filters').not(filter_box).slideUp(500).addClass('hidden')

        if (filter_box.hasClass('hidden')) {
            filter_box.slideDown(500).removeClass('hidden');
        } else {
            filter_box.slideUp(500).addClass('hidden');
        }

        return false;
    })
});
