define(['jquery', 'bootstrap'], function () {
    var home = {
        getTopListUrl: '/get_top_list',
        loaderDelay: 1000,
        init: function () {
            setTimeout(home.reloadUsersList, home.loaderDelay);

            $('[data-toggle="tooltip"]').tooltip(
                {"trigger": "hover", delay: {"show": 500, "hide": 100}}
            );
        },
        /**
         * Send Vote
         */
        sendVote: function () {
            $('#jsTopUsersList').on('click touchstart', 'button', function (event) {
                event.preventDefault();
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
                            home.reloadUsersList();
                            // update user karma after sending vote

                            home.reloadUsersList();
                            $voteBtn.siblings('button').removeClass('btn-primary');
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
         */
        reloadUsersList: function () {
            var $topUsersListBlock = $('#jsTopUsersList');
            var $loaderBlock = $('#jsListLoading');
            $loaderBlock.removeClass('hidden');
            $topUsersListBlock.fadeOut('slow');

            var startTime = new Date().getTime();
            $.ajax({
                url: home.getTopListUrl,
                method: 'POST',
                success: function (result) {
                    var endTime = new Date().getTime();
                    // if internet connection is low - set delay = 0
                    var delay = (endTime - startTime) < 1000 ? home.loaderDelay : 0;
                    console.log(delay);
                    setTimeout(
                        function () {
                            $loaderBlock.addClass('hidden');
                            $topUsersListBlock.html(result);
                            $topUsersListBlock.fadeIn('slow');
                        }, delay
                    );
                },
                error: function (result) {
                    console.log(result);
                }

            })

        }

    };
    return home;
});