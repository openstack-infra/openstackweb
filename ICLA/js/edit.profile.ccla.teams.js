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

    var form = $('#ccla_teams_form');

    var form_validator = form.validate({
        rules: {
            add_member_email  : {
                required:true,
                email:true
            },
            add_member_lname  : {
                required:true,
                ValidPlainText:true
            },
            add_member_fname  : {
                required:true,
                ValidPlainText:true
            },
            add_member_team:{
                required:true
            }
        },
        onfocusout: false,
        focusCleanup: true,
        focusInvalid: false,
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            var element = $(validator.errorList[0].element);
            if(!element.is(":visible")){
                element = element.parent();
            }

            $('html, body').animate({
                scrollTop: element.offset().top
            }, 2000);
        },
        errorPlacement: function(error, element) {
            if(!element.is(":visible")){
                element = element.parent();
            }
            error.insertAfter(element);
        }
    });

    $('#add_member_email').autocomplete({
        source:  'api/v1/ccla/members?field=email',
        minLength: 2,
        select: function (event, ui) {
            $("#add_member_email" ).val(ui.item.email);
            $("#add_member_lname" ).val(ui.item.last_name);
            $("#add_member_fname" ).val(ui.item.first_name);
            $('#add_member_row').attr("data-member-id", ui.item.value);
            return false;
        },
        focus: function( event, ui ) {
            $("#add_member_email" ).val(ui.item.email);
            $("#add_member_lname" ).val(ui.item.last_name);
            $("#add_member_fname" ).val(ui.item.first_name);
            return false;
        }
    })

    $('#add_member_lname').autocomplete({
        source:  'api/v1/ccla/members?field=lname',
        minLength: 2,
        select: function (event, ui) {
            $("#add_member_email" ).val(ui.item.email);
            $("#add_member_lname" ).val(ui.item.last_name);
            $("#add_member_fname" ).val(ui.item.first_name);
            $('#add_member_row').attr("data-member-id", ui.item.value);
            return false;
        },
        focus: function( event, ui ) {
            $("#add_member_email" ).val(ui.item.email);
            $("#add_member_lname" ).val(ui.item.last_name);
            $("#add_member_fname" ).val(ui.item.first_name);
             return false;
        }
    })

    $('#add_member_fname').autocomplete({
        source:  'api/v1/ccla/members?field=fname',
        minLength: 2,
        select: function (event, ui) {
            $("#add_member_email" ).val(ui.item.email);
            $("#add_member_lname" ).val(ui.item.last_name);
            $("#add_member_fname" ).val(ui.item.first_name);
            $('#add_member_row').attr("data-member-id", ui.item.value);
            return false;
        },
        focus: function( event, ui ) {
            $("#add_member_email" ).val(ui.item.email);
            $("#add_member_lname" ).val(ui.item.last_name);
            $("#add_member_fname" ).val(ui.item.first_name);
            return false;
        }
    })

    $('.delete_member').live('click',function(event){
        event.preventDefault();
        event.stopPropagation();

        if(confirm('Are you sure?')){
            var button =  $(this);
            if(button.prop('disabled')){
                return false;
            }

            button.prop('disabled',true);

            var id      = button.attr('data-id');
            var status  = button.attr('data-status');
            var team_id = button.attr('data-team-id');

            $.ajax({
                type: 'DELETE',
                url: 'api/v1/ccla/teams/'+team_id+'/memberships/'+id+'/'+status,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data,textStatus,jqXHR) {
                    var row = button.parent().parent();
                    row.remove();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    ajaxError(jqXHR, textStatus, errorThrown);
                    button.prop('disabled',false);
                }
            });
        }
        return false;
    });

    $('#add_member').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        var button =  $(this);
        if(button.prop('disabled')){
            return false;
        }

        var is_valid = form.valid();
        if(!is_valid){
            return false;
        }

        //save
        var invitation = {
            email      : $("#add_member_email" ).val(),
            first_name : $("#add_member_fname" ).val(),
            last_name  : $("#add_member_lname" ).val(),
            team_id    : $("#add_member_team" ).val()
        };

        var member_id = $('#add_member_row').attr("data-member-id");

        if(typeof(member_id) !== "undefined")
            invitation.member_id = member_id;

        button.prop('disabled',true);

        $.ajax({
            type: 'POST',
            url: 'api/v1/ccla/invitations',
            data: JSON.stringify(invitation),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data,textStatus,jqXHR) {
                button.prop('disabled',false);
                $("#add_member_email" ).val('');
                $("#add_member_fname" ).val('');
                $("#add_member_lname" ).val('');
                $("#add_member_team" ).val('');
                $('#add_member_row').attr("data-member-id",'');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                ajaxError(jqXHR, textStatus, errorThrown);
                button.prop('disabled',false);
            }
        });

        return false;
    });

    $('#select_edit_team').change(function(event){
        var team_id = $(this).val();
        if(team_id!=''){
            $('#edit_team_name').val($('option:selected', this).text());
            $('#add_team').hide();
            $('#save_team').show();
            $('#delete_team').show();
        }
        else{
            $('#add_team').show();
            $('#save_team').hide();
            $('#delete_team').hide();
            $('#edit_team_name').val('');
        }
    });

    $('#save_team').click(function(event){

        $('#add_team').show();
        $('#save_team').hide();
        $('#delete_team').hide();
        var button  =  $(this);
        var team_id = $('#select_edit_team').val();
        var team = {name :  $('#edit_team_name').val()};

        if(team.name.trim()===''){
            alert('You must provide a valid team name!');
            return false;
        }

        $('#edit_team_name').val('');
        $.ajax({
            type: 'PUT',
            url: 'api/v1/ccla/teams/'+team_id,
            data: JSON.stringify(team),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data,textStatus,jqXHR) {
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                ajaxError(jqXHR, textStatus, errorThrown);
                button.prop('disabled',false);
            }
        });
    });

    $('#add_team').click(function(event){

        var button =  $(this);
        var team = { name: $('#edit_team_name').val() , company_id : company_id};

        if(team.name.trim()===''){
            alert('You must provide a valid team name!');
            return false;
        }

        $('#edit_team_name').val('');
        $('#add_team').show();
        $('#save_team').hide();
        $('#delete_team').hide();
        button.prop('disabled',true);
        $.ajax({
            type: 'POST',
            url: 'api/v1/ccla/teams',
            data: JSON.stringify(team),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data,textStatus,jqXHR) {
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                ajaxError(jqXHR, textStatus, errorThrown);
                button.prop('disabled',false);
            }
        });
    });

    $('#delete_team').click(function(event){
        if(confirm('Are you sure? this will clear all memberships and all pending invitations as well.')){
            $('#edit_team_name').val('');
            $('#add_team').show();
            $('#save_team').hide();
            $('#delete_team').hide();
            var button =  $(this);
            button.prop('disabled',true);
            var team_id = $('#select_edit_team').val();
            $.ajax({
                type: 'DELETE',
                url: 'api/v1/ccla/teams/'+team_id,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data,textStatus,jqXHR) {
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    ajaxError(jqXHR, textStatus, errorThrown);
                    button.prop('disabled',false);
                }
            });
        }
    });
});
