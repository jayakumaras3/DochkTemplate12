<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel = explode(',', $userlevel);

?>

<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn'); ?>">Edit UCN</a></li>
				</ol>
			</div>
			<h4 class="page-title">Project Courses</h4>
		</div>
	</div>
</div>
<?php if ($project_data[0]['status'] != '4') { ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
						<div class="row">
							<div class="col-lg-3">
								<div class="mb-3">
									<label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
									<input required type="text" class="form-control col-md-12" name="course_name" placeholder="Enter Course Name" />
								</div>
							</div>
							<div class="col-lg-3">
								<div class="mb-3">
									<label for="course_code" class="form-label">Course Duration (min)</label>
									<input type="text" class="form-control col-md-12" name="duration" placeholder="Enter Duration (min)" min="0" />
								</div>
							</div>
							<div class="col-lg-3">
								<div class="mb-3">
									<label for="course_code" class="form-label">Language</label>
									<select name="language" class="form-control">
										<option value="English">English</option>
										<option value="Spanish">Spanish</option>
										<option value="French">French</option>
										<option value="Russian">Russian</option>
										<option value="Portuguese">Portuguese</option>
										<option value="Bahasa">Bahasa</option>
										<option value="Arabic">Arabic</option>
										<option value="German">German</option>
										<option value="Italian">Italian</option>
										<option value="Japanese">Japanese</option>
										<option value="Korean">Korean</option>
										<option value="Turkish">Turkish</option>
										<option value="Swedish">Swedish</option>
										<option value="Dutch">Dutch</option>
										<option value="Kazakh">Kazakh</option>
										<option value="Simplified Chinese">Simplified Chinese</option>
										<option value="Traditional Chinese">Traditional Chinese</option>
										<option value="Vietnamese">Vietnamese</option>
										<option value="Bahasa Malayasian">Bahasa Malaysian</option>
										<option value="Bahasa Indonesian">Bahasa Indonesian</option>
										<option value="Khmer">Khmer</option>
										<option value="Czech">Czech</option>
										<option value="Polish">Polish</option>
										<option value="Macedonian">Macedonian</option>
									</select>
									<!-- <input placeholder="Enter Language" type="text" class="form-control col-md-12" name="language" value="English" /> -->
								</div>
							</div>
							<div class="col-lg-3">
								<div class="mb-3">
									<label for="course_code" class="form-label">Course Type</label>
									<select name="type" class="form-control" required>
										<option value="">--Select Course Type--</option>
										<option value="10" selected>SCORM</option>
										<option value="11">Course Builder</option>
										<!-- <option value="5">AR/VR/Sim</option> -->

									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="justify-content-end row">
								<div class="col-8 col-xl-9">
									<?php if (isset($coursevalidation)) : ?>
										<div class=col-12 col-sm-4>
											<div class="alert alert-danger" role="alert">
												<?= $coursevalidation->listErrors() ?>
											</div>
										</div>
									<?php endif; ?>
									<input type="hidden" name="project_id" value="<?php echo $projectid; ?>">
									<input type="hidden" name="addprojects" value="1">
									<input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="text-sm-end mt-2 mt-sm-0">
									<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
										Add New Course
									</button>
								</div>
							</div> <!-- end col -->
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php if (in_array('6', $arrayuserlevel)) { ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body">
						<form class="form-horizontal" action="<?php echo base_url('SCORM/scorm_courses/link_course_project') ?>" method="POST"><?= csrf_field() ?>
							<div class="row">
								<div class="col-lg-6">
									<div class="mb-3">
										<label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
										<select class="form-select" name="course_id">
											<?php
											foreach ($coursesDetails as $courses) {
												echo '<option value="' . $courses['scourse_id'] . '">' . $courses['course_name'] . '</>';
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="mb-3 mt-3">

										<input type="hidden" name="project_id" value="<?php echo $projectid; ?>">
										<input type="hidden" name="client" value="<?php echo $project_data[0]['client']; ?>">
										<button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
											Link Course to Project
										</button>

									</div>
								</div> <!-- end col -->
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="alternative-page-datatable" class="table table-sm table-striped">

						<thead>
							<tr>
								<th>#</th>
								<th>C ID</th>
								<th>Course Name</th>
								<th>Language</th>
								<th>Type</th>
								<th>Mode</th>
								<th>Reviewers</th>
								<!-- <th>Master</th> -->
								<th>View</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$j = 0;
							foreach ($courses_by_project as $data) {
								$j++;
							?>
								<tr>
									<td><?php echo $j; ?></td>
									<td><?php echo $data['scourse_id']; ?></td>
									<td><?php echo $data['course_name']; ?></td>
									<td><?php echo $data['language']; ?></td>
									<td>
										<?php $type = $data['type'];
										// echo $type;
										switch ($type) {
											case 5:
												echo 'AR/VR/Sim';
												break;
											case 10:
												echo 'SCORM';
												break;
											case 11:
												echo 'Course Builder';
												break;
										}
										?>
									</td>
									<td>
										<?php
										$status = $data['mode'];
										// echo $status;
										switch ($status) {
											case 1:
												echo 'Development';
												break;
											case 2:
												echo 'Live';
												break;
											case 3:
												echo 'Alpha Rev';
												break;
											case 4:
												echo 'Alpha Rev2';
												break;
											case 5:
												echo 'Beta Rev';
												break;
											case 6:
												echo 'Beta Rev2';
												break;
											case 7:
												echo 'Gamma Rev';
												break;
											case 8:
												echo 'Gamma Rev2';
												break;
										}
										?>
									</td>
									<td>
										<form action="<?php echo base_url('Project_Manage/PM_ucn/team') ?>" method="POST"><?= csrf_field() ?>
											<input type="hidden" name="scourse_id" value="<?php echo $data['scourse_id']; ?>">
											<input type="hidden" name="project_id" value="<?php echo $projectid; ?>">
											<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
												<span class="mdi mdi-magnify"></span></button>
										</form>
									</td>
									<?php if ($project_data[0]['status'] != '4') { ?>
										<!-- <td>
											<form action="<?php echo base_url('Task/Task_master/task_master_pm') ?>" method="POST"><?= csrf_field() ?>
												<input type="hidden" name="status" value="0">
												<input type="hidden" name="scourse_id" value="<?php echo $data['scourse_id']; ?>">
												<input type="hidden" name="course_name" value="<?php echo $data['course_name']; ?>">
												<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
													<span class="mdi mdi-clipboard-check-outline font-20"></span></button>
											</form>
										</td> -->
									<?php } else { ?>
										<td>Project Closed</td>
									<?php } ?>
									<td>

										<?php // if ($status != 0) { 
										?>
										<form action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
											<input type="hidden" name="detail_type" value="1">
											<input type="hidden" name="tab" value="1">
											<input type="hidden" name="crid" value="<?php echo $data['scourse_id']; ?>">
											<button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light">
												<span class="mdi mdi-pencil-outline"></span></button>
										</form>

										<?php
										//}
										?>
									</td>
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