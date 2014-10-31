jQuery(document).ready(function($){
    var form_foundation_members = $('#form-export-foundation-members');
    var form_companies          = $('#form-export-company-data');
    var form_cla_users          = $('#form-export-cla-users-data');
    var form_gerrit_users       = $('#form-export-gerrit-users-data');

    //main form validation
    form_validator1 = form_foundation_members.validate({
        onfocusout: false,
        focusCleanup: true,
        rules: {
            'fields[]'  : {
                required: true,
                minlength: 1 }
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

    //main form validation
    form_validator2 = form_companies.validate({
        onfocusout: false,
        focusCleanup: true,
        rules: {
            'fields[]'  : { required: true,
                minlength: 1},
            'levels[]'  : { required: true,
                minlength: 1}
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

    var form_validator_3 = form_cla_users.validate({
        onfocusout: false,
        focusCleanup: true,
        rules: {
            'fields[]'  : {
                required: true,
                minlength: 1 },
            'status[]'  : {
                required: true,
                minlength: 1 }
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

    var form_validator_4 = form_gerrit_users.validate({
        onfocusout: false,
        focusCleanup: true,
        rules: {
            'status[]'  : {
                required: true,
                minlength: 1 }
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

    //foundation members
    $('#btn1_xls').click(function(event){
        $('#ext',form_foundation_members).val('xls');
    });

    $('#btn1_csv').click(function(event){
        $('#ext',form_foundation_members).val('csv');
    });

    form_foundation_members.submit(function(event){
        var is_valid = form_foundation_members.valid();
        if(!is_valid){
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    })

    //cla users

    $('#btn3_xls').click(function(event){
        $('#ext',form_cla_users).val('xls');
    });

    $('#btn3_csv').click(function(event){
        $('#ext',form_cla_users).val('csv');
    });

    form_cla_users.submit(function(event){
        var is_valid = form_cla_users.valid();
        if(!is_valid){
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    })

    //companies

    $('#btn2_xls').click(function(event){
        $('#ext',form_companies).val('xls');
    });

    $('#btn2_csv').click(function(event){
        $('#ext',form_companies).val('csv');
    });

    form_companies.submit(function(event){
        var is_valid = form_companies.valid();
        if(!is_valid){
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    })

    //gerrit users ...
    $('#btn4_xls').click(function(event){
        $('#ext',form_gerrit_users).val('xls');
    });

    $('#btn4_csv').click(function(event){
        $('#ext',form_gerrit_users).val('csv');
    });

    form_gerrit_users.submit(function(event){
        var is_valid = form_gerrit_users.valid();
        if(!is_valid){
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    });


    $('#status_all').click(function(evt){
        var is_checked = $(this).is(':checked');
        $('.group').prop('checked', is_checked);
    });

});
