var Tmenu = false;
var TogglemenuControl = false;
//header and menu bar fullscren enabled but footer disabledClass
var HF_footerdisBool = false;

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
            menuIcon.style.marginTop = "6px";
        } else {
            // Change the marginTop property
            menuIcon.style.marginTop = "6px";
            // Set the new source path
            if (!Tmenu) {

               
                menuIcon.src = "theme/images/footer-menu/Mclose.png";
                elements.style.display = "block";
                menuIcon.style.display = "none";
                menuIcon1.style.display = "none";
                var sideBarController = angular.element(document.querySelector(".sideBar"));
                sideBarController.scope().sb.tocClick('toc');
                document.getElementById("TmenuIcon").focus();
                menuIcon.style.marginTop = "1%";
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
            menuIcon.style.marginTop = "6px";

            // Set the new source path
            if (!Tmenu) {                
                menuIcon.src = "theme/images/footer-menu/Mclose.png";
                elements.style.display = "block";
                menuIcon.style.display = "none";
                menuIcon1.style.display = "none";
                var sideBarController = angular.element(document.querySelector(".sideBar"));
                sideBarController.scope().sb.tocClick('toc');
                document.getElementById("TmenuIcon").focus();
                menuIcon.style.marginTop = "1%";
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
	console.log("menu close"+Tmenu);
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
    console.log("menuEnDisble_fun called");
    var headingArea = document.querySelector('.headingArea1');
    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    var img = document.getElementById('TmenuIcon');
    //Menu Disable added
    if (AudioVersionEnable) {
        if (temp == 2) {
            headingArea.style.display = 'block';

            // Change the src attribute to the new image path
            img.setAttribute('src', 'theme/images/footer-menu/disableMenu.png');
            img.style.pointerEvents = 'none';

            TtoggleMenu();
            document.getElementById("pageNo").innerHTML = "Audio";
            //img.style.cursor = 'cursor'; 

        } else {
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.png');
            img.style.pointerEvents = 'auto';
            img.style.cursor = 'pointer';
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



function menuEnDisble_fun_PREV() {
    console.log("menuEnDisble_fun called");
    var headingArea = document.querySelector('.headingArea1');
    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    var img = document.getElementById('TmenuIcon');
    //Menu Disable added
    if (AudioVersionEnable) {
        if (temp == 2) {
            headingArea.style.display = 'block';

            // Change the src attribute to the new image path
            img.setAttribute('src', 'theme/images/footer-menu/disableMenu.png');
            img.style.pointerEvents = 'none';

            TtoggleMenu();
            document.getElementById("pageNo").innerHTML = "Audio";
            //img.style.cursor = 'cursor'; 

        } else {
            img.setAttribute('src', 'theme/images/footer-menu/Menuopen.png');
            img.style.pointerEvents = 'auto';
            img.style.cursor = 'pointer';
        }

    } else {

        img.setAttribute('src', 'theme/images/footer-menu/Menuopen.png');
        img.style.pointerEvents = 'auto';
        img.style.cursor = 'pointer';


    }

}

function beginButCall() {
console.log("called===");
    pageArray[0] = 1;
	pageArray[1] = 1;
    if (AudioVersionEnable) {
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