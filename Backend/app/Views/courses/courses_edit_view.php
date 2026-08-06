<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li>Projects List</li>
			<li><b>&nbsp;>&nbsp;</b>
				<a href="<?php echo base_url('Project/project_details?projectid=' . $projectid) ?>"><?php echo $projectDetails['0']['projectname'] ?></a>
			</li><b>&nbsp;>&nbsp;</b>
			
		</ol>
	</div>
</div>
<div class="row">

	<div class="col-md-4">
		<div class="block">
			<div class="content">
				<div class="x_panel">
					<form class="form-horizontal" action="<?php echo base_url('Project/project_details/editcourse?projectid=' . $projectid . '&course_id=' . $course_id) ?>" method="POST" id="submitForm"><?= csrf_field() ?>
						<div class="form-group col-md-12">
							<label>Course Name</label>
							<input type="text" class="form-control col-md-12" name="course_name" placeholder="course name" value="<?php echo $row['course_name'] ?>" />
						</div>
						<div class="form-group col-md-12">
							<label>Order by</label>
							<input type="number" class="form-control col-md-12" name="orderby" placeholder="num" value="<?php echo $row['orderby'] ?>" />
						</div>
						<div class="form-group  col-md-12">
							<label>Select Package</label>
							<select name="type" class="form-control col-md-12">
								<?php if (!empty($package)) {
									foreach ($package as $eachpackage) {
										if ($row['type'] == $eachpackage['id_d']) { ?>
											<option selected='selected' value="<?php echo $eachpackage['id_d'] ?>"><?php echo $eachpackage['name'] ?></option>
										<?php } else { ?>
											<option value="<?php echo $eachpackage['id_d'] ?>"><?php echo $eachpackage['name'] ?></option>
								<?php }
									}
								}
								?>
							</select>

						</div>
						<div class="form-group  col-md-12">
							<label>Select Course Status</label>
							<select name="status" class="form-control col-md-12">
								<?php if (!empty($colourstatus)) {
									foreach ($colourstatus as $eachccolourstatus) {
										if ($row['status'] == $eachccolourstatus['id_cs']) { ?>
											<option selected='selected' value="<?php echo $eachccolourstatus['id_cs'] ?>"><?php echo $eachccolourstatus['name'] ?></option>
										<?php } else { ?>
											<option value="<?php echo $eachccolourstatus['id_cs'] ?>"><?php echo $eachccolourstatus['name'] ?></option>
								<?php }
									}
								}
								?>
							</select>

						</div>
						<div class="form-group col-md-12">
							<label>Description</label>
							<input type="text" class="form-control col-md-12" name="description" placeholder="Description" value="<?php echo $row['description'] ?>" />
						</div>
						<div class="form-group col-md-12">
							<label>Completion %</label>
							<input type="number" class="form-control col-md-12" name="completion" placeholder="Completion" value="<?php echo $row['completion'] ?>" />
						</div>
						<div class="form-group col-md-12">
							<label>Notes</label>
							<textarea type="text" class="form-control col-md-12" name="notes" placeholder="Notes" value="<?php echo $row['notes'] ?>"><?php echo $row['notes'] ?></textarea>
						</div>

						<div class="form-group  col-md-12">
							<?php if (isset($courseeditvalidation)) : ?>
								<div class=col-12 col-sm-4>
									<div class="alert alert-danger" role="alert">
										<?= $courseeditvalidation->listErrors() ?>
									</div>
								</div>
							<?php endif; ?>
							<input type="hidden" name="user" value="">
							<input type="hidden" name="addprojects" value="1">
							<button type="submit" class="btn btn-warning btn-sm col-md-4" id="submitButton">
								<i class="ace-icon fa fa-key bigger-110"></i> Update
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>