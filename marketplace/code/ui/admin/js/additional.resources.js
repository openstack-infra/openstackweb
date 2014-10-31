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

               $.validator.addMethod("resource_max_count", function (value, element, arg) {
                   var max_count      = arg[0];
                   var table          = arg[1];
                   var rows           = $("tbody > tr",table);
                   return rows.length <= max_count;
               }, "You reached the maximum allowed number of additional resources.");

               $.validator.addMethod("validate_resource_name", function (value, element, arg) {
                   var table = arg[0];
                   var rows  = $("tbody > tr",table);
                   element   = $(element);
                   var length = rows.length-1;
                   if(length===0) return true;
                   var res = true;
                   for(var i=0;i < length;i++){
                       var aux_element = $('.additional-resource-name',rows[i]);
                       res = res && !(element.attr('id') != aux_element.attr('id') && aux_element.val().trim() == value.trim());
                       if(!res) break;
                   }

                   return true;

               }, "Resource name already defined.");


               form_validator = form.validate({
                   rules: {
                       add_document_name  : {
                           required:true,
                           ValidPlainText:true,
                           maxlength: 125,
                           resource_max_count:[10,table],
                           validate_resource_name:[table] },
                       add_document_link  : {  required: {
                           depends:function(){
                               $(this).val($.trim($(this).val()));
                               return true;
                           }
                       }, complete_url:true }
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
                       items: '> tr:not(.add-additional-resource)',
                       update: function( event, ui ) {
                           renderOrders();
                       }
                   }
               );

               $('#add-new-additional-resource').click(function(event){
                   var is_valid = form.valid();
                   event.preventDefault();
                   event.stopPropagation();
                   if (is_valid){
                       var new_resource = {};
                       new_resource.name = $('#add_document_name').val();
                       new_resource.link = $('#add_document_link').val();
                       new_resource.id   = 0;
                       addAdditionalResource(new_resource);
                       $('#add_document_name').val('');
                       $('#add_document_link').val('');
                       form_validator.resetForm();
                   }
                   return false;
               });

               $(".remove-additional-resource").live('click',function(event){
                   var remove_btn = $(this);
                   var tr = remove_btn.parent().parent();
                   var name = $('input.additional-resource-name',tr);
                   name.rules("remove", "required");
                   name.rules("remove", "ValidPlainText");
                   name.rules("remove", "maxlength");
                   var link =  $('input.additional-resource-link',tr);
                   link.rules("remove", "required");
                   link.rules("remove", "url");
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
               var resource = {};
               resource.name = $('input.additional-resource-name',rows[i]).val();
               resource.link = $('input.additional-resource-link',rows[i]).val();
               res.push(resource);
           }
           return res;
       },
       load:function(additional_resources){
            for(i in additional_resources){
                addAdditionalResource(additional_resources[i]);
            }
       }
   };

   $.fn.additional_resources = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.additional_resources' );
        }
   };

   //helper functions
   function renderOrders(){
        var rows = $("tbody > tr",table);
        for(var i=0;i<rows.length-1;i++){
            var th_order = $('.additional-resource-order',rows[i]);
            th_order.text(i+1);
        }
   }

   function addAdditionalResource(new_resource){
        var rows_number = $("tbody > tr",table).length;

        var row_template = $('<tr><td class="additional-resource-order" style="border: 1px solid #ccc;background:#eaeaea;width:5%;font-weight:bold;"></td>' +
            '<td style="border: 1px solid #ccc;width:30%;background:#fff;">' +
            '<input type="text" style="width:300px;" class="additional-resource-name text autocompleteoff"></td>' +
            '<th style="border: 1px solid #ccc;width:30%;background:#fff;">' +
            '<input type="text" style="width:300px;" class="additional-resource-link text autocompleteoff"></td>' +
            '<td style="border: 1px solid #ccc;background:#eaeaea;width:10%;color:#cc0000;">' +
            '<a href="#" class="remove-additional-resource">x&nbsp;Remove</a></td></tr>>');

        var directives = {
            'td.additional-resource-order':function(arg){ return rows_number;},
            'input.additional-resource-name@value':'name',
            'input.additional-resource-link@value':'link',
            'input.additional-resource-name@id'   : function(arg){ return 'additional-resource-name_'+(rows_number);},
            'input.additional-resource-link@id'   : function(arg){ return 'additional-resource-link_'+(rows_number);},
            'input.additional-resource-name@name' : function(arg){ return 'additional-resource-name_'+(rows_number);},
            'input.additional-resource-link@name' : function(arg){ return 'additional-resource-link_'+(rows_number);}
        };
        var html = row_template.render(new_resource, directives);
        $(".add-additional-resource",table).before(html);

        var name = $('#additional-resource-name_'+(rows_number));
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
        name.rules("add", { validate_resource_name:[table]});
        var link = $('#additional-resource-link_'+(rows_number));
        link.rules("add", "required");
        link.rules("add", "complete_url");
    }
// End of closure.
}( jQuery ));