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
jQuery(document).ready(function($){
    var form = $('#search_private_clouds');
    // initialize widgets
    $('#company_id',form).chosen({disable_search_threshold: 10});

    //search params
    var name = $.QueryString["name"];
    if(name!='undefined'){
        $('#name').val(name);
    }

    var company_id = $.QueryString["company_id"];
    if(company_id!='undefined'){
        $('#company_id').val(company_id);
        $("#company_id").trigger("chosen:updated");
    }

    $(".delete-private-cloud").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        if(confirm("Are you sure to delete this?")){
            var id   = $(this).attr('data-id');
            var url  = 'api/v1/marketplace/private-clouds'
            url      = url+'/'+id;
            $.ajax({
                type: "DELETE",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data,textStatus,jqXHR) {
                    window.location = listing_url;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    ajaxError(jqXHR, textStatus, errorThrown);
                }
            });
        }
        return false;
    });
});

