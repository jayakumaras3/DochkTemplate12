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
        // Flash pages are excluded — finishLoading() fires after the canvas is ready.
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
        try {
            // Show preloader using PreloadManager for smooth fade in
            if (typeof PreloadManager !== 'undefined') {
                PreloadManager.show();
            } else {
                var preloader = document.getElementById("preloader");
                if (preloader) preloader.style.opacity = "1";
            }
            this.toc = this.globalVariableService.getTocData();
            var currentPage = this.globalVariableService.getPageCounter();
            
            // Ensure page counter is valid
            if (!currentPage || currentPage < 1) {
                currentPage = 1;
                this.globalVariableService.setPageCounter(1);
            }
            
            if (this.globalVariableService.toclevel == false) {
                // Safe access with bounds checking
                var pageMap = this.globalVariableService.pagesmodcount[currentPage - 1];
                if (pageMap && this.toc && this.toc[pageMap[0]] && this.toc[pageMap[0]][pageMap[1] - 1]) {
                    this.modulenumber = pageMap[0];
                    this.currentpagenumber = pageMap[1];
                    this.header = this.toc[this.modulenumber][this.currentpagenumber - 1]['header'];
                } else if (this.toc && this.toc[0] && this.toc[0][currentPage - 1]) {
                    // Fallback to flat structure
                    this.header = this.toc[0][currentPage - 1].header;
                }
            } else if (this.toc && this.toc[currentPage - 1]) {
                // Safe flat access
                this.header = this.toc[currentPage - 1].header;
            }
        } catch (error) {
            console.error("Error in getTocData:", error);
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
			// Extract and validate response data
			var responseData = response.data;
			var currentPageIndex = this.globalVariableService.getPageCounter() - 1;
			
			// Validate page index is in bounds
			if (currentPageIndex < 0 || currentPageIndex >= (responseData && responseData["0"] ? responseData["0"].length : 0)) {
				// Correct out-of-bounds index
				currentPageIndex = 0;
				this.globalVariableService.setPageCounter(1);
			}

			// Ensure responseData["0"] exists and is an array
			if (responseData && responseData["0"] && Array.isArray(responseData["0"]) && responseData["0"].length > currentPageIndex) {
				
				var pageData = responseData["0"][currentPageIndex];
				var firstPageSettings = pageData && pageData.settings ? pageData.settings : null;

				if (!firstPageSettings) {
					console.warn("Page settings not found for index", currentPageIndex);
					return;
				}

				// Log the settings for debugging
				//console.log("Page Settings:", firstPageSettings);

				// Update response with settings
				response = firstPageSettings;

				// Reset captivateFrame if present
				if (document.getElementById("captivateFrame")) {
					document.getElementById("captivateFrame").contentWindow.cp = undefined;
				}

				// Update the UI
				document.body.style.display = "block";
				var preloader = document.getElementById("preloader");
				if (preloader) {
					preloader.style.display = "block";
					preloader.style.opacity = "1";
				}

				this.pageContentType = "";
				this.pageContent = "";

				// Use self to preserve context in timeout
				var self = this;
				this.timeout(function () {
					if (self.scope && self.scope.$apply) {
						try {
							self.scope.$apply();
						} catch (e) {
							// Already in $apply/digest phase
						}
					}
					self.changeFooterBtnOnPage(firstPageSettings);
				}, 10);
			} else {
				// Log an error if the response data is invalid
				console.error("Invalid or missing response data. Data structure:", responseData);
				return;
			}
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
        var actualPageCounter = this.globalVariableService.getPageCounter();
        var hidePageNumberForAudioIntro = AudioVersionEnable && (actualPageCounter === 1 || actualPageCounter === 2);
		//console.log("TotalPageNo1:: "+Totalpage)
       // document.getElementById("pageNo").innerHTML = ""+currentPageNo+"/"+TotalPageNo;
        if(hidePageNumberForAudioIntro){
        document.getElementById("pagenoHeader").textContent = "";
        document.getElementById("pagenoHeader").setAttribute("aria-label", "");
        pagenumberEna_DIsable(false);
        }
        else if(AudioVersionEnable){
		//	console.log("currentPageNo cc :"+currentPageNo);
			var curcurrentPageNo=currentPageNo-1;
			var curTotalPageNo=TotalPageNo-1;
			//console.log("curcurrentPageNo "+curcurrentPageNo);
	    document.getElementById("pagenoHeader").textContent = ""+curcurrentPageNo+"/"+curTotalPageNo;
	    document.getElementById("pagenoHeader").setAttribute("aria-label", "Page "+curcurrentPageNo+" of "+curTotalPageNo);
        pagenumberEna_DIsable(true);
		}
		else{
		//	console.log("currentPageNo22  "+currentPageNo);
			document.getElementById("pagenoHeader").textContent = ""+currentPageNo+"/"+TotalPageNo;
			document.getElementById("pagenoHeader").setAttribute("aria-label", "Page "+currentPageNo+" of "+TotalPageNo);
            pagenumberEna_DIsable(true);
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

        /**
         * Initialize Steps Toggle Button
         * Detect steps from response and initialize the toggle button feature
         */
        try {
            var steps = response.steps || [];
            this.globalVariableService.currentPageSteps = steps;

            // Keep footer state in sync directly (robust against event timing).
            var footerScope = angular.element(document.querySelector('.footer')).scope();
            if (footerScope && footerScope.fb && typeof footerScope.fb.updateStepsButtonVisibility === 'function') {
                footerScope.fb.updateStepsButtonVisibility(steps);
                if (!footerScope.$$phase) {
                    footerScope.$applyAsync();
                }
            }

            // Also broadcast for compatibility with existing listeners.
            this.$rootScope.$broadcast('updateSteps', steps);
            
            // Initialize StepsToggleButton module for panel management
            if (typeof window.StepsToggleButton !== 'undefined') {
                StepsToggleButton.reset(); // Reset state for new page
                
                if (steps && steps.length > 0) {
                    StepsToggleButton.init(steps);
                } else {
                    StepsToggleButton.destroy(); // Hide button if no steps
                }
            }
        } catch (err) {
            console.error('[ContentController] Error initializing StepsToggleButton:', err);
        }

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
        } else {
            nextElem.removeClass("disabledClass");
        }

        this.$rootScope.$broadcast("navigationPage");
        this.loadContent(response);
        if (this.globalVariableService.getPageCounter() === 1) {
			//prevElem.setAttribute('aria-disabled', 'true'); 
            prevElem.addClass("disabledClass");
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
        }	
		}
		if (this.globalVariableService.getPageCounter()== Totalpage) {
         nextElem.addClass("disabledClass");
         }
		 if(AudioVersionEnable){
				 if(this.globalVariableService.getPageCounter()==1)
				 {
					nextElem.addClass("disabledClass");
					if(TogglemenuControl)
					{
						
					}
					else
					{
                        
						menuEnDisble_fun(false);
					}

				 } else if(this.globalVariableService.getPageCounter()==2)
				 {
						menuEnDisble_fun(true);
					    nextElem.addClass("disabledClass");
					 
				 }else
				 {
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
        // Hide preloader using the PreloadManager for smooth fade out
        if (typeof PreloadManager !== 'undefined') {
            PreloadManager.hide();
        } else {
            document.getElementById("preloader").style.display = "none";
        }
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
				
				// Immediately after Angular creates the new video element (#vidArea with autoplay),
				// pause it and remove autoplay to prevent the browser from auto-playing it.
				// This ensures the only play trigger is onHidden() callback after preloader disappears.
				var vid = document.getElementById("vidArea");
				if (vid) {
					vid.pause();  // Pause any auto-play attempt
					vid.currentTime = 0;  // Reset to start
					vid.removeAttribute('autoplay');  // Remove autoplay so browser doesn't retry
				}
				
				// Defer video playback until preloader is fully hidden
				// This ensures no audio plays while the preloader is visible (MIN_SHOW_MS + FADE_MS)
				var startVideo = function () {
					var vid = document.getElementById("vidArea");
					if (vid) {
						// Ensure context menu is disabled
						vid.oncontextmenu = function(event) {
							event.preventDefault();
						};
						// Play video (currentTime already reset above)
						var playPromise = vid.play();
						if (playPromise !== undefined) {
							playPromise.catch(function(err) {
								// If autoplay policy blocks play, silently fail
								// User can manually click play button
							});
						}
					}
				};

				// Use PreloadManager.onHidden() to ensure video only starts after preloader is completely hidden
				if (typeof PreloadManager !== 'undefined') {
					PreloadManager.onHidden(startVideo);
				} else {
					startVideo();
				}
				// Hide the course nav loader when the video has buffered its first frame
				var vid2 = document.getElementById("vidArea");
				if (vid2) {
					var vidFired = false;
					function onVidReady() {
						if (vidFired) return;
						vidFired = true;
						vid2.removeEventListener('loadeddata', onVidReady);
						vid2.removeEventListener('error', onVidReady);
						if (typeof hideNavLoader === 'function') hideNavLoader();
					}
					vid2.addEventListener('loadeddata', onVidReady);
					vid2.addEventListener('error', onVidReady);
					if (vid2.readyState >= 2) onVidReady();
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