/**
 * Created by pc3 on 12/25/2016.
 */
/*jslint browser: true*/
var aristoFramework = window.aristoFramework || {};
var currentContentValue;
var soundClass = new assetLoader.soundjs();
var currentFrame = 0;
var stage, preload, lib = {};
(function () {
    /**
     * @ngdoc controller
     * @name aristoFramework.controller:contentController
     * @Description
     * Content controller is to control the header and content area. When controller is intailized it will fetch the pagecounter
     * global variable,load the json,load the preload data and display the content
     *
     * @param {Object} scope - scope injector of angularjs to update the model
     * @param {Object} $http - http provider of angularjs
     * @param {Object} $rootScope - parent of all the scope used here for  brodcast the event
     * @param {Object} globalSettingService - A service which we used for storing all the setting globally
     * @param {Object} globalVariableService - A service which we store all global variables
     * @param {Object} $timeout - timeout function in angularjs
     */
    var contentController = function (scope, $http, $rootScope, globalSettingService, globalVariableService, $timeout) {
        this.http = $http;
        this.scope = scope;
        this.globalSettingService = globalSettingService;
        this.$rootScope = $rootScope;
        this.timeout = $timeout;
        this.globalVariableService = globalVariableService;
		//this.onvideoended=false;
        /**
         * To store all the toc data
         *
         * @property toc
         * @type Object
         * @default ""
         */
        this.toc = {};
        /**
         * To store the language  which we set in the global variable service
         *
         * @property language
         * @type String
         * @default "english"
         */
        this.language = this.globalVariableService.language;
        /**
         * To display the header or not
         *
         * @property showHeader
         * @type Boolean
         * @default true
         */
        this.showHeader = true;
        /**
         * To get the content from the page json
         *
         * @property contentData
         * @type object
         * @default undefined
         */
        this.contentData = undefined;
        /**
         * To store the page content type which is used on the navigation and index page
         * to display the DOM element or not
         *
         * @property pageContentType
         * @type String
         * @default ""
         */
        this.pageContentType = "";
        /**
         * To set the full screen or not
         *
         * @property fullScreen
         * @type Boolean
         * @default  true
         */
        this.fullScreen = true;

        document.getElementById("preloader").className = "preloaderteempcls";
        currentContentValue = "";
        var self = this;
        this.scope.$on('initalizeController', assetLoader.proxy(this.globalSettingJson, this));
        this.scope.$on('getTocData', assetLoader.proxy(this.getTocData, this));
        this.scope.$on('showHeader', assetLoader.proxy(this.showHeaderFunc, this));
        // Hide nav loader after ng-include content is in the DOM and all images are loaded.
        // Flash pages are excluded here — finishLoading() fires after the canvas is ready.
        this.scope.$on('$includeContentLoaded', function() {
            if (self.pageContentType === 'flash') return;
            var area = document.getElementById('htmlArea');
            var imgs = area ? area.querySelectorAll('img') : [];
            if (!imgs.length) {
                if (typeof hideNavLoader === 'function') hideNavLoader();
                return;
            }
            var loaded = 0, total = imgs.length, done = false;
            function onImgDone() {
                if (done) return;
                if (++loaded >= total) { done = true; if (typeof hideNavLoader === 'function') hideNavLoader(); }
            }
            for (var i = 0; i < imgs.length; i++) {
                if (imgs[i].complete) { onImgDone(); }
                else {
                    imgs[i].addEventListener('load', onImgDone);
                    imgs[i].addEventListener('error', onImgDone);
                }
            }
        });

    };
    var p = contentController.prototype;
    p.showHeaderFunc = function ()
    {
        this.showHeader = true;

    }
    /**
     * @ngdoc method
     * @name globalSettingJson
     * @methodOf aristoFramework.controller:contentController
     * @description
     * The function will called when MainController will broadcast the intailizecontroller message
     * when the global setting is loaded
     *
     */
    p.globalSettingJson = function () {
        this.globalSettings = this.globalSettingService.getGlobalSettings();
    };
    /**
     * @ngdoc method
     * @name getTocData
     * @methodOf aristoFramework.controller:contentController
     * @description
     * The function will called when SidebarController will broadcast the getTocData message
     * when the toc json is loaded
     *
     */
    p.getTocData = function () {

        var preloader = document.getElementById("preloader");
        if (preloader) {
            preloader.style.opacity = "1";
        }

        this.toc = this.globalVariableService.getTocData() || {};

        var currentPage = this.globalVariableService.getPageCounter();
        if (!currentPage || currentPage < 1) {
            currentPage = 1;
            this.globalVariableService.setPageCounter(1);
        }

		if (this.globalVariableService.toclevel == false) {
            var pageMap = this.globalVariableService.pagesmodcount[currentPage - 1];
            if (pageMap && this.toc[pageMap[0]] && this.toc[pageMap[0]][pageMap[1] - 1]) {
                this.modulenumber = pageMap[0];
                this.currentpagenumber = pageMap[1];
                this.header = this.toc[this.modulenumber][this.currentpagenumber - 1]['header'];
            } else if (this.toc[0] && this.toc[0][currentPage - 1]) {
                this.header = this.toc[0][currentPage - 1].header;
            } else {
                this.header = "";
            }
		}
		else {
			if (this.toc[currentPage - 1] && this.toc[currentPage - 1].header) {
				this.header = this.toc[currentPage - 1].header;
			} else if (this.toc[0] && this.toc[0][currentPage - 1]) {
				this.header = this.toc[0][currentPage - 1].header;
			} else {
				this.header = "";
			}
		}
        this.http({
            method: 'GET',
			url: 'assets/json/toc.json'
			//url: 'assets/content/' + this.language + '/toc.json'
            //url: 'assets/content/' + this.language + '/pages/page' + this.globalVariableService.getPageCounter() + '.json'
        }).then(assetLoader.proxy(this.getPageJSON, this), function errorCallback(response) {

        });

    };
    /**
     * @ngdoc method
     * @name getPageJSON
     * @methodOf aristoFramework.controller:contentController
     * @param  {object} response JSON object of indivual page
     * @description
     * callback after when each page is loaded
     *
     */
	p.getPageJSON = function (response) {
		try {
            var responseData = response ? response.data : null;
            var pages = [];

            if (responseData && Array.isArray(responseData["0"])) {
                pages = responseData["0"];
            } else if (Array.isArray(responseData)) {
                pages = responseData;
            }

            if (!pages.length) {
                console.error("Invalid or missing response data. Data structure:", responseData);
                return;
            }

            var currentPageIndex = (this.globalVariableService.getPageCounter() || 1) - 1;
            if (currentPageIndex < 0) {
                currentPageIndex = 0;
            }
            if (currentPageIndex >= pages.length) {
                currentPageIndex = pages.length - 1;
                this.globalVariableService.setPageCounter(currentPageIndex + 1);
            }

            var pageObj = pages[currentPageIndex] || {};
            var firstPageSettings = pageObj.settings || null;
            if (!firstPageSettings) {
                console.error("Missing page settings for index:", currentPageIndex);
                return;
            }

            if (document.getElementById("captivateFrame")) {
                document.getElementById("captivateFrame").contentWindow.cp = undefined;
            }

            document.body.style.display = "block";
            var preloaderElem = document.getElementById("preloader");
            if (preloaderElem) {
                preloaderElem.style.display = "block";
                preloaderElem.style.opacity = "1";
            }

            this.pageContentType = "";
            this.pageContent = "";

            var self = this;
            this.timeout(function () {
                if (!self.scope.$$phase) {
                    self.scope.$apply();
                }
                self.changeFooterBtnOnPage(firstPageSettings);
            }, 10);
		} catch (error) {
			// Log unexpected errors
			console.error("Error in getPageJSON:", error);
		}
	};

    /**
     * @ngdoc method
     * @name changeFooterBtnOnPage
     * @methodOf aristoFramework.controller:contentController
     * @param  {object} response JSON object of indivual page
     * @description
     * To apply the footer button style and fullscreen property from indivdual page json
     *
     */
    p.changeFooterBtnOnPage = function (response) {
		
		//console.log("skipPage :"+skipPage);
       // document.getElementById("pageNo").innerHTML = response.data.pageNumber;
		var currentPageNo=this.globalVariableService.getPageCounter()-skipPage;
		//console.log("this.globalVariableService.getPageCounter() :"+this.globalVariableService.getPageCounter());
		var TotalPageNo=Totalpage-skipPage;
		//console.log("TotalPageNo1:: "+Totalpage)
       // document.getElementById("pageNo").innerHTML = ""+currentPageNo+"/"+TotalPageNo;
	    if(AudioVersionEnable){
		//	console.log("currentPageNo cc :"+currentPageNo);
			var curcurrentPageNo=currentPageNo-1;
			var curTotalPageNo=TotalPageNo-1;
			//console.log("curcurrentPageNo "+curcurrentPageNo);
	    document.getElementById("pagenoHeader").innerHTML = ""+curcurrentPageNo+"/"+curTotalPageNo;
		}
		else{
		//	console.log("currentPageNo22  "+currentPageNo);
			document.getElementById("pagenoHeader").innerHTML = ""+currentPageNo+"/"+TotalPageNo;
		}

		var footerEvent;
		var sideBarEvent;
		
		//single page functionality  
	//	console.log("Totalpage"+Totalpage);
		if(Totalpage==1)
		{			
			sideBarEvent = (response.sidebar) ? this.$rootScope.$broadcast("showSideBar") : this.$rootScope.$broadcast("showSideBar");
			footerEvent = (response.footer) ? this.$rootScope.$broadcast("hideFooter") : this.$rootScope.$broadcast("hideFooter");
			this.showHeader = (response.header !== undefined) ? response.header : response.header;
			this.fullScreen = (response.fullScreen) ? response.fullScreen : response.fullScreen;
		}
		else{
			sideBarEvent = (response.sidebar) ? this.$rootScope.$broadcast("showSideBar") : this.$rootScope.$broadcast("hideSideBar");
			footerEvent = (response.footer) ? this.$rootScope.$broadcast("showFooter") : this.$rootScope.$broadcast("hideFooter");
			this.showHeader = (response.header !== undefined) ? response.header : true;
			this.fullScreen = (response.fullScreen) ? response.fullScreen : false;
			
		}
		
        this.contentData = response.content;
        this.globalVariableService.navCircle = (response.navigation) ? response.navigation : [];
        this.globalVariableService.audscript = (response.cc) ? response.cc : [];
		
        this.globalVariableService.modulenumber = response.module;
        var nextElem = angular.element(document.getElementById("next"));
        var prevElem = angular.element(document.getElementById("prev"));
        var pauseElem = angular.element(document.getElementById("pause"));
        var ccElem = angular.element(document.getElementById("cc"));
        var resourceElem = angular.element(document.getElementById("resource"));
        $("#audscript").html('');
        var preloaderText = document.getElementById("preloaderText");
		resourceElem.addClass("disabledClass");
		resourceElem.removeAttr("title");
		ccElem.addClass("disabledClass");
        ccElem.removeAttr("title");
		enablemute();enablenextbtn();enableplapause();
		//show menu and header 
	//	console.log( this.fullScreen);
		//console.log( this.showHeader);
		if (!this.fullScreen && this.showHeader)
		{
			
			//menuEnDisble_fun(true);
			TogglemenuControl=true;
		}
		if (this.fullScreen && this.showHeader && !this.footerEvent)
		{
			
			//menuEnDisble_fun(true);
			HF_footerdisBool=true;
			TogglemenuControl=true;
		}
        if (this.fullScreen === true) {
            preloaderText.style.marginLeft = "33%";
        } else {

            document.getElementById("preloader").className = "";
            preloaderText.style.marginLeft = "40%";
        }

        if (response.nextBtnDisabled === true) {
            nextElem.addClass("disabledClass");
            nextElem.removeAttr("title");
        } else {
            nextElem.removeClass("disabledClass");
           nextElem.attr("title", NextTitle);
        }

        this.$rootScope.$broadcast("navigationPage");
        this.loadContent(response);
        if (this.globalVariableService.getPageCounter() === 1) {
			//prevElem.setAttribute('aria-disabled', 'true'); 
            prevElem.addClass("disabledClass");
            prevElem.removeAttr("title");
			// Assuming it's disabled
			
        } 
		if(masterBool)
		{
			
		}
		else{
		 if(pageArray[this.globalVariableService.getPageCounter()-1]==1)
		 {
			 enablenextbtn();
			

		 }
		 else if (this.globalVariableService.getPageCounter() >=1) {
          
		    nextElem.addClass("disabledClass");
			nextElem.removeAttr("title", NextTitle)
        }	
		}
		if (this.globalVariableService.getPageCounter()== Totalpage) {
         nextElem.addClass("disabledClass");
      
			nextElem.removeAttr("title", NextTitle);
         }
		 if(AudioVersionEnable){
				 if(this.globalVariableService.getPageCounter()==1)
				 {
					nextElem.addClass("disabledClass");
					nextElem.removeAttr("title", NextTitle)
					 pagenumberEna_DIsable(false);
					if(TogglemenuControl)
					{
						
					}
					else
					{
						menuEnDisble_fun(false);
					}

				 } else if(this.globalVariableService.getPageCounter()==2)
				 {
				//	 alert();
						pagenumberEna_DIsable(false);
						menuEnDisble_fun(true);
					    nextElem.addClass("disabledClass");
						nextElem.removeAttr("title", NextTitle);
					 
				 }else
				 {
					  pagenumberEna_DIsable(true);
					 menuEnDisble_fun(true); 
				 }
			 
		}
		else
		{
			if(this.globalVariableService.getPageCounter()==1)
			 {
				
				if(TogglemenuControl)
				{
					
				}
				else
				{
					menuEnDisble_fun(false);
				}

			 }
			 else
			 {
				menuEnDisble_fun(true); 
			 }
			
		}
		NexPrevAcessiblity_Check();

    };
    /**
     * @ngdoc method
     * @name loadContent
     * @methodOf aristoFramework.controller:contentController
     * @param  {object} response JSON object of indivdual page
     * @description
     * Fetch the preload content in json and pass it to the preloader class to load the data
     *
     */
    p.loadContent = function (response) {
        this.globalVariableService.setContentCounter(0);
        currentCaptivativeFrames = 0;
        for (var prop in lib) {
            delete lib[prop];
        }
        if (preload) {
            preload.unload();
            preload.removeAll();

        }

        if (response.preloader) {
            preload = new assetLoader.preload();
            preload.loadManifest(response.preloader);
            preload.addEventListener("complete", assetLoader.proxy(this.displayContent, this));
            $(".wholeContainer").show();
        } else {
            $(".wholeContainer").show();
            this.displayContent();

        }
    };
    /**
     * @ngdoc method
     * @name checkNextContent
     * @methodOf aristoFramework.controller:contentController
     * @param {number} contentCounter counter variable which will change depends on the content
     * @description
     * To check whether the next content to load is there
     *
     */
    p.checkNextContent = function (contentCounter) {
        if (contentCounter < this.contentData.length) {
            if (contentCounter > 1) {
                for (var prop in lib) {
                    delete lib[prop];
                }
            }
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
    /**
     * @ngdoc method
     * @name displayContent
     * @methodOf aristoFramework.controller:contentController
     * @description
     * To display the content in the content placeholder
     *
     */
    p.displayContent = function () {
        var contentCounter = this.globalVariableService.getContentCounter();
        currentContentValue = this.contentData[contentCounter];
        document.getElementById("preloader").style.display = "none";
        document.body.style.display = "block";
        var self = this;
		CurrentcontentType=currentContentValue.type;
		//console.log(currentContentValue.type);							
        switch (currentContentValue.type) {
            case "flash":

                this.pageContent = this.contentData[contentCounter].path;
				this.pageContentType = this.contentData[contentCounter].type;
                this.functionName = this.contentData[contentCounter].functionName;
                this.mouseover = this.contentData[contentCounter].mouseover;
                document.getElementsByClassName("pageContent")[0].style.height = "655px";
                this.timeout(function () {
                    self.scope.$apply();
                }, 10);

                break; 
			case "html":
				//console.log("dfgdfg+++",this.contentData[contentCounter].type);
                this.pageContent = this.contentData[contentCounter].path;
				this.pageContentType = this.contentData[contentCounter].type;
                document.getElementsByClassName("pageContent")[0].style.height = "655px";
                this.timeout(function () {
                    self.scope.$apply();
                }, 10);

                break;
			case "video":

                this.pageContent = this.contentData[contentCounter].path;
				this.pageContentType = this.contentData[contentCounter].type;
				this.globalVariableService.onvideoended = this.contentData[contentCounter].onendnextscrn;
				disableplapause();
				this.timeout(function () {
                    self.scope.$apply();
				var vid = document.getElementById("vidArea");
				if (vid) {
					vid.play();
					vid.oncontextmenu = function(event) { event.preventDefault(); };
					// Hide loader when the browser has buffered the first frame
					var vidFired = false;
					function onVidReady() {
						if (vidFired) return;
						vidFired = true;
						vid.removeEventListener('loadeddata', onVidReady);
						vid.removeEventListener('error', onVidReady);
						if (typeof hideNavLoader === 'function') hideNavLoader();
					}
					vid.addEventListener('loadeddata', onVidReady);
					vid.addEventListener('error', onVidReady);
					if (vid.readyState >= 2) onVidReady(); // already cached
				} else {
					if (typeof hideNavLoader === 'function') hideNavLoader();
				}
                }, 10);

                break;
            case "captivate":
			  document.getElementsByClassName("pageContent")[0].style.height = "655px";
                this.pageContent = this.contentData[contentCounter].path;
               highlightNavCircle("s")
                this.pageContentType = this.contentData[contentCounter].type;
                // Loader is hidden by captivateIframeComplete() when the iframe fires onload
                this.timeout(function () {
                    self.scope.$apply();
                }, 10);

                break;
            case "Articulate":

                document.getElementsByClassName("pageContent")[0].style.height = "100%";
                document.getElementById("preloader").style.display = "block";
                document.getElementById("preloader").style.opacity = "0.01";
                this.showHeader = (this.contentData[contentCounter].showHeader) ? this.contentData[contentCounter].showHeader : false;
                this.pageContent = this.contentData[contentCounter].path;
               highlightNavCircle("s")
                this.pageContentType = this.contentData[contentCounter].type;
                this.timeout(function () {
                    self.scope.$apply();
                    if (typeof hideNavLoader === 'function') hideNavLoader();
                }, 10);

                break;

        }
    };
    /**
     * @ngdoc method
     * @name finishLoading
     * @methodOf aristoFramework.controller:contentController
     * @description
     * once the flash template is loaded to initalize the stage and call the main file
     *
     */
    p.finishLoading = function () {
		//console.log(this.functionName);
  
        var exportRoot = new lib[this.functionName]();
        var canvas = document.getElementById("canvas");
        stage = new createjs.Stage(canvas);
        if (this.mouseover) {
            stage.enableMouseOver();
        }
        stage.addChild(exportRoot);
        exportRoot.gotoAndPlay(currentFrame);
        stage.update();
        createjs.Ticker.setFPS(24);
        createjs.Ticker.addEventListener("tick", stage);
        currentFrame = 0;
        if (typeof hideNavLoader === 'function') hideNavLoader();
    };


    contentController.$inject = ['$scope', '$http', '$rootScope', 'globalSettingService', 'globalVariableService', '$timeout'];
    aristoFramework.contentController = contentController;
}());