var aristoFramework = window.aristoFramework || {};
var currentContentValue;
var soundClass = new assetLoader.soundjs();
var currentFrame = 0;
var stage, preload, lib = {};

(function () {
    var contentController = function (scope, $http, $rootScope, globalSettingService, globalVariableService, $timeout) {
        this.http = $http;
        this.scope = scope;
        this.globalSettingService = globalSettingService;
        this.$rootScope = $rootScope;
        this.timeout = $timeout;
        this.globalVariableService = globalVariableService;

        this.toc = {};
        this.language = this.globalVariableService.language;
        this.showHeader = true;
        this.contentData = undefined;
        this.pageContentType = "";
        this.fullScreen = true; 

        // The main change: always use the modern content template
        this.pageContentTemplate = 'theme/modern-content.html';

        document.getElementById("preloader").className = "preloaderteempcls";
        currentContentValue = "";
        this.scope.$on('initalizeController', assetLoader.proxy(this.globalSettingJson, this));
        this.scope.$on('getTocData', assetLoader.proxy(this.getTocData, this));
        this.scope.$on('showHeader', assetLoader.proxy(this.showHeaderFunc, this));
    };

    var p = contentController.prototype;

    p.showHeaderFunc = function () {
        this.showHeader = true;
    };

    p.globalSettingJson = function () {
        this.globalSettings = this.globalSettingService.getGlobalSettings();
    };

    p.getTocData = function () {
        document.getElementById("preloader").style.opacity = "1";
        this.toc = this.globalVariableService.getTocData();
        if (this.globalVariableService.toclevel == false) {
            this.modulenumber = this.globalVariableService.pagesmodcount[this.globalVariableService.getPageCounter() - 1][0];
            this.currentpagenumber = this.globalVariableService.pagesmodcount[this.globalVariableService.getPageCounter() - 1][1];
            this.header = this.toc[this.modulenumber][this.currentpagenumber - 1]['header'];
        } else {
            this.header = this.toc[this.globalVariableService.getPageCounter() - 1].header;
        }
        this.http({
            method: 'GET',
            url: 'assets/json/toc.json'
        }).then(assetLoader.proxy(this.getPageJSON, this), function errorCallback(response) {});
    };

    p.getPageJSON = function (response) {
        try {
            const responseData = response.data;
            if (responseData && responseData["0"] && Array.isArray(responseData["0"]) && responseData["0"].length > 0) {
                const pageSettings = responseData["0"][this.globalVariableService.getPageCounter() - 1].settings;
                response = pageSettings;

                if (document.getElementById("captivateFrame")) {
                    document.getElementById("captivateFrame").contentWindow.cp = undefined;
                }

                document.body.style.display = "block";
                document.getElementById("preloader").style.display = "block";
                document.getElementById("preloader").style.opacity = "1";

                // Reset content type and path before loading new content
                this.pageContentType = "";
                this.pageContent = "";

                const self = this;
                this.timeout(function () {
                    self.scope.$apply();
                    self.changeFooterBtnOnPage(pageSettings);
                }, 10);
            } else {
                console.error("Invalid or missing response data.", responseData);
            }
        } catch (error) {
            console.error("Error in getPageJSON:", error);
        }
    };

    p.changeFooterBtnOnPage = function (response) {
        var currentPageNo = this.globalVariableService.getPageCounter() - skipPage;
        var TotalPageNo = Totalpage - skipPage;

        document.getElementById("pagenoHeader").innerHTML = "" + currentPageNo + "/" + TotalPageNo;

        this.contentData = response.content;
        this.loadContent(response); 
    };

    p.loadContent = function (response) {
        if (response.preloader) {
            var preload = new assetLoader.preload();
            preload.loadManifest(response.preloader);
            preload.addEventListener("complete", assetLoader.proxy(this.displayContent, this));
        } else {
            this.displayContent();
        }
    };

    p.displayContent = function () {
        var contentCounter = this.globalVariableService.getContentCounter();
        currentContentValue = this.contentData[contentCounter];
        document.getElementById("preloader").style.display = "none";

        this.pageContentType = currentContentValue.type;
        this.pageContent = currentContentValue.path; // For video, html, etc.

        // The view will update automatically because of the bindings in modern-content.html
        const self = this;
        this.timeout(function () {
            self.scope.$apply();
            if (self.pageContentType === 'video') {
                var vid = document.getElementById("vidArea");
                if (vid) {
                    vid.play();
                    vid.oncontextmenu = function (e) { e.preventDefault(); };
                }
            }
        }, 10);
    };
    
    p.checkNextContent = function (contentCounter) {
        if (contentCounter < this.contentData.length) {
            soundClass.stop();
            var self = this;
            this.pageContent = "";
            this.timeout(function () {
                self.scope.$apply();
                self.globalVariableService.setContentCounter(contentCounter);
                self.displayContent();
            }, 10);
        }
    };

    p.finishLoading = function () {
        // This function might need to be adapted if it's still in use with the new design.
    };

    contentController.$inject = ['$scope', '$http', '$rootScope', 'globalSettingService', 'globalVariableService', '$timeout'];
    aristoFramework.contentController = contentController;
}());
