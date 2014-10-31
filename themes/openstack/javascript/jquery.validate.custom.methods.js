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


$.validator.addMethod("ValidPlainText", function (value, element, arg) {
    value = value.trim();
    if(value.length == 0)
        return true;
    value = value.replace(/(<([^>]+)>)/ig,"");
    value = value.replace(/[<>\=;\(\)\/\\\"\']*/ig,"");
    jQuery(element).val(value);
    return value.length>0;
}, "Field is not valid Text!");


$.validator.addMethod("color", function (value, element, arg) {
    return value.match(/^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/g);
}, "Field is not a valid color value (RGB)!");

$.validator.addMethod("validate_duplicate_field", function (value, element, arg) {
    var table     = arg[0];
    var css_class = arg[1];
    var rows  = jQuery("tbody > tr",table);
    element   = jQuery(element);
    var length = rows.length-1;
    if(length===0) return true;
    var res = true;
    for(var i=0;i < length;i++){
        var aux_element = jQuery(css_class,rows[i]);
        if(aux_element.is("input")){
            res = res && !( element.attr('id') != aux_element.attr('id')
                      && aux_element.val().trim()!=''
                      && value.trim()!='' &&  aux_element.val().trim() == value.trim()
                      );
        }
        else{
            res = res && !( element.attr('id') != aux_element.attr('id')
                && value.trim()!='' &&  aux_element.text().trim() == value.trim());
        }
        if(!res) break;
    }
    return res;

}, "Field already defined.");


$.validator.addMethod("rows_max_count", function (value, element, arg) {
    var max_count      = arg[0];
    var table          = arg[1];
    var rows           = jQuery("tbody > tr",table);
    return rows.length <= max_count;
}, "You reached the maximum allowed number Items.");


$.validator.addMethod("complete_url", function(value, element, arg) {
    // if no url, don't do anything
    value = value.trim();
    if (value.length == 0) { return true; }

    // if user has not entered http:// https:// or ftp:// assume they mean http://
    if(!/^(https?|ftp):\/\//i.test(value)) {
        value = 'http://'+value; // set both the value
        $(element).val(value); // also update the form element
    }
    // now check if valid url
    // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
    return this.optional(element) || /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
}, 'You must enter a valid URL');

}( jQuery ));