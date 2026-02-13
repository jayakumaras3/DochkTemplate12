var aristoFramework = window.aristoFramework || {};
(function () {
    'use strict';
    /**
     @ngdoc theme
     @name aristoFramework
     @description A Framework to view the flash content in an interactive manner. All the configuration are in JSON format
     to update the content
     **/
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
                  //  templateUrl: "assets/template/content.html" 
				  templateUrl: "theme/content.html"
                })
                .when("/certificate", {
                    templateUrl: "assets/template/certification.html"
                })
                .when("/content", {
                    templateUrl: "theme/content.html"
                  //  templateUrl: "assets/template/content.html"
                })

        });
}());
