<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url("SCORM/scorm_courses") ?>">My Courses</a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more') ?>">Course Detail</a></li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $sub_header_2; ?></h4>
		</div>
	</div>
</div>
<div class="col-lg-8">
	<div class="card">
		<div class="card-body">
			<form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/add_input_variable') ?>" method="POST"><?= csrf_field() ?>
				<div class="row mb-3">
					<label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Variable Name {Developer}</label>
					<div class="col-8 col-xl-9">
						<input type="text" class="form-control col-md-12" required name="var_name" placeholder="Variable Name" />
					</div>
				</div>
				<div class="row mb-3">
					<label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Type of Variable</label>
					<div class="col-8 col-xl-9">
						<select class="form-select" name="var_type">
							<option value="1">Text</option>
							<option value="2">Dropdown</option>
						</select>
					</div>
				</div>
				<div class="row mb-3">
					<label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Instructions</label>
					<div class="col-8 col-xl-9">
						<input type="text" class="form-control col-md-12" name="instructions" placeholder="Instructions" />
					</div>
				</div>
				<div class="row mb-3">
					<label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Short Description</label>
					<div class="col-8 col-xl-9">
						<input type="text" class="form-control col-md-12" required name="description" placeholder="Description" />
					</div>
				</div>
				<?php if (isset($coursevalidation)) : ?>
					<div class=col-12 col-sm-4>
						<div class="alert alert-danger" role="alert">
							<?= $coursevalidation->listErrors() ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="justify-content-end row">
					<div class="col-8 col-xl-9">
						<input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
						<button type="submit" class="btn btn-info btn-sm col-md-4">
							<?php echo  lang('Buttons.Create') ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
					<thead>
						<tr>
							<th>#</th>
							<th>Variable Name</th>
							<th>Description</th>
							<th>Instructions</th>
							<th>Variable Value</th>
							<th>Edit</th>
							<th>Del</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$j = 0;
						foreach ($inputVariables as $inputVar) {
							$j = $j + 1; ?>
							<tr>
								<td><?= $j ?></td>
								<td><?= $inputVar['variable_name'] ?></td>
								<td><?= $inputVar['variable_description'] ?></td>
								<td><?= $inputVar['instructions'] ?></td>
								<td><?php $type = $inputVar['variable_type'];
									if ($type == 1) {
										echo $inputVar['default_text'];
									}
									if ($type == 2) {
										echo isset($inputVar['textName']) ? $inputVar['textName'] : 'Dropdown Values';
									}
									?>
								</td>
								<td>
									<form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/view_input_variable') ?>" method="POST"><?= csrf_field() ?>
										<input type="hidden" name="xiv" value="<?php echo $inputVar['xiv'] ?>">
										<button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
									</form>
								</td>
								<td>
									<form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/del_input_variable'); ?>" method="POST"><?= csrf_field() ?>
										<input type="hidden" name="xiv" value="<?php echo $inputVar['xiv'] ?>">
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
</div>