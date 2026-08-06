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
				Troubleshooting Edit
			</h4>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/troubleshoot_update') ?>" method="POST"><?= csrf_field() ?>
					<div class="col-md-12">
						<div class="form-group col-md-12 mb-2">
							<label>Question</label>
							<input type="text" class="form-control col-md-12" name="question"  value="<?php echo $troubleshoot_data[0]['question'] ?>" />
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Description</label>
							<textarea class="form-control col-md-12" name="description"><?php echo $troubleshoot_data[0]['description'] ?></textarea>
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Delete the troubleshooting?</label>
							<select name="status" class="form-control">
								<option value="1">NO</option>
								<option value="0">Yes</option>
							</select>
						</div>
						<div class="form-group  col-md-12">
							<input type="hidden" name="et_id" value="<?php echo $et_id ?>">
							<button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">
								Update
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>