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

var DatePickerFieldHandler = function(){
    var $ = jQuery;

    this.initAll = function(){
        var fnc = this.initById;
        $('.DatePickerField').each(function(){
            fnc('#' + $(this).attr('id'));
        });
    }

    this.initById = function(id){
        var date_picker = $(id);
        date_picker.datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect:function(text,inst){
                var dependant = $(this).attr('data-dependant-on');
                if(dependant){
                    var date_dependant = $('#'+dependant);
                    date_dependant.val($(this).val());
                }
            }
        });
    }
}

datePickerFieldHandler = new DatePickerFieldHandler();

//check if prototype wrapper is required.
if(typeof Behaviour == 'object'){
    Behaviour.register({
        '.DatePickerField' : {
            initialise : function(){
                datePickerFieldHandler.initById('#' + this.id);
            }
        }
    });
}

jQuery(document).ready(function(){
    datePickerFieldHandler.initAll();
});