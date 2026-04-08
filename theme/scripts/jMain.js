var Tmenu = false;
var TogglemenuControl = false;
//header and menu bar fullscren enabled but footer disabledClass
var HF_footerdisBool = false;
// For Accesiblity Enable Disable for Next and prev

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

	var pointerEvents = window.getComputedStyle(TmenuIcon).getPropertyValue('pointer-events');

	if (pointerEvents === 'none') {
		TmenuIcon.setAttribute('aria-label', '');
        TmenuIcon.setAttribute('aria-disabled', 'true');
        TmenuIcon.setAttribute('tabindex', '-1'); // remove from tab order
        TmenuIcon.style.pointerEvents = 'none'; 
		//console.log('TmenuIcon is not interactive (pointer-events: none)');
	} else {
		TmenuIcon.setAttribute('aria-label', Menutitle);
        TmenuIcon.setAttribute('aria-disabled', 'false');
        TmenuIcon.setAttribute('tabindex', '0');
        TmenuIcon.style.pointerEvents = 'auto';
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
		nextButton.setAttribute('aria-label', NextTitle);
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
		prevButton.setAttribute('aria-label', Prevtitle);
        prevButton.setAttribute('aria-disabled', 'true');
        prevButton.setAttribute('tabindex', '-1'); // remove from tab order
        prevButton.style.pointerEvents = 'none'; // disable click
    }
}
function TtoggleMenu() {
    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    var elements = document.getElementById("Tmenu");
    // Access the first element in the collection

    var menuIcon = document.getElementById("TmenuIcon");
    var menuIcon1 = document.getElementById("TmenuIcon1");
    if (AudioVersionEnable) {
        // Audio version added
        if (temp == 2) {
            Tmenu = false;
            //menuIcon.src = "assets/images/footer-menu/Menuopen.png";
            elements.style.display = "none";
            menuIcon.style.display = "block";
            menuIcon1.style.display = "none";
           // menuIcon.style.marginTop = "6px";
        } else {
            // Change the marginTop property
            //menuIcon.style.marginTop = "6px";
            // Set the new source path
            if (!Tmenu) {

               
                //menuIcon.src = "theme/images/footer-menu/Mclose.png";
                elements.style.display = "block";
                menuIcon.style.display = "none";
                menuIcon1.style.display = "none";
                var sideBarController = angular.element(document.querySelector(".sideBar"));
                sideBarController.scope().sb.tocClick('toc');
               // document.getElementById("TmenuIcon").focus();
                //menuIcon.style.marginTop = "1%";
				menuOpen();
				setTimeout(callTmenuBool, 5);


            } else {
                Tmenu = false;
                menuIcon.src = "theme/images/footer-menu/Menuopen.png";
                elements.style.display = "none";
                menuIcon.style.display = "block";
                menuIcon1.style.display = "none";
            }

        }


    } else {
        {
            // Change the marginTop property
           // menuIcon.style.marginTop = "6px";

            // Set the new source path
            if (!Tmenu) {                
             //   menuIcon.src = "theme/images/footer-menu/Mclose.png";
                elements.style.display = "block";
                menuIcon.style.display = "none";
                menuIcon1.style.display = "none";
                var sideBarController = angular.element(document.querySelector(".sideBar"));
                sideBarController.scope().sb.tocClick('toc');
           //     document.getElementById("TmenuIcon").focus();
               // menuIcon.style.marginTop = "1%";
				menuOpen();
				setTimeout(callTmenuBool, 5);				

            } else {
                Tmenu = false;
                menuIcon.src = "theme/images/footer-menu/Menuopen.png";
                elements.style.display = "none";
                menuIcon.style.display = "block";
                menuIcon1.style.display = "none";
            }

        }
    }
	//single page functionality
	if(Totalpage==1)
	{
		document.querySelector(".sideBar").style.top = "1px"; 
		document.querySelector(".sideBar").style.left = "2px";
		//menuIcon1.style.marginLeft = "210px";
	}
	else{
		document.querySelector(".sideBar").style.top = "0px";
		document.querySelector(".sideBar").style.left = "8px";
		menuIcon1.style.marginLeft = "206px";
	}


}
function callTmenuBool()
{
	
Tmenu = true;
}
// for disable and enabled menuIcon
function menuOpen()
{
	
	var clickDiv=document.getElementById('clickableDiv')
	/*clickDiv.addEventListener('click', TtoggleMenu); */ 
  // Check if the div already has the 'headingArea1' class
  if (clickDiv.classList.contains('headingArea1')) {
    // Remove 'headingArea1' and add 'headingcloser'
    //clickDiv.classList.remove('headingArea1');
    clickDiv.classList.add('headingcloser');
  }


}
function menuClose()
{
	//console.log("menu close"+Tmenu);
	if(Tmenu)
	{
	var clickDiv=document.getElementById('clickableDiv')
    // Otherwise, revert the classes back
    clickDiv.classList.remove('headingcloser');
    clickDiv.classList.add('headingArea1');
	TtoggleMenu();
	}
  

}

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
          //  alert("Audio page");
            // Change the src attribute to the new image path
           // img.setAttribute('src', 'theme/images/footer-menu/disableMenu.png');
            img.style.pointerEvents = 'none';

            TtoggleMenu();
            document.getElementById("pageNo").innerHTML = "Audio";
           img.style.cursor = 'default'; 
             img.style.pointerEvents = 'none';
             document.getElementById('clickableDiv').classList.add('disabledClass');
             

        } else {
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.png');
            img.style.pointerEvents = 'auto';
            img.style.cursor = 'pointer';
            document.getElementById('clickableDiv').classList.remove('disabledClass');
        }

    } else {

        img.setAttribute('src', 'theme/images/footer-menu/Menuopen.png');
        img.style.pointerEvents = 'auto';
        img.style.cursor = 'pointer';


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
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.png');
            img.style.pointerEvents = 'auto';
            img.style.cursor = 'pointer';
           
            

        }

    } 

 MainmenuAcessibility();
  
}
function pagenumberEna_DIsable(bool)
{
    var pageNoHeader = document.getElementById('pagenoHeader');
    if (!pageNoHeader) {
        return;
    }
	if(bool)
	{
        pageNoHeader.style.setProperty('display', 'inline-flex', 'important');
	}
	else{
        pageNoHeader.style.setProperty('display', 'none', 'important');
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
            // Change the src attribute to the new image path
            //img.setAttribute('src', 'theme/images/footer-menu/disableMenu.png');
            document.getElementById('clickableDiv').classList.add('disabledClass');
            img.style.pointerEvents = 'none';
        }

    }  
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

            // Change the src attribute to the new image path
            //img.setAttribute('src', 'theme/images/footer-menu/disableMenu.png');
            
            img.style.pointerEvents = 'none';

            TtoggleMenu();
            document.getElementById("pageNo").innerHTML = "Audio";
            //img.style.cursor = 'cursor'; 
            document.getElementById('clickableDiv').classList.add('disabledClass');

        } else {
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.png');
            img.style.pointerEvents = 'auto';
            img.style.cursor = 'pointer';
            document.getElementById('clickableDiv').classList.remove('disabledClass');
        }

    } else {

        img.setAttribute('src', 'theme/images/footer-menu/Menuopen.png');
        document.getElementById('clickableDiv').classList.remove('disabledClass');
        img.style.pointerEvents = 'auto';
        img.style.cursor = 'pointer';


    }

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