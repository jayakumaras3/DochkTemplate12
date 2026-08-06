var Totalpage = 0;
var PercentageskipPage = 0;
var skipPage = 0;
var PassScorevalue = 0;
var curAttempt = 0;
var PrecurAttempt = 0;
var QuizAttemptLimit = 0;
var pretestSuccess = false;

function onendofvideo() {


    var contentController = angular.element(document.querySelector(".contentArea"));
    var getvalueonend = contentController.scope().cc.globalVariableService.onvideoended;

    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    //audio Version added	
    if (AudioVersionEnable) {
        if (temp == 2) {
            gotoCertainPage(Totalpage);
        } else {
			//console.log("completed");
			if(contentController.scope().cc.globalVariableService.getPageCounter()==Totalpage)
				{
					
					getTracking(temp);
				}
				else{
					PageCompleteNextFun();
				}
        }
		
        if (getvalueonend == true) {
          //  console.log("temp:: ss")
            //audio Version added	
            if (temp == 2) {} else {
                gotoNextBtnAuto();
            }
        } else {
            //console.log("temp:: added")
            //audio Version 	
            if (temp == 2) {} else {
				
				//console.log(contentController.scope().cc.globalVariableService.getPageCounter());
				//console.log(Totalpage);
				//console.log(completed);
				if(contentController.scope().cc.globalVariableService.getPageCounter()==Totalpage)
				{
					
				}
				/*else if(contentController.scope().cc.globalVariableService.getPageCounter()==)
				{
					
				}*/
				else{
					nextHighlight();
				}
                
            }
        }
		
    } else {
        PageCompleteNextFun();
        if (getvalueonend == true) {
            //console.log("temp::",temp)
            //audio Version removed	
            /*if(temp==2)
            {
            }*/
				if(contentController.scope().cc.globalVariableService.getPageCounter()==Totalpage)
				{
					
					getTracking(temp);
				}
					else
				{
					gotoNextBtnAuto();
				}
        } else {
            //console.log("temp::",temp)
            //audio Version removed	
            /*if(temp==2)
            {
            }
            else*/
			if(contentController.scope().cc.globalVariableService.getPageCounter()==Totalpage)
				{
					
					getTracking(temp);
				}
					else
            {

                nextHighlight();
            }
        }
    }


    if (completed == Totalpage) {
        nextHighlightCongrats()
        //angular.element(document.getElementById("exit")).addClass("exitHighlight");
    }



}

function updatePageCounter() {
    //if(pageArray[temp])
    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;

    var Tempst = 'Mitem' + (temp);
    var Tempprevst = 'Sitem' + (temp);
    //console.log("temp::" +temp);
    //console.log("temp::" +pageArray[temp-3]);
    if (pageArray[temp - 3] == 1) {
        var elementToRemoveClass = document.getElementById(Tempst);

        if (elementToRemoveClass && elementToRemoveClass.classList.contains('disabledClass') && !elementToRemoveClass.classList.contains('tickSymbol')) {
            elementToRemoveClass.classList.remove('disabledClass');
            var elementToRemoveClass1 = document.getElementById(Tempprevst);
            //elementToRemoveClass.classList.add('visitedTOC');
            //elementToRemoveClass1.classList.add('tickSymbol');
        }
    }

}

function pageSormTrack() {
    for (var i = 0; i < Totalpage; i++) {
        var Tempst = 'Mitem' + (i + 1);
        var Tempprevst = 'Sitem' + (i + 1);

        var elementToRemoveClass = document.getElementById(Tempst);
        var elementToRemoveClass1 = document.getElementById(Tempprevst);
        //console.log("pageArray:: " + Tempst);
       // console.log("pageArray:: " + pageArray);
        if (AudioVersionEnable && i == 1) {
			
		} else {
				
            if (pageArray[i] == "1") {
                //	console.log("i: "+i);
                elementToRemoveClass1.style.visibility = 'visible';
                /*if (!elementToRemoveClass1.classList.contains('tickSymbol')) {
                	
                	//elementToRemoveClass1.classList.remove('tickSymbol');
                //	elementToRemoveClass1.classList.add('tickSymbol1');
                	
                	
                }*/
                if (elementToRemoveClass.classList.contains('disabledClass')) {
                    elementToRemoveClass.classList.remove('disabledClass');
                }
                //angular.element(sideBarController[i]).addClass("visitedTOC");

            } else {
				elementToRemoveClass1.style.visibility = 'Hidden';
                /*if(i == 1)
                {
                	//console.log(pageArray[i]);
                }
                else*/
                {

                    if (elementToRemoveClass.classList.contains('disabledClass')) {
                        elementToRemoveClass.classList.remove('disabledClass');
                    }
                    if (masterBool) {

                    } else {
                        break;
                    }
                }

            }
        }

    }
}

function PageCompleteNextFun() {
	 var contentController = angular.element(document.querySelector(".contentArea"));
	
	var temp = contentController.scope().cc.globalVariableService.getPageCounter();
	console.log("temp:: "+ contentController.scope().cc.globalVariableService.getPageCounter());
    console.log("Totalpage:: "+Totalpage);
 if(contentController.scope().cc.globalVariableService.getPageCounter()==Totalpage) {
    
   
    


    if (AudioVersionEnable) {
        var Tempst = 'Mitem' + (temp + 1);
    } else {
        var Tempst = 'Mitem' + (temp);
    }

    var Tempprevst = 'Sitem' + (temp);
    //console.log("temp::" +temp);

    var elementToRemoveClass = document.getElementById(Tempst);

    if (elementToRemoveClass && elementToRemoveClass.classList.contains('disabledClass') && !elementToRemoveClass.classList.contains('tickSymbol')) {
        elementToRemoveClass.classList.remove('disabledClass');
        var elementToRemoveClass1 = document.getElementById(Tempprevst);
        //elementToRemoveClass.classList.add('visitedTOC');
        elementToRemoveClass1.classList.add('tickSymbol');
    }



    getTracking(temp);
   

    // console.log("Totalpage:"+Totalpage)
    pageSormTrack();
}
else
{
	
	 enablenextbtn();
    


    if (AudioVersionEnable) {
        var Tempst = 'Mitem' + (temp + 1);
    } else {
        var Tempst = 'Mitem' + (temp);
    }

    var Tempprevst = 'Sitem' + (temp);
    //console.log("temp::" +temp);

    var elementToRemoveClass = document.getElementById(Tempst);

    if (elementToRemoveClass && elementToRemoveClass.classList.contains('disabledClass') && !elementToRemoveClass.classList.contains('tickSymbol')) {
        elementToRemoveClass.classList.remove('disabledClass');
        var elementToRemoveClass1 = document.getElementById(Tempprevst);
        //elementToRemoveClass.classList.add('visitedTOC');
        elementToRemoveClass1.classList.add('tickSymbol');
    }



    getTracking(temp);
    nextHighlight();

    // console.log("Totalpage:"+Totalpage)
    pageSormTrack();
}

    // console.log("Page:::::::::::"+pageArray);
    // console.log("Page:::::::::::"+contentController.scope().cc.globalVariableService.pageCounter);
    // console.log("Page:::::::::::"+temp);
}

function LanguageTrackChange(val1,val2)
{
	
	
	 let video = document.getElementById("vidArea");

    if (!video) {
        console.error("Video element not found!");
        return;
    }
    let oldTrack = document.getElementById("englishTrack");
    if (oldTrack) {
        video.removeChild(oldTrack); // Remove the existing track
    }

    // Get new track attributes from the selected option
   // let selectedOption = this.options[this.selectedIndex];
  //  let newSrc = val1;
    let newLang = val1;
    let newLabel = val2;

    // Create a new track element
    let newTrack = document.createElement("track");
    newTrack.id = "englishTrack";
    newTrack.kind = "captions";
    newTrack.src = "assets/vtt/En_en_1.vtt";
    newTrack.srclang = newLang;
    newTrack.label = newLabel;
    newTrack.default = true;

    // Append the new track to the video element
    video.appendChild(newTrack);

    // Reload subtitles
    video.textTracks[0].mode = "showing";
}
function changeTrackSrc() {
   var interval = setInterval(() => {
        var video = document.getElementById('vidArea');

        // Check if the video is ready
        if (video && video.readyState >= 2) {
			LanguageTrackChange(VttLanguage, VttLabel);
            clearInterval(interval); // Stop checking once the video is ready

            if (CurrentcontentType === "video") {
                var contentController = angular.element(document.querySelector(".contentArea"));
                var temp = contentController.scope().cc.globalVariableService.pageCounter;

                // Get the video path
                var videoPath = video.currentSrc;

                // Get the video name without extension
                var videoNameWithExtension = videoPath.split('/').pop(); // Splitting by '/' and getting the last part
                var videoName = videoNameWithExtension.split('.')[0]; // Splitting by '.' and getting the first part (name without extension)

                var enStr = "assets/vtt/En_" + videoName + ".vtt";
                var spStr = "assets/vtt/Sp_" + videoName + ".vtt";
                var tuStr = "assets/vtt/Tu_" + videoName + ".vtt";
                var chStr = "assets/vtt/Ch_" + videoName + ".vtt";
                var englishTrack = document.getElementById('englishTrack');
                var spanishTrack = document.getElementById('spanishTrack');
                var chineseTrack = document.getElementById('chineseTrack');
                var turkishTrack = document.getElementById('turkishTrack');

                if (englishTrack) {
                    englishTrack.src = enStr;
                }

                if (spanishTrack) {
                    spanishTrack.src = spStr;
                }
                if (chineseTrack) {
                    chineseTrack.src = chStr;
                }
                if (turkishTrack) {
                    turkishTrack.src = tuStr;
                }

                // After changing the track, call your function to change to the current track
                changeToCurrentTrack();
            }
        }
    }, 200); // Check every 1000 milliseconds (1 second)
}

function pdfLoader() {
    window.open(TranscriptPath, "_blank");
}

function videochange() {
    //console.log("**-***-");
}

function getCurrentTrackName() {
    if (CurrentcontentType == "video") {
        var vid = document.getElementById("vidArea");
        var currentTrack = getCurrentTrack(vid);
        if (currentTrack) {
            //	console.log('Current track name:', currentTrack.label);
            CurrentLanguage = currentTrack.id;
        } else {
            //	console.log('No track currently showing.');
        }
    }
}

function getCurrentTrack(video) {
    var tracks = video.textTracks;
    for (var i = 0; i < tracks.length; i++) {
        if (tracks[i].mode === 'showing') {
            return tracks[i];
        }
    }
    return null;
}
//ch

function changeToCurrentTrack() {
    if (CurrentcontentType == "video") {
        ResetTrack();
        var contentController = angular.element(document.querySelector(".contentArea"));
        var temp = contentController.scope().cc.globalVariableService.pageCounter;
        var video = document.getElementById('vidArea');

        // Get the video path
        var videoPath = video.currentSrc;
        //console.log("videoPath::" + videoPath);

        // Get the video name without extension
        var videoNameWithExtension = videoPath.split('/').pop(); // Splitting by '/' and getting the last part
        var videoName = videoNameWithExtension.split('.')[0]; // Splitting by '.' and getting the first part (name without extension)

        //console.log("Video Name: " + videoName);

        var tuStr = "";
        var currTrack = "";
        if (CurrentLanguage == "englishTrack") {
            currTrack = document.getElementById("englishTrack")
            tuStr = "assets/vtt/En_" + videoName + ".vtt";
        } else if (CurrentLanguage == "spanishTrack") {
            currTrack = document.getElementById("spanishTrack")
            tuStr = "assets/vtt/Sp_" + videoName + ".vtt";
        } else if (CurrentLanguage == "chineseTrack") {
            currTrack = document.getElementById("chineseTrack")
            tuStr = "assets/vtt/Ch_" + videoName + ".vtt";
        } else if (CurrentLanguage == "turkishTrack") {
            currTrack = document.getElementById("turkishTrack")
            tuStr = "assets/vtt/Tu_" + videoName + ".vtt";
        }
        //	console.log(CurrentLanguage);
        //	console.log(tuStr);
        currTrack.src = tuStr;
        currTrack.track.mode = "showing";
    }

}

function ResetTrack() {
    if (CurrentcontentType == "video") {
        var contentController = angular.element(document.querySelector(".contentArea"));
        var temp = contentController.scope().cc.globalVariableService.pageCounter;
        var video = document.getElementById('vidArea');

        // Get the video path
        var videoPath = video.currentSrc;
        //console.log("videoPath::" + videoPath);

        // Get the video name without extension
        var videoNameWithExtension = videoPath.split('/').pop(); // Splitting by '/' and getting the last part
        var videoName = videoNameWithExtension.split('.')[0]; // Splitting by '.' and getting the first part (name without extension)
        var enStr = "assets/vtt/En_" + videoName + ".vtt";
        var spStr = "assets/vtt/Sp_" + videoName + ".vtt";
        var tuStr = "assets/vtt/Tu_" + videoName + ".vtt";
        var chStr = "assets/vtt/Ch_" + videoName + ".vtt";

        var englishTrack = document.getElementById('englishTrack');
        var spanishTrack = document.getElementById('spanishTrack');
        var chineseTrack = document.getElementById('chineseTrack');
        var turkishTrack = document.getElementById('turkishTrack');

        if (englishTrack) {
            englishTrack.src = enStr;
            englishTrack.track.mode = "disabled"; // Show the English track
        }

        if (spanishTrack) {
            spanishTrack.src = spStr;
            spanishTrack.track.mode = "disabled"; // Disable the Spanish track
        }

        if (chineseTrack) {
            chineseTrack.src = chStr;
            chineseTrack.track.mode = "disabled"; // Disable the Chinese track
        }

        if (turkishTrack) {
            turkishTrack.src = tuStr;
            turkishTrack.track.mode = "disabled"; // Disable the Turkish track
        }
    }
}

function setPassScore(val) {
    PassScorevalue = val;
}
var scoreActive;
var scoreInStroedCheck;
var ResultState;

function passScoreTOarticulate() {


    if (pageArray[Totalpage - 1] == 1 || pageArray[Totalpage - 1] == "1") {
        //alert("passScoreTOarticulate");
        scoreActive = true;
        scoreInStroedCheck = Number(scorm.get("cmi.core.score.raw"));
        ResultState = scorm.get("cmi.core.lesson_status");


    }

}

function checkEnableStatus() {

    var statusstr = scorm.get("cmi.core.lesson_status")
    //alert(statusstr);
    if (statusstr == "passed" || statusstr == "failed") {
        pageArray[Totalpage - 1] = 1;



        //alert("checkEnableStatus");
        scoreActive = true;
        ResultState = scorm.get("cmi.core.lesson_status");
        scoreInStroedCheck = Number(scorm.get("cmi.core.score.raw"));

        completed = 0;
        for (var i = 0; i <= Totalpage; i++) {
            if (pageArray[i] == "1" || pageArray[i] == 1) {
                //pageArray[i] = 1;
                if (AudioVersionEnable) {
                    if (i >= 2) {
                        completed++;
                    }
                } else {
                    if (i >= 0) {
                        completed++;
                    }
                }


            } else {
                //	pageArray[i] = 0;
            }
        }
        getPageCompleted();
        var footerController = angular.element(document.querySelector(".footer"));
        footerController.scope().fb.changeFooterNavigation();

    }

}

function resetscore() {
    //	scoreActive=false;
    //scoreInStroedCheck=0;


}

function scoreSubmit(result) {
    var score = RoundToPrecision(Number(result), 2)
    var scoreInStroed = scorm.get("cmi.core.score.raw")
    var lesson_status2 = scorm.get("cmi.success_status");

    var statusstr = scorm.get("cmi.core.lesson_status")
    scorm.set("cmi.core.score.raw", score);


    if (Number(result) >= PassScorevalue) {
        scorm.set("cmi.core.lesson_status", LMSpassed);
		certificateDate=formatDate();
		setSuspendString("str2", certificateDate);
		if(!pretestSuccess)
		{
			
			enableCertificateButton();
		}
        //alert(result);
        //alert(PassScorevalue);

    } else {
        if (curAttempt == QuizAttemptLimit) {
            scorm.set("cmi.core.lesson_status", LMSfailed);
			certificateDate=formatDate();
			setSuspendString("str2", certificateDate);
			//enableCertificateButton();
        }
        //alert(PassScorevalue);
        //alert("failed");
    }

    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    if (AudioVersionEnable) {
        var Tempst = 'Mitem' + (temp + 1);
    } else {
        var Tempst = 'Mitem' + (temp);
    }



    var Tempprevst = 'Sitem' + temp; // Adjusted to previous instead of temp

    var elementToRemoveClass = document.getElementById(Tempst);
    var elementToRemoveClass1 = document.getElementById(Tempprevst);

    // Error checking if the elements are found
    if (elementToRemoveClass1) {
        // Check if the class is not already present
        if (!elementToRemoveClass1.classList.contains('tickSymbol')) {
           // elementToRemoveClass1.classList.add('tickSymbol');
        } else {}
    } else {}
    getTracking(temp);
    var footerController = angular.element(document.querySelector(".footer"));
    footerController.scope().fb.changeFooterNavigation();
    //console.log("Submit");
    nextHighlightCongrats()
    angular.element(document.getElementById("exit")).addClass("exitHighlight");
    passScoreTOarticulate();
	//set 2 added
	pageSormTrack();

}
function scoreSubmit_PostQuiz(result) {
    var score = RoundToPrecision(Number(result), 2)
    var scoreInStroed = scorm.get("cmi.core.score.raw")
    var lesson_status2 = scorm.get("cmi.success_status");

    var statusstr = scorm.get("cmi.core.lesson_status")
    scorm.set("cmi.core.score.raw", score);


    if (Number(result) >= PassScorevalue) {
        scorm.set("cmi.core.lesson_status", LMSpassed);
		certificateDate=formatDate();
		setSuspendString("str2", certificateDate);
		if(!pretestSuccess)
		{
			enableCertificateButton();
		}
        //alert(result);
        //alert(PassScorevalue);

    } else {
        if (curAttempt == QuizAttemptLimit) {
            scorm.set("cmi.core.lesson_status", LMSfailed);
			certificateDate=formatDate();
			setSuspendString("str2", certificateDate);
			//enableCertificateButton();
        }
        //alert(PassScorevalue);
        //alert("failed");
    }

    var contentController = angular.element(document.querySelector(".contentArea"));
    var temp = contentController.scope().cc.globalVariableService.pageCounter;
    if (AudioVersionEnable) {
        var Tempst = 'Mitem' + (temp + 1);
    } else {
        var Tempst = 'Mitem' + (temp);
    }



    var Tempprevst = 'Sitem' + temp; // Adjusted to previous instead of temp

    var elementToRemoveClass = document.getElementById(Tempst);
    var elementToRemoveClass1 = document.getElementById(Tempprevst);

    // Error checking if the elements are found
    if (elementToRemoveClass1) {
        // Check if the class is not already present
        if (!elementToRemoveClass1.classList.contains('tickSymbol')) {
           // elementToRemoveClass1.classList.add('tickSymbol');
        } else {}
    } else {}
    getTracking(temp);
   /* var footerController = angular.element(document.querySelector(".footer"));
    footerController.scope().fb.changeFooterNavigation();
    //console.log("Submit");
    nextHighlightCongrats()
    angular.element(document.getElementById("exit")).addClass("exitHighlight");
    passScoreTOarticulate();*/
	//set 2 added
	pageSormTrack();

}

function RoundToPrecision(number, significantDigits) {

    number = parseFloat(number);
    return (Math.round(number * Math.pow(10, significantDigits)) / Math.pow(10, significantDigits))
}

function TranscriptPdfCaller() {
    window.open("assets/content/PDF/Sustainable Futures in New Media_Transcript.pdf", "_blank");
}