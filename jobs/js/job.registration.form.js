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
function addLocation(location){
    var row_template = $('<tr><td>' +
        '<span class="location_city"></span>'+
        '<input class="location_city" type="hidden" id="location_city[]" name="location_city[]">' +
        '</td>' +
        '<td>' +
        '<span class="location_state"></span>'+
        '<input class="location_state" type="hidden" id="location_state[]" name="location_state[]"></td>' +
        '<td>' +
        '<span class="location_country"></span>'+
        '<input class="location_country" type="hidden" id="location_country[]" name="location_country[]"></td>' +
        '<td>' +
        '<button class="delete_location">Remove</button></td></tr>');

    var directives = {
        'span.location_city' : 'city',
        'input.location_city@value' : 'city',
        'span.location_state' :'state',
        'input.location_state@value' : 'state',
        'span.location_country' :'country',
        'input.location_country@value' : 'country'
    };

    var row = row_template.render(location, directives);

    $('#locations_table > tbody:last').append(row);
}

var form_validator = null;

jQuery(document).ready(function($) {

    var form = $('form.job-registration-form');

    var job_valid      = false;

    if(form.length > 0){
        var form_id = form.attr('id');

        jQuery.validator.addMethod("locations_min_count", function (value, element, arg) {
            var location_type = $('#'+form_id+'_location_type',form).val();
            if(location_type!=='Various') return true;
            var min_count      = arg[0];
            var table          = arg[1];
            var rows           = jQuery("tbody > tr",table);
            return rows.length >= min_count;
        }, "Please Add at least {0} items.");


        //main form validation
        form_validator = form.validate({
            onfocusout: false,
            focusCleanup: true,
            ignore: [],
            rules: {
                point_of_contact_name    : { required: true , ValidPlainText:true, maxlength: 100 },
                point_of_contact_email   : { required: true , email:true, maxlength: 100 },
                title        : { required: true , ValidPlainText:true, maxlength: 100 },
                description  : { required: true },
                instructions : { required: true },
                company_name : { required: true, ValidPlainText:true },
                url          : {required: true, complete_url: true, maxlength: 255},
                location_type  : {required: true,locations_min_count:[1,  $('#locations_table',form)]},
                city:{required:true}
            },
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
        // initialize widgets

        $('#'+form_id+'_company_name').autocomplete({
            source: 'api/v1/job-registration-requests/companies',
            minLength: 2
        });

        var d = new Date();
        d.setMonth(d.getMonth() + 2);
        $('#'+form_id+'_expiration_date').val(d.toLocaleDateString());
        $('#'+form_id+'_expiration_date').prop('disabled',true);

        $('#'+form_id+'_country',form).chosen({
            disable_search_threshold: 10,
            width: '315px'
        });

        $('#'+form_id+'_country',form).change(function () {
            form_validator.resetForm();
        });

        var location_type = $('#'+form_id+'_location_type',form).val();

        if(location_type==='Various')
            $('#locations_table',form).show();
        else
            $('#locations_table',form).hide();

        $('.delete_location',form).live('click',function(event){
            event.preventDefault();
            event.stopPropagation();
            var row = $(this).parent().parent();
            row.remove();
            return false;
        });

        $('#add_location',form).click(function(event){
            event.preventDefault();
            event.stopPropagation();

            form_validator.settings.ignore = ".job_control";
            var is_valid = form.valid();
            //re add rules
            form_validator.settings.ignore = [];

            if(is_valid){
                var location = {
                    city    : $('#'+form_id+'_city').val(),
                    state   : $('#'+form_id+'_state').val(),
                    country : $('#'+form_id+'_country').val()
                };

                $('#'+form_id+'_city').val('');
                $('#'+form_id+'_state').val('');

                addLocation(location);
            }

            return false;
        });

        $('#'+form_id+'_location_type',form).change(function () {
            var type = $(this).val();
            if(type =='Various'){
                $('#locations_table',form).show();
            }
            else{
                $('#locations_table',form).hide();
            }
        });

        $(form).submit(function( event ) {
            if(job_valid) return;

            $('#JobRegistrationRequestForm_JobRegistrationRequestForm_action_saveJobRegistrationRequest').prop('disabled', true);
            event.preventDefault();
            event.stopPropagation();

            form.find('textarea').each(function() {
                var text_area = $(this);
                var text_editor = tinyMCE.get(text_area.attr('id'));
                if (text_editor)
                    text_area.val(text_editor.getContent());
            });
            form_validator.settings.ignore = ".physical_location";
            var is_valid = form.valid();
            //re add rules
            form_validator.settings.ignore = [];

            if(is_valid){

                ajaxIndicatorStart('saving data.. please wait..');
                var locations = [];
                var location_type = $('#'+form_id+'_location_type',form).val();

                if(location_type === 'Various'){
                     var rows  =  $("tbody > tr",$('#locations_table',form));

                    for(var i=0; i < rows.length;i++){
                        var row = rows[i];
                        locations.push({
                            city    : $('input.location_city',row).val(),
                            state   : $('input.location_state',row).val(),
                            country : $('input.location_country',row).val()
                        });
                    }

                }
                $(this).geocoding({
                    requests:locations,
                    buildGeoRequest:function(location){
                        var restrictions = {
                            locality: location.city,
                            country:location.country
                        };
                        if(location.state!=''){
                            restrictions.administrativeArea = location.state;
                        }
                        var request = {componentRestrictions:restrictions};
                        return request;
                    },
                    postProcessRequest:function(location, lat, lng){
                        location.lat = lat;
                        location.lng = lng;
                    },
                    processFinished:function(){
                        job_valid = true;
                        form_validator.settings.ignore = ".physical_location";
                        form.submit();
                        ajaxIndicatorStop();
                    },
                    cancelProcess:function(){
                        job_valid = false;
                        ajaxIndicatorStop();
                        $('#JobRegistrationRequestForm_JobRegistrationRequestForm_action_saveJobRegistrationRequest').prop('disabled', false);
                    },
                    errorMessage:function(location){
                        return 'job location: address ( city:'+location.city+',state: '+location.state+', country:'+location.country+' )';
                        $('#JobRegistrationRequestForm_JobRegistrationRequestForm_action_saveJobRegistrationRequest').prop('disabled', false);
                    }
                });
                return false;
            }
        });
    }
});