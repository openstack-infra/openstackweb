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

	// JCAROUSEL ///////

	  var carouselOptions = {
	    auto: true,
	    visible: 2,
	    speed: 300,
	    pause: true,
	    btnPrev: function() {
	      return $(this).find('.prev');
	    },
	    btnNext: function() {
	      return $(this).find('.next');
	    }
	  };



	$('.slideshow').jCarouselLite(carouselOptions);


  $('a.screenshots').colorbox();
  $("a.vimeo").colorbox({iframe:true, innerWidth:800, innerHeight:530});
	
	
});
