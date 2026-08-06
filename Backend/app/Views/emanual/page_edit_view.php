<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"> <a href="<?php echo base_url($sub_header_2_link); ?>">
							Page View
						</a>
					</li>
				</ol>
			</div>
			<h4 class="page-title">
				<?php echo $sub_header_1; ?>
			</h4>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
					<div class="col-md-12">
						<div class="form-group col-md-12 mb-2">
							<input type="text" class="form-control col-md-12" name="page_name" placeholder="Page name" value="<?php echo $row['page_name'] ?>" />
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Page Number</label>
							<input type="number" class="form-control col-md-12" name="page_number" value="<?php echo $row['page_number'] ?>" />
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Delete the page?</label>
							<select name="status" class="form-control">
								<option value="1">NO</option>
								<option value="0">Yes</option>
							</select>
						</div>
						<div class="form-group  col-md-12">
							<input type="hidden" name="user" value="">
							<input type="hidden" name="empg_id" value="<?php echo $empg_id ?>">
							<button type="submit" class="btn btn-warning btn-sm col-md-12">
								<i class="ace-icon fa fa-key bigger-110"></i> Update
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>