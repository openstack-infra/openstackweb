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

    var $training_form = $('#Form_AddTrainingCourseForm');

    if($training_form.length > 0 ){

        var $online_checkbox = $('.course-online-checkbox:checkbox',$training_form);
        var $online_link     = $('.course-online-link:input',$training_form);

        if ($online_checkbox.is(":checked")){
            $('.note_online').show();
            //hide schedules bc does not apply
            $('#schedules',$training_form).hide();
            $('#addSchedule',$training_form).hide();
            $('#Link').show();
            $online_link.prop('required', true);
        }
        else{
            // Set Required
            var $city_input = $('.city_name').find('input');
            $city_input.prop('required', true);

            var $state_input = $('.state').find('input');

            var $country_input = $('.country').find('select');
            $country_input.prop('required', true);

            var url_input = $('.url').find('input');
            url_input.prop('required', true);

            $('#Link').hide();
            $online_link.prop('required', false);
        }

        $online_checkbox.click(function(){

            var city_input    = $('.city_name').find('input');
            var state_input   = $('.state').find('input');
            var country_input = $('.country').find('select');
            var url_input     = $('.url').find('input');
            var is_online     = $(this).is(":checked");

            if(is_online){
                $('.note_online').show();
                //hide schedules bc does not apply
                $('#schedules',$training_form).hide();
                $('#addSchedule',$training_form).hide();
                city_input.prop('disabled', true);
                city_input.prop('required', false);
                state_input.prop('disabled', true);
                country_input.prop('disabled', true);
                country_input.prop('required', false);
                url_input.prop('disabled', true);
                url_input.prop('required', false);
                $('#Link').show();
                $online_link.prop('required', true);
                $online_link.prop('disabled', false);
            }else{
                $('.note_online').hide();
                $('#schedules',$training_form).show();
                $('#addSchedule',$training_form).show();
                city_input.prop('disabled', false);
                city_input.prop('required', true);
                state_input.prop('disabled', false);
                country_input.prop('disabled', false);
                country_input.prop('required', true)
                url_input.prop('disabled', false);
                url_input.prop('required', true);
                $('#Link').hide();
                $online_link.prop('disabled', true);
                $online_link.prop('required', false);
            }
        });

        //auto complete links
        $training_form.submit(function() {
            $('.url').find('input').each( function(){
                var val = $(this).val();
                if(val.indexOf('http://') == -1 && val.indexOf('https://') == -1){
                    $(this).val('http://'+val);
                }
            });
        });

        //calendars
        $training_form.find('.dateSelector input').each(function(index) {

            if($(this).hasClass('start')){
                next_input = $(this).closest('div').parent().next('div').find('input');
                limit = next_input.val();
            }else if($(this).hasClass('end')){
                prev_input = $(this).closest('div').parent().prev('div').find('input');
                limit = prev_input.val();
            }

            $(this).datepicker({dateFormat : 'yy-mm-dd', buttonImage : '/sapphire/images/calendar-icon.gif', buttonImageOnly : true,
                onClose: function( selectedDate ) {
                    if($(this).hasClass('start')){
                        next_input.datepicker( "option", "minDate", selectedDate );
                    }else if($(this).hasClass('end')){
                        prev_input.datepicker( "option", "maxDate", selectedDate );
                    }
                }
            });

            if($(this).hasClass('start')){
                $(this).datepicker('option', 'maxDate',limit);
            }
            else if($(this).hasClass('end')){
                $(this).datepicker('option', 'minDate',limit);
            }
        });

        $('.scheduleRow',$training_form).each(function(){
            $(this).append('<div class="remove">X</div>');
        });

        $('.scheduleRow .remove',$training_form).live('click',function(){
            if($('.scheduleRow',$training_form).length>1){
                $(this).parent().remove();
            }
        });

        $('#addSchedule',$training_form).click( function(){

            var new_row = $('.scheduleRow:last',$training_form).clone();

            new_row.find('input,select').each( function(){

                $(this).val('');

                // Refresh Name
                var input_name = $(this).attr('name');
                var index_num = input_name.replace( /[^\d.]/g,'');
                $(this).attr('name', input_name.replace(/[0-9]/g, parseInt(index_num)+1 ) );

                // Refresh id
                var input_id = $(this).attr('id');
                index_num = input_id.replace( /[^\d.]/g,'');
                $(this).attr('id', input_id.replace(/[0-9]/g, parseInt(index_num)+1 ) );

                if($(this).hasClass('hasDatepicker')){

                    $(this).removeClass('hasDatepicker').removeAttr('id');
                    $(this).datepicker({dateFormat : 'yy-mm-dd', buttonImage : '/sapphire/images/calendar-icon.gif', buttonImageOnly : true,
                        onClose: function( selectedDate ) {
                            if($(this).hasClass('start')){
                                next_input = $(this).closest('div').parent().next('div').find('input');
                                next_input.datepicker( "option", "minDate", selectedDate );
                            }else if($(this).hasClass('end')){
                                prev_input = $(this).closest('div').parent().prev('div').find('input');
                                prev_input.datepicker( "option", "maxDate", selectedDate );
                            }
                        }
                    });

                }

            });
            new_row.appendTo('#schedules');

            return false;

        });

        //required fields
        $('.course-name',$training_form).prop('required',true);
        $('.course-description',$training_form).prop('required',true);

    }
});