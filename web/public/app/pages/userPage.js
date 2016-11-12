define(['jquery', 'editor'], function ($, MediumEditor) {
    var flagExit = true;
    var swalWaitTime = 1500;
    var editorConfig = {
        toolbar: {
            /* These are the default options for the toolbar,
             if nothing is passed this is what is used */
            allowMultiParagraphSelection: true,
            buttons: ['bold', 'italic', 'underline', 'orderedlist', 'unorderedlist',
                'justifyLeft', 'justifyCenter', 'justifyRight'],
            diffLeft: 30,
            diffTop: -10,
            firstButtonClass: 'medium-editor-button-first',
            lastButtonClass: 'medium-editor-button-last',
            relativeContainer: null,
            standardizeSelectionStart: false,
            static: false,
            /* options which only apply when static is true */
            align: 'center',
            sticky: false,
            updateOnEmptySelection: false
        },
        placeholder: {
            text: 'Write your comment here...',
            hideOnClick: true
        }
    };
    var user = {
        /**
         * Main init function
         */
        init: function () {
            var editor = new MediumEditor('.medium-editor', editorConfig);
            editor.subscribe('editableInput', function (event) {
                $('#jsCommentArea').val(event.target.innerHTML);
            });
            user.sendForm();
        },
        /**
         * Send Comments Form
         */
        sendForm: function () {
            var sendBtn = $('#jsSendCommentBtn');
            sendBtn.on('click touchstart', function (event) {
                event.preventDefault();
                //
                if (user.isLongMessage()) {
                    return false;
                } else {
                    sendBtn.attr('disabled', true);
                    $.ajax({
                        url: '/message/set',
                        method: 'POST',
                        data: $('#jsCommentsForm').serialize(),
                        success: function (result) {
                            sendBtn.attr('disabled', false);
                            if (result['isError'] == false) {
                                swal({
                                    title: 'Message approved!',
                                    text: 'Now page will be reloaded',
                                    showConfirmButton: false,
                                    type: 'success',
                                    timer: swalWaitTime

                                });
                                setTimeout(function () {
                                    location.reload();
                                }, swalWaitTime);
                            } else {
                                swal({
                                    title: 'You\'ve got some errors!',
                                    text: result['errors']['message'],
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
                            sendBtn.attr('disabled', false);
                        });
                }
            });
        },
        /**
         * Checks message length
         */
        isLongMessage: function () {
            if ($('#jsCommentArea').val().length > 3000) {
                swal({
                    title: 'Seems you a great writer...',
                    text: 'Your text is too long!',
                    showConfirmButton: false,
                    type: 'warning',
                    timer: swalWaitTime
                });
                return true;
            } else if ($('#jsCommentArea').val().length < 5) {
                swal({
                    title: 'Seems you a bad writer...',
                    text: 'Please, write anything else!',
                    showConfirmButton: false,
                    type: 'warning',
                    timer: swalWaitTime
                });
                return true;
            }
            else {
                return false;
            }
        }
    };
    return user;
});