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
jQuery(document).ready(function($){

    var form = $("#public_cloud_form");

    if(form.length > 0){
        //main form validation

        //main form
        form.marketplace_type_header();

        $("#components_form").components();
        $("#hypervisors_form").hypervisors();
        $("#guest_os_form").guest_os();
        $("#videos-form").videos();
        $("#support-channels-form").support_channels();
        $("#additional-resources-form").additional_resources();
        $("#data-centers-form").datacenter_locations();
        $('#pricing_schema_form').pricing_schemas();

        //if we are editing data, load it ...
        if(typeof(public_cloud)!=='undefined'){
            //populate form and widgets
            $("#company_id",form).val(public_cloud.company_id);
            $("#company_id").trigger("chosen:updated");
            $("#name",form).val(public_cloud.name);
            $("#overview",form).val(public_cloud.overview);
            $("#call_2_action_uri",form).val(public_cloud.call_2_action_uri);
            if(public_cloud.active){
                $('#active',form).prop('checked',true);
            }
            else{
                $('#active',form).prop('checked',false);
            }

            //this is a draft
            if (public_cloud.live_service_id) {
                $("#id",form).val(public_cloud.id);
                $("#live_id",form).val(public_cloud.live_service_id);
            } else { //its not a draft is the live version, so we remove the id and set the live_service_id
                $("#live_id",form).val(public_cloud.id);
                $('.publish-public-cloud').prop('disabled',true);
            }

            //reload widgets
            $("#components_form").components('load',public_cloud.capabilities);
            if(public_cloud.capabilities.length>0){
                $('#pricing_schema_form').pricing_schemas('load',public_cloud.capabilities[0].pricing_schemas);
            }
            $("#hypervisors_form").hypervisors('load',public_cloud.hypervisors);
            $("#guest_os_form").guest_os('load',public_cloud.guest_os);
            $("#videos-form").videos('load',public_cloud.videos);
            $("#support-channels-form").support_channels('load',public_cloud.regional_support);
            $("#additional-resources-form").additional_resources('load',public_cloud.additional_resources);
            $("#data-centers-form").datacenter_locations('load',public_cloud.data_centers);

        }

        $('.save-public-cloud').click(function(event){
            var button =  $(this);
            if(button.prop('disabled')){
                return false;
            }
            event.preventDefault();
            event.stopPropagation();
            var form_validator = form.marketplace_type_header('getFormValidator');
            form_validator.settings.ignore = ".add-comtrol";
            var is_valid = form.valid();
            if(!is_valid) return false;
            form_validator.resetForm();
            var additional_resources = $("#additional-resources-form").additional_resources('serialize');
            var regional_support     = $("#support-channels-form").support_channels('serialize');
            var capabilities         = $("#components_form").components('serialize');
            var guest_os             = $("#guest_os_form").guest_os('serialize');
            var hyper_visors         = $("#hypervisors_form").hypervisors('serialize');
            var videos               = $("#videos-form").videos('serialize');
            var data_centers         = $("#data-centers-form").datacenter_locations('serialize');
            var pricing_schemas       = $("#pricing_schema_form").pricing_schemas('serialize');

            if(additional_resources !== false &&
                regional_support    !== false &&
                capabilities        !== false &&
                guest_os            !== false &&
                hyper_visors        !== false &&
                videos              !== false &&
                data_centers        !== false &&
                pricing_schemas     !== false
                ){

                ajaxIndicatorStart('saving data.. please wait..');

                //create public_cloud object and POST it
                var public_cloud = {};
                public_cloud.id                      = parseInt($("#id",form).val());
                public_cloud.live_service_id         = parseInt($("#live_id",form).val());
                public_cloud.company_id              = parseInt($("#company_id",form).val());
                public_cloud.name                    = $("#name",form).val();
                public_cloud.overview                = $("#overview",form).val();
                public_cloud.call_2_action_uri       = $("#call_2_action_uri",form).val();
                public_cloud.active                  = $('#active',form).is(":checked");
                public_cloud.videos                  = videos;
                public_cloud.hypervisors             = hyper_visors;
                public_cloud.guest_os                = guest_os;
                public_cloud.capabilities            = capabilities;
                for(var i in public_cloud.capabilities){
                    var c = public_cloud.capabilities[i];
                    c.pricing_schemas = pricing_schemas;
                }
                public_cloud.regional_support        = regional_support;
                public_cloud.additional_resources    = additional_resources;
                public_cloud.data_centers            = data_centers;


                var type   = public_cloud.id > 0 ?'PUT':'POST';

                $('.save-public-cloud').prop('disabled',true);


                $(this).geocoding({
                    requests:public_cloud.data_centers.locations,
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
                        $.ajax({
                            type: type,
                            url: 'api/v1/marketplace/public-clouds',
                            data: JSON.stringify(public_cloud),
                            contentType: "application/json; charset=utf-8",
                            dataType: "json",
                            success: function (data,textStatus,jqXHR) {
                                //window.location = listing_url;
                                if(public_cloud.id < 1) $("#id",form).val(data);
                                $('.publish-public-cloud').prop('disabled',false);
                                $('.save-public-cloud').prop('disabled',false);
                                ajaxIndicatorStop();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                ajaxIndicatorStop();
                                $('.save-public-cloud').prop('disabled',false);
                                ajaxError(jqXHR, textStatus, errorThrown);
                            }
                        });
                    },
                    cancelProcess:function(){
                        ajaxIndicatorStop();
                        $('.save-public-cloud').prop('disabled',false);
                    },
                    errorMessage:function(location){
                        return 'data center location: address ( city:'+location.city+',state: '+location.state+', country:'+location.country+' )';
                    }
                });


            }

            return false;
        });

        $('.publish-public-cloud').click(function(event){
            var button =  $(this);
            if(button.prop('disabled')){
                return false;
            }
            event.preventDefault();
            event.stopPropagation();
            var form_validator = form.marketplace_type_header('getFormValidator');
            form_validator.settings.ignore = ".add-comtrol";
            var is_valid = form.valid();
            if(!is_valid) return false;
            form_validator.resetForm();
            var additional_resources = $("#additional-resources-form").additional_resources('serialize');
            var regional_support     = $("#support-channels-form").support_channels('serialize');
            var capabilities         = $("#components_form").components('serialize');
            var guest_os             = $("#guest_os_form").guest_os('serialize');
            var hyper_visors         = $("#hypervisors_form").hypervisors('serialize');
            var videos               = $("#videos-form").videos('serialize');
            var data_centers         = $("#data-centers-form").datacenter_locations('serialize');
            var pricing_schemas       = $("#pricing_schema_form").pricing_schemas('serialize');

            if(additional_resources !== false &&
                regional_support    !== false &&
                capabilities        !== false &&
                guest_os            !== false &&
                hyper_visors        !== false &&
                videos              !== false &&
                data_centers        !== false &&
                pricing_schemas     !== false
                ){

                ajaxIndicatorStart('saving data.. please wait..');

                //create public_cloud object and POST it
                var public_cloud = {};
                public_cloud.id                      = parseInt($("#id",form).val());
                public_cloud.live_service_id         = parseInt($("#live_id",form).val());
                public_cloud.company_id              = parseInt($("#company_id",form).val());
                public_cloud.name                    = $("#name",form).val();
                public_cloud.overview                = $("#overview",form).val();
                public_cloud.call_2_action_uri       = $("#call_2_action_uri",form).val();
                public_cloud.active                  = $('#active',form).is(":checked");
                public_cloud.videos                  = videos;
                public_cloud.hypervisors             = hyper_visors;
                public_cloud.guest_os                = guest_os;
                public_cloud.capabilities            = capabilities;
                for(var i in public_cloud.capabilities){
                    var c = public_cloud.capabilities[i];
                    c.pricing_schemas = pricing_schemas;
                }
                public_cloud.regional_support        = regional_support;
                public_cloud.additional_resources    = additional_resources;
                public_cloud.data_centers            = data_centers;


                var url  = 'api/v1/marketplace/public-clouds/'+public_cloud.live_service_id;

                $('.publish-public-cloud').prop('disabled',true);


                $(this).geocoding({
                    requests:public_cloud.data_centers.locations,
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
                        $.ajax({
                            type: 'PUT',
                            url: url,
                            data: JSON.stringify(public_cloud),
                            contentType: "application/json; charset=utf-8",
                            dataType: "json",
                            success: function (data,textStatus,jqXHR) {
                                window.location = listing_url;
                                ajaxIndicatorStop();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                ajaxIndicatorStop();
                                $('.publish-public-cloud').prop('disabled',false);
                                ajaxError(jqXHR, textStatus, errorThrown);
                            }
                        });
                    },
                    cancelProcess:function(){
                        ajaxIndicatorStop();
                        $('.publish-public-cloud').prop('disabled',false);
                    },
                    errorMessage:function(location){
                        return 'data center location: address ( city:'+location.city+',state: '+location.state+', country:'+location.country+' )';
                    }
                });


            }

            return false;
        });

    }
});