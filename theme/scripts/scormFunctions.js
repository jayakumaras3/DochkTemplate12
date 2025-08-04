// scormFunctions.js

var scorm = pipwerks.SCORM;
var lessonstat = "";
var lessonloc = "";
var Resume_Bool = false;
var retake = false;
var res;
var CurrentLanguage = "englishTrack";
var pause_str = "start";
var modulelength;
var pause_bool = false;
var controllAudioVersionBool = false;
var splitted
var masterBool;
var AudioVersionEnable;
var PageLevelCourseComplete=false;

var pretestCompleteCHeck="";
var lmsStatus="";
var LMSfailed="";
var LMSpassed="";

loadJsonvalue();
var g_dtmInitialized;
 function SCO_Init() {
      const success = pipwerks.SCORM.init();
      if (success) {
        g_dtmInitialized = new Date();
        const status = pipwerks.SCORM.get("cmi.core.lesson_status");
        if (status === "not attempted" || status === "unknown") {
          pipwerks.SCORM.set("cmi.core.lesson_status", "incomplete");
        }
       // document.getElementById("status").textContent = "✅ SCO initialized successfully.";
      } else {
       // document.getElementById("status").textContent = "❌ SCO initialization failed.";
      }
    }

    function SCO_Exit() {
    if (!g_dtmInitialized) {
      //  document.getElementById("status").textContent = "⚠️ SCO was not initialized.";
        return;
      }
      const now = new Date();
      const elapsedSeconds = (now.getTime() - g_dtmInitialized.getTime()) / 1000;
      const formattedTime = ORGformatelapsedTime(elapsedSeconds);
      pipwerks.SCORM.set("cmi.core.session_time", formattedTime);
      pipwerks.SCORM.save();
      
	//  console.log("exit called");
     // document.getElementById("status").textContent = "✅ SCO exited. Session time: " + formattedTime;*/
    }
function init() {

	//console.log("init");
	scorm.version = "1.2";
	scorm.init();
//	res = scorm.get("cmi.core.suspend_data");
	//var splitValue = scorm.get("cmi.suspend_data");
	var splitValue = getSuspendString("str1");
	splitted = splitValue.split(",");
	lessonstat = scorm.get("cmi.core.lesson_status");
	SCO_Init();
	

	var trimstring = scorm.get("cmi.core.lesson_location");
	lessonloc = trimstring.slice(1, 3);

	setTimeout(function() {
	
		var lesson_status1 = scorm.get("cmi.core.lesson_status");
		var lesson_status2 = scorm.get("cmi.success_status");
		pretestCompleteCHeck=getSuspendString("str3");
		if (lessonloc != "") {
			curAttempt = trimstring.slice(0, 1);
			lessonloc = trimstring.slice(1, 3);
			getPageCompleted();
			document.getElementById("mainPage").style.display = "none";
			$("#resumemainContainer").css("display", "block")
			$(".preloaderDisplayresume").attr('tabindex', '0').focus();
			Resume_Bool = true;
			retake = true
		}
		else{
			$("#resumemainContainer").css("display", "none")
		}
		if(CertificateEnabled)
		{
			disableCertificateButton();
		}
		else
		{
			removeCertificateButton();
		}
		//Audio version enable and disable
			if (AudioVersionEnable) {
				PercentageskipPage = 2;
				skipPage = 1;
			} else {
				PercentageskipPage = 0;
				skipPage = 0;
			}
		//AfterTemaplateJson();
		
	}, 300);
	setTimeout(function() {
		
		 var prevButton = document.getElementById('prev');
        
        if (prevButton) {
            // Dynamically add or update accessibility attributes
            prevButton.setAttribute('aria-label', 'Previous Button2');
            prevButton.setAttribute('aria-disabled', 'false'); // Assuming it's not disabled
            prevButton.setAttribute('tabindex', '0');  // Ensure it's focusable
            // Add keyboard event listener for the 'Enter' or 'Space' key
            prevButton.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();  // Prevent default action (e.g., form submission)
                   // handleButtonClick(prevButton); // Trigger the click function
					var footerController = angular.element(document.querySelector(".footer"));
					footerController.scope().fb.navigationBtnClick(prevButton);
					
                }
            });
        }
		var nextButton = document.getElementById('next');
        
        if (nextButton) {
            // Dynamically add or update accessibility attributes
           nextButton.setAttribute('aria-label', 'Next Button1');
            nextButton.setAttribute('aria-disabled', 'false'); // Assuming it's not disabled
            nextButton.setAttribute('tabindex', '0');  // Ensure it's focusable

            // Optionally, you can modify the class for visual feedback

            // Add keyboard event listener for the 'Enter' or 'Space' key
            nextButton.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();  // Prevent default action (e.g., form submission)
                   // handleButtonClick(prevButton); // Trigger the click function
					var footerController = angular.element(document.querySelector(".footer"));
					footerController.scope().fb.navigationBtnClick(nextButton);
					
                }
            });
        }
		var transButton = document.getElementById('resource1');
        
        if (transButton) {
			
            transButton.setAttribute('aria-disabled', 'false'); // Assuming it's not disabled
            transButton.setAttribute('tabindex', '0');  // Ensure it's focusable
            // Add keyboard event listener for the 'Enter' or 'Space' key
            transButton.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();  // Prevent default action (e.g., form submission)
                   // handleButtonClick(prevButton); // Trigger the click function
					var footerController = angular.element(document.querySelector(".footer"));
					footerController.scope().fb.navigationBtnClick(transButton);
					
                }
            });
        }
		
		
		
		var setIndexNumval = Totalpage + 10;
		document.getElementById("exit").tabIndex = 0;
		//document.getElementById("exit").focus();
		document.getElementById("mute").tabIndex = 0;
	//	document.getElementById("mute").focus();
		document.getElementById("prev").tabIndex = 0;
		//document.getElementById("prev").focus();
		document.getElementById("next").tabIndex = 0;
		//document.getElementById("next").focus();
	//	document.getElementById("TmenuIcon").tabIndex = "0";
	//	document.getElementById("TmenuIcon").focus();
		var vid = document.getElementById("vidArea");
		if (document.getElementById("vidArea")) {
			vid.pause();

		}
	if(ResourceEanbled)
	{
		$("#resource1").css("display", "block");
	}
	else{
		$("#resource1").css("display", "none");
	}

NexPrevAcessiblity_Check();
	}, 1000);

}

function AfterTemaplateJson() {
    const MspanElement = document.querySelector('#toc_id span');
    if (MspanElement) MspanElement.textContent = MenuName;

    const TspanElement = document.querySelector('#trans_id span');
    if (TspanElement) TspanElement.textContent = TranscriptName;

    const spanCli = document.getElementById("spanCliContinue");
    if (spanCli) spanCli.innerHTML = spanCliContinue;

    const resumeHeading = document.getElementById("Resumeheading");
    if (resumeHeading) resumeHeading.innerHTML = ResumeTitle;

    const resumeId = document.getElementById("resume_id");
    if (resumeId) resumeId.innerHTML = ResumeHeader;

    const resumeYes = document.getElementById("resumeyes");
    if (resumeYes) resumeYes.innerHTML = ResumeYES;

    const resumeNo = document.getElementById("resumeno");
    if (resumeNo) resumeNo.innerHTML = ResumeNO;

    const menuIcon = document.getElementById("TmenuIcon");
    if (menuIcon) menuIcon.title = Menutitle;

    const menuIcon1 = document.getElementById("TmenuIcon1");
    if (menuIcon1) menuIcon1.title = Menutitle;

    const prevBtn = document.getElementById("prev");
    if (prevBtn) prevBtn.title = NextTitle;

    const nextBtn = document.getElementById("next");
    if (nextBtn) nextBtn.title = Prevtitle;

    // Language Change
    waitForVideoAndChangeTrack?.(VttLanguage, VttLabel);
}

function waitForVideoAndChangeTrack(val1, val2) {
    let interval = setInterval(() => {
        let video = document.getElementById("vidArea");
        if (video) {
            clearInterval(interval); // Stop checking once found
            LanguageTrackChange(val1, val2);
        }
    }, 500); // Check every 500ms
}
/*window.onload = function() {
	init();
	document.addEventListener("DOMContentLoaded", function() {
		let element = document.getElementById("transcriptDownL");
		if (element) {
			element.addEventListener("click", function() {
				alert("Element clicked!");
			});
		} else {
			console.error("Element with ID 'transcriptDownL' not found!");
		}
	});
}*/
function waitForSCORMAndInit(callback, maxAttempts = 50, interval = 200) {
    let attempts = 0;
    const wait = setInterval(() => {
        if (typeof scorm !== "undefined" && typeof scorm.get === "function") {
            clearInterval(wait);
            callback();
        } else {
            attempts++;
            if (attempts >= maxAttempts) {
                clearInterval(wait);
                console.error("SCORM API not available after waiting.");
            }
        }
    }, interval);
}

window.addEventListener("load", function () {
    waitForSCORMAndInit(() => {
        // Your original logic after SCORM and page are ready
        init();
    });
});


/*window.onunload = function() {
	end();
}*/

function end() {
	SCO_Exit();
	scorm.quit();
	window.open('', '_self', '');
	window.opener = self;
	window.close();
	window.parent.close();
}

function yesBtnClick() {
	var lesson_status1 = scorm.get("cmi.core.lesson_status");
	var lesson_status2 = scorm.get("cmi.success_status");
	document.getElementById("mainPage").style.display = "block";
	//	console.log("i am passed or failed"+Totalpage);
	if (AudioVersionEnable) {
		if (lesson_status1 == "passed" || lesson_status1 == "failed" || lesson_status2 == "passed" || lesson_status2 == "failed") {
			//console.log("i am passed or failed");
			completed = 0;
			for (var i = 0; i <= Totalpage; i++) {
				if (splitted[i] == "1" || splitted[i] == 1) {
					pageArray[i] = 1;
					if (i >= 2) {
						completed++;
					}
				} else {
					pageArray[i] = 0;
				}
			}

		} else {
			//	console.log(splitted);
			completed = 0;
			for (var i = 0; i <= Totalpage; i++) {
				if (splitted[i] == "1" || splitted[i] == 1) {
					pageArray[i] = 1;
					if (i >= 2) {
						completed++;
					}
				} else {
					pageArray[i] = 0;
				}
			}
			//console.log(pageArray);

		}
	} else {
		if (lesson_status1 == "passed" || lesson_status1 == "failed" || lesson_status2 == "passed" || lesson_status2 == "failed") {

			completed = 0;
			for (var i = 0; i <= Totalpage; i++) {
				if (splitted[i] == "1" || splitted[i] == 1) {
					pageArray[i] = 1;
					if (i >= 1) {
						completed++;
					}
				} else {
					pageArray[i] = 0;
				}
			}

		} else {
			//	console.log(splitted);
			completed = 0;
			for (var i = 0; i <= Totalpage; i++) {
				if (splitted[i] == "1" || splitted[i] == 1) {
					pageArray[i] = 1;
					if (i >= 1) {
						completed++;
					}
				} else {
					pageArray[i] = 0;
				}
			}
			//console.log(pageArray);

		}
	}



	Resume_Bool = true;
	$("#resumemainContainer").css("display", "none")
	//console.log(scorm.get("cmi.suspend_data"));
	gotoCertainPage(Number(lessonloc));
	//audio version added
	if (AudioVersionEnable) {

		if (Number(lessonloc) == 2) {
			controllAudioVersionBool = true;
		}
		if (lesson_status1 == "passed" || lesson_status1 == "failed" || lesson_status2 == "passed" || lesson_status2 == "failed") {


			completed = 0;
			for (var i = 0; i <= Totalpage; i++) {

				pageArray[i] = 1;
				completed++;
				var page_arr = pageArray.toString();
				setSuspendString("str1", page_arr);
				//scorm.set("cmi.suspend_data", page_arr)
				//scorm.save();
			}
			//console.log("i am inside"+pageArray);

		}

	} else {
		if (lesson_status1 == "passed" || lesson_status1 == "failed" || lesson_status2 == "passed" || lesson_status2 == "failed") {
			completed = 0;
			for (var i = 0; i <= Totalpage; i++) {

				pageArray[i] = 1;
				completed++;
				var page_arr = pageArray.toString();
				setSuspendString("str1", page_arr);
				//scorm.set("cmi.suspend_data", page_arr)
				//scorm.save();
			}
			//console.log("i am inside"+pageArray);
		}
	}
	//console.log(scorm.get("cmi.suspend_data"));

	//Intro page manual edit
	if (pageArray[2] == 1 || Number(lessonloc) == 3 || Number(lessonloc) == 2) {
		//	alert(Number(lessonloc));
		pageArray[1] = 1;
	}
	//getTracking(1);	
	var str1 = "Mitem" + Number(lessonloc);
	if (AudioVersionEnable) {
		var str2 = "Mitem" + (Number(lessonloc) + 1);
	} else {
		var str2 = "Mitem" + (Number(lessonloc));
	}

	angular.element(document.getElementById(str1)).removeClass("disabledClass");
	if (pageArray[(Number(lessonloc) - 1)] == "1") {
		//	console.log("TotalPageNo5:: "+Totalpage)
		if (Number(lessonloc) < Totalpage) {
			angular.element(document.getElementById(str2)).removeClass("disabledClass");
		}
	}
	getPageCompleted()
	var footerController = angular.element(document.querySelector(".footer"));
	footerController.scope().fb.changeFooterNavigation();
}

function noBtnClick() {
	document.getElementById("mainPage").style.display = "block";
	if (AudioVersionEnable) {
		for (var i = 0; i < Totalpage; i++) {
			pageArray[i] = 0;
			var Tempst = 'Mitem' + (i + 1);
			var Tempprevst = 'Sitem' + (i + 1);

			var elementToRemoveClass = document.getElementById(Tempst);
			var elementToRemoveClass1 = document.getElementById(Tempprevst);

		//	elementToRemoveClass1.classList.remove('tickSymbol');

			if (masterBool) {

			} else {
				
				if(i!=1)
				{
					
					elementToRemoveClass.classList.add('disabledClass');
				}
			}

		}
	} else {
		for (var i = 1; i < Totalpage; i++) {
			pageArray[i] = 0;
			var Tempst = 'Mitem' + (i);
			var Tempprevst = 'Sitem' + (i);

			var elementToRemoveClass = document.getElementById(Tempst);
			var elementToRemoveClass1 = document.getElementById(Tempprevst);



		//	elementToRemoveClass1.classList.remove('tickSymbol');
			if (masterBool) {

			} else {
				elementToRemoveClass.classList.add('disabledClass');
			}

		}
	}
	var trimstring = scorm.get("cmi.core.lesson_location");

		// lessonloc = scorm.get("cmi.core.lesson_location");
		lessonloc = trimstring.slice(1, 3);
		if (lessonloc != "") {
			curAttempt = trimstring.slice(0, 1);
			lessonloc = 0;
			//console.log(curAttempt);
			//console.log(lessonloc);
			getPageCompleted();
			$("#resumemainContainer").css("display", "block")
			Resume_Bool = true;
			retake = true
		}
	var page_arr = pageArray.toString();
	setSuspendString("str1", page_arr);
//	scorm.set("cmi.suspend_data", page_arr)
	//scorm.save();
	// getPageCompleted()
	completed = 0;
	//	scorm.set("cmi.core.lesson_status", "incomplete")
	//scorm.set("cmi.core.score.raw",0);
	//	scorm.save();
	gotoCertainPage(1);
	Resume_Bool = false;
	$("#resumemainContainer").css("display", "none");
	pageSormTrack();

}
//Str1=Suspended Pagearray; Str2= Date; str3=attempt;
// Utility: Parse suspend_data string to object
function parseSuspendData(data) {
    let obj = { str1: "", str2: "", str3: "",str4: "" };
    if (data) {
        let parts = data.split(";");
        parts.forEach(part => {
            let [key, val] = part.split("=");
            if (key && val !== undefined) {
                obj[key.trim()] = val;
            }
        });
    }
    return obj;
}

// Utility: Convert object to suspend_data string
function serializeSuspendData(obj) {
    return `str1=${obj.str1};str2=${obj.str2};str3=${obj.str3};str4=${obj.str4}`;
}


function setSuspendString(key, value) {
    let data = scorm.get("cmi.suspend_data");
    let obj = parseSuspendData(data);
    if (["str1", "str2", "str3", "str4"].includes(key)) {
        obj[key] = value;
        let newData = serializeSuspendData(obj);
        let success = scorm.set("cmi.suspend_data", newData);
        scorm.save();
      //  show("Set " + key + " = " + value + ". Call success? " + success);
    } else {
       // show("Invalid key: " + key);
    }
}


function getSuspendString(key) {
    let data = scorm.get("cmi.suspend_data");
    let obj = parseSuspendData(data);
    let val = obj[key] || "";
   // show("Get " + key + " = " + val);
    return val;
}

function PretestAttemptSet()
{
	setSuspendString("str3", "completed");
}