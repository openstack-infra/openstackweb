jQuery(document).ready(function($){

    $('.addDeploymentBtn').click(function(){
        $('.addDeploymentForm').fadeIn();
        $('.addDeploymentForm').find('input[name=label]:first').focus();
        return false;
    });

    var form = $('#seach_deployments');

    $('.addDeploymentBtn').click(function(event){
        $('.addDeploymentForm').fadeIn();
        $('.addDeploymentForm').find('input[name=label]:first').focus();
        event.preventDefault();
        event.stopPropagation();
        return false;
    });

    $("#date-from").datepicker({ dateFormat: "yy-mm-dd",autoSize: true  });
    $( "#date-to").datepicker({ dateFormat: "yy-mm-dd",autoSize: true  });

    $("#date-from").change(  function() {
        form_validator.resetForm();
        $( "#date-to").val($(this).val());
    });

    $("#date-to").change(  function() {
        form_validator.resetForm();
    });

    var date_to   = $.QueryString["date-to"];
    var date_from = $.QueryString["date-from"];

    if(date_to!="undefined"){
        $("#date-to").val(date_to);
    }

    if(date_from!="undefined"){
        $("#date-from").val(date_from);
    }

    $.urlParam = function(name){
        var results = new RegExp("[\\?&]" + name + "=([^&#]*)").exec(window.location.href);
        if (results==null){
            return null;
        }
        else{
            return results[1] || 0;
        }
    }

    if($.urlParam("dep")){
        var anchor = $("#dep" + $.urlParam("dep"));
        $("html, body").animate({
            scrollTop: anchor.offset().top - 30
        }, 2000);
        anchor.parents("tr").css("background-color","lightyellow");
    }

    //main form validation
    form_validator = form.validate({
        onfocusout: false,
        focusCleanup: true,
        rules: {
            'date-from'  : {dpDate: true},
            'date-to'    : {dpDate: true, dpCompareDate:'ge #date-from'}
        },
        focusInvalid: false,
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            var element = $(validator.errorList[0].element);
            if(!element.is(":visible")){
                element = element.parent();
            }

            $('html, body').animate({
                scrollTop: element.offset().top
            }, 2000);
        },
        errorPlacement: function(error, element) {
            if(!element.is(":visible")){
                element = element.parent();
            }
            error.insertAfter(element);
        }
    });

    form.submit(function(event){
        var is_valid = form.valid();
        if(!is_valid){
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    })
});
