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

    var form            = null;
    var table_regions   = null;
    var table_az        = null;
    var form_validator  = null;
    var locations       = [];

    var methods = {
        init : function(options){
            form = $(this);
            //regions

            table_regions = $('#datacenter-regions-table',form);
            table_az      = $('#az-table',form);

            $('#add-datacenter-location-country',form)
                .addClass('add-location-control')
                .addClass('location-country');

            $('#add-datacenter-location-country',form).chosen({disable_search_threshold: 3});

            $('#add-datacenter-location-region',form).chosen({disable_search_threshold: 3});

            $('#add_region_color',form).ColorPicker({
                onSubmit: function(hsb, hex, rgb, el) {
                    $(el).val(hex);
                    $(el).css('backgroundColor', '#' + hex);
                    $(el).ColorPickerHide();
                },
                onBeforeShow: function () {
                    $(this).ColorPickerSetColor(this.value);
                },
                onChange: function (hsb, hex, rgb) {
                    var input = $(this).data('colorpicker').el;
                    $(input).val(hex);
                    $(input).css('backgroundColor', '#' + hex);
                }
            }).bind('keyup', function(){
                $(this).ColorPickerSetColor(this.value);
            });

            $.validator.addMethod("validate_duplicate_location", function (value, element, arg) {
                var locations     = $('.data-center-location','#data-center-locations-container');
                var location_info_container = arg[0];
                var city          = $('.location-city',location_info_container).val();
                var state         = $('.location-state',location_info_container).val();
                var country       = $('.location-country',location_info_container).val();
                var region        = $('.location-region',location_info_container).val();
                var location_slug = convertToSlug(city)+'-'+convertToSlug(state)+'-'+convertToSlug(country)+'-'+convertToSlug(region);
                for(var i=0;i<locations.length;i++){
                    if($(locations[i]).attr('id') === location_slug
                        && $(element).attr('id') != $('.location-city',locations[i]).attr('id'))
                        return false;
                }
                return true;

            }, "Location is already defined!");

            form_validator = form.validate({
                rules: {
                    'add_region_name'  : {
                        required:true,
                        ValidPlainText:true,
                        maxlength: 125,
                        validate_duplicate_field:[table_regions,'.region-name']
                    },
                    'add_region_endpoint': {
                        complete_url:true,
                        maxlength: 512
                    },
                    'add_region_color': {
                        required:true,
                        maxlength: 6,
                        color: true
                    },
                    'add-datacenter-location-city':{
                        required:true,
                        maxlength: 125,
                        ValidPlainText:true,
                        validate_duplicate_location:[$('.location-info-container',form)]
                    },
                    'add-datacenter-location-state':{
                        maxlength: 50,
                        ValidPlainText:true
                    },
                    'add-datacenter-location-country':{
                        required:true
                    },
                    'add-datacenter-location-region':{
                        required:true
                    },
                    'add-datacenter-location-zone-name':{
                        required:true,
                        maxlength: 125,
                        validate_duplicate_field:[table_az,'.zone-name']
                    }
                },
                messages: {
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

            $('.region-name').live('focusout', function (event) {
                var former_value  = $(this).data('original-value');
                var current_value = $(this).val();
                repopulateRegions({
                    former_value : former_value,
                    current_value: current_value
                });
                $(this).data('original-value',current_value)
            });

            $('#add-new-datacenter-region',form).click(function(event){
                event.preventDefault();
                event.stopPropagation();

                form_validator.settings.ignore = ".add-location-control";
                var is_valid = form.valid();
                //re add rules
                form_validator.settings.ignore = [];
                if(!is_valid){
                    return false;
                }

                var input_name     = $('#add_region_name',table_regions);
                var input_color = $('#add_region_color',table_regions);
                var input_endpoint = $('#add_region_endpoint',table_regions);
                var region = {
                  name    : input_name.val(),
                  color   : input_color.val(),
                  endpoint: input_endpoint.val()
                };
                input_name.val('');
                input_endpoint.val('');
                input_color.val('');
                input_color.css('backgroundColor', '#FFFFFF');
                addDataCenterRegion(region);
                repopulateRegions();
                return false;
            });

            $('.remove-additional-region',table_regions).live('click',function(event){
                var remove_btn = $(this);
                var tr = remove_btn.parent().parent();
                tr.remove();
                event.preventDefault();
                event.stopPropagation();
                repopulateRegions();
                return false;
            });

            //locations
            $('#add-datacenter-location',form).click(function(event){
                event.preventDefault();
                event.stopPropagation();
                form_validator.resetForm();
                form_validator.settings.ignore = ".add-region-control,.add-az-control";
                var is_valid = form.valid();
                //re add rules
                form_validator.settings.ignore = [];

                if(!is_valid){
                    return false;
                }

                var input_city    = $('#add-datacenter-location-city',form);
                var input_state   = $('#add-datacenter-location-state',form);
                var input_country = $('#add-datacenter-location-country',form);
                var input_region  = $('#add-datacenter-location-region',form);

                var location = {
                    city               : input_city.val(),
                    state              : input_state.val(),
                    country            : input_country.val(),
                    region             : input_region.val(),
                    availability_zones : []
                };

                $("#az-table tbody tr:not(.add-az-row)",form).each(function(){
                    var tr   = $(this);
                    var az   = {};
                    az.name  = $('.zone-name',tr).val();
                    location.availability_zones.push(az);
                });
                
                addDataCenterLocation(location);
                input_city.val('');
                input_state.val('');
                $('option:selected',input_country).removeAttr("selected");
                $('option:selected',input_region).removeAttr("selected");
                input_country.trigger("chosen:updated");
                input_region.trigger("chosen:updated");
                $("#az-table tbody tr:not(.add-az-row)",form).remove();
                return false;
            });


            $('.remove-data-center','#data-center-locations-container').live('click',function(event){
                event.preventDefault();
                event.stopPropagation();
                var data_center_slug = $(this).attr('data-slug');
                $('#'+data_center_slug,form).remove();
                return false;
            });
            //az

            $('#add-az',table_az).click(function(event){
                event.preventDefault();
                event.stopPropagation();
                form_validator.settings.ignore = ".add-region-control,.location-control";
                var is_valid = form.valid();
                //re add rules
                form_validator.settings.ignore = [];
                if(!is_valid){
                    return false;
                }
                var input_az_name = $('#add-datacenter-location-zone-name',table_az);
                var az = {name:input_az_name.val()};
                addAZRow(az,table_az);
                input_az_name.val('');
                return false;
            });

            $('.remove-az',form).live('click',function(event){
                event.preventDefault();
                event.stopPropagation();
                var remove_btn = $(this);
                var tr = remove_btn.parent().parent();
                tr.remove();
                return false;
            });

            $('.add-az',form).live('click',function(event){
                event.preventDefault();
                event.stopPropagation();
                var location_slug = $(this).attr('data-slug');
                var container     = $('#'+location_slug,'#data-center-locations-container');
                var row           = $(this).parent().parent();
                var name          = $('#add-az-name-'+location_slug,row);
                var valid         = name.valid()
                if(valid) {
                    var az = { name: name.val() };
                    addAZRow(az, $('.az-table',container));
                    name.val('');
                }
                return false;
            });
        },
        serialize: function(){
            //remove validator for add controls
            form_validator.settings.ignore = ".add-control";
            var is_valid = form.valid();
            //re add rules
            form_validator.settings.ignore = [];
            if(!is_valid){
                return false;
            }
            form_validator.resetForm();
            var res = {
                regions:[],
                locations:[]
            };
            //regions
            var rows = $("tbody > tr",table_regions);
            for(var i=0;i<rows.length-1;i++){
                var region = {
                    name     : $('input.region-name',rows[i]).val().trim(),
                    endpoint : $('input.region-endpoint',rows[i]).val().trim(),
                    color    : $('input.region-color',rows[i]).val().trim()
                }
                region.id = convertToSlug(region.name);
                res.regions.push(region);
            }
            //locations
            $('.data-center-location',form).each(function(){
                var container    = $(this);
                var location     = {
                    city               : $('.location-city',container).val().trim(),
                    state              : $('.location-state',container).val().trim(),
                    country            : $('.location-country',container).val().trim(),
                    region             : $('.location-region',container).val().trim(),
                    availability_zones : []
                };

                $("tr.new-zone:not(.add-az-row)",container).each(function(){
                    location.availability_zones.push({ name : $('input.new-zone-name', $(this)).val() });
                });
                res.locations.push(location);
            });

            return res;
        },
        load:function(data){
            for(var i in data.regions){
                addDataCenterRegion(data.regions[i]);
            }
            repopulateRegions();
            for(var i in data.locations){
                addDataCenterLocation(data.locations[i]);
            }
        }
    };

    $.fn.datacenter_locations = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.datacenter_locations' );
        }
    };

    //helpers

    function repopulateRegions(event){

        var regions = getCurrentRegions();

        $('.add-datacenter-location-region,.location-region',form).each(function(){
            var dll_region     = $(this);
            var current_region = dll_region.val();

            dll_region.html('');
            dll_region.append($('<option>', {
                value: '',
                text : '--select region--'
            }));

            for(var i in regions){
                dll_region.append($('<option>', {
                    value: regions[i].id,
                    text : regions[i].name
                }));
            }

            if(typeof(event)!=='undefined'){
                var former_value  = convertToSlug(event.former_value);
                var current_value = convertToSlug(event.current_value);
                if(current_region === former_value)
                    current_region = current_value;
            }
            if(current_region != '')
                dll_region.find("option[value="+current_region+"]").attr("selected","selected");
            dll_region.trigger("chosen:updated");
        });
    }

    function getCurrentRegions(){
        var regions = [];
        var rows = $("tbody > tr:not(.add-additional-datacenter-region)",table_regions);
        for(var i=0;i<rows.length;i++){
            var row = rows[i];
            var region = {
              name: $('.region-name',row).val(),
              endpoint: $('.region-endpoint',row).val()
            };
            region.id = convertToSlug(region.name);
            regions.push(region);
        }
        return regions;
    }

    function addDataCenterRegion(region){

        var rows_number = $("tbody > tr",table_regions).length;
        var slug        = convertToSlug(region.name);

        var row_template = $('<tr class="datacenter-region">'+
            '<td style="border: 1px solid #ccc;width:50%;background:#fff;">'+
            '<input type="text" style="width:300px;" class="text autocompleteoff region-name">'+
            '</td>'+
            '<td style="border: 1px solid #ccc;width:25%;background:#fff;">'+
            '<input type="text" class="text autocompleteoff region-color" value="" style="width:50px;" maxlength="6">'+
            '</td>'+
            '<td style="border: 1px solid #ccc;width:50%;background:#fff;">'+
            '<input type="text" style="width:300px;" class="text autocompleteoff region-endpoint">'+
            '</td>'+
            '<td style="border: 1px solid #ccc;background:#eaeaea;width:10%;color:#cc0000;">'+
            '<a href="#" class="remove-additional-region">x&nbsp;Remove</a>'+
            '</td>'+
            '</tr>');

        var directives = {
            '.region-name@value':'name',
            '.region-name@id':function(arg){ return slug;},
            '.region-name@name':function(arg){ return slug;},
            'input.region-endpoint@value':'endpoint',
            'input.region-endpoint@id'   : function(arg){ return 'region_endpoint_'+(rows_number);},
            'input.region-endpoint@name' : function(arg){ return 'region_endpoint_'+(rows_number);},
            'input.region-color@value':'color',
            'input.region-color@id'   : function(arg){ return 'region_color'+(rows_number);},
            'input.region-color@name' : function(arg){ return 'region_color_'+(rows_number);}
        };
        var html = row_template.render(region, directives);
        html.attr('id',slug);

        $(".add-additional-datacenter-region",table_regions).before(html);

        $('.region-color',html).ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                $(el).val(hex);
                $(el).css('backgroundColor', '#' + hex);
                $(el).ColorPickerHide();
            },
            onBeforeShow: function () {
                $(this).ColorPickerSetColor(this.value);
            },
            onChange: function (hsb, hex, rgb) {
                var input = $(this).data('colorpicker').el;
                $(input).val(hex);
                $(input).css('backgroundColor', '#' + hex);
            }
        }).bind('keyup', function(){
            $(this).ColorPickerSetColor(this.value);
        });

        $('.region-name',html).data('original-value',$('.region-name',html).val());

        $('.region-name',html).rules('add',{
            required:true,
            ValidPlainText:true,
            maxlength: 125,
            validate_duplicate_field:[table_regions,'.region-name']
        });

        $('.region-endpoint',html).rules('add',{
            complete_url:true,
            maxlength: 512
        });

        $('.region-color',html).rules('add',{
            required:true,
            maxlength: 6,
            color: true
        });

        $('.region-color',html).css('backgroundColor', '#' + region.color);
    }

    function addAZRow(az,az_table){
        var rows_number = $("tbody > tr",az_table).length;
        var slug        = convertToSlug(az.name);
        var row_template = $(
            '<tr class="data-center-location-az new-zone">'+
            '<td style="border: 1px solid #ccc;width:45%;">'+
            '<input type="text" style="width:90%;" value="" maxlength="125" class="zone-name text new-zone-name">'+
            '</td>'+
            '<td style="border: 1px solid #ccc;;width:10%;color:#cc0000;">'+
            '<a href="#" class="remove-az">x&nbsp;Remove</a>'+
            '</td>'+
            '</tr>');

        var row = row_template.render(az, directives);
        var directives = {
            'input.zone-name@value':'name',
            'input.zone-name@id'   : function(arg){ return 'az_name'+(rows_number)+slug;},
            'input.zone-name@name' : function(arg){ return 'region_name_'+(rows_number)+slug;}
        };
        var row = row_template.render(az, directives);
        //insert new row
        $(".add-az-row",az_table).before(row);
    }

    function addDataCenterLocation(location){
        var location_slug = convertToSlug(location.city)+'-'+convertToSlug(location.state)+'-'+convertToSlug(location.country)+'-'+convertToSlug(location.region);
        var location_template = $('<div id="'+location_slug+'" class="data-center-location" style="padding-top:15px;padding-bottom:15px;">' +
        '<div class="location-info-container" style="padding-top:40px;display:inline;width:80%;margin-bottom:30px;">'+
        '<div style="float:left;display:inline;width:15%;">'+
        '<label class="left">City:</label>'+
        '<input type="text" class="text autocompleteoff location-control location-city" style="width:80%;" maxlength="125">'+
        '</div>'+
        '<div style="float:left;display:inline;width:15%;">'+
        '<label class="left">State:</label>'+
        '<input type="text" class="text autocompleteoff location-control location-state"style="width:80%;" maxlength="125">'+
        '</div>'+
        '<div style="float:left;display:inline;padding-left:10px;">'+
        '<label class="left">Country:</label><br>'+
        '<div style="display:inline-block;max-width:200px;" class="location-country-container">'+
        '</div>'+
        '</div>'+
        '<div style="float:left;display:inline;padding-left:10px;">'+
        '<label class="left">Region Name:</label><br>'+
        '<div style="display:inline-block;max-width:200px;" class="location-region-container">'+
        '</div>'+
        '</div>'+
        '<div style="float:left;width:20%;">'+
        '<a href="#" class="roundedButton addDeploymentBtn remove-data-center" style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;display:inline;">Remove Data Center</a>'+
        '</div>'+
        '</div>'+
        '<div style="border: 1px solid #ccc; border-collapse:collapse;clear:both;width:70%;padding-left:30px;padding-top:10px;padding-bottom:10px;">'+
        '<strong>Zones covered by your Data Center</strong>'+
        'Please tell us which zones your data center services:<br>'+
        '<table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;width:90%;margin:0;padding:0;" class="az-table">'+
        '<thead>'+
        '<tr>'+
        '<th style="border: 1px solid #ccc;background:#eaeaea;width:10%;">Zone Name</th>'+
        '<th style="border: 1px solid #ccc;background:#eaeaea;width:10%;">Add/Remove</th>'+
        '</tr>'+
        '</thead>'+
        '<tbody>'+
        '<tr class="new-zone">'+
        '<td style="border: 1px solid #ccc;width:45%;">'+
        '<input type="text" class="new-zone-name location-control zone-name" maxlength="125" value="" style="width:90%;">'+
        '</td>'+
        '<td style="border: 1px solid #ccc;;width:10%;color:#cc0000;">'+
        '<a href="#" class="remove-az">-&nbsp;Remove</a>'+
        '</td>'+
        '</tr>'+
        '<tr class="add-az-row">'+
        '<td style="border: 1px solid #ccc;width:45%;">'+
        '<input type="text" class="zone-name text add-control location-control add-location-control add-az-control" maxlength="125" value="" style="width:90%;">'+
        '</td>'+
        '<td style="border: 1px solid #ccc;;width:10%;color:#cc0000;">'+
        '<a href="#" class="add-az">+&nbsp;Add</a>'+
        '</td>'+
        '</tr>'+
        '</tbody>'+
        '</table>'+
        '</div>'+
        '</div>');

        var directives = {
           'input.location-city@value'   :'city',
           'input.location-city@id'      : function(arg){
                return 'location_city_'+location_slug;
           },
           'input.location-city@name'      : function(arg){
               return 'location_city_'+location_slug;
            },
           'input.location-state@value'  :'state',
            'input.location-state@id'      : function(arg){
                return 'location_state_'+location_slug;
            },
            'input.location-state@name'      : function(arg){
                return 'location_state_'+location_slug;
            },
            '.remove-data-center@data-slug':function(arg){
              return location_slug;
            },
           'div.location-country-container':function(arg){
               var select_name='location_country_'+location_slug;
               var select = $('#add-datacenter-location-country',form).clone();
               select.removeClass()
                   .attr('id',select_name)
                   .attr('name',select_name)
                   .attr('style','')
                   .addClass('convert-to-chosen')
                   .addClass('location-country')
                   .addClass('add-location-control')
                   .addClass('location-comtrol')
                   .css('width','100%');

               var option_val = arg.context.country;
               select.find("option[value="+option_val+"]").attr("selected","selected");
               return select[0].outerHTML;
           },
           'div.location-region-container':function(arg){
               var select_name='location_region_'+location_slug;
               var select = $('#add-datacenter-location-region',form).clone();
               select.removeClass()
                   .attr('id',select_name)
                   .attr('name',select_name)
                   .attr('style','')
                   .addClass('convert-to-chosen')
                   .addClass('location-region')
                   .addClass('location-comtrol')
                   .addClass('add-location-control')
                   .css('width','100%');
               var option_val = arg.context.region;
               select.find("option[value="+option_val+"]").attr("selected","selected");
               return select[0].outerHTML;
           },
          '.add-az@data-slug':function(arg){
                return location_slug;
            },
          'input.add-az-control@id':function(arg){
              return 'add-az-name-'+location_slug;
          },
          'input.add-az-control@name':function(arg){
              return 'add-az-name-'+location_slug;
          },
          "tr.new-zone":{
                "az <- availability_zones": {
                    'input.new-zone-name@value':function(arg){
                        return arg.item.name;
                    },
                    'input.new-zone-name@id':function(arg){
                        return 'location_zone_'+convertToSlug(arg.item.name)+location_slug;
                    },
                    'input.new-zone-name@name':function(arg){
                        return 'location_zone_'+convertToSlug(arg.item.name)+location_slug;
                    },
                    'input.new-zone-name@data-name':function(arg){
                        return arg.item.name;
                    }
                }
            }
        };

        var data_center_container = location_template.render(location, directives);
        data_center_container.appendTo('#data-center-locations-container');
        //set widgets and validation rules...
        $('.convert-to-chosen', data_center_container).chosen({disable_search_threshold: 3});
        $('.convert-to-chosen', data_center_container).removeClass('convert-to-chosen');

        $('.location-city', data_center_container).rules('add', {
            required: true,
            ValidPlainText: true,
            maxlength: 125,
            validate_duplicate_location:[$('.location-info-container',data_center_container)]
        });

        $('.location-state', data_center_container).rules('add', {
            ValidPlainText: true,
            maxlength: 50
        });

        $('.location-country', data_center_container).rules('add', {
            required: true
        });

        $('.location-region', data_center_container).rules('add', {
            required: true
        });

        $('#add-az-name-'+location_slug, data_center_container).rules('add',{
            required: true,
            ValidPlainText: true,
            maxlength: 125,
            validate_duplicate_field:[$('.az-table',data_center_container),'.zone-name']
        });

        $('.zone-name', data_center_container).rules('add',{
            required: true,
            ValidPlainText: true,
            maxlength: 125,
            validate_duplicate_field:[$('.az-table',data_center_container),'.zone-name']
        });

    }
}( jQuery ));

