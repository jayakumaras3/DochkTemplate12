/**
 * Created by pc3 on 12/20/2016.
 */
var aristoFramework = window.aristoFramework || {};
(function () {
    "use strict";
    /**
     * @ngdoc service
     * @name aristoFramework.service:globalVariableService
     * @Description
     To store the global variable and used across the controller and hide it from plain javascript
     */
    var globalVariableService = function () {
        this.pageCounter = 1;
        this.replaybtnvisible = false;
        this.contentCounter = 0;
        this.modulenumber = 0;
        this.pagescount = [];
		this.toclevel=false;
        this.pagesmodcount = [];
		this.maintopicarray = ["Importance of Data"];
        this.language = "english";
        this.totalCompletedPage = [];
		this.glossarydata="";
        this.toc = {};
        this.startingPointTocCount = 0;
        this.navCircle = [];
        this.nextBtnDisabled = false;
        this.userName = "";
    };

    var p = globalVariableService.prototype;
    p.resetAllValue = function () {
        //this.pageCounter = 1;
        this.contentCounter = 0;
        this.replaybtnvisible = false;
        this.language = "english";
        //this.totalCompletedPage = [];
        this.toc = {};
        this.startingPointTocCount = 0;
        this.navCircle = [];
        this.nextBtnDisabled = false;
        this.userName = "";
    };
    p.getUserName = function () {
        return this.userName;
    };
    p.setUserName = function (name) {
        this.userName = name;
    };
    /**
     * @ngdoc method
     * @name getStartingPointTocCount
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     * To get the starting point TOC and used for displaying the TOC
     */

		p.calling=function(val){
		 
		 this.addCompletePage(val);
		 
	 }
    p.getStartingPointTocCount = function () {
        return this.startingPointTocCount;
    };
    /**
     * @ngdoc method
     * @name getPageCounter
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     * To get the page counter of the template
     */
    p.getPageCounter = function () {
        return this.pageCounter;
    };
    /**
     * @ngdoc method
     * @name getContentCounter
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     * To get the content counter of a template
     */
    p.getContentCounter = function () {
        return this.contentCounter;
    };
    /**
     * @ngdoc method
     * @name setContentCounter
     * @param {number} contentCounter to replace the value of the content counter variable
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     * To get the starting point TOC and used for displaying the TOC
     */
    p.setContentCounter = function (contentCounter) {
        this.contentCounter = contentCounter;
    };
    /**
     * @ngdoc method
     * @name addCompletePage
     * @param {number} pageCounter page number
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     * To check whether the page is already visited or not if not add it to the totalCompletedPage array
     */
    p.addCompletePage = function (pageCounter) {
        var added = false;
        if (pageCounter > this.startingPointTocCount) {
            var index = this.totalCompletedPage.indexOf(pageCounter);
            if (index > -1) {
                this.totalCompletedPage.splice(index, 1);
            }

            // console.log("-------------added", this.totalCompletedPage)
            if (added === false) {
                //  console.log("this.totalCompletedPage from initial >> " + this.totalCompletedPage);


                //console.log("pageCounter");
                //console.log(pageCounter);
                this.totalCompletedPage.push(pageCounter);
			//	getTracking(pageCounter);		 

                var totalCompletedPagestr = this.totalCompletedPage.toString();
				var splitvalue=totalCompletedPagestr.split(",");
				
				var page_arr=pageArray.toString();
				setSuspendString("str1", page_arr);
				// scorm.set("cmi.suspend_data", pageArray)
				//pageVistedList(totalCompletedPagestr);
				//console.log("pagecounter ", page_arr);
				pageVistedList(pageCounter);	
                //console.log("totalCompletedPagestr >> " + totalCompletedPagestr);

                // console.log("this from update >> ", this);

            /*    var FILENAME = emailName.replace(/[^A-Za-z0-9]/g, "");
                // console.log("FILENAME from update >> ", FILENAME);

                $.post("update_xml.asp", {
                    xmlfile: FILENAME,
                    tagName: "visitedpages",
                    tagValue: totalCompletedPagestr
                }, function (data) {


                });

                var totalToc = this.totalCompletedPage;
                var indicatorValue = Math.floor(((totalToc.length) / 12) * 100);
                // console.log("update")
                //  console.log(indicatorValue)
                $.post("update_xml.asp", {
                    xmlfile: FILENAME,
                    tagName: "progress",
                    tagValue: indicatorValue
                }, function (data) {

                });*/
            }
        }
    };
    /**
     * @ngdoc method
     * @name checkCompletedPage
     * @param {number} pageCounter page number
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     * To check whether the page is completed or not
     */
    p.checkCompletedPage = function (pageCounter) {
        var completed = false;
        // console.log(this.totalCompletedPage, pageCounter)
        for (var i = 0; i < this.totalCompletedPage.length - 1; i++) {
            if (this.totalCompletedPage[i] === pageCounter) {
                completed = true;
            }
        }
        //  console.log("---completed", completed)
        return completed
    };
    /**
     * @ngdoc method
     * @name getCompletedPage
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     * To get the completed page of the template
     */
    p.getCompletedPage = function () {
        return this.totalCompletedPage;
    };
    /**
     * @ngdoc method
     * @name setPageCounter
     * @param {number} pageCounter page number
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     * To set the page counter
     */
    p.setPageCounter = function (pageCounter) {
        // console.log("update alert " + pageCounter)
        this.pageCounter = pageCounter;
    };
    /**
     * @ngdoc method
     * @name getTocData
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     *  To get the TOC Data
     */
    p.getTocData = function () {
        return this.toc;
    };
    /**
     * @ngdoc method
     * @name setTocData
     * @methodOf aristoFramework.service:globalVariableService
     * @description
     * To store the TOC Data
     */
    p.setTocData = function (toc) {

        //console.log("##################################################   :");
        //console.log(toc);

        this.toc = toc;
    };
    aristoFramework.globalVariableService = globalVariableService;

}());