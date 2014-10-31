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
jQuery(document).ready(function($) {

    $('.ccla_checkbox').click(function(event){
        var sign = $(this).is(":checked");

        if(!sign && !confirm("Are you sure...?")){
            event.preventDefault();
            event.stopPropagation();
            return false;
        }

        var company_id = $(this).attr('data-company-id');
        var url        = 'api/v1/ccla/companies/'+company_id+'/sign';
        var verb       = sign?'PUT':'DELETE';

        $.ajax({
            async:true,
            type: verb,
            url: url,
            dataType: "json",
            success: function (data,textStatus,jqXHR) {
                var td = $('#ccla_date_'+company_id);
                if(sign){
                    var date = data.sign_date.date;
                    td.html(date);
                }
                else{
                    td.html('');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert( "Request failed: " + textStatus );
            }
        });
    });
});