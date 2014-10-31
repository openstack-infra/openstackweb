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

    $('#add-new-product').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        var marketplace_type_id = $("#marketplace_type_id").val();
        if(marketplace_type_id!=''){
            window.location = add_link+"?type_id="+marketplace_type_id;
        }
        return false;
    });

});
