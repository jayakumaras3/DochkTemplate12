var aristoFramework = window.aristoFramework || {};

(function () {
    /**
     * @ngdoc controller
     * @name aristoFramework.controller:mainBarController
     * @Description
     * SideBarController is to controll the TOC and transcript data
     * @param {Object} scope - scope injector of angularjs to update the model
     * @param {Object} $http - http provider of angularjs
     * @param {Object} globalSettingService - A service which we used for storing all the setting globally
     */
    var mainBarController = function (scope, $http, globalSettingService) {
        this.http = $http;
        this.scope = scope;
        this.globalSettingService = globalSettingService;

        $http({
            method: 'GET',
            url: 'theme/Json/config.json'
        }).then(assetLoader.proxy(this.setGlobalConfig, this), function errorCallback() {
           // console.log("error");
        });


    };
    var p = mainBarController.prototype;
    /**
     * @ngdoc method
     * @name setGlobalConfig
     * @methodOf aristoFramework.controller:mainBarController
     * @description
     * Callback function for the global settings json load complete and broadcast the initalize controller to
     * initalize the other controller
     */
    p.setGlobalConfig = function (response) {
        this.globalSettingService.setGlobalSettings(response.data);
        this.scope.$broadcast('initalizeController');
    };


    mainBarController.$inject = ['$scope', '$http', 'globalSettingService'];
    aristoFramework.mainBarController = mainBarController;

}());