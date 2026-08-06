<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">

					<li class="breadcrumb-item"><a
							href="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order'); ?>">Purchase
							Orders</a></li>

				</ol>
			</div>
			<h4 class="page-title">Upload PDF</h4>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="col-8 col-md-8 col-lg-8 mg-t-10">
			<div class="card">
				<div class="card-header">
					<h6>Upload PDF</h6>
				</div>
				<div class="card-body">

					<?php
					$baseloc = '';
					$base = base_url();
					if ($base == 'http://localhost/Dochek_V3/Dochek_V3') {
						$baseloc = '/Users/pchandran/Sites/dochek_v3/Dochek_V3/';
					}
					if ($base == 'http://localhost/projects_dochek/') {
						$baseloc = 'D:/wampp/www/projects_dochek/';
					}
					if ($base == 'https://dochek.com/') {
						$baseloc = '/var/www/html/';
					}
					if ($base == 'http://localhost/DOCHEKDOTCOM') {
						$baseloc = 'D:/wampp/www/DOCHEKDOTCOM/';
					}
					if ($base == 'https://staging.dochek.com/') {
						$baseloc = '/var/www/html/DOCHEK/';
					}
					if ($base == 'http://localhost/DOCHEK/') {
						$baseloc = 'D:/wampp/www/DOCHEK/';
					}
					if ($base == 'http://172.16.2.218/DOCHEK/') {
						$baseloc = '/var/www/DOCHEK/';
					}
					$folderloc = $baseloc . 'assets/assets/uploads/po_pdf/' . $po_id;
					// print_r($folderloc);
					if (is_dir($folderloc)) {
						$files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
						$sno = 0;
						if (!empty($po_details)) {
							$filepath = FCPATH . 'assets/assets/uploads/po_pdf/' . $po_id . '/' . $po_details[0]['po_upload'];
							// print_r($filepath);
							if (file_exists($filepath)) {

								echo '<table class="table  table-sm">';
								echo '<tr><th>File</th><th>On</th><th>del</th></tr>';

								foreach ($po_details as $filerow) {
									// print_r($filerow);
									echo '<tr><td>';
									echo $filerow['po_upload'];

									echo '</td><td>';
									$file_creation_date = filemtime($filepath);
									echo date('Y-m-d H:i:s', $file_creation_date);
									echo '</td><td>'; ?>
									<form class="form-horizontal deletePOupload" method="POST"><?= csrf_field() ?>
										<input type="hidden" name="fileloc"
											value="<?php echo $filepath . '/' . $filerow['po_upload']; ?>">
										<input type="hidden" name="po_uid" value="<?php echo $filerow['po_uid'] ?>">
										<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"
											onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
												class="mdi mdi-trash-can-outline"></span></button>
									</form>
									<?php echo '</td></tr>';
								}
							}
						} else {
							echo 'No Files';
						}
						echo '</table>';
					} else {
						echo 'No Files';
					} ?>

				</div>
			</div>

		</div>
		<div class="col-md-4 mg-t-10">
			<div class="card">

				<div class="card-body">
					<div class="row">
						<form class="form-horizontal2" enctype="multipart/form-data" action=<?php echo base_url('Project_Manage/PM_purchase_order/uploadpdf'); ?> method="post" id="submitForm"><?= csrf_field() ?>

							<div class="form-group col-md-6 mb-2">
								<input type="file" name="file" accept=".pdf" required />
							</div>
							<div class="form-group col-md-12 mb-2">
								<input type="hidden" name="po_id" value="<?php echo $po_id ?>">
								<button type="submit"
									class="btn btn-outline-success waves-effect btn-sm waves-light col-md-12"
									id="submitButton">Upload PDF</button>
							</div>
							<?php if (isset($povalidation)): ?>
								<div class="form-group col-md-12">
									<div class="alert alert-white" role="alert">
										<?= $povalidation->listErrors() ?>
									</div>
								</div>
							<?php endif; ?>
						</form>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>
<script>
	$(document).ready(function () {
		// Bind submit event to each individual delete form
		$('.deletePOupload').on('submit', function (event) {
			event.preventDefault(); // Prevent default form submission

			var dataString = new FormData(this); // 'this' refers to the form that was submitted

			// Ensure the form is being submitted correctly
			if (typeof FormData !== 'undefined') {
				$.ajax({
					url: '<?php echo base_url('Project_Manage/PM_purchase_order/delpo_upload'); ?>',
					type: "POST",
					data: dataString,
					async: false,
					processData: false,
					contentType: false,
					success: function (data) {
						var obj = JSON.parse(data);
						console.log(obj);
						if (obj.status === 'OK') {
							console.log('File deleted successfully!');
							// Optionally, remove the image from the DOM without reloading the page
							$(event.target).closest('form').remove(); // Remove the form from DOM
							location.reload();
						} else {
							alert('Error: Something went wrong! Please contact the Site Admin.');
						}
					},
					error: function (xhr, textStatus, errorThrown) {
						console.log('Request failed');
					}
				});
			} else {
				alert("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
			}
		});
	});
</script>