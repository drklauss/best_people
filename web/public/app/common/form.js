define(['jquery', 'swal'], function ($, swal) {

    var form = {
        id: '',
        warning_msg: 'Warning!',
        success_msg: 'Success!',
        success_form_sent: 'The form was sent',
        warning_check_inputs: 'Please, check all inputs',
        warning_long_text: 'Your Text is too long',
        warning_goes_wrong: 'Something goes wrong. Try it later',
        /**
         * Input point
         * @method init
         */
        init: function (id) {
            form.id = id;
            form.subscribe();
        },
        /**
         * Subscribe for page events
         * @method subscribe
         */
        subscribe: function () {
            $formSelector = $('#' + form.id);
            $formSelector.find('[type=text], textarea').each(function () {
                $(this).on('change', function (event) {
                    $(this).removeClass('error');
                });
            });
            // send forms via ajax
            $formSelector.find('[type=submit]').click(function (event) {
                event.preventDefault();
                form.send($(this));
                return false;
            });
        },
        /**
         * Send form via ajax
         * @method send
         * @param  {object}    $clickBtn
         * @return {boolean}
         */
        send: function ($clickBtn) {
            var $formSelector = $($clickBtn).parents('form');
            var $submitBtn = $($formSelector).find("[type='submit']");
            var flag_exit = false;

            // highlight input if error
            if (form.validate($formSelector)) {
                var $error_item = $('.error').first();
                var firstTop = $error_item.parent().offset().top;
                $('body,html').animate({
                    scrollTop: firstTop
                }, 'slow');
                if ($error_item.is('[type=text], textarea')) {
                    $error_item.first().focus();
                }
                swal({
                    title: form.warning_msg,
                    text: form.warning_check_inputs,
                    type: 'error',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 1500
                });
                return false;
            }
            $.ajax({
                type: "POST",
                url: "/content/action",
                data: $formSelector.serialize() + "&ActionCollectInformation=1",
                beforeSend: function () {
                    $submitBtn.addClass('send').attr('disabled', true);
                },
                success: function (result) {
                    if (result.indexOf("Collected information") !== -1) {
                        $submitBtn.removeClass('send');
                        swal({
                            title: form.success_msg,
                            text: form.success_form_sent,
                            type: 'success',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timer: 1500
                        });
                        // scroll to the success message
                        $formSelector.trigger('reset');
                    } else if (result.indexOf("Missing or invalid input") !== -1) {
                        swal({
                            title: form.warning_msg,
                            text: message,
                            type: 'error',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timer: 1500
                        });

                    } else {
                        swal({
                            title: form.warning_msg,
                            text: form.warning_goes_wrong,
                            type: 'error',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timer: 1500
                        });
                        $submitBtn.removeClass('send');
                    }
                },
                error: function (data) {
                    console.log("error", data);
                }
            })
                .always(function () {
                    $submitBtn.removeAttr('disabled');
                });
            return false;
        },
        /**
         * Form validation
         * @method validate
         * @param  {object} $formSelector
         * @return {boolean}
         */
        validate: function ($formSelector) {
            var flag_exit = false;
            var flag_is_checked = false;
            // check type=text and textarea
            $formSelector.find('[type=text]').each(function () {
                if ($(this).attr('required') && !$(this).val().length) {
                    $(this).addClass('error');
                    flag_exit = true;
                    // check quantity of symbols
                } else if ($(this).val().length > 20) {
                    $(this).addClass('error');
                    flag_exit = true;
                    swal({
                        title: form.warning_msg,
                        text: form.warning_long_text,
                        type: 'error',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 1500
                    });
                    return false;
                }
            });
            $formSelector.find('textarea').each(function () {
                if ($(this).attr('required') && !$(this).val().length) {
                    $(this).addClass('error');
                    flag_exit = true;
                    // check quantity of symbols
                } else if ($(this).val().length > 3000) {
                    $(this).addClass('error');
                    flag_exit = true;
                    swal({
                        title: form.warning_msg,
                        text: form.warning_long_text,
                        type: 'error',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 1500
                    });
                    return false;
                }
            });
            return flag_exit;
        }
    };
    return form;
});
