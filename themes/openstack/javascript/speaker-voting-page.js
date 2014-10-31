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

	//hide comment boxes
	$('.commentArea').hide();
	
	// toggles comment area  
	$('a.toggleCommentArea').click(function() {
	   $(this).closest('div.submission').find('div.commentArea').slideToggle(400);
	   return false;
	});

	$('.commentSubmitButton').each(function()	{
		
		$(this).click(function() {
			var submission = $(this).closest('div .submission').attr('id');
			var comment = document.getElementById('comment' + submission).value;
		    $.ajax({
		           type: 'POST',
		           url: '/summit/portland-2013/vote-for-speakers/savecomment/',
		           data: {'comment':comment, 'submission':submission},
		           success: function(data){ $("div.saved-rating#saved" + submission).show().animate({opacity: 1.0}, 1000).fadeOut(500); }         
		         }) 
		    return false; // prevent normal submit
		});
	});

	$('.ratingsButton').each(function()	{
		
		$(this).click(function() {

			$(this).closest('ul').find('.ratingsButton').removeClass('selected');
			$(this).addClass('selected');

			url = $(this).attr("href");
		    $.get(url, function (resp) {
   				 $("div.saved-rating#saved" + resp).show().animate({opacity: 1.0}, 1000).fadeOut(500);
			});
			return false; // prevent normal click
		});
		
	});

	$("#topic-selector").change(function() {
        location.hash = encodeURI($(this).val());
    });

});