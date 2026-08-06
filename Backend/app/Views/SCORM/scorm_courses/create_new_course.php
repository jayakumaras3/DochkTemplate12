<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a></li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $sub_header_1; ?></h4>
		</div>
	</div>
</div>
<?php
$userlevel = session()->get('userlevel');
if (empty($userlevel)) {
	header('Location:' . base_url('my_training'));
	exit();
}
$arrayuserlevel = explode(',', $userlevel);
if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
?>
	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST">
						<div class="row mb-3">
							<label for="course_name" class="col-4 col-xl-3 col-form-label">Course Name <span class="text-danger">*</span></label>
							<div class="col-8 col-xl-9">
								<input required type="text" class="form-control col-md-12" name="course_name" placeholder="Enter Course Name" />
							</div>
						</div>
						<div class="row mb-3">
							<label for="course_code" class="col-4 col-xl-3 col-form-label">Course Duration (min)</label>
							<div class="col-8 col-xl-9">
								<input type="number" class="form-control col-md-12" name="duration"  min="0" placeholder="Enter Duration (min)" />
							</div>
						</div>
						<div class="row mb-3">
							<label for="course_code" class="col-4 col-xl-3 col-form-label">Language</label>
							<div class="col-8 col-xl-9">
								<select name="language" class="form-control" placeholder="Language" required>
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
								</select>
							</div>
						</div>
						<div class="row mb-3">
							<label for="course_code" class="col-4 col-xl-3 col-form-label">Course Type</label>
							<div class="col-8 col-xl-9">
								<select name="type" class="form-control">
									<option value="11">Course Builder</option>
									<option value="10">SCORM</option>
								</select>
							</div>
						</div>
				</div>

				<div class="row mb-3">
					<div class="justify-content-end row">
						<div class="col-8 col-xl-9">
							<?php if (isset($coursevalidation)) : ?>
								<div class=col-12 col-sm-4>
									<div class="alert alert-danger" role="alert">
										<?= $coursevalidation->listErrors() ?>
									</div>
								</div>
							<?php endif; ?>
							<input type="hidden" name="project_id" value="0">
							<input type="hidden" name="addprojects" value="1">
							<input type="hidden" name="typeval" value="0">
							<input type="hidden" name="returnUrl" value="<?php echo $returnUrl ?>">
							<input type="hidden" name="sc_cgid" value="<?php echo isset($sc_cgid) ? $sc_cgid : '' ?>">

						</div>
					</div>

					<div class="justify-content-end row">
						<div class="col-8 col-xl-9">
							<button type="submit" id="submitButton"
								class="btn btn-outline-danger btn-xs waves-effect waves-light">
								<?php echo $sub_header_1; ?>
							</button>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	</div>
<?php
}
?>
</div>