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
jQuery(document).ready(function($) {

    var form_id ="#HoneyPotForm_RegistrationForm";

    $("#AffiliationEditForm_AffiliationEditForm").affiliations({
        storage:'local'
    });

    var country = $(form_id+'_Country');

    if(country.length>0)
        country.chosen();

    var registration_form = $(form_id);

    var default_country = country.val();
    var state_input = $(form_id + ' input[name="State"]');

    if(registration_form.length>0){

        $('.autocompleteoff').attr('autocomplete', 'off');


        var country = $(form_id+'_Country');

        country.chosen().change(function(){
            var country_selected = $(this).val();
            
            if(country_selected == 'US'){
                state_input.rules( "add", {required: true} );
            }else{
                state_input.rules( "remove", "required" );
            }
        });

        //custom validation
        jQuery.validator.addMethod('checkAffiliations', function(value, element,params) {
            var count = $("#AffiliationEditForm_AffiliationEditForm").affiliations('count');
            return count >0;
        },'You must add at least one Affiliation.');

        jQuery.validator.addMethod(
            "regex",
            function(value, element, regexp) {
                var re = new RegExp(regexp,'g');
                var res =  re.test(value);
                return !res;
            },
            "Please check your input."
        );

        registration_form.validate({
            onfocusout: false,
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    var element = validator.errorList[0].element;
                    var offset = (element.name == 'Affiliations') ? $(element).prev().offset().top : $(element).offset().top;
                    $('html, body').animate({
                        scrollTop: offset-100
                    }, 2000);
                }
            },
            ignore: [],
            rules: {
                FirstName:{required: true,regex:'[\"()=<>]+' },
                Surname:{required: true,regex:'[\"()=<>]+'},
                Email: {required: true,email: true, remote: '/DeploymentSurveyPage_Controller/CheckEmail'},
                StatementOfInterest:{required: true},
                Address:{required: true},
                City:{required: true},
                State:{required: true},
                Postcode:{required: true},
                'Password[_Password]': {required: true,minlength: 5},
                'Password[_ConfirmPassword]': {required: true,minlength: 5,equalTo: '#Password-_Password'},
                'Affiliations':{checkAffiliations:true},
                'Gender':{required:true}
            },
            messages: {
                FirstName:{
                    required:'First Name is required.',
                    regex:'First Name is not valid.'
                },
                Surname:{
                    required:'Last Name is required.',
                    regex:'Last Name is not valid.'
                },
                StatementOfInterest:'Statement of Interest is required.',
                Address:'Street Address (Line1) is required.',
                City:'City is required.',
                State:'State is required.',
                Postcode:'Postcode is required.',
                Email:{
                    required:'Primary Email Address is required.',
                    email:'Primary Email Address is not valid.',
                    remote:'That address is already in use by another user'
                },
                Gender:'Please specify your gender'
            }
        });

        var GenderSpecify = $('input[name=GenderSpecify]');
        var GenderField = $('input[name=Gender]');
        
        GenderField.on('change',function(){
            var value = $(this).val();
            if(value == 'Specify'){
                $('#GenderSpecify').removeClass('hide').fadeIn();
                GenderSpecify.removeClass('hide').fadeIn();
            } else {
                $('#GenderSpecify').fadeOut();
                GenderSpecify.fadeOut();
            }
        });

        registration_form.submit(function(event){
            var data_source = $("#AffiliationEditForm_AffiliationEditForm").affiliations('local_datasource');
            $(form_id+"_Affiliations").val(JSON.stringify(data_source));
        })
    }


    if(default_country != 'US'){
        state_input.rules( "remove", "required" );
    }

});
