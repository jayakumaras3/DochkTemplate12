<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"> <a href="<?php echo base_url('Emanual/emanual_product/troubleshoot_view') ?>">
							Troubleshooting Dashboard
						</a>
					</li>
				</ol>
			</div>
			<h4 class="page-title">
				Troubleshooting Links
			</h4>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/troubleshoot_addLink') ?>" method="POST"><?= csrf_field() ?>
					<div class="col-md-12">
						<div class="form-group col-md-12 mb-2">
							<label>Question</label>
							<select class="form-select" name="link_id">
								<?php
								foreach ($troubleshoot_data as $trouble_links_data) {
									echo '<option value="' . $trouble_links_data['et_id'] . '">' . $trouble_links_data['question'] . '</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group  col-md-12">
							<input type="hidden" name="et_id" value="<?php echo $et_id ?>">
							<input type="hidden" name="link_type" value="1">
							<button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">
								Add Next Options
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<td>Options</td>
							<td>Description</td>
							<td>Del</td>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($trouble_links as $data) {
						?>
							<tr>
								<?php
								echo '<td>' . $data['question'] . '</td><td>' . $data['description'] . '</td>';
								echo '<td>';
								?>
								<form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/del_trouble_link') ?>" method="POST"><?= csrf_field() ?>
									<input type="hidden" name="etl_id" value="<?php echo $data['etl_id']; ?>">
									<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
								</form>
								<?php
								echo '</td>';
								?>
							</tr>
						<?php
						}
						?>

					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/troubleshoot_addLink') ?>" method="POST"><?= csrf_field() ?>
					<div class="col-md-12">
						<div class="form-group col-md-12 mb-2">
							<label>Documents</label>
							<select class="form-select" name="link_id">
								<?php
								foreach ($getAssignpages as $getAssignpage) {
									echo '<option value="' . $getAssignpage['empg_id'] . '">' . $getAssignpage['page_name'] . '</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group  col-md-12">
							<input type="hidden" name="et_id" value="<?php echo $et_id ?>">
							<input type="hidden" name="link_type" value="2">
							<button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">
								Add Page Link
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<td>Page</td>
							<td>Del</td>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($trouble_pages as $data_page) {
						?>
							<tr>
								<?php
								echo '<td>' . $data_page['page_name'] . '</td>';
								echo '<td width="20px">';
								?>
								<form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/del_trouble_link') ?>" method="POST"><?= csrf_field() ?>
									<input type="hidden" name="etl_id" value="<?php echo $data_page['etl_id']; ?>">
									<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
								</form>
								<?php
								echo '</td>';
								?>
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
</div>