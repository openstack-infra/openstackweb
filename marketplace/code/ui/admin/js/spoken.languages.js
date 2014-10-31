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
    var form_validator = null;

    var methods = {
        init : function(options) {
            form = $(this);

            if(form.length>0){

                table = $("table",form);

                $('#add_language_name',form).autocomplete({
                    source: 'api/v1/marketplace/consultants/languages',
                    minLength: 3
                })

                form_validator = form.validate({
                    rules: {
                        'add_language_name'  : {
                            required:true,
                            ValidPlainText:true,
                            maxlength: 125,
                            rows_max_count:[5,table],
                            validate_duplicate_field:[table,'.additional-language-name'] }
                    },
                    messages: {
                        'add_language_name':{
                            rows_max_count:'You reached the maximum allowed number Of Spoken Languages.',
                            validate_duplicate_field:'Language already defined.'
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

                $('tbody',table).sortable(
                    {
                        items: '> tr:not(.add-additional-language)',
                        update: function( event, ui ) {
                            renderOrders();
                        }
                    }
                );

                $('#add-new-additional-language').click(function(event){
                    var is_valid = form.valid();
                    event.preventDefault();
                    event.stopPropagation();
                    if (is_valid){
                        var new_language = {};
                        new_language.name = $('#add_language_name').val();
                        addAdditionalLanguage(new_language);
                        $('#add_language_name').val('');
                        form_validator.resetForm();
                    }
                    return false;
                });

                $(".remove-additional-language").live('click',function(event){
                    var remove_btn = $(this);
                    var tr = remove_btn.parent().parent();
                    tr.remove();
                    renderOrders();
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                });
            }
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
            var res = [];
            var rows = $("tbody > tr",table);
            for(var i=0;i<rows.length-1;i++){
                res.push($('input.additional-language-name',rows[i]).val());
            }
            return res;
        },
        load:function(spoken_languages){
            for(var i in spoken_languages){
                addAdditionalLanguage(spoken_languages[i]);
            }
        }
    };

    $.fn.spoken_languages = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.spoken_languages' );
        }
    };

    //helper functions
    function renderOrders(){
        var rows = $("tbody > tr",table);
        for(var i=0;i<rows.length-1;i++){
            var th_order = $('.additional-language-order',rows[i]);
            th_order.text(i+1);
        }
    }

    function addAdditionalLanguage(new_language){
        var rows_number = $("tbody > tr",table).length;

        var row_template = $('<tr><td class="additional-language-order" style="border: 1px solid #ccc;background:#eaeaea;width:5%;font-weight:bold;"></td>' +
            '<td style="border: 1px solid #ccc;width:30%;background:#fff;">' +
            '<input type="text" style="width:300px;" class="additional-language-name text autocompleteoff"></td>' +
            '<td style="border: 1px solid #ccc;width:30%;background:#fff;">' +
            '<a href="#" class="remove-additional-language">x&nbsp;Remove</a></td></tr>');

        var directives = {
            'td.additional-language-order':function(arg){ return rows_number;},
            'input.additional-language-name@value':'name',
            'input.additional-language-name@id'   : function(arg){ return 'additional-language-name_'+(rows_number);},
            'input.additional-language-name@name' : function(arg){ return 'additional-language-name_'+(rows_number);}
        };
        var html = row_template.render(new_language, directives);

        $(".add-additional-language",table).before(html);

        var name = $('#additional-language-name_'+(rows_number));
        name.rules("add",{
            required: {
                depends:function(){
                    $(this).val($.trim($(this).val()));
                    return true;
                }
            }});
        name.rules("add", { required:true });
        name.rules("add", { ValidPlainText:true });
        name.rules("add", { maxlength: 125});
        name.rules("add", { validate_duplicate_field:[table,'.additional-language-name']});
    }
// End of closure.
}( jQuery ));
