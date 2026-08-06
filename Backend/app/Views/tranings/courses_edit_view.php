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
		<div class="block"> 
			<div class="content">
				<div class="x_panel">
					<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>

						<div class="form-group col-md-12">
							<label>Course Name</label>
							<input type="text" class="form-control col-md-12" name="course_name" required placeholder="course name" value="<?php echo $row['course_name'] ?>" />
						</div>
						<div class="form-group col-md-12">
							<label>Duration (min)</label>
							<input type="text" class="form-control col-md-12" name="duration" placeholder="Duration (min)" value="<?php echo $row['duration'] ?>" />
						</div>
						<div class="form-group col-md-12">
							<label>Description</label>
							<input class="form-control" name="valid" type="hidden" />
							<textarea class="ckeditor" name="description"><?php echo $row['description'] ?></textarea>
						</div>
						<div class="form-group col-md-12">
							<label>Objectives</label>
							<input class="form-control" name="valid" type="hidden" />
							<textarea class="ckeditor" name="objectives"><?php echo $row['objectives'] ?></textarea>
						</div>
						<div class="form-group  col-md-12">
							<input type="hidden" name="user" value="">
							<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
							<button type="submit" class="btn btn-warning btn-sm col-md-4">
								<i class="ace-icon fa fa-key bigger-110"></i> Update
							</button>
						</div>
				</div>

				</form>

			</div>
		</div>
	</div>

</div>
<script>
	$('.fa').show();

	$('#uploadzipfile').on('submit', function(event) {

		event.preventDefault();

		var dataString = new FormData($('#uploadzipfile')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('SCORM/scorm_courses/scorm_upload') ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);

					var obj = JSON.parse(data);

					console.log(obj);

					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						//window.location.href = 'project_settings.php';
						location.reload();
						alert('File Uploaded Successfully');

					} else {

						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}

				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			})
		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}

	});
</script>
<script>
	$('#addcategoryForm').on('submit', function(event) {

		event.preventDefault();

		var dataString = new FormData($('#addcategoryForm')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('SCORM/scorm_courses/assignmetacategory') ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function(data) {

					var obj = JSON.parse(data);

					console.log(obj);

					if (obj.status === 'OK') {
						console.log('inside on condition');
						//window.location.href = 'project_settings.php';
						location.reload();

					} else {

						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}

				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			})
		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}

	});
</script>
<script>
	$('#addmetadataForm').on('submit', function(event) {

		event.preventDefault();

		var dataString = new FormData($('#addmetadataForm')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('SCORM/scorm_courses/assignmetacategory') ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function(data) {

					var obj = JSON.parse(data);

					console.log(obj);

					if (obj.status === 'OK') {
						console.log('inside on condition');
						//window.location.href = 'project_settings.php';
						location.reload();


					} else {

						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}

				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			})
		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}

	});
	$(document).ready(function() {

		$('#dynamic-table').DataTable();

	});
</script>