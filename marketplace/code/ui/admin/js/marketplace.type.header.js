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
    var form_validator = null;
    var methods = {
        init : function(options) {
            form = $(this);
            if(form.length>0){
                //main form validation
                form_validator = form.validate({
                    onfocusout: false,
                    focusCleanup: true,
                    rules: {
                        name  : { required: true , ValidPlainText:true, maxlength:45 },
                        overview: {required: true, ValidPlainText: true, maxlength: 250},
                        call_2_action_uri: { required: {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }
                        }, complete_url: true},
                        company_id: {required: true}
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
                $('#company_id').chosen({disable_search_threshold: 10});
                $('#company_id').change(function () {
                    form_validator.resetForm();
                });
                //if we have only one company available , then set it by default
                var length = $('#company_id > option').length;
                if(length == 2){
                    $($('#company_id > option')[1]).attr('selected', 'selected');
                    $("#company_id").trigger("chosen:updated");
                }

                //product overview

                var options2 = {
                    'maxCharacterSize': 250,
                    'originalStyle': 'originalDisplayInfo',
                    'warningStyle': 'warningDisplayInfo',
                    'warningNumber': 40,
                    'displayFormat': '#input Characters | #left Characters Left'
                };

                $('#overview',form).textareaCount(options2);
            }
        },
        getFormValidator:function(){
            return form_validator;
        }
    };

    $.fn.marketplace_type_header = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.marketplace_type_header' );
        }
    };

// End of closure.
}( jQuery ));