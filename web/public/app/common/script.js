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
         * @param  {string} css_str path
         */
        loadCss: function (css_str) {
            if (css_loaded.indexOf(css_str) == -1) {
                var link = document.createElement("link");
                css_loaded.push(css_str);
                link.type = "text/css";
                link.rel = "stylesheet";
                link.href = css_str;
                document.getElementsByTagName("head")[0].appendChild(link);
            }
        }
    };
    return common;
});
