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
	jQuery(function($) {
		$('.folder, .folder>a').click(function(e) {
			var div, folder_cont, offset_arrow;
			if ($(e.target).parents('.folder-contents').length) {
				e.stopPropagation();
				return true;
			}
			$('.folder-contents').fadeOut();
			if ($(this).children('.folder-contents').length>0) {
				div = $(this);
			} else {
				div = $(this).parent();
			}
			folder_cont = div.children('.folder-contents');
            if(folder_cont.length>0){
                if (folder_cont.is(':visible')) {
                    // folder_cont.fadeOut();
                } else {
                    folder_cont.fadeIn();
                }
                offset_arrow = div.position().left + 55;
                div.children('.folder-contents').children('.arrow').css({
                    'margin-left' : offset_arrow + 'px'
                });
            }
			return false;
		});
		$('body').click(function(e) {
			$('.folder-contents').fadeOut();
		});
		$('.close').click(function(e) {
			$(this).parent().parent().fadeOut();
			return false;
		})
	});
