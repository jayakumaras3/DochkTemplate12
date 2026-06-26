var Tmenu = false;
var TogglemenuControl = false;
//header and menu bar fullscren enabled but footer disabledClass
var HF_footerdisBool = false;
var MENU_STATE_KEY = "player.sidebar.open";
// For Accesiblity Enable Disable for Next and prev

function isMobileDrawerViewport() {
    return window.matchMedia('(max-width: 1024px)').matches;
}

function updateMenuExpandedState(isOpen) {
    var menuIcon = document.getElementById("TmenuIcon");
    if (menuIcon) {
        menuIcon.setAttribute("aria-expanded", isOpen ? "true" : "false");
    }
}

function updateMenuVisualState(isOpen) {
    var elements = document.getElementById("Tmenu");
    var menuIcon = document.getElementById("TmenuIcon");
    var menuIcon1 = document.getElementById("TmenuIcon1");
    var wholeContainer = document.querySelector(".wholeContainer");
    var clickDiv = document.getElementById("clickableDiv");

    if (elements) {
        elements.style.display = isOpen ? "block" : "none";
    }
    if (menuIcon) {
        menuIcon.style.display = "block";
    }
    if (menuIcon1) {
        menuIcon1.style.display = "none";
    }
    if (wholeContainer) {
        wholeContainer.classList.toggle("menu-open", isOpen);
    }
    document.body.classList.toggle("menu-open-body", isOpen);
    document.documentElement.classList.toggle("menu-open-body", isOpen);
    if (clickDiv) {
        clickDiv.classList.remove("headingcloser");
        clickDiv.classList.add("headingArea1");
    }
    updateMenuExpandedState(isOpen);

    // Transparent overlay to capture clicks on iframes when menu is open.
    var overlay = document.getElementById("menuClickOverlay");
    if (isOpen) {
        if (!overlay) {
            overlay = document.createElement("div");
            overlay.id = "menuClickOverlay";
            overlay.setAttribute("aria-hidden", "true");
            overlay.style.cssText =
                "position:fixed;top:0;left:0;right:0;bottom:0;" +
                "z-index:1004;background:rgba(8,15,30,0.2);cursor:pointer;display:block;" +
                "width:100vw;height:100dvh;";
            overlay.addEventListener("click", function() { menuClose(); });
            document.body.appendChild(overlay);
        } else {
            overlay.style.display = "block";
            overlay.style.width = "100vw";
            overlay.style.height = "100dvh";
        }
    } else {
        if (overlay) { overlay.style.display = "none"; }
    }
}

function persistMenuState(isOpen) {
    try {
        sessionStorage.setItem(MENU_STATE_KEY, isOpen ? "1" : "0");
    } catch (e) {
        // Ignore storage failures in restricted LMS iframes.
    }
}

function shouldMenuBeForcedClosedForAudioPage() {
    if (!AudioVersionEnable) {
        return false;
    }
    var contentController = angular.element(document.querySelector(".contentArea"));
    if (!contentController || !contentController.scope() || !contentController.scope().cc) {
        return false;
    }
    return contentController.scope().cc.globalVariableService.pageCounter == 2;
}

function NexPrevAcessiblity_Check()
{
	 var nextButton = document.getElementById('next');
	  var prevButton = document.getElementById('prev');
	if (prevButton.classList.contains('disabledClass')) {
			// The button is visually and functionally disabled
			PrevAcessibility(false);
		} else {
			// The button is enabled
			PrevAcessibility(true);
			
		}

		if (nextButton.classList.contains('disabledClass')) {
			// The button is visually and functionally disabled
			NextAcessibility(false);
		} else {
			// The button is enabled
			NextAcessibility(true);
		}
}
function MainmenuAcessibility()
{
	var TmenuIcon = document.getElementById('TmenuIcon');
    var clickDiv = document.getElementById('clickableDiv');

	var pointerEvents = window.getComputedStyle(TmenuIcon).getPropertyValue('pointer-events');

	if (pointerEvents === 'none') {
		TmenuIcon.setAttribute('aria-label', '');
        TmenuIcon.setAttribute('aria-disabled', 'true');
        TmenuIcon.setAttribute('tabindex', '-1'); // remove from tab order
        TmenuIcon.style.pointerEvents = 'none'; 
        TmenuIcon.style.cursor = 'default';
        if (clickDiv) {
            clickDiv.style.cursor = 'default';
        }
		//console.log('TmenuIcon is not interactive (pointer-events: none)');
	} else {
		TmenuIcon.setAttribute('aria-label', Menutitle);
        TmenuIcon.setAttribute('aria-disabled', 'false');
        TmenuIcon.setAttribute('tabindex', '0');
        TmenuIcon.style.pointerEvents = 'auto';
        TmenuIcon.style.cursor = 'pointer';
        if (clickDiv) {
            clickDiv.style.cursor = 'pointer';
        }
		//console.log('TmenuIcon is interactive (pointer-events: ' + pointerEvents + ')');
	}
		
}
function NextAcessibility(en)
{
	 var nextButton = document.getElementById('next');

    if (en) {
		nextButton.setAttribute('aria-label', NextTitle);
        nextButton.setAttribute('aria-disabled', 'false');
        nextButton.setAttribute('tabindex', '0');
        nextButton.style.pointerEvents = 'auto'; // enable click
    } else {
		nextButton.setAttribute('aria-label', '');
        nextButton.setAttribute('aria-disabled', 'true');
        nextButton.setAttribute('tabindex', '-1'); // remove from tab order
        nextButton.style.pointerEvents = 'none'; // disable click
    }
}
function PrevAcessibility(en) {
    var prevButton = document.getElementById('prev');

   
    if (en) {
		 prevButton.setAttribute('aria-label', Prevtitle);

        prevButton.setAttribute('aria-disabled', 'false');
        prevButton.setAttribute('tabindex', '0');
        prevButton.style.pointerEvents = 'auto'; // enable click
    } else {
		prevButton.setAttribute('aria-label', '');
        prevButton.setAttribute('aria-disabled', 'true');
        prevButton.setAttribute('tabindex', '-1'); // remove from tab order
        prevButton.style.pointerEvents = 'none'; // disable click
    }
}
function TtoggleMenu() {
    var menuIcon = document.getElementById("TmenuIcon");
    if (!menuIcon || menuIcon.style.pointerEvents === "none" || shouldMenuBeForcedClosedForAudioPage()) {
        Tmenu = false;
        updateMenuVisualState(false);
        persistMenuState(false);
        return;
    }

    if (!Tmenu) {
        var sideBarController = angular.element(document.querySelector(".sideBar"));
        if (sideBarController && sideBarController.scope() && sideBarController.scope().sb) {
            sideBarController.scope().sb.tocClick('toc');
        }
        Tmenu = true;
        updateMenuVisualState(true);
        persistMenuState(true);
    } else {
        Tmenu = false;
        menuIcon.src = "theme/images/footer-menu/Menuopen.svg";
        updateMenuVisualState(false);
        persistMenuState(false);
    }
}
function callTmenuBool()
{
	
Tmenu = true;
}
// for disable and enabled menuIcon
function menuOpen()
{
    Tmenu = true;
    updateMenuVisualState(true);
    persistMenuState(true);


}
function menuClose()
{
    if(!Tmenu) {
        return;
    }
    Tmenu = false;
    updateMenuVisualState(false);
    persistMenuState(false);
  

}

function restorePersistedMenuState() {
    if (shouldMenuBeForcedClosedForAudioPage()) {
        Tmenu = false;
        updateMenuVisualState(false);
        persistMenuState(false);
        return;
    }

    var savedState = null;
    try {
        savedState = sessionStorage.getItem(MENU_STATE_KEY);
    } catch (e) {
        savedState = null;
    }

    if (savedState === "1") {
        Tmenu = true;
        updateMenuVisualState(true);
    } else {
        Tmenu = false;
        updateMenuVisualState(false);
    }
}

window.addEventListener('load', function() {
    Tmenu = false;
    updateMenuVisualState(false);
    try { sessionStorage.setItem(MENU_STATE_KEY, "0"); } catch(e) {}
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && Tmenu) {
        menuClose();
    }
});

document.addEventListener('click', function(event) {
    if (!Tmenu) {
        return;
    }
    var sideBar = document.getElementById('Tmenu');
    var menuIcon = document.getElementById('TmenuIcon');

    if (!sideBar || !menuIcon) {
        return;
    }

    var clickedInsideSidebar = sideBar.contains(event.target);
    var clickedMenuIcon = menuIcon.contains(event.target);

    if (!clickedInsideSidebar && !clickedMenuIcon) {
        menuClose();
    }
});

function menuEnDisble_fun(bool) {
   // console.log("menuEnDisble_fun called");
    var headingArea = document.querySelector('.headingArea1');
    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    var img = document.getElementById('TmenuIcon');
    //Menu Disable added
    if (AudioVersionEnable) {
        if (temp == 2) {
            headingArea.style.display = 'block';

            // Keep the new menu icon and visually indicate disabled state
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.svg');
            img.setAttribute('aria-disabled', 'true');
            img.setAttribute('tabindex', '-1');
            img.style.pointerEvents = 'none';
            img.style.cursor = 'default';
            img.style.opacity = '0.35';
            img.style.filter = 'grayscale(100%)';

            TtoggleMenu();
            document.getElementById("pageNo").innerHTML = "Audio";
            //img.style.cursor = 'cursor'; 

        } else {
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.svg');
            img.setAttribute('aria-disabled', 'false');
            img.setAttribute('tabindex', '0');
            img.style.pointerEvents = 'auto';
            img.style.cursor = 'pointer';
            img.style.opacity = '';
            img.style.filter = '';
        }

    } else {

        img.setAttribute('src', 'theme/images/footer-menu/Menuopen.svg');
        img.setAttribute('aria-disabled', 'false');
        img.setAttribute('tabindex', '0');
        img.style.pointerEvents = 'auto';
        img.style.cursor = 'pointer';
        img.style.opacity = '';
        img.style.filter = '';


    }


    if (bool) {

        if (headingArea) { // Check if the element exists
            headingArea.style.display = 'block';
        }
    } else {

        if (headingArea) { // Check if the element exists
            headingArea.style.display = 'none';
        }
    }

    MainmenuAcessibility();
}
//Content Foryou
function menuEnable_fun_firstpage() {
   // console.log("menuEnDisble_fun called");
    var headingArea = document.querySelector('.headingArea1');
    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    var img = document.getElementById('TmenuIcon');
    //Menu Disable added
     {
       {
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.svg');
            img.setAttribute('aria-disabled', 'false');
            img.setAttribute('tabindex', '0');
            img.style.pointerEvents = 'auto';
            img.style.cursor = 'pointer';
            img.style.opacity = '';
            img.style.filter = '';
           
            

        }

    } 

 MainmenuAcessibility();
  
}
function pagenumberEna_DIsable(bool)
{
	if(bool)
	{
		document.getElementById('pagenoHeader').style.display="block";
	}
	else{
		
		document.getElementById('pagenoHeader').style.display="none";
	}
	
}

function menuEnDisble_fun_firstpage() {
   // console.log("menuEnDisble_fun called");
    var headingArea = document.querySelector('.headingArea1');
    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    var img = document.getElementById('TmenuIcon');
    //Menu Disable added
     {
       {
            headingArea.style.display = 'block';
            // Keep the new menu icon and visually indicate disabled state
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.svg');
            img.setAttribute('aria-disabled', 'true');
            img.setAttribute('tabindex', '-1');
            img.style.pointerEvents = 'none';
            img.style.cursor = 'default';
            img.style.opacity = '0.35';
            img.style.filter = 'grayscale(100%)';
        }

    }  

    MainmenuAcessibility();
}



function menuEnDisble_fun_PREV() {
   // console.log("menuEnDisble_fun called");
    var headingArea = document.querySelector('.headingArea1');
    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    var img = document.getElementById('TmenuIcon');
    //Menu Disable added
    if (AudioVersionEnable) {
        if (temp == 2) {
            headingArea.style.display = 'block';

            // Keep the new menu icon and visually indicate disabled state
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.svg');
            img.setAttribute('aria-disabled', 'true');
            img.setAttribute('tabindex', '-1');
            img.style.pointerEvents = 'none';
            img.style.cursor = 'default';
            img.style.opacity = '0.35';
            img.style.filter = 'grayscale(100%)';

            TtoggleMenu();
            document.getElementById("pageNo").innerHTML = "Audio";
            //img.style.cursor = 'cursor'; 

        } else {
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.svg');
            img.setAttribute('aria-disabled', 'false');
            img.setAttribute('tabindex', '0');
            img.style.pointerEvents = 'auto';
            img.style.cursor = 'pointer';
            img.style.opacity = '';
            img.style.filter = '';
        }

    } else {

        img.setAttribute('src', 'theme/images/footer-menu/Menuopen.svg');
        img.setAttribute('aria-disabled', 'false');
        img.setAttribute('tabindex', '0');
        img.style.pointerEvents = 'auto';
        img.style.cursor = 'pointer';
        img.style.opacity = '';
        img.style.filter = '';


    }

    MainmenuAcessibility();

}

function beginButCall() {
//console.log("called===");
    pageArray[0] = 1;
	
    if (AudioVersionEnable) {
		pageArray[1] = 1;
        PageCompleteNextFun();
        gotoCertainPage(3);
        disableclassRemover(3);
    } else {
        getTracking(0)
        gotoCertainPage(2);
    }


}

function audioPlayPagefun() {

    if (controllAudioVersionBool) {
        controllAudioVersionBool = false;
        gotoCertainPage(1);
    }

    /*const myTimeout = setTimeout(myStopFunction, 1000);

	function myStopFunction() {
	console.log("called+++");
	  clearTimeout(myTimeout);
	}*/

}

function audioButCall() {

    //added Audio page
    if (AudioVersionEnable) {
        controllAudioVersionBool = true;
        gotoCertainPage(2);
    } else {
        controllAudioVersionBool = false;

        gotoCertainPage(1);
    }


}

// To remove menu disable class
function disableclassRemover(temp) {
    var Tempst = 'Mitem' + (temp);

    var elementToRemoveClass = document.getElementById(Tempst);

    if (elementToRemoveClass && elementToRemoveClass.classList.contains('disabledClass')) {
        elementToRemoveClass.classList.remove('disabledClass');
    }
}

function disableclassAdd(temp) {
    var Tempst = 'Mitem' + (temp);

    var elementToRemoveClass = document.getElementById(Tempst);

    if (elementToRemoveClass && !elementToRemoveClass.classList.contains('disabledClass')) {
        elementToRemoveClass.classList.addClass('disabledClass');
    }
}
//To increase Video Height 
function videoHeight_fun(str) {
    var pageContents = document.querySelectorAll('.pageContent');

    // Loop through each element and update its height
    pageContents.forEach(function (element) {
        // Set new height (example: 400px)
        element.style.height = str;
    });
}