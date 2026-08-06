<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b>
			<li class="active"><?php echo $sub_header_1; ?></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="x_panel">
			<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
					<div class="form-group col-md-12">
						<label>Course Name</label>
						<input type="text" class="form-control col-md-12" required name="course_name" placeholder="Course Name" />
					</div>
					<div class="form-group col-md-12">
						<label>Duration (min)</label>
						<input type="text" class="form-control col-md-12" name="duration" placeholder="Duration (min)" />
					</div>	
					<div class="form-group col-md-12">
						<label>Description</label>
						<input class="form-control" name="valid" type="hidden" />
						<textarea class="ckeditor" name="description"></textarea>
					</div>
					<div class="form-group col-md-12">
						<label>Objectives</label>
						<input class="form-control" name="valid" type="hidden" />
						<textarea class="ckeditor" name="objectives"></textarea>
					</div>
					<div class="form-group col-md-12">
					<input type="hidden" name="user" value="">
					<input type="hidden" name="addprojects" value="1">
					<input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
					<button type="submit" class="btn btn-info btn-sm col-md-4">
						<i class="ace-icon fa fa-key bigger-110"></i> <?php echo  lang('Buttons.Create') ?>
					</button>
					</div>
			</form>
		</div>
	</div>
</div>