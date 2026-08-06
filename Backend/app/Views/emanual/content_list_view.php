<style>
	.list-div {
		display: inline-block;
		margin: 20px;
		vertical-align: top;
		/* Add this to align buttons properly */
	}


	ul.sortable {
		width: 100%;
		float: left;
		margin: 20px 0;
		list-style: none;
		position: relative !important;
	}

	ul.sortable li {
		cursor: move;
	}

	ul.sortable li.ui-sortable-helper {
		border-color: #3498db;
	}

	ul.sortable li.placeholder {
		height: 50px;
		background: #eee;
		border: 2px dashed #bbb;
		display: block;
		opacity: 0.6;
		border-radius: 2px;
		-moz-border-radius: 2px;
		-webkit-border-radius: 2px;
	}
</style>
<style>
	#videoContainer {
		width: 80%;
		height: 80%;
		display: flex;
		justify-content: left;
		align-items: left;
	}

	#videoElement {
		max-width: 80%;
		max-height: 80%;
	}
</style>
<?php $userlevel = session()->get('userlevel');
$userlevlarray  = explode(',', $userlevel); ?>
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"> <a href="<?php echo base_url('Emanual/emanual_product/page_view'); ?>">
							Content View
						</a>
					</li>
				</ol>
			</div>
			<h4 class="page-title">
				<?php echo $sub_header_3; ?>
			</h4>
		</div>
	</div>
</div>
<div class="row">
	<?php

	$currentPage = isset($empg_id) ? $empg_id : '';
	$totalPages = $totalPages; // Replace with your actual total number of pages

	// Get the index of the current document ID
	$currentDocumentIndex = -1;
	foreach ($getAllpagedetails as $index => $pagedetail) {
		if ($pagedetail['document_id'] == $emd_id) {
			$currentDocumentIndex = $index;
			break;
		}
	}

	if ($currentDocumentIndex >= 0) {
		$currentDocument = $getAllpagedetails[$currentDocumentIndex];
		$db = \Config\Database::connect(); // Get the current document's page details
		$builder = $db->table('emanual_page as ep');
		$builder->select('ep.*');
		$builder->where('ep.document_id =', $currentDocument['document_id']);
		$pagedata = $builder->get()->getResultArray();

		$currentDocumentPageIds = array_column($pagedata, 'empg_id');

		// Find the current page's index
		$currentPageIndex = array_search($currentPage, $currentDocumentPageIds);

		// Calculate the previous and next page numbers within the same document
		$previousPage = ($currentPageIndex > 0) ? $currentDocumentPageIds[$currentPageIndex - 1] : null;
		$nextPage = ($currentPageIndex < count($currentDocumentPageIds) - 1) ? $currentDocumentPageIds[$currentPageIndex + 1] : null;

	?>
		<div class="form-group col-md-4 mb-2">
			<?php
			if ($previousPage !== null) {
			?>

				<form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_pagecontent') ?>" method="POST"><?= csrf_field() ?>
					<input type="hidden" name="empg_id" value="<?php echo $previousPage; ?>">
					<input type="hidden" name="emd_id" value="<?php echo $emd_id; ?>">
					<button type="submit" alt="Back" class="" style="all: unset; cursor: pointer;"><i class="mdi mdi-arrow-left-circle-outline font-22"></i></button>
				</form>

			<?php
				//echo '<a href="emanual_pagecontent?emd_id=' . $emd_id . '&empg_id=' . $previousPage . '"><button class="btn btn-sm btn-info">Previous</button></a>';
			}
			echo '</div>';

			echo '<div class="form-group col-md-4 mb-2" style="text-align: center;">Current Page: ' . $page_number . '/' . $totalPages . '</div>';

			echo '<div class="form-group col-md-4 mb-2 ribbon ribbon-blue float-start">';
			if ($nextPage !== null) {
			?>
				<form class="form-horizontal float-end mt-0" action="<?php echo base_url('Emanual/emanual_pagecontent') ?>" method="POST"><?= csrf_field() ?>
					<input type="hidden" name="empg_id" value="<?php echo $nextPage; ?>">
					<input type="hidden" name="emd_id" value="<?php echo $emd_id; ?>">
					<button type="submit" alt="Next" class="" style="all: unset; cursor: pointer;"><i class="mdi mdi-arrow-right-circle-outline font-22"></i></button>
				</form>
		<?php
				//echo '<a href="emanual_pagecontent?emd_id=' . $emd_id . '&empg_id=' . $nextPage . '"><button class="btn btn-sm btn-success">Next</button></a>';
			}
		} else {
			echo 'Invalid document ID';
		}
		?>
		</div>
</div>



<div class="row">
	<div class="col-6 col-md-6 col-lg-6">
		<div class="card">
			<div class="card-body">
				<form><?= csrf_field() ?>
					<div class="bookly-form-group" data-type="contenttype">
						<div class="form-group col-md-12">
							<select name="contenttype" class="form-control">
								<option value="0">- Select Content Type -</option>
								<?php foreach ($contenttype as $eachcategoryItem) { ?>
									<option value="<?php echo $eachcategoryItem['id_d'] ?>"><?php echo $eachcategoryItem['name'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="bookly-form-group" data-type="headers">
			<div class="card">
				<div class="card-body">
					<form class="form" id="addcontentform" method='post'><?= csrf_field() ?>
						<div class="col-md-12 mb-2">
							<input type="hidden" id="headerstype" name="type" value="88" />
							<input type="text" class="form-control" name="content1" placeholder="Header" value="" />
						</div>

						<div class="col-md-12">
							<input type="hidden" name="empg_id" value="<?= $empg_id ?>" />
							<input type="hidden" name="page_name" value="<?= $page_name ?>" />
							<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
								Add Header Text
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="bookly-form-group" data-type="sub-headers">
			<div class="card">
				<div class="card-body">
					<form class="form" id="add_sub_content" method='post'><?= csrf_field() ?>
						<div class="col-md-12 mb-2">
							<input type="hidden" id="headerstype" name="type" value="89" />
							<input type="text" class="form-control" name="content1" placeholder="Header" value="" />
						</div>

						<div class="col-md-12">
							<input type="hidden" name="empg_id" value="<?= $empg_id ?>" />
							<input type="hidden" name="page_name" value="<?= $page_name ?>" />
							<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
								Add Sub Header Text
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="form" class="bookly-form-group" data-type="image">
			<div class="card">
				<div class="card-body">
					<form class="pageUploadform" class="form-horizontal2" enctype="multipart/form-data"><?= csrf_field() ?><?= csrf_field() ?>
						<div class="col-md-12 mb-2">
							<input type="file" name="file" />
							<input type="hidden" id="imagetype" name="type" value="" />
						</div>

						<div class="col-md-12">
							<input type="hidden" name="empg_id" value="<?= $empg_id ?>" />
							<input type="hidden" name="page_name" value="<?= $page_name ?>" />
							<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Upload Image</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="form" class="bookly-form-group" data-type="video">
			<div class="card">
				<div class="card-body">
					<form class="videoUploadform" class="form-horizontal2" enctype="multipart/form-data"><?= csrf_field() ?><?= csrf_field() ?>
						<div class="col-md-12 mb-2">
							<input type="file" name="file" />
							<input type="hidden" id="videotype" name="type" value="" />
						</div>

						<div class="col-md-12">
							<input type="hidden" name="empg_id" value="<?= $empg_id ?>" />
							<input type="hidden" name="page_name" value="<?= $page_name ?>" />
							<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Upload Video</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="bookly-form-group" data-type="createTable">
			<div class="card">
				<div class="card-body">
					<form class="createTableform"><?= csrf_field() ?>
						<div class="col-md-12 mb-2">
							<input type="hidden" id="tabletype" name="type" value="101" />
							<textarea name="contentx" class="ckeditor" class="form-control"></textarea>
						</div>
						<div class="col-md-12">
							<input type="hidden" name="empg_id" value="<?= $empg_id ?>" />
							<input type="hidden" name="page_name" value="<?= $page_name ?>" />
							<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
								Add Content
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="bookly-form-group" data-type="content_table">
			<div class="card">
				<div class="card-body">
					<form class="caution_content"><?= csrf_field() ?>
						<div class="col-md-12 mb-2">
							<input type="hidden" id="tabletype" name="type" value="90" />
							<textarea name="content_caution" class="ckeditor" class="form-control"></textarea>
						</div>
						<div class="col-md-12">
							<input type="hidden" name="empg_id" value="<?= $empg_id ?>" />
							<input type="hidden" name="page_name" value="<?= $page_name ?>" />
							<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
								Add Caution Content
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<td>Content</td>
							<td>Edit</td>
							<td>Delete</td>
							<!-- <td>View</td> -->
						</tr>
					</thead>
					<tbody>

						<?php foreach ($pagecontentdata as $order => $eachpagecontentdata) { //forloop of content display 
							// print_r($eachpagecontentdata['emc_id']);
						?>
							<tr>
								<?php if ($eachpagecontentdata['type'] == '96') {  // Image type 
								?>
									<td>
										<?php if ($eachpagecontentdata['content1'] != '') { ?>
											<div class="head bg-dot30 np tac">
												<img src="<?php echo base_url() ?>assets/assets/uploads/emanual_image/<?php echo $eachpagecontentdata['page_id'] ?>/<?php echo $eachpagecontentdata['content1'] ?>" class="img-squre img-thumbnail" />
											</div>
										<?php } ?>
									</td>
									<?php if (in_array('46', $userlevlarray) || in_array('5', $userlevlarray)) {
										if ($eachpagecontentdata['status'] == '1') { // Edit or delete or Ready for review state 	
									?>
											<td>
												<div class="popup" data-bs-toggle="modal" data-bs-target="#win-<?= $eachpagecontentdata['emc_id'] ?>"><button class="btn btn-outline-warning btn-xs waves-effect waves-light"><span class="mdi mdi-pencil-outline"></span></button></div>
											</td>
											<div id="win-<?= $eachpagecontentdata['emc_id'] ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
												<!-- <div class="modal fade bs-example-modal-lg<?= $eachpagecontentdata['emc_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;"> -->
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h4 class="modal-title" id="standard-modalLabel">Update Image</h4>
															<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body">
															<form class="editpageUploadform" class="form-horizontal2" enctype="multipart/form-data"><?= csrf_field() ?><?= csrf_field() ?>
																<div class="col-md-6 mb-2">
																	<input type="file" name="file" />
																	<input type="hidden" id="imagetype" name="type" value="" />
																</div>
																<div class="col-md-6">
																	<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
																	<input type="hidden" name="status" value="<?php echo $eachpagecontentdata['status'] ?>" />
																	<input type="hidden" name="content1" value="<?php echo $eachpagecontentdata['content1'] ?>" />
																	<input type="hidden" name="type" value="<?php echo $eachpagecontentdata['type'] ?>" />
																	<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
																	<input type="hidden" name="empg_id" value="<?= $empg_id ?>" />
																	<input type="hidden" name="page_name" value="<?= $page_name ?>" />
																	<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Upload New Image</button>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>

											<?php if ($eachpagecontentdata['status'] == '1') { ?>
												<td>
													<form method='post' class="deleteContentForm"><?= csrf_field() ?>
														<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
														<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
														<button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light"><span class="mdi mdi-trash-can-outline"></button>
													</form>
												</td>

											<!-- 	<td>
													<form method='post' class="reviewContentForm">
														<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
														<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light"><span class="mdi mdi-eye-outline"></button>
													</form>
												</td> -->
											<?php } else { ?>
												<td></td>
												<td></td>
											<?php } ?>
									<?php
										} else {
											echo '<td></td><td></td><td></td>';
										}
									} ?>

									<?php if (in_array('46', $userlevlarray) || in_array('5', $userlevlarray)) {
										if ($eachpagecontentdata['status'] == '2') { // approve or reject by approver 
											// print_r($eachpagecontentdata['status']);
									?>
											<td>
												<form method='post' class="approveContentForm"><?= csrf_field() ?>
													<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
													<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
													<input type="hidden" name="reference_id" value="<?php echo $eachpagecontentdata['reference_id'] ?>" />
													<button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light btn-sm" title="Delete"><span class="mdi mdi-thumb-up-outline"></button>
												</form>

											</td>
											<td>
												<form method='post' class="rejectContentForm"><?= csrf_field() ?>
													<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
													<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
													<button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light btn-sm" title="Review"><span class="mdi mdi-thumb-down-outline"></button>
												</form>
											</td>
											<td></td>
									<?php }
									} ?>

								<?php } elseif ($eachpagecontentdata['type'] == '97') {  // video type 
								?>
									<?php if ($eachpagecontentdata['content1'] != '') { ?>

										<td>
											<video id="videoElement" controls>

												<?php $videoUrl =  base_url("assets/assets/uploads/emanual_video/" . $empg_id . "/" . $eachpagecontentdata['content1']);
												?>
												<source src="<?= $videoUrl ?>" type="video/mp4">
												Your browser does not support the video tag.
											</video>
										</td>

									<?php } ?>

									<?php if (in_array('46', $userlevlarray) || in_array('5', $userlevlarray)) {
										if ($eachpagecontentdata['status'] == '1') { // Edit or delete or Ready for review state 	
									?>
											<td>
												<div class="popup" data-bs-toggle="modal" data-bs-target="#win-<?= $eachpagecontentdata['emc_id'] ?>"><button class="btn btn-outline-warning btn-xs waves-effect waves-light"><span class="mdi mdi-pencil-outline"></span></button></div>
											</td>
											<div id="win-<?= $eachpagecontentdata['emc_id'] ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">

												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h4 class="modal-title" id="standard-modalLabel">Update Video</h4>
															<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body">
															<form class="editvideoUploadform" class="form-horizontal2" enctype="multipart/form-data"><?= csrf_field() ?><?= csrf_field() ?>
																<div class="col-md-6 mb-2">
																	<input type="file" name="file" />
																	<input type="hidden" id="videotype" name="type" value="" />
																</div>
																<div class="col-md-6">
																	<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
																	<input type="hidden" name="status" value="<?php echo $eachpagecontentdata['status'] ?>" />
																	<input type="hidden" name="content1" value="<?php echo $eachpagecontentdata['content1'] ?>" />
																	<input type="hidden" name="type" value="<?php echo $eachpagecontentdata['type'] ?>" />
																	<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
																	<input type="hidden" name="empg_id" value="<?= $empg_id ?>" />
																	<input type="hidden" name="page_name" value="<?= $page_name ?>" />
																	<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Upload</button>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>

											<?php if ($eachpagecontentdata['status'] == '1') { ?>
												<td>
													<form method='post' class="deleteContentForm"><?= csrf_field() ?>
														<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
														<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
														<button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light"><span class="mdi mdi-trash-can-outline"></button>
													</form>
												</td>
												<!-- <td>
													<form method='post' class="reviewContentForm">
														<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
														<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
														<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light"><span class="mdi mdi-eye-outline"></button>
													</form>
												</td> -->
											<?php } else { ?>
												<td></td>
												<td></td>
											<?php } ?>
									<?php } else {
											echo '<td></td><td></td><td></td>';
										}
									} ?>

									<?php if (in_array('46', $userlevlarray) || in_array('5', $userlevlarray)) {
										if ($eachpagecontentdata['status'] == '2') { // approve or reject by approver 
									?>
											<td>
												<form method='post' class="approveContentForm"><?= csrf_field() ?>
													<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
													<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
													<input type="hidden" name="reference_id" value="<?php echo $eachpagecontentdata['reference_id'] ?>" />
													<button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light btn-sm" title="Delete"><span class="mdi mdi-thumb-up-outline"></button>
												</form>
											</td>
											<td>
												<form method='post' class="rejectContentForm"><?= csrf_field() ?>
													<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
													<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
													<button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light btn-sm" title="Review"><span class="mdi mdi-thumb-down-outline"></button>
												</form>
											</td>
											<td></td>
									<?php }
									} ?>
								<?php } else { ?>
									<td>
										<?php echo $eachpagecontentdata['content1'] ?>
									</td>

									<?php if (in_array('46', $userlevlarray) || in_array('5', $userlevlarray)) {
										if ($eachpagecontentdata['status'] == '1') { // Edit or delete or Ready for review state 	
									?>
											<td>
												<div class="popup" data-bs-toggle="modal" data-bs-target="#win<?= $eachpagecontentdata['emc_id'] ?>"><button class="btn btn-outline-warning btn-xs waves-effect waves-light"><span class="mdi mdi-pencil-outline"></span></button></div>
											</td>
											<div id="win<?= $eachpagecontentdata['emc_id'] ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h4 class="modal-title" id="standard-modalLabel">Edit Text</h4>
															<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body">
															<?php if ($eachpagecontentdata['type'] === '101') { ?>
																<form method='post' class="editContentckeditorForm" data-formid="<?php echo $eachpagecontentdata['emc_id'] ?>"><?= csrf_field() ?>
																	<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
																	<input type="hidden" name="page_id" value="<?php echo $eachpagecontentdata['page_id'] ?>" />
																	<input type="hidden" name="type" value="<?php echo $eachpagecontentdata['type'] ?>" />
																	<input type="hidden" name="status" value="<?php echo $eachpagecontentdata['status'] ?>" />
																	<textarea type="text" name="editcontent" id="editcontent_<?php echo $eachpagecontentdata['emc_id'] ?>" class="ckeditor" placeholder="content"><?php echo $eachpagecontentdata['content1'] ?></textarea>

																	<button type="submit" class="btn btn-warning btn-sm mt-2">Update</button>
																</form>
															<?php } else { ?>
																<form method='post' class="editContentForm"><?= csrf_field() ?>
																	<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
																	<input type="hidden" name="page_id" value="<?php echo $eachpagecontentdata['page_id'] ?>" />
																	<input type="hidden" name="type" value="<?php echo $eachpagecontentdata['type'] ?>" />
																	<input type="hidden" name="status" value="<?php echo $eachpagecontentdata['status'] ?>" />
																	<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
																	<?php if ($eachpagecontentdata['type'] === "88" || ($eachpagecontentdata['type'] === "89")) { ?>
																		<input type="text" class="form-control" name="content1" value="<?php echo $eachpagecontentdata['content1'] ?>" />
																		<br />
																	<?php } else ?>
																	<?php if ($eachpagecontentdata['type'] === "91" || $eachpagecontentdata['type'] === "92" || $eachpagecontentdata['type'] === "93" || $eachpagecontentdata['type'] === "94" || $eachpagecontentdata['type'] === "95") { ?>
																		<textarea type="text" name="content1" class="form-control" placeholder="content" rows="8"><?php echo $eachpagecontentdata['content1'] ?></textarea>
																		<br />
																	<?php } ?>
																	<?php if ($eachpagecontentdata['type'] === '101' || ($eachpagecontentdata['type'] === "90")) { ?>
																		<textarea type="text" name="content1" class="ckeditor" placeholder="content" rows="8"><?php echo $eachpagecontentdata['content1'] ?></textarea>
																		<br />
																	<?php } ?>
																	<button type="submit" class="btn btn-warning btn-sm mt-2">Update</button>
																</form>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>

											<?php if ($eachpagecontentdata['status'] == '1' || $eachpagecontentdata['status'] == '3') { ?>

												<td>
													<form method='post' class="deleteContentForm"><?= csrf_field() ?>
														<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
														<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
														<button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light"><span class="mdi mdi-trash-can-outline"></button>
													</form>
												</td>

												<!-- <td>
													<form method='post' class="reviewContentForm">
														<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
														<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
														<button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light"><span class="mdi mdi-eye-outline"></button>
													</form>
												</td> -->
											<?php } else { ?>
												<td></td>
												<td></td>
											<?php } ?>
									<?php } else {
											echo '<td></td><td></td><td></td>';
										}
									} ?>
									<?php if (in_array('46', $userlevlarray) || in_array('5', $userlevlarray)) {
										if ($eachpagecontentdata['status'] == '2') { // approve or reject by approver 

									?>
											<td>
												<form method='post' class="approveContentForm"><?= csrf_field() ?>
													<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
													<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
													<input type="hidden" name="reference_id" value="<?php echo $eachpagecontentdata['reference_id'] ?>" />
													<button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light btn-sm" title="Delete"><span class="mdi mdi-thumb-up-outline"></button>
												</form>
											</td>
											<td>
												<form method='post' class="rejectContentForm"><?= csrf_field() ?>
													<input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
													<input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
													<button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light btn-sm" title="Review"><span class="mdi mdi-thumb-down-outline"></button>
												</form>
											</td>
											<td></td>
									<?php }
									} ?>
								<?php } ?>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
<script>
	$('div[data-type="headers"]').hide();
	$('div[data-type="sub-headers"]').hide();
	$('div[data-type="image"]').hide();
	$('div[data-type="video"]').hide();
	$('div[data-type="createTable"]').hide();
	$('div[data-type="content_table"]').hide();

	$('div[data-type="contenttype"] select').on('change', (function() {
		var value = $('div[data-type="contenttype"] select option:selected').val();
		console.log(value);

		if (value === "88") {
			$('div[data-type="headers"]').show();
		} else {
			$('div[data-type="headers"]').hide();
		}
		if (value === "89") {
			$('div[data-type="sub-headers"]').show();
		} else {
			$('div[data-type="sub-headers"]').hide();
		}
		if (value === "96") {
			$('div[data-type="image"]').show();
		} else {
			$('div[data-type="image"]').hide();
		}
		if (value === "97") {
			$('div[data-type="video"]').show();
		} else {
			$('div[data-type="video"]').hide();
		}
		if (value === "101") {
			$('div[data-type="createTable"]').show();
		} else {
			$('div[data-type="createTable"]').hide();
		}

		if (value === "90") {
			$('div[data-type="content_table"]').show();
		} else {
			$('div[data-type="content_table"]').hide();
		}

		document.getElementById("headerstype").value = value;
		document.getElementById("imagetype").value = value;
		document.getElementById("videotype").value = value;
		document.getElementById("tabletype").value = value;
	}));

	$('.fa').show();

	$('#addcontentform').on('submit', function(event) {

		event.preventDefault();

		var dataString = new FormData($('#addcontentform')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/addContent'); ?>',
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
	$('#add_sub_content').on('submit', function(event) {

		event.preventDefault();

		var dataString = new FormData($('#add_sub_content')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/addContent'); ?>',
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

	$('.fa').show();

	$('#addcontentform1').on('submit', function(event) {

		event.preventDefault();

		var dataString = new FormData($('#addcontentform1')[0]);

		if (typeof FormData !== 'undefined') {

			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/addContent'); ?>',
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
	$('.fa').show();

	$('.createTableform').on('submit', function(event) {
		event.preventDefault();

		var formData = new FormData(this);
		var content1Value = CKEDITOR.instances.contentx.getData();
		formData.set('content1', content1Value);

		$.ajax({
			url: '<?php echo base_url('Emanual/emanual_pagecontent/addContent'); ?>',
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function(data) {
				$('.my_update_panel').html(data);
				var obj = JSON.parse(data);
				console.log(obj);
				if (obj.status === 'OK') {
					$('#loading_spinner').hide();
					console.log('inside on condition');
					location.reload();
					// alert('Uploaded Successfully');
				} else {
					alert('error', 'Something Went Wrong! Please contact Site Admin!');
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				console.log('request failed');
			}
		});
	});

	$('.caution_content').on('submit', function(event) {
		event.preventDefault();

		var formData = new FormData(this);
		var content1Value = CKEDITOR.instances.content_caution.getData();
		formData.set('content1', content1Value);

		$.ajax({
			url: '<?php echo base_url('Emanual/emanual_pagecontent/addContent'); ?>',
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function(data) {
				$('.my_update_panel').html(data);
				var obj = JSON.parse(data);
				console.log(obj);
				if (obj.status === 'OK') {
					$('#loading_spinner').hide();
					console.log('inside on condition');
					location.reload();
					// alert('Uploaded Successfully');
				} else {
					alert('error', 'Something Went Wrong! Please contact Site Admin!');
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				console.log('request failed');
			}
		});
	});


	$('.pageUploadform').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData(this); // Use 'this' to reference the current form element

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/imageUpload'); ?>',
				type: "POST",
				data: dataString,
				processData: false, // Remove the 'async' and 'contentType' options
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						alert('Uploaded Successfully');
					} else if (obj.status === 'error') {
						alert('File is already exists');
						location.reload();
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
						location.reload();
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});

	$('.videoUploadform').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData(this); // Use 'this' to reference the current form element

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/videoUpload'); ?>',
				type: "POST",
				data: dataString,
				processData: false, // Remove the 'async' and 'contentType' options
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						alert('Uploaded Successfully');
					} else if (obj.status === 'error') {
						alert('File is already exists');
						location.reload();
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});
	$('.fa').show();

	$('.deleteContentForm').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData(this); // Use 'this' to reference the current form element

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/deleteContent'); ?>',
				type: "POST",
				data: dataString,
				processData: false, // Remove the 'async' and 'contentType' options
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						// alert('Uploaded Successfully');
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});
	$('.fa').show();

	$('.editContentForm').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData(this); // Use 'this' to reference the current form element

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/editContent'); ?>',
				type: "POST",
				data: dataString,
				processData: false, // Remove the 'async' and 'contentType' options
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						// alert('Uploaded Successfully');
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});
	$(document).on('submit', '.editContentckeditorForm', function(event) {
		event.preventDefault();

		var form = $(this);
		var formId = form.data('formid');
		var formData = new FormData(form[0]);
		var content1Value = CKEDITOR.instances['editcontent_' + formId].getData();
		formData.set('content1', content1Value);

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?= base_url('Emanual/emanual_pagecontent/editContent'); ?>',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						// alert('Uploaded Successfully');
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});

	$('.editpageUploadform').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData(this); // Use 'this' to reference the current form element

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/editpageUpload'); ?>',
				type: "POST",
				data: dataString,
				processData: false, // Remove the 'async' and 'contentType' options
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						alert('Uploaded Successfully');
					} else if (obj.status === 'error') {
						alert('File is already exists');
						location.reload();
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
						location.reload();
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});
	$('.editvideoUploadform').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData(this); // Use 'this' to reference the current form element

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/editvideoUpload'); ?>',
				type: "POST",
				data: dataString,
				processData: false, // Remove the 'async' and 'contentType' options
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						alert('Uploaded Successfully');
					} else if (obj.status === 'error') {
						alert('File is already exists');
						location.reload();
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
						location.reload();
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});
	$('.reviewContentForm').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData(this); // Use 'this' to reference the current form element

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/readyforReview'); ?>',
				type: "POST",
				data: dataString,
				processData: false, // Remove the 'async' and 'contentType' options
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						// alert('Uploaded Successfully');
					} else if (obj.status === 'error') {
						alert('File is already exists');
						location.reload();
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
						location.reload();
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});
	$('.approveContentForm').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData(this); // Use 'this' to reference the current form element

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/approveContent'); ?>',
				type: "POST",
				data: dataString,
				processData: false, // Remove the 'async' and 'contentType' options
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						alert('Approved Successfully');
					} else if (obj.status === 'error') {
						// alert('File is already exists');
						location.reload();
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
						location.reload();
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});
	$('.rejectContentForm').on('submit', function(event) {
		event.preventDefault();

		var dataString = new FormData(this); // Use 'this' to reference the current form element

		if (typeof FormData !== 'undefined') {
			$.ajax({
				url: '<?php echo base_url('Emanual/emanual_pagecontent/rejectContent'); ?>',
				type: "POST",
				data: dataString,
				processData: false, // Remove the 'async' and 'contentType' options
				contentType: false,
				success: function(data) {
					$('.my_update_panel').html(data);
					var obj = JSON.parse(data);
					console.log(obj);
					if (obj.status === 'OK') {
						$('#loading_spinner').hide();
						console.log('inside on condition');
						location.reload();
						alert('Rejected');
					} else if (obj.status === 'error') {
						// alert('File is already exists');
						location.reload();
					} else {
						alert('error', 'Something Went Wrong! Please contact Site Admin!');
						location.reload();
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log('request failed');
				}
			});
		} else {
			message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
		}
	});
</script>
<script>
	$(function() {
		$("#sortable").sortable({
			update: function(event, ui) {
				var data = $(this).sortable('toArray');
				console.log(data);

				$.ajax({
					url: '<?php echo base_url('Emanual/emanual_pagecontent/sortPageContent') ?>',
					method: 'POST',
					data: {
						sequence: data
					},
					success: function(response) {

					},
					error: function() {

					}
				});
			}
		});
	});
</script>