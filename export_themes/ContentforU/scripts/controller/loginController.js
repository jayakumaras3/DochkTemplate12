/**
 * Created by pradeep on 05-02-2017.
 */
var aristoFramework = window.aristoFramework || {};
var userName, emailName, domElem, str_allCountPage, arr_allCountPage;
(function () {
    /**
     * @ngdoc controller
     * @name aristoFramework.controller:loginController
     * @Description
     * CertificationController will controll the certification page similar to display the name
     * @param {Object} scope - scope injector of angularjs to update the model
     * @param {Object} $location - angular js service for changing the url
     * @param {Object} globalVariableService - common service used across all the controller
     */

    var alltext = [];
    var _contentXMLArray = [];
    var loginController = function (scope, http, $location, globalVariableService, $timeout) {
        this.scope = scope;
        document.body.style.display = "block";
        this.$timeout = $timeout;
        this.userName = "";
        this.emailName = "";
        this.accessCode = "";
        this.loginAttempt = 5;
        this.showPassword = false;
        this.$location = $location;
        this.errorDiv = false;
        this.http = http;
        this.globalVariableService = globalVariableService;
        this.globalVariableService.totalCompletedPage = [];
        this.showLoginPage = true;

    };
    var p = loginController.prototype;
    p.showPwdClick = function () {
        if (this.showPassword == true) {
            document.getElementById("inputPassword").type = "text";
        } else {
            document.getElementById("inputPassword").type = "password";
        }
    };
    p.yesBtnClick = function () {
        this.$location.path("content");
		this.globalVariableService.totalCompletedPage = pageArray;												  
    };
    p.noBtnClick = function () {
        this.globalVariableService.totalCompletedPage = [1];
        this.globalVariableService.setPageCounter(1);
        this.$location.path("content");
    }
    p.returnXml = function (fileName) {
        var self = this;
        this.http({
            method: 'GET',
            url: "userXML/" + fileName + ".xml?" + new Date().getTime()
        }).then(function mySuccess(xml) {
            var obj = {}
            alltext = [];
            obj.textArray = []
            var dom = {};
            if (typeof DOMParser != "undefined") {
                var parser = new DOMParser();
                dom = parser.parseFromString(xml.data, "text/xml");
            } else {
                var doc = new ActiveXObject("Microsoft.XMLDOM");
                doc.async = false;
                dom = doc.loadXML(xml.data);
            }
            domElem = dom
            str_allCountPage = dom.getElementsByTagName("user")[0].childNodes[2].textContent
            arr_allCountPage = str_allCountPage.split(',');
            for (var t = 0; t < arr_allCountPage.length; t++) {
                self.globalVariableService.totalCompletedPage.push(Number(arr_allCountPage[t]));
            }
            self.globalVariableService.setPageCounter(self.globalVariableService.totalCompletedPage[self.globalVariableService.totalCompletedPage.length - 1]);
            if (arr_allCountPage.length !== 1)
            {
                self.showLoginPage = false;
                setTimeout(function () {
                    self.scope.$apply()
                }, 10)
            } else
            {
                self.globalVariableService.totalCompletedPage = [1];
                self.globalVariableService.setPageCounter(1);
                self.$location.path("content");
            }
        }, function errorCallback(response) {
            self.globalVariableService.totalCompletedPage = [1];
            self.globalVariableService.setPageCounter(1);
            self.$location.path("content");
        });


    };
    p.continueClick = function () {
        this.errorDiv = true;
        var emailReg = /^(?=[^@]*[A-Za-z])([a-zA-Z0-9])(([a-zA-Z0-9])*([\._-])?([a-zA-Z0-9]))*@(([a-zA-Z0-9\-])+(\.))+([a-zA-Z]{2,4})+$/i
        var FILENAME = this.emailName.replace(/[^A-Za-z0-9]/g, "");
        if ((this.userName == "") || (this.emailName == "")) {
            this.loginAttempt--;
            this.errorMessage = "Name or Email Field cannot be blank. <br/>";
            (this.loginAttempt > 1) ? this.errorMessage += "You are left with " + this.loginAttempt + " attempts." : this.errorMessage += "You are left with " + this.loginAttempt + " attempt.";
        }  else if (!(this.emailName).match(emailReg)) {

            this.loginAttempt--;
            this.errorMessage = "Email is not valid.</br>";
            (this.loginAttempt > 1) ? this.errorMessage += "You are left with " + this.loginAttempt + " attempts." : this.errorMessage += "You are left with " + this.loginAttempt + " attempt.";

        } /*else if (this.accessCode !== '123') {
            this.loginAttempt--;
            this.errorMessage = " Incorrect Access Code. <br/>";
            (this.loginAttempt > 1) ? this.errorMessage += "You are left with " + this.loginAttempt + " attempts." : this.errorMessage += "You are left with " + this.loginAttempt + " attempt.";
        }*/ else {
            var lblerror = angular.element(document.querySelector("#lblerror"));
            lblerror.removeClass("text-danger").addClass("text-success");
            angular.element(document.querySelector("#error-div")).addClass("error-div");
            userName = this.userName;
            emailName = this.emailName;
            var self = this;
            $.post("create_xml.asp", {
                xmlfile: FILENAME,
                username: userName,
                email: emailName
            }, function (data) {
                //alert("creat")
                /*var obj = $.parseJSON(data);*/
           });
            lblerror[0].innerHTML = "Login successfully";
            alltext = this.returnXml(FILENAME);
        }
        if (this.loginAttempt == 0) {
            document.getElementById("inputName").disabled = true;
            document.getElementById("inputEmail").disabled = true;
           // document.getElementById("inputPassword").disabled = true;
            this.globalVariableService.resetAllValue();
           $('input[type="button"]').attr('disabled', 'disabled');
            this.$timeout(function () {
                $('#loginForm').slideUp('slow').fadeOut(function () {
                    window.location.reload();

                });
            }, 1000);

        }
    };


    loginController.$inject = ['$scope', '$http', '$location', 'globalVariableService', '$timeout'];
    aristoFramework.loginController = loginController;

}());