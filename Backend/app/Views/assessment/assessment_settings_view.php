<style>
	.qs-section-icon {
		width: 34px;
		height: 34px;
		border-radius: 9px;
		background: rgba(var(--ct-primary-rgb), 0.12);
		color: rgb(var(--ct-primary-rgb));
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		flex-shrink: 0;
	}

	.qs-header-icon {
		width: 48px;
		height: 48px;
		border-radius: 12px;
		background: rgba(var(--ct-primary-rgb), 0.12);
		color: rgb(var(--ct-primary-rgb));
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 24px;
		flex-shrink: 0;
	}

	.qs-card {
		border-radius: 16px;
		border: none;
		box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
	}

	[data-bs-theme="dark"] .qs-card {
		box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
	}

	.qs-card-title {
		font-weight: 700;
	}

	.qs-field-label {
		display: flex;
		align-items: center;
		gap: 6px;
		font-weight: 600;
		font-size: 13.5px;
		margin-bottom: 8px;
	}

	.qs-field-icon {
		color: rgb(var(--ct-primary-rgb));
		font-size: 15px;
	}

	.qs-field-status {
		display: inline-block;
		font-size: 11.5px;
		margin-top: 4px;
		min-height: 15px;
	}

	.qs-behavior-item {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		border: 1px solid var(--ct-border-color-translucent);
		border-radius: 12px;
		padding: 14px 16px;
	}

	.qs-behavior-icon {
		width: 38px;
		height: 38px;
		border-radius: 10px;
		background: rgba(var(--ct-primary-rgb), 0.12);
		color: rgb(var(--ct-primary-rgb));
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
		flex-shrink: 0;
	}

	.qs-behavior-title {
		font-weight: 600;
		font-size: 14px;
	}

	.qs-behavior-desc {
		font-size: 12px;
		color: var(--ct-secondary-color);
	}

	.qs-info-bar {
		display: flex;
		align-items: center;
		gap: 10px;
		background: rgba(var(--ct-primary-rgb), 0.06);
		border-radius: 10px;
		padding: 12px 16px;
		font-size: 13.5px;
		color: var(--ct-body-color);
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url($main_header_link) ?>"><?php echo $main_header ?></a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/course_builder/Editor') ?>"><?php echo lang('UI_Text.CB_Course_Builder'); ?></a></li>

				</ol>
			</div>
			<h4 class="page-title"><?php echo $sub_header_1; ?></h4>
		</div>
	</div>
</div>
<div class="row">
	<ul class="nav nav-pills nav-fill navtab-bg">
		<li class="nav-item">
			<a href="#Settings" data-bs-toggle="tab" aria-expanded="true" class="nav-link <?php if ($tab == 1) echo "active"; ?>">
				<?php echo lang('UI_Text.CB_General_Settings'); ?>
			</a>
		</li>
		<li class="nav-item">
			<a href="#Template" data-bs-toggle="tab" aria-expanded="false" class="nav-link <?php if ($tab == 2) echo "active"; ?>">
				<?php echo lang('UI_Text.CB_Template_Settings'); ?>
			</a>
		</li>
	</ul>
	<?php $userlevel = session()->get('userlevel');
	$array  = array_map('intval', str_split($userlevel)); ?>
	<div class="tab-content">
		<div class="tab-pane <?php if ($tab == 1) echo "show active"; ?>" id="Settings">
			<?php
			// Every field always renders, even before its first save - looked up by type from
			// $getAssessmentSettings rather than iterating it directly, so a setting that's
			// never been touched still shows with a sensible default instead of disappearing
			// from the page.
			$qsByType = [];
			foreach ($getAssessmentSettings as $eachSetting) {
				$qsByType[$eachSetting['type']] = $eachSetting;
			}
			$qsAttempts    = $qsByType[24]['value'] ?? '';
			$qsAttemptsSid = $qsByType[24]['s_id'] ?? 0;
			$qsPassing     = $qsByType[23]['value'] ?? '';
			$qsPassingSid  = $qsByType[23]['s_id'] ?? 0;
			$qsMaxQ        = $qsByType[22]['value'] ?? '';
			$qsMaxQSid     = $qsByType[22]['s_id'] ?? 0;
			$qsDuration    = $qsByType[21]['value'] ?? '';
			$qsDurationSid = $qsByType[21]['s_id'] ?? 0;

			$qsPreAttempt       = (($qsByType[4]['value'] ?? 'Disabled') === 'Enabled');
			$qsPreAttemptSid    = $qsByType[4]['s_id'] ?? 0;
			$qsPreTest          = (($qsByType[3]['value'] ?? 'Disabled') === 'Enabled');
			$qsPreTestSid       = $qsByType[3]['s_id'] ?? 0;
			$qsRandOptions      = (($qsByType[2]['value'] ?? 'Disabled') === 'Enabled');
			$qsRandOptionsSid   = $qsByType[2]['s_id'] ?? 0;
			$qsRandQuestions    = (($qsByType[1]['value'] ?? 'Disabled') === 'Enabled');
			$qsRandQuestionsSid = $qsByType[1]['s_id'] ?? 0;
			?>

			<div class="card qs-card mb-3">
				<div class="card-body">
					<div class="row g-3">
						<div class="col-6 col-lg-3">
							<div class="qs-field-label"><i class="mdi mdi-target qs-field-icon"></i> <?php echo lang('UI_Text.CB_Attempts_Allowed'); ?></div>
							<div class="input-group">
								<input type="number" min="0" class="form-control qs-auto-save" id="qsAttempts" data-type="24" data-sid="<?php echo (int) $qsAttemptsSid; ?>" value="<?php echo esc($qsAttempts); ?>" onblur="qsSaveSetting(this)">
								<span class="input-group-text"><?php echo lang('UI_Text.CB_Attempts'); ?></span>
							</div>
							<span id="qsAttemptsStatus" class="qs-field-status"></span>
						</div>
						<div class="col-6 col-lg-3">
							<div class="qs-field-label"><i class="mdi mdi-target qs-field-icon"></i> <?php echo lang('UI_Text.CB_Passing_Percentage'); ?></div>
							<div class="input-group">
								<input type="number" min="0" max="100" class="form-control qs-auto-save" id="qsPassing" data-type="23" data-sid="<?php echo (int) $qsPassingSid; ?>" value="<?php echo esc($qsPassing); ?>" onblur="qsSaveSetting(this)">
								<span class="input-group-text">%</span>
							</div>
							<span id="qsPassingStatus" class="qs-field-status"></span>
						</div>
						<div class="col-6 col-lg-3">
							<div class="qs-field-label"><i class="mdi mdi-format-list-bulleted qs-field-icon"></i> <?php echo lang('UI_Text.CB_Maximum_Questions'); ?></div>
							<div class="input-group">
								<input type="number" min="0" class="form-control qs-auto-save" id="qsMaxQ" data-type="22" data-sid="<?php echo (int) $qsMaxQSid; ?>" value="<?php echo esc($qsMaxQ); ?>" onblur="qsSaveSetting(this)">
								<span class="input-group-text"><?php echo lang('UI_Text.CB_Questions'); ?></span>
							</div>
							<span id="qsMaxQStatus" class="qs-field-status"></span>
						</div>
						<div class="col-6 col-lg-3">
							<div class="qs-field-label"><i class="mdi mdi-clock-outline qs-field-icon"></i> <?php echo lang('UI_Text.CB_Duration'); ?></div>
							<div class="input-group">
								<input type="number" min="0" max="300" class="form-control qs-auto-save" id="qsDuration" data-type="21" data-sid="<?php echo (int) $qsDurationSid; ?>" value="<?php echo esc($qsDuration); ?>" onblur="qsSaveSetting(this)">
								<span class="input-group-text"><?php echo lang('UI_Text.CB_Minutes'); ?></span>
							</div>
							<span id="qsDurationStatus" class="qs-field-status"></span>
						</div>
					</div>
				</div>
			</div>

			<div class="card qs-card mb-3">
				<div class="card-body">
					<div class="d-flex align-items-center gap-2 mb-3">
						<span class="qs-section-icon"><i class="mdi mdi-shuffle-variant"></i></span>
						<h5 class="qs-card-title mb-0"><?php echo lang('UI_Text.CB_Quiz_Behavior'); ?></h5>
					</div>
					<div class="row g-3">
						<div class="col-md-6">
							<div class="qs-behavior-item">
								<div class="d-flex align-items-center gap-3">
									<span class="qs-behavior-icon"><i class="mdi mdi-monitor"></i></span>
									<div>
										<div class="qs-behavior-title"><?php echo lang('UI_Text.CB_Pre_Attempt_Post_Attempt'); ?></div>
										<div class="qs-behavior-desc"><?php echo lang('UI_Text.CB_Pre_Attempt_Post_Attempt_Desc'); ?></div>
									</div>
								</div>
								<div class="form-check form-switch mb-0">
									<input class="form-check-input qs-auto-save" type="checkbox" id="qsPreAttempt" data-type="4" data-sid="<?php echo (int) $qsPreAttemptSid; ?>" <?php echo $qsPreAttempt ? 'checked' : ''; ?> onchange="qsSaveSetting(this)">
								</div>
							</div>
							<span id="qsPreAttemptStatus" class="qs-field-status"></span>
						</div>
						<div class="col-md-6">
							<div class="qs-behavior-item">
								<div class="d-flex align-items-center gap-3">
									<span class="qs-behavior-icon"><i class="mdi mdi-monitor"></i></span>
									<div>
										<div class="qs-behavior-title"><?php echo lang('UI_Text.CB_Pre_Test_Post_Test'); ?></div>
										<div class="qs-behavior-desc"><?php echo lang('UI_Text.CB_Pre_Test_Post_Test_Desc'); ?></div>
									</div>
								</div>
								<div class="form-check form-switch mb-0">
									<input class="form-check-input qs-auto-save" type="checkbox" id="qsPreTest" data-type="3" data-sid="<?php echo (int) $qsPreTestSid; ?>" <?php echo $qsPreTest ? 'checked' : ''; ?> onchange="qsSaveSetting(this)">
								</div>
							</div>
							<span id="qsPreTestStatus" class="qs-field-status"></span>
						</div>
						<div class="col-md-6">
							<div class="qs-behavior-item">
								<div class="d-flex align-items-center gap-3">
									<span class="qs-behavior-icon"><i class="mdi mdi-shuffle-variant"></i></span>
									<div>
										<div class="qs-behavior-title"><?php echo lang('UI_Text.CB_Randomize_Options'); ?></div>
										<div class="qs-behavior-desc"><?php echo lang('UI_Text.CB_Randomize_Options_Desc'); ?></div>
									</div>
								</div>
								<div class="form-check form-switch mb-0">
									<input class="form-check-input qs-auto-save" type="checkbox" id="qsRandOptions" data-type="2" data-sid="<?php echo (int) $qsRandOptionsSid; ?>" <?php echo $qsRandOptions ? 'checked' : ''; ?> onchange="qsSaveSetting(this)">
								</div>
							</div>
							<span id="qsRandOptionsStatus" class="qs-field-status"></span>
						</div>
						<div class="col-md-6">
							<div class="qs-behavior-item">
								<div class="d-flex align-items-center gap-3">
									<span class="qs-behavior-icon"><i class="mdi mdi-format-list-bulleted"></i></span>
									<div>
										<div class="qs-behavior-title"><?php echo lang('UI_Text.CB_Randomize_Questions'); ?></div>
										<div class="qs-behavior-desc"><?php echo lang('UI_Text.CB_Randomize_Questions_Desc'); ?></div>
									</div>
								</div>
								<div class="form-check form-switch mb-0">
									<input class="form-check-input qs-auto-save" type="checkbox" id="qsRandQuestions" data-type="1" data-sid="<?php echo (int) $qsRandQuestionsSid; ?>" <?php echo $qsRandQuestions ? 'checked' : ''; ?> onchange="qsSaveSetting(this)">
								</div>
							</div>
							<span id="qsRandQuestionsStatus" class="qs-field-status"></span>
						</div>
					</div>

					<div class="qs-info-bar mt-3">
						<i class="mdi mdi-information-outline"></i>
						<span><?php echo lang('UI_Text.CB_Changes_Apply_Future_Attempts'); ?></span>
					</div>
				</div>
			</div>
		</div>


		<div class="tab-pane <?php if ($tab == 2) echo "show active"; ?>" id="Template">
			<div class="card">
				<div class="card-body">
					<div class="col-md-12">
						<P>NOTE : Respective Default Template will be display if you are not filled any fields </P>
						<?php //$getAssessmentSets = '';
						foreach ($assessment_sets as $x => $sets) {
							// print_r($AssessmentSettings[$x]);
							// exit();
							if (!empty($AssessmentSettings[$x])) {
								$item = $AssessmentSettings[$x][0]['value'];
								$s_id = $AssessmentSettings[$x][0]['s_id'];


						?>
								<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>" method="POST"><?= csrf_field() ?>
									 <?= csrf_field() ?>
									<input type="hidden" name="quiz_settings_type" value="<?php echo $x ?>">
									<input type="hidden" name="add_or_update" value="2">
									<input type="hidden" name="s_id" value="<?php echo $s_id; ?>">
									<input type="hidden" name="scourse_id" value="<?php echo $getAssessmentSettings[0]['scourse_id']; ?>">
									<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
									<input type="hidden" name="tab" value="2">
									<div class="row">
										<label><b>Default : </b><?php echo $sets ?></label><br>
										<div class="col-lg-10">
											<input class="form-control" name="valid" type="hidden" />
											<input class="form-control" name="value" type="input" value="<?php echo isset($item) ? $item : $sets; ?>" />
										</div>
										<div class="col-lg-2">
											<button type="submit" class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light mt-2">
												Update</button>
										</div>
									</div><br />
								</form>
							<?php
							} else {
							?>
								<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>" method="POST"><?= csrf_field() ?>
									 <?= csrf_field() ?>
									<input type="hidden" name="quiz_settings_type" value=" <?php echo $x ?>">
									<input type="hidden" name="add_or_update" value="1">
									<input type="hidden" name="s_id" value="0">
									<input type="hidden" name="quiz_settings_id" value="<?php echo isset($getAssessmentSettings[0]['s_id']) ? $getAssessmentSettings[0]['s_id'] : ''; ?>">
									<input type="hidden" name="scourse_id" value="<?php echo isset($getAssessmentSettings[0]['scourse_id']) ? $getAssessmentSettings[0]['scourse_id'] : ''; ?>">
									<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
									<input type="hidden" name="tab" value="2">
									<div class="row">
										<div class="col-lg-10">

											<label><b>Default :</b> <?php echo $sets ?></label><br>
											<input class="form-control" name="valid" type="hidden" />
											<input name="value" class="form-control" value="" required />
										</div>
										<div class="col-lg-2">
											<button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light mt-2">
												Submit</button>
										</div>
									</div><br />
								</form>
						<?php
							}
						}
						?>
					</div>
					<!-- <div class="col-md-6">
					<?php if ($getAssessmentSettings32) {
						$item32 = $getAssessmentSettings32[0]['value'];
						$s_id = $getAssessmentSettings32[0]['s_id'];
					?>
						<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>" method="POST"><?= csrf_field() ?>
							<input type="hidden" name="quiz_settings_type" value="32">
							<input type="hidden" name="add_or_update" value="2">
							<input type="hidden" name="s_id" value="<?php echo $s_id; ?>">
							<input type="hidden" name="scourse_id" value="<?php echo $getAssessmentSettings[0]['scourse_id']; ?>">
							<div class="col-lg-12">

								<input class="form-control" name="valid" type="hidden" />
								<textarea class="ckeditor" name="value"><?php echo $item32; ?></textarea>
							</div>
							<button type="submit" class="btn btn-outline-info btn-xs rounded-pill waves-effect waves-light mt-2">
								Update Result Page Description</button>
						</form>
					<?php
					} else {
					?>
						<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>" method="POST"><?= csrf_field() ?>
							<input type="hidden" name="quiz_settings_type" value="32">
							<input type="hidden" name="add_or_update" value="1">
							<input type="hidden" name="s_id" value="0">
							<input type="hidden" name="quiz_settings_id" value="<?php //echo $getAssessmentSettings[0]['s_id']; 
																				?>">
							<input type="hidden" name="scourse_id" value="<?php //echo $getAssessmentSettings[0]['scourse_id']; 
																			?>">
							<div class="col-lg-12">

								<input class="form-control" name="valid" type="hidden" />
								<textarea class="ckeditor" name="value"> </textarea>
							</div>
							<button type="submit" class="btn btn-outline-info btn-xs rounded-pill waves-effect waves-light mt-2">
								Add Result Page Description</button>
						</form>
					<?php
					}
					?>
				</div> -->
					<div class="col-md-6">
						<?php if ($getAssessmentSettings31) {
							$item31 = $getAssessmentSettings31[0]['value'];
							$s_id = $getAssessmentSettings31[0]['s_id'];
						?>
							<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>" method="POST"><?= csrf_field() ?>
								 <?= csrf_field() ?>
								<input type="hidden" name="quiz_settings_type" value="31">
								<input type="hidden" name="add_or_update" value="2">
								<input type="hidden" name="s_id" value="<?php echo $s_id; ?>">
								<input type="hidden" name="scourse_id" value="<?php echo isset($getAssessmentSettings[0]['scourse_id']) ? $getAssessmentSettings[0]['scourse_id'] : ''; ?>">
								<div class="col-lg-12">

									<input class="form-control" name="valid" type="hidden" />
									<textarea class="ckeditor" name="value"><?php echo $item31; ?></textarea>
								</div>
								<button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light mt-2">
									Update Start Page Description</button>
							</form>
						<?php
						} else {
						?>
							<form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>" method="POST"><?= csrf_field() ?>
								 <?= csrf_field() ?>
								<input type="hidden" name="quiz_settings_type" value="31">
								<input type="hidden" name="add_or_update" value="1">
								<input type="hidden" name="s_id" value="0">
								<input type="hidden" name="quiz_settings_id" value="<?php echo isset($getAssessmentSettings[0]['s_id']) ? $getAssessmentSettings[0]['s_id'] : ''; ?>">
								<input type="hidden" name="scourse_id" value="<?php echo isset($getAssessmentSettings[0]['scourse_id']) ? $getAssessmentSettings[0]['scourse_id'] : ''; ?>">
								<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
								<div class="col-lg-12">

									<input class="form-control" name="valid" type="hidden" />
									<textarea class="ckeditor" name="value" required> </textarea>
								</div>
								<button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light mt-2">
									Add Start Page Description</button>
							</form>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Template tab forms only now - the Settings tab's number/toggle fields save themselves via
	// qsSaveSetting() below instead of a form submit. Guarded on the button existing at all,
	// since not every form here necessarily has one.
	document.querySelectorAll('form').forEach(function(form) {
		form.addEventListener('submit', function(e) {
			var button = form.querySelector('.submitButton') || form.querySelector('button[type="submit"]');
			if (!button) return;

			if (button.disabled) {
				e.preventDefault();
				return false;
			}

			button.disabled = true;
			button.innerHTML = 'Submitting...';
		});
	});

	// Auto-saves every General Settings field / Quiz Behavior toggle on blur/change - no page
	// reload, so the row's s_id (stored on the element itself) is kept in sync with each
	// response, otherwise a second edit to the same field without a reload would deactivate a
	// row that's no longer the current one.
	function qsSaveSetting(el) {
		var type = el.dataset.type;
		var sid = parseInt(el.dataset.sid || '0', 10);
		var value = (el.type === 'checkbox') ? (el.checked ? 'Enabled' : 'Disabled') : el.value;
		var statusEl = document.getElementById(el.id + 'Status');

		if (statusEl) {
			statusEl.textContent = '<?php echo lang('UI_Text.CB_Saving_Ellipsis'); ?>';
			statusEl.className = 'qs-field-status text-muted';
		}

		var formData = new FormData();
		formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
		formData.append('quiz_settings_type', type);
		formData.append('quiz_settings_id', sid);
		formData.append('scourse_id', '<?php echo $scourse_id; ?>');
		formData.append('page_id', '<?php echo $page_id; ?>');
		formData.append('tab', '1');
		formData.append('value', value);

		fetch('<?php echo base_url('Assessment/trainings/change_settings') ?>', {
				method: 'POST',
				body: formData,
				credentials: 'same-origin'
			})
			.then(function(response) {
				if (!response.ok) throw new Error('save failed');
				return response.json();
			})
			.then(function(obj) {
				if (obj.status === 'OK') {
					if (obj.s_id) el.dataset.sid = obj.s_id;
					if (statusEl) {
						statusEl.textContent = '<?php echo lang('UI_Text.CB_Saved'); ?>';
						statusEl.className = 'qs-field-status text-success';
						setTimeout(function() {
							statusEl.textContent = '';
						}, 2000);
					}
				} else if (statusEl) {
					statusEl.textContent = '<?php echo lang('UI_Text.CB_Failed_To_Save'); ?>';
					statusEl.className = 'qs-field-status text-danger';
				}
			})
			.catch(function() {
				if (statusEl) {
					statusEl.textContent = '<?php echo lang('UI_Text.CB_Failed_To_Save'); ?>';
					statusEl.className = 'qs-field-status text-danger';
				}
			});
	}
</script>
