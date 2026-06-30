(function() {
	/**
	 * @ngdoc controller
	 * @name aristoFramework.controller:sideBarController
	 * @Description
	 * SideBarController is to controll the TOC and transcript data
	 * @param {Object} scope - scope injector of angularjs to update the model
	 * @param {Object} http - http provider of angularjs
	 * @param {Object} $rootScope - parent of all the scope used here for  brodcast the event
	 * @param {Object} globalSettingService - A service which we used for storing all the setting globally
	 * @param {Object} globalVariableService - A service which we store all global variables
	 * @param {Object} $sce - A service for html data binding
	 */

	var sideBarController = function(scope, $rootScope, http, globalSettingService, globalVariableService, $sce, $location) {
		this.scope = scope;
		this.$rootScope = $rootScope;
		this.http = http;
		this.globalVariableService = globalVariableService;
		this.globalVariableService.resetAllValue();
		this.globalSettingService = globalSettingService;
		this.$sce = $sce;


		this.$location = $location;
		if (!userName) {
			$location.path("")
		}
		/**
		 * To display the sidebar depends on the individual page json
		 *
		 * @property showSideBar
		 * @type Boolean
		 * @default true
		 */
		this.showSideBar = true;
		/**
		 * To Store the global settings and manipulating
		 *
		 * @property globalSettings
		 * @type object
		 * @default {}
		 */
		this.globalSettings = {};
		/**
		 * To store the which language to display depends on the global variable
		 *
		 * @property language
		 * @type string
		 * @default ''
		 */
		this.language = '';
		/**
		 * A object to store the TOC json
		 *
		 * @property tocData
		 * @type object
		 * @default undefined
		 */
		this.tocData = {};
		/**
		 * A DOM string to display the TOC
		 *
		 * @property tocContent
		 * @type string
		 * @default ''
		 */
		this.tocContent = '';
		/**
		 * To check whether transcript is clicked or not
		 *
		 * @property transcriptClicked
		 * @type boolean
		 * @default false
		 */
		this.transcriptClicked = false;
		this.glossaryClicked = false;
		this.tocTypeClassMap = {};
		this.scope.$on('initalizeController', assetLoader.proxy(this.globalSettingJson, this));
		this.scope.$on('showSideBar', assetLoader.proxy(this.showSideBarToggle, this, true));
		this.scope.$on('hideSideBar', assetLoader.proxy(this.showSideBarToggle, this, false));
		this.scope.$on('tocSelectedChange', assetLoader.proxy(this.broadCastChangeTocColorChange, this));
	};


	var p = sideBarController.prototype;
	p.navigateToLoginPage = function() {
		this.$location.path("")
		this.scope.$apply();
	}
	p.certficateClicked = function() {
		this.$location.path("certificate")
		this.scope.$apply();
	};
	/**
	 * @ngdoc method
	 * @name broadCastChangeTocColorChange
	 * @methodOf aristoFramework.controller:sideBarController
	 * @description
	 * The function will called when ContentController will broadcast the tocSelectedChange message
	 * when user click next or previous to update the TOC
	 *
	 */
	p.broadCastChangeTocColorChange = function() {
		this.changeTocColorChange(this.globalVariableService.getPageCounter() - 1)
	};
	/**
	 * @ngdoc method
	 * @name showSideBarToggle
	 * @methodOf aristoFramework.controller:sideBarController
	 * @param {Event} e - angularjs broadcast event object
	 * @param {boolean} toggleValue - Boolean value to display the sidebar or not
	 * @description
	 * The function will called when ContentController will broadcast the showSideBar message
	 * when the individual page json to needs sidebar or not
	 *
	 */
	p.showSideBarToggle = function(e, toggleValue) {
		this.showSideBar = toggleValue;
		if (!toggleValue && typeof menuClose === 'function' && window.Tmenu) {
			menuClose();
			p.menuState = 'closed';
		}

	};
	/**
	 * @ngdoc method
	 * @name globalSettingJson
	 * @methodOf aristoFramework.controller:sideBarController
	 * @description
	 * The function will called when MainController will broadcast the intailizecontroller message
	 * when the global setting is loaded
	 *
	 */
	p.globalSettingJson = function() {
		this.globalSettings = this.globalSettingService.getGlobalSettings();
		this.language = this.globalSettingService.getLanguage();
		this.http({
			method: 'GET',
			url: 'assets/json/toc.json'
			//url: 'assets/content/' + this.language + '/toc.json'
		}).then(assetLoader.proxy(this.setTocConfig, this), function errorCallback(response) {
			console.log('error', response);
		});


	};
	/**
	 * @ngdoc method
	 * @name setTocConfig
	 * @methodOf aristoFramework.controller:sideBarController
	 * @param {Object} response - JSON Object
	 * @description
	 * Callback function when the TOC.json loaded
	 */
	p.setTocConfig = function(response) {
		this.tocTypeClassMap = {};
		if (response.data.toc == undefined) {
			this.tocData = response.data;
			var countpage = 0;
			this.globalVariableService.pagesmodcount = [];
			this.tocContent += "";
			for (var y = 0; y < Object.keys(response.data).length; y++) {
				modulelength = Object.keys(response.data).length;
				this.looptocData = response.data[y];
				this.globalVariableService.pagescount[y] = [y, this.looptocData.length];
				this.tocContent += ""
				this.tocContent += '<ol id="tocData">';
				for (var i = 0; i < this.looptocData.length; i++) {
					countpage++;
					Totalpage = countpage;
					this.globalVariableService.pagesmodcount.push([y, (i + 1)]);
					var data = this.looptocData[i].title;
					var disabledClass = this.looptocData[i].disabled;
					this.cacheTopicTypeClass(Totalpage, this.looptocData[i]);
					if (data != undefined) {
						var st = '' + "Mitem" + Totalpage.toString() + '"';
						var st1 = '="' + "LSitem" + Totalpage.toString() + '"';
						var st2 = '' + "Sitem" + Totalpage.toString() + '';
						
						if (AudioVersionEnable) {
							if (i == 1) {
								this.tocContent +=  '<span style="display: flex;">' +    '<li style="display: none;" class="disabledClass" id' + st + '>' +      '<span ' +        'id' + st1 + ' ' +        'role="button" ' +        'tabindex="0" ' +        'aria-label="' + data + '" ' +        'aria-disabled="true" ' +        'onclick="tocDataClick(this)" ' +        'onkeydown="tocKeyHandler(event, this)">' +        data +      '</span>' +    '</li>' +  '</span>';

							} else {
								if (masterBool) {
									this.tocContent +=
									  '<span style="display: flex;">' +
										'<li id="' + st + '" style="list-style: none;">' +
										  '<span ' +
											'id=' + st1 + ' ' +
											'role="button" ' +
											'tabindex="0" ' +
											'aria-label="' + data + '" ' +
											'onclick="tocDataClick(this)" ' +
											'onkeydown="tocKeyHandler(event, this)" ' +
											'style="cursor: pointer;">' +
											data +
										  '</span>' +
										'</li>' +
										'<p style="visibility: hidden;" class="tickSymbol" id=' + st2 + '></p>' +
									  '</span>';

								} else {
									if (disabledClass == "yes") {										
								this.tocContent += '<span style="display: flex;">' +
								  '<li id="' + st + '" style="list-style: none;">' +
									'<span id=' + st1 +
									  ' role="button"' +
									  ' tabindex="0"' +
									  ' aria-label="' + data + '"' +
									  ' onclick="tocDataClick(this)"' +
									  ' onkeydown="tocKeyHandler(event, this)"' +
									  ' style="cursor: pointer;">' +
									  data +
									'</span>' +
								  '</li>' +
								  '<p style="visibility: hidden;" class="tickSymbol" id=' + st2 + '></p>' +
								'</span>';
	

									} else {	
									this.tocContent += '<span style="display: flex;">' +
									  '<li class="disabledClass" id="' + st + '" style="list-style: none;">' +
										'<span id=' + st1 +
										  ' role="button"' +
										  ' tabindex="0"' +
										  ' aria-label="' + data + '"' +
										  ' onclick="tocDataClick(this)"' +
										  ' onkeydown="tocKeyHandler(event, this)"' +
										  ' style="cursor: pointer;	">' +
										  data +
										'</span>' +
									  '</li>' +
									  '<p style="visibility: hidden;" class="tickSymbol" id=' + st2 + '></p>' +
									'</span>';

									}

								}
							}
						} else {
							// free navigation
							if (masterBool) {
							 this.tocContent += '<span style="display: flex;">' +
							  '<li id="' + st + '" style="list-style: none;">' +
								'<span id=' + st1 +
								  ' role="button"' +
								  ' tabindex="0"' +
								  ' aria-label="' + data + '"' +
								  ' onclick="tocDataClick(this)"' +
								  ' onkeydown="tocKeyHandler(event, this)"' +
								  ' style="cursor: pointer;">' +
								  data +
								'</span>' +
							  '</li>' +
							  '<p style="visibility: hidden;" class="tickSymbol" id=' + st2 + '></p>' +
							'</span>';

							} else {
								if (disabledClass == "yes") {
								this.tocContent += '<span style="display: flex;">' +
								  '<li id="' + st + '" style="list-style: none;">' +
									'<span id=' + st1 +
									  ' role="button"' +
									  ' tabindex="0"' +
									  ' aria-label="' + data + '"' +
									  ' onclick="tocDataClick(this)"' +
									  ' onkeydown="tocKeyHandler(event, this)"' +
									  ' style="cursor: pointer;">' +
									  data +
									'</span>' +
								  '</li>' +
								  '<p style="visibility: hidden;" class="tickSymbol" id="' + st2 + '"></p>' +
								'</span>';


								} else {
									
									this.tocContent += '<span style="display: flex;">' +
									  '<li class="disabledClass" id="' + st + '" style="list-style: none;">' +
										'<span id=' + st1 +
										  ' role="button"' +
										  ' tabindex="0"' +  // Optional: if you want it keyboard focusable
										  ' aria-label="' + data + '"' +
										  ' onclick="tocDataClick(this)"' +
										  ' onkeydown="tocKeyHandler(event, this)"' +
										  ' style="cursor: pointer;">' +
										  data +
										'</span>' +
									  '</li>' +
									  '<p style="visibility: hidden;" class="tickSymbol" id="' + st2 + '"></p>' +
									'</span>';
									
									

								}

							}

						}


					}

				}
				this.tocContent += '</ol>';
				this.sideBarData = this.$sce.trustAsHtml(this.tocContent);
			}
		} else {
			this.tocData = response.data.toc;
			this.globalVariableService.toclevel = true;
			document.getElementsByClassName("tocData")[0].style.overflow = "hidden"
			this.tocContent += ""
			this.tocContent += '<ol id="tocData" style="margin-left:18px;">';
			for (var i = 1; i < this.tocData.length; i++) {
				var data = this.tocData[i].title;
				var disabledClass = this.tocData[i].disabled;
				this.cacheTopicTypeClass(i, this.tocData[i]);
				if (data != undefined) {
					if (AudioVersionEnable) {
						//Audio version added
						if (i == 1) {

						} else {

							if (disabledClass == "yes") {

									//  this.tocContent += '<li class="disabledClass" ><span role="button" onclick="tocDataClick(this)"  > ' + data + '</span> </li>';

								/*	this.tocContent += '<li id' + st + '><span id' + st1 + 'role="button" onclick="tocDataClick(this)" > ' + data + '</span> </li>';*/
								this.tocContent += '<span style="display: flex;">' +
									'<li id="' + st + '" style="list-style: none;">' +
										'<span  id="' + st1 + '" role="button" onclick="tocDataClick(this)">' + data + '</span>' +
									'</li>' +
									'<p style="visibility :hidden;" class="tickSymbol" id=' + st2 + '></p>' +
								'</span>';

								} else {
									/*this.tocContent += '<li class="disabledClass" id' + st + ' ><span id' + st1 + ' role="button" onclick="tocDataClick(this)"  > ' + data + '</span> </li>';*/
									//  this.tocContent += '<li ><span role="button" onclick="tocDataClick(this)" > ' + data + '</span> </li>';
									this.tocContent += '<span style="display: flex;">' +
									'<li class="disabledClass" id="' + st + '" style="list-style: none;">' +
										'<span  id="' + st1 + '" role="button" onclick="tocDataClick(this)">' + data + '</span>' +
									'</li>' +
									'<p style="visibility :hidden;" class="tickSymbol" id=' + st2 + '></p>' +
								'</span>';
								}

						}

					} else {
						//Audio version removed
						/*if(i==1)
						{
							
						}
						else*/
						{

							if (disabledClass == "yes") {

									//  this.tocContent += '<li class="disabledClass" ><span role="button" onclick="tocDataClick(this)"  > ' + data + '</span> </li>';

								/*	this.tocContent += '<li id' + st + '><span id' + st1 + 'role="button" onclick="tocDataClick(this)" > ' + data + '</span> </li>';*/
								this.tocContent += '<span style="display: flex;">' +
									'<li id="' + st + '" style="list-style: none;">' +
										'<span  id="' + st1 + '" role="button" onclick="tocDataClick(this)">' + data + '</span>' +
									'</li>' +
									'<p style="visibility :hidden;" class="tickSymbol" id=' + st2 + '></p>' +
								'</span>';

								} else {
									/*this.tocContent += '<li class="disabledClass" id' + st + ' ><span id' + st1 + ' role="button" onclick="tocDataClick(this)"  > ' + data + '</span> </li>';*/
									//  this.tocContent += '<li ><span role="button" onclick="tocDataClick(this)" > ' + data + '</span> </li>';
									this.tocContent += '<span style="display: flex;">' +
									'<li class="disabledClass" id="' + st + '" style="list-style: none;">' +
										'<span  id="' + st1 + '" role="button" onclick="tocDataClick(this)">' + data + '</span>' +
									'</li>' +
									'<p style="visibility :hidden;" class="tickSymbol" id=' + st2 + '></p>' +
								'</span>';
								}

						}
					}




				}
			}
			this.tocContent += '</ol>';

			this.sideBarData = this.$sce.trustAsHtml(this.tocContent);
		}

		this.globalVariableService.setTocData(this.tocData);
		this.$rootScope.$broadcast("getTocData");
		var self = this;
		setTimeout(function() {
			self.changeTocColorChange(self.globalVariableService.getPageCounter() - 1);
			self.applyTopicTypeClasses();
			//alert($("#header0").attr('checked'));
		}, 100);

		//this.changeTocData();
	};
	/**
	 * @ngdoc method
	 * @name tocClick
	 * @param {String} name - DOM inner html
	 * @methodOf aristoFramework.controller:sideBarController
	 * @description
	 * EventListener function toc and transcript button
	 *
	 */
	 p.menuState = 'closed';
p.handleKeydown = function(event, name) {
  if (event.key === 'Enter' || event.key === ' ') {
    event.preventDefault(); // Prevent spacebar from scrolling the page
	var img = document.getElementById('TmenuIcon');

	if (img) {
	  var pointerEvents = window.getComputedStyle(img).pointerEvents;

	  if (pointerEvents === 'none') {
	    console.log('Pointer events are disabled');
	  } else {
		  this.tocClick(name);
		  document.getElementById("clickableDiv").focus();
	    console.log('Pointer events are enabled');
	  }
	}
  }
};

	p.updateTabSelectionState = function(activeTab) {
		var isTocActive = activeTab === 'toc';
		var tocTab = document.getElementById('toc_id');
		var transTab = document.getElementById('trans_id');

		if (tocTab) {
			tocTab.setAttribute('aria-selected', isTocActive ? 'true' : 'false');
			if (isTocActive) {
				tocTab.setAttribute('aria-current', 'page');
			} else {
				tocTab.removeAttribute('aria-current');
			}
		}

		if (transTab) {
			transTab.setAttribute('aria-selected', isTocActive ? 'false' : 'true');
			if (isTocActive) {
				transTab.removeAttribute('aria-current');
			} else {
				transTab.setAttribute('aria-current', 'page');
			}
		}
	};

	p.tocClick = function(name) {
	//	console.log(name);
		switch (name) {
			case 'toc':
				var switchedFromAlternatePanel = this.transcriptClicked || this.glossaryClicked;
				this.updateTabSelectionState('toc');
				getCurrentTrackName();
				$("#toc_id").addClass('tocclickedclscss');
				$("#trans_id").removeClass('tocclickedclscss');
				$("#glossary_id").removeClass('tocclickedclscss');

				// Avoid re-rendering TOC when Menu is already active; this prevents visible list jerk.
				if (!switchedFromAlternatePanel) {
					this.transcriptClicked = false;
					this.glossaryClicked = false;
					this.globalVariableService.replaybtnvisible = true;
					break;
				}

				this.sideBarData = this.$sce.trustAsHtml(this.tocContent);
				var self = this;
				this.transcriptClicked = false;
				this.glossaryClicked = false;
				this.globalVariableService.replaybtnvisible = true;
				setTimeout(function() {
					self.applyTopicTypeClasses();
					self.changeTocColorChange(self.globalVariableService.getPageCounter() - 1)
					pageSormTrack();

					updatePageCounter();
					if (AudioVersionEnable) {
						var num = self.globalVariableService.getPageCounter();
						var Tempst = 'Mitem' + num;
						//console.log(num);
						var elementToRemoveClass = document.getElementById(Tempst);
						if (elementToRemoveClass.classList.contains('disabledClass')) {
							elementToRemoveClass.classList.remove('disabledClass');
						}
					}
					// page tiltle enable when click transcript and menu toggle end
				}, 0);
				break;
			
			 case 'toggleMenu':
			  TtoggleMenu();
			  p.menuState = !!window.Tmenu ? 'open' : 'closed';
			  break;

			case 'toggleMenuclose':
			  // Close the menu here (you can call your menuClose function)
			  menuClose();

			  // Mark the menu as closed
			  p.menuState = 'closed';
			  break;

			case 'transcript':
				this.updateTabSelectionState('transcript');
			
				$("#toc_id").removeClass('tocclickedclscss');
				$("#glossary_id").removeClass('tocclickedclscss');
				$("#trans_id").addClass('tocclickedclscss');
				document.getElementsByClassName("tocData")[0].style.overflow = "auto"
				this.changeTocData();
				this.transcriptClicked = true;
				this.glossaryClicked = false;
				//alert();
				break;
			case 'glossary':
				this.updateTabSelectionState('toc');
				$("#toc_id").removeClass('tocclickedclscss');
				$("#trans_id").removeClass('tocclickedclscss');
				$("#glossary_id").addClass('tocclickedclscss');
				document.getElementsByClassName("tocData")[0].style.overflow = "auto"
				this.changeglossary();
				this.transcriptClicked = false;
				this.glossaryClicked = true;

				break;
		}
	};

	p.cacheTopicTypeClass = function(pageIndex, tocItem) {
		if (!pageIndex || !tocItem) {
			return;
		}
		this.tocTypeClassMap[pageIndex] = this.getMenuIcon(tocItem);
	};

	p.getMenuIconMap = function() {
		return {
			'home': 'home',
			'book-open': 'book-open',
			'brain': 'brain',
			'lightbulb': 'lightbulb',
			'clipboard-check': 'clipboard-check',
			'accessibility': 'accessibility',
			'badge-check': 'badge-check',
			'check-circle': 'check-circle',
			'clipboard-list': 'clipboard-list',
			'video': 'video',
			'quiz': 'quiz'
		};
	};

	p.normalizeIconKey = function(iconValue) {
		return (iconValue || '')
			.toString()
			.toLowerCase()
			.trim()
			.replace(/_/g, '-')
			.replace(/\s+/g, '-');
	};

	p.getAutoMenuIcon = function(tocItem) {
		var title = (tocItem.title || '').toLowerCase().trim();
		var contentNode = (tocItem.settings && tocItem.settings.content && tocItem.settings.content.length) ? tocItem.settings.content[0] : {};
		var type = (contentNode.type || '').toLowerCase();
		var path = (contentNode.path || '').toLowerCase();

		if (title.indexOf('summary') > -1) { return 'check-circle'; }
		if (type === 'video') { return 'video'; }
		if (path.indexOf('assets/quiz/') > -1 || title.indexOf('quiz') > -1) { return 'quiz'; }
		if (title.indexOf('knowledge check') > -1) {
			return (title.indexOf('2') > -1) ? 'badge-check' : 'clipboard-check';
		}
		if (title.indexOf('welcome') > -1) { return 'home'; }
		if (title.indexOf('introduction') > -1) { return 'book-open'; }
		if (title.indexOf('accommodation') > -1) { return 'accessibility'; }
		if (title.indexOf('advantage') > -1) { return 'lightbulb'; }
		if (title.indexOf('workforce') > -1 || (title.indexOf('neurodiversity') > -1 && title.indexOf('in the') > -1)) { return 'brain'; }

		return 'book-open';
	};

	p.getMenuIcon = function(tocItem) {
		var masterIconEnabled = tocItem && (
			tocItem.masterIcon === true ||
			tocItem.masterIcon === 'true' ||
			tocItem.masterIcon === 1 ||
			tocItem.masterIcon === '1'
		);

		if (!masterIconEnabled) {
			return null;
		}

		var iconMap = this.getMenuIconMap();
		var requestedIcon = this.normalizeIconKey(tocItem.icon);

		if (requestedIcon && iconMap[requestedIcon]) {
			return requestedIcon;
		}

		var autoIcon = this.normalizeIconKey(this.getAutoMenuIcon(tocItem));
		return iconMap[autoIcon] ? autoIcon : 'book-open';
	};

	p.getTocItemIconSVG = function(iconKey) {
		var icons = {
			'home':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<path d="m3 9 9-7 9 7"/>' +
				'<path d="M9 22V12h6v10"/>' +
				'<path d="M21 22H3"/>' +
				'</svg>',
			'book-open':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>' +
				'<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>' +
				'</svg>',
			'brain':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<path d="M9.5 2a3.5 3.5 0 0 0-3.5 3.5V8a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2v1.5A3.5 3.5 0 0 0 9.5 18H10v4"/>' +
				'<path d="M14.5 2A3.5 3.5 0 0 1 18 5.5V8a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2v1.5A3.5 3.5 0 0 1 14.5 18H14v4"/>' +
				'<path d="M10 8h4"/>' +
				'<path d="M10 13h4"/>' +
				'</svg>',
			'lightbulb':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<line x1="9" y1="18" x2="15" y2="18"/>' +
				'<line x1="10" y1="22" x2="14" y2="22"/>' +
				'<path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/>' +
				'</svg>',
			'clipboard-check':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>' +
				'<rect x="9" y="3" width="6" height="4" rx="2"/>' +
				'<path d="m9 12 2 2 4-4"/>' +
				'</svg>',
			'accessibility':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<circle cx="16" cy="4" r="1"/>' +
				'<path d="m18 19 1-7-6 1"/>' +
				'<path d="m5 8 3-3 5.5 3-2.36 3.5"/>' +
				'<path d="M4.24 14.5a5 5 0 0 0 6.88 6"/>' +
				'<path d="M13.76 17.5a5 5 0 0 0-6.88-6"/>' +
				'</svg>',
			'badge-check':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' +
				'<path d="m9 12 2 2 4-4"/>' +
				'</svg>',
			'check-circle':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<circle cx="12" cy="12" r="10"/>' +
				'<path d="m9 12 2 2 4-4"/>' +
				'</svg>',
			'clipboard-list':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>' +
				'<rect x="9" y="3" width="6" height="4" rx="2"/>' +
				'<line x1="9" y1="12" x2="15" y2="12"/>' +
				'<line x1="9" y1="16" x2="15" y2="16"/>' +
				'</svg>',
			'video':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<polygon points="23 7 16 12 23 17 23 7"/>' +
				'<rect x="1" y="5" width="15" height="14" rx="2"/>' +
				'</svg>',
			'quiz':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>' +
				'<rect x="9" y="3" width="6" height="4" rx="2"/>' +
				'<path d="M12 11v5"/>' +
				'<path d="M12 8h.01"/>' +
				'</svg>',
			'lesson':
				'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
				'<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>' +
				'<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>' +
				'</svg>'
		};
		return icons[iconKey] || icons['lesson'];
	};

	p.applyTopicTypeClasses = function() {
		var labelsByOrder = document.querySelectorAll('#tocData li > span[role="button"]');
		var orderedKeys = Object.keys(this.tocTypeClassMap).sort(function(a, b) {
			return Number(a) - Number(b);
		});

		for (var idx = 0; idx < orderedKeys.length; idx++) {
			var key = orderedKeys[idx];
			if (!this.tocTypeClassMap.hasOwnProperty(key)) { continue; }

			var label = document.getElementById('LSitem' + key) || labelsByOrder[idx];
			if (!label) { continue; }
			if (label.getAttribute('data-icon-injected') === '1') { continue; }
			if (!label.id) {
				label.id = 'LSitem' + key;
			}

			var iconKey = this.tocTypeClassMap[key];
			if (!iconKey) {
				continue;
			}
			var svgHtml = this.getTocItemIconSVG(iconKey);

			var textContent = label.textContent || label.innerText || '';
			var normalizedText = (textContent || '').trim();

			var listItem = label.parentElement;
			var rowWrapper = listItem ? listItem.parentElement : null;
			if (listItem) {
				listItem.classList.add('toc-list-item');
			}
			if (rowWrapper) {
				rowWrapper.classList.add('toc-list-row');
			}

			var iconSpan = document.createElement('span');
			iconSpan.className = 'toc-item-icon';
			iconSpan.setAttribute('aria-hidden', 'true');
			iconSpan.innerHTML = svgHtml;

			var textSpan = document.createElement('span');
			textSpan.className = 'toc-item-text';
			textSpan.textContent = textContent;

			label.innerHTML = '';
			label.appendChild(iconSpan);
			label.appendChild(textSpan);
			label.classList.add('toc-row-item');
			label.setAttribute('data-icon-injected', '1');
			label.setAttribute('data-icon-type', iconKey);
			label.setAttribute('aria-label', normalizedText || textContent);
			if (normalizedText) {
				label.setAttribute('title', normalizedText);
			}
		}
	};
	/**
	 * @ngdoc method
	 * @name tocDataClick
	 * @methodOf aristoFramework.controller:sideBarController
	 * @param {String} data - DOM inner html
	 * @description
	 * Event listener for the TOC list
	 *
	 */
	p.tocDataClick = function(data) {
        if (typeof showNavLoader === 'function' && !showNavLoader()) return;


		if (stage) {
			stage.removeAllChildren();
			stage.update();
			createjs.Ticker.removeEventListener("tick", stage);
		}
		soundClass.stop();
		if (document.getElementById("captivateFrame")) {
			//var a = document.getElementById("captivateFrame").contentWindow.cp;
			// var c = "";
			// if (a !== undefined) {
			//      a.movie.pause(a.ReasonForPause.PLAYBAR_ACTION), c = "pauseAnimation";
			//       a.useg && a.showGesturesAnim && a.showGesturesAnim(c)
			// }
		}
		this.globalVariableService.replaybtnvisible = false;
		if (preload) {
			preload.unloadPreload()
		}
		if (document.getElementById("captivateFrame")) {
			document.getElementById("captivateFrame").src = "about:blank";
		}
		this.changeTocColorChange(data, "yes")
		if (window.matchMedia('(max-width: 1024px)').matches && window.Tmenu) {
			this.tocClick('toggleMenuclose');
		}
		//changeTrackSrc();

	};
	/**
	 * @ngdoc method
	 * @name changeTocColorChange
	 * @methodOf aristoFramework.controller:sideBarController
	 * @description
	 * To remove the class of visited and selected page. Add the current page to the completed page variable
	 *
	 */
	p.changeTocColorChange = function(data, clicked) {

		var count = this.globalVariableService.getStartingPointTocCount();
		var sideBarController = angular.element(document.querySelectorAll("#tocData li > span[role='button']"));


		for (var prop in sideBarController) {

			if (sideBarController.hasOwnProperty(prop)) {
				if ($(sideBarController[prop]).attr('role') !== undefined) {
					count++;

					//console.log("Count :" + count +" title :");

					angular.element(sideBarController[prop]).removeClass("visitedTOC");
					angular.element(sideBarController[prop]).removeClass("selectedToc");

					if (clicked === "yes") {
						if (sideBarController[prop].innerHTML === data.innerHTML) {

							this.globalVariableService.setPageCounter(count);
							this.globalVariableService.addCompletePage(count);
							this.$rootScope.$broadcast("getTocData");
						}
					} else {
						if (count - 1 == data) {
							//	console.log("Data5::"+count);
							this.globalVariableService.addCompletePage(count);

						}
					}
				}
			}

		}
		this.updateTOCColor();
	};
	/**
	 *@ngdoc method
	 * @name changeTocData
	 * @methodOf aristoFramework.controller:sideBarController
	 * @description
	 * On click of transcript to update the values in the sidebar from TOC to transcript
	 *
	 */
	p.changeTocData = function() {

		if (this.globalVariableService.toclevel == false) {
			
			this.tocData1 = this.globalVariableService.getTocData();
			this.modulenumber = this.globalVariableService.pagesmodcount[this.globalVariableService.getPageCounter() - 1][0];
			this.currentpagenumber = this.globalVariableService.pagesmodcount[this.globalVariableService.getPageCounter() - 1][1];
			var transcriptData = '<img class="transcriptDownL" role="button" title="Learning Aids" ng-controller="sideBar as sb" id="mute" ' +
				'onclick="pdfLoader()" ' +
				'src="theme/images/footer-menu/Setting.svg" alt="image">' +
				'<div class="transcriptClass">' +
				(this.tocData1?.[this.modulenumber]?.[this.currentpagenumber - 1]?.['transcript'] || "Transcript not found") +
				'</div>';

		} else if (this.globalVariableService.toclevel == true) {
		
			var transcriptData = '<div class="transcriptDownL" id="transcriptDownLid"></div><div class="transcriptClass"> ' + this.tocData[this.globalVariableService.getPageCounter() - 1].transcript + '</div>';
		}
		this.sideBarData = this.$sce.trustAsHtml(transcriptData);
		document.getElementsByClassName("tocData")[0].scrollTop = 0;
	};
	p.loadglossary = function(response) {

		xmlDoc2 = response.responseXML;
		$(".glossary_Index").delegate('a', 'click', function() {
			$(function() {
				var links = $('#glossaryindex .glossary_Index a').click(function() {
					links.removeClass('active');
					$(this).addClass('active');
				});
			});
			var alphabetid = $(this).attr('id');
			listindex(alphabetid);
			document.getElementById("glossaryinfo").innerHTML = "";
		});


		$("#indexlist").delegate('a', 'click', function() {
			$(this).addClass('active');
			$(function() {
				var links = $('#indexlist a.link').click(function() {
					links.removeClass('active');
				});
			});
			var listitemid = $(this).attr('id');
			displayInfo(listitemid);
		});

		var list = "";

		function listindex(id) {
			list = xmlDoc2.getElementsByTagName(id);
			var indexlist = "";
			for (i = 0; i < list.length; i++) {
				if (list[i].getElementsByTagName("Name")[0].innerHTML !== "Error") {
					var itemname = list[i].getElementsByTagName("Name")[0].innerHTML;
					indexlist += '<a class="link"  href="javascript:void(0)" id="' + i + '">' + itemname + '</a><br/><br/>';
				} else {
					indexlist += "No Words for this Letter";
				}
			}
			document.getElementById("indexlist").innerHTML = indexlist;
		}

		function displayInfo(listitemid) {
			var desc = '';
			var name = list[listitemid].getElementsByTagName("Name")[0].childNodes[0].nodeValue;
			desc += '<b>' + name + ' :</b><br/> ';
			desc += list[listitemid].getElementsByTagName("description")[0].childNodes[0].nodeValue;
			document.getElementById("glossaryinfo").innerHTML = desc;
		}

	}
	p.changeglossary = function() {


		var glosary = '<div id="glossary" class="" style="text-align:left;"><div style="height:5px;"></div><div id="glossaryindex" style="margin-left:10px; height:16px;"><div class="glossary_Index"><a href="javascript:void(0)" id="A">A</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="B">B</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="C">C</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="D">D</a></div><div class="glossary_Index"  ><a href="javascript:void(0)" id="E">E</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="F">F</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="G">G</a></div><div class="glossary_Index"  ><a href="javascript:void(0)" id="H">H</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="I">I</a></div><div class="glossary_Index"  ><a href="javascript:void(0)" id="J">J</a></div><div class="glossary_Index"  ><a href="javascript:void(0)" id="K">K</a></div>        <div class="glossary_Index"><a href="javascript:void(0)" id="L">L</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="M">M</a></div>        <div class="glossary_Index"  ><a href="javascript:void(0)" id="N">N</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="O">O</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="P">P</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="Q">Q</a></div>       <div class="glossary_Index"><a href="javascript:void(0)" id="R">R</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="S">S</a></div>       <div class="glossary_Index"><a href="javascript:void(0)" id="T">T</a></div><div class="glossary_Index"><a href="javascript:void(0)" id="U">U</a></div><div class="glossary_Index"  ><a href="javascript:void(0)" id="V">V</a></div><div class="glossary_Index"  ><a href="javascript:void(0)" id="W">W</a></div><div class="glossary_Index"  ><a href="javascript:void(0)" id="X">X</a></div><div class="glossary_Index"  ><a href="javascript:void(0)" id="Y">Y</a></div><div class="glossary_Index"  ><a href="javascript:void(0)" id="Z">Z</a></div></div>      <br/>      <hr/>      <table border="0">        <tr>          <td><div id="indexlist"> </div></td>          <td valign="top"><div id="glossaryinfo"> </div></td></tr></table><div>';
		this.sideBarData = this.$sce.trustAsHtml(glosary);

		/*this.http({
            method: 'GET',
            url: 'assets/content/' + this.language + '/glossarybutton.xml'
        }).then(assetLoader.proxy(this.loadglossary, this), function errorCallback(response) {
            console.log('error', response);
        });*/

		var self = this;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				self.loadglossary(this);
			}
		};
		xhttp.open("GET", "assets/content/" + this.language + "/glossarybutton.xml", true);
		xhttp.send();

	}
	/**
	 * @ngdoc method
	 * @name updateTOCColor
	 * @methodOf aristoFramework.controller:sideBarController
	 * @description
	 * To change the color of current visited page and last visited page and add it
	 *
	 */
	p.updateTOCColor = function() {
		this.applyTopicTypeClasses();

		var totalCompletedToc = this.globalVariableService.getCompletedPage();
		var currentTOC = this.globalVariableService.getPageCounter();
		//console.log("currentTOC::"+currentTOC);
		totalCompletedToc.sort();
		var sideBarController = angular.element(document.querySelectorAll("#tocData li > span[role='button']"));
		for (var i = 0; i < totalCompletedToc.length; i++) {
			var count = this.globalVariableService.getStartingPointTocCount();
			for (var prop in sideBarController) {
				count++;
				//	alert((angular.element(document.querySelector("#tocData li")).hasClass('disabledClass')) + " "+ angular.element(document.querySelector("#tocData li span")).html());



				if (sideBarController.hasOwnProperty(prop)) {
					if (count == totalCompletedToc[i]) {
						angular.element(sideBarController[prop]).addClass("visitedTOC");
					}
					if (count == currentTOC) {
						angular.element(sideBarController[prop]).removeClass("visitedTOC");
						angular.element(sideBarController[prop]).addClass("selectedToc");
						if ($($("#tocData li")[i]).hasClass('disabledClass')) {
							$($("#tocData li")[i]).removeClass("disabledClass");
						}

					}
					/*   if (totalCompletedToc.length >= 12) {
					       angular.element(document.querySelector("#tocData li.disabledClass")).removeClass("disabledClass")
					   }*/
					if (Resume_Bool) {
						//	console.log("TotalPageNo3:: "+Totalpage)
						for (var i = 0; i < Totalpage; i++) {

							if (Resume_Bool && pageArray[i] == "1") {
								angular.element(sideBarController[i]).addClass("visitedTOC");

							}

						}

					}
				}
			}
		}
		if (this.transcriptClicked) {
			this.changeTocData();
		}
		if (this.glossaryClicked) {
			this.changeglossary();
		}
		this.$rootScope.$broadcast("changeFooterNavigation");
		changeTrackSrc();
	};

	sideBarController.$inject = ['$scope', '$rootScope', '$http', 'globalSettingService', 'globalVariableService', '$sce', "$location"];
	aristoFramework.sideBarController = sideBarController;
}());