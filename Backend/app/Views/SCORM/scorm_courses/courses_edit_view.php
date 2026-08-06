<style>
	.settings-back-link {
		display: inline-flex;
		align-items: center;
		gap: .35rem;
		font-weight: 600;
		font-size: .875rem;
		color: #6658dd;
		text-decoration: none;
	}

	[data-bs-theme="dark"] .settings-back-link {
		color: #9298f5;
	}

	.settings-back-link:hover {
		text-decoration: underline;
	}

	.settings-section {
		border-radius: 16px;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
		border: none;
	}

	.settings-section .section-title {
		display: flex;
		align-items: center;
		gap: .5rem;
		font-weight: 700;
		font-size: 1rem;
		margin-bottom: 1rem;
	}

	.settings-section .section-title i {
		color: #6658dd;
	}

	[data-bs-theme="dark"] .settings-section .section-title i {
		color: #9298f5;
	}
</style>
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a
							href="<?php echo base_url($header_link_1) ?>"><?= lang('UI_Text.Course_Details') ?></a></li>

				</ol>
			</div>
			<h4 class="page-title"><?php echo $sub_header_1 . ' - ' . $course_name; ?></h4>
		</div>
	</div>
</div>
<?php
$base = base_url();
// print_r($base);
//exit();
if ($base == 'http://localhost:8888/projects_dochek/') {
	$baseloc = '/Users/pchandran/Sites/projects_dochek/';
}
if ($base == 'http://localhost/projects_dochek/') {
	$baseloc = 'D:/wampp/www/projects_dochek/';
}
if ($base == 'https://dochek.com/') {
	$baseloc = '/var/www/html/';
}
if ($base == 'http://localhost/DOCHEK/') {
	$baseloc = 'D:/wampp/www/DOCHEK';
}
if ($base == 'https://staging.dochek.com/') {
	$baseloc = '/var/www/html/DOCHEK//';
}
if ($base == 'http://localhost/DOCHEK/') {
	$baseloc = 'C:/wamp64/www/DOCHEK/';
}
if ($base == 'http://172.16.2.218/DOCHEK/') {
	$baseloc = '/var/www/DOCHEK/';
}
?>
<?php if ($typeval != 8) { ?>
	<!-- <div class="tab-pane" id="scorm"> -->
	<div class="row mt-3">

		<?php

		$folderloc = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id;

		// COUNT ONLY PACKAGE FOLDERS
		$uploadedCount = 0;

		if (is_dir($folderloc)) {

			$items = scandir($folderloc);

			foreach ($items as $item) {

				// skip . and ..
				if ($item == '.' || $item == '..') {
					continue;
				}

				// count only folders
				if (is_dir($folderloc . '/' . $item)) {
					$uploadedCount++;
				}
			}
		}

		?>

		<!-- LEFT SIDE -->
		<div class="col-lg-8">

			<div class="card settings-section mb-3">

				<div class="card-body">

					<h5 class="section-title">
						<i class="mdi mdi-cards-variant"></i>
						SCORM (With imsmanifest.xml) &mdash; Uploaded Files
					</h5>

					<div class="table-responsive">

						<table class="table table-borderless mb-0">

							<thead class="table-light">
								<tr>
									<th>#</th>
									<th>Description</th>
									<th>Created</th>
									<th>Activate</th>
									<th>Download</th>
									<th>Overwrite</th>
									<th>Delete</th>
								</tr>
							</thead>

							<tbody>

								<?php

								if (is_dir($folderloc)) {

									$files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);

									$sno = 0;

									foreach ($files2 as $key => $value) {

										// skip . and ..
										if ($value == '.' || $value == '..') {
											continue;
										}

										// only folders
										if (!is_dir($folderloc . '/' . $value)) {
											continue;
										}

										$sno++;

								?>

										<tr>

											<!-- SERIAL -->
											<td>
												<?php echo $sno; ?>
											</td>

											<!-- DESCRIPTION -->
											<td>

												<?php

												$resultx = array_search(
													$value,
													array_column($getAllFileOwner, 'folder')
												);

												if (is_numeric($resultx)) {

													echo $getAllFileOwner[$resultx]['description'];
												}

												?>

											</td>

											<!-- CREATED -->
											<td>

												<?php

												$file_creation_date = filectime($folderloc . '/' . $value);

												echo date('Y-m-d H:i:s', $file_creation_date);

												?>

											</td>

											<!-- ACTIVATE -->
											<td>

												<?php

												$existingfile = $row['upload'];

												if ($existingfile != $value) {

												?>

													<form action="<?php echo base_url('XAPI/XAPI_courses/activate'); ?>"
														method="POST">

														<?= csrf_field() ?>

														<input type="hidden"
															name="scourse_id"
															value="<?php echo $scourse_id ?>">

														<input type="hidden"
															name="filename"
															value="<?php echo $value; ?>">

														<button type="submit"
															class="btn btn-outline-warning rounded-pill btn-xs waves-effect waves-light"
															onclick="return confirm('<?php echo lang('Alert.Aler_011') ?>')">

															Activate

														</button>

													</form>

												<?php

												} else {

													echo '<span class="badge bg-success">Active</span>';
												}

												?>

											</td>

											<!-- DOWNLOAD -->
											<td>

												<form action="<?php echo base_url('XAPI/XAPI_courses/downloadSCORMCoursePackage'); ?>"
													method="POST">

													<?= csrf_field() ?>

													<input type="hidden"
														name="scourse_id"
														value="<?php echo $scourse_id ?>">

													<input type="hidden"
														name="filename"
														value="<?php echo $value; ?>">

													<button type="submit"
														class="btn btn-outline-success rounded-pill btn-xs waves-effect waves-light">

														<span class="fa fa-download"></span>
														Download

													</button>

												</form>

											</td>

											<!-- OVERWRITE -->
											<td>

												<button type="button"
													class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light"
													data-bs-toggle="modal"
													data-bs-target="#uploadZipModal_<?php echo $sno; ?>">

													<span class="fa fa-upload"></span>
													Upload

												</button>

												<!-- MODAL -->
												<div class="modal fade"
													id="uploadZipModal_<?php echo $sno; ?>"
													tabindex="-1"
													aria-hidden="true">

													<div class="modal-dialog modal-dialog-centered">

														<div class="modal-content">

															<div class="modal-header">

																<h5 class="modal-title">
																	Upload ZIP & Overwrite
																</h5>

																<button type="button"
																	class="btn-close"
																	data-bs-dismiss="modal">
																</button>

															</div>

															<div class="modal-body">

																<form action="<?php echo base_url('XAPI/XAPI_courses/overwriteupload_zip'); ?>"
																	method="POST"
																	enctype="multipart/form-data">

																	<?= csrf_field() ?>

																	<input type="hidden"
																		name="timestamp"
																		value="<?php echo $value; ?>">

																	<input type="hidden"
																		name="scourse_id"
																		value="<?php echo $scourse_id ?>">

																	<div class="mb-3">

																		<label class="form-label">
																			Select ZIP File
																		</label>

																		<input type="file"
																			name="zip_file"
																			accept=".zip"
																			class="form-control"
																			required>

																	</div>

																	<button type="submit"
																		class="btn btn-primary rounded-pill w-100">

																		<i class="fa fa-upload"></i>
																		Upload

																	</button>

																</form>

															</div>

														</div>

													</div>

												</div>

											</td>

											<!-- DELETE -->
											<td>

												<form action="<?php echo base_url('XAPI/XAPI_courses/del_folder'); ?>"
													method="POST">

													<?= csrf_field() ?>

													<input type="hidden"
														name="folderloc"
														value="<?php echo $folderloc . '/' . $value; ?>">

													<input type="hidden"
														name="folder_name"
														value="<?php echo $value; ?>">

													<input type="hidden"
														name="scourse_id"
														value="<?php echo $scourse_id ?>">

													<button type="submit"
														class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"
														onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')">

														<span class="mdi mdi-trash-can-outline"></span>
														Delete

													</button>

												</form>

											</td>

										</tr>

									<?php
									}
								} else {

									?>

									<tr>
										<td colspan="7" class="text-center text-danger">
											No Files
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

		<!-- RIGHT SIDE -->
		<div class="col-lg-4">

			<div class="card settings-section mb-3">

				<div class="card-body">

					<h5 class="section-title">
						<i class="mdi mdi-cloud-upload-outline"></i>
						Upload New SCORM
					</h5>

					<?php if ($uploadedCount >= 2) { ?>

						<div class="persistent-warning">
							<div class="danger-text">

								<strong>Maximum Upload Limit Reached!</strong>

								<hr>

								Only 2 SCORM packages are allowed.

								<br>

								Please delete one package before uploading another.

							</div>
						</div>

					<?php } else { ?>

						<form class="form-horizontal1"
							id="uploadzipfile"
							enctype="multipart/form-data">

							<?= csrf_field() ?>

							<div class="mb-3">

								<input type="text"
									class="form-control"
									name="description"
									placeholder="Description"
									required />

							</div>

							<div class="mb-3">

								<input type="file"
									name="zip_file"
									accept=".zip"
									required />

							</div>

							<div class="form-group col-md-12">

								<input type="hidden"
									name="scourse_id"
									value="<?php echo $scourse_id ?>">

								<button type="submit"
									class="btn btn-outline-success rounded-pill waves-effect btn-sm waves-light mb-3"
									id="submitButton">

									Upload Zip Package

								</button>

							</div>

						</form>

					<?php } ?>

				</div>

				<div class="progress" style="display:none;">

					<div class="progress-bar"
						role="progressbar"
						style="width:0%;"
						aria-valuenow="0"
						aria-valuemin="0"
						aria-valuemax="100">
					</div>

				</div>

			</div>

		</div>

	</div>
	<!-- end SCORM section content -->
<?php } ?>
<script>
	document.getElementById('uploadzipfile').addEventListener('submit', function() {
		var button = document.getElementById('submitButton');
		button.disabled = true;
		button.innerHTML = 'Submitting...';
	});
</script>
<script>
	$('.fa').show();

	$('#uploadzipfile').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData($('#uploadzipfile')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('SCORM/scorm_courses/scorm_upload') ?>',
				type: "POST",
				data: dataString,
				processData: false,
				contentType: false,
				beforeSend: function() {
					// Show progress bar
					$(".progress").show();
				},
				success: function(data) {
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
				error: function(xhr, textStatus, errorThrown) {
					// console.log('request failed');
				},
				complete: function() {
					// Hide progress bar after completion
					$(".progress").hide();
				},
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(evt) {
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
	$('#addcategoryForm').on('submit', function(event) {

		event.preventDefault();

		var dataString = new FormData($('#addcategoryForm')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('scorm_courses/assignmetacategory') ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function(data) {

					var obj = JSON.parse(data);

					console.log(obj);

					if (obj.status === 'OK') {
						console.log('inside on condition');
						//window.location.href = 'project_settings.php';
						location.reload();

					} else {

						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}

				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			})
		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}

	});
</script>
<script>
	$('#addmetadataForm').on('submit', function(event) {

		event.preventDefault();

		var dataString = new FormData($('#addmetadataForm')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('scorm_courses/assignmetacategory') ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function(data) {

					var obj = JSON.parse(data);

					console.log(obj);

					if (obj.status === 'OK') {
						console.log('inside on condition');
						//window.location.href = 'project_settings.php';
						location.reload();


					} else {

						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}

				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			})
		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}

	});
	$(document).ready(function() {

		$('#dynamic-table').DataTable();

	});
</script>