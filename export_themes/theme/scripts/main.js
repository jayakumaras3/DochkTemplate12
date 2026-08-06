/**
 * Created by pc3 on 12/31/2016.
 */
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
var NextTitle= "NextT";
var Prevtitle="PrevT";



function nextHighlight() {
	
console.log("next---");
    if (!angular.element(document.getElementById("next")).hasClass("disabledClass")) {
        angular.element(document.getElementById("next")).addClass("nextClassHighlight").removeClass("disabledClass");
    }
    angular.element(document.getElementById("play")).removeClass("showContent").addClass("hideContent");
    angular.element(document.getElementById("pause")).removeClass("showContent").addClass("hideContent");
  //  angular.element(document.getElementById("replay")).removeClass("hideContent").addClass("showContent");

}
function nextHighlightCongrats()
{
    //angular.element(document.getElementById("play")).removeClass("showContent").addClass("hideContent");
   // angular.element(document.getElementById("pause")).removeClass("showContent").addClass("hideContent");
  //  angular.element(document.getElementById("replay")).removeClass("hideContent").addClass("showContent");
}
function showCertificate() {
	
	 localStorage.setItem("Name", scorm.get("cmi.core.student_name"));
	 window.open('assets/template/certification.html', '_blank');
	 
	
}

function __nextHighlightSim() {
    nextHighlight();
}
var tocDataClick = function (data) {
    if (data.parentNode.className !== "disabledClass") {
        var sideBarController = angular.element(document.querySelector(".sideBar"));
        sideBarController.scope().sb.tocDataClick(data);
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
    console.log(this.window.document.getElementById("main_container"))
}

function checkNextContent(counter) {
    var contentController = angular.element(document.querySelector(".contentArea"));
    contentController.scope().cc.checkNextContent(counter);
}

function backToMainPage() {
	pause_str="start";
    angular.element(document.getElementById("resourceArea")).removeClass("showContent").addClass("hideContent");
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
    var splitValue = scorm.get("cmi.suspend_data");
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
    document.getElementById("preloader").style.display = "none";
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
	nextElem.attr("title",NextTitle);
}
function disablenextbtn()
{ 
	var nextElem = angular.element(document.getElementById("next"));
		nextElem.addClass("disabledClass");
	nextElem.attr("title");
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
async function loadJsonvalue() {
  try {
    // Check if data is in localStorage (browser cache)
   /* const cachedData = localStorage.getItem('TemplateData');
    if (cachedData) {
      const data = JSON.parse(cachedData);
      processMasterValue(data);
      return;  // Skip fetch if data is cached
    }*/

    const response = await fetch('assets/json/Template.json');
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();  // Parse the JSON data
   
    processMasterValue1(data);
  } catch (error) {
    console.error('There was a problem with the fetch operation:', error);
  }
}

function processMasterValue1(data) {
	console.log(data.master);
	console.log(data.AudioVersionEnable);
	console.log(data.QuizAttempt);
	console.log(data.MenuName);
	console.log(data.TranscriptName);
	console.log(data.ResumeHeader);
	console.log(data.ResumeYES);
	console.log(data.ResumeNO);
	console.log(data.ResumeTitle);
	console.log(data.Menutitle);
	console.log(data.NextTitle);
	console.log(data.Prevtitle);
	console.log(data.PageLevelCourseComplete)
	
  if (data.master) {
   // console.log('Master value is true');
    masterBool = true;
  } else {
    masterBool = false;
  }
  if (data.AudioVersionEnable) {
   //console.log('Master value is true');
    AudioVersionEnable = true;
  } else {
    AudioVersionEnable = false;
  }
  if (data.QuizAttempt) {
	  QuizAttemptLimit=data.QuizAttempt;
  }
  if (data.MenuName) {
	  MenuName=data.MenuName;
  }
  if (data.TranscriptName) {
	  TranscriptName=data.TranscriptName;
  }
  if (data.ResumeHeader) {
	  ResumeHeader=data.ResumeHeader;
  }
  if (data.ResumeYES) {
	  ResumeYES=data.ResumeYES;
  }
  if (data.ResumeNO) {
	  ResumeNO=data.ResumeNO;
  }
  if (data.ResumeTitle) {
	  ResumeTitle=data.ResumeTitle;
  }
  if (data.Menutitle) {
	  Menutitle=data.Menutitle;
  }
  if (data.NextTitle) {
	  NextTitle=data.NextTitle;
  }
  if (data.Prevtitle) {
	  Prevtitle=data.Prevtitle;
  }
  if (data.PageLevelCourseComplete) {
	  PageLevelCourseComplete=data.PageLevelCourseComplete;
  }
  setTimeout(function() {
  AfterTemaplateJson();
  }, 100);
}
  

