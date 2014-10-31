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
(function( $ ){

    var form  = null;
    var table = null;
    var settings = {};

    var methods = {
        init: function(options){

            settings = $.extend({
                // These are the defaults.
                versions_by_release_component_url: 'api/v1/marketplace/admin/openstack-releases/@RELEASE_ID/components/@COMPONENT_ID/versions'
            }, options );

            form = $(this);
            if(form.length > 0){

                table = $('table', form);

                $('.component-releases').change(function () {
                    var release_id          = $(this).val()
                    var component_id        = $(this).attr('data-component-id');
                    var supports_versioning = $(this).attr('data-component-supports-versioning');
                    if(!supports_versioning) return;

                    if(release_id!=''){
                        var url        = settings.versions_by_release_component_url.replace('@RELEASE_ID',release_id);
                        var url        = url.replace('@COMPONENT_ID',component_id);
                        $.ajax({
                                type: "GET",
                                url: url,
                                dataType: "json",
                                success: function (data,textStatus,jqXHR) {
                                    //load data...
                                    populateVersionsSelect(component_id,data)
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    alert( "Request failed: " + textStatus );
                                }
                        });
                    }
                    else{
                        clearSelect($component_id);
                    }
                });

                /*$('.release-api-versions').click(function () {
                    var component_id = $(this).attr('data-component-id');
                    var release_id   = $('#releases_component_'+component_id,table).val();
                    if(release_id!=''){

                    }
                    else{
                        clearSelect($component_id);
                    }
                });*/

                var form_validator = form.validate({
                    onfocusout: false,
                    focusCleanup: true,
                    ignore: [],
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

                $('.component-releases',form).each(function(){

                    var select       = $(this);
                    var codename     = select.attr('data-component-codename');
                    var releases     = component_releases[codename];
                    select.append($('<option>', {
                        value: "",
                        text: '--select--'
                    }));
                    if(releases.length>0){
                        for (var i in releases){
                            var release = releases[i];
                            select.append($('<option>', {
                                value: release.id,
                                text: release.name
                            }));
                        }
                    }
                });

                $(".component-releases",form).prop("disabled", true);
                $(".release-api-versions",form).prop("disabled", true);
                $(".component-pricing-schema",form).prop("disabled", true);
                $(".api-coverage",form).prop("disabled", true);

                $(".available-component").click(function(){
                    var enable              = $(this).is(":checked");
                    var component_id        = $(this).attr('value');
                    var supports_versioning = $(this).attr('data-supports-versioning');
                    if(enable){
                        $("#releases_component_"+component_id,form).prop("disabled", false);
                        $("#releases_component_"+component_id,form).rules('add',{ required:true });
                        if(supports_versioning){
                            $("#release_api_version_component_"+component_id,form).prop("disabled", false);
                            $("#api_coverage_amount_"+component_id,form).prop("disabled", false);

                            $("#release_api_version_component_"+component_id,form).rules('add',{ required:true });
                            $("#api_coverage_amount_"+component_id,form).rules('add',{ required:true });
                        }
                    }
                    else{
                        $("#releases_component_"+component_id,form).prop("disabled", true);
                        $("#releases_component_"+component_id,form).rules('remove','required');
                        if(supports_versioning){
                            $("#release_api_version_component_"+component_id,form).prop("disabled", true);
                            $("#release_api_version_component_"+component_id,form).rules('remove','required');

                            $("#api_coverage_amount_"+component_id,form).prop("disabled", true);
                            $("#api_coverage_amount_"+component_id,form).rules('remove','required');
                        }
                    }
                });
            }
        },
        serialize:function (){
            var is_valid = form.valid();
            if(!is_valid) return false;
            var components = [];
            //iterate over collection
            $(".available-component:checked").each(function(){
                var checkbox            = $(this);
                var component_id        = parseInt(checkbox.attr('value'));
                var supports_versioning = checkbox.attr('data-supports-versioning');
                var new_component = {};
                new_component.component_id = component_id
                new_component.release_id   = parseInt($('#releases_component_'+component_id+' option:selected').val());
                if(supports_versioning){
                    new_component.version_id = parseInt($('#release_api_version_component_'+component_id+' option:selected').val());
                    new_component.coverage   = parseInt($('#api_coverage_amount_'+component_id+' option:selected').val());
                }
                else{
                    new_component.version_id = 0;
                    new_component.coverage   = 0;
                }
                components.push(new_component)
            });
            return components;
        },
        load: function(components) {
            for(var i in components){
                var component = components[i];
                $('#component_'+component.component_id,form).prop('checked',true);
                $("#releases_component_"+component.component_id,form).prop("disabled", false);
                $("#release_api_version_component_"+component.component_id,form).prop("disabled", false);
                $("#api_coverage_amount_"+component.component_id,form).prop("disabled", false);
                //set combos values
                $("#releases_component_"+component.component_id,form).val(component.release_id);
                //rules
                $("#releases_component_"+component.component_id,form).rules('add',{ required:true });
                if(component.supports_versioning){
                    //set coverage
                    if(component.coverage > 0 && component.coverage < 50){
                        component.coverage = 50;
                    }
                    else if (component.coverage > 50){
                        component.coverage = 100;
                    }
                    $("#api_coverage_amount_"+component.component_id,form).val(component.coverage);
                    $("#api_coverage_amount_"+component.component_id,form).rules('add',{ required:true });
                    //version
                    var url = settings.versions_by_release_component_url.replace('@RELEASE_ID',component.release_id);
                    var url = url.replace('@COMPONENT_ID',component.component_id);
                    $.ajax({
                        async:false,
                        type: "GET",
                        url: url,
                        dataType: "json",
                        success: function (data,textStatus,jqXHR) {
                            //load data...
                            populateVersionsSelect(component.component_id,data)
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert( "Request failed: " + textStatus );
                        }
                    });
                    $("#release_api_version_component_"+component.component_id,form).val(component.version_id);
                    $("#release_api_version_component_"+component.component_id,form).rules('add',{ required:true });
                }
            }
        }
    };

    $.fn.components = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.components' );
        }
    };

    //helper functions
    function populateVersionsSelect($component_id ,data){
        var select = $("#release_api_version_component_"+$component_id);
        if(select.length>0){
            clearSelect($component_id);
            if(data.length>0){
                for (var i in data){
                    var version = data[i];
                    select.append($('<option>', {
                        value: version.id,
                        text: version.version
                    }));
                }
            }
        }
    }

    function clearSelect($component_id){
        var select = $("#release_api_version_component_"+$component_id);
        if(select.length>0){
            select.empty();
            select.append($('<option>', {
                value: "",
                text: '--select--'
            }));
        }
    }
// End of closure.
}( jQuery ));



