var aristoFramework = window.aristoFramework || {};
(function () {
    "use strict";
    /**
     * @ngdoc service
     * @name aristoFramework.service:globalSettingService
     * @Description
      To store all the global configuration and share with each controller.
     */
    var globalSettingService = function () {
        this.globalSettings = undefined;
        this.language = "english";
    };

    var p = globalSettingService.prototype;
    /**
     * @ngdoc method
     * @name getGlobalSettings
     * @methodOf aristoFramework.service:globalSettingService
     * @description
     * To get the global settings
     */
    p.getGlobalSettings = function () {
        return this.globalSettings;
    };
    /**
     * @ngdoc method
     * @name setGlobalConfig
     * @methodOf aristoFramework.service:globalSettingService
     * @description
     * To store the global settings
     */
    p.setGlobalSettings = function (globalSettings) {
	this.globalSettings = globalSettings;
    };
    /**
     * @ngdoc method
     * @name setGlobalConfig
     * @methodOf aristoFramework.service:globalSettingService
     * @description
     * To get the language
     */
    p.getLanguage = function () {
        return this.language;
    };
    /**
     * @ngdoc method
     * @name setLanguage
     * @methodOf aristoFramework.service:globalSettingService
     * @description
     * To set the new language
     */
    p.setLanguage = function (language) {
        this.language = language;
    };
    aristoFramework.globalSettingService = globalSettingService;

}());