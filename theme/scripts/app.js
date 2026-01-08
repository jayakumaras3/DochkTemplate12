var aristoFramework = window.aristoFramework || {};
(function () {
    'use strict';
    
    angular.module('aristoFramework', ["ngRoute", 'ngSanitize', 'radialIndicator'])
        .service('globalSettingService', aristoFramework.globalSettingService)
        .service('globalVariableService', aristoFramework.globalVariableService)
        .controller('mainController', aristoFramework.mainBarController)
        .controller('contentController', aristoFramework.contentController)
        .controller('footerBar', aristoFramework.footerBarController)
        .controller('sideBar', aristoFramework.sideBarController)
        .controller('loginController', aristoFramework.loginController)
        .controller('certificateController', aristoFramework.certificateController)
        .config(function ($routeProvider) {
            $routeProvider
                .when("/", {
                    templateUrl: "theme/modern-content.html"
                })
                .when("/certificate", {
                    templateUrl: "assets/template/certification.html"
                })
                .when("/content", {
                    templateUrl: "theme/modern-content.html"
                });
        });
}());
