define(['jquery', 'bootstrap'], function () {
    var formIsValid = true;
    var $errorsBlock = $('#jsRegisterFormErrors');
    var register = {
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
            var registerBtn = $('#jsRegisterBtn');
            registerBtn.click(function (event) {
                event.preventDefault();
                register.removeFormErrors();
                var $form = $('#jsRegisterForm');
                register.validateForm($form);
                if (formIsValid) {
                    registerBtn.attr('disabled', true);
                    var formData = new FormData($form[0]);
                    $.ajax({
                        url: '/register/user',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (result) {

                            if (result['isError'] == false) {
                                swal({
                                    title: 'Account created!',
                                    text: 'Now you will be redirected to your profile',
                                    showConfirmButton: false,
                                    type: 'success',
                                    timer: swalWaitTime

                                });
                                setTimeout(function () {
                                    location.href = '/profile';
                                }, swalWaitTime);
                            } else {
                                register.addFormErrors(result['errors']);
                                swal({
                                    title: 'You\'ve got some errors!',
                                    text: 'Please be more patience and follow instructions',
                                    showConfirmButton: false,
                                    type: 'warning',
                                    timer: swalWaitTime
                                })
                            }
                        },
                        error: function (result) {
                            // console.log(result);
                        }

                    })
                        .always(function () {
                            registerBtn.attr('disabled', false);
                        });
                } else {
                    swal({
                        title: 'Oohhh...',
                        text: 'Check and fill all inputs please!',
                        type: 'error',
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
    return register;
});