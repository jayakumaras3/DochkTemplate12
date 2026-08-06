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
	<div class="card">
		<div class="card-body">
			<form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/update_input_variable') ?>" method="POST"><?= csrf_field() ?>
				<div class="mb-3">
					<div class="form-group col-md-12">
						<label>Variable Name {Developer}</label>
						<input type="text" class="form-control col-md-12" required name="var_name" value="<?php echo $inputVariables_details[0]['variable_name']; ?>" />
					</div>
				</div>
				<div class="mb-3">
					<div class="form-group col-md-12">
						<label>Type of Variable</label>
						<?php $type = $inputVariables_details[0]['variable_type']; ?>
						<select class="form-select" name="var_type">
							<option value="1" <?php if ($type == 1) echo "SELECTED"; ?>>Text</option>
							<option value="2" <?php if ($type == 2) echo "SELECTED"; ?>>Dropdown</option>
						</select>
					</div>
				</div>
				<div class="mb-3">
					<div class="form-group col-md-12">
						<label>Instructions</label>
						<input type="text" class="form-control col-md-12" name="instructions" value="<?php echo $inputVariables_details[0]['instructions']; ?>" />
					</div>
				</div>
				<div class="mb-3">
					<div class="form-group col-md-12">
						<label>Short Description</label>
						<input type="text" class="form-control col-md-12" required name="description" value="<?php echo $inputVariables_details[0]['variable_description']; ?>" />
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
						<input type="hidden" name="xiv" value="<?php echo $inputVariables_details[0]['xiv']; ?>">
						<button type="submit" class="btn btn-warning btn-sm col-md-4">
							Update
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php if ($type == 1) { ?>
		<div class="row">
			<div class="card">
				<div class="card-body">
					<div class="mb-3">

						<form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/add_default_text_input_variable') ?>" method="POST"><?= csrf_field() ?>
							<div class="col-md-12">
								<div class="form-group col-md-12">
									<label>Default Text Description</label>
									<input type="text" class="form-control col-md-12" required name="default_text" value="<?php echo $inputVariables_details[0]['default_text']; ?>" />
								</div>
							</div>
							<div class="form-group  col-md-12">
								<?php if (isset($coursevalidation)) : ?>
									<div class=col-12 col-sm-12>
										<div class="alert alert-danger" role="alert">
											<?= $coursevalidation->listErrors() ?>
										</div>
									</div>
								<?php endif; ?>
								<input type="hidden" name="xiv" value="<?php echo $inputVariables_details[0]['xiv']; ?>">
								<button type="submit" class="btn btn-info btn-sm col-md-4">
									Save
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>


	<?php } ?>
	<?php if ($type == 2) { ?>
		<div class="col-md-6">
			<div class="row">
				<div class="card">
					<div class="card-body">
						<form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/add_dropdown_values') ?>" method="POST"><?= csrf_field() ?>
							<div class="mb-3">
								<div class="form-group col-md-12">
									<label>Dropdown Value</label>
									<select class="form-select" name="dropdown_val">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
							</div>
							<div class="mb-3">
								<div class="form-group col-md-12">
									<label>Dropdown Text</label>
									<input type="text" class="form-control col-md-12" required name="drop_down_text" value="" />
								</div>
							</div>
							<div class="form-group  col-md-12">
								<?php if (isset($coursevalidation)) : ?>
									<div class=col-12 col-sm-12>
										<div class="alert alert-danger" role="alert">
											<?= $coursevalidation->listErrors() ?>
										</div>
									</div>
								<?php endif; ?>
								<input type="hidden" name="xiv" value="<?php echo $inputVariables_details[0]['xiv']; ?>">
								<button type="submit" class="btn btn-info btn-sm col-md-4">
									Save
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
						<thead>
							<tr>
								<th>#</th>
								<th>Value</th>
								<th>Text</th>
								<th>Del</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$j = 0;
							foreach ($getDropDownValues as $dropdown) {
								$j = $j + 1; ?>
								<tr>
									<td><?= $j ?></td>
									<td><?= $dropdown['value'] ?></td>
									<td><?= $dropdown['text'] ?></td>
									<td>
										<form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/del_dropdown_variable'); ?>" method="POST"><?= csrf_field() ?>
											<input type="hidden" name="xidv" value="<?php echo $dropdown['xidv'] ?>">
											<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
										</form>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
</div>
<?php } ?>
