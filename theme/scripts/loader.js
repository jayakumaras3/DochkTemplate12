var Totalpage = 0;
var PercentageskipPage = 0;
var skipPage = 0;
var PassScorevalue = 0;
var curAttempt = 0;
var PrecurAttempt = 0;
var QuizAttemptLimit = 0;
var pretestSuccess = false;

// QuizAttempt (questions.json / Template.json) may arrive as a string ("0", "3")
// or a number, so every read goes through Number(). A limit of 0 -- or anything
// that is not a usable positive number (blank, null, NaN) -- means UNLIMITED
// attempts, NOT "no attempts allowed". Any positive value is the real cap.
function getQuizAttemptLimit() {
    var limit = Number(QuizAttemptLimit);
    return isFinite(limit) && limit > 0 ? limit : 0;
}

function isUnlimitedQuizAttempts() {
    return getQuizAttemptLimit() === 0;
}

// The codebase's original test for "no attempts left" was
// `curAttempt == QuizAttemptLimit`, which is true on the very first attempt
// when the limit is 0 ("0" == 0) -- that is exactly why 0 behaved as
// "locked out" instead of "unlimited". Unlimited now short-circuits to false,
// and the limited case uses >= so an over-count can never slip past the cap.
function isQuizAttemptLimitReached(currentAttempt) {
    if (isUnlimitedQuizAttempts()) {
        return false;
    }
    return (Number(currentAttempt) || 0) >= getQuizAttemptLimit();
}

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
            if (contentController.scope().cc.globalVariableService.getPageCounter() == Totalpage) {

                getTracking(temp);
            }
            else {
                PageCompleteNextFun();
            }
        }

        if (getvalueonend == true) {
            //  console.log("temp:: ss")
            //audio Version added	
            if (temp == 2) { } else {
                gotoNextBtnAuto();
            }
        } else {
            //console.log("temp:: added")
            //audio Version 	
            if (temp == 2) { } else {

                //console.log(contentController.scope().cc.globalVariableService.getPageCounter());
                //console.log(Totalpage);
                //console.log(completed);
                if (contentController.scope().cc.globalVariableService.getPageCounter() == Totalpage) {

                }
                /*else if(contentController.scope().cc.globalVariableService.getPageCounter()==)
                {
                	
                }*/
                else {
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
            if (contentController.scope().cc.globalVariableService.getPageCounter() == Totalpage) {

                getTracking(temp);
            }
            else {
                gotoNextBtnAuto();
            }
        } else {
            //console.log("temp::",temp)
            //audio Version removed	
            /*if(temp==2)
            {
            }
            else*/
            if (contentController.scope().cc.globalVariableService.getPageCounter() == Totalpage) {

                getTracking(temp);
            }
            else {

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
                if (elementToRemoveClass) {
                    elementToRemoveClass1.style.visibility = 'visible';
                }
                /*if (!elementToRemoveClass1.classList.contains('tickSymbol')) {
                	
                    //elementToRemoveClass1.classList.remove('tickSymbol');
                //	elementToRemoveClass1.classList.add('tickSymbol1');
                	
                	
                }*/
                if (elementToRemoveClass) {
                    if (elementToRemoveClass.classList.contains('disabledClass')) {
                        elementToRemoveClass.classList.remove('disabledClass');
                    }
                }
                //angular.element(sideBarController[i]).addClass("visitedTOC");

            } else {

                if (elementToRemoveClass1) {
                    elementToRemoveClass1.style.visibility = 'Hidden';
                }
                /*if(i == 1)
                {
                    //console.log(pageArray[i]);
                }
                else*/
                {

                    if (elementToRemoveClass && elementToRemoveClass.classList.contains('disabledClass')) {
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
    //console.log("temp:: "+ contentController.scope().cc.globalVariableService.getPageCounter());
    //  console.log("Totalpage:: "+Totalpage);
    if (contentController.scope().cc.globalVariableService.getPageCounter() == Totalpage) {





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
    else {

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

}

/**
 * English-only caption track.
 * NO fetch(), NO async, NO network pre-check.
 * Just create the track element and let the browser handle it.
 * If the VTT file is missing, the browser silently ignores it.
 */
function LanguageTrackChange() {
    var video = document.getElementById("vidArea");
    if (!video) return;

    // Step 1: Remove ALL old track elements
    var oldTracks = video.querySelectorAll("track");
    for (var i = 0; i < oldTracks.length; i++) {
        oldTracks[i].parentNode.removeChild(oldTracks[i]);
    }

    // Step 2: Get video name from the resolved src
    var src = video.currentSrc || video.getAttribute("src");
    if (!src) {
        // Angular/ng-src can populate src after element creation; retry briefly.
        LanguageTrackChange._retryCount = (LanguageTrackChange._retryCount || 0) + 1;
        if (LanguageTrackChange._retryCount <= 20) {
            setTimeout(LanguageTrackChange, 100);
        }
        return;
    }
    LanguageTrackChange._retryCount = 0;

    var videoName = src.split('/').pop().split('.')[0];

    // Step 3: Create English caption track element and append it
    var track = document.createElement("track");
    track.id = "captionTrack";
    track.kind = "captions";
    track.label = "English";
    track.srclang = "en";
    track.src = "assets/vtt/En_" + videoName + ".vtt?v=" + Date.now();
    track.setAttribute("default", "");
    video.appendChild(track);

    // Step 4: Force captions to show after browser processes the track
    setTimeout(function () {
        if (video.textTracks && video.textTracks.length > 0) {
            video.textTracks[0].mode = "showing";
        }
    }, 300);
}

function bindVideoCaptionLifecycle() {
    var video = document.getElementById("vidArea");
    if (!video) return;

    // Prevent duplicate listeners on the same video element.
    if (video.dataset.enCaptionBound === "1") return;
    video.dataset.enCaptionBound = "1";

    video.addEventListener("loadedmetadata", function () {
        LanguageTrackChange();
    }, false);

    video.addEventListener("loadeddata", function () {
        LanguageTrackChange();
    }, false);

    // Attempt once immediately in case metadata is already available.
    setTimeout(LanguageTrackChange, 50);
}

// Keep binding resilient for SPA/SCORM navigation where vidArea is recreated.
setInterval(bindVideoCaptionLifecycle, 500);
function changeTrackSrc() {
    if (CurrentcontentType !== "video") {
        return;
    }

    var interval = setInterval(function () {
        var video = document.getElementById('vidArea');

        if (video && video.readyState >= 2) {
            clearInterval(interval);

            LanguageTrackChange();

            if (window.VideoTrackInitializer && window.VideoTrackInitializer.reinitialize) {
                setTimeout(function () {
                    VideoTrackInitializer.reinitialize();
                }, 100);
            }
        }
    }, 200);
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
    // English-only implementation: no language switching
    if (CurrentcontentType == "video") {
        const video = document.getElementById('vidArea');
        if (!video) return;

        const englishTrack = document.getElementById('captionTrack');
        
        // Show English track if it exists
        if (englishTrack && englishTrack.track) {
            englishTrack.track.mode = "showing";
        }
    }
}

function ResetTrack() {
    // English-only implementation: disable captions
    if (CurrentcontentType == "video") {
        const video = document.getElementById('vidArea');
        if (!video) return;

        const englishTrack = document.getElementById('captionTrack');
        
        // Disable English track if it exists
        if (englishTrack && englishTrack.track) {
            englishTrack.track.mode = "disabled";
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
        certificateDate = formatDate();
        setSuspendString("str2", certificateDate);
        if (!pretestSuccess) {

            enableCertificateButton();
        }
        //alert(result);
        //alert(PassScorevalue);

    } else {
        // Only stamp a terminal "failed" status once the learner has genuinely
        // run out of attempts. With unlimited attempts that never happens.
        if (isQuizAttemptLimitReached(curAttempt)) {
            scorm.set("cmi.core.lesson_status", LMSfailed);
            certificateDate = formatDate();
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
        } else { }
    } else { }
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
        certificateDate = formatDate();
        setSuspendString("str2", certificateDate);
        if (!pretestSuccess) {
            enableCertificateButton();
        }
        //alert(result);
        //alert(PassScorevalue);

    } else {
        // Only stamp a terminal "failed" status once the learner has genuinely
        // run out of attempts. With unlimited attempts that never happens.
        if (isQuizAttemptLimitReached(curAttempt)) {
            scorm.set("cmi.core.lesson_status", LMSfailed);
            certificateDate = formatDate();
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
        } else { }
    } else { }
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