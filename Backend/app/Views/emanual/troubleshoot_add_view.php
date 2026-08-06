<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"> <a href="<?php echo base_url('Emanual/emanual_product/document_view'); ?>">
							e-Manual
						</a>
					</li>
				</ol>
			</div>
			<h4 class="page-title">
				Troubleshoot
			</h4>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/troubleshoot_add') ?>" method="POST"><?= csrf_field() ?>
					<div class="col-md-12">
						<div class="form-group col-md-12 mb-2">
							<label>Troubleshoot Name</label>
							<input type="text" class="form-control col-md-12" name="troubleshoot_name" />
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Type</label>
							<select class="form-select" name="type">
								<option value="1">Major</option>
								<option value="2">Minor</option>
							</select>
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Description</label>
							<textarea class="form-control" name="description"></textarea>
						</div>
						<div class="form-group  col-md-12">
							<input type="hidden" name="emd_id" value="<?php echo $emd_id ?>">
							<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
								<i class="ace-icon fa fa-key bigger-110"></i> <?php echo  lang('Buttons.Create') ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card">
			<div class="card-body">
				<table id="searchdatatable" class="table table-sm table-bordered table-striped">
					<thead>
						<tr>
							<th width=5%>#</th>
							<th>Troubleshoot Name</th>
							<th>Type</th>
							<th>Links</th>
							<th>Edit</th>
					</thead>
					<tbody>

						<?php
						$j = 0;
						foreach ($troubleshoot_data as $troubleshoot) {
							$j = $j + 1;
						?>
							<tr>
								<td><?php echo $j; ?></td>
								<td><?php echo $troubleshoot['question'] ?></td>
								<td><?php $type = $troubleshoot['type'];
									if ($type == 1) {
										echo 'Major';
									}
									if ($type == 2) {
										echo ' - ';
									}
									?></td>
								<td>
									<form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/troubleshoot_link') ?>" method="POST"><?= csrf_field() ?>
										<input type="hidden" name="et_id" value="<?php echo $troubleshoot['et_id']; ?>">
										<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
									</form>
								</td>
								<td>
									<form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/troubleshoot_edit') ?>" method="POST"><?= csrf_field() ?>
										<input type="hidden" name="et_id" value="<?php echo $troubleshoot['et_id']; ?>">
										<button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
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