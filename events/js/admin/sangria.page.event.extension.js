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

    var edit_dialog = $( "#edit_dialog" ).dialog({
            width: 450,
            height: 900,
            modal: true,
            autoOpen: false,
            resizable: false,
            buttons: {
                "Save": function() {

                    var form     = $('form',edit_dialog);
                    var is_valid = form.valid();
                    if(!is_valid) return false;
                    var row     = edit_dialog.data('row');
                    var id      = parseInt(edit_dialog.data('id'));
                    var form_id = form.attr('id');
                    var url     = 'api/v1/event-registration-requests';

                    var request = {
                        id : id,
                        point_of_contact_name  :  $('#'+form_id+'_point_of_contact_name',form).val(),
                        point_of_contact_email :  $('#'+form_id+'_point_of_contact_email',form).val(),
                        title      : $('#'+form_id+'_title',form).val(),
                        url        : $('#'+form_id+'_url',form).val(),
                        city       : $('#'+form_id+'_city',form).val(),
                        state      : $('#'+form_id+'_state',form).val(),
                        country    : $('#'+form_id+'_country',form).val(),
                        start_date : $('#'+form_id+'_start_date',form).val(),
                        end_date   : $('#'+form_id+'_end_date',form).val()
                    };

                    $.ajax({
                        type: 'PUT',
                        url: url,
                        data: JSON.stringify(request),
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function (data,textStatus,jqXHR) {
                            edit_dialog.dialog( "close" );
                            form.cleanForm();
                            //update row values...
                            $('.title',row).text(request.title);
                            $('.url',row).text(request.url);
                            $('.city',row).text(request.city);
                            $('.state',row).text(request.state);
                            $('.country',row).text(request.country);
                            $('.start-date',row).text(request.start_date);
                            $('.end-date',row).text(request.end_date);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            ajaxError(jqXHR, textStatus, errorThrown);
                        }
                    });

                },
                "Cancel": function() {
                    edit_dialog.dialog( "close" );
                }
            }
    });

    var confirm_reject_dialog = $('#dialog-reject-post').dialog({
        resizable: false,
        autoOpen: false,
        height:380,
        width: 450,
        modal: true,
        buttons: {
            "Reject": function() {
                var form     = $('form',confirm_reject_dialog);
                //var is_valid = form.valid();
                //if(!is_valid) return false;
                var id  = parseInt(confirm_reject_dialog.data('id'));
                var row = confirm_reject_dialog.data('row');
                var reject_data = {
                  send_rejection_email : $('#send_rejection_email',form).is(':checked'),
                  custom_reject_message: $('#custom_reject_message',form).val()
                };

                var url = 'api/v1/event-registration-requests/'+id+'/rejected';
                $.ajax({
                    type: 'PUT',
                    url: url,
                    data: JSON.stringify(reject_data),
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data,textStatus,jqXHR) {
                        row.hide('slow', function(){ row.remove();});
                        confirm_reject_dialog.dialog( "close" );
                        form.cleanForm();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        ajaxError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
            "Cancel": function() {
                confirm_reject_dialog.dialog( "close" );
            }
        }
    });

    var confirm_post_dialog = $('#dialog-confirm-post').dialog({
        resizable: false,
        autoOpen: false,
        height:160,
        modal: true,
        buttons: {
            "Post": function() {
                var id  = $(this).data('id');
                var row = $(this).data('row');
                var url = 'api/v1/event-registration-requests/'+id+'/posted';
                $.ajax({
                    type: 'PUT',
                    url: url,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data,textStatus,jqXHR) {
                        row.hide('slow', function(){ row.remove();});
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        ajaxError(jqXHR, textStatus, errorThrown);
                    }
                });
                $(this).dialog( "close" );
            },
            "Cancel": function() {
                $( this ).dialog( "close" );
            }
        }
    });


    $('.edit-event').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        var id  = $(this).attr('data-request-id');
        var row = $(this).parent().parent();
        var url = 'api/v1/event-registration-requests/'+id;
        $.ajax({
            type: 'GET',
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data,textStatus,jqXHR) {
                var form    = $('form',edit_dialog);
                form.cleanForm();
                var form_id = form.attr('id');
                //populate edit form
                $('#'+form_id+'_point_of_contact_name',form).val(data.point_of_contact_name);
                $('#'+form_id+'_point_of_contact_email',form).val(data.point_of_contact_email);
                $('#'+form_id+'_title',form).val(data.title);
                $('#'+form_id+'_url',form).val(data.url);
                $('#'+form_id+'_city',form).val(data.city);
                $('#'+form_id+'_state',form).val(data.state);
                $('#'+form_id+'_country',form).val(data.country);
                $('#'+form_id+'_country',form).trigger("chosen:updated");
                $('#'+form_id+'_start_date',form).val(data.start_date);
                $('#'+form_id+'_end_date',form).val(data.end_date);
                edit_dialog.data('id',id).data('row',row).dialog( "open");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                ajaxError(jqXHR, textStatus, errorThrown);
            }
        });

        return false;
    });

    $('.reject-event').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        var id  = $(this).attr('data-request-id');
        var row = $(this).parent().parent();
        confirm_reject_dialog.data('id',id).data('row',row).dialog( "open");
        return false;
    });

    $('.post-event').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        var id  = $(this).attr('data-request-id');
        var row = $(this).parent().parent();
        confirm_post_dialog.data('id',id).data('row',row).dialog( "open");
        return false;
    });


    $.urlParam = function(name){
        var results = new RegExp("[\\?&]" + name + "=([^&#]*)").exec(window.location.href);
        if (results==null){
            return null;
        }
        else{
            return results[1] || 0;
        }
    }

    if($.urlParam("evt")){

        var anchor = $("#evt" + $.urlParam("evt"));

        $("html, body").animate({
            scrollTop: anchor.offset().top - 30
        }, 2000);

        anchor.parents("tr").css("background-color","lightyellow");
    }
});
