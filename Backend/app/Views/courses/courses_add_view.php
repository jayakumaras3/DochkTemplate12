<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li>Projects List</li>
			<li><b>&nbsp;>&nbsp;</b>
				<a href="<?php echo base_url('Project/project_details?projectid=' . $projectid) ?>"><?php echo $projectDetails['0']['projectname'] ?></a>
			</li><b> &nbsp;>&nbsp;</b>
	
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="x_panel">
			<form class="form-horizontal" action="<?php echo base_url('Project/project_details/addcourse?projectid=' . $projectid) ?>" method="POST"><?= csrf_field() ?>
				<div class="form-group col-md-12">
					<label>Course Name</label>
					<input type="text" class="form-control col-md-12" name="course_name" placeholder="course name" />
				</div>
				<div class="form-group col-md-12">
					<label>Order by</label>
					<input type="number" class="form-control col-md-12" name="orderby" placeholder="num" />
				</div>
				<div class="form-group  col-md-12">
					<label>Select Package</label>
					<select name="type" class="form-control col-md-12">
						<?php foreach ($package as $eachpackage) { ?>
							<option value="<?php echo $eachpackage['id_d'] ?>"><?php echo $eachpackage['name'] ?></option>
						<?php } ?>
					</select>

				</div>
				<div class="form-group  col-md-12">
					<label>Select Course Status</label>
					<select name="status" class="form-control col-md-12">
						<?php foreach ($colourstatus as $eachccolourstatus) { ?>
							<option value="<?php echo $eachccolourstatus['id_cs'] ?>"><?php echo $eachccolourstatus['name'] ?></option>
						<?php } ?>
					</select>

				</div>
				<div class="form-group col-md-12">
					<label>Description</label>
					<input type="text" class="form-control col-md-12" name="description" placeholder="Description" />
				</div>
				<div class="form-group col-md-12">
					<label>Notes</label>
					<textarea type="text" class="form-control col-md-12" name="notes" placeholder="Notes"></textarea>
				</div>
				<div class="form-group  col-md-12">
					<?php if (isset($coursevalidation)) : ?>
						<div class=col-12 col-sm-4>
							<div class="alert alert-danger" role="alert">
								<?= $coursevalidation->listErrors() ?>
							</div>
						</div>
					<?php endif; ?>
					<input type="hidden" name="user" value="">
					<input type="hidden" name="addprojects" value="1">
					<button type="submit" class="btn btn-info btn-sm col-md-4">
						<i class="ace-icon fa fa-key bigger-110"></i> <?php echo  lang('Buttons.Create') ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>