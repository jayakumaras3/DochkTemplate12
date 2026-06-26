/**
 * Created by pc3 on 12/31/2016.
 */

// ── Navigation loader state ──────────────────────────────────────────────────
// Defined here so it survives any revert of scormFunctions.js.
// showNavLoader() is called by nav buttons; hideNavLoader() is called from
// page-ready events (finishLoading, loadeddata, $includeContentLoaded, etc.).
var isPageLoading = false;

function showNavLoader() {
	if (isPageLoading) return false;
	isPageLoading = true;
	showCourseLoader();
	// Safety: release the lock after 8s if a page-ready event is never fired
	setTimeout(function() {
		if (isPageLoading) { isPageLoading = false; hideCourseLoader(); }
	}, 8000);
	return true;
}

function hideNavLoader() {
	if (!isPageLoading) return;
	isPageLoading = false;
	hideCourseLoader();
}

var pauseAlreadyClicked = false;
var currentCaptivativeFrames = 0;
var volume_change=1;
var volume_mute=false;
var CurrentcontentType;
var MenuName="Menu";
var TranscriptName="Transcript";
var ResumeHeader="ResumeHeader";
var ResumeYES="Yes";
var ResumeNO="No";
var ResumeTitle="Resume";
var Menutitle="Menutitle";
var LearningAidsTitle= "LearningAidsTitle";
var NextTitle= "NextT";
var Prevtitle="PrevT";
var VttLabel="English";
var VttLanguage="English";
var TranscriptPath="";
var spanCliContinue="";
var ResourceBool=false;
var CertificateEnabled=false;
var ResourceEanbled=false;
var certificateDate="";

 /**function tocKeyHandler(event, element) {
  if ((event.key === 'Enter' || event.key === ' ') && element.getAttribute('aria-disabled') !== 'true') {
    event.preventDefault();
	var sideBarController = angular.element(document.querySelector(".sideBar"));
    sideBarController.scope().sb.tocDataClick(element);

  }
}*/
function keyHandler(event, type) {
	 if (event.key === 'Enter' || event.key === ' ') {
    event.preventDefault(); // Prevent spacebar from scrolling the page
   var sideBarController = angular.element(document.querySelector(".sideBar"));
  //For keyboard accessibility 2 time calling toc call
    sideBarController.scope().sb.tocClick('toc');
		sideBarController.scope().sb.tocClick(type);
  }
   
};
function handleKeydown(event, name) {
  if (event.key === 'Enter' || event.key === ' ') {
    event.preventDefault(); // Prevent spacebar from scrolling the page	
	
	 const popup = document.getElementById('clickableDiv'); // Replace with actual popup ID
      if (popup) {
        popup.focus();
      }
    p.tocClick(name);
  }
};
function tocKeyHandler(event, element) {
  // Check if Enter or Space is pressed
  if ((event.key === 'Enter' || event.key === ' ')) {
    event.preventDefault();

    // Find the parent <li> (which may have the "disabledClass")
    var liElement = element.closest('li');

    // Only execute if it doesn't have the "disabledClass"
    if (!liElement || !liElement.classList.contains('disabledClass')) {
      var sideBarController = angular.element(document.querySelector(".sideBar"));
      if (sideBarController.scope() && sideBarController.scope().sb && typeof sideBarController.scope().sb.tocDataClick === 'function') {
        sideBarController.scope().sb.tocDataClick(element);
      } else {
        console.warn("tocDataClick function not found on sb");
      }
    }
  }
}

function nextHighlight() {
	
	//console.log("next---");
    if (!angular.element(document.getElementById("next")).hasClass("disabledClass")) {
        angular.element(document.getElementById("next")).addClass("nextClassHighlight").removeClass("disabledClass");
		next_continue(true);
    }
    angular.element(document.getElementById("play")).removeClass("showContent").addClass("hideContent");
    angular.element(document.getElementById("pause")).removeClass("showContent").addClass("hideContent");
	var contentController = angular.element(document.querySelector(".contentArea"));
	var temp = contentController.scope().cc.globalVariableService.pageCounter;
	getTracking(temp);
				

}
function next_continue(val)
{
	if(val)
	{	
		document.getElementById("clickNextId").style.display = "block";
	}
	else
	{
		document.getElementById("clickNextId").style.display = "none";
	}
}
function nextHighlightCongrats()
{
    //angular.element(document.getElementById("play")).removeClass("showContent").addClass("hideContent");
   // angular.element(document.getElementById("pause")).removeClass("showContent").addClass("hideContent");
  //  angular.element(document.getElementById("replay")).removeClass("hideContent").addClass("showContent");
}

function formatDate() {
  const d = new Date();
  const day = String(d.getDate()).padStart(2, '0');
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const year = d.getFullYear();
  return `${day}.${month}.${year}`;
}

function showCertificate() {
	
	 localStorage.setItem("CourseTitle", CourseName);
	 localStorage.setItem("Date", certificateDate);
	 localStorage.setItem("Name", scorm.get("cmi.core.student_name"));
	 window.open('theme/certificate/index.html', '_blank');
	 
	
}
  function removeCertificateButton() {
    const btn = document.getElementById("certificateid");
    btn.disabled = true;
	btn.style.display = "none";
  }
function enableCertificateButton() {
    const btn = document.getElementById("certificateid");

    if (btn) {
        btn.disabled = false;
        btn.style.opacity = 1;
		
		document.getElementById("Imagcertificateid").src = "theme/images/footer-menu/CertificateEnabled.svg";
       
        // btn.alt = "Certificate"; // Optional: enable for accessibility
    } else {
        console.warn("Element with ID 'certificateid' not found.");
    }
}


  function disableCertificateButton() {
    const btn = document.getElementById("certificateid");
    btn.disabled = true;
	 btn.style.opacity = 0.5;
  }

function __nextHighlightSim() {
    nextHighlight();
}
var tocDataClick = function (data) {
    if (data.parentNode.className !== "disabledClass") {
        var sideBarController = angular.element(document.querySelector(".sideBar"));
        sideBarController.scope().sb.tocDataClick(data);
    if (typeof menuClose === "function") {
      menuClose();
    }
    }

};


function gotoNextBtnAuto() {
    var footerController = angular.element(document.querySelector(".footer"));
    footerController.scope().fb.nextBtnClick();
}

function gotoCertainPage(pageNum) {
	
	var footerController = angular.element(document.querySelector(".footer"));
    footerController.scope().fb.gotoCertainPage(pageNum);
	angular.element(document.getElementById("searchContainer")).addClass("hideContent").removeClass("showContent");
	
   
}

function captivateIframeComplete() {
    if (typeof hideNavLoader === 'function') hideNavLoader();
}

function checkNextContent(counter) {
    // Show preloader when navigating via navigation circles
    if (typeof PreloadManager !== 'undefined') {
        PreloadManager.show();
    }
    var contentController = angular.element(document.querySelector(".contentArea"));
    contentController.scope().cc.checkNextContent(counter);
}

function backToMainPage() {
	pause_str="start";
    document.getElementById("resourceArea").style.display = "none";
    var footerController = angular.element(document.querySelector(".footer"));
    if (pauseAlreadyClicked === false) {
        footerController.scope().fb.playBtnClick();
    }
}
function backToMainPage1() {
	pause_str="start";
    angular.element(document.getElementById("resourceArea1")).removeClass("showContent").addClass("hideContent");
    var footerController = angular.element(document.querySelector(".footer"));
    if (pauseAlreadyClicked === false) {
        footerController.scope().fb.playBtnClick();
    }
}

function exitCloseNo() {
	pause_str="start";
    angular.element(document.getElementById("exitContainer")).removeClass("showContent").addClass("hideContent");
    var footerController = angular.element(document.querySelector(".footer"));
   if (pauseAlreadyClicked === false) {
       footerController.scope().fb.playBtnClick();
    }
		var vid = document.getElementById("vidArea");
				if (document.getElementById("vidArea")) {
					vid.play();
				}

}

function getPageCompleted() {
    var splitValue = getSuspendString("str1");
   // var splitValue = scorm.get("cmi.suspend_data");
    var splitted = splitValue.split(",");
	//console.log("----------------------------------++",splitted);
	//var sideBarController = angular.element(document.querySelector(".sideBar"));
    for (var i = 0; i <= Totalpage; i++) {
		//console.log("TotalPageNo4:: "+Totalpage)
        if (splitted[i] == "1") {
            pageArray[i] = 1;
			var st=i+1;
			var str1= "Mitem"+st;
			var str2= "Sitem"+st;
			//console.log("str1::"+str1);
			
			if (!angular.element(document.getElementById(str1)).hasClass("tickSymbol")) {
			//angular.element(document.getElementById(str2)).removeClass("disabledClass").addClass("tickSymbol");
			angular.element(document.getElementById(str1)).removeClass("disabledClass")
}
			

			//p.addCompletePage(pageArray[i]);
			//sideBarController.scope().sb.globalVariableService.calling(i);
			//calling(i);
        }
		else{
			//pageArray[i] = "0";
			//p.addCompletePage(pageArray[i]);
		}
		if (pageArray[i]=="1"){
			//calling(i);
		}
	//	console.log(i+"-----------"+pageArray[i]);		
    }	
	pageSormTrack();
}
function setScormInteractionData(interactionIndex, interactionId, objectiveId, timestamp, learnerResponseStr, correctResponseStr, result, description) {
  // Log the parameters for debugging
  console.log("Interaction ID:", interactionId);
  console.log("Learner Response:", learnerResponseStr);
  console.log("Correct Response:", correctResponseStr);  // Log correctResponseStr
  console.log("Result:", result);
  console.log("Timestamp:", timestamp);
  console.log("Objective:", objectiveId);
  console.log("Description:", description);

  try {
    // Set SCORM interaction data
   /* pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.id`, interactionId);
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.type`, "choice");
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.objectives.0.id`, objectiveId);
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.timestamp`, timestamp);
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.learner_response`, learnerResponseStr);
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.result`, result);
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.description`, description);
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.correct_response`, correctResponseStr); // Added correct response
	pipwerks.SCORM.save();*/
	pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.id`, interactionId);
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.type`, "choice");
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.objectives.0.id`, "1");
  //  pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.timestamp`, timestamp);
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.learner_response`, "2");
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.result`, "correct");
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.description`, "1234");
    pipwerks.SCORM.set(`cmi.interactions.${interactionIndex}.correct_response`, "12"); 

    
  } catch (error) {
    console.error("Error setting SCORM interaction data:", error);
  }
}
function safeScormSet(parameter, value) {
  const success = pipwerks.SCORM.set(parameter, value);
  if (!success) {
    console.error(`Failed to set ${parameter} to ${value}`);
    console.error("SCORM Error:", pipwerks.SCORM.getLastError(), pipwerks.SCORM.getErrorString(pipwerks.SCORM.getLastError()));
  }
}
// get Current Video Time*******
function GetVideoTime() {
    if (CurrentcontentType === "video") {
        var vid1 = document.getElementById("vidArea");
        var currentTime = vid1.currentTime;
        var formattedTime = formatTime(currentTime); // No need to declare again

        return formattedTime; // Return the formatted time directly
    }
    
    return null; // Return null if not a video
}
function formatTime(seconds) {
    var minutes = Math.floor(seconds / 60);
    var secs = Math.floor(seconds % 60);
    
    // Pad seconds with leading zero if less than 10
    secs = secs < 10 ? '0' + secs : secs;

    return minutes + ':' + secs;
}

function searchbtnclick(){

	var contentController = angular.element(document.querySelector(".contentArea"));
    var gettoccontent=contentController.scope().cc.toc;
	var textsearch=$("#searchtext").val();
	var count=0;
	var nocount=0;
	var innercontent="";
	$("#searchArea").css('height','167px');
	$("#searchcountresult").html('');
	$("#searchresult").html('');
	$.each(gettoccontent, function(index, value) {
		var selecttranscript=gettoccontent[index]['transcript'];
			var exp = new RegExp(textsearch,"gi");
			searchExp = selecttranscript.match(exp);
			if ( searchExp != null ) {
				count++;
				var getheader=gettoccontent[index]['header'];
				var pageNum=Number(gettoccontent[index]['name'].split("page")[1]);
			     $("#searchcountresult").html(count+'matches found<hr style="margin-top:5px;margin-bottom:5px;">');	
				innercontent+='<p style="cursor:pointer;" onclick="gotoCertainPage('+pageNum+')">'+getheader+'</p>';
				$("#searchresult").html(innercontent);
				$("#searchArea").css('height',(Number($("#searchArea").height())+(Number(count)*10))+"px");
			}
			else{
				 
				nocount++;
			}
		
	}); 
	if(nocount!=0 && count==0)
	{
		 $("#searchcountresult").html('0 matches found');	
	}

	
}
/*function changingcheckbox(status)
{
    $("#header0,#header1,#header2").removeAttr('checked')
	
   
}*/
function changingcheckbox(y) {
    $(".toggle-box").not('#header' + y).prop('checked', false); // Uncheck other checkboxes
    $(".collapse").not('#demo' + y).removeClass('show'); // Hide other collapse elements
}
function collapseReset()
{
	/*for(var i=1;i<=modulelength;i++)
	{
		changingcheckbox(i);
	}*/

}

function exitCloseYes() {

  //  var sideBarController = angular.element(document.querySelector(".sideBar"));
 //   sideBarController.scope().sb.navigateToLoginPage();
		window.close();
	self.close();
	parent.window.close();	  
}

function exitCloseYesWBT() {

	window.open('', '_self', ''); window.close();
		//console.log("end called")
	scorm.quit();
	window.open('','_self','');
	window.opener=self;
	window.close();
	window.parent.close();
	
}

function highlightNavCircle(name) {

    var footerController = angular.element(document.querySelector(".footer"));
    var navCircle = footerController.scope().fb.globalVariableService.navCircle;
    var found;
    for (var i = 0; i < navCircle.length; i++) {
        if (navCircle[i] == name) {
            found = i;
            break;
        }
    }
    if (found !== undefined) {
        angular.element(document.querySelectorAll(".navCircle")).removeClass("navCircleHighlight");
        if (navCircle.length > 12) {
            found = Math.ceil(12 * (found / (navCircle.length - 1)));
        }
       setTimeout(function () {
           angular.element(document.querySelectorAll(".navCircle")[found]).addClass("navCircleHighlight");
       },10)

        


    }
}
function showaudscript(num)
{

    var contentController = angular.element(document.querySelector(".contentArea"));
    var getvalue=contentController.scope().cc.globalVariableService.audscript;
	$("#audscript").html(getvalue[num])

}
function hidePreloader() {
    // Hide preloader using PreloadManager for smooth fade out
    if (typeof PreloadManager !== 'undefined') {
        PreloadManager.hide();
    } else {
        document.getElementById("preloader").style.display = "none";
    }
    if(document.getElementById("captivateFrame"))
    {
        setTimeout(function(){document.getElementById("captivateFrame").contentWindow.cp.cpEISetValue("m_VarHandle.cpCmndGotoFrameAndResume", parseInt(currentCaptivativeFrames));},100)

    }

}

function enablemute(){
	var muteElem = angular.element(document.getElementById("mute"));
	muteElem.removeClass("disabledClass");
	muteElem.attr("title","Learning Aids");

}
function disablemute(){
	var muteElem = angular.element(document.getElementById("mute"));
	muteElem.addClass("disabledClass");
	muteElem.removeAttr("title");
	
}
function disableplapause()
{ 
	var pauseElem = angular.element(document.getElementById("pause"));
	pauseElem.addClass("disabledClass");
	pauseElem.removeAttr("title");
	
	var playElem = angular.element(document.getElementById(play));
	playElem.addClass("disabledClass");
	playElem.removeAttr("title");
}
function enablenextbtn()
{ 
	var nextElem = angular.element(document.getElementById("next"));
		nextElem.removeClass("disabledClass");
	NexPrevAcessiblity_Check()
}
function enablePrevbtn()
{ 
	var PrevElem = angular.element(document.getElementById("prev"));
	PrevElem.removeClass("disabledClass");
	NexPrevAcessiblity_Check()
}
function disablenextbtn()
{ 
	var nextElem = angular.element(document.getElementById("next"));
		nextElem.addClass("disabledClass");
	nextElem.attr("title");
	NexPrevAcessiblity_Check();
}

function enableplapause()
{ 
	var pauseElem = angular.element(document.getElementById("pause"));
	pauseElem.removeClass("disabledClass");
	pauseElem.attr("title","Pause");
	var playElem = angular.element(document.getElementById(play));
	playElem.removeClass("disabledClass");
	playElem.attr("title","Play");
}


// Example usage:


function onPlayVid()
{
    var footerController = angular.element(document.querySelector(".footer"));
    if (!angular.element(document.getElementById("replay")).hasClass("showContent")) {
            angular.element(document.getElementById("pause")).removeClass("hideContent").addClass("showContent");
			angular.element(document.getElementById("play")).removeClass("showContent").addClass("hideContent");
       }
	   if(retake)
	{
	//console.log("****");
		retake=false;
		var vid = document.getElementById("vidArea");

		//vid.pause();

	}
	   var vid1 = document.getElementById("vidArea");
    vid1.volume=volume_change;
	vid1.muted=volume_mute;
}
function volumechange_fun()
{
   // volume_change=
   var vid1 = document.getElementById("vidArea");
  // console.log(vid1.volume);
   volume_change=vid1.volume;
   volume_mute=vid1.muted
}
function onPauseVid()
{
    var footerController = angular.element(document.querySelector(".footer"));
    if (!angular.element(document.getElementById("replay")).hasClass("showContent")) {
          //  angular.element(document.getElementById("play")).removeClass("hideContent").addClass("showContent disabledClass");
			//angular.element(document.getElementById("pause")).removeClass("showContent").addClass("hideContent");
       }
}
function disableNextPrevMenu()
{
	
	disablenextbtn();
	menuEnDisble_fun_firstpage();
	 var prevElem = angular.element(document.getElementById("prev"));
	      prevElem.addClass("disabledClass");
                    prevElem.removeAttr("title")
					NexPrevAcessiblity_Check();
					MainmenuAcessibility();
}
function EnabledNextPrevMenu()
{
	document.getElementById('clickableDiv').classList.remove('disabledClass');
}
async function loadJsonvalue() {
  try {
    const response = await fetch('assets/json/Template.json');
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();

    // First process master values
    processMasterValue1(data);

    // Wait for AfterTemaplateJson to complete (if it's async)
  //  await AfterTemaplateJson(); // make this function async if needed

    // Conditionally show or hide resource list
    

  } catch (error) {
    console.error('There was a problem with the fetch operation:', error);
  }
}

function processMasterValue1(data) {

  window.templateJsonData = data || {};

	
  masterBool = data.master || false;
  AudioVersionEnable = data.AudioVersionEnable || false;
  QuizAttemptLimit = data.QuizAttempt || null;
  MenuName = data.MenuName || "";
  TranscriptName = data.TranscriptName || "";
  ResumeHeader = data.ResumeHeader || "";
  ResumeYES = data.ResumeYES || "";
  ResumeNO = data.ResumeNO || "";
  ResumeTitle = data.ResumeTitle || "";
  Menutitle = data.Menutitle || "";
  LearningAidsTitle = data.LearningAidsTitle || "";
  NextTitle = data.NextTitle || "";
  Prevtitle = data.Prevtitle || "";
  CourseName = data.CourseName || "";
  window.stepText = data.stepText || "";
  PageLevelCourseComplete = data.PageLevelCourseComplete || false;
  VttLanguage = data.VttLanguage || "";
  VttLabel = data.VttLabel || "";
  spanCliContinue=data.spanCliContinue || "";
  ResourceBool=data.Resource || false;
  CertificateEnabled=data.CertificateEnabled || false;
  ResourceEanbled=data.Resource || false;
  lmsStatus=data.lmsStatus || "";
  if(lmsStatus=="Passed/Incomplete")
  {
	 LMSpassed="passed";
	 LMSfailed="incomplete";
	 
  }
  else if(lmsStatus=="Passed/Failed")
  {
	 LMSpassed="passed";
	 LMSfailed="failed";
	 
  }
  else if(lmsStatus=="Completed/Incomplete")
  {
	 LMSpassed="completed";
	 LMSfailed="incomplete";
	 
  } 
  else if(lmsStatus=="Completed/Failed")
  {
	 LMSpassed="completed";
	 LMSfailed="failed";
	 
  } 
	  
  
  setTimeout(function() {
    AfterTemaplateJson();
	if (ResourceBool) {
      generateResourceList(data); // Call function after fetching JSON
    } else {
      document.getElementById("resource1").style.display = "none";
    }
  }, 300);
}
function handleKeydown_Close(event) {
  if (event.key === 'Enter' || event.key === ' ') {
    event.preventDefault(); // Prevent spacebar from scrolling
    backToMainPage(); // Trigger the click event
  }
}

function generateResourceList(data) {
  const resourceContent = document.getElementById("resourceContent");
  resourceContent.innerHTML = ""; // Clear existing content

  // Ensure Resources is always an array
  let resources = data?.ResourceArea?.LearningAids?.Resources;
  if (!Array.isArray(resources)) {
    resources = resources ? [resources] : []; // Convert single object to array or set empty array
  }

 // console.log("Resources:", resources); // Debugging - Check the extracted resources

  // Generate the resource list
  resourceContent.innerHTML = `
	<div class="Navigation_cls_main" onclick="backToMainPage()" 
		 role="button" 
		 tabindex="0" 
		 aria-label="Back to Main Page"
		 onkeydown="handleKeydown_Close(event)">
	  <img id="ResourceClose" src="theme/images/footer-menu/Navclose.png" alt="Close navigation menu" role="presentation">
	</div>
    <div class="leftResourceArea" style="font-size:1.8rem;">
      <ul style="list-style-type: none; padding: 0;">
        ${resources.length > 0 
          ? resources.map(resource => `
            <li style="margin-bottom: 5px;">
              <a style="color:#870D0D" target="_blank" href="${resource.URL}">
                ${resource.Title}
              </a>
            </li>
          `).join('')
          : "<li>No resources available</li>"
        }
      </ul>
    </div>
  `;
}


  

