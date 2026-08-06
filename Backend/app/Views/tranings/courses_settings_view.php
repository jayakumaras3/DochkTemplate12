<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b>
			<li class="active"><?php echo $sub_header_1; ?></li>
		</ol>
	</div>
</div>
<!-- <?php if (isset($row['upload']) & $row['upload'] != '') { ?>

<?php } else {
} ?> -->
<div class="row">
	<div class="col-md-4">
		<div class="x_panel">
			<h6>Thumbnail</h6>
			<?php if ($row['thumbnail'] == '') { ?>
			<?php } else { ?>
				<div class="head bg-dot30 np tac">
					<img style="max-height:100px;"
						src="<?php echo base_url() ?>/assets/assets/uploads/SCORM_course_thumbnail/<?php echo $scourse_id ?>/<?php echo $row['thumbnail'] ?>"
						class="img-squre img-thumbnail" />
				</div><br />
			<?php } ?>
			<div class="form-row">
				<form class="form-horizontal1" enctype="multipart/form-data"
					action="<?php echo base_url($form_url_1) ?>" method="POST"><?= csrf_field() ?>
					<div class="form-group col-md-12">
						<input type="file" name="file" />
					</div>
					<div class="form-group col-md-12">
						<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
						<button type="submit" class="btn btn-info btn-sm form-control">Upload</button>
					</div>
					<?php if (isset($thumbnailvalidation)): ?>
						<div class="form-group col-md-12">
							<div class="alert alert-danger" role="alert">
								<?= $thumbnailvalidation->listErrors() ?>
							</div>
						</div>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="x_panel">
			<h6>SCORM 1.2 Only</h6>
			<div class="row">
				<div class="col-md-6">
					<?php
					$base = base_url();
					if ($base == 'http://localhost:8888/projects_dochek/projects_dochek') {
						$baseloc = '/Users/pchandran/Sites/projects_dochek/projects_dochek/';
					}
					if ($base == 'http://localhost:8080/projects_dochek') {
						$baseloc = 'C:/wamp6/www/projects_dochek/';
					}
					if ($base == 'https://dochek.com/') {
						$baseloc = '/var/www/html/';
					}
					if ($base == 'https://staging.dochek.com/') {
						$baseloc = '/var/www/html/DOCHEK/';
					}
					if ($base == 'http://localhost/DOCHEK/') {
						$baseloc = '/var/www/html/';
					}
					if ($base == 'http://172.16.2.218/DOCHEK/') {
						$baseloc = '/var/www/DOCHEK/';
					}
					$folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id;
					if (is_dir($folderloc)) {
						$files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
						$sno = 0;
						echo '<table class="table  table-sm">';
						echo '<tr><th>#</th><th>Description</th><th>Created</th><th>Activate</th><th>Del</th></tr>';
						foreach ($files2 as $key => $value) {
							if (strlen($value) > 3) {
								$sno++;
								echo '<tr><td>';
								echo $sno;
								echo '</td><td>';
								$resultx = array_search($value, array_column($getAllFileOwner, 'folder'));
								if ($resultx) {
									echo $getAllFileOwner[$resultx]['description'];
								}
								echo '</td><td>';
								$file_creation_date = filectime($folderloc . '/' . $value);
								echo date('Y-m-d H:i:s', $file_creation_date);
								echo '</td><td>';
								$existingfile = $row['upload'];
								if ($existingfile != $value) {
									?>
									<form class="form-horizontal" action="<?php echo base_url('User_login/client_users/activate'); ?>"
										method="POST"><?= csrf_field() ?>
										<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
										<input type="hidden" name="filename" value="<?php echo $value; ?>">
										<button type="submit" class="btn btn-sm widget-icon btn-warning"
											onclick="return confirm('<?php echo lang('Alert.Aler_015') ?>')">Activate</button>
									</form>
									<?php
								} else {
									echo "Active";
								}
								echo '</td><td>';
								if ($existingfile != $value) {
									?>
									<form class="form-horizontal" action="<?php echo base_url('User_login/client_users/del_folder'); ?>"
										method="POST"><?= csrf_field() ?>
										<input type="hidden" name="folderloc" value="<?php echo $folderloc . '/' . $value; ?>">
										<input type="hidden" name="folder_name" value="<?php echo $value; ?>">
										<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
										<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"
											onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
												class="mdi mdi-trash-can-outline"></span></button>
									</form>
									<?php
								}
								echo '</td><tr>';
							}
						}
						echo '</table>';
					} else {
						echo 'No Files';
					}
					?>
				</div>
				<div class="col-md-6">
					<div class="form-row">
						<form class="form-horizontal1" id="uploadzipfile" enctype="multipart/form-data"><?= csrf_field() ?>
							<div class="form-group col-md-12">
								<input type="text" class="form-control" name="description" placeholder="Description"
									required />
							</div>
							<div class="form-group col-md-12">
								<input type="file" name="zip_file" required />
							</div>
							<div class="form-group col-md-12">
								<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
								<button type="submit" class="btn btn-danger btn-sm form-control">Upload Package</button>
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

	$('#uploadzipfile').on('submit', function (event) {

		event.preventDefault();

		var dataString = new FormData($('#uploadzipfile')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url($form_url_2) ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function (data) {
					$('.my_update_panel').html(data);

					var obj = JSON.parse(data);

					console.log(obj);

					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						alert('File Uploaded Successfully');

					} else {

						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}

				},
				error: function (xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			})
		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}

	});
</script>
<script>
	$('#addcategoryForm').on('submit', function (event) {

		event.preventDefault();

		var dataString = new FormData($('#addcategoryForm')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url($form_url_4) ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function (data) {

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
				error: function (xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			})
		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}

	});
</script>
<script>
	$('#addmetadataForm').on('submit', function (event) {

		event.preventDefault();

		var dataString = new FormData($('#addmetadataForm')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url($form_url_4) ?>',
				type: "POST",
				data: dataString,
				async: false,
				processData: false,
				contentType: false,
				success: function (data) {

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
				error: function (xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			})
		} else {
			message("Your Browser Don't support FormData API! Use IE 10 or Above!");
		}

	});
	$(document).ready(function () {

		$('#dynamic-table').DataTable();

	});
</script>