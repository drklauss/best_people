var common_path = "/public/";
// list of used css files
var css_list = {
    bootstrap: common_path + "vendor/bootstrap/dist/css/bootstrap.min.css",
    screen: common_path + "assets/stylesheets/screen.css",
    font_awesome: common_path + "vendor/font-awesome/css/font-awesome.min.css"
};
// need to prevent duplicate css files
var css_loaded = [];

requirejs.config({
    waitSeconds: 15,
    shim: {
        bootstrap: {
            deps: ['jquery']
        }
    },
    paths: {
        jquery: "vendor/jquery/dist/jquery.min",
        bootstrap: "vendor/bootstrap/dist/js/bootstrap.min",
        swal: "vendor/sweetalert2/dist/sweetalert2.min"
    },
    baseUrl: common_path,
    urlArgs: 'v=0.1'
});

requirejs(['app/common/script'], function (common) {
    common.init();
    common.loadCss(css_list.screen);
    common.loadCss(css_list.bootstrap);
    common.loadCss(css_list.font_awesome);
});


