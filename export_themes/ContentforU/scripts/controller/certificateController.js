var aristoFramework = window.aristoFramework || {};

(function () {
    /**
     * @ngdoc controller
     * @name aristoFramework.controller:certificateController
     * @Description
     * CertificationController will controll the certification page similar to display the name
     * @param {Object} scope - scope injector of angularjs to update the model
     */
    var certificateController = function (scope,$location) {
        this.scope = scope;
        document.body.style.display="block";
        this.userName = userName;
        if(!userName)
        {

            $location.path("")
        }
	
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!

        var yyyy = today.getFullYear();
        if(dd<10){
            dd='0'+dd;
        }
        if(mm<10){
            mm='0'+mm;
        }
        this.date= dd  + "/" + mm  + "/" + yyyy;
window.print()

    };
    var p = certificateController.prototype;



    certificateController.$inject = ['$scope','$location'];
    aristoFramework.certificateController = certificateController;

}());