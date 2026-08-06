<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url($sub_header_1_link) ?>"><?php echo $sub_header_1; ?></a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url($sub_header_2_link) ?>"><?php echo $sub_header_2; ?></a></li>
				
				</ol>
			</div>
			<h4 class="page-title"><?php echo $sub_header_3; ?></h4>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/update_output_variable') ?>" method="POST"><?= csrf_field() ?>
					<div class="mb-3">
						<div class="form-group col-md-12">
							<label>Variable Name {Developer}</label>
							<input type="text" class="form-control col-md-12" required name="var_name" value="<?php echo $outputVariables_details[0]['variable_name']; ?>" />
						</div>
					</div>
					<div class="mb-3">
						<div class="form-group col-md-12">
							<label>Verbs {<a href="<?php echo base_url('verbs/admin_verbs') ?>">Add New Verb</a>} </label>
							<select class="form-select" name="verbs">
								<?php
								foreach ($verbs as $verb) {
									$var = $verb['verb'];
									$curvar = $outputVariables_details[0]['variable_name'];
									echo '<option value="' . $var . '" ';
									if ($curvar == $var) {
										echo 'SELECTED';
									}
									echo ' >';
									echo  $var;
									echo '</option>';
								}
								?></td>
								?>
							</select>
						</div>
					</div>
					<div class="mb-3">
						<div class="form-group col-md-12">
							<label>Variable Description</label>
							<input type="text" class="form-control col-md-12" required name="variable_description" value="<?php echo $outputVariables_details[0]['variable_description']; ?>" />
						</div>
					</div>
					<div class="mb-3">
						<div class="form-group col-md-12">
							<label>Feedback</label>
							<input type="text" class="form-control col-md-12" name="feedback" value="<?php echo $outputVariables_details[0]['feedback']; ?>" />
						</div>
					</div>
					<div class="mb-3">
						<div class="form-group  col-md-12">
							<?php if (isset($coursevalidation)) : ?>
								<div class=col-12 col-sm-4>
									<div class="alert alert-danger" role="alert">
										<?= $coursevalidation->listErrors() ?>
									</div>
								</div>
							<?php endif; ?>
							<input type="hidden" name="xov" value="<?php echo $outputVariables_details[0]['xov']; ?>">
							<button type="submit" class="btn btn-warning btn-sm col-md-4">
								Update
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>