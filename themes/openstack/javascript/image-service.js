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

	//Default Action
	$(".tabContent").hide(); //Hide all content
	$("ul.tabs li.active").show(); //Activate first tab
	var activeTab = $("ul.tabs li.active:first").find("a").attr("href");
	$(activeTab).show(); //Show first tab content
	
	//On Click Event
	
	$("ul.tabs li").click(function() {
		
		//Keep the height of the tab from adjusting and causing page scroll
		$("div.tabSet").css('height', $(this).closest("div.tabSet").height());
		$("div.tabSet").css('overflow', 'hidden');
	
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tabContent").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		
		//Reset the tabs to a natural height
		$("div.tabSet").css('height', 'auto'); 
		$("div.tabSet").css('overflow', 'visible');
		
		//Keep the click from bubbling up
		return false;
	});
	
	
});
