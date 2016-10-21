define(['jquery', 'bootstrap'], function () {

    var common = {
        /**
         * Input point
         * @method init
         */
        init: function () {

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
