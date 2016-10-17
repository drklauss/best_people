define(['jquery', 'bootstrap'], function () {
    var main = {
        init: function () {
            $('[data-toggle="tooltip"]').tooltip(
                {"trigger": "hover"}
            );
            $('#js_disclose_password').click(function () {
                if ($(this).prop('checked')) {
                    $('#js_password_input').attr('type', 'text')
                } else {
                    $('#js_password_input').attr('type', 'password')
                }
            });
            // imitate clicking on file button
            $('#js_file_input_trigger').on('click touchstart', function (event) {
                event.preventDefault();
                $('#js_file_input').click();
            });
            // write filename after file select
            $('#js_file_input').on('change', function(){
                var filename = $(this).val().split('\\').pop();
                $('#js_filename_area').val(filename);
            });
            // need to imitate selector
            $('#js_gender_select').on('click touchstart', 'button',function(event){
                event.preventDefault();
                $('#js_gender_select').find('button').removeClass('active');
                $(this).addClass('active');
            })
        }

    }
    return main;
});