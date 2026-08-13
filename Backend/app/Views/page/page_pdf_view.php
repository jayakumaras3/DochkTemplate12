<?php
// $baseloc is used by both the SCORM Export tab (mode == '2' only) and the
// Reference tab (always rendered regardless of mode) below, so it must be
// resolved unconditionally here rather than inside the mode-gated block that
// used to set it - otherwise it's left undefined for any course still in
// Development mode.
$baseloc = '';
$base = base_url();
if ($base == 'http://localhost/Dochek_V3/Dochek_V3') {
	$baseloc = '/Users/pchandran/Sites/dochek_v3/Dochek_V3/';
}
if ($base == 'http://localhost/projects_dochek') {
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
	$baseloc = 'C:/wamp64/www/DOCHEK/';
}
if ($base == 'http://172.16.2.218/DOCHEK/') {
	$baseloc = '/var/www/DOCHEK/';
}

function formatSizeExact($bytes)
{
	if ($bytes >= 1048576) {
		return sprintf("%.2f MB", $bytes / 1048576);
	} elseif ($bytes >= 1024) {
		return sprintf("%.2f KB", $bytes / 1024);
	} else {
		return $bytes . ' bytes';
	}
}
?>
<style>
	.pdfv-card {
		border-radius: 16px;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
		border: none;
	}

	[data-bs-theme="dark"] .pdfv-card {
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3), 0 2px 6px rgba(0, 0, 0, 0.2);
	}

	.pdfv-card-icon {
		width: 40px;
		height: 40px;
		border-radius: 12px;
		background-color: rgba(var(--ct-primary-rgb), 0.12);
		color: rgb(var(--ct-primary-rgb));
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 19px;
		flex-shrink: 0;
	}

	.pdfv-export-tabs {
		border: none;
		background-color: var(--ct-secondary-bg);
		border-radius: 16px;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
		padding: 6px;
		gap: 6px;
	}

	[data-bs-theme="dark"] .pdfv-export-tabs {
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3), 0 2px 6px rgba(0, 0, 0, 0.2);
	}

	.pdfv-export-tabs .nav-item {
		flex: 1 1 0;
	}

	.pdfv-export-tabs .nav-link {
		border: none;
		border-radius: 10px;
		color: var(--ct-body-color);
		font-weight: 600;
		font-size: 14px;
		padding: 14px 10px;
		text-align: center;
		border-bottom: 3px solid transparent;
	}

	.pdfv-export-tabs .nav-link i {
		margin-right: 6px;
		font-size: 16px;
	}

	.pdfv-export-tabs .nav-link.active {
		color: rgb(var(--ct-primary-rgb));
		background-color: transparent;
		border-bottom: 3px solid rgb(var(--ct-primary-rgb));
	}

	.pdfv-table thead th {
		font-weight: 700;
		font-size: 13px;
		color: var(--ct-body-color);
		background-color: rgba(var(--ct-primary-rgb), 0.06);
		border: none;
		padding: 12px 16px;
	}

	.pdfv-table td {
		vertical-align: middle;
		font-size: 13.5px;
		padding: 12px 16px;
		border-color: var(--ct-border-color-translucent);
	}

	.pdfv-empty-state {
		text-align: center;
		padding: 48px 20px;
	}

	.pdfv-empty-icon {
		width: 88px;
		height: 88px;
		border-radius: 50%;
		background-color: rgba(var(--ct-primary-rgb), 0.1);
		color: rgb(var(--ct-primary-rgb));
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 38px;
		margin: 0 auto 16px;
	}

	.pdfv-empty-title {
		font-weight: 700;
		color: var(--ct-body-color);
		margin-bottom: 4px;
	}

	.pdfv-empty-text {
		color: var(--ct-secondary-color);
		font-size: 13.5px;
	}

	.pdfv-info-bar {
		display: flex;
		align-items: center;
		gap: 10px;
		background-color: rgba(var(--ct-primary-rgb), 0.06);
		border-radius: 10px;
		padding: 14px 18px;
		font-size: 13.5px;
		color: var(--ct-body-color);
	}

	.pdfv-info-bar i {
		color: rgb(var(--ct-primary-rgb));
		font-size: 18px;
	}

	.pdfv-form-label {
		font-weight: 600;
		font-size: 13.5px;
		color: var(--ct-body-color);
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.pdfv-form-label i {
		color: var(--ct-secondary-color);
		font-size: 14px;
	}

	.pdfv-identifier-group {
		position: relative;
	}

	.pdfv-identifier-group input {
		padding-right: 42px;
	}

	.pdfv-copy-btn {
		position: absolute;
		right: 6px;
		top: 50%;
		transform: translateY(-50%);
		border: none;
		background: transparent;
		color: rgb(var(--ct-primary-rgb));
		width: 30px;
		height: 30px;
		border-radius: 8px;
	}

	.pdfv-copy-btn:hover {
		background-color: rgba(var(--ct-primary-rgb), 0.1);
	}

	.pdfv-create-btn {
		background-color: rgb(var(--ct-primary-rgb));
		border-color: rgb(var(--ct-primary-rgb));
		font-weight: 600;
	}

	/* The theme's global .btn:hover reset (color: #6658dd !important; background-color:
	   rgba(0,0,0,.075)) has the same specificity as these :hover rules and otherwise wins on
	   source order, stripping this button's solid background and white text down to a
	   near-transparent background with purple text - unreadable against the card's own light
	   background. Bootstrap's own variant buttons (btn-primary, etc.) carry their own hover
	   colors that already survive this; this custom class needs the same explicitly. */
	.pdfv-create-btn:hover,
	.pdfv-create-btn:focus,
	.pdfv-create-btn:active {
		background-color: rgb(var(--ct-primary-rgb)) !important;
		border-color: rgb(var(--ct-primary-rgb)) !important;
		color: #fff !important;
	}

	.pdfv-about-box {
		background-color: rgba(var(--ct-primary-rgb), 0.06);
		border-radius: 12px;
		padding: 16px;
		display: flex;
		gap: 12px;
	}

	.pdfv-about-box i {
		color: rgb(var(--ct-primary-rgb));
		font-size: 20px;
	}

	.pdfv-about-box h6 {
		font-weight: 700;
		margin-bottom: 4px;
	}

	.pdfv-about-box p {
		font-size: 13px;
		color: var(--ct-secondary-color);
		margin-bottom: 0;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/course_builder/Editor'); ?>">Course
							Builder</a></li>
				</ol>
			</div>
			<h4 class="page-title">Export - <?php echo esc($row['course_name']); ?></h4>
		</div>
	</div>
</div>

<div class="row mb-4">
	<div class="col-12">
		<ul class="nav pdfv-export-tabs mb-3">
			<li class="nav-item">
				<a href="#scormexport" data-bs-toggle="tab" aria-expanded="true" class="nav-link <?php if ($tab == 1)
																										echo "active"; ?>">
					<i class="mdi mdi-export-variant"></i> SCORM Export
				</a>
			</li>
			<li class="nav-item">
				<a href="#settings" data-bs-toggle="tab" aria-expanded="false" class="nav-link <?php if ($tab == 2)
																									echo "active"; ?>">
					<i class="mdi mdi-cog-outline"></i> Settings
				</a>
			</li>
			<li class="nav-item">
				<a href="#Reference" data-bs-toggle="tab" aria-expanded="false" class="nav-link <?php if ($tab == 3)
																									echo "active"; ?>">
					<i class="mdi mdi-file-pdf-box"></i> Upload Reference PDF
				</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane <?php if ($tab == 1)
										echo "show active"; ?>" id="scormexport">

				<?php if ($getAllpdfFileOwner[0]['mode'] != '2') { ?>
					<div class="pdfv-info-bar" style="background-color: rgba(var(--ct-warning-rgb, 247, 183, 51), 0.12);">
						<i class="mdi mdi-alert-outline" style="color: #f7b733;"></i>
						SCORM package export is currently unavailable as the project is still under development. Please try
						again once the development is complete.
					</div>
				<?php } else {
					// Resolve the exported package file (if one exists) exactly as before - only the
					// output template below changed, none of this path/lookup logic.
					$folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/';
					$exportFile = null;
					if (is_dir($folderloc)) {
						$files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
						$course_name = htmlspecialchars($getAllpdfFileOwner[0]['course_name'], ENT_QUOTES, 'UTF-8');
						$decoded_course_name = html_entity_decode($course_name, ENT_QUOTES, 'UTF-8');
						$courses_name = preg_replace('/[^A-Za-z0-9 _.\-]/u', ' ', $decoded_course_name);
						$courses_name = trim($courses_name, " _-");
						$courses_name = preg_replace('/[\s\-]+/', ' ', $courses_name);
						$filename = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $courses_name . '.zip';
						if (file_exists($filename)) {
							foreach ($files2 as $value) {
								if ($value == $courses_name . '.zip') {
									$exportFile = [
										'name' => $value,
										'size' => formatSizeExact(filesize($filename)),
										'exported_on' => date('Y-m-d H:i:s', filemtime($filename)),
										'download_url' => base_url('assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . htmlspecialchars($courses_name, ENT_QUOTES, 'UTF-8') . '.zip'),
									];
								}
							}
						}
					}
				?>
					<div class="row">
						<div class="col-lg-8 mb-3 mb-lg-0">
							<div class="card pdfv-card h-100">
								<div class="card-body">
									<div class="d-flex align-items-center gap-2 mb-3">
										<div class="pdfv-card-icon"><i class="mdi mdi-export-variant"></i></div>
										<div>
											<h5 class="mb-0">Export Package Files</h5>
											<p class="text-muted mb-0 font-13">Files included in the SCORM export package.</p>
										</div>
									</div>

									<?php if ($exportFile) { ?>
										<div class="table-responsive">
											<table class="table pdfv-table mb-0">
												<thead>
													<tr>
														<th>Folder Name</th>
														<th>Size</th>
														<th>Export On</th>
														<th>Version</th>
														<th>Download</th>
														<th>Delete</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?php echo $exportFile['name']; ?></td>
														<td><?php echo $exportFile['size']; ?></td>
														<td><?php echo $exportFile['exported_on']; ?></td>
														<td>SCORM 1.2</td>
														<td>
															<a href="<?php echo $exportFile['download_url']; ?>" data-download="1"
																class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light"
																title="Download"><i class="mdi mdi-download"></i></a>
														</td>
														<td>
															<button type="submit"
																class="btn btn-outline-danger btn-sm rounded-pill waves-effect waves-light"
																onclick="downloadAndDelete('<?php echo $scourse_id; ?>', '<?php echo ($courses_name); ?>')">
																<i class="mdi mdi-trash-can-outline"></i>
															</button>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									<?php } else { ?>
										<div class="pdfv-empty-state">
											<div class="pdfv-empty-icon"><i class="mdi mdi-folder-outline"></i></div>
											<div class="pdfv-empty-title">No files to display</div>
											<div class="pdfv-empty-text">Add content and generate the export package.</div>
										</div>
									<?php } ?>

									<div class="pdfv-info-bar mt-3">
										<i class="mdi mdi-information-outline"></i>
										Only published course content will be included in the SCORM export package.
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-4">
							<div class="card pdfv-card h-100">
								<div class="card-body">
									<div class="d-flex align-items-center gap-2 mb-3">
										<div class="pdfv-card-icon"><i class="mdi mdi-tune-variant"></i></div>
										<div>
											<h5 class="mb-0">Export Settings</h5>
											<p class="text-muted mb-0 font-13">Configure SCORM export information.</p>
										</div>
									</div>

									<form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/exportCoursePackage'); ?>"
										method="post" enctype="multipart/form-data" id="submitForm"><?= csrf_field() ?>
										<div class="mb-3">
											<label class="pdfv-form-label">Identifier <i class="mdi mdi-information-outline"
													title="Unique identifier for this SCORM package"></i></label>
											<div class="pdfv-identifier-group">
												<input class="form-control" name="Identifier" id="pdfvIdentifier" type="text"
													value="<?php echo 'DCK' . preg_replace('/[^a-zA-Z0-9]/', '', $getAllpdfFileOwner[0]['createdon']); ?>"
													required pattern="[A-Za-z0-9]+"
													title="Only alphanumeric characters allowed (no spaces or special characters)"
													oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')" />
												<button type="button" class="pdfv-copy-btn" title="Copy"
													onclick="pdfvCopyIdentifier()"><i class="mdi mdi-content-copy"></i></button>
											</div>
										</div>
										<div class="mb-3">
											<label class="pdfv-form-label">LMS Reporting <i class="mdi mdi-information-outline"
													title="How this package reports completion status to your LMS"></i></label>
											<select name="lmsStatus" class="form-select">
												<option value="Passed/Failed">Passed/Failed</option>
												<option value="Passed/Incomplete">Passed/Incomplete</option>
												<option value="Completed/Incomplete">Completed/Incomplete</option>
												<option value="Completed/Failed">Completed/Failed</option>
											</select>
										</div>
										<div class="mb-3">
											<label class="pdfv-form-label">SCORM Version <i class="mdi mdi-information-outline"
													title="SCORM specification version to export to"></i></label>
											<select name="theme" class="form-select">
												<option value="1">SCORM 1.2</option>
												<!-- <option value="2">SCORM 2004</option> -->
											</select>
										</div>
										<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
										<input type="hidden" name="tab" value="1">
										<input type="hidden" name="returnUrl" value="1">
										<button type="submit" class="btn pdfv-create-btn text-white rounded-pill w-100 waves-effect waves-light"
											id="submitButton"><i class="mdi mdi-tray-arrow-up"></i> Create Export File</button>

										<?php if (isset($promovalidation)): ?>
											<div class="mt-2">
												<div class="alert alert-white" role="alert">
													<?= $promovalidation->listErrors() ?>
												</div>
											</div>
										<?php endif; ?>
									</form>

									<div class="pdfv-about-box mt-3">
										<i class="mdi mdi-lightbulb-on-outline"></i>
										<div>
											<h6>About SCORM Export</h6>
											<p>This will package your course content into a SCORM compliant ZIP file that can be
												uploaded to any LMS.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>


			<div class="tab-pane <?php if ($tab == 2)
										echo "show active"; ?>" id="settings">
				<div class="card pdfv-card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<p>NOTE : Respective Default Template will be display if you are not filled any fields
								</p>
								<?php //$assessment_export_sets = '';
								foreach ($assessment_export_sets as $x => $sets) {
									// print_r($x);
									// exit();
									if ($x == "62" || $x == "63" || $x == "74") {
										if (!empty($AssessmentSettings[$x])) {
											$item = $AssessmentSettings[$x][0]['value'];
											$s_id = $AssessmentSettings[$x][0]['s_id'];


								?>
											<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>"
												method="POST"><?= csrf_field() ?>
												<input type="hidden" name="quiz_settings_type" value="<?php echo $x ?>">
												<input type="hidden" name="add_or_update" value="2">
												<input type="hidden" name="s_id" value="<?php echo $s_id; ?>">
												<input type="hidden" name="scourse_id"
													value="<?php echo isset($getAssessmentSettings[0]['scourse_id']) ? $getAssessmentSettings[0]['scourse_id'] : $scourse_id; ?>">
												<input type="hidden" name="page_id" value="0">
												<input type="hidden" name="returnUrl" value="1">
												<input type="hidden" name="tab" value="2">
												<input class="form-control" name="valid" type="hidden" />
												<div class="row">
													<?php if ($x == "62") { ?>
														<label><b>Default : </b>Free navigation</label><br>
													<?php } elseif ($x == "63") { ?>
														<label><b>Default : </b>Page level course completion</label><br>
													<?php } elseif ($x == "74") { ?>
														<label><b>Default : </b>Certificate Enable/Disable</label><br>
													<?php } ?>

													<div class="col-lg-3">
														<?php if ($item == 1) { ?>
															<input class="form-control" name="value" type="hidden" value="0" />
														<?php } else { ?>
															<input class="form-control" name="value" type="hidden" value="1" />

														<?php } ?>
													</div>
													<div class="col-lg-9">
														<?php if ($item == 1) { ?>
															<button type="submit" class="btn btn-success btn-sm rounded-pill"><i
																	class="fa fa-toggle-off"></i></button>
														<?php } else { ?>
															<button type="submit" class="btn btn-danger btn-sm rounded-pill"><i
																	class="fa fa-toggle-on"></i></button>
														<?php } ?>
													</div>
												</div><br />
											</form>
										<?php

										} else {

										?>
											<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>"
												method="POST"><?= csrf_field() ?>
												<input type="hidden" name="quiz_settings_type" value=" <?php echo $x ?>">
												<input type="hidden" name="add_or_update" value="1">
												<input type="hidden" name="s_id" value="0">
												<input type="hidden" name="quiz_settings_id"
													value="<?php echo isset($getAssessmentSettings[0]['s_id']) ? $getAssessmentSettings[0]['s_id'] : ''; ?>">
												<input type="hidden" name="scourse_id"
													value="<?php echo isset($getAssessmentSettings[0]['scourse_id']) ? $getAssessmentSettings[0]['scourse_id'] : $scourse_id; ?>">
												<input type="hidden" name="page_id" value="0">
												<input type="hidden" name="returnUrl" value="1">
												<input type="hidden" name="tab" value="2">
												<div class="row">
													<div class="col-lg-3">

														<?php if ($x == "62") { ?>
															<label><b>Default : </b>Free navigation</label><br>
														<?php } elseif ($x == "63") { ?>
															<label><b>Default : </b>Page level course completion</label><br>
														<?php } elseif ($x == "74") { ?>
															<label><b>Default : </b>Certificate Enable/Disable</label><br>
														<?php } ?>
														<input class="form-control" name="valid" type="hidden" />

													</div>
													<?php if ($x == "74") { ?>
														<div class="col-lg-9">
															<input type="hidden" name="value" class="form-control" value="0" required />
															<button type="submit" class="btn btn-success btn-sm rounded-pill"><i
																	class="fa fa-toggle-on"></i></button>
														</div>
													<?php } else { ?>
														<div class="col-lg-9">
															<input type="hidden" name="value" class="form-control" value="1" required />
															<button type="submit" class="btn btn-danger btn-sm rounded-pill"><i
																	class="fa fa-toggle-off"></i></button>
														</div>
													<?php } ?>
												</div><br />
											</form>
										<?php }
									} else {

										if (!empty($AssessmentSettings[$x])) {
											$item = $AssessmentSettings[$x][0]['value'];
											$s_id = $AssessmentSettings[$x][0]['s_id'];


										?>

											<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>"
												method="POST"><?= csrf_field() ?>
												<input type="hidden" name="quiz_settings_type" value="<?php echo $x ?>">
												<input type="hidden" name="add_or_update" value="2">
												<input type="hidden" name="s_id" value="<?php echo $s_id; ?>">
												<input type="hidden" name="scourse_id"
													value="<?php echo isset($getAssessmentSettings[0]['scourse_id']) ? $getAssessmentSettings[0]['scourse_id'] : $scourse_id; ?>">
												<input type="hidden" name="page_id" value="0">
												<input type="hidden" name="returnUrl" value="1">
												<input type="hidden" name="tab" value="2">
												<input class="form-control" name="valid" type="hidden" />
												<div class="row align-items-center">
													<?php if ($x == '64') { ?>
														<label><b>Default :</b> <?php echo $sets ?> (VTT Language)</label><br>
													<?php } elseif ($x == '65') { ?>
														<label><b>Default :</b> <?php echo $sets ?> (VTT Label)</label><br>
													<?php } else { ?>
														<label><b>Default :</b> <?php echo $sets ?></label><br>
													<?php } ?>
													<div class="col-lg-10">
														<input class="form-control" name="value" type="input"
															value="<?php echo isset($item) ? $item : $sets; ?>" />
													</div>
													<div class="col-lg-2 d-flex align-items-end">
														<button type="submit" class="btn btn-outline-warning w-0 py-0 rounded-pill">
															Update</button>
													</div>
												</div><br />
											</form><br />
										<?php

										} else {

										?>
											<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>"
												method="POST"><?= csrf_field() ?>
												<input type="hidden" name="quiz_settings_type" value=" <?php echo $x ?>">
												<input type="hidden" name="add_or_update" value="1">
												<input type="hidden" name="s_id" value="0">
												<input type="hidden" name="quiz_settings_id"
													value="<?php echo isset($getAssessmentSettings[0]['s_id']) ? $getAssessmentSettings[0]['s_id'] : ''; ?>">
												<input type="hidden" name="scourse_id"
													value="<?php echo isset($getAssessmentSettings[0]['scourse_id']) ? $getAssessmentSettings[0]['scourse_id'] : $scourse_id; ?>">
												<input type="hidden" name="page_id" value="0">
												<input type="hidden" name="returnUrl" value="1">
												<input type="hidden" name="tab" value="2">
												<div class="row align-items-center">
													<div class="col-lg-10">
														<?php if ($x == '64') { ?>
															<label><strong>Default:</strong> <?php echo $sets ?> (VTT Language)</label>
														<?php } elseif ($x == '65') { ?>
															<label><strong>Default:</strong> <?php echo $sets ?> (VTT Label)</label>
														<?php } else { ?>
															<label><strong>Default:</strong> <?php echo $sets ?></label>
														<?php } ?>
														<input name="value" class="form-control" value="" required />
													</div>

													<div class="col-lg-2 d-flex align-items-end">
														<button type="submit" class="btn btn-outline-primary w-0 py-0 rounded-pill">
															Add
														</button>
													</div>
												</div>


											</form><br />
								<?php
										}
									}
								}

								?>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane <?php if ($tab == 3) echo "show active"; ?>" id="Reference">
				<div class="card pdfv-card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<?php
								$folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $row['createdon'] . '/assets/PDF';
								// print_r($folderloc);
								if (is_dir($folderloc)) {
								?>
									<div class="col-md-6">
										<div class="table-responsive">
											<table class="table table-borderless mb-0">
												<thead class="table-light">
													<tr>
														<th>#</th>
														<th><?= lang('UI_Text.Folder') ?></th>
														<th><?= lang('UI_Text.Created') ?></th>
														<th><?= lang('UI_Text.Delete') ?></th>
													</tr>
												</thead>
												<tbody>
													<?php
													$files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
													$sno = 0;

													foreach ($files2 as $key => $value) {
														if (strlen($value) > 3) {

															$dontshow = 0;
															$file_parts = pathinfo($value);
															if ($file_parts['extension'] != 'DS_Store') {
																$sno++;
																echo '<tr><td>';
																echo $sno;
																echo '</td><td>';
																echo $value;

																echo '</td><td>';
																$file_creation_date = filectime($folderloc . '/' . $value);
																echo date('Y-m-d H:i:s', $file_creation_date);
																echo '</td><td>';
																if ($row['thumbnail'] != $value) {
													?>
																	<form class="form-horizontal"
																		action="<?php echo base_url('SCORM/Scorm_courses/del_file'); ?>"
																		method="POST"><?= csrf_field() ?>
																		<input type="hidden" name="fileloc"
																			value="<?php echo $folderloc . '/' . $value; ?>">
																		<input type="hidden" name="foldername" value="<?php echo $value; ?>">
																		<input type="hidden" name="scourse_id"
																			value="<?php echo $scourse_id ?>">
																		<input type="hidden" name="tab" value="3">
																		<button type="submit" class="btn btn-outline-danger waves-effect btn-xs rounded-pill waves-light"
																			onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
																				class="mdi mdi-trash-can-outline"></span> Delete</button>
																	</form>
												<?php
																}

																echo '</td><tr>';
															}
														}
													}

													echo '</tbody></table></div><hr>';
												}
												?>
										</div>
									</div>

									<div class="col-md-6">
										<form class="form-horizontal2" enctype="multipart/form-data" action=<?php echo base_url('SCORM/scorm_courses/uploadpdf'); ?> method="post" id="submitForm"><?= csrf_field() ?>
											<label><?= lang('UI_Text.Description') ?></label>
											<div class="mb-3">
												<input type="input" name="description" class="form-control"
													required />
											</div>
											<div class="mb-3">
												<input type="file" name="file" class="form-control" accept="application/pdf" required />
											</div>
											<div class="mb-3">
												<input type="hidden" name="tab" value="3">
												<input type="hidden" name="scourse_id"
													value="<?php echo $scourse_id ?>">
												<input type="hidden" name="createdon"
													value="<?php echo $row['createdon'] ?>">
												<button type="submit"
													class="btn btn-outline-success waves-effect btn-sm rounded-pill waves-light mb-3"
													id="submitButton"><?= lang('Buttons.Upload_PDF_Document') ?></button>
											</div>
											<?php if (isset($pdfvalidation)): ?>
												<div class="form-group col-md-12">
													<div class="alert alert-danger" role="alert">
														<?= $pdfvalidation->listErrors() ?>
													</div>
												</div>
											<?php endif; ?>
										</form>
										<?php
										?>

									</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<script>
	function pdfvCopyIdentifier() {
		var input = document.getElementById('pdfvIdentifier');
		if (!input) return;
		input.select();
		input.setSelectionRange(0, 99999);
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(input.value);
		} else {
			document.execCommand('copy');
		}
	}

	function downloadAndDelete(courseId, zipFileName) {
		// AJAX request to the PHP script for deleting the zip file
		var xhr = new XMLHttpRequest();
		xhr.open("GET", "<?php echo base_url('SCORM/course_builder/Scorm_course_pages/delete_zip'); ?>/" + courseId + "/" + zipFileName, true);
		xhr.onload = function() {
			if (xhr.status === 200) {
				// Reload the page after successful deletion
				location.reload();
			}
		};
		xhr.send();
	}
</script>