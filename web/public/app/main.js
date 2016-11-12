var commonPath = "/public/";
// list of used css files
var cssList = {
    bootstrap: commonPath + "vendor/bootstrap/css/bootstrap.min.css",
    screen: commonPath + "assets/stylesheets/screen.css",
    fontAwesome: commonPath + "vendor/font-awesome/css/font-awesome.min.css",
    swal: commonPath + "vendor/sweetalert/sweetalert.css",
    mediumEditor: commonPath + "vendor/medium-editor/css/medium-editor.min.css",
    mediumEditorTheme: commonPath + "vendor/medium-editor/css/themes/beagle.min.css"
};
// need to prevent duplicate css files
var cssLoaded = [];
var swalWaitTime = 1500;

requirejs.config({
    waitSeconds: 15,
    shim: {
        bootstrap: {
            deps: ['jquery']
        },
        editor: {
            deps: ['jquery']
        }
    },
    paths: {
        jquery: "vendor/jquery/jquery.min",
        bootstrap: "vendor/bootstrap/js/bootstrap.min",
        swal: "vendor/sweetalert/sweetalert.min",
        editor: "vendor/medium-editor/js/medium-editor.min"
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
        common.loadCss(cssList.swal);
    }
);

if (typeof(homePage) !== 'undefined') {
    requirejs([
            'app/pages/homePage'
        ], function (home) {
            home.init();
            home.sendVote();
        }
    );
}
if (typeof(userInfo) !== 'undefined') {
    requirejs([
            'app/pages/homePage',
            'app/pages/userPage',
            'app/common/script'
        ], function (home, user, common) {
            common.loadCss(cssList.mediumEditor);
            common.loadCss(cssList.mediumEditorTheme);
            home.init();
            user.init();
            home.sendVote();
        }
    );
}
if (typeof(registerPage) !== 'undefined') {
    requirejs([
            'app/pages/registerPage'
        ], function (register) {
            register.init();
            register.sendForm();
        }
    );
}
if (typeof(loginPage) !== 'undefined') {
    requirejs([
            'app/pages/loginPage'
        ], function (login) {
            login.init();
            login.sendForm();
        }
    );
}