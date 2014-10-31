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

    var form = $("#NewsRequestForm_NewsRequestForm");
    var form_validator = null;

    if(form.length > 0){

        $('#NewsRequestForm_NewsRequestForm_date').datetimepicker({
            minDate: 0
        });

        $('#NewsRequestForm_NewsRequestForm_date_embargo').datetimepicker({
            minDate: 0
        });

        $('#NewsRequestForm_NewsRequestForm_date_expire').datetimepicker({
            minDate: 0
        });




        //main form validation

        jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
        }, "Please specify a valid phone number (ie 333-333-4444)");

        form_validator = form.validate({
            onfocusout: false,
            focusCleanup: true,
            rules: {
                submitter_phone:{required: true, phoneUS:true}
                //date  : {required: true, dpDate: true},
                //date_embargo    : {required: true, dpDate: true, dpCompareDate:'ge #NewsRequestForm_NewsRequestForm_date'}
            },
            messages: {
                submitter_phone:{
                    required:'Phone is required.'
                }
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

        /*var date_picker_date = $('#NewsRequestForm_NewsRequestForm_date',form);
        date_picker_date.datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0
        });

        var date_picker_emabargo = $('#NewsRequestForm_NewsRequestForm_date_embargo',form);
        date_picker_emabargo.datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0
        });

        var date_picker_expire = $('#NewsRequestForm_NewsRequestForm_date_expire',form);
        date_picker_expire.datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0
        });*/
    }

});
