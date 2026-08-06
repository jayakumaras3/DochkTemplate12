<?php
$base = base_url();
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
	$baseloc = 'C:/wamp64/www/DOCHEK/';
}
if ($base == 'https://staging.dochek.com/') {
	$baseloc = '/var/www/html/DOCHEK/';
}
if ($base == 'http://localhost/DOCHEKDOTCOM/') {
	$baseloc = 'D:/wampp/www/DOCHEKDOTCOM/';
}
if ($base == 'http://172.16.2.218/DOCHEK/') {
	$baseloc = '/var/www/DOCHEK/';
}

$client = session()->get('client');

$statusMeta = [
	'1' => ['label' => lang('UI_Text.Development'), 'class' => 'secondary'],
	'2' => ['label' => lang('UI_Text.Live'), 'class' => 'success'],
	'3' => ['label' => lang('UI_Text.Alpha'), 'class' => 'primary'],
	'4' => ['label' => lang('UI_Text.Alpha_2'), 'class' => 'primary'],
	'5' => ['label' => lang('UI_Text.Beta'), 'class' => 'info'],
	'6' => ['label' => lang('UI_Text.Beta_2'), 'class' => 'info'],
	'7' => ['label' => lang('UI_Text.Gamma'), 'class' => 'warning'],
	'8' => ['label' => lang('UI_Text.Gamma_2'), 'class' => 'warning'],
];
$currentMode = (string) ($row['mode'] ?? '1');
$currentStatus = $statusMeta[$currentMode] ?? $statusMeta['1'];
$lastModified = !empty($row['last_updated_on']) ? date('d M Y h:i A', (int) $row['last_updated_on']) : '—';
?>
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

	.settings-tabs {
		display: flex;
		gap: .5rem;
		flex-wrap: wrap;
		margin-bottom: 1rem;
	}

	.settings-tabs .nav-link {
		display: inline-flex;
		align-items: center;
		gap: .4rem;
		border-radius: 10px;
		font-weight: 600;
		font-size: .875rem;
		padding: .55rem 1rem;
		color: var(--ct-secondary-color, #6c757d);
		background-color: var(--ct-secondary-bg, #fff);
		border: 1px solid var(--ct-border-color-translucent, rgba(0, 0, 0, .08));
	}

	.settings-tabs .nav-link.active {
		background-color: #6658dd;
		color: #fff;
		border-color: #6658dd;
	}

	[data-bs-theme="dark"] .settings-tabs .nav-link.active {
		background-color: #9298f5;
		border-color: #9298f5;
		color: #1a1d2e;
	}

	.quick-actions-card .quick-action-item {
		display: flex;
		align-items: center;
		width: 100%;
		gap: .65rem;
		padding: .65rem .25rem;
		border: none;
		background: none;
		text-align: left;
		color: var(--ct-body-color, #313a46);
		font-weight: 600;
		font-size: .875rem;
		border-radius: 10px;
	}

	.quick-actions-card .quick-action-item:not(:disabled):hover {
		background-color: rgba(102, 88, 221, 0.08);
	}

	[data-bs-theme="dark"] .quick-actions-card .quick-action-item:not(:disabled):hover {
		background-color: rgba(146, 152, 245, 0.15);
	}

	.quick-actions-card .quick-action-item:disabled {
		color: var(--ct-secondary-color, #adb5bd);
		cursor: default;
	}

	.quick-actions-card .quick-action-item .mdi:first-child {
		font-size: 1.1rem;
	}

	.quick-actions-card .quick-action-item .label-text {
		flex-grow: 1;
	}

	.quick-actions-card hr {
		margin: .25rem 0;
	}

	.demo-toggle {
		display: flex;
		align-items: center;
		gap: .5rem;
	}

	.demo-toggle .form-check-input {
		width: 2.75rem;
		height: 1.5rem;
		margin-top: 0;
		flex-shrink: 0;
	}

	.demo-toggle .form-check-label {
		margin-bottom: 0;
		line-height: 1.5rem;
	}

	.demo-toggle .form-check-input:checked {
		background-color: #6658dd;
		border-color: #6658dd;
	}

	[data-bs-theme="dark"] .demo-toggle .form-check-input:checked {
		background-color: #9298f5;
		border-color: #9298f5;
	}

	.upload-recommend {
		background-color: var(--ct-tertiary-bg, #f4f6fb);
		border-radius: 12px;
		padding: 1rem 1.1rem;
	}

	.upload-recommend .upload-recommend-title {
		font-weight: 700;
		font-size: .875rem;
		margin-bottom: .6rem;
		color: var(--ct-body-color, #313a46);
	}

	.upload-recommend ul {
		list-style: none;
		margin: 0;
		padding: 0;
		display: flex;
		flex-direction: column;
		gap: .55rem;
	}

	.upload-recommend ul li {
		display: flex;
		align-items: center;
		gap: .55rem;
		font-size: .85rem;
		color: var(--ct-secondary-color, #5c6570);
	}

	.upload-recommend ul li i {
		color: #28a745;
		font-size: 1rem;
		flex-shrink: 0;
	}

	[data-bs-theme="dark"] .upload-recommend ul li i {
		color: #4ecb71;
	}
</style>
 <div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url($courses_link); ?>"><?= esc($courses_link_label) ?></a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url($header_link); ?>"><?= lang('UI_Text.Course_Details') ?></a></li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo esc($sub_header_1); ?></h4>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">


		<div class="d-flex flex-wrap justify-content-between align-items-start mt-2 gap-2">
			<div>
				
				<div class="d-flex flex-wrap align-items-center gap-3 font-13 text-muted">
					<span><i class="mdi mdi-calendar-outline me-1"></i><?= lang('UI_Text.Last_Modified') ?>: <?= esc($lastModified) ?></span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row mt-3">
	<div class="col-xl-9 col-lg-8">
		<ul class="nav settings-tabs" role="tablist">
			<li class="nav-item">
				<a href="#edit_course" data-bs-toggle="tab" aria-expanded="true" class="nav-link <?php if ($tab == 1) echo "active"; ?>">
					<i class="mdi mdi-file-document-outline"></i> <?= lang('Buttons.General') ?>
				</a>
			</li>
			<li class="nav-item">
				<a href="#objectives" data-bs-toggle="tab" aria-expanded="true" class="nav-link <?php if ($tab == 2) echo "active"; ?>">
					<i class="mdi mdi-target"></i> <?= lang('Buttons.Learning_Objectives') ?>
				</a>
			</li>
			<li class="nav-item">
				<a href="#thumbnail" data-bs-toggle="tab" aria-expanded="false" class="nav-link <?php if ($tab == 3) echo "active"; ?>">
					<i class="mdi mdi-image-outline"></i> <?= lang('Buttons.Thumbnail') ?>
				</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane <?php if ($tab == 1) echo "show active"; ?>" id="edit_course">
				<form class="form-horizontal" id="courseGeneralForm" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>

					<div class="card settings-section mb-3">
						<div class="card-body">
							<h5 class="section-title"><i class="mdi mdi-information-outline"></i> <?= lang('UI_Text.Basic_Information') ?></h5>
							<div class="row">
								<div class="col-lg-4 mb-3">
									<label class="form-label"><?= lang('UI_Text.Course Name') ?> <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="course_name"
										placeholder="<?= lang('UI_Text.Course Name') ?>" value="<?php echo esc($row['course_name']) ?>" required />
								</div>

								<div class="col-lg-4 mb-3">
									<label class="form-label"><?= lang('UI_Text.Course Code') ?></label>
									<input type="text" class="form-control" name="course_code"
										placeholder="<?= lang('UI_Text.Course Code') ?>"
										value="<?php echo isset($row['course_code']) ? esc($row['course_code']) : ''; ?>" />
								</div>

								<div class="col-lg-4 mb-3">
									<label class="form-label"><i class="mdi mdi-web me-1"></i><?= lang('UI_Text.Language') ?></label>
									<select name="language" class="form-select">
										<option value="English" <?php echo ($row['language'] == 'English') ? 'Selected' : ''; ?>>English</option>
										<option value="Spanish" <?php echo ($row['language'] == 'Spanish') ? 'Selected' : ''; ?>>Spanish</option>
										<option value="French" <?php echo ($row['language'] == 'French') ? 'Selected' : ''; ?>>French</option>
										<option value="Russian" <?php echo ($row['language'] == 'Russian') ? 'Selected' : ''; ?>>Russian</option>
										<option value="Portuguese" <?php echo ($row['language'] == 'Portuguese') ? 'Selected' : ''; ?>>Portuguese</option>
										<option value="Bahasa" <?php echo ($row['language'] == 'Bahasa') ? 'Selected' : ''; ?>>Bahasa</option>
										<option value="Arabic" <?php echo ($row['language'] == 'Arabic') ? 'Selected' : ''; ?>>Arabic</option>
										<option value="German" <?php echo ($row['language'] == 'German') ? 'Selected' : ''; ?>>German</option>
										<option value="Italian" <?php echo ($row['language'] == 'Italian') ? 'Selected' : ''; ?>>Italian</option>
										<option value="Japanese" <?php echo ($row['language'] == 'Japanese') ? 'Selected' : ''; ?>>Japanese</option>
										<option value="Korean" <?php echo ($row['language'] == 'Korean') ? 'Selected' : ''; ?>>Korean</option>
										<option value="Turkish" <?php echo ($row['language'] == 'Turkish') ? 'Selected' : ''; ?>>Turkish</option>
										<option value="Swedish" <?php echo ($row['language'] == 'Swedish') ? 'Selected' : ''; ?>>Swedish</option>
										<option value="Dutch" <?php echo ($row['language'] == 'Dutch') ? 'Selected' : ''; ?>>Dutch</option>
										<option value="Kazakh" <?php echo ($row['language'] == 'Kazakh') ? 'Selected' : ''; ?>>Kazakh</option>
										<option value="Simplified Chinese" <?php echo ($row['language'] == 'Simplified Chinese') ? 'Selected' : ''; ?>>Simplified Chinese</option>
										<option value="Traditional Chinese" <?php echo ($row['language'] == 'Traditional Chinese') ? 'Selected' : ''; ?>>Traditional Chinese</option>
										<option value="Vietnamese" <?php echo ($row['language'] == 'Vietnamese') ? 'Selected' : ''; ?>>Vietnamese</option>
										<option value="Bahasa Malayasian" <?php echo ($row['language'] == 'Bahasa Malaysian') ? 'Selected' : ''; ?>>Bahasa Malaysian</option>
										<option value="Bahasa Indonesian" <?php echo ($row['language'] == 'Bahasa Indonesian') ? 'Selected' : ''; ?>>Bahasa Indonesian</option>
										<option value="Khmer" <?php echo ($row['language'] == 'Khmer') ? 'Selected' : ''; ?>>Khmer</option>
										<option value="Czech" <?php echo ($row['language'] == 'Czech') ? 'Selected' : ''; ?>>Czech</option>
										<option value="Polish" <?php echo ($row['language'] == 'Polish') ? 'Selected' : ''; ?>>Polish</option>
										<option value="Macedonian" <?php echo ($row['language'] == 'Macedonian') ? 'Selected' : ''; ?>>Macedonian</option>
									</select>
								</div>

								<div class="col-lg-4 mb-3">
									<label class="form-label"><i class="mdi mdi-clock-outline me-1"></i><?= lang('UI_Text.Duration') ?> (<?= lang('UI_Text.Minutes') ?>)</label>
									<input type="number" class="form-control" name="duration" min="0"
										onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
										placeholder="<?= lang('UI_Text.Duration') ?>" value="<?php echo $row['duration'] ?>" />
								</div>

								<div class="col-lg-4 mb-3">
									<label class="form-label"><?= lang('UI_Text.Status') ?></label>
									<select name="mode" class="form-select">
										<option value="1" <?php echo ($row['mode'] == 1) ? 'selected' : ''; ?>>
											<?= lang('UI_Text.Development') ?></option>
										<option value="2" <?php echo ($row['mode'] == 2) ? 'selected' : ''; ?>>
											<?= lang('UI_Text.Live') ?></option>
										<?php if ($client == 1) { ?>
											<option value="3" <?php echo ($row['mode'] == 3) ? 'selected' : ''; ?>>
												<?= lang('UI_Text.Alpha') ?></option>
											<option value="4" <?php echo ($row['mode'] == 4) ? 'selected' : ''; ?>>
												<?= lang('UI_Text.Alpha_2') ?></option>
											<option value="5" <?php echo ($row['mode'] == 5) ? 'selected' : ''; ?>>
												<?= lang('UI_Text.Beta') ?></option>
											<option value="6" <?php echo ($row['mode'] == 6) ? 'selected' : ''; ?>>
												<?= lang('UI_Text.Beta_2') ?></option>
											<option value="7" <?php echo ($row['mode'] == 7) ? 'selected' : ''; ?>>
												<?= lang('UI_Text.Gamma') ?></option>
											<option value="8" <?php echo ($row['mode'] == 8) ? 'selected' : ''; ?>>
												<?= lang('UI_Text.Gamma_2') ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="col-lg-4 mb-3">
									<label class="form-label"><i class="mdi mdi-information-outline me-1" title="<?= lang('UI_Text.Coming_Soon') ?>"></i><?= lang('UI_Text.Default_Certificate') ?></label>
									<select class="form-select" disabled>
										<option><?= lang('UI_Text.No') ?></option>
									</select>
									<div class="form-text"><?= lang('UI_Text.Coming_Soon') ?></div>
								</div>
							</div>
						</div>
					</div>

					<div class="card settings-section mb-3">
						<div class="card-body">
							<h5 class="section-title"><i class="mdi mdi-cog-outline"></i> <?= lang('UI_Text.Course_Configuration') ?></h5>
							<div class="row align-items-end">
								<?php if ($client == 1) { ?>
									<div class="col-lg-4 mb-3">
										<label class="form-label"><?= lang('UI_Text.Course Type') ?></label>
										<select name="type" class="form-select">
											<option value="10" <?php echo ($row['type'] == 10) ? 'selected' : ''; ?>>SCORM</option>
											<option value="11" <?php echo ($row['type'] == 11) ? 'selected' : ''; ?>>Course Builder</option>
										</select>
									</div>
									<div class="col-lg-4 mb-3">
										<label class="form-label"><?= lang('UI_Text.Course Theme') ?></label>
										<select name="theme" class="form-select">
											<option value="1" <?php echo ($row['theme'] == 1) ? 'selected' : ''; ?>>Default</option>
											<option value="2" <?php echo ($row['theme'] == 2) ? 'selected' : ''; ?>>ContentforU</option>
											<option value="3" <?php echo ($row['theme'] == 3) ? 'selected' : ''; ?>>Assessment</option>
											<option value="4" <?php echo ($row['theme'] == 4) ? 'selected' : ''; ?>>Knowledge Works</option>
											<option value="5" <?php echo ($row['theme'] == 5) ? 'selected' : ''; ?>>Assessment Arabic</option>
											<option value="6" <?php echo ($row['theme'] == 6) ? 'selected' : ''; ?>>Wabtec Default</option>
											<option value="7" <?php echo ($row['theme'] == 7) ? 'selected' : ''; ?>>Vertical ContentforU</option>
											<option value="8" <?php echo ($row['theme'] == 8) ? 'selected' : ''; ?>>Modern Theme</option>
										</select>
									</div>
									<div class="col-lg-4 mb-3">
										<label class="form-label"><?= lang('UI_Text.Is Demo?') ?></label>
										<input type="hidden" name="demo" id="demoHiddenInput" value="<?php echo (int) $row['demo']; ?>">
										<div class="form-check form-switch demo-toggle">
											<input class="form-check-input" type="checkbox" role="switch" id="demoSwitch"
												<?php echo ((int) $row['demo'] === 1) ? 'checked' : ''; ?>
												onchange="document.getElementById('demoHiddenInput').value = this.checked ? 1 : 0; document.getElementById('demoSwitchLabel').textContent = this.checked ? '<?= lang('UI_Text.Yes') ?>' : '<?= lang('UI_Text.No') ?>';">
											<label class="form-check-label" id="demoSwitchLabel" for="demoSwitch">
												<?php echo ((int) $row['demo'] === 1) ? lang('UI_Text.Yes') : lang('UI_Text.No'); ?>
											</label>
										</div>
									</div>
									<div class="col-12">
										<span class="badge bg-danger">* <?= lang('UI_Text.Only_Touchstone') ?></span>
									</div>
								<?php } else { ?>
									<div class="col-lg-4 mb-3">
										<label class="form-label"><?= lang('UI_Text.Course Theme') ?></label>
										<select name="theme" class="form-select">
											<option value="3" <?php echo ($row['theme'] == 3) ? 'selected' : ''; ?>>Assessment</option>
											<option value="5" <?php echo ($row['theme'] == 5) ? 'selected' : ''; ?>>Assessment Arabic</option>
										</select>
									</div>
									<?php
									echo '<input type="hidden" name="theme" value="1">';
									echo '<input type="hidden" name="demo" value="0">';
									echo '<input type="hidden" name="type" value="' . $row['type'] . '">';
									?>
								<?php } ?>
							</div>
						</div>
					</div>

					<div class="card settings-section mb-3">
						<div class="card-body">
							<h5 class="section-title"><i class="mdi mdi-text-box-edit-outline"></i> <?= lang('UI_Text.Course Description') ?></h5>
							<textarea class="ckeditor" name="description"><?php echo $row['description'] ?></textarea>
						</div>
					</div>

					<div class="text-end mb-3">
						<button type="submit" class="btn btn-primary rounded-pill waves-effect waves-light">
							<i class="mdi mdi-content-save-outline me-1"></i><?= lang('Buttons.Save_Changes') ?>
						</button>
					</div>

					<input type="hidden" name="objectives" value="">
					<input type="hidden" name="tab" value="1">
					<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
					<!-- "status" here is the record's soft-delete flag (1 = keep). Deleting a course now
						 happens via the dedicated Quick Actions > Delete Course action instead of this form. -->
					<input type="hidden" name="status" value="1">

					<?php if (isset($courseeditvalidation)) : ?>
						<div class="alert alert-danger mt-2">
							<?= $courseeditvalidation->listErrors() ?>
						</div>
					<?php endif; ?>
				</form>
			</div> <!-- end tab-pane -->

			<div class="tab-pane <?php if ($tab == 2) echo "show active"; ?>" id="objectives">
				<div class="card settings-section mb-3">
					<div class="card-body">
						<h5 class="section-title"><i class="mdi mdi-target"></i> <?= lang('Statements.State_0004') ?></h5>
						<form class="form-horizontal" action="<?php echo base_url($form_objective) ?>"
							method="POST" id="submitForm"><?= csrf_field() ?>
							<div class="row align-items-center">
								<div class="col-lg-10 mb-2">
									<input type="text"
										class="form-control"
										name="objective"
										placeholder="<?= lang('Buttons.Objectives') ?>"
										required>
								</div>

								<div class="col-lg-2 text-end mb-2">
									<input type="hidden" name="tab" value="2">
									<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">

									<button type="submit"
										class="btn btn-primary rounded-pill waves-effect waves-light w-100"
										id="submitButton">
										<?= lang('Buttons.Add_New_Objective') ?>
									</button>
								</div>
							</div>
						</form>

						<table class="table table-bordered table-striped mt-2 mb-0">
							<thead>
								<tr>
									<th width="50px;">#</th>
									<th><?= lang('Buttons.Objectives') ?></th>
									<th width="100px;"><?= lang('UI_Text.Edit') ?></th>
									<th width="100px;"><?= lang('UI_Text.Delete') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$j = 0;
								foreach ($getAllObjectives as $objectives) {
									$j = $j + 1; ?>
									<tr>
										<td><?= $j ?></td>
										<td><?= $objectives['objective'] ?></td>
										<td>
											<button
												type="button"
												class="btn btn-outline-warning waves-effect btn-xs rounded-pill waves-light"
												data-bs-toggle="modal"
												data-bs-target="#editObjectiveModal<?= $objectives['sco_id'] ?>">
												<span class="mdi mdi-pencil-outline"></span>
												<?= lang('UI_Text.Edit') ?>
											</button>

											<div class="modal fade" id="editObjectiveModal<?= $objectives['sco_id'] ?>" tabindex="-1">
												<div class="modal-dialog modal-dialog-centered">
													<div class="modal-content">

														<div class="modal-header">
															<h5 class="modal-title">Edit Objective</h5>
															<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
														</div>

														<form class="form-horizontal"
															action="<?php echo base_url('SCORM/scorm_courses/update_objective'); ?>"
															method="POST">
															<?= csrf_field() ?>
															<div class="modal-body">
																<input type="hidden" name="tab" value="2">
																<input type="hidden" name="sco_id" value="<?= $objectives['sco_id'] ?>">

																<div class="mb-3">
																	<label>Objective</label>
																	<input type="text"
																		name="objective"
																		class="form-control"
																		value="<?= $objectives['objective'] ?>"
																		required>
																</div>

																<div class="text-center">
																	<button type="submit" class="btn btn-primary btn-xs rounded-pill waves-effect waves-light"><?= lang('Buttons.Submit') ?></button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</td>
										<td>
											<form class="form-horizontal" action="<?php echo base_url($delete_form); ?>"
												method="POST"><?= csrf_field() ?>
												<input type="hidden" name="tab" value="2">
												<input type="hidden" name="sco_id" value="<?php echo $objectives['sco_id'] ?>">
												<button type="submit" class="btn btn-outline-danger waves-effect btn-xs rounded-pill waves-light"
													onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span
														class="mdi mdi-trash-can-outline"></span> <?= lang('UI_Text.Delete') ?></button>
											</form>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div> <!-- end tab-pane -->

			<div class="tab-pane <?php if ($tab == 3) echo "show active"; ?>" id="thumbnail">
				<div class="row">
					<div class="col-md-6 mb-3">
						<div class="card settings-section h-100">
							<div class="card-body">
								<h5 class="section-title"><i class="mdi mdi-image-outline"></i> <?= lang('Buttons.Thumbnail') ?></h5>
								<?php
								$imagedisplay = 0;
								$folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_thumbnail/' . $scourse_id;
								if (is_dir($folderloc)) {
									$files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);

									foreach ($files2 as $key => $value) {
										if (strlen($value) > 3) {
											$file_parts = pathinfo($value);
											if ($file_parts['extension'] != 'DS_Store') {
												$imagedisplay = 1;
								?>
												<img class="img-thumbnail mb-2"
													src="<?php echo base_url() ?>/assets/assets/uploads/SCORM_course_thumbnail/<?php echo $scourse_id ?>/<?php echo $value ?>" />
												<form class="form-horizontal mt-2"
													action="<?php echo base_url('XAPI/XAPI_courses/del_file'); ?>" method="POST"><?= csrf_field() ?>
													<input type="hidden" name="tab" value="3">
													<input type="hidden" name="fileloc" value="<?php echo $folderloc . '/' . $value; ?>">
													<button type="submit"
														class="btn btn-outline-danger waves-effect btn-sm rounded-pill waves-light mb-3"
														onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><?php echo lang('Buttons.Delete_Thumbnail') ?></button>
												</form>
									<?php
											}
										}
									}
								}
								if ($imagedisplay == 0) {
								?>
									<form enctype="multipart/form-data" action="<?php echo base_url($form_url_1) ?>"
										method="post" id="submitForm"><?= csrf_field() ?>
										<p class="text-danger"><?= lang('Statements.State_0001') ?></p>

										<div class="mb-3">
											<label for="firstname" class="form-label"><?= lang('UI_Text.Upload_Thumbnail') ?></label><br>
											<input type="file" name="file" required class="form-control" />
										</div>
										<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
										<input type="hidden" name="tab" value="3">
										<button type="submit"
											class="btn btn-outline-success waves-effect btn-sm rounded-pill waves-light mb-3"
											id="submitButton"><?= lang('Buttons.Upload_Thumbnail') ?></button>
									</form>
									<?php if (isset($thumbnailvalidation)): ?>
										<div class="alert alert-danger" role="alert">
											<?= $thumbnailvalidation->listErrors() ?>
										</div>
									<?php endif; ?>
								<?php
								}
								?>
							</div>
						</div>
					</div>
					<div class="col-md-6 mb-3">
						<div class="card settings-section h-100">
							<div class="card-body">
								<h5 class="section-title"><i class="mdi mdi-video-outline"></i> <?= lang('UI_Text.Preview Video') ?></h5>
								<?php $videodisplay = 0;
								$folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_promovideo/' . $scourse_id;
								if (is_dir($folderloc)) {
									$files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
									foreach ($files2 as $key => $value) {
										if (strlen($value) > 3) {
											$file_parts = pathinfo($value);
											if ($file_parts['extension'] != 'DS_Store') {
												$videodisplay = 1;
								?>
												<video width="100%" class="mb-2" controls>
													<source
														src="<?php echo base_url() ?>/assets/assets/uploads/SCORM_course_promovideo/<?php echo $scourse_id ?>/<?php echo $value ?>"
														type="video/mp4">
													<?= lang('Statements.State_0002') ?>
												</video>
												<form class="form-horizontal mt-1"
													action="<?php echo base_url('XAPI/XAPI_courses/del_file'); ?>" method="POST"><?= csrf_field() ?>
													<input type="hidden" name="tab" value="3">
													<input type="hidden" name="fileloc" value="<?php echo $folderloc . '/' . $value; ?>">
													<button type="submit" class="btn btn-outline-danger waves-effect btn-sm rounded-pill waves-light"
														onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><?= lang('Buttons.Delete_Video') ?></button>
												</form>
									<?php
											}
										}
									}
								}
								?>
								<?php if ($videodisplay == 0) { ?>
									<div class="upload-recommend mb-3">
										<div class="upload-recommend-title"><?= lang('UI_Text.Recommended') ?>:</div>
										<ul>
											<li><i class="mdi mdi-check-circle-outline"></i> <?= lang('Statements.State_0016') ?></li>
											<li><i class="mdi mdi-check-circle-outline"></i> <?= lang('Statements.State_0017') ?></li>
											<li><i class="mdi mdi-check-circle-outline"></i> <?= lang('Statements.State_0018') ?></li>
											<li><i class="mdi mdi-check-circle-outline"></i> <?= lang('Statements.State_0019') ?></li>
										</ul>
									</div>

									<form class="form-horizontal2 mt-2" enctype="multipart/form-data" action=<?php echo base_url($form_url_3); ?> method="post" id="submitForm"><?= csrf_field() ?>

										<div class="mb-3">
											<input type="file" name="file" required class="form-control" />
										</div>
										<input type="hidden" name="tab" value="3">
										<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
										<button type="submit"
											class="btn btn-outline-success waves-effect btn-sm rounded-pill waves-light mb-3"
											id="submitButton"><?= lang('Buttons.Upload_Video') ?></button>
										<?php if (isset($promovalidation)): ?>
											<div class="alert alert-danger" role="alert">
												<?= $promovalidation->listErrors() ?>
											</div>
										<?php endif; ?>
									</form>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- end tab-pane -->
		</div>
	</div> <!-- end col -->

	<div class="col-xl-3 col-lg-4">
		<div class="card settings-section quick-actions-card">
			<div class="card-body">
				<h6 class="section-title"><i class="mdi mdi-lightning-bolt-outline"></i> <?= lang('UI_Text.Quick_Actions') ?></h6>

				<form action="<?php echo base_url('SCORM/scorm_courses/duplicateCourse'); ?>" method="POST"
					onsubmit="return confirm('<?php echo lang('Alert.Aler_021') ?>')"><?= csrf_field() ?>
					<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
					<button type="submit" class="quick-action-item">
						<i class="mdi mdi-content-copy"></i>
						<span class="label-text"><?= lang('UI_Text.Duplicate_Course') ?></span>
						<i class="mdi mdi-chevron-right"></i>
					</button>
				</form>
				<?php if ((int) $row['type'] === 10) : ?>
					<hr>
					<form action="<?php echo base_url('SCORM/scorm_courses/course_edit_view'); ?>" method="POST"><?= csrf_field() ?>
						<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
						<button type="submit" class="quick-action-item">
							<i class="mdi mdi-cloud-upload-outline"></i>
							<span class="label-text"><?= lang('Buttons.SCORM Upload') ?></span>
							<i class="mdi mdi-chevron-right"></i>
						</button>
					</form>
				<?php endif; ?>
				<hr>
				<form action="<?php echo base_url('XAPI/XAPI_courses/courseusersassigned_report'); ?>" method="POST"><?= csrf_field() ?>
					<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
					<button type="submit" class="quick-action-item">
						<i class="mdi mdi-account-multiple-plus-outline"></i>
						<span class="label-text"><?= lang('UI_Text.Assign_Users') ?></span>
						<i class="mdi mdi-chevron-right"></i>
					</button>
				</form>
				<hr>
				<form action="<?php echo base_url('SCORM/scorm_courses/deleteCoursedetails'); ?>" method="POST"
					onsubmit="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><?= csrf_field() ?>
					<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
					<button type="submit" class="quick-action-item text-danger">
						<i class="mdi mdi-trash-can-outline"></i>
						<span class="label-text"><?= lang('UI_Text.Delete_Course') ?></span>
						<i class="mdi mdi-chevron-right"></i>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
