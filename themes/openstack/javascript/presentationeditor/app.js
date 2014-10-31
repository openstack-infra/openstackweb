
$(function () {

	$('#add-note').on('shown.bs.modal', function () {
	  $('#PresentationFlagForm_FlagForm_FlagComment').focus();
	});

	$('#presentation-tabs a:first').tab('show');

	if($('#' + CurrentPresentation).length != 0) {
		$('#' + CurrentPresentation).addClass('selected');		
		$("#presentation-table").scrollTop($('#' + CurrentPresentation).position().top - 200);
	}

	nextHref = $('tr#'+CurrentPresentation).next('tr').find('td a').attr('href');
	$("a#Next").attr("href", nextHref);

	prevHref = $('tr#'+CurrentPresentation).prev('tr').find('td a').attr('href');
	$("a#Prev").attr("href", prevHref);

	// Bind next and previous buttons
	Mousetrap.bind('p', function() { window.location = $("#Prev").attr("href"); });
	Mousetrap.bind('n', function() { window.location = $("#Next").attr("href"); });
	
	// Bind number keys to categories
	Mousetrap.bind('1', function() { window.location = $("#cat-1").attr("href"); });
	Mousetrap.bind('2', function() { window.location = $("#cat-2").attr("href"); });
	Mousetrap.bind('3', function() { window.location = $("#cat-3").attr("href"); });
	Mousetrap.bind('4', function() { window.location = $("#cat-4").attr("href"); });
	Mousetrap.bind('5', function() { window.location = $("#cat-5").attr("href"); });
	Mousetrap.bind('6', function() { window.location = $("#cat-6").attr("href"); });
	Mousetrap.bind('7', function() { window.location = $("#cat-7").attr("href"); });
	Mousetrap.bind('8', function() { window.location = $("#cat-8").attr("href"); });
	Mousetrap.bind('9', function() { window.location = $("#cat-9").attr("href"); });
	Mousetrap.bind('0', function() { window.location = $("#cat-10").attr("href"); });

});
