define(['jquery', 'bootstrap'], function () {
    var formIsValid = true;
    var $errorsBlock = $('#jsLoginFormErrors');
    var login = {
        init: function () {

            $('[data-toggle="tooltip"]').tooltip(
                {"trigger": "hover"}
            );
            $('#jsDisclosePassword').on('click touchstart',function () {
                if ($(this).prop('checked')) {
                    $('#jsPasswordInput').attr('type', 'text')
                } else {
                    $('#jsPasswordInput').attr('type', 'password')
                }
            });
        },
        /**
         * Send Form
         */
        sendForm: function () {
            var loginBtn = $('#jsLoginBtn');
            loginBtn.on('click touchstart', function (event) {
                event.preventDefault();
                login.removeFormErrors();
                var $form = $('#jsLoginForm');
                login.validateForm($form);
                if (formIsValid) {
                    loginBtn.attr('disabled', true);
                    $.ajax({
                        url: '/login/user',
                        method: 'POST',
                        data: $form.serialize(),
                        success: function (result) {

                            if (result['isError'] == false) {
                                swal({
                                    title: 'Successfully login!',
                                    text: 'Now you will be redirected to main page',
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: swalWaitTime
                                });
                                setTimeout(function(){
                                    location.href = '/';
                                }, swalWaitTime);
                            } else {
                                login.addFormErrors(result['errors']);
                                swal({
                                    title: 'You\'ve got some errors!',
                                    text: 'Please be more patience and follow instructions',
                                    type: 'warning',
                                    showConfirmButton: false,
                                    timer: swalWaitTime
                                })
                            }
                        },
                        error: function (result) {
                            // console.log(result);
                        }

                    })
                        .always(function () {
                            loginBtn.attr('disabled', false);
                        });
                } else {
                    swal({
                        title: 'Oohhh...',
                        text: 'Check and fill all inputs please!',
                        type: 'error',
                        showConfirmButton: false,
                        timer: swalWaitTime
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
            $form.find('input:required').each(function () {
                if (!$(this).val().length) {
                    inputsAreValid = false;
                }
            });
            formIsValid = inputsAreValid;
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
    return login;
});