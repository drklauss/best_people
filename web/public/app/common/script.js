define(['jquery', 'bootstrap', 'swal'], function () {

    var common = {
        /**
         * Input point
         * @method init
         */
        init: function () {
            common.logOut();
        },
        /**
         * Logout user
         */
        logOut: function(){
            $('#logOut').click(function (event) {
                event.preventDefault();
                $.post($(this).attr('href'), function(result){
                    if (result['isError'] == false) {
                        swal({
                            title: 'Successfully log out!',
                            text: 'Now you will be redirected to main page',
                            type: 'success',
                            timer: swalWaitTime
                        });
                        setTimeout(function(){
                            location.href = '/';
                        }, swalWaitTime);
                    } else {
                        main.addFormErrors(result['errors']);
                        swal({
                            title: 'You\'ve got some errors!',
                            text: result[errors][0],
                            type: 'warning',
                            timer: swalWaitTime
                        })
                    }
                });
            });
        },

        /**
         * loadCss files
         * @method loadCss
         * @param  {string} cssStr path
         */
        loadCss: function (cssStr) {
            if (cssLoaded.indexOf(cssStr) == -1) {
                var link = document.createElement("link");
                cssLoaded.push(cssStr);
                link.type = "text/css";
                link.rel = "stylesheet";
                link.href = cssStr;
                document.getElementsByTagName("head")[0].appendChild(link);
            }
        }
    };
    return common;
});
