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


    var rest_urls = {
        SaveAffiliation:"/userprofile/SaveAffiliation",
        DeleteAffiliation:"/userprofile/DeleteAffiliation",
        GetAffiliation:"/userprofile/GetAffiliation",
        ListAffiliations:"/userprofile/ListAffiliations",
        ListOrganizations:"/userprofile/ListOrganizations",
        AffiliationsCount:"/userprofile/AffiliationsCount"
    };

    var settings = {};
    var local_storage = {};
    var last_id = 0;
    var affiliation_form = null;
    var affiliation_form_id ='';

    var methods = {
        init : function(options) {
            settings = $.extend({
                // These are the defaults.
                storage: "remote"//could be also "local"
            }, options );

            affiliation_form = $(this);
            affiliation_form_id = "#"+affiliation_form.attr("id");

            // register AJAX prefilter : options, original options
            $.ajaxPrefilter(function( options, originalOptions, jqXHR ) {

                // retry not set or less than 2 : retry not requested
                if( !originalOptions.retryMax || !originalOptions.retryMax >=2 ) return;
                // no timeout was setup
                if( !originalOptions.timeout >0 ) return;

                if( originalOptions.retryCount ) {
                    // increment retry count each time
                    originalOptions.retryCount++;
                }else{
                    // init the retry count if not set
                    originalOptions.retryCount = 1;
                    // copy original error callback on first time
                    originalOptions._error = originalOptions.error;
                };

                // overwrite error handler for current request
                options.error = function( _jqXHR, _textStatus, _errorThrown ){
                    // retry max was exhausted or it is not a timeout error
                    if( originalOptions.retryCount >= originalOptions.retryMax || _textStatus!='timeout' ){
                        // call original error handler if any
                        if( originalOptions._error ) originalOptions._error( _jqXHR, _textStatus, _errorThrown );
                        return;
                    };
                    // Call AJAX again with original options
                    $.ajax( originalOptions);
                };
            });

            if(affiliation_form.length>0){

                var affiliation_form_validator = affiliation_form.validate({
                    rules: {
                        OrgName  : {required: true, ValidPlainText:true},
                        StartDate: {required: true, dpDate: true},
                        EndDate  : {dpDate: true,dpCompareDate:'gt '+affiliation_form_id+'_StartDate'}
                    },
                    onfocusout: false,
                    invalidHandler: function(form, validator) {
                        var errors = validator.numberOfInvalids();
                        if (errors) {
                            validator.errorList[0].element.focus();
                        }
                    }
                });

                $("#add-affiliation").click(function(event){
                    event.preventDefault();
                    event.stopPropagation();
                    var dlg = $('#affiliation-edition-dialog');
                    var current = $('#Current',affiliation_form_id);
                    current.show();
                    dlg.dialog("option", "title", "Add Affiliation");
                    dlg.dialog("open");
                    return false;
                });

                var org_name = $(affiliation_form_id+'_OrgName');

                if(org_name.length>0){
                    org_name.autocomplete({
                        source: rest_urls.ListOrganizations,
                        minLength: 2,
                        open: function( event, ui ) {
                            org_name.autocomplete("widget").css('z-index',5000);
                        }
                    });

                }

                var date_picker_start = $(affiliation_form_id+"_StartDate");
                date_picker_start.datepicker({dateFormat: 'yy-mm-dd'});

                var date_picker_end = $(affiliation_form_id+"_EndDate");
                date_picker_end.datepicker({dateFormat: 'yy-mm-dd',onSelect:function(date_str,inst){
                    var date_arr = date_str.split("-");
                    var end_date = new Date(parseInt(date_arr[0]),parseInt(date_arr[1])-1,parseInt(date_arr[2]));
                    var today = new Date();
                    var current = $('#Current',affiliation_form_id);
                    if(end_date < today){
                        //reset checkbox
                        $(affiliation_form_id+"_Current").prop('checked', false);
                        current.hide();
                    }
                    else
                        current.show();
                }});

                $(affiliation_form_id+"_Current").click(function(event){
                    var checked = $(this).is(':checked');
                    var date_str = date_picker_end.val();
                    var date_arr = date_str.split("-");
                    var end_date = new Date(parseInt(date_arr[0]),parseInt(date_arr[1])-1,parseInt(date_arr[2]));
                    var today = new Date();
                    if(end_date < today && checked){
                        date_picker_end.val('');
                    }
                });

                $(".edit-affiliation").live('click',function(event){
                    var current = $('#Current',affiliation_form_id);
                    current.show();
                    var id = $(this).attr('data-id');
                    var dlg = $('#affiliation-edition-dialog');
                    dlg.dialog("option", "title", "Edit Affiliation");
                    switch(settings.storage){
                        case 'local':
                        {
                            var data = local_storage[id];
                            LoadAffiliationData(dlg,data);
                        }
                            break;
                        default:
                        {
                            $.ajax(
                                {
                                    type: "GET",
                                    url: rest_urls.GetAffiliation + '/' + id,
                                    dataType: "json",
                                    timeout:60000,
                                    retryMax: 2,
                                    success: function (data,textStatus,jqXHR) {
                                        //load data...
                                        LoadAffiliationData(dlg,data);
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        alert( "Request failed: " + textStatus );
                                    }
                                }
                            );
                        }
                            break;
                    }
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                });

                $(".del-affiliation").live('click',function(event){
                    var id = $(this).attr('data-id');
                    if(window.confirm("Are you sure?")){
                        switch(settings.storage){
                            case 'local':
                            {
                                delete local_storage[id];
                                LoadAffiliationList();
                            }
                                break;
                            default:
                            {
                               $.ajax(
                                    {
                                        type: "GET",
                                        url: rest_urls.DeleteAffiliation + '/' + id,
                                        dataType: "json",
                                        timeout:5000,
                                        retryMax: 2,
                                        complete: function (jqXHR,textStatus) {
                                            LoadAffiliationList();
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            alert( "Request failed: " + textStatus );
                                        }
                                    }
                                );
                            }
                            break;
                        }
                    }
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                });

                var affiliation_dialog_edit = $("#affiliation-edition-dialog");
                if(affiliation_dialog_edit.length>0){
                    affiliation_dialog_edit.dialog({
                        autoOpen: false,
                        height: 550 ,
                        width: 400,
                        modal: true,
                        resizable: false,
                        close: function( event, ui ) {
                            affiliation_form.cleanForm();
                            affiliation_form_validator.resetForm();
                        },
                        buttons: {
                            'Save': function () {
                                var is_valid = affiliation_form.valid();
                                if (!is_valid) return;
                                var affiliation     = affiliation_form.serializeForm();
                                var checked         = $(affiliation_form_id+"_Current").is(':checked');
                                affiliation.Current = checked?1:0;
                                var today           = new Date();
                                var yyyy            = today.getFullYear().toString();
                                var mm              = (today.getMonth()+1).toString(); // getMonth() is zero-based
                                var dd              = today.getDate().toString();

                                affiliation.ClientToday = yyyy +'-'+ (mm[1]?mm:"0"+mm[0])  +'-'+ (dd[1]?dd:"0"+dd[0]);
                                var $this = this;
                                switch(settings.storage){
                                    case 'local':
                                    {
                                        if(affiliation.Id==0){
                                            affiliation.Id = ++last_id;
                                        }
                                        local_storage[affiliation.Id] = affiliation;
                                        LoadAffiliationList();
                                    }
                                    break;
                                    default:
                                    {
                                        $.ajax(
                                            {
                                                type: "POST",
                                                url: rest_urls.SaveAffiliation,
                                                data: JSON.stringify(affiliation),
                                                contentType: "application/json; charset=utf-8",
                                                dataType: "json",
                                                timeout:60000,
                                                retryMax: 2,
                                                complete: function (jqXHR,textStatus) {
                                                    LoadAffiliationList();
                                                },
                                                error: function (jqXHR, textStatus, errorThrown) {
                                                    alert( "Request failed: " + textStatus );
                                                }
                                            }
                                        );
                                    }
                                        break;
                                }
                                $($this).dialog('close');
                            }
                        }
                    });
                }

                LoadAffiliationList();
            }
        },
        update : function() {
            LoadAffiliationList();
        },
        count:function(){
            switch(settings.storage){
                case 'local':
                        return Object.keys(local_storage).length;
                    break;
                default:
                {
                    var count = 0;
                    $.ajax(
                        {
                            async:false,
                            type: "GET",
                            url: rest_urls.AffiliationsCount,
                            contentType: "application/json; charset=utf-8",
                            dataType: "json",
                            success: function (data) {
                                count = parseInt(data);
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                alert( "Request failed: " + textStatus );
                            }
                        });
                    return count;
                }
                break;
            }
        },
        local_datasource:function(){
            if(settings.storage!='local'){
                $.error( 'storage is not set on local mode!' );
                return {};
            }
            return local_storage;
        }
    };

    $.fn.affiliations = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.affiliations' );
        }
    };

    function LoadAffiliationList(){
        switch(settings.storage){
            case 'local':
                LoadLocalAffiliationList();
                break;
            default:
                LoadRemoteAffiliationList();
                break;
        }
    }

    //helper functions

    function LoadAffiliationData(dlg,affiliation){
        $(affiliation_form_id+"_OrgName").val(affiliation.OrgName);
        $(affiliation_form_id+"_StartDate").val(affiliation.StartDate);
        if(affiliation.EndDate!='')
            $(affiliation_form_id+"_EndDate").val(affiliation.EndDate);
        $(affiliation_form_id+"_Id").val(affiliation.Id);
        $(affiliation_form_id+"_Current").prop('checked', affiliation.Current == 1 ? true : false);
        dlg.dialog("open");
    }

    function renderAffiliationList(data){
        if (data.length > 0) {
            //remove error message
            $("label.error[for='HoneyPotForm_RegistrationForm_Affiliations']").remove();

            var template = $('<ul><li><div class="affiliation-header">' +
                '<span class="title"></span>' +
                '<span class="affiliation-actions">&nbsp;' +
                '<a href="#" class="edit-affiliation" title="Edit Affiliation">Edit</a>&nbsp;' +
                '<a href="#" class="del-affiliation" title="Delete Affiliation">Delete</a>&nbsp;' +
                '</span></div></li></ul>');

            var directives = {
                'li': {
                    'affiliation<-context': {
                        'a.edit-affiliation@data-id': 'affiliation.Id',
                        'a.del-affiliation@data-id': 'affiliation.Id',
                        'span.title':function(arg){
                            var title = "<div class='org-name'><span><b>"+ arg.item.OrgName +"</b></span></div><div class='affiliation-info'>From " + arg.item.StartDate;
                            if(arg.item.EndDate!=''){
                                title+=' To ' + arg.item.EndDate +'</div>';
                            }
                            else{
                                title+=' (Current) </div>';
                            }
                            return title;
                        }
                    }
                }
            };
            $("#affiliations-container").html(template.render(data, directives));
        }
        else{
            $("#affiliations-container").html('');
        }
    }

    function LoadLocalAffiliationList(){
        var array = [];
        for ( var item in local_storage ){
            array.push( local_storage[ item ] );
        }
        renderAffiliationList(array);
    }

    function LoadRemoteAffiliationList(){
        $.ajax(
            {
                type: "GET",
                url: rest_urls.ListAffiliations,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    renderAffiliationList(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert( "Request failed: " + textStatus );
                },
                timeout:15000,
                retryMax: 5
            });
    };

    // End of closure.

}( jQuery ));
