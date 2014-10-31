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
jQuery(document).ready(function($) {
    var form = $('#DeploymentSurveyOrgInfoForm_Form');
    var form_validator = null;
    if(form.length > 0) {

        $.validator.addMethod("ValidNewOrgName", function (value, element, arg) {
            value = value.trim();
            var field = arg[0];
            if(value=='0' && field.val().trim()==='')
                return false;
            return true;
        }, "You Must Specify a New Organization Name!.");

        form_validator = form.validate({
            rules: {
                'Title'  : {required: true },
                'PrimaryCity'  : {required: true },
                'PrimaryCountry'  : {required: true },
                'FirstName'  : {required: true },
                'Surname'  : {required: true }
            },
            onfocusout: false,
            focusCleanup: true,
            ignore: [],
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    validator.errorList[0].element.focus();
                }
            }
        });

        var ddl   = $('#DeploymentSurveyOrgInfoForm_Form_OrgID',form);
        var input = $('#DeploymentSurveyOrgInfoForm_Form_Organization',form);

        if(ddl.length > 0) {
             var new_org_name = $('.new-org-name',form);
             new_org_name.hide();

            ddl.rules('add',{
                required:true,
                 ValidNewOrgName:[new_org_name]
             });

             ddl.change(function(event){
                var ddl = $(this);
                if(ddl.val()=='0'){
                    new_org_name.show();
                    form_validator.resetForm();
                }
                else{
                    new_org_name.hide();
                }
            });
        }
        else if(input.length > 0) {
            input.rules('add',{required:true});
        }

        form.submit(function( event ) {
            var valid = form.valid();
            if(!valid){
                event.preventDefault();
                return false;
            }
        });
    }
});
