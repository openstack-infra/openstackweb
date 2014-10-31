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
    var form = $("#appliance_form");

    if(form.length > 0){

        //main form
        form.marketplace_type_header();

        $("#videos-form").videos();
        $("#guest_os_form").guest_os();
        $("#hypervisors_form").hypervisors();
        $("#components_form").components();
        $("#additional-resources-form").additional_resources();
        $("#support-channels-form").support_channels();
        //if we are editing data, load it ...
        if(typeof(appliance)!=='undefined'){
            //populate form and widgets
            $("#company_id",form).val(appliance.company_id);
            $("#company_id").trigger("chosen:updated");
            $("#name",form).val(appliance.name);
            $("#overview",form).val(appliance.overview);
            $("#call_2_action_uri",form).val(appliance.call_2_action_uri);
            if(appliance.active){
                $('#active',form).prop('checked',true);
            }
            else{
                $('#active',form).prop('checked',false);
            }
            $("#id",form).val(appliance.id);
            //reload widgets
            $("#guest_os_form").guest_os('load',appliance.guest_os);
            $("#hypervisors_form").hypervisors('load',appliance.hypervisors);
            $("#components_form").components('load',appliance.capabilities);
            $("#support-channels-form").support_channels('load',appliance.regional_support);
            $("#videos-form").videos('load',appliance.videos);
            $("#additional-resources-form").additional_resources('load',appliance.additional_resources);
        }

        $('.save-appliance').click(function(event){
            event.preventDefault();
            event.stopPropagation();
            var button =  $(this);
            if(button.prop('disabled')){
                return false;
            }
            var form_validator = form.marketplace_type_header('getFormValidator');
            form_validator.settings.ignore = ".add-comtrol";
            var is_valid = form.valid();
            if(!is_valid) return false;
            form_validator.resetForm();
            var additional_resources = $("#additional-resources-form").additional_resources('serialize');
            var regional_support     = $("#support-channels-form").support_channels('serialize');
            var capabilities         = $("#components_form").components('serialize');
            var guest_os             = $("#guest_os_form").guest_os('serialize');
            var hypervisors          = $("#hypervisors_form").hypervisors('serialize');
            var videos               = $("#videos-form").videos('serialize');

            if(additional_resources !== false &&
                regional_support    !== false &&
                capabilities        !== false &&
                guest_os            !== false &&
                hypervisors         !== false &&
                videos              !== false){

                //create distribution object and POST it
                var appliance = {};
                appliance.id                      = parseInt($("#id",form).val());
                appliance.company_id              = parseInt($("#company_id",form).val());
                appliance.name                    = $("#name",form).val();
                appliance.overview                = $("#overview",form).val();
                appliance.call_2_action_uri       = $("#call_2_action_uri",form).val();
                appliance.active                  = $('#active',form).is(":checked");;
                appliance.videos                  = videos;
                appliance.hypervisors             = hypervisors;
                appliance.guest_os                = guest_os;
                appliance.capabilities            = capabilities;
                appliance.regional_support        = regional_support;
                appliance.additional_resources    = additional_resources;

                var type = appliance.id > 0 ?'PUT':'POST';
                $('.save-appliance').prop('disabled',true);
                $.ajax({
                    type: type,
                    url: 'api/v1/marketplace/appliances',
                    data: JSON.stringify(appliance),
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data,textStatus,jqXHR) {
                        window.location = listing_url;
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        ajaxError(jqXHR, textStatus, errorThrown);
                        $('.save-appliance').prop('disabled',false);
                    }
                });
            }
            return false;
        });
    }
});

