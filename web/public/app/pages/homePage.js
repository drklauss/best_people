define(['jquery', 'bootstrap'], function () {
    var home = {
        init: function () {
            $('[data-toggle="tooltip"]').tooltip(
                {"trigger": "hover", delay: {"show": 500, "hide": 100}}
            );
        },
        /**
         * Send Vote
         */
        sendVote: function () {
            $('.vote-action').on('click touchstart', 'button', function (event) {
                event.preventDefault();
                console.log('ffff');
                var $voteBtn = $(this);
                $voteBtn.parent().find('button').attr('disabled', true);
                $voteBtn.find('i').addClass('fa-circle-o-notch fa-spin');

                var userId = parseInt($voteBtn.parent().data('id'));
                var isGoodVote = $voteBtn.hasClass('vote-up');
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
                                showConfirmButton: false,
                                timer: swalWaitTime
                            });
                            // update user karma after sending vote
                            setTimeout(function(){
                                location.reload();
                            }, swalWaitTime);
                            home.recalculateKarma(userId, $voteBtn);
                            $voteBtn.removeClass('btn-default').addClass('btn-primary');
                        } else {
                            swal({
                                title: 'You\'ve got some errors!',
                                text: result['errors']['voteError'],
                                type: 'warning',
                                showConfirmButton: false,
                                timer: swalWaitTime
                            });
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    }

                })
                    .always(function () {
                        // hack: destroys all tooltips
                        $('[data-toggle="tooltip"]').tooltip('destroy');
                        $voteBtn.parent().find('button').attr('disabled', false);
                        $voteBtn.find('i').removeClass('fa-circle-o-notch fa-spin');
                    });
            });
        },

        /**
         * Recalculate karma after sending vote
         * @param userId
         * @param $voteBtn
         */
        recalculateKarma: function (userId, $voteBtn) {
            var karmaEl = $voteBtn.parents('.item').siblings('.js-karma').find('h2');
            $.ajax({
                url: '/recalculate/' + userId,
                method: 'GET',
                success: function (result) {
                    karmaEl.text(result['karma']);
                },
                error: function (result) {
                    console.log(result);
                }

            })

        }

    };
    return home;
});