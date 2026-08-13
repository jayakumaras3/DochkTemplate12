<!-- <style>


	/* Set height of the grid so .sidenav can be 100% (adjust if needed) */
	/* .row.content { */
	/*  min-height: 600px; */
	/* } */

	/* Set gray background color and 100% height */
	.sidenav {
		background-color: #f5f5f5;
	}

	.feedback_form {
		background-color: #fff;
		margin-bottom: 10px;
		border-radius: 5px;
		box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
	}

	.feedback_inside_form {
		padding: 5px;

	}

	.feedback_view {
		background-color: #f5f5f5;
	}

	.individual_feedback_design {
		background-color: #fff;
		border-radius: 5px;
		width: 100%;
		margin-bottom: 10px;
		padding: 5px;
		border: 1px;
		box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
	}

	.individual_feedback_design:hover {
		box-shadow: 0 3px 10px rgb(0 0 0 / 0.5);
	}




	/* On small screens, set height to 'auto' for sidenav and grid */
	@media screen and (max-width: 767px) {
		.sidenav {
			height: auto;
			padding: 15px;
		}

		.row.content {
			height: auto;
		}
	}

	.noDecoration {
		background-color: #f1f1f1;
		color: black;
		padding: 1em 1.5em;
		text-decoration: none;
		width: 270px;
		text-align: left;
		padding: 5px;
		margin: 5px;
		text-wrap: wrap;
	}

	.noDecoration_select {
		background-color: #d8d8d8;
		color: black;
		padding: 1em 1.5em;
		text-decoration: none;
		width: 270px;
		text-align: left;
		padding: 5px;
		margin: 5px;
		text-wrap: wrap;
	}

	#menu_details {
		position: absolute;
		display: none;
		width: 300px;
		text-align: left;

		top: 0px;
		left: 0px;
		right: 0;
		bottom: 0;
		background-color: white;
		border: 1px;
		z-index: 1;
	}

	.main-content {
		/*     min-height: 600px; */
		background-color: #fff;
		z-index: 10;
	}

	.hide_menu {
		position: absolute;
		top: 40px;
		left: -0px;
		width: 100%;
		height: 95%;
		background-color: rgba(0, 0, 0, 0.2);
		z-index: 1;
	}

	.header-design {
		background-color: #727272;
		color: white;
		padding-top: 5px;
	}

	.menu {
		height: 500px;
		overflow: auto;
		padding: 5px;
		background-color: white;
		text-decoration: none;
	}

	.question_bg {
		background-color: #acd8db;
		color: black;
		padding: 20px;
	}

	a:hover {
		text-decoration: none;
	}

	.question_base {
		background-color: #253790;
		color: white;
		border-radius: 5px;
		font-size: 18px;
		margin: 20px;
		padding: 5px;
	}

	.option_container {
		margin: 10px;
		padding: 10px;

	}

	.options_correct {
		border-radius: 5px;
		background-color: #329649;
		color: white;
		padding: 5px;
		margin: 5px;
		width: 90%;
		cursor: pointer;
		font-size: 14px;
	}

	.options_correct:hover {
		background-color: #76ac82;
	}

	.options {
		border-radius: 5px;
		background-color: #2b709b;
		color: white;
		padding: 5px;
		margin: 5px;
		width: 90%;
		cursor: pointer;
		font-size: 14px;
	}

	.options:hover {
		background-color: #6a9ab8;
	}

	.feedback_correct {
		background-color: #62bd5e;
		color: white;
		padding: 5px;
		margin: 5px;
		width: 100%;
		font-size: 14px;
		border-radius: 5px;
		display: none;
	}

	.feedback_wrong {
		background-color: #f56969;
		color: white;
		padding: 5px;
		margin: 5px;
		width: 100%;
		font-size: 14px;
		border-radius: 5px;
		display: none;
	}

	.page_number_design {
		padding-top: 5px;
	}

	.img_circle {
		border-radius: 50%;
		width: 20px;
		height: 20px;
	}

	.feedback_details {
		margin-top: 10px;
		margin-left: 10px;
	}

	.iframe-container {
		position: relative;
		overflow: hidden;
		width: 100%;
		padding-top: 56.25%;
		/* 16:9 Aspect Ratio (divide 9 by 16 = 0.5625) */
	}

	.responsive-iframe {
		position: absolute;
		top: 0;
		left: 0;
		bottom: 0;
		border: 0;
		right: 0;
		width: 100%;
		height: 100%;
	}

	.footer-design {
		position: absolute;
		width: 100%;
		top: 45%;
		padding-bottom: 5px;
	}

	.bottom_border {
		height: 2px;
		background-color: #727272;
	}

	.prev_btn {
		background-image: url(<?php echo base_url('assets/assets/img/Back_N.png'); ?>);
		height: 35px;
		width: 40px;
		background-repeat: no-repeat;
	}

	.prev_btn_container {
		position: absolute;
		top: 50%;
	}

	.next_btn_container {
		position: absolute;
		top: 50%;
		right: 15px;
	}

	.next_btn {
		background-image: url(<?php echo base_url('assets/assets/img/Next_N.png'); ?>);
		height: 35px;
		width: 40px;
		background-repeat: no-repeat;
	}

	.dropbtn {
		background-color: white;
		color: black;
		padding: 2px;
		font-size: 15px;
		border: none;
		cursor: pointer;
	}

	.dropdown {
		position: relative;
		display: inline-block;
	}

	.dropdown-content {
		display: none;
		position: absolute;
		right: 0;
		background-color: #f9f9f9;
		min-width: 160px;
		box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
		z-index: 1;
	}

	.dropdown-content a {
		color: black;
		padding: 12px 16px;
		text-decoration: none;
		display: block;
	}

	.dropdown-content a:hover {
		background-color: #f1f1f1;
	}

	.dropdown:hover .dropdown-content {
		display: block;
	}

	.dropdown:hover .dropbtn {
		background-color: grey;
	}
</style> -->
<?php if (!empty($getAssessmentSettings)) {
	foreach ($getAssessmentSettings as $value) {
		$type = $value['type'];
		// print_r($type."<br/>");
		if ($type == 59) {
			$kyuselectscqdescrip = $value['value'];
		}
		if ($type == 60) {
			$kyuselectmcqdescrip = $value['value'];
		}
		if ($type == 61) {
			$kyusubmit = $value['value'];
		}
		if ($type == 68) {
			$kyupleaseselectanswer = $value['value'];
		}
	}
}
$kyuselectscqdescrip = (isset($kyuselectscqdescrip) && $kyuselectscqdescrip != '') ? $kyuselectscqdescrip : $assessment_scqmcq_sets['59'];
// print_r($assessment_scqmcq_sets['59']);
// exit();
$kyuselectmcqdescrip = (isset($kyuselectmcqdescrip) && $kyuselectmcqdescrip != '') ? $kyuselectmcqdescrip : $assessment_scqmcq_sets['60'];
$kyusubmit = (isset($kyusubmit) && $kyusubmit != '') ? $kyusubmit : $assessment_scqmcq_sets['61'];
$kyupleaseselectanswer = (isset($kyupleaseselectanswer) && $kyupleaseselectanswer != '') ? $kyupleaseselectanswer : $assessment_scqmcq_sets['68'];


$userlevel = session('userlevel');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
?>
<script>
	// Store video time in seconds
	var TimeStore = 0;

	function GetVideoTime() {
		var vid1 = document.getElementById("vidArea");
		if (vid1) {
			var currentTime = vid1.currentTime;
			TimeStore = currentTime; // Update stored time
			return currentTime; // Format and return time
		}
		return ''; // Return null if video element is not found
	}

	// Helper function to format time as MM:SS
	function formatTime(seconds) {
		var mins = Math.floor(seconds / 60);
		var secs = Math.floor(seconds % 60);
		return mins + ":" + (secs < 10 ? "0" : "") + secs;
	}

	// Function to go to the stored time
	function goToSession(timeInSeconds) {

		var vid1 = document.getElementById("vidArea");
		if (vid1) {
			vid1.currentTime = timeInSeconds; // Seek to the stored time
			vid1.play(); // Start playing from that time
			console.log("Jumped to:", formatTime(timeInSeconds));
		}
	}

	// Function to show the current time of the video
	function showCurrentTime() {
		var formattedTime = GetVideoTime();
		if (formattedTime) {
			document.getElementById("currentTimeDisplay").innerText =
				"Current Video Time: " + formattedTime + " (In seconds: " + TimeStore.toFixed(2) + ")";
			console.log("Current Video Time:", formattedTime);
		} else {
			document.getElementById("currentTimeDisplay").innerText =
				"No video is currently playing.";
		}
	}
</script>

<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url($header_link); ?>">Development</a></li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_name; ?> (
				<?php
				$type = $row['type'];
				switch ($type) {
					case 1:
						echo 'Articulate';
						break;
					case 2:
						echo 'Video';
						break;
					case 8:
						echo 'Video Sub Page';
						break;
					case 3:
						echo 'Html';
						break;
					case 4:
						echo 'Quiz';
						break;
					case 5:
						echo 'SCQ';
						break;
					case 6:
						echo 'MCQ';
						break;
					case 9:
						echo 'Audio Version';
						break;
				}

				?>

				)
			</h4>
		</div>
	</div>
</div>
<style>
	.card-body td:nth-child(2) {
		max-width: 200px;
		/* Adjust the max-width value as needed */
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
</style>
<?php $baseloc = '';
$base = base_url();
if ($base == 'http://localhost/Dochek_V3/Dochek_V3') {
	$baseloc = '/Users/pchandran/Sites/dochek_v3/Dochek_V3/';
}
if ($base == 'http://localhost/projects_dochek/') {
	$baseloc = 'D:/wampp/www/projects_dochek/';
}
if ($base == 'https://dochek.com/') {
	$baseloc = '/var/www/html/';
}
if ($base == 'https://www.aristo-tle.com') {
	$baseloc = '/';
}
if ($base == 'https://staging.dochek.com/') {
	$baseloc = '/var/www/html/DOCHEK/';
}
if ($base == 'http://localhost/DOCHEK/') {
	$baseloc = 'D:/wampp/www//DOCHEK/';
}
if ($base == 'http://172.16.2.218/DOCHEK/') {
	$baseloc = '/var/www/DOCHEK/';
}
?>


<!-- <?php if ($row['type'] == 2) { ?>
	<div class="row">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h6>Transcript</h6>
				</div>
				<div class="card-body">
					<?php

					if (isset($pagetraanscript)) {
						// echo "Came ehre";
				
						echo '<table class="table table-sm">';
						echo '<tr>
							<th>Lang</th>
							<th>On</th>
							<th>By</th>
							<th>Edit</th>
							<th>Del</th>
							</tr>';
						foreach ($pagetraanscript as $transcript) {

							echo '<tr><td>';
							if ($transcript['language'] == 1) {
								echo 'English';
							} elseif ($transcript['language'] == 2) {
								echo 'Spanish';
							} elseif ($transcript['language'] == 3) {
								echo 'French';
							}
							echo '</td><td>';
							echo date('d-m-Y', $transcript['createdon']);

							echo '</td><td>';
							echo $transcript['createdby'];
							echo '</td><td>'; ?>
							<form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_edittranscript_view') ?>" method="POST"><?= csrf_field() ?>
								<input type="hidden" name="t_id" value="<?php echo $transcript['t_id'] ?>">
								<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
							</form>
							<?php echo '</td><td>'; ?>
					
					<?php echo '</td></tr>';
						}
						echo '</table>';
					} else {
						echo 'No Files';
					} ?>


				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-body">
					<form class="form-horizontal1" action="<?php echo base_url($transcript_link) ?>" method="POST"><?= csrf_field() ?>
						<div class="form-group col-md-12">
							<input type="hidden" name="page_id" value="<?php echo $page_id ?>">
							<button type="submit" class="btn btn-sm btn-info form-control">Add Transcript</button>
						</div>

					</form>

				</div>
			</div>
		</div>
	</div><br />
<?php } ?> -->
<div class="row">
	<?php
	$sub_page_main = $row['sub_page_main'];
	if ($sub_page_main == 0) {
		?>
		<div class="form-group col-md-4 mb-2">
			<?php if ($prev_page) { ?>
				<form class="form-horizontal"
					action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
					<input type="hidden" name="page_number" value="<?php echo $prev_page[0]['page_number'] ?>">
					<input type="hidden" name="page_id" value="<?php echo $prev_page[0]['page_id'] ?>">
					<input type="hidden" name="page_name" value="<?php echo $prev_page[0]['page_name'] ?>">
					<button type="submit" alt="Next" class="" style="all: unset; cursor: pointer;"><i
							class="mdi mdi-arrow-left-circle-outline font-22"></i></button>

				</form>
			<?php } ?>
		</div>

		<div class="form-group col-md-4 mb-2 ">
			<span class="badge badge-outline-pink">
				<h4 class="mt-0 mb-0">Page <?php echo abs($row['page_number']); ?></h4>
			</span>
		</div>
		<!-- <div class="form-group col-md-2 mb-2 ">
			<a class="nav-link waves-effect waves-light" data-bs-toggle="offcanvas" href="#theme-settings-offcanvas">
				<i class="mdi mdi-clipboard-edit-outline font-22"></i>
			</a>
		</div> -->
		<div class="form-group col-md-4 mb-2 ribbon ribbon-blue float-start">
			<?php if ($next_page) { ?>
				<form class="form-horizontal  float-end mt-0"
					action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
					<input type="hidden" name="page_number" value="<?php echo $next_page[0]['page_number'] ?>">
					<input type="hidden" name="page_id" value="<?php echo $next_page[0]['page_id'] ?>">
					<input type="hidden" name="page_name" value="<?php echo $next_page[0]['page_name'] ?>">
					<button type="submit" alt="Next" style="all: unset; cursor: pointer;"><i
							class="mdi mdi-arrow-right-circle-outline font-22"></i></button>
				</form>
			<?php } else {
				if ($sub_page_main == 0) {
					$nxt_page = $row['page_number'] + 1;
					?>
					<?php
				}
			} ?>
		</div>
		<?php
	} else {
		?>
		<form class="form-horizontal mb-2"
			action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
			<input type="hidden" name="page_id" value="<?php echo $row['sub_page_main']; ?>">
			<input type="hidden" name="page_number" value="<?php echo $row['sub_page_main']; ?>">
			<input type="hidden" name="page_name" value="">
			<button type="submit" class="btn btn-success rounded-pill waves-effect waves-light"><i
					class="mdi mdi-arrow-left-bold-circle-outline"></i> Main</button>
		</form>
		<?php
	}
	?>

</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas" aria-modal="true" role="dialog">
	<div class="offcanvas-body p-3 h-100" data-simplebar="init">
		<div class="simplebar-wrapper" style="margin: -24px;">
			<div class="simplebar-height-auto-observer-wrapper">
				<div class="simplebar-height-auto-observer"></div>
			</div>
			<div class="simplebar-mask">
				<div class="simplebar-offset" style="right: 0px; bottom: 0px;">
					<div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content"
						style="height: 100%; overflow: hidden scroll;">
						<div class="simplebar-content" style="padding: 24px;">
							<form class="form-horizontal"
								action="<?php echo base_url('Task/Task_manage/add_new_task') ?>" method="POST"><?= csrf_field() ?>
								<div class="col-12 ">
									<div class="form-group mb-2">
										<label>Description</label>
										<textarea class="form-control" name="description"></textarea>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group mb-2">
										<label>Assign To</label>
										<select class="form-select" name="assigned_to">
											<?php foreach ($getUserlatestclientCourseByScenario as $users) {
												echo '<option value="' . $users['id_user'];
												echo '">';
												echo $users['username'];
												echo '</option>';
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group mb-2">
										<label>Level</label>
										<select class="form-select" name="unit">
											<?php
											for ($x = 1; $x <= 10; $x++) {
												echo '<option value="' . $x . '">' . $x . '</opiton>';
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group mb-2">
										<label>Due Date</label>
										<input class="form-control" id="due_date" name="due_date" type="date" value="">
									</div>
								</div>
								<div class="col-12">
									<div class="form-group mb-2">
										<label>Priority</label>
										<select class="form-select" name="priority">
											<option value="High">High</option>
											<option value="Medium">Medium</option>
											<option value="Low">Low</option>
										</select>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group mt-2 d-grid">
										<input type="hidden" name="feedbackid"
											value="<?php echo $row['page_number']; ?>">
										<input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
										<input type="hidden" name="type_of_task" value="3">
										<button
											onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();"
											class="btn btn-sm btn-danger btn-block">Assign Task</button>
									</div>
								</div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$subpages_Count = count($sub_page_content);
if ($subpages_Count > 0) {
	echo '<div class="row">';
	foreach ($sub_page_content as $subPages) {

		?>
		<div class="col-3 col-md-3 col-lg-3">
			<form class="form-horizontal mb-2"
				action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
				<input type="hidden" name="page_id" value="<?php echo $subPages['page_id']; ?>">
				<input type="hidden" name="page_name" value="<?php echo $subPages['page_name']; ?>">
				<input type="hidden" name="page_number" value="<?php echo $subPages['page_number']; ?>">
				<button type="submit"
					class="btn btn-outline-dark waves-effect waves-light"><?php echo $subPages['page_number']; ?>
					<?php echo $subPages['page_name']; ?></button>
			</form>
		</div>
		<?php
	}
	echo '</div>';
}
?>
<div class="col-lg-12 col-xl-12">
	<div class="card">
		<div class="card-body">
			<ul class="nav nav-pills nav-fill navtab-bg">
				<li class="nav-item">
					<a href="#course" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
						Course
					</a>
				</li>
				<li class="nav-item">
					<a href="#feedback" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
						Feedback
					</a>
				</li>
				<li class="nav-item">
					<a href="#storyboard" data-bs-toggle="tab" aria-expanded="false" class="nav-link ">
						Storyboard
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane  show active" id="course">
					<!--  review  page -->
					<div class="row">
						<div class="col-12 col-md-12 col-lg-12 mg-t-10">
							<?php
							if ($row['type'] == 3) {
								//HTML Page
								// Path for the iframe content
								$html_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/html/" . $row['page_id'] . "/Screen_01.html";
								$path = FCPATH . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/html/" . $row['page_id'] . "/Screen_01.html"; ?>
								<?php if (file_exists($path)) { ?>
									<div class="iframe-container">
										<iframe class="responsive-iframe" src="<?php echo $html_path; ?>">
											Your browser does not support iframes.
										</iframe>
									</div>
								<?php } else {
									echo '<h4>Page Under Development</h4>';
								} ?>
							<?php } elseif ($row['type'] == 1) {
								//Articulate Page
								// Path for Articulate content
								$articulate_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/Articulate/" . $row['page_id'] . "/story.html";
								$path = FCPATH . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/Articulate/" . $row['page_id'] . "/story.html"; ?>
								<?php if (file_exists($path)) { ?>
									<div class="iframe-container">
										<iframe class="responsive-iframe" src="<?php echo $articulate_path; ?>">
											Your browser does not support iframes.
										</iframe>
									</div>
								<?php } else {
									echo '<h4>Page Under Development</h4>';
								} ?>
							<?php } elseif ($row['type'] == 2 || $row['type'] == 8 || $row['type'] == 9) {
								//Video Page
								// Path for the video
								$Video = isset($pageVideo[0]['filename']) ? $pageVideo[0]['filename'] : '';
								$Vtt = isset($pageVtt[0]['filename']) ? $pageVtt[0]['filename'] : '';
								if ($Video != '') {
									$video_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/video/" . $Video;
								}
								// if ($Vtt != '') {
								$vtt_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/vtt/" . $Vtt;
								// }
								$path = FCPATH . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/video/" . $Video;

								?>
								<?php
								//echo $path; 
								//echo " - ".$row['video_upload'];
								if (file_exists($path) && ($Video != '')) { ?>
									<video src="<?php echo $video_path; ?>" style="width: 100%; height: auto;" id="vidArea"
										controls controlsList="nodownload" disablePictureInPicture>
										<?php if (file_exists($vtt_path) && !empty($Vtt)) { ?>
											<track id="englishTrack" kind="captions" src="<?php echo $vtt_path; ?>" srclang="en"
												label="English" default>
										<?php } ?>
									</video>
								<?php } else {
									echo '<h4>Page Under Development</h4>';
								}
							} elseif ($row['type'] == 5) {
								//CYU Page
								?>

								<div class="question_bg" style="min-height: 600px" ;>
									<div class="question_base">
										<?php echo $question['question']; ?>
									</div>
									<div class="option_container">
										<!-- <i>Select the correct answer, and then click <strong>Submit.</strong></i> -->
										<i><?php echo $kyuselectmcqdescrip; ?></i>
										<form id="radioForm"><?= csrf_field() ?>
											<?php if (isset($question_options)) {
												$count = 1;
												foreach ($question_options as $options) { ?>
													<div class="form-check">

														<?php $correct = $options['truefalse'];
														if ($correct == 1) {
															?>
															<input class="form-check-input" type="radio" name="exampleRadios"
																id="exampleRadios<?php echo $count; ?>" value="feedback_correct">

															<label class="form-check-label options_correct" id="correct" value="1"
																onchange="toggleDiv()" for="exampleRadios<?php echo $count; ?>">

																<?php
														} else {
															?>
																<input class="form-check-input" type="radio" name="exampleRadios"
																	id="exampleRadios<?php echo $count; ?>" value="feedback_wrong">
																<label class="form-check-label options" id="incorrect" value="0"
																	onchange="toggleDiv()" for="exampleRadios<?php echo $count; ?>">
																	<?php
														}
														?>

																<?php echo $options['values']; ?>
															</label>
													</div>
													<?php $count++; ?>
													<?php // echo $options['score']; 
																?>
												<?php }
											} ?>
											<br>
											<button
												class="btn btn-outline-primary waves-effect btn-sm waves-light"><?php echo $kyusubmit; ?></button></br></br>
										</form>
										<div id="correct_feedback"
											style="color :white;background:#15a159;padding:5px;border-radius: 5px;">
											<b>Correct Feedback :</b> <?php echo $question['correct']; ?>
										</div><br />
										<div id="incorrect_feedback"
											style="color :white;background:Tomato;padding:5px;border-radius: 5px;"><b>In
												correct : </b><?php echo $question['incorrect']; ?></div>
									</div>
									<?php // echo $question['noAttempts']; 
										?>
								</div>
							<?php } elseif ($row['type'] == 6) {
								//CYU Page
								?>
								<div class="question_bg" style="min-height: 600px">
									<div class="question_base">
										<?php echo $question['question']; ?>
									</div>
									<div class="option_container">
										<i><?php echo $kyuselectmcqdescrip; ?></i>
										<form id="checkboxForm">
											<?php if (isset($question_options)) {
												$count = 1;
												$totalcorrect = 0;
												foreach ($question_options as $options) { ?>
													<div class="form-check">
														<?php
														$correct = $options['truefalse'];
														if ($correct == 1) {
															$totalcorrect++;
															?>
															<input class="form-check-input" type="checkbox" name="checkanswer"
																id="exampleCheckbox<?php echo $count; ?>" value="feedback_correct">

															<label class="form-check-label options"
																for="exampleCheckbox<?php echo $count; ?>">

																<?php
														} else {
															?>
																<input class="form-check-input" type="checkbox" name="checkanswer"
																	id="exampleCheckbox<?php echo $count; ?>" value="feedback_wrong">
																<label class="form-check-label options_correct"
																	for="exampleCheckbox<?php echo $count; ?>">
																	<?php
														}
														?>
																<?php echo $options['values']; ?>
															</label>
													</div>
													<?php $count++; ?>

												<?php } ?>
												<input type="hidden" id="totalcorrect" name="totalcorrect2"
													value="<?php echo $totalcorrect; ?>">
											<?php } ?>
											<br>
											<button
												class="btn btn-outline-primary waves-effect btn-sm waves-light"><?php echo $kyusubmit; ?></button><br><br>
										</form>
										<div id="correct_feedback"
											style="color :white;background:#15a159 ;padding:5px;border-radius: 5px;">
											<b>Correct Feedback :</b> <?php echo $question['correct']; ?>
										</div><br />
										<div id="incorrect_feedback"
											style="color :white;background:Tomato;padding:5px;border-radius: 5px;"><b>In
												correct : </b><?php echo $question['incorrect']; ?></div>
									</div>
								</div>

							<?php } ?>


							<?php if ($row['type'] == 1) { ?>
								<div class="row">
									<div class="col-12 col-md-12 col-lg-12 mg-t-10">
										<div class="card">
											<div class="card-body">
												<?php
												$folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $row['createdon'] . 'assets/Articulate/' . $row['page_id'];
												if (!empty($pageArticulate)) {
													echo '<table class="table  table-sm">';
													echo '<tr><th>Articulate Folder</th><th>Lang</th><th>On</th><th>By</th><th>Delete</th></tr>';
													foreach ($pageArticulate as $Articulate) {

														echo '<tr><td>';
														echo $Articulate['folder'];
														echo '</td><td>';
														if ($Articulate['language'] == 1) {
															echo 'English';
														} elseif ($Articulate['language'] == 2) {
															echo 'Spanish';
														} elseif ($Articulate['language'] == 3) {
															echo 'French';
														}
														echo '</td><td>';
														echo date('d-m-Y h:i:s', $Articulate['createdon']);

														echo '</td><td>';
														echo $Articulate['createdby'];
														echo '</td><td>'; ?>
														<?php if (in_array('46', $arrayuserlevel) && $row['status'] != 8) { ?>
															<form class="form-horizontal"
																action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/del_folder'); ?>"
																method="POST"><?= csrf_field() ?>
																<input type="hidden" name="page_id"
																	value="<?php echo $row['page_id'] ?>">
																<input type="hidden" name="folderloc" value="<?php echo $folderloc; ?>">
																<input type="hidden" name="folder_name"
																	value="<?php echo $Articulate['folder']; ?>">
																<button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light"
																	onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
																		class="mdi mdi-trash-can-outline"></span> Delete</button>
															</form>
														<?php } ?>
														<?php echo '</td></tr>';
													}
													echo '</table>';
												} else {
													echo 'No Files';
												}
												?>
											</div>
										</div>
									</div>
									<?php if (empty($pageArticulate)) { ?>
										<?php if ($row['status'] != 8) { ?>
											<div class="col-12 col-md-12 col-lg-12 mg-t-10">
												<div class="card">
													<div class="card-body">
														<div class="form-row">
															<form class="form-horizontal1" id="uploadzipfile"
																enctype="multipart/form-data"><?= csrf_field() ?>
																<div class="form-group col-md-12 mb-2">
																	<label>Select Language</label>
																	<select name="language" class="form-control">
																		<option value="1">English</option>
																		<option value="2">Spanish</option>
																		<option value="3">French</option>
																	</select>
																</div>
																<div class="form-group col-md-12 mb-2">
																	<input type="file" name="zip_file" accept=".ZIP,.zip"
																		required />
																</div>
																<div class="form-group col-md-12 mb-2">
																	<input type="hidden" name="course_id"
																		value="<?php echo $course_id ?>">
																	<input type="hidden" name="page_id"
																		value="<?php echo $page_id ?>">
																	<button type="submit"
																		class="btn btn-outline-success waves-effect btn-sm waves-light form-control"
																		id="uploadButton">Upload Package</button>
																</div>
															</form>
															<div class="progress" style="display:none;">
																<div class="progress-bar" role="progressbar" style="width: 0%;"
																	aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
							<?php } elseif ($row['type'] == 2 || $row['type'] == 8 || $row['type'] == 9) { ?>
								<div class="row">
									<?php if (!empty($pageVideo)) { ?>
										<div class="col-12 col-md-12 col-lg-12">
											<div class="card">
												<div class="card-body">
													<?php
													$folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $row['createdon'] . '/assets/video';
													//  print_r($fileloc);
													if (!empty($pageVideo)) {
														echo '<table class="table  table-sm">';
														echo '<tr><th>Video File</th><th>Lang</th><th>On</th><th>By</th><th>Del</th></tr>';

														foreach ($pageVideo as $video) {

															echo '<tr><td>';
															echo $video['filename'];
															echo '</td><td>';
															if ($video['language'] == 1) {
																echo 'English';
															} elseif ($video['language'] == 2) {
																echo 'Spanish';
															} elseif ($video['language'] == 3) {
																echo 'French';
															}
															echo '</td><td>';
															echo date('d-m-Y h:i:s', $video['createdon']);
															echo '</td><td>';
															echo $video['createdby'];
															echo '</td><td>'; ?>
															<?php if (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel) && $row['status'] != 8) { ?>
																<form class="form-horizontal"
																	action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/del_file'); ?>"
																	method="POST"><?= csrf_field() ?>
																	<input type="hidden" name="page_id"
																		value="<?php echo $row['page_id'] ?>">
																	<input type="hidden" name="fileloc"
																		value="<?php echo $folderloc . '/' . $video['filename']; ?>">
																	<input type="hidden" name="file_name"
																		value="<?php echo $video['filename']; ?>">
																	<button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light"
																		onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
																			class="mdi mdi-trash-can-outline"></span> Delete</button>
																</form>
															<?php } ?>
															<?php echo '</td></tr>';
														}
														echo '</table>';
													} else {
														echo 'No Video Files';
													}
													?>

												</div>
											</div>

										</div>
									<?php } ?>
									<?php if (empty($pageVideo)) { ?>
										<?php if ($row['status'] != 8) { ?>
											<div class="col-12 col-md-12 col-lg-12 mg-t-10">
												<div class="card">

													<div class="card-body">
														<div class="form-row">
															<form class="form-horizontal2" enctype="multipart/form-data"
																action=<?php echo base_url('SCORM/course_builder/scorm_course_pages/uploadvideo'); ?>
																method="post" id="uploadForm"><?= csrf_field() ?>
																<div class="form-group col-md-12 mb-2">
																	<label>Select Language</label>
																	<select name="language" class="form-control">
																		<option value="1">English</option>
																		<option value="2">Spanish</option>
																		<option value="3">French</option>
																	</select>
																</div>
																<div class="form-group col-md-6 mb-2">
																	<input type="file" name="file" accept=".mp4,.MP4" required />
																</div>
																<div class="form-group col-md-12 mb-2">
																	<input type="hidden" name="course_id"
																		value="<?php echo $course_id ?>">
																	<input type="hidden" name="page_id"
																		value="<?php echo $page_id ?>">
																	<button type="submit"
																		class="btn btn-outline-success waves-effect btn-sm waves-light form-control"
																		id="uploadButton">Upload Video</button>
																</div>
																<?php if (isset($promovalidation)): ?>
																	<div class="form-group col-md-12">
																		<div class="alert alert-white" role="alert">
																			<?= $promovalidation->listErrors() ?>
																		</div>
																	</div>
																<?php endif; ?>
															</form>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
									<?php } ?>

								</div>
							<?php } elseif ($row['type'] == 3) { ?>
								<?php
								$folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $row['createdon'] . '/assets/html';
								?>

								<div class="row">
									<div class="col-12 col-md-12 col-lg-12">
										<div class="card">
											<div class="card-body">
												<?php $currentFolder = $folderloc . '/' . $row['page_id']; ?>

												<?php if (is_dir($currentFolder)) { ?>
													<!-- Folder exists: show table + delete option -->
													<table class="table table-sm">
														<tr>
															<th>Folder</th>
															<th>Lang</th>
															<th>On</th>
															<th>By</th>
															<th>Del</th>
														</tr>

														<?php foreach ($pageArticulate as $Articulate) { ?>
															<tr>
																<td><?= $Articulate['page_id'] ?></td>
																<td>
																	<?php
																	if ($Articulate['language'] == 1)
																		echo 'English';
																	elseif ($Articulate['language'] == 2)
																		echo 'Spanish';
																	elseif ($Articulate['language'] == 3)
																		echo 'French';
																	?>
																</td>
																<td><?= date('d-m-Y h:i:s', $Articulate['createdon']) ?></td>
																<td><?= $Articulate['createdby'] ?></td>
																<td>
																	<?php if (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel) && $Articulate['status'] != 8) { ?>
																		<form method="POST"
																			action="<?= base_url('SCORM/course_builder/Scorm_course_pages/del_folder'); ?>">
																			<input type="hidden" name="page_id"
																				value="<?= $Articulate['page_id'] ?>">
																			<input type="hidden" name="folderloc"
																				value="<?= $currentFolder ?>">
																			<input type="hidden" name="folder_name"
																				value="<?= $Articulate['page_id'] ?>">
																			<button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light"
																				onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')">
																				<span class="mdi mdi-trash-can-outline"> Delete</span>
																			</button>
																		</form>
																	<?php } ?>
																</td>
															</tr>
														<?php } ?>
													</table>

												<?php } else { ?>
													<!-- Folder not found: show upload option -->
													<?php if ($row['status'] != 8) { ?>
														<div class="form-row">
															<form class="form-horizontal1" id="uploadhtmlfile"
																enctype="multipart/form-data"><?= csrf_field() ?>
																<div class="form-group col-md-12 mb-2">
																	<label>Select Language</label>
																	<select name="language" class="form-control">
																		<option value="1">English</option>
																		<option value="2">Spanish</option>
																		<option value="3">French</option>
																	</select>
																</div>
																<div class="form-group col-md-12 mb-2">
																	<input type="file" name="zip_file" accept=".ZIP,.zip"
																		required />
																</div>
																<div class="form-group col-md-12 mb-2">
																	<input type="hidden" name="course_id" value="<?= $course_id ?>">
																	<input type="hidden" name="page_id" value="<?= $page_id ?>">
																	<button type="submit" class="btn btn-sm btn-success form-control"
																		id="uploadButton">
																		Upload HTML Zip Package
																	</button>
																</div>
															</form>
															<div class="progress" style="display:none;">
																<div class="progress-bar" role="progressbar" style="width: 0%;"
																	aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
															</div>
														</div>
													<?php } ?>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>


							<?php } ?>


							<?php if ($row['type'] == 2 || $row['type'] == 8 || $row['type'] == 9) { ?>

								<div class="row">
									<div class="col-12 col-md-12 col-lg-12">
										<div class="card">
											<div class="card-body">
												<?php
												$fileloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $row['createdon'] . '/assets/vtt';
												//  print_r($fileloc);
												if (!empty($pageVtt)) {
													echo '<table class="table  table-sm">';
													echo '<tr><th>VTT File</th><th>Lang</th><th>On</th><th>By</th><th>Del</th></tr>';

													foreach ($pageVtt as $vtt) {

														echo '<tr><td>';
														echo $vtt['filename'];
														echo '</td><td>';
														if ($vtt['language'] == 1) {
															echo 'English';
														} elseif ($vtt['language'] == 2) {
															echo 'Spanish';
														} elseif ($vtt['language'] == 3) {
															echo 'French';
														}
														echo '</td><td>';
														echo date('d-m-Y h:i:s', $vtt['createdon']);
														echo '</td><td>';
														echo $vtt['createdby'];
														echo '</td><td>'; ?>
														<?php if ((in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel)) && $row['status'] != 8) { ?>
															<form class="form-horizontal"
																action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/del_file'); ?>"
																method="POST"><?= csrf_field() ?>
																<input type="hidden" name="page_id"
																	value="<?php echo $row['page_id'] ?>">
																<input type="hidden" name="fileloc"
																	value="<?php echo $fileloc . '/' . $vtt['filename']; ?>">
																<input type="hidden" name="file_name"
																	value="<?php echo $vtt['filename']; ?>">
																<button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light"
																	onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
																		class="mdi mdi-trash-can-outline"></span> Delete</button>
															</form>
														<?php } ?>
														<?php echo '</td></tr>';
													}

													echo '</table>';
												} else {
													if ($row['status'] != 8) { ?>

														<div class="form-row">
															<p style='color:red;'>Note: The file name should follow the format En_&lt;video name&gt; (e.g., En_en_3.vtt).</p>

															<form class="form-horizontal2" enctype="multipart/form-data"
																action=<?php echo base_url('SCORM/course_builder/scorm_course_pages/uploadvtt'); ?>
																method="post" id="uploadForm"><?= csrf_field() ?>
																<div class="form-group col-md-12 mb-2">
																	<label>Select Language</label>
																	<select name="language" class="form-control">
																		<option value="1">English</option>
																		<option value="2">Spanish</option>
																		<option value="3">French</option>
																	</select>
																</div>
																<div class="form-group col-md-6 mb-2">
																	<input type="file" name="file" accept=".vtt,.VTT" required />
																</div>
																<div class="form-group col-md-12 mb-2">
																	<input type="hidden" name="course_id"
																		value="<?php echo $course_id ?>">
																	<input type="hidden" name="page_id"
																		value="<?php echo $page_id ?>">
																	<button type="submit"
																		class="btn btn-outline-success waves-effect btn-sm waves-light form-control"
																		id="uploadButton">Upload VTT</button>
																</div>
																<?php if (isset($promovalidation)): ?>
																	<div class="form-group col-md-12">
																		<div class="alert alert-white" role="alert">
																			<?= $promovalidation->listErrors() ?>
																		</div>
																	</div>
																<?php endif; ?>
															</form>
														</div>

													<?php }
												}
												?>

											</div>
										</div>
									</div>
								</div>
							<?php } ?>

							<div class="row">
								<div class="card">
									<div class="card-body">

										<?php $button_name = '';
										$button_name_reject = '';
										$status = 0;
										if ($row['status'] == 6 && (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel))) { // Developer
											$status = 7;
											$button_name = 'Dev Completed';
										} elseif ($row['status'] == 7 && (in_array('67', $arrayuserlevel) || in_array('46', $arrayuserlevel))) { // QA
											$status = 8;
											$button_name = 'QA Approved';
											$status_reject = 6;
											$button_name_reject = 'Reject QA Approved';
										} elseif ($row['status'] == 8 && (in_array('4', $arrayuserlevel))) { // PM
											$status = 6;
											$button_name = 'Reopen';
										} ?>
										<div class="row">
											<?php if ($status == 6 || $status == 7 || $status == 8) { ?>
												<div class="col-4 col-md-4 col-lg-4 mg-t-2">
													<form class="form-horizontal"
														action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/update_status') ?>"
														method="POST"><?= csrf_field() ?>
														<div class="form-group col-md-12 mb-1">
															<?php if (isset($coursevalidation)): ?>
																<div class=col-12 col-sm-4>
																	<div class="alert alert-white" role="alert">
																		<?= $coursevalidation->listErrors() ?>
																	</div>
																</div>
															<?php endif; ?>
															<input type="hidden" name="status"
																value="<?php echo $status ?>">
															<input type="hidden" name="course_id"
																value="<?php echo $course_id ?>">
															<input type="hidden" name="page_id"
																value="<?php echo $page_id ?>">
															<button
																class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><?php echo $button_name ?></button>
														</div>
													</form>
												</div>
											<?php } ?>
											<?php if ($status == 8) { ?>
												<div class="col-4 col-md-4 col-lg-4 mg-t-2">
													<form class="form-horizontal"
														action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/update_status') ?>"
														method="POST"><?= csrf_field() ?>
														<div class="form-group col-md-12 mb-1">
															<?php if (isset($coursevalidation)): ?>
																<div class=col-12 col-sm-4>
																	<div class="alert alert-white" role="alert">
																		<?= $coursevalidation->listErrors() ?>
																	</div>
																</div>
															<?php endif; ?>
															<input type="hidden" name="status"
																value="<?php echo $status_reject ?>">
															<input type="hidden" name="course_id"
																value="<?php echo $course_id ?>">
															<input type="hidden" name="page_id"
																value="<?php echo $page_id ?>">
															<button
																class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3"><?php echo $button_name_reject ?></button>
															<!-- <input type="hidden" name="status" value="1"> -->
														</div>
													</form>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>

						</div>
						<!-- <div class="col-4 col-md-4 col-lg-4 mg-t-10">
							<iframe id="Iframe" scrolling="no" frameBorder="0" width="100%" height="100%" src="<?php echo base_url('SCORM/Course_builder/review_course/launcher3/' . $course_id . '/' . $page_id) ?>" title="feedback"></iframe>


						</div> -->


					</div>


				</div>
				<div class="tab-pane" id="feedback">
					<?php if (!empty($feedback)): ?>

						<table class="table dt-responsive nowrap w-100">
							<thead>
								<tr>
									<th class="center">#</th>
									<th>Stage</th>
									<th>Time</th>
									<th>Feedback</th>
									<th>Status</th>
									<th>Creator</th>
									<th>On</th>
									<th>Reply</th>
								</tr>
							</thead>
							<tbody>
								<?php $j = 0;
								foreach ($feedback as $feedback_details) {

									$j = $j + 1; ?>
									<tr>
										<td class="center"><?php echo $j ?></td>
										<td><?php
										$stage = $feedback_details['stage'];
										switch ($stage) {
											case 0:
												echo '-';
												break;
											case 3:
												echo 'Alp';
												break;
											case 4:
												echo 'Alp 2 ';
												break;
											case 5:
												echo 'Bet';
												break;
											case 6:
												echo 'Bet 2';
												break;
											case 7:
												echo 'Gam';
												break;
											case 8:
												echo 'Gam';
												break;
										}
										?></td>
										<td><?php echo $feedback_details['videotime'] ?></td>
										<td><b>Comment :</b> <?php echo $feedback_details['feedback'] ?>

											<div><?php if (isset($replies[$feedback_details['feedbackid']])) {
												foreach ($replies[$feedback_details['feedbackid']] as $reply) { ?>
														<b> R : </b> <?php echo $reply['feedback_replies'] ?><br />
													<?php }
											} ?>
											</div>
										</td>
										<td><?php $status = $feedback_details['status'];
										switch ($status) {
											case 1:
												echo 'New';
												break;
											case 2:
												echo 'Replied';
												break;
											case 3:
												echo 'Fixed';
												break;
											case 4:
												echo 'QA Ver';
												break;
											case 5:
												echo 'ReOpen';
												break;
											case 6:
												echo 'Closed';
												break;
										}
										?></td>
										<td><?php echo $feedback_details['fname'] ?></td>
										<td><?php echo date('m/d', $feedback_details['createdon']); ?></td>


										<td>
											<form class="form-horizontal"
												action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedbackReplies') ?>"
												method="POST"><?= csrf_field() ?>
												<input type="hidden" name="feedbackid"
													value="<?php echo $feedback_details['feedbackid'] ?>">
												<input type="hidden" name="typeofpage" value="1">
												<button class="btn btn-outline-primary waves-effect btn-xs waves-light"><i class="mdi mdi-information-outline"></i></button>
											</form>
										</td>
										<!-- <td></td> -->
										<?php
								} ?>
								</tr>
							</tbody>
						</table>



					<?php endif; ?>
				</div>
				<div class="tab-pane" id="storyboard">
					<table class="table dt-responsive wrap w">
						<thead>
							<tr>
								<th>Audio</th>
								<th>On Screen</th>
								<th>Production Notes</th>
							</tr>
						</thead>
						<tbody>
							<?php $j = 0;
							foreach ($page_content as $eachpagesDetails) {
								$j = $j + 1;
								?>
								<tr>
									<td><?php echo $eachpagesDetails['audio'] ?></td>
									<td><?php echo $eachpagesDetails['on_screen_text'] ?></td>
									<td><?php echo $eachpagesDetails['production_notes'] ?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>




<script>
	$('.fa').show();

	$('#uploadzipfile').on('submit', function (event) {
		event.preventDefault();

		var dataString = new FormData($('#uploadzipfile')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('SCORM/course_builder/Scorm_course_pages/uploadZipfile') ?>',
				type: "POST",
				data: dataString,
				processData: false,
				contentType: false,
				beforeSend: function () {
					// Show progress bar
					$(".progress").show();
				},
				success: function (data) {
					// console.log('Server Response:', data);
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);

					// console.log(obj);

					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						// console.log('inside on condition');
						location.reload();
						alert('File Uploaded Successfully');
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}
				},
				error: function (xhr, textStatus, errorThrown) {
					// console.log('request failed');
				},
				complete: function () {
					// Hide progress bar after completion
					$(".progress").hide();
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function (evt) {
						// Update progress bar
						if (evt.lengthComputable) {
							var percentComplete = (evt.loaded / evt.total) * 100;
							$(".progress-bar").width(percentComplete + '%');
							$(".progress-bar").html(percentComplete.toFixed(2) + '%');
						}
					}, false);
					return xhr;
				}
			});

		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}
	});
</script>
<script>
	$('.fa').show();

	$('#uploadhtmlfile').on('submit', function (event) {
		event.preventDefault();

		var dataString = new FormData($('#uploadhtmlfile')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('SCORM/course_builder/Scorm_course_pages/uploadHTML') ?>',
				type: "POST",
				data: dataString,
				processData: false,
				contentType: false,
				beforeSend: function () {
					// Show progress bar
					$(".progress").show();
				},
				success: function (data) {
					// console.log('Server Response:', data);
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);

					// console.log(obj);

					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						// console.log('inside on condition');
						location.reload();
						alert('File Uploaded Successfully');
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}
				},
				error: function (xhr, textStatus, errorThrown) {
					// console.log('request failed');
				},
				complete: function () {
					// Hide progress bar after completion
					$(".progress").hide();
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function (evt) {
						// Update progress bar
						if (evt.lengthComputable) {
							var percentComplete = (evt.loaded / evt.total) * 100;
							$(".progress-bar").width(percentComplete + '%');
							$(".progress-bar").html(percentComplete.toFixed(2) + '%');
						}
					}, false);
					return xhr;
				}
			});

		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}
	});
</script>
<script>
	function checkImageDimensions() {
		const input = document.getElementById('imageInput');
		const file = input.files[0];

		if (file) {
			const img = new Image();

			img.onload = function () {
				const width = this.width;
				const height = this.height;

				// Set your desired dimensions
				const desiredWidth = 420;
				const desiredHeight = 236;

				if (width === desiredWidth && height === desiredHeight) {
					// Dimensions are correct
					alert('Image dimensions are correct. You can proceed with the upload.');
				} else {
					// Dimensions are not correct
					alert('Image dimensions are not correct. Please choose an image with dimensions 450x236.');
					// Optionally, you can reset the file input to clear the selected file
					// input.value = '';
				}
			};

			img.src = URL.createObjectURL(file);
		}
	}
</script>

<script>
	$(document).ready(function () {
		$('#addNewfeedback').on('submit', function (event) {

			event.preventDefault();

			var dataString = new FormData($('#addNewfeedback')[0]);

			var vidDuration = parent.GetVideoTime();
			dataString.append("videotime", vidDuration);

			if (typeof FormData !== 'undefined') {
				$.ajax({
					url: '<?php echo base_url('SCORM/Course_builder/review_course/addNewfeedback') ?>',
					type: "POST",
					data: dataString,
					async: false,
					processData: false,
					contentType: false,
					success: function (data) {
						var obj = JSON.parse(data);
						console.log(obj);
						if (obj.status === 'OK') {
							console.log('inside on condition');
							location.reload();
							// $("#result").load("SCORM/Course_builder/page_feedback_view");

						} else {

							alert('error', 'Something Went Wrong! Please contact Site Admin!');
						}

					},
					error: function (xhr, textStatus, errorThrown) {
						console.log('request failed');
					}
				})
			} else {
				message("Your Browser Don't support FormData API! Use IE 10 or Above!");
			}

		});
	});
</script>
<script>
	$(document).on('submit', '.replay_inside_form', function (event) {
		event.preventDefault();

		var form = $(this); // This refers to the dynamically generated form that was submitted
		var dataString = new FormData(form[0]);

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('SCORM/Course_builder/review_course/addreplyfeedback') ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function (data) {
					var obj = JSON.parse(data);
					console.log(obj);

					if (obj.status === 'OK') {
						// You can reload or update the DOM dynamically here if needed
						location.reload(); // Uncomment if you want to reload the page
						// console.log('Reply submitted successfully');
					} else {
						alert('Error: Something went wrong. Please contact Site Admin!');
					}
				},
				error: function (xhr, textStatus, errorThrown) {
					console.log('Request failed');
				}
			});
		} else {
			alert("Your browser doesn't support FormData API! Use IE 10 or above.");
		}
	});
</script>
<script>
	$(document).on('submit', '.delete_feedback', function (event) {
		event.preventDefault();

		var form = $(this); // This refers to the dynamically generated form that was submitted
		var dataString = new FormData(form[0]);

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('SCORM/Course_builder/review_course/delete_feedback') ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function (data) {
					var obj = JSON.parse(data);
					console.log(obj);

					if (obj.status === 'OK') {
						// You can reload or update the DOM dynamically here if needed
						location.reload(); // Uncomment if you want to reload the page
						// console.log('Reply submitted successfully');
					} else {
						alert('Error: Something went wrong. Please contact Site Admin!');
					}
				},
				error: function (xhr, textStatus, errorThrown) {
					console.log('Request failed');
				}
			});
		} else {
			alert("Your browser doesn't support FormData API! Use IE 10 or above.");
		}
	});
</script>
<script>
	$(document).on('submit', '.delete_reply', function (event) {
		event.preventDefault();

		var form = $(this); // This refers to the dynamically generated form that was submitted
		var dataString = new FormData(form[0]);

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('SCORM/Course_builder/review_course/delete_reply') ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function (data) {
					var obj = JSON.parse(data);
					console.log(obj);

					if (obj.status === 'OK') {
						// You can reload or update the DOM dynamically here if needed
						location.reload(); // Uncomment if you want to reload the page
						// console.log('Reply submitted successfully');
					} else {
						alert('Error: Something went wrong. Please contact Site Admin!');
					}
				},
				error: function (xhr, textStatus, errorThrown) {
					console.log('Request failed');
				}
			});
		} else {
			alert("Your browser doesn't support FormData API! Use IE 10 or above.");
		}
	});
</script>
<script>
	document.getElementById('uploadForm').addEventListener('submit', function () {
		var button = document.getElementById('uploadButton');
		button.disabled = true;
		button.innerHTML = 'Uploading...';
	});
</script>
<script>
	document.getElementById('uploadhtmlfile').addEventListener('submit', function () {
		var button = document.getElementById('uploadButton');
		button.disabled = true;
		button.innerHTML = 'Uploading...';
	});
</script>
<script>
	document.getElementById('uploadzipfile').addEventListener('submit', function () {
		var button = document.getElementById('uploadButton');
		button.disabled = true;
		button.innerHTML = 'Uploading...';
	});
</script>
