
jQuery(function ($) {

	$("#sort-list").sortable({
	  items : "li:not(.unused-position)",
      update : function () {
		var order = $('#sort-list').sortable('serialize');
  		$("#info").load(processingLink+order);
      }
    }).disableSelection();

});