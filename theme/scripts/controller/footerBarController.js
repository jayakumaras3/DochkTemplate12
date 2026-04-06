/*jslint browser: true*/
var aristoFramework = window.aristoFramework || {};
var pauseTimer;
(function () {
    /**
     * @ngdoc controller
     * @name aristoFramework.controller:footerBarController
     * @Description
     * footerBarController is to control the prev,next,play,pause,mute,unmute and resource button functionalities
     * @param {Object} scope - scope injector of angularjs to update the model
     * @param {Object} $http - http provider of angularjs
     * @param {Object} $rootScope - parent of all the scope used here for  brodcast the event
     * @param {Object} globalSettingService - A service which we used for storing all the setting globally
     * @param {Object} globalVariableService - A service which we store all global variables
     * @param {Object} radialIndicatorInstance - A service which is to display the completed percentage
     */
    var footerBarController = function (scope, $rootScope, $http, globalSettingService, globalVariableService, radialIndicatorInstance, $timeout) {
        this.http = $http;
        this.scope = scope;
        this.rootScope = $rootScope;
        this.globalSettingService = globalSettingService;
        this.globalVariableService = globalVariableService;
        this.$timeout = $timeout;

        this.scope.$on('initalizeController', assetLoader.proxy(this.globalSettingJson, this));
        this.scope.$on('showFooter', assetLoader.proxy(this.showFooterToggle, this, true));
        this.scope.$on('hideFooter', assetLoader.proxy(this.showFooterToggle, this, false));
        this.scope.$on('changeFooterNavigation', assetLoader.proxy(this.changeFooterNavigation, this));
        this.scope.$on('navigationPage', assetLoader.proxy(this.navigationPage, this));
        
        // Handle updateSteps event from contentController broadcast.
        var self = this;
        this.scope.$on('updateSteps', function(event, steps) {
            self.updateStepsButtonVisibility(steps);
            self.scope.$evalAsync();
        });
        
        this.showFooter = true;
        this.scope.indicatorOption = {
            radius: 15,
            percentage: true,
            barColor: "#fa601e"
        };
        this.navCircle = [];
        this.scope.indicatorValue = 0;
        this.paused = false;
        this.showStepsButton = false;
        this.stepsEnabled = false;
        this.currentSteps = [];
    };


    var p = footerBarController.prototype;
    /**
     * @ngdoc method
     * @name navigationPage
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     *To display the navigation circle which we will get from the individula page
     *
     */
    p.navigationPage = function () {
		 
        this.paused = false;
        this.navCircle = this.globalVariableService.navCircle.slice();
        this.updateStepsButtonVisibility(this.globalVariableService.currentPageSteps || []);
        if (this.navCircle.length > 15) {
            for (var i = this.navCircle.length; i > 15; i--) {
                this.navCircle.splice(i, 1);
            }
        }
        angular.element(document.querySelectorAll(".navCircle")).removeClass("navCircleHighlight");

    };

    p.renderStepsButton = function (steps) {
        var self = this;
        console.log('[Steps] renderStepsButton called with steps:', steps);
        
        // First attempt at 80ms
        this._injectStepsButton(steps, 80);
        
        // Retry at 500ms to catch Angular re-renders
        setTimeout(function() {
            self._injectStepsButton(steps, 500);
        }, 500);
    };

    /**
     * Internal method to inject and configure the Steps button
     */
    p._injectStepsButton = function (steps, delay) {
        var self = this;
        var hasSteps = !!(steps && steps.length > 0);
        var container = document.querySelector('.rightNavPanel .footer');
        
        console.log('[Steps@' + delay + 'ms] Container found:', !!container);
        
        if (!container) {
            console.warn('[Steps@' + delay + 'ms] Container not found: .rightNavPanel .footer');
            return;
        }

        var existingBtn = document.getElementById('stepsBtn');
        
        console.log('[Steps@' + delay + 'ms] hasSteps:', hasSteps, 'existingBtn:', !!existingBtn);

        if (!hasSteps) {
            console.log('[Steps@' + delay + 'ms] No steps, removing button if exists');
            if (existingBtn && existingBtn.parentNode) {
                existingBtn.parentNode.removeChild(existingBtn);
                console.log('[Steps@' + delay + 'ms] Button removed');
            }
            self.stepsEnabled = false;
            return;
        }

        if (!existingBtn) {
            console.log('[Steps@' + delay + 'ms] Creating new button');
            existingBtn = document.createElement('div');
            existingBtn.id = 'stepsBtn';
            existingBtn.className = 'steps-control-btn';
            existingBtn.setAttribute('role', 'button');
            existingBtn.setAttribute('tabindex', '0');
            existingBtn.setAttribute('title', 'Steps');
            existingBtn.setAttribute('aria-label', 'Toggle Steps Panel');
            
            // AGGRESSIVE inline styles to force visibility
            existingBtn.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 99999 !important; pointer-events: auto !important; width: 46px !important; height: 46px !important; overflow: visible !important;';
            
            existingBtn.innerHTML = '<img src="theme/images/footer-menu/steps%20icon.png" alt="Steps" class="steps-control-icon" style="width: 22px; height: 22px; display: block;" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'inline\';" /><span class="steps-control-fallback" style="display:none;font-size:22px;line-height:1;">📄</span>';

            existingBtn.addEventListener('click', function (event) {
                console.log('[Steps Button] Click detected');
                event.preventDefault();
                event.stopPropagation();
                self.toggleStepsPanel();
            });

            existingBtn.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    self.toggleStepsPanel();
                }
            });

            // Append as last child to avoid clipping by siblings
            container.appendChild(existingBtn);
            console.log('[Steps@' + delay + 'ms] Button created and appended to footer');
        } else {
            console.log('[Steps@' + delay + 'ms] Reusing existing button');
            // Force visibility on existing button
            existingBtn.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 99999 !important; pointer-events: auto !important; width: 46px !important; height: 46px !important; overflow: visible !important;';
        }

        // Keep Steps at the end of the control stack: menu -> prev -> next -> learning -> page number -> steps
        container.appendChild(existingBtn);

        // Debug: Log bounding rect to detect clipping/positioning issues
        var rect = existingBtn.getBoundingClientRect();
        console.log('[Steps@' + delay + 'ms] Button rect - width:', rect.width, 'height:', rect.height, 'top:', rect.top, 'left:', rect.left);
        
        if (rect.width === 0 || rect.height === 0) {
            console.warn('[Steps@' + delay + 'ms] WARNING: Button has zero dimensions - CSS issue detected');
        }
        
        if (rect.top < 0 || rect.left < 0) {
            console.warn('[Steps@' + delay + 'ms] WARNING: Button positioned off-screen - layout issue detected');
        }
        
        self.stepsEnabled = true;
        console.log('[Steps@' + delay + 'ms] Button injection complete');
    };
    /**
     * @ngdoc method
     * @name stopFlashAndCaptivative
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * To stop the flash and captivative files
     */
    p.stopFlashAndCaptivative = function () {
        if (this.paused === true) {
            this.paused = false;

            if (stage) {
                createjs.Ticker.addEventListener("tick", stage);
            }

        }
        soundClass.stop();
        this.pauseCaptivateFiles();
    };
    /**
     * @ngdoc method
     * @name pauseCaptivateFiles
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * To pause/stop the captivative files
	 * To pause/stop Added for video also.
     */
    p.pauseCaptivateFiles = function () {
    var captivateFrame = document.getElementById("captivateFrame");
    if (captivateFrame && captivateFrame.contentWindow) {
        var script1 = captivateFrame.contentWindow.Script1;
        if (typeof script1 === "function") {
            var a = script1();
            var c = "";
            if (a !== undefined) {
                a.movie.pause(a.ReasonForPause.PLAYBAR_ACTION);
                c = "pauseAnimation";
                if (a.useg && a.showGesturesAnim) {
                    a.showGesturesAnim(c);
                }
            }
        } else {
            //console.error("Script1 is not a function");
        }
    }

    var vid = document.getElementById("vidArea");
    if (vid) {
        vid.pause();
        //console.log("Video Paused", this.globalVariableService.getPageCounter());
    }
};
    /**
     * @ngdoc method
     * @name navCaptivativeFiles
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * on clicking of naviation button circle if we click on the simulation circle we will check the whether it is
     * captivative and play the simulation files
     */
    p.navCaptivativeFiles = function (values) {
        currentCaptivativeFrames = (values) ? (values - 1) : 0;
        if (stage) {
            stage.removeAllChildren();
            stage.update();
            createjs.Ticker.removeEventListener("tick", stage);
        }
        if (document.getElementById("captivateFrame") == null) {
            if (this.globalVariableService.getPageCounter() == 4) {
                checkNextContent(0)
            } else {
                checkNextContent(1)
            }
        } else
        {
            if (currentCaptivativeFrames !== 0) {
                hidePreloader();
            } else
            {
                if (this.globalVariableService.getPageCounter() == 4) {
                    checkNextContent(0)
                } else {
                    checkNextContent(1)
                }
            }
        }

    };
    /**
     * @ngdoc method
     * @name navBtnClick
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * on click of navigation checking whether it has 8 circle if it is then we are not calculation otherwise we are calculation
     * depending upon the result we are navigating the page. if the index is values is 'S' then it is a captivate
     * simulation page.
     *
     */
    p.onKeyDown = function(event, btn) {
    if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        p.navigationBtnClick(btn);
    }
    };
    p.navBtnClick = function (index) {

        angular.element(document.querySelectorAll(".navCircle")).removeClass("navCircleHighlight");
        angular.element(document.getElementById("play")).removeClass("showContent").addClass("hideContent");
        angular.element(document.getElementById("pause")).removeClass("hideContent").addClass("showContent");
        angular.element(document.getElementById("replay")).removeClass("showContent").addClass("hideContent");
        this.stopFlashAndCaptivative();
        var captivateFrame = document.getElementById("captivateFrame");
        var nextElem = angular.element(document.getElementById("next"));
        nextElem.removeClass("nextClassHighlight");
		next_continue(false);
        var flashArea = document.getElementById("flashArea");

        var frameLabel, splitArray
        if (this.globalVariableService.navCircle.length > 15) {
			
            var count = Math.floor((this.globalVariableService.navCircle.length - 1) * (index / 13));
            frameLabel = this.globalVariableService.navCircle[count].toString();
            splitArray = frameLabel.split(",");
            if (splitArray[0] != "s") {
                if (flashArea == null) {
                    this.rootScope.$broadcast("showHeader");
                    this.pauseCaptivateFiles();
                    currentFrame = this.navCircle[count];
                    checkNextContent(0)
                } else {
                    this.pauseCaptivateFiles();
                    stage.getChildAt(0).gotoAndPlay(this.globalVariableService.navCircle[count]);
                }

            } else {
                highlightNavCircle(frameLabel)
                this.navCaptivativeFiles(splitArray[1]);
            }
        } else {
            frameLabel = this.globalVariableService.navCircle[index].toString();
            splitArray = frameLabel.split(",");
            if (splitArray[0] != "s") {
                if (flashArea == null) {
                    this.rootScope.$broadcast("showHeader");
                    this.pauseCaptivateFiles();
                    currentFrame = this.navCircle[index];
                    checkNextContent(0)
                } else {
                    this.pauseCaptivateFiles();
                    stage.getChildAt(0).gotoAndPlay(this.navCircle[index]);
                }
            } else {
                highlightNavCircle(frameLabel)
                this.navCaptivativeFiles(splitArray[1]);
            }
        }

    };
    /**
     * @ngdoc method
     * @name changeFooterNavigation
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * To update the completion page and remove the next button highlight
     *
     */
    p.changeFooterNavigation = function () {
	//  console.log("progress****",completed);
       var totalToc1 = completed;
	   var TotalPageNo=Totalpage-PercentageskipPage;
        this.scope.indicatorValue = Math.floor(((totalToc1) / TotalPageNo) * 100);
		if( this.scope.indicatorValue ==100)
		{
			 // complete();
		}
        var self = this;
        setTimeout(function () {
            self.scope.$apply()
			
        }, 10);
		if(this.globalVariableService.toclevel==false){
       // this.modulenumber = this.globalVariableService.pagesmodcount[this.globalVariableService.getPageCounter() - 1][0];
        $(".toggle-box").removeProp('checked');
       // $("#header" + this.modulenumber).prop("checked", true);
		}
        // $("#header" + this.modulenumber).attr('checked', 'checked');
        if (this.globalVariableService.replaybtnvisible == false) {
            var nextElem = angular.element(document.getElementById("next"));
            nextElem.removeClass("nextClassHighlight");
			next_continue(false);
            var prevElem = angular.element(document.getElementById("prev"));
            prevElem.removeClass("nextClassHighlight");
            var exitElem = angular.element(document.getElementById("exit"));
            exitElem.removeClass("exitHighlight");
            var resourceElem = angular.element(document.getElementById("resource"));
            resourceElem.removeClass("resourceHighlight");
            if (this.globalVariableService.getPageCounter() > 1) {
                prevElem.removeClass("disabledClass");
            }			
			
            if (this.globalVariableService.nextBtnDisabled == true) {
                if (!this.globalVariableService.checkCompletedPage(this.globalVariableService.getPageCounter())) {
                    nextElem.addClass("disabledClass");
                } else {
					if (this.globalVariableService.getPageCounter() == 1) {
					}
					else{
						
                    nextElem.removeClass("disabledClass");
					}
                }
            }
			
            // var this.globalVariableService.getTocData();
            angular.element(document.getElementById("play")).removeClass("showContent").addClass("hideContent");
            angular.element(document.getElementById("pause")).removeClass("hideContent").addClass("showContent");
            angular.element(document.getElementById("replay")).removeClass("showContent").addClass("hideContent");
        }
    };
    /**
     * @ngdoc method
     * @name showFooterToggle
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * To display the footer bar depends on the indivdual page configuration
     *
     */
    p.showFooterToggle = function (e, toggleValue) {
        this.showFooter = toggleValue;
    };
    /**
     * @ngdoc method
     * @name globalSettingJson
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * The function will called when Maincontroller  will broadcast the globalSettingJson message
     * when global setting is loaded
     *
     */
    p.globalSettingJson = function () {
        var response = this.globalSettingService.getGlobalSettings();
        this.navigationBtn = [];
        this.getOrderArray = response.footer.navigation.order;
        this.globalVariableService.nextBtnDisabled = (response.nextBtnDisabled == undefined) ? false : response.nextBtnDisabled
        this.navigationJSON = response.footer.navigation;
        if (this.getOrderArray) {
            this.getOrderArray.forEach(function (element) {
                this.navigationBtn.push(this.navigationJSON[element]);
            }, this);
        } else {
            this.navigationBtn = response.footer.navigation;
        }
    };
    /**
     * @ngdoc method
     * @name navigationBtnClick
     * @methodOf aristoFramework.controller:footerBarController
     * @param {string} currentNav  id of the footer bar
     * @description
     * Event callback for the each button in the footer bar
     *
     */
	 p.NavigationKeyBoardfun = function(btn) {
				NavigationKeyBoardfun(btn.id);
};

    p.navigationBtnClick = function (currentNav) {
        var id = currentNav.id;
        var self = this;
        switch (id) {
            case "prev":

              
               if (!angular.element(document.getElementById("prev")).hasClass("disabledClass")) {
				   getCurrentTrackName();
				   collapseReset();
                    this.globalVariableService.replaybtnvisible = false;
                    this.prevBtnClick();
					menuEnDisble_fun_PREV();
                }
                break;



            case "next":
                if (!angular.element(document.getElementById("next")).hasClass("disabledClass")) {
					var temp = this.globalVariableService.pageCounter;
					 getCurrentTrackName();
					console.log("pageArray::"+pageArray);
					if(masterBool)
					{
									collapseReset();
									this.globalVariableService.replaybtnvisible = false;
									this.nextBtnClick();
					}
					else{
								//if (pageArray[temp-1]=="1")
								{
								//	console.log("temp"+temp);
									collapseReset();
									this.globalVariableService.replaybtnvisible = false;
									this.nextBtnClick();
								}
							}
				}
                break;
            case "replay":
                if (angular.element(document.getElementById("replay")).hasClass("showContent")) {
                    angular.element(document.getElementById("replay")).removeClass("showContent").addClass("hideContent");
                    angular.element(document.getElementById("pause")).removeClass("hideContent").addClass("showContent");
                }

                this.commonForNaviagation();
                // Show preloader for replay action
                if (typeof PreloadManager !== 'undefined') {
                    PreloadManager.show();
                }
                this.rootScope.$broadcast("getTocData");
                this.changeFooterNavigation();
                break;
            case "mute":
					
				/*pause_str="pause";
               angular.element(document.getElementById("resourceArea")).removeClass("hideContent").addClass("showContent");
                if (!angular.element(document.getElementById("play")).hasClass("showContent")) {
                    this.pauseBtnClick();
                    pauseAlreadyClicked = false;
                } else {
                    pauseAlreadyClicked = true;
                }*/
				pdfLoader();
				 //window.open("assets/content/PDF/Importance_of_Data_Transcript.pdf", "_blank");

                break;
            case "unmute":
                angular.element(document.getElementById("mute")).removeClass("hideContent").addClass("showContent");
                angular.element(document.getElementById("unmute")).removeClass("showContent").addClass("hideContent");
                soundClass.unMute();
                if (document.getElementById("captivateFrame")) {
                    document.getElementById("captivateFrame").contentWindow.audioMute();
                }
				var vid = document.getElementById("vidArea");
				if (document.getElementById("vidArea")) {
					vid.muted = false;
					//console.log("Video unMuted");
				}
				
                break;
            case "play":
			  if (!angular.element(document.getElementById("play")).hasClass("disabledClass")) {
                if (pauseTimer == undefined) {
                    this.playBtnClick();
                    pauseTimer = setTimeout(function () {
                        pauseTimer = undefined
                    }, 250);
                }
			  }
			

                break;
            case "pause":
			if (!angular.element(document.getElementById("pause")).hasClass("disabledClass")) {
                if (pauseTimer == undefined) {
                    this.pauseBtnClick();
                    pauseTimer = setTimeout(function () {
                        pauseTimer = undefined
                    }, 250);
                }
                }
				

                break;
            case "exit1":
              

				//window.close();
                break;
            case "exit": 
			
			angular.element(document.getElementById("exitContainer")).removeClass("hideContent").addClass("showContent")
			pause_str="pause";
			 if (document.getElementById("captivateFrame")) {
            var c = "";
            var a = document.getElementById("captivateFrame").contentWindow.Script1();
            if (a !== undefined) {
                a.movie.play(a.ReasonForPause.PLAYBAR_ACTION);
                c = "playAnimation";
                a.useg && a.showGesturesAnim && a.showGesturesAnim(c)
            }
        }
			var vid = document.getElementById("vidArea");
				if (document.getElementById("vidArea")) {
					vid.pause();
				}
             ;
			  //angular.element(document.getElementById("exitwithoutlogContainer")).removeClass("hideContent").addClass("showContent");
               

				//window.close();
                break;
			case "cc":
			if (!angular.element(document.getElementById("cc")).hasClass("showContent")) {
					$("#audscript").toggle();
			}
					break;	
			case "search":
					this.pauseBtnClick();
					angular.element(document.getElementById("searchContainer")).removeClass("hideContent").addClass("showContent");
					$("#searchtext").val('');
					$("#searchArea").css('height','167px');
					$("#searchcountresult").html('');
					$("#searchresult").html('');
					break;
            case "resource1":
				pause_str="pause";
				//angular.element(document.getElementById("resourceArea1")).removeClass("hideContent").addClass("showContent");
				document.getElementById("resourceArea").style.display = "block";
                if (!angular.element(document.getElementById("play")).hasClass("showContent")) {
                    this.pauseBtnClick();
                    pauseAlreadyClicked = false;
                } else {
                    pauseAlreadyClicked = true;
                }
                break

        }
    };
    /**
     * @ngdoc method
     * @name playBtnClick
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * To resume the scene
     *
     */
    p.playBtnClick = function () {
        soundClass.resume();
        this.paused = false;
		angular.element(document.getElementById("play")).addClass("hideContent").addClass("showContent");																						   
        if (!angular.element(document.getElementById("replay")).hasClass("showContent")) {
            angular.element(document.getElementById("play")).removeClass("showContent").addClass("hideContent");
            angular.element(document.getElementById("pause")).removeClass("hideContent").addClass("showContent");
        }
        if (stage) {
            createjs.Ticker.addEventListener("tick", stage);
        }
        if (document.getElementById("captivateFrame")) {
            var c = "";
            var a = document.getElementById("captivateFrame").contentWindow.Script1();
            if (a !== undefined) {
                a.movie.play(a.ReasonForPause.PLAYBAR_ACTION);
                c = "playAnimation";
                a.useg && a.showGesturesAnim && a.showGesturesAnim(c)
            }
        }
		
		var vid = document.getElementById("vidArea");
		if (document.getElementById("vidArea")) {
			vid.play();
		//	console.log("Video Play");
		}	

    };
    /**
     * @ngdoc method
     * @name pauseBtnClick
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * To pause the  scene
     *
     */
    p.pauseBtnClick = function () {
        soundClass.pause();
		enableplapause();		   
        this.paused = true;
       /* if (!angular.element(document.getElementById("replay")).hasClass("showContent")) {
            angular.element(document.getElementById("pause")).removeClass("showContent").addClass("hideContent");
            angular.element(document.getElementById("play")).removeClass("hideContent").addClass("showContent");
        }
        if (stage) {
            createjs.Ticker.removeAllEventListeners("tick");
        }*/

        this.pauseCaptivateFiles();

    };
    /**
     * @ngdoc method
     * @name nextBtnClick
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * The function will called when ContentController will broadcast the tocSelectedChange message
     * common code for the next page
     *
     */
    p.nextBtnClick = function () {
        // Show preloader immediately when navigating
        if (typeof PreloadManager !== 'undefined') {
            PreloadManager.show();
        }
        var pageCounter = this.globalVariableService.getPageCounter();
        var prevElem = angular.element(document.getElementById("prev"));
        prevElem.removeClass("disabledClass");
        this.commonForNaviagation();
        this.globalVariableService.addCompletePage(pageCounter + 1);
        this.globalVariableService.setPageCounter(pageCounter + 1);
        this.globalVariableService.setContentCounter(0);
        
        this.rootScope.$broadcast("getTocData");
        this.rootScope.$broadcast("tocSelectedChange");
        
        this.changeFooterNavigation();
    };
    p.prevBtnClick = function () {
		//audio Version page added
		if (this.globalVariableService.getPageCounter() === 3 && AudioVersionEnable) {
			gotoCertainPage(1);
		}
		else{
        // Show preloader immediately when navigating
        if (typeof PreloadManager !== 'undefined') {
            PreloadManager.show();
        }
			
        if (angular.element(document.getElementById("replay")).hasClass("showContent")) {
            angular.element(document.getElementById("replay")).removeClass("showContent").addClass("hideContent");
            angular.element(document.getElementById("pause")).removeClass("hideContent").addClass("showContent");
        }

        if (angular.element(document.getElementById("play")).hasClass("showContent")) {
            angular.element(document.getElementById("play")).removeClass("showContent").addClass("hideContent");
            angular.element(document.getElementById("pause")).removeClass("hideContent").addClass("showContent");
        }


        var pageCounter = this.globalVariableService.getPageCounter();
        this.commonForNaviagation();
        this.globalVariableService.addCompletePage(pageCounter - 1);
        this.globalVariableService.setPageCounter(pageCounter - 1);
        this.globalVariableService.setContentCounter(0);
        
        this.rootScope.$broadcast("getTocData");
        this.rootScope.$broadcast("tocSelectedChange");
        
        this.changeFooterNavigation();
		}
    };
    /**
     * @ngdoc method
     * @name gotoCertainPage
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * To move in to certain page.
     *
     */
    p.gotoCertainPage = function (gotoPage) {
        var pageCounter = this.globalVariableService.getPageCounter();
        this.globalVariableService.replaybtnvisible = false;
        this.commonForNaviagation();
        this.globalVariableService.addCompletePage(pageCounter);
        this.globalVariableService.setPageCounter(gotoPage);
        this.globalVariableService.setContentCounter(0);
        this.rootScope.$broadcast("getTocData");
        this.rootScope.$broadcast("tocSelectedChange");
        this.changeFooterNavigation();
    };
    /**
     * @ngdoc method
     * @name commonForNaviagation
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * common code for navigation
     *
     */
    p.commonForNaviagation = function () {
        var nextElem = angular.element(document.getElementById("next"));
        nextElem.removeClass("nextClassHighlight");
		next_continue(false);
        this.paused = false;
        if (stage) {
            stage.removeAllChildren();
            stage.update();
            createjs.Ticker.removeEventListener("tick", stage);
        }
        soundClass.stop();
        this.pauseCaptivateFiles();
    };

    /**
     * @ngdoc method
     * @name toggleStepsPanel
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * Toggle standalone Steps panel
     */
    p.toggleStepsPanel = function () {
        console.log('[FooterBar] toggleStepsPanel called, showStepsButton:', this.showStepsButton);
        
        if (!this.showStepsButton || typeof StepsToggleButton === 'undefined') {
            console.warn('[FooterBar] toggleStepsPanel skipped: showStepsButton:', this.showStepsButton, 'StepsToggleButton defined:', typeof StepsToggleButton !== 'undefined');
            return;
        }

        try {
            // Ensure panel exists before toggling.
            StepsToggleButton.ensureStepPanelExists();

            if (StepsToggleButton.isEnabled) {
                StepsToggleButton.disablePanel();
                this.stepsEnabled = false;
            } else {
                StepsToggleButton.enablePanel();
                this.stepsEnabled = true;
            }

            console.log('[FooterBar] Standalone Steps panel toggled. Enabled:', this.stepsEnabled);
        } catch (err) {
            console.error('[FooterBar] Error toggling steps:', err);
        }
    };

    /**
     * @ngdoc method
     * @name updateStepsButtonVisibility
     * @methodOf aristoFramework.controller:footerBarController
     * @description
     * Update the visibility of the steps button based on whether steps exist for current page
     * @param {Array} steps - Array of steps for the current page
     */
    p.updateStepsButtonVisibility = function (steps) {
        var effectiveSteps = steps;
        if (!effectiveSteps || !effectiveSteps.length) {
            effectiveSteps = this.globalVariableService.currentPageSteps || [];
        }
        this.currentSteps = effectiveSteps || [];
        this.showStepsButton = !!(effectiveSteps && effectiveSteps.length > 0);
        this.stepsEnabled = false;
        this.renderStepsButton(this.currentSteps);
        this.scope.$evalAsync();
    };




	
	
	
    footerBarController.$inject = ['$scope', '$rootScope', '$http', 'globalSettingService', 'globalVariableService', 'radialIndicatorInstance', '$timeout'];
    aristoFramework.footerBarController = footerBarController;
}());