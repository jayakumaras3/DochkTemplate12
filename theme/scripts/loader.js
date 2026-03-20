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

                if (elementToRemoveClass1) {
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
                    if (elementToRemoveClass) {
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
}

function PageCompleteNextFun() {
    var contentController = angular.element(document.querySelector(".contentArea"));

    var temp = contentController.scope().cc.globalVariableService.getPageCounter();
    console.log("temp:: " + contentController.scope().cc.globalVariableService.getPageCounter());
    console.log("Totalpage:: " + Totalpage);
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
 * DYNAMIC VIDEO CAPTION LOADING
 * Extracts video filename and builds corresponding VTT path
 * Handles timing issues with ng-src and video element readiness
 */
function ensureCustomCaptionLayer(video) {
    var frame = video.closest('.shorts-video-frame') || video.parentNode;
    if (!frame) {
        return null;
    }

    var layer = frame.querySelector('.custom-caption-layer');
    if (!layer) {
        layer = document.createElement('div');
        layer.className = 'custom-caption-layer';
        layer.setAttribute('aria-live', 'polite');
        layer.setAttribute('aria-atomic', 'true');
        frame.appendChild(layer);
    }

    return layer;
}

function renderCustomCaptions(video, textTrack) {
    var layer = ensureCustomCaptionLayer(video);
    if (!layer) {
        return;
    }

    var activeCues = textTrack && textTrack.activeCues ? textTrack.activeCues : [];
    var captionLines = [];
    var index;

    for (index = 0; index < activeCues.length; index++) {
        if (activeCues[index] && activeCues[index].text) {
            captionLines.push(activeCues[index].text);
        }
    }

    if (captionLines.length > 0) {
        layer.innerHTML = '<div class="custom-caption-text">' + captionLines.join('<br>') + '</div>';
        layer.classList.add('is-visible');
    } else {
        layer.innerHTML = '';
        layer.classList.remove('is-visible');
    }
}

function bindCustomCaptionTrack(video, trackElement) {
    if (!video || !trackElement || !trackElement.track) {
        return;
    }

    var textTrack = trackElement.track;
    var layer = ensureCustomCaptionLayer(video);
    if (!layer) {
        return;
    }

    if (video._customCaptionTrack && video._customCaptionCueHandler) {
        video._customCaptionTrack.removeEventListener('cuechange', video._customCaptionCueHandler);
    }

    video._customCaptionCueHandler = function() {
        renderCustomCaptions(video, textTrack);
    };

    video._customCaptionTrack = textTrack;
    textTrack.mode = 'hidden';
    textTrack.addEventListener('cuechange', video._customCaptionCueHandler);
    renderCustomCaptions(video, textTrack);
}

function LanguageTrackChange(val1, val2) {
    var video = document.getElementById("vidArea");
    
    if (!video) {
        // Retry briefly if video element not ready
        LanguageTrackChange._retryCount = (LanguageTrackChange._retryCount || 0) + 1;
        if (LanguageTrackChange._retryCount <= 20) {
            setTimeout(function() {
                LanguageTrackChange(val1, val2);
            }, 100);
        }
        return;
    }
    LanguageTrackChange._retryCount = 0;

    // Remove all old caption tracks
    var oldTracks = video.querySelectorAll("track");
    for (var i = 0; i < oldTracks.length; i++) {
        oldTracks[i].parentNode.removeChild(oldTracks[i]);
    }

    if (video._customCaptionTrack && video._customCaptionCueHandler) {
        video._customCaptionTrack.removeEventListener('cuechange', video._customCaptionCueHandler);
        video._customCaptionTrack = null;
        video._customCaptionCueHandler = null;
    }

    var existingCaptionLayer = ensureCustomCaptionLayer(video);
    if (existingCaptionLayer) {
        existingCaptionLayer.innerHTML = '';
        existingCaptionLayer.classList.remove('is-visible');
    }

    // Get video source dynamically
    var src = video.currentSrc || video.getAttribute("src");
    if (!src) {
        // ng-src may not be ready yet, retry
        LanguageTrackChange._retryCount = (LanguageTrackChange._retryCount || 0) + 1;
        if (LanguageTrackChange._retryCount <= 20) {
            setTimeout(function() {
                LanguageTrackChange(val1, val2);
            }, 100);
        }
        return;
    }

    // Extract video filename (e.g., "en_2.mp4" -> "en_2")
    var videoName = src.split('/').pop().split('.')[0];
    
    // Build English VTT path dynamically
    var vttPath = "assets/vtt/En_" + videoName + ".vtt?v=" + Date.now();
    
    // Create track element
    var track = document.createElement("track");
    track.id = "captionTrack";
    track.kind = "captions";
    track.label = val2 || "English";
    track.srclang = val1 || "en";
    track.src = vttPath;
    track.setAttribute("default", "");
    
    video.appendChild(track);

    track.addEventListener('load', function() {
        bindCustomCaptionTrack(video, track);
    });

    // Fallback bind in case load event already fired or browser delays it.
    setTimeout(function() {
        bindCustomCaptionTrack(video, track);
    }, 300);
}

// Backward-compatibility shim: some legacy controllers still call changeTrackSrc().
// Keep it safe and route to the dynamic caption handler.
function changeTrackSrc() {
    LanguageTrackChange(VttLanguage || "en", VttLabel || "English");
}

/**
 * LIFECYCLE-BASED CAPTION BINDING
 * Attaches caption tracks when video element is recreated during SPA navigation
 * Prevents duplicate binding with dataset flag
 */
function bindVideoCaptionLifecycle() {
    var video = document.getElementById("vidArea");
    
    if (!video) return;
    
    // Prevent duplicate binding on same video element
    if (video.dataset.enCaptionBound === "1") return;
    video.dataset.enCaptionBound = "1";
    
    // Bind to metadata ready events
    video.addEventListener("loadedmetadata", function() {
        LanguageTrackChange("en", "English");
    }, false);
    
    video.addEventListener("loadeddata", function() {
        LanguageTrackChange("en", "English");
    }, false);
    
    // Initial attachment attempt
    setTimeout(function() {
        LanguageTrackChange("en", "English");
    }, 50);
}

/**
 * CONTINUOUS VIDEO ELEMENT MONITORING
 * Detects when video element is recreated (as in SPA/SCORM navigation) 
 * Re-applies lifecycle binding to ensure captions always load
 */
setInterval(function() {
    bindVideoCaptionLifecycle();
}, 500);

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
        certificateDate = formatDate();
        setSuspendString("str2", certificateDate);
        if (!pretestSuccess) {

            enableCertificateButton();
        }
        //alert(result);
        //alert(PassScorevalue);

    } else {
        if (curAttempt == QuizAttemptLimit) {
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
        if (curAttempt == QuizAttemptLimit) {
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