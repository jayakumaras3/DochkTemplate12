<style>
	.card {
		border-radius: 16px;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
		border: none;
	}

	[data-bs-theme="dark"] .card {
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3), 0 2px 6px rgba(0, 0, 0, 0.2);
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
		color: var(--ct-body-color);
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
					<li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/view_full_sb'); ?>">Storyboard</a></li>
				</ol>
			</div>
			<h4 class="page-title">Audio Transcript - <?php echo esc($row['page_name']) ?></h4>
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
				<div class="d-flex align-items-center gap-1">
					<?php if ($prev_page) { ?>
						<form class="d-inline" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
							<input type="hidden" name="page_id" value="<?php echo $prev_page[0]['page_id'] ?>">
							<input type="hidden" name="page_number" value="<?php echo $prev_page[0]['page_number'] ?>">
							<input type="hidden" name="page_name" value="<?php echo $prev_page[0]['page_name'] ?>">
							<button type="submit" title="Previous Page" class="pev-nav-btn waves-effect"><i class="mdi mdi-arrow-left"></i></button>
						</form>
					<?php } ?>

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

			<?php if ($row['type'] != 5 && $row['type'] != 6) { ?>
				<button data-bs-toggle="modal" data-bs-target="#full-width-modal" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light"><span class="mdi mdi-plus-circle"></span> Add Content</button>
			<?php } ?>
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
										<textarea class="ckeditor-lite" name="audio" required></textarea>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-1">
									<label for="inputEmail3" class="col-form-label">On Screen Text</label>
									<div>
										<textarea class="ckeditor-lite" name="on_screen_text" required></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="mb-1">
									<label for="inputEmail3" class="col-form-label">Sequence</label>
									<input type="number" class="form-control col-md-12 mb-1" name="sequence" min="0" placeholder="Sequence" value="" />
								</div>
							</div>
							<div class="col-md-8">
								<div class="mb-1">
									<label for="inputEmail3" class="col-form-label">Production Notes</label>
									<div>
										<textarea class="form-control" name="production_notes"></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="text-center">

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
																		<textarea class="ckeditor-lite" name="audio" value="><?php echo $eachpagesDetails['audio'] ?>" required><?php echo $eachpagesDetails['audio'] ?></textarea>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="mb-1">
																	<label for="inputEmail3" class="col-form-label">On Screen Text</label>
																	<div>
																		<textarea class="ckeditor-lite" name="on_screen_text" value="<?php echo $eachpagesDetails['on_screen_text'] ?>" required><?php echo $eachpagesDetails['on_screen_text'] ?></textarea>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="row">
																<div class="col-md-4">
																	<div class="mb-1">
																		<label for="inputEmail3" class="col-form-label">Sequence</label>
																		<input type="number" class="form-control col-md-12 mb-1" name="sequence" min="0" placeholder="Sequence" value="<?php echo $eachpagesDetails['page_sequense'] ?>" />
																	</div>
																</div>
																<div class="col-md-8">
																	<div class="mb-1">
																		<label for="inputEmail3" class="col-form-label">Production Notes</label>
																		<div>
																			<textarea class="form-control" name="production_notes" value="<?php echo $eachpagesDetails['production_notes'] ?>" ><?php echo $eachpagesDetails['production_notes'] ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
															<div class="text-center">

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

<script>
	(function() {
		var ckTextareas = Array.prototype.slice.call(document.querySelectorAll('textarea.ckeditor-lite'));
		if (!ckTextareas.length) return;

		var initCkeditors = function() {
			ckTextareas.forEach(function(textarea) {
				CKEDITOR.replace(textarea, {
					toolbar: [
						{ name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'] },
						{ name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote'] },
						{ name: 'links', items: ['Link', 'Unlink'] }
					],
					removePlugins: 'elementspath',
					resize_enabled: true
					<?php if (session('theme_color') != 0): ?>
					,
					contentsCss: ['<?= base_url(); ?>/public/assets/ckeditor/contents-dark.css']
					<?php endif; ?>
				});
			});
		};

		if (window.CKEDITOR) {
			initCkeditors();
		} else {
			var ckScript = document.createElement('script');
			ckScript.src = '<?= base_url(); ?>/public/assets/ckeditor/ckeditor.js';
			ckScript.onload = initCkeditors;
			document.body.appendChild(ckScript);
		}
	})();
</script>