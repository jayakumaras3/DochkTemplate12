//creating shortcut for less verbose code
var scorm = pipwerks.SCORM;


function init(){
	//Specify SCORM 1.2:
	scorm.version = "1.2";
	
	show("Initializing course.");
	
	var callSucceeded = scorm.init();
	
	show("Call succeeded? " +callSucceeded);
}


function send(){

	var field = document.getElementById("userText"),
		value = "Placeholder text";
	
	if(field.value !== null && field.value !== ""){
		value = field.value;
	}
	
	set('cmi.suspend_data', value);

}


function set(param, value){

	show("Sending: '" +value +"'");

	var callSucceeded = scorm.set(param, value);

	show("Call succeeded? " +callSucceeded);

}


function get(param){

	var value = scorm.get(param);
	//var value='1,1,0';
	show("Received: '" +value +"'");
	return value;
}


function complete(){

	show("Setting course status to 'completed'.");

	var callSucceeded = scorm.set("cmi.core.lesson_status", "completed");

	show("Call succeeded? " +callSucceeded);

}


function end(){

	show("Terminating connection.");

	var callSucceeded = scorm.quit();

	show("Call succeeded? " +callSucceeded);

}


function show(msg){

	var debugText = document.getElementById("debugText");
	if(debugText){
		debugText.innerHTML += msg +"<br/>";
	}

	//Can also show data using pipwerks.UTILS.trace
	pipwerks.UTILS.trace(msg);


}
