
jQuery(function ($) {

	if(showIntro == 'yes') {
		$('#instructions-modal').modal('show');
	}

	$('#voter-tools a').click(function (e) {
    	e.preventDefault();               // prevent default anchor behavior
   		var goTo = this.href;             // store anchor href

    	$('.current-vote').removeClass('current-vote');
    	$(this).addClass('current-vote');

	    setTimeout(function(){
	         window.location = goTo;
	    },1000);

	});

	$("p:empty").hide(); 


	// Bind the voting keys
	Mousetrap.bind('3', function() { window.location = $("#vote-3").attr("href"); });
	Mousetrap.bind('2', function() { window.location = $("#vote-2").attr("href"); });
	Mousetrap.bind('1', function() { window.location = $("#vote-1").attr("href"); });
	Mousetrap.bind('0', function() { window.location = $("#vote-0").attr("href"); });
	Mousetrap.bind('s', function() { window.location = $("#skip").attr("href"); });

});