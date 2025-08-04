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
    var footerBarController = function (scope, $rootScope, $http, globalSettingService, globalVariableService, radialIndicatorInstance) {
        this.http = $http;
        this.scope = scope;
        this.rootScope = $rootScope;
        this.globalSettingService = globalSettingService;
        this.globalVariableService = globalVariableService;

        this.scope.$on('initalizeController', assetLoader.proxy(this.globalSettingJson, this));
        this.scope.$on('showFooter', assetLoader.proxy(this.showFooterToggle, this, true));
        this.scope.$on('hideFooter', assetLoader.proxy(this.showFooterToggle, this, false));
        this.scope.$on('changeFooterNavigation', assetLoader.proxy(this.changeFooterNavigation, this));
        this.scope.$on('navigationPage', assetLoader.proxy(this.navigationPage, this));
        this.showFooter = true;
        this.scope.indicatorOption = {
            radius: 15,
            percentage: true,
            barColor: "#fa601e"
        };
        this.navCircle = [];
        this.scope.indicatorValue = 0;
        this.paused = false;
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
        if (this.navCircle.length > 15) {
            for (var i = this.navCircle.length; i > 15; i--) {
                this.navCircle.splice(i, 1);
            }
        }
        angular.element(document.querySelectorAll(".navCircle")).removeClass("navCircleHighlight");

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
                prevElem.attr("title", Prevtitle);
            }			
			
            if (this.globalVariableService.nextBtnDisabled == true) {
                if (!this.globalVariableService.checkCompletedPage(this.globalVariableService.getPageCounter())) {
                    nextElem.addClass("disabledClass");
                    nextElem.removeAttr("title")
                } else {
					if (this.globalVariableService.getPageCounter() == 1) {
					}
					else{
						
                    nextElem.removeClass("disabledClass");
                    nextElem.attr("title", NextTitle)
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
        $("#prev").attr('title', 'Previous');
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
					changeTrackSrc();
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
									changeTrackSrc();
					}
					else{
								//if (pageArray[temp-1]=="1")
								{
								//	console.log("temp"+temp);
									collapseReset();
									this.globalVariableService.replaybtnvisible = false;
									this.nextBtnClick();
									changeTrackSrc();
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
        var pageCounter = this.globalVariableService.getPageCounter();
        var prevElem = angular.element(document.getElementById("prev"));
        prevElem.removeClass("disabledClass");
        prevElem.attr("title", Prevtitle);
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




	
	
	
    footerBarController.$inject = ['$scope', '$rootScope', '$http', 'globalSettingService', 'globalVariableService', 'radialIndicatorInstance'];
    aristoFramework.footerBarController = footerBarController;
}());