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

loadJsonvalue();

function init() {

	//console.log("init");
	scorm.version = "1.2";
	scorm.init();
	res = scorm.get("cmi.core.suspend_data");
	var splitValue = scorm.get("cmi.suspend_data");
	splitted = splitValue.split(",");
	lessonstat = scorm.get("cmi.core.lesson_status");
	//Audio version enable and disable
	if (AudioVersionEnable) {
		PercentageskipPage = 2;
		skipPage = 1;
	} else {
		PercentageskipPage = 0;
		skipPage = 0;
	}

var trimstring = scorm.get("cmi.core.lesson_location");
lessonloc = trimstring.slice(1, 3);

	setTimeout(function() {
	
		var lesson_status1 = scorm.get("cmi.core.lesson_status");
		var lesson_status2 = scorm.get("cmi.success_status");
		// lessonloc = scorm.get("cmi.core.lesson_location");
		//console.log("TotalPageNo4---:: "+lessonloc)
		if (lessonloc != "") {
			curAttempt = trimstring.slice(0, 1);
			lessonloc = trimstring.slice(1, 3);
			//console.log(curAttempt);
			//console.log(lessonloc);
			getPageCompleted();
		//	$("#resumemainContainer").css("display", "block")
			
			Resume_Bool = true;
			retake = true
			yesBtnClick();
		}
		else{
			$("#resumemainContainer").css("display", "none")
		}
		
	}, 100);
	setTimeout(function() {
		var setIndexNumval = Totalpage + 10;
		document.getElementById("trans_id").tabIndex = setIndexNumval + 8;
		document.getElementById("trans_id").focus();
		document.getElementById("toc_id").tabIndex = setIndexNumval + 7;
		document.getElementById("toc_id").focus();
		document.getElementById("exit").tabIndex = setIndexNumval + 2;
		document.getElementById("exit").focus();
		document.getElementById("mute").tabIndex = setIndexNumval + 3;
		document.getElementById("mute").focus();
		document.getElementById("prev").tabIndex = setIndexNumval + 4;
		document.getElementById("prev").focus();
		document.getElementById("next").tabIndex = setIndexNumval + 5;
		document.getElementById("next").focus();
		for (var i = 1; i <= Totalpage; i++) {
			var st = "Mitem" + i;
			var temp = i + 10;
			var st1 = String(temp);
			var element = document.getElementById(st);
			if (element) {
				element.tabIndex = temp;
				element.focus();
			}
		}
		document.getElementById("TmenuIcon").tabIndex = "10";
		document.getElementById("TmenuIcon").focus();
		var vid = document.getElementById("vidArea");
		if (document.getElementById("vidArea")) {
			vid.pause();

		}



	}, 1000);

}
function AfterTemaplateJson()
{
	var MspanElement = document.querySelector('#toc_id span');

		// Update the text content of the span element
		MspanElement.textContent = MenuName;
		var TspanElement = document.querySelector('#trans_id span');

		// Update the text content of the span element
		TspanElement.textContent = TranscriptName;
			//	document.getElementById("courseNameSet").innerHTML=CourseName;
		document.getElementById("Resumeheading").innerHTML=ResumeTitle;
		document.getElementById("resume_id").innerHTML=ResumeHeader;
		
		document.getElementById("resumeyes").innerHTML=ResumeYES;
		document.getElementById("resumeno").innerHTML=ResumeNO;

		 document.getElementById("TmenuIcon").title = Menutitle;
		 document.getElementById("TmenuIcon1").title = Menutitle;
		 
		 document.getElementById("prev").title = NextTitle;
		 document.getElementById("next").title = Prevtitle;
		 //Language Change		 
		 waitForVideoAndChangeTrack(VttLanguage, VttLabel);
		 
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
window.onload = function() {
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
}

window.onunload = function() {
	end();
}

function end() {
	scorm.quit();
	window.open('', '_self', '');
	window.opener = self;
	window.close();
	window.parent.close();
}

function yesBtnClick() {
	var lesson_status1 = scorm.get("cmi.core.lesson_status");
	var lesson_status2 = scorm.get("cmi.success_status");
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
				scorm.set("cmi.suspend_data", page_arr)
				scorm.save();
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
				scorm.set("cmi.suspend_data", page_arr)
				scorm.save();
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
				elementToRemoveClass.classList.add('disabledClass');
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
			//$("#resumemainContainer").css("display", "block")
			Resume_Bool = true;
			retake = true
		}
	var page_arr = pageArray.toString();
	
	scorm.set("cmi.suspend_data", page_arr)
	scorm.save();
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