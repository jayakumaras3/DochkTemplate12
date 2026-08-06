<style>
	.card {
		border-radius: 16px;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
		border: none;
	}

	.btn-add-page {
		background-color: rgba(var(--ct-primary-rgb), 0.1);
		color: rgb(var(--ct-primary-rgb));
		border: 1px solid rgba(var(--ct-primary-rgb), 0.25);
		font-weight: 600;
		font-size: 12.5px;
	}

	.btn-add-page:hover {
		background-color: rgba(var(--ct-primary-rgb), 0.18);
		color: rgb(var(--ct-primary-rgb));
	}

	.pev-nav-btn {
		width: 38px;
		height: 38px;
		border-radius: 50%;
		background-color: rgba(var(--ct-primary-rgb), 0.1);
		color: rgb(var(--ct-primary-rgb));
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
		border: none;
	}

	.pev-nav-btn:hover {
		background-color: rgba(var(--ct-primary-rgb), 0.18);
	}

	.pev-table {
		table-layout: fixed;
	}

	.pev-table thead th {
		font-weight: 700;
		font-size: 13px;
		color: #495057;
		background-color: rgba(var(--ct-primary-rgb), 0.06);
		border: none;
		padding: 12px 16px;
	}

	.pev-table th,
	.pev-table td {
		max-width: 40%;
		overflow-wrap: break-word;
	}

	.pev-table td {
		vertical-align: top;
		font-size: 13.5px;
		padding: 12px 16px;
	}

	.modal-content {
		border-radius: 16px;
		border: none;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/course_builder/Editor'); ?>">Course Builder</a></li>
				</ol>
			</div>
			<?php
			$sub_page_main = $row['sub_page_main'];
			if ($sub_page_main > 0) {
			?>
				<h4 class="page-title">Edit Sub Page: <?php echo esc($row['page_name']) ?></h4>
			<?php
			} else {
			?>
				<h4 class="page-title">Edit - <?php echo esc($row['page_name']) ?></h4>
			<?php
			}
			?>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-6 col-md-6 col-lg-12">
		<div class="d-flex align-items-center justify-content-between mb-3">
			<?php
			$sub_page_main = $row['sub_page_main'];
			if ($sub_page_main == 0) {
			?>
				<div>
					<?php if ($prev_page) { ?>
						<form class="d-inline" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
							<input type="hidden" name="page_id" value="<?php echo $prev_page[0]['page_id'] ?>">
							<input type="hidden" name="page_number" value="<?php echo $prev_page[0]['page_number'] ?>">
							<input type="hidden" name="page_name" value="<?php echo $prev_page[0]['page_name'] ?>">
							<button type="submit" title="Previous Page" class="pev-nav-btn waves-effect"><i class="mdi mdi-arrow-left"></i></button>
						</form>
					<?php } ?>
				</div>

				<form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_add_sub_page') ?>" method="POST"><?= csrf_field() ?>
					<input type="hidden" name="page_number" value="<?php echo $row['page_number'] ?>">
					<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
					<input type="hidden" name="scourse_id" value="<?php echo $course_id ?>">
					<button type="submit" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">Create Sub Page <i class="mdi mdi-arrow-up-bold-circle-outline"></i></button>
				</form>

				<div>
					<?php if ($next_page) { ?>
						<form class="d-inline" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
							<input type="hidden" name="page_id" value="<?php echo $next_page[0]['page_id'] ?>">
							<input type="hidden" name="page_number" value="<?php echo $next_page[0]['page_number'] ?>">
							<input type="hidden" name="page_name" value="<?php echo $next_page[0]['page_name'] ?>">
							<button type="submit" title="Next Page" class="pev-nav-btn waves-effect"><i class="mdi mdi-arrow-right"></i></button>
						</form>
					<?php } else {
						if ($sub_page_main == 0) {
							$nxt_page = $row['page_number'] + 1;
						?>
							<form class="d-inline" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_add_view') ?>" method="POST"><?= csrf_field() ?>
								<input type="hidden" name="nxt_pageid" value="<?php echo $nxt_page; ?>">
								<input type="hidden" name="course_id" value="<?php echo $course_id ?>">
								<button type="submit" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">Create New Page <i class="mdi mdi-arrow-right-bold-circle-outline"></i></button>
							</form>
					<?php
						}
					} ?>
				</div>
			<?php
			} else {
			?>
				<form action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
					<input type="hidden" name="page_id" value="<?php echo $row['sub_page_main']; ?>">
					<input type="hidden" name="page_number" value="<?php echo $row['sub_page_main']; ?>">
					<input type="hidden" name="course_id" value="<?php echo $course_id ?>">
					<input type="hidden" name="page_name" value="">
					<button type="submit" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light"><i class="mdi mdi-arrow-left-bold-circle-outline"></i> Main</button>
				</form>
			<?php
			}
			?>
		</div>

		<?php
		$subpages_Count = count($sub_page_content);
		if ($subpages_Count > 0) {
			echo '<div class="row mb-1">';
			foreach ($sub_page_content as $subPages) {

		?>
				<div class="col-3 col-md-3 col-lg-3">
					<form class="form-horizontal mb-2" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
						<input type="hidden" name="page_number" value="<?php echo $subPages['page_number']; ?>">
						<input type="hidden" name="page_id" value="<?php echo $subPages['page_id']; ?>">
						<input type="hidden" name="page_name" value="<?php echo $subPages['page_name']; ?>">
						<button type="submit" class="btn btn-outline-dark rounded-pill waves-effect waves-light"><?php echo  $subPages['page_number']; ?> <?php echo  $subPages['page_name']; ?></button>
					</form>
				</div>
		<?php
			}
			echo '</div>';
		}
		?>


		<div class="row">
			<div class="col-6 col-md-6 col-lg-12">
				<div class="card">
					<div class="card-body">
						<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST" id="submitForm"><?= csrf_field() ?>
							<div class="row">
								<div class="form-group col-md-4 mb-2">
									<label>Page Name</label>
									<input type="text" class="form-control col-md-12" name="page_name" placeholder="Page Name" value="<?php echo $row['page_name'] ?>" />
								</div>
								<div class="form-group col-md-2 mb-2">
									<label>Page Type</label>
									<select name="type" class="form-select">
										<option value="1" <?php echo ($row['type'] == 1) ? 'selected' : ''; ?>>Articulate</option>
										<option value="9" <?php echo ($row['type'] == 9) ? 'selected' : ''; ?>>Audio Version</option>
										<option value="2" <?php echo ($row['type'] == 2) ? 'selected' : ''; ?>>Video</option>
										<option value="8" <?php echo ($row['type'] == 8) ? 'selected' : ''; ?>>Video Sub Page</option>
										<option value="3" <?php echo ($row['type'] == 3) ? 'selected' : ''; ?>>Html</option>
										<option value="10" <?php echo ($row['type'] == 10) ? 'selected' : ''; ?>>Text</option>
										<!--<option value="4" <?php echo ($row['type'] == 4) ? 'selected' : ''; ?>>Quiz</option>
									 <option value="5" <?php echo ($row['type'] == 5) ? 'selected' : ''; ?>>SCQ</option>
										<option value="6" <?php echo ($row['type'] == 6) ? 'selected' : ''; ?>>MCQ</option> -->
									</select>
								</div>

								<div class="form-group col-md-2 mb-2">
									<label>Page Number</label>
									<input type="text" step="0.1" class="form-control col-md-12" name="page_number" placeholder="Page Number" value="<?php echo $row['page_number'] ?>" />
								</div>
								<?php if ($sub_page_main != 0) { ?>
									<div class="form-group col-md-2 mb-2">
										<label>Return Page</label>
										<input type="text" step="0.1" class="form-control col-md-12" name="sub_page_main" placeholder="Return Page" value="<?php echo $row['sub_page_main'] ?>" />
									</div>
								<?php } else {
									echo '<input type="hidden" name="sub_page_main" value="0" />';
								} ?>
								<div class="form-group col-md-2 mb-2">
									<label>Status</label>
									<select name="status" class="form-select">
										<option value="1" <?php echo ($row['status'] == 1) ? 'selected' : ''; ?>>Editing</option><!--
										<option value="2" <?php echo ($row['status'] == 2) ? 'selected' : ''; ?>>CE Rev</option>
										<option value="3" <?php echo ($row['status'] == 3) ? 'selected' : ''; ?>>CE Fix</option>
										<option value="4" <?php echo ($row['status'] == 4) ? 'selected' : ''; ?>>Client Rev</option>
										<option value="5" <?php echo ($row['status'] == 5) ? 'selected' : ''; ?>>Client Fix</option> -->
										<option value="6" <?php echo ($row['status'] == 6) ? 'selected' : ''; ?>>Ready for Dev</option>
										<option value="0" <?php echo ($row['status'] == 0) ? 'selected' : ''; ?>>Delete</option>
									</select>
								</div>
								<div class="form-group col-md-2 mt-3 mb-2">
									<?php if (isset($coursevalidation)) : ?>
										<div class=col-12 col-sm-4>
											<div class="alert alert-white" role="alert">
												<?= $coursevalidation->listErrors() ?>
											</div>
										</div>
									<?php endif; ?>
									<input type="hidden" name="page_id" value="<?php echo $row['page_id']; ?>">
									<!-- <input type="hidden" name="status" value="1"> -->
									<button type="submit" class="btn btn-add-page waves-effect btn-sm rounded-pill waves-light mb-3" id="submitButton">
										Update
									</button>
								</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	if ($row['type'] != 5 && $row['type'] != 6) { ?>

		<div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-full-width modal-dialog modal-full-width-scrollable" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="fullWidthModalLabel">Description</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/add_content') ?>" method="POST"><?= csrf_field() ?>
							<div class="row">
								<div class="col-md-6">
									<div class="mb-1">
										<label for="inputEmail3" class="col-form-label">Audio</label>
										<div>
											<textarea class="ckeditor" name="audio" required></textarea>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-1">
										<label for="inputEmail3" class="col-form-label">On Screen Text</label>
										<div>
											<textarea class="ckeditor" name="on_screen_text" required></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="mb-1">
										<label for="inputEmail3" class="col-form-label">Production Notes</label>
										<div>
											<textarea class="ckeditor" name="production_notes" required></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="text-center">
								<input type="number" class="form-control col-md-12 mb-1" name="sequence"  min="0" placeholder="Sequence" value="" />

								<?php if (isset($coursevalidation)) : ?>
									<div class=col-12 col-sm-4>
										<div class="alert alert-white" role="alert">
											<?= $coursevalidation->listErrors() ?>
										</div>
									</div>
								<?php endif; ?>
								<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
								<button type="submit" class="btn btn-add-page rounded-pill waves-effect btn-sm waves-light mb-3">
									Submit
								</button>
							</div>
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog modal-full-width -->
		</div><!-- /.modal -->
		<div class="row">
			<div class="col-6 col-md-6 col-lg-12">
				<div class="card">

					<div class="card-body">
						<div class="d-flex justify-content-end mb-3"><button data-bs-toggle="modal" data-bs-target="#full-width-modal" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light"><span class="mdi mdi-plus-circle"></span> Add Content</button></div>

						<div class="table-responsive">
							<table class="table pev-table dt-responsive nowrap w-100">
								<thead>
									<tr>
										<th style="width: 5%; max-width: 40%;">#</th>
										<th style="width: 30%; max-width: 40%;">Audio</th>
										<th style="width: 25%; max-width: 40%;">On Screen</th>
										<th style="width: 25%; max-width: 40%;">Notes</th>
										<th style="width: 15%; max-width: 40%;">Action</th>
								</thead>
								<tbody>

									<?php $j = 0;
									foreach ($page_content as $eachpagesDetails) {
										$j = $j + 1;
									?>
										<tr>
											<td><?php echo $eachpagesDetails['page_sequense'] ?></td>
											<td><?php echo $eachpagesDetails['audio'] ?></td>
											<td><?php echo $eachpagesDetails['on_screen_text'] ?></td>
											<td><?php echo $eachpagesDetails['production_notes'] ?></td>
											<td>
												<button data-bs-toggle="modal" data-bs-target="#full-width-modal-<?php echo  $j; ?>" class="btn btn-outline-warning rounded-pill waves-effect btn-xs waves-light">Edit</button>
												<form class="form-horizontal mb-2 d-inline" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_del_content') ?>" method="POST"><?= csrf_field() ?>
													<input type="hidden" name="scourse_id" value="<?php echo $course_id; ?>">
													<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
													<input type="hidden" name="page_content_id" value="<?php echo $eachpagesDetails['page_content_id']; ?>">
													<button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light">Delete</button>
												</form>
											</td>
										</tr>
										<div id="full-width-modal-<?php echo  $j; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel-<?php echo  $j; ?>" aria-hidden="true">
											<div class="modal-dialog modal-full-width">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title" id="fullWidthModalLabel-<?php echo  $j; ?>">Description</h4>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
														<form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/update_content') ?>" method="POST"><?= csrf_field() ?>
															<div class="row">
																<div class="col-md-6">
																	<div class="mb-1">
																		<label for="inputEmail3" class="col-form-label">Audio</label>
																		<div>
																			<textarea class="ckeditor" name="audio" value="><?php echo $eachpagesDetails['audio'] ?>" required><?php echo $eachpagesDetails['audio'] ?></textarea>
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="mb-1">
																		<label for="inputEmail3" class="col-form-label">On Screen Text</label>
																		<div>
																			<textarea class="ckeditor" name="on_screen_text" value="<?php echo $eachpagesDetails['on_screen_text'] ?>" required><?php echo $eachpagesDetails['on_screen_text'] ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="mb-1">
																		<label for="inputEmail3" class="col-form-label">Production Notes</label>
																		<div>
																			<textarea class="ckeditor" name="production_notes" value="<?php echo $eachpagesDetails['production_notes'] ?>" required><?php echo $eachpagesDetails['production_notes'] ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
															<div class="text-center">
																<input type="number" class="form-control col-md-12 mb-1" name="sequence" min="0" placeholder="Sequence" value="<?php echo $eachpagesDetails['page_sequense'] ?>" />

																<?php if (isset($coursevalidation)) : ?>
																	<div class=col-12 col-sm-4>
																		<div class="alert alert-white" role="alert">
																			<?= $coursevalidation->listErrors() ?>
																		</div>
																	</div>
																<?php endif; ?>
																<!-- <input type="hidden" name="page_id" value="<?php echo $page_id; ?>"> -->
																<input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
																<input type="hidden" name="page_content_id" value="<?php echo $eachpagesDetails['page_content_id'] ?>">
																<button type="submit" class="btn btn-outline-warning rounded-pill waves-effect btn-sm waves-light">
																	Update
																</button>
															</div>
														</form>
													</div>
												</div><!-- /.modal-content -->
											</div><!-- /.modal-dialog modal-full-width -->
										</div><!-- /.modal -->

									<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php } ?>
	</div>
</div>
