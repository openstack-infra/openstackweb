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


    var form  = null;
    var table = null;
    var form_validator = null;

    var methods = {
        init : function(options) {
            form = $(this);
            if(form.length>0){

                table = $("table",form);

                form_validator = form.validate({
                    rules: {
                        'add-office-address1'  : {
                            ValidPlainText:true,
                            maxlength: 255,
                            validate_duplicate_field:[table,'.office-address1']
                        },
                        'add-office-address2'  : {
                            ValidPlainText:true,
                            maxlength: 255,
                            validate_duplicate_field:[table,'.office-address2']
                        },
                        'add-office-city'  : {
                            required:true,
                            ValidPlainText:true,
                            maxlength: 125
                        },
                        'add-office-state'  : {
                            ValidPlainText:true,
                            maxlength: 125
                        },
                        'add-office-zip-code'  : {
                            maxlength: 25
                        },
                        'add-office-country': {
                            required:true
                        }
                    },
                    ignore:[],
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

                $('#add-office-country',form).chosen({disable_search_threshold: 10});

                $('#add-office-country',form).change(function () {
                    form_validator.resetForm();
                });

                $('tbody',table).sortable(
                    {
                        items: '> tr:not(.add-additional-office)',
                        update: function( event, ui ) {
                            renderOrders();
                        }
                    }
                );

                $('.remove-additional-office').live('click',function(event){
                    var remove_btn = $(this);
                    var tr = remove_btn.parent().parent();
                    tr.remove();
                    renderOrders();
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                });

                $('#add-additional-office').click(function(event){
                    event.preventDefault();
                    event.stopPropagation();
                    var is_valid = form.valid();
                    if(is_valid){
                        var address1 = $('#add-office-address1',form);
                        var address2 = $('#add-office-address2',form);
                        var city     = $('#add-office-city',form);
                        var state    = $('#add-office-state',form);
                        var zip_code = $('#add-office-zip-code',form);
                        var country  = $('#add-office-country',form);

                        var new_office = {
                            address_1: address1.val(),
                            address_2: address2.val(),
                            city     : city.val(),
                            state    : state.val(),
                            zip_code : zip_code.val(),
                            country  : country.val()
                        };

                        addAdditionalOffice(new_office);

                        address1.val('');
                        address2.val('');
                        city.val('');
                        state.val('');
                        zip_code.val('');
                        country.val('');
                        country.trigger("chosen:updated");
                        form_validator.resetForm();
                    }
                    return false;
                });

            }
        },
        serialize:function(){
            //remove validator for add controls
            form_validator.settings.ignore = ".add-control";
            var is_valid = form.valid();
            //re add rules
            form_validator.settings.ignore = [];
            if(!is_valid){
                return false;
            }
            var res = [];
            var rows = $("tbody > tr",table);
            for(var i=0;i<rows.length-1;i++){
                var new_office = {
                    address_1: $('input.office-address1',rows[i]).val().trim(),
                    address_2: $('input.office-address2',rows[i]).val().trim(),
                    city     : $('input.office-city',rows[i]).val().trim(),
                    state    : $('input.office-state',rows[i]).val().trim(),
                    zip_code : $('input.office-zip-code',rows[i]).val().trim(),
                    country  : $('.office-country-select',rows[i]).val()
                };
                res.push(new_office);
            }
            return res;
        },
        load: function(offices){
            for(var i in offices){
                addAdditionalOffice(offices[i]);
            }
        }
    };

    $.fn.offices = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.offices' );
        }
    };

    //helpers
    function renderOrders(){
        var rows = $("tbody > tr",table);
        for(var i=0;i<rows.length-1;i++){
            var th_order = $('.additional-office-order',rows[i]);
            th_order.text(i+1);
        }
    }

    function addAdditionalOffice(office){
        var slug =  convertToSlug(office.address_1+office.address_2+office.city+office.state+office.zip_code+office.country);

        var rows_number = $("tbody > tr",table).length;

        var row_template = $('<tr>' +
            '<td class="additional-office-order"></td>'+
            '<td><input type="text" class="office-address1 text autocompleteoff" value="" style="width:150px;"/></td>'+
            '<td><input type="text" class="office-address2 text autocompleteoff" value="" style="width:150px;"/></td>'+
            '<td><input type="text" class="office-city text autocompleteoff" value="" style="width:150px;"/></td>'+
            '<td><input type="text" class="office-state text autocompleteoff" value="" style="width:150px;"/></td>'+
            '<td><input type="text" class="office-zip-code text autocompleteoff" value="" style="width:50px;"/></td>'+
            '<td><div class="office-country" style="display:inline-block;max-width:200px;"></div></td>'+
            '<td><a href="#" class="remove-additional-office">x&nbsp;Remove</a></td>'+
            '</tr>');

        var directives = {
            'td.additional-office-order':function(arg){ return rows_number;},
            'input.office-address1@value':'address_1',
            'input.office-address1@id':function(arg){
                return 'office-address1_'+slug;
            },
            'input.office-address1@name':function(arg){
                return 'office-address1_'+slug;
            },
            'input.office-address2@value':'address_2',
            'input.office-address2@id':function(arg){
                return 'office-address2_'+slug;
            },
            'input.office-address2@name':function(arg){
                return 'office-address2_'+slug;
            },
            'input.office-city@value':'city',
            'input.office-city@id':function(arg){
                return 'office-city_'+slug;
            },
            'input.office-city@name':function(arg){
                return 'office-city_'+slug;
            },
            'input.office-state@value':'state',
            'input.office-state@id':function(arg){
                return 'office-state_'+slug;
            },
            'input.office-state@name':function(arg){
                return 'office-state_'+slug;
            },
            'input.office-zip-code@value':'zip_code',
            'input.office-zip-code@id':function(arg){
                return 'office-zip-code_'+slug;
            },
            'input.office-zip-code@name':function(arg){
                return 'office-zip-code_'+slug;
            },
            '.office-country':function(arg){
                var select_name = 'office_country_'+slug;
                var select = $('#add-office-country',form).clone();
                select.removeClass()
                    .attr('id',select_name)
                    .attr('name',select_name)
                    .attr('style','')
                    .addClass('convert-to-chosen')
                    .addClass('office-country-select')
                    .addClass('countries-ddl');
                var option_val = arg.context.country;
                select.find("option[value="+option_val+"]").attr("selected","selected");
                return select[0].outerHTML;
            }
        };
        var html = row_template.render(office, directives);
        $(".add-additional-office",table).before(html);

        $('.convert-to-chosen', table).chosen({disable_search_threshold: 3});

        $('.convert-to-chosen', table).rules('add',{required:true});

        $('.convert-to-chosen', table).removeClass('convert-to-chosen');

        $('#office-address1_'+slug , table).rules('add',{
                ValidPlainText:true,
                maxlength: 255,
                validate_duplicate_field:[table,'.office-address1']
        });

        $('#office-address2_'+slug, table).rules('add',{
            ValidPlainText:true,
            maxlength: 255,
            validate_duplicate_field:[table,'.office-address2']
        });

        $('#office-city_'+slug, table).rules('add',{
            required:true,
            ValidPlainText:true,
            maxlength: 125
        });

        $('#office-state_'+slug, table).rules('add',{
            ValidPlainText:true,
            maxlength: 125});

        $('#office-zip-code_'+slug, table).rules('add',{
            ValidPlainText:true,
            maxlength: 25});
    }
// End of closure.
}( jQuery ));
