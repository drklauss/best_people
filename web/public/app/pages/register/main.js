define(['jquery', 'swal'], function () {
    var formIsValid = true;
    var main = {
        init: function () {

            $('[data-toggle="tooltip"]').tooltip(
                {"trigger": "hover"}
            );
            $('#jsDisclosePassword').click(function () {
                if ($(this).prop('checked')) {
                    $('#jsPasswordInput').attr('type', 'text')
                } else {
                    $('#jsPasswordInput').attr('type', 'password')
                }
            });
            // imitate clicking on file button
            $('#jsFileInputTrigger').on('click touchstart', function (event) {
                event.preventDefault();
                $('#jsFileInput').click();
            });
            // write filename after file select
            $('#jsFileInput').on('change', function(){
                var filename = $(this).val().split('\\').pop();
                $('#jsFileNameArea').val(filename);
            });
            // need to imitate selector
            $('#jsGenderSelect').on('click touchstart', 'button',function(event){
                event.preventDefault();
                $('#jsGenderSelect').find('button').removeClass('active');
                $(this).addClass('active');
                $('#jsGenderInput').val($(this).text());
            })
        },
        /**
         * Send Form
         */
        sendForm : function() {
            $('#jsRegisterBtn').click(function (event) {
                event.preventDefault();
                var $form = $('#jsRegisterForm');
                main.validateForm($form);
                console.log('form:', formIsValid);
                if (formIsValid) {
                    $('#jsRegisterBtn').attr('disabled', true);
                    var formData = new FormData($form[0]);
                    $.ajax({
                        url: '/register/user',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            $('#jsRegisterBtn').attr('disabled', false);
                            //console.log(result);
                        },
                        error: function (result) {
                            // console.log(result);
                        }

                    });
                } else {
                    swal({
                        title: 'Oohhh...',
                        text: 'Check and fill all inputs please!',
                        type: 'error'
                    })
                }
            });
        },
        validateForm : function($form) {
            var passwordIsValid = true;
            var genderIsValid = false;
            $form.find('input:required').each(function() {
                if(!$(this).val().length){
                    passwordIsValid = false;
                }
            });
            $('#jsGenderSelect').find('button').each(function() {
                if($(this).hasClass('active')) {
                    genderIsValid = true;
                }
            });
            formIsValid = genderIsValid && passwordIsValid;
        }

    };
    return main;
});