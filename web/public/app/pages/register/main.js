define(['jquery', 'swal'], function () {
    var formIsValid = true;
    var $errorsBlock = $('#js_register_form_errors');
    var waitTime = 2000;
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
            $('#jsFileInput').on('change', function () {
                var filename = $(this).val().split('\\').pop();
                $('#jsFileNameArea').val(filename);
            });
            // need to imitate selector
            $('#jsGenderSelect').on('click touchstart', 'button', function (event) {
                event.preventDefault();
                $('#jsGenderSelect').find('button').removeClass('active');
                $(this).addClass('active');
                var isFemale = ($(this).text() === 'Female');
                $('#jsGenderInput').val(Boolean(isFemale));
            })
        },
        /**
         * Send Form
         */
        sendForm: function () {
            $('#jsRegisterBtn').click(function (event) {
                event.preventDefault();
                main.removeFormErrors();
                var $form = $('#jsRegisterForm');
                main.validateForm($form);
                if (formIsValid) {
                    $('#jsRegisterBtn').attr('disabled', true);
                    var formData = new FormData($form[0]);
                    $.ajax({
                        url: '/register/user',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (result) {
                            $('#jsRegisterBtn').attr('disabled', false);
                            if (result['isError'] == false) {
                                swal({
                                    title: 'Account created!',
                                    text: 'Now you will be redirected to login form',
                                    type: 'success'

                                });
                                setTimeout(function(){
                                    location.href = '/login';
                                }, waitTime);
                            } else {
                                main.addFormErrors(result['errors']);
                                swal({
                                    title: 'You\'ve got some errors!',
                                    text: 'Please be more patience and follow instructions',
                                    type: 'warning',
                                    timer: waitTime
                                })
                            }
                        },
                        error: function (result) {
                            // console.log(result);
                        }

                    });
                } else {
                    swal({
                        title: 'Oohhh...',
                        text: 'Check and fill all inputs please!',
                        type: 'error',
                        timer: waitTime
                    })
                }
            });
        },
        /**
         * Validate Form
         * @param $form
         */
        validateForm: function ($form) {
            var inputsAreValid = true;
            var genderIsValid = false;
            $form.find('input:required').each(function () {
                if (!$(this).val().length) {
                    inputsAreValid = false;
                }
            });
            $('#jsGenderSelect').find('button').each(function () {
                if ($(this).hasClass('active')) {
                    genderIsValid = true;
                }
            });
            formIsValid = genderIsValid && inputsAreValid;
        },

        /**
         * Add errors above the form and shows it
         * @param errors
         */
        addFormErrors: function (errors) {
            errors.forEach(function (error) {
                console.log(error);
                $errorsBlock.find('ul').append('<li>' + error + '</li>');
            });
            $errorsBlock.removeClass('hidden');
            grecaptcha.reset();
        },

        /**
         * CLear Form errors
         */
        removeFormErrors: function () {
            $errorsBlock.addClass('hidden');
            $errorsBlock.find('ul li').remove();
        }

    };
    return main;
});