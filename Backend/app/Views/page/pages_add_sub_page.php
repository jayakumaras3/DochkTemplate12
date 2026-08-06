<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/course_builder/Editor'); ?>">Pages</a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view'); ?>">Main Page</a></li>
				</ol>
			</div>
			<h4 class="page-title">Add Sub Page</h4>
		</div>
	</div>
</div>

<div class="section-body">
	<div class="row">
		<div class="col-6 col-md-6 col-lg-12">
			<div class="card">
				<div class="card-body">
					<form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/addsubpage') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
						<div class="row">
							<div class="form-group col-md-6">
								<label>Sub Page Name</label>
								<input type="text" class="form-control col-md-12" name="page_name" placeholder="Sub Page Name" required />
							</div>
							<div class="form-group col-md-3">
								<label>Sub Page Type</label>
								<select name="type" class="form-control">
									<option value="2" SELECTED>Video</option>
									<option value="1">Articulate</option>
									<option value="5">SCQ Check your understanding</option>
									<option value="6">MCQ Check your understanding</option>
									<option value="4">Quiz</option>
									<option value="3">Html</option>
									<option value="10">Text Only</option>
									<option value="11">Image + Text</option>
									<option value="12">Text + Image</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="form-group  col-md-12 mt-3 mb-1">
								<?php if (isset($coursevalidation)) : ?>
									<div class=col-12 col-sm-4>
										<div class="alert alert-white" role="alert">
											<?= $coursevalidation->listErrors() ?>
										</div>
									</div>
								<?php endif; ?>
								<input type="hidden" name="page_number" value="<?php echo $page_number; ?>">
								<input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
								<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
								<button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light col-md-12" id="submitButton">
									Add Sub Page
								</button>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function validateInput(input) {
		var value = input.value;
		var errorMsg = document.getElementById('errorMsg');
		if (value <= 0) {
			errorMsg.textContent = "Please enter a positive number.";
			input.value = ""; // Clear the input field
		} else {
			errorMsg.textContent = "";
		}
	}
</script>