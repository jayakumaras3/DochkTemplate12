<!doctype html>
<html lang="en-au">
    <head>
        <title>Gantt Graph</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" >
        <link rel="stylesheet" href="<?php echo base_url(); ?>/public/Gantt/css/style.css" />
		<link rel="shortcut icon" href="<?php echo base_url(); ?>public/Landing/images/favicon.ico">
		<style type="text/css">
			body {
				font-family: Helvetica, Arial, sans-serif;
				font-size: 13px;
				padding: 0 0 50px 0;
			}
			.contain {
				width: 100%;
				margin: 0 auto;
			}
			h1 {
				margin: 40px 0 20px 0;
			}
			h2 {
				font-size: 1.5em;
				padding-bottom: 3px;
				border-bottom: 1px solid #DDD;
				margin-top: 5px;
				margin-bottom: 5px;
			}
			table th:first-child {
				width: 150px;
			}
		</style>
    </head>
    <body>
	<img src="" class="img-rectangular img-thumbnail" />
		<div class="contain">
			<p><b>Project Name :</b> <?php echo isset($dealtimelineData['0']['projectname'])?$dealtimelineData['0']['projectname']:'' ?><p>
			<?php //for($i=1;$i<=count($headerdata);$i++){ ?>
				<div class="gantt"></div>
				<?php // } ?>
        </div>

    </body>
	
	<!-- jQuery -->
	<script src="<?php echo base_url(); ?>/public/css/vendors/jquery/dist/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script src="<?php echo base_url(); ?>/public/Gantt/js/jquery.fn.gantt.js"></script>
	
	<!--<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tooltip.js"></script>
	<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-popover.js"></script>
	<script src="http://taitems.github.com/UX-Lab/core/js/prettify.js"></script>-->
    <script>

		$(function() {
			
			var x = [];
			var passedArray = <?php echo json_encode($dealtimelineData); ?>;
			for (var i = 0; i < passedArray.length; i++) {
				// var start_date = new Date(passedArray[i]['start_date']).getTime();
				// var end_date = new Date(passedArray[i]['end_date']).getTime();
				var start_date = new Date(passedArray[i]['start_date']);
				var sd = start_date.setDate(start_date.getDate()-1);
				var end_date = new Date(passedArray[i]['end_date']);
				var ed = end_date.setDate(end_date.getDate()-1);
				/*console.log(passedArray[i]['start_date']);
				console.log(start_date);
				console.log(passedArray[i]['end_date']);*/
				console.log(passedArray[i]['header']);

				if(passedArray[i]['itemtypename'] == 'Client'){
					var customColor	= "ganttGreen";
				}else if(passedArray[i]['itemtypename'] == 'TQ'){
					var customColor	= "ganttOrange";
				}
				const date = new Date(passedArray[i]['start_date']);
				const astart_date = date.toDateString();

				const edate = new Date(passedArray[i]['end_date']);
				const eend_date = edate.toDateString();
				var ev = [{
					name:'<b style="color:red">'+passedArray[i]['item_description'],
					desc: ' '+passedArray[i]['itemtypename'],
					
					//header: passedArray[i]['header'],
					values: [{ 
						from: "/Date("+sd+")/",
						to: "/Date("+ed+")/",
						label: passedArray[i]['completion']+'%', 
						customClass: customColor,
						dataObj: passedArray[i]['item_description']+'\nStart date : '+astart_date+'\nEnd date : '+eend_date+'\nCompletion :'+passedArray[i]['completion']
						
					}]
					//console.log(values.);
					//on: new Date(passedArray[i]['start_date']),
				}]
				x.push(ev[0]);
			}
			console.log(x[0]['desc']);

			$(".gantt").gantt({
				source: x,
				navigate: "scroll",
				scale: "days",
				maxScale: "months",
				minScale: "weeks",
				itemsPerPage: 200,
				onItemClick: function(data) {
					alert(data);
					
				},
				/*onAddClick: function(dt, rowId) {
					alert("Empty space clicked - add an item!");
				},
				onRender: function() {
					if (window.console && typeof console.log === "function") {
						console.log("chart rendered");
					}
				}*/
				
			});
			
		});
		
    </script>
	
</html>