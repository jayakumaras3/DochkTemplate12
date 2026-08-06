<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"> <a href="<?php echo base_url($header_link); ?>">
							Docuemnt View
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
							<label>Document Name</label>
							<input type="text" class="form-control col-md-12" name="document_name" placeholder="Document name" value="<?php echo $row['document_name'] ?>" />
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Type of Document</label>
							<select name="type" class="form-control">
								<option value="6" <?php if ($row['type'] == 6) echo "SELECTED";  ?>>Maintenance Manual</option>
								<option value="7" <?php if ($row['type'] == 7) echo "SELECTED";  ?>>TroubleShooting</option>
							</select>
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Sequence</label>
							<input type="number" class="form-control col-md-12" name="sequence" placeholder="Sequence" value="<?php echo $row['sequence'] ?>" />
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Type of Link</label>
							<select name="launch_link" class="form-control">
								<option value="1" <?php if ($row['typeofLink'] == 1) echo "SELECTED";  ?>>Open</option>
								<option value="2" <?php if ($row['typeofLink'] == 2) echo "SELECTED";  ?>>Login Based</option>
							</select>
						</div>
						<div class="form-group col-md-12 mb-2">
							<label>Delete the document?</label>
							<select name="status" class="form-control">
								<option value="1">NO</option>
								<option value="0">Yes</option>
							</select>
						</div>
						<div class="form-group  col-md-12 mb-2">
							<input type="hidden" name="emd_id" value="<?php echo $emd_id ?>">
							<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
								Update
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<h6>Thumbnail</h6>
				<?php if ($row['thumbnail'] == '') { ?>
				<?php } else { ?>
					<div class="head bg-dot30 np tac">
						<img style="max-height:100px;" src="<?php echo base_url() ?>assets/assets/uploads/emanual_document_thumbnail/<?php echo $emd_id ?>/<?php echo $row['thumbnail'] ?>" class="img-squre img-thumbnail" />
					</div><br />
				<?php } ?>
				<div class="form-row">
					<form class="form-horizontal1" enctype="multipart/form-data" action="<?php echo base_url($form_url_1) ?>" method="POST"><?= csrf_field() ?>
						<div class="form-group col-md-12 mb-2">
							<input type="file" name="file" />
						</div>
						<div class="form-group col-md-12">
							<input type="hidden" name="emd_id" value="<?php echo $emd_id ?>">
							<button type="submit" class="btn btn-info btn-sm form-control">Upload</button>
						</div>
					</form>
				</div>
			</div>
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