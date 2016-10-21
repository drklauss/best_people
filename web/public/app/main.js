var commonPath = "/public/";
// list of used css files
var cssList = {
    bootstrap: commonPath + "vendor/bootstrap/dist/css/bootstrap.min.css",
    screen: commonPath + "assets/stylesheets/screen.css",
    fontAwesome: commonPath + "vendor/font-awesome/css/font-awesome.min.css",
    swal: commonPath + "vendor/sweetalert2/dist/sweetalert2.min.css"
};
// need to prevent duplicate css files
var cssLoaded = [];

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
    baseUrl: commonPath,
    urlArgs: 'v=0.12'
});

requirejs([
        'app/common/script'
    ], function (common) {
        common.init();
        common.loadCss(cssList.screen);
        common.loadCss(cssList.bootstrap);
        common.loadCss(cssList.fontAwesome);
    }
);

if (typeof(registerPage) !== 'undefined') {
    requirejs([
        'app/pages/register/main',
        'app/common/script'
    ], function (main, common) {

            main.init();
            main.sendForm();
            common.loadCss(cssList.swal);
        }
    );
}