var pageArray = [0,0,0,0,0,0,0,0,0]
/*var dochekArray=["0","240316","240317","240318","240319","240320","240321","240322","240323","240324","240325","240326","240327","240328","240329","240330","240331","240332","240333","240334","240335","240336","240337","240338","240339","240340","240341","240342","240343","240344","240345","240346","240347","240348","240349","240350","240351","240352","240353","240354","240355","240356","240357","240358","240359","240360","240361","240362","240363","240364","240365","240366","240367","240368","240369","240370","240371","240372","240373","240374","240375","240376","240377","240378","240379","240380","240381","240382","240383","240384"]
*/
function QuizpageVistedList() {
	var contentController = angular.element(document.querySelector(".contentArea"));
	var temp=contentController.scope().cc.globalVariableService.pageCounter;
	pageVistedList(temp);
	
}
function pageVistedList(pageValue) {
	
	//console.log("Page visited", pageValue)
	var page_no=curAttempt.toString()+""+pageValue.toString();
	//console.log(Totalpage);
	//console.log(curAttempt);
	//console.log(page_no);
    scorm.set("cmi.core.lesson_location", page_no);
    scorm.save();
}
var completed = 0;

function getTracking(value) {
	//console.log("********************** get tracking calling"+value);
	
	/*if(value==1)
	{
		parent.callpage(Number(dochekArray[value]));
	}
	*/
	//console.log("TotalPageNo6:: "+Totalpage)
		for (var i = 0; i <Totalpage; i++) 
		 {
			 if(value==i)
			 {
				// parent.callpage(Number(dochekArray[value]));
			 }
		 }
	pageArray[value-1] = 1;
	    
    completed = 0;
    for (var i = 0; i <Totalpage; i++) {
        if (pageArray[i] == 1) {			
            completed++;
		//	console.log(completed);
        }
		else{
			pageArray[i] =0;
		}
    }

    if (completed == Totalpage && PageLevelCourseComplete) {
		//enableCertificateButton();
		certificateDate=formatDate();
		setSuspendString("str2", certificateDate);
	//	console.log("**********************************ccccccccccccccccccccccccc");
       scorm.set("cmi.core.lesson_status", LMSpassed);
       scorm.save();
    }
	var page_arr=pageArray.toString();
	setSuspendString("str1", page_arr);
   // scorm.set("cmi.suspend_data", page_arr)
  //  scorm.save();
    //test(scorm.get("cmi.core.lesson_location","currentLocation"))
		//lessonloc = scorm.get("cmi.core.lesson_location");
		//console.log("lessonlocation >> ",lessonloc)

}
