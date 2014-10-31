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
$(window).load(function(){
	$("#preloader").delay(350).fadeOut("slow");

	// Expand Map
	$('.map a').click(function(e){
		e.preventDefault();
		
		if($(this).hasClass('open-map')){
			var open = true;
		}
		else if($(this).hasClass('close-map')){
			var open = false;
		}

		if(open){
			openMap();
		}
		else{
			closeMap();
		}
	});

	$('.map .cover').click(function(e){
		e.preventDefault();
		openMap();
	});

	// CreateShadows
	i = 1; // Counter
	var shadowbg_pos = new Array();
		shadowbg_pos[1] = '85px center';
		shadowbg_pos[2] = 'center center';
		shadowbg_pos[3] = '60px center';

	$('.robots img').each(function(){

		var top_b = $(this).position().top + $(this).height();
		var left_b = $(this).position().left;
		var width_b = $(this).width() + 'px';

		$('.shadow.shadow-'+i).css({
			width: width_b,
			top: top_b,
			left: left_b,
			'background-position': shadowbg_pos[i]});

		i++;

	});

});

// on Scroll
$(window).scroll(function() {
	// Celebrate
	if( $(this).scrollTop() > scrollStartPix('.celebrate') ){
		$('.celebrate img').animate({bottom: '-50px'}, 1000, "easeOutBack");
	}

	// Last Years
	if( $(this).scrollTop() > scrollStartPix('.last-years') ){
		$('.infographics').fadeIn(800);
	}

	// Global Events
	if( $(this).scrollTop() > scrollStartPix('.global-events')){
		$('.global-events h2:first').fadeIn(500, function(){
			$('.global-events h2:nth-of-type(2)').fadeIn(500, function(){
				$('.global-events .btn').fadeIn(500);
			});	
		});
		
	}
	

	// Join twitter
	if( $(this).scrollTop() > scrollStartPix('.join-twitter') ){
		$('.join-twitter img').animate({top: '45px'}, 1000, "easeOutBack");
	}
});

// Functions

// Map Functions

function openMap(){
	$('.map iframe').animate({height: '400px'});
	$('.map .cover').css({height: '0px'});
	$('.map a').removeClass('open-map').addClass('close-map');
	$('.map a img').attr('src','/themes/openstack/images/anniversary/3/section_map-arrow-close.png');
}
function closeMap(){
	$('.map iframe').animate({height: '150px'});
	$('.map .cover').css({height: '150px'});
	$('.map a').removeClass('close-map').addClass('open-map');       
	$('.map a img').attr('src','/themes/openstack/images/anniversary/3/section_map-arrow.png');
}

function scrollStartPix(div){
	return parseInt($(div).offset().top - ($(window).height()/2));

}
