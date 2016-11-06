define(['jquery', 'bootstrap'], function () {
    var main = {
        init: function () {
            $('[data-toggle="tooltip"]').tooltip(
                {"trigger": "hover", delay: { "show": 500, "hide": 100 }}
            );

        },
        /**
         * Send Form
         */
        sendVote: function () {
            $('.vote-action').on('click touchstart', 'button', function (event) {
                event.preventDefault();
                var $voteBtn = $(this);
                $voteBtn.parent().find('button').attr('disabled', true);
                $voteBtn.find('i').addClass('fa-circle-o-notch fa-spin');

                var userId = parseInt($voteBtn.parent().data('id'));
                var isGoodVote = $voteBtn.hasClass('vote-up');
                // console.log($(this), userId, isGoodVote);
                $.ajax({
                    url: '/vote',
                    method: 'GET',
                    data: {
                        id: userId,
                        isGoodVote: isGoodVote
                    },
                    success: function (result) {
                        if (result['isError'] == false) {
                            swal({
                                title: 'Success!',
                                text: 'You are successfully voted for this user',
                                type: 'success',
                                timer: swalWaitTime
                            });
                            setTimeout(function () {
                                location.href = '/';
                            }, swalWaitTime);
                        } else {
                            swal({
                                title: 'You\'ve got some errors!',
                                text: 'Please be more patience and follow instructions',
                                type: 'warning',
                                timer: swalWaitTime
                            })
                        }
                    },
                    error: function (result) {
                        console.log(result);
                        $voteBtn.parent().find('button').attr('disabled', false);
                    }

                })

                    .always(function () {
                        console.log('jere i am');
                        $voteBtn.find('i').removeClass('fa-circle-o-notch fa-spin');
                    });
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
        }


    };
    return main;
});