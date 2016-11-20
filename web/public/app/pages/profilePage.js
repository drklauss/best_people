define(function (require) {
    var $ = require('jquery');
    require('bootstrap');


    var formIsValid = true;
    var $errorsBlock = $('#jsRegisterFormErrors');


    var profile = {
        init: function () {

            $('[data-toggle="tooltip"]').tooltip(
                {"trigger": "hover"}
            );
            $('.disclose-password').on('click touchstart', function () {
                if ($(this).prop('checked')) {
                    $(this).parent().siblings('[type="password"]').attr('type', 'text');
                } else {
                    $(this).parent().siblings('[type="text"]').attr('type', 'password');
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
        },
        /**
         * Send Form
         */
        sendForm: function () {

            var registerBtn = $('#jsRegisterBtn');
            registerBtn.click(function (event) {
                event.preventDefault();
                profile.removeFormErrors();
                var $form = $('#jsRegisterForm');
                profile.validateForm($form);
                if (formIsValid) {
                    registerBtn.attr('disabled', true);
                    var formData = new FormData($form[0]);
                    $.ajax({
                        url: '/edit/user',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (result) {

                            if (result['isError'] == false) {
                                swal({
                                    title: 'Saved',
                                    text: 'Profile data were successfully updated!',
                                    showConfirmButton: false,
                                    type: 'success',
                                    timer: swalWaitTime

                                });
                                setTimeout(function () {
                                    location.reload();
                                }, swalWaitTime);
                            } else {
                                profile.addFormErrors(result['errors']);
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
            if($('#collapsePasswords').hasClass('collapse in')){
                $form.find('input:required').each(function () {
                    if (!$(this).val().length) {
                        inputsAreValid = false;
                    }
                });
            } else {
                $form.find('input:required').each(function () {
                    if (!$(this).val().length && !$(this).hasClass('change-password')) {
                        inputsAreValid = false;
                    }
                });
            }

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
        },

        /**
         * CLear Form errors
         */
        removeFormErrors: function () {
            $errorsBlock.addClass('hidden');
            $errorsBlock.find('ul li').remove();
        }

    };
    return profile;
});