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

    var form = $("#consultant_form");

    if(form.length > 0){

        form.marketplace_type_header();
        $('#expertise_areas_form').expertise_areas();
        $('#configuration_management_form').configuration_management_expertise();
        $('#reference_clients_form').reference_clients();
        $('#services_offered_form').services_offered();
        $('#support-channels-form').support_channels();
        $('#languages_spoken_form').spoken_languages();
        $('#offices_form').offices();
        $('#videos-form').videos();
        $('#additional-resources-form').additional_resources();

        //if we are editing data, load it ...
        if(typeof(consultant)!=='undefined'){
            //populate form and widgets
            $("#company_id",form).val(consultant.company_id);
            $("#company_id").trigger("chosen:updated");
            $("#name",form).val(consultant.name);
            $("#overview",form).val(consultant.overview);
            $("#call_2_action_uri",form).val(consultant.call_2_action_uri);
            if(consultant.active){
                $('#active',form).prop('checked',true);
            }
            else{
                $('#active',form).prop('checked',false);
            }
            $("#id",form).val(consultant.id);
            //reload widgets
            $("#videos-form").videos('load',consultant.videos);
            $("#support-channels-form").support_channels('load',consultant.regional_support);
            $("#additional-resources-form").additional_resources('load',consultant.additional_resources);
            $('#expertise_areas_form').expertise_areas('load',consultant.expertise_areas);
            $('#configuration_management_form').configuration_management_expertise('load',consultant.configuration_management);
            $('#reference_clients_form').reference_clients('load',consultant.reference_clients);
            $('#services_offered_form').services_offered('load',consultant.services_offered);
            $('#languages_spoken_form').spoken_languages('load',consultant.languages_spoken);
            $('#offices_form').offices('load',consultant.offices);
        }

        $('.save-consultant').click(function(event){
            event.preventDefault();
            event.stopPropagation();
            var button =  $(this);
            if(button.prop('disabled')){
                return false;
            }
            var form_validator = form.marketplace_type_header('getFormValidator');
            form_validator.settings.ignore = ".add-comtrol";
            var is_valid = form.valid();
            form_validator.settings.ignore = [];
            if(!is_valid) return false;
            form_validator.resetForm();

            var expertise_areas          = $('#expertise_areas_form').expertise_areas('serialize');
            var configuration_management = $('#configuration_management_form').configuration_management_expertise('serialize');
            var reference_clients        = $('#reference_clients_form').reference_clients('serialize');
            var services_offered         = $('#services_offered_form').services_offered('serialize');
            var regional_support         = $('#support-channels-form').support_channels('serialize');
            var languages_spoken         = $('#languages_spoken_form').spoken_languages('serialize');
            var offices                  = $('#offices_form').offices('serialize');
            var videos                   = $('#videos-form').videos('serialize');
            var additional_resources     = $('#additional-resources-form').additional_resources('serialize');

            if(expertise_areas!==false &&
                configuration_management!== false &&
                reference_clients!== false &&
                services_offered !== false &&
                regional_support !== false &&
                languages_spoken !== false &&
                offices !== false &&
                videos !== false &&
                additional_resources !== false ){

                ajaxIndicatorStart('saving data.. please wait..');

                var consultant = {
                    id         : parseInt($("#id",form).val()),
                    company_id : parseInt($("#company_id",form).val()),
                    name       : $("#name",form).val().trim(),
                    overview   : $("#overview",form).val().trim(),
                    call_2_action_uri : $("#call_2_action_uri",form).val().trim(),
                    active : $('#active',form).is(":checked"),
                    expertise_areas: expertise_areas,
                    configuration_management: configuration_management,
                    reference_clients: reference_clients,
                    services_offered: services_offered,
                    regional_support: regional_support,
                    languages_spoken: languages_spoken,
                    offices: offices,
                    videos: videos,
                    additional_resources: additional_resources
                }
                $('.save-consultant').prop('disabled',true);
                var type   = consultant.id > 0 ?'PUT':'POST';

                $(this).geocoding({
                    requests:consultant.offices,
                    buildGeoRequest:function(office){
                        var address =  office.address_1+' '+office.address_2;
                        address = address.trim();
                        if(address!=''){
                            address+= ', '+office.city;
                        }
                        var restrictions = {
                            locality: office.city,
                            country:office.country
                        };
                        if(office.state!=''){
                            restrictions.administrativeArea = office.state;
                            if(address!=''){
                                address+= ', '+office.state;
                            }
                        }
                        if(office.zip_code!=''){
                            //restrictions.postalCode = office.zip_code;
                            if(address!=''){
                                address+= ', '+office.zip_code;
                            }
                        }
                        var request = {componentRestrictions:restrictions};
                        if(address!=''){
                            request.address = address;
                        }
                        return request;
                    },
                    postProcessRequest:function(office, lat, lng){
                        office.lat = lat;
                        office.lng = lng;
                    },
                    processFinished:function(){
                        $.ajax({
                            type: type,
                            url: 'api/v1/marketplace/consultants',
                            data: JSON.stringify(consultant),
                            contentType: "application/json; charset=utf-8",
                            dataType: "json",
                            success: function (data,textStatus,jqXHR) {
                                window.location = listing_url;
                                ajaxIndicatorStop();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                ajaxIndicatorStop();
                                $('.save-consultant').prop('disabled',false);
                                ajaxError(jqXHR, textStatus, errorThrown);
                            }
                        });
                    },
                    cancelProcess:function(){
                        ajaxIndicatorStop();
                        $('.save-consultant').prop('disabled',false);
                    },
                    errorMessage:function(office){
                        return 'office: address ( address_1:'+office.address_1+', address_2:'+office.address_2+', city:'+office.city+',state: '+office.state+', country:'+office.country+' )';
                    }
                });
            }
            return false;
        });
    }
});