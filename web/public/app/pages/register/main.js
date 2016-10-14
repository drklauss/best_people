define(['jquery', 'bootstrap'], function () {
    var main = {
        init: function () {
            $('[data-toggle="tooltip"]').tooltip(
                { "trigger": "hover" }
            );
            $('#js_disclose_password').click(function () {
                if($(this).prop('checked')){
                    $('#register_form_password').attr('type','text')
                } else {
                    $('#register_form_password').attr('type','password')
                }
            })
        }

    }
    return main;
});