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
function ajaxError(jqXHR, textStatus, errorThrown){
    var HTTP_status = jqXHR.status;
    if(HTTP_status==412){
        response = jQuery.parseJSON(jqXHR.responseText);
        if(response.error==='validation'){
            var msg = '';
            for(var i=0;i < response.messages.length ; i++) {
                var error = response.messages[i];
                msg +='* '+error.message+'\n';
            }
            displayErrorMessage('validation error',msg);
        }
        else
            displayErrorMessage('server error',response.error);
    }
    else if(HTTP_status==409){
        displayErrorMessage('validation error','Entity Already exists!');
    }
    else if(HTTP_status==401){
        displayErrorMessage(errorThrown, jqXHR.responseText);
    }
    else{
        displayErrorMessage('server error','You got an error!');
    }
}

function displayErrorMessage(title,message){
    var $alert = jQuery('<div id="alert" title="'+title+'"><p>'+message+'</p></div>');
    jQuery('body').append($alert);
    $alert.dialog();
}

function convertToSlug(txt){
    if(txt == null) return '';
    return txt
        .toLowerCase()
        .replace(/[^A-Za-z0-9-]+/g,'-');
}


function ajaxIndicatorStart(text)
{
    if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
        jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="marketplace/code/ui/admin/images/ajax-loader.gif"><div>'+text+'</div></div><div class="bg"></div></div>');
    }

    jQuery('#resultLoading').css({
        'width':'100%',
        'height':'100%',
        'position':'fixed',
        'z-index':'10000000',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto'
    });

    jQuery('#resultLoading .bg').css({
        'background':'#000000',
        'opacity':'0.7',
        'width':'100%',
        'height':'100%',
        'position':'absolute',
        'top':'0'
    });

    jQuery('#resultLoading>div:first').css({
        'width': '250px',
        'height':'75px',
        'text-align': 'center',
        'position': 'fixed',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto',
        'font-size':'16px',
        'z-index':'10',
        'color':'#ffffff'

    });

    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeIn(300);
    jQuery('body').css('cursor', 'wait');
}

function ajaxIndicatorStop()
{
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(300);
    jQuery('body').css('cursor', 'default');
}

