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

                form_validator = form.validate({
                    rules: {
                        add_client_name  : {
                            required:true,
                            ValidPlainText:true,
                            maxlength: 125,
                            rows_max_count:[5,table],
                            validate_duplicate_field:[table,'.additional-customer-name'] }
                    },
                    messages: {
                        'add_client_name':{
                            rows_max_count:"You reached the maximum allowed number of Reference Clients.",
                            validate_duplicate_field:'Client name already defined.'
                        }
                    },
                    onfocusout: false,
                    focusCleanup: true
                });


                $('tbody',table).sortable(
                    {
                        items: '> tr:not(.add-additional-customer)',
                        update: function( event, ui ) {
                            renderOrders();
                        }
                    }
                );

                $('#add-new-additional-client').click(function(event){
                    var is_valid = form.valid();
                    event.preventDefault();
                    event.stopPropagation();
                    if (is_valid){
                        var new_customer = {};
                        new_customer.name = $('#add_client_name').val();
                        addAdditionalCustomer(new_customer);
                        $('#add_client_name').val('');
                        form_validator.resetForm();
                    }
                    return false;
                });

                $(".remove-additional-customer").live('click',function(event){
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
                res.push($('input.additional-customer-name',rows[i]).val());
            }
            return res;
        },
        load:function(clients){
            for(var i in clients){
                addAdditionalCustomer(clients[i]);
            }
        }
    };

    $.fn.reference_clients = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.reference_clients' );
        }
    };

    //helper functions
    function renderOrders(){
        var rows = $("tbody > tr",table);
        for(var i=0;i<rows.length-1;i++){
            var th_order = $('.additional-customer-order',rows[i]);
            th_order.text(i+1);
        }
    }

    function addAdditionalCustomer(new_customer){
        var rows_number = $("tbody > tr",table).length;

        var row_template = $('<tr><td class="additional-customer-order" style="border: 1px solid #ccc;background:#eaeaea;width:5%;font-weight:bold;"></td>' +
            '<td style="border: 1px solid #ccc;width:30%;background:#fff;">' +
            '<input type="text" style="width:300px;" class="additional-customer-name text autocompleteoff"></td>' +
            '<th style="border: 1px solid #ccc;width:30%;background:#fff;">' +
            '<a href="#" class="remove-additional-customer">x&nbsp;Remove</a></td></tr>>');

        var directives = {
            'td.additional-customer-order':function(arg){ return rows_number;},
            'input.additional-customer-name@value':'name',
            'input.additional-customer-name@id'   : function(arg){ return 'additional-customer-name_'+(rows_number);},
            'input.additional-customer-name@name' : function(arg){ return 'additional-customer-name_'+(rows_number);}
        };
        var html = row_template.render(new_customer, directives);

        $(".add-additional-customer",table).before(html);

        var name = $('#additional-customer-name_'+(rows_number));
        name.rules("add",{
            required: {
                depends:function(){
                    $(this).val($.trim($(this).val()));
                    return true;
                }
            }});

        name.rules("add", { required:true,
                            ValidPlainText:true,
                            maxlength: 125,
                            validate_duplicate_field:[table,'.additional-customer-name']
        });
    }
// End of closure.
}( jQuery ));
