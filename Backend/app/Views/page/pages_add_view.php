<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/course_builder/Editor'); ?>">Pages</a></li>

				</ol>
			</div>
			<h4 class="page-title">Create New Page</h4>
		</div>
	</div>
</div>

<div class="section-body">
	<div class="row">
		<div class="col-6 col-md-6 col-lg-12">
			<div class="card">
				<div class="card-body">
					<form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/addpage') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
						<div class="row">
							<div class="form-group col-md-6">
								<label>Page Name</label>
								<input type="text" class="form-control col-md-12" name="page_name" placeholder="Page Name" required />
							</div>
							<div class="form-group col-md-2">
								<label>Page Type</label>
								<select name="type" class="form-control">
									<option value="2" SELECTED>Video</option>
									<option value="9">Audio Version</option>
									<option value="8">Video Sub Page</option>
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
							<div class="form-group col-md-2">
								<label>Page Number</label>
								<input type="number" min=1 class="form-control col-md-12" name="page_number" placeholder="Page number" oninput="validateInput(this)" value="<?php echo $page_name; ?>" required />
								<div><span id="errorMsg" style="color: red;"></span></div>
							</div>

							<div class="form-group  col-md-2 mt-3 mb-1">
								<?php if (isset($coursevalidation)) : ?>
									<div class=col-12 col-sm-4>
										<div class="alert alert-white" role="alert">
											<?= $coursevalidation->listErrors() ?>
										</div>
									</div>
								<?php endif; ?>
								<input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
								<button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light" id="submitButton">
									Add Page
								</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $client = session()->get('client');
if ($client == 1) { ?>
	<div class="row">
		<div class="col-6 col-md-6 col-lg-6">
			<div class="card">
				<div class="card-body">

					<form class="form-inline" enctype="multipart/form-data" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/importPageStructure'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
						<div class="col-md-4"><a href="<?php echo base_url('assets/assets/uploads/import_page_structure.xlsx'); ?>" target="_blank" class="btn btn-outline-info  btn-xs rounded-pill waves-effect waves-light">Template</a></div><br />
						<div class="col-md-8">
							<div class="mb-2">
								<div class="input-group file">
									<input type="file" name="file" id="file" accept=".xlsx" required>
								</div>
							</div>
							<div class="mb-2">
								<input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
								<button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light col-md-12" id="submitButton">
									Import New Page structure
								</button>
							</div>
						</div>
						<?php if (isset($excelvalidation)) : ?>
							<div class=col-12 col-sm-4>
								<div class="alert alert-danger" role="alert">
									<?= $excelvalidation->listErrors() ?>
								</div>
							</div>
						<?php endif; ?>
					</form>

				</div>
			</div>
		</div>
	</div>
<?php } ?>
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