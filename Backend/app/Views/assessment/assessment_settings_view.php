<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url($main_header_link) ?>"><?php echo $main_header ?></a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>">Pages</a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url($header_link_1) ?>"><?php echo $header_1 ?></a></li>

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
				Assessment Quiz Settings
			</a>
		</li>
		<li class="nav-item">
			<a href="#Template" data-bs-toggle="tab" aria-expanded="false" class="nav-link <?php if ($tab == 2) echo "active"; ?>">
				Assessment Quiz Template
			</a>
		</li>
	</ul>
	<?php $userlevel = session()->get('userlevel');
	$array  = array_map('intval', str_split($userlevel)); ?>
	<div class="tab-content">
		<div class="tab-pane <?php if ($tab == 1) echo "show active"; ?>" id="Settings">
			<div class="card">
				<div class="card-body">
					<table class="table  table-sm table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th width=5%>#</th>
								<th>Item</th>
								<th>Current Values</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 0;


							// $preTestEnabled = false;
							// foreach ($getAssessmentSettings as $setting) {
							// 	if ($setting['type'] == 3 && $setting['value'] == 'Enabled') {
							// 		$preTestEnabled = true;
							// 		break;
							// 	}
							// }

							$i = 0;
							foreach ($getAssessmentSettings as $value) {
								$type = $value['type'];
								if ($type == 5) continue;
								if ($type >= 31) continue;
								if ($type == 25) continue;

								$i++;
								if ($type < 20) {

									echo '<tr role="row" class="odd">';
									echo '<td class="dtr-control sorting_1" tabindex="0">' . $i . '</td>';
									echo '<td>';
									switch ($type) {
										case 1:
											echo "Randomize Questions (Default: Randomize Questions Disabled)";
											break;
										case 2:
											echo "Randomize Options (Default: Randomize Options Disabled)";
											break;
										case 3:
											echo "Pre-Test / Post-Test <b>(Note: When the toggle is ON, the Quiz is in Post-Test mode.
																	<br/>When the toggle is OFF, the Quiz is in Pre-Test mode.)</b>";
											break;
										case 4:
											echo "Pre-Attempt / Post-Attempt <b>(Note: When the toggle is ON, the Quiz is in Post-Attempt mode.
																	<br/>When the toggle is OFF, the Quiz is in Pre-Attempt mode.)</b>";
											break;
									}
									echo '</td>';
									echo '</td>';
									echo '<td>';
									echo '<form  action="' . base_url('Assessment/trainings/change_settings') . '" method="post"" >';
									echo' <?= csrf_field() ?>';
									echo '<input type="hidden" name="quiz_settings_type" value="' . $value['type'] . '"/>';
									echo '<input type="hidden" name="quiz_settings_id" value="' . $value['s_id'] . '"/>';
									echo '<input type="hidden" name="scourse_id" value="' . $value['scourse_id'] . '"/>';
									echo '<input type="hidden" name="page_id" value="' . $page_id . '"/>';
									echo '<input type="hidden" name="tab" value="1">';
									if ($value['value'] == 'Enabled') {
										echo '<input type="hidden" name="value" value="Disabled"/>';
										echo '<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-toggle-on"></li></button>';
									} else {
										echo '<input type="hidden" name="value" value="Enabled"/>';
										echo '<button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-toggle-off"></li></button>';
									}
									echo '</form>';
									echo '</td></tr>';
								} else {

									echo '<tr role="row" class="odd">';
									echo '<td class="dtr-control sorting_1" tabindex="0">' . $i . '</td>';
									echo '<td>';

									switch ($type) {
										case 21:
											echo "Duration (min)";
											break;
										case 22:
											echo "Max Questions";
											break;
										case 23:
											echo "Passing %";
											break;
										case 24:
											echo "Attempts Allowed";
											break;
									}
									echo '</td>';
									echo '</td>';
									echo '<td>';
									echo '<form  action="' . base_url('Assessment/trainings/change_settings') . '" method="post"" id="submitForm" >';
									echo' <?= csrf_field() ?>';
									echo '<input type="hidden" name="quiz_settings_type" value="' . $value['type'] . '"/>';
									echo '<input type="hidden" name="quiz_settings_id" value="' . $value['s_id'] . '"/>';
									echo '<input type="hidden" name="scourse_id" value="' . $value['scourse_id'] . '"/>
							<input type="hidden" name="page_id" value="' . $page_id . '"/><div class="row"><div class="col-lg-6"><input type="hidden" name="tab" value="1">';
									echo '<input type="number" class="form-control" maxlength="3"  min="0" max="300" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" name="value" value="' . $value['value'] . '" required/>&nbsp;&nbsp;';
									echo '</div><div class="col-lg-6"><button type="submit" class="btn btn-outline-info btn-xs rounded-pill waves-effect waves-ligh submitButton">Save</button></div></div>';
									echo '</form>';
									echo '</td></tr>';
								}
								// }
							}

							?>
						</tbody>
					</table>


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
	// Use event delegation or loop through all forms
	document.querySelectorAll('form').forEach(function(form) {
		form.addEventListener('submit', function(e) {
			var button = form.querySelector('.submitButton'); // Use class instead of ID

			// Check if the button is already disabled (indicating the form is being submitted)
			if (button.disabled) {
				e.preventDefault(); // Prevent the form from being submitted again
				return false;
			}

			// Disable the submit button and change its text to 'Submitting...'
			button.disabled = true;
			button.innerHTML = 'Submitting...';
		});
	});
</script>