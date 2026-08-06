<?php $accessmenu = session()->get('accessmenu');
$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu)); ?>
<style>
	/* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php)
	   and other redesigned pages this session. */
	.certificate-table-card {
		border: none;
		border-radius: 18px;
		overflow: hidden;
		box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
	}

	.certificate-table-card table.dataTable thead th {
		border-bottom: 2px solid #eef2f7;
		color: #6c757d;
		font-weight: 700;
		white-space: nowrap;
	}

	[data-bs-theme="dark"] .certificate-table-card table.dataTable thead th {
		border-bottom-color: #36404a;
		color: #cedeef;
	}

	.certificate-table-card table.dataTable tbody td {
		vertical-align: middle;
	}

	.certificate-table-card .dataTables_length select {
		border-radius: .5rem;
		border: 1px solid #dee2e6;
		padding: .25rem 1.75rem .25rem .6rem;
	}

	.certificate-table-card .dataTables_filter input {
		border-radius: 2rem;
		border: 1px solid #dee2e6;
		padding: .4rem .75rem;
		min-width: 260px;
	}

	[data-bs-theme="dark"] .certificate-table-card .dataTables_length select,
	[data-bs-theme="dark"] .certificate-table-card .dataTables_filter input {
		border-color: #36404a;
	}

	.certificate-table-card .pagination .page-link {
		border: none;
		margin: 0 2px;
		border-radius: 0;
		color: #6658dd;
	}

	.certificate-table-card .pagination .page-item.active .page-link {
		background-color: #6658dd;
		color: #fff;
	}

	.certificate-table-card .pagination .page-item.disabled .page-link {
		color: #ced4da;
		background: transparent;
	}

	.create-certificate-modal {
		border-radius: 18px;
		border: none;
		box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
	}

	.create-certificate-modal .form-control,
	.create-certificate-modal .form-select {
		border-radius: 10px;
		padding: 0.55rem 0.9rem;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<?php
					$userlevel = session()->get('userlevel');
					$arrayuserlevel = explode(',', $userlevel);
					//print_r($arrayuserlevel);
					if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) {
					?>
						<li class="nav-item ms-auto">
							<button type="button"
								class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light float-end"
								data-bs-toggle="modal"
								data-bs-target="#createCertificateModal">
								<i class="mdi mdi-plus-circle"></i> <?= lang('Buttons.Create_New_Certificate') ?>
							</button>
							<div class="modal fade" id="createCertificateModal" tabindex="-1" aria-hidden="true">
								<div class="modal-dialog modal-lg modal-dialog-centered">
									<div class="modal-content create-certificate-modal">

										<div class="modal-header border-0 pb-0">
											<div>
												<h5 class="modal-title fw-bold"><?= lang('Buttons.Create_New_Certificate') ?></h5>
												<p class="text-muted font-13 mb-0"><?= lang('UI_Text.Fill_Details_Create_Certificate') ?></p>
											</div>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= lang('Buttons.Close') ?>"></button>
										</div>

										<div class="modal-body pt-2">
											<form id="createCertificateForm" action="<?php echo base_url('Certification/Dashboard/add_new_certificate') ?>" method="POST"><?= csrf_field() ?>
												<div class="mb-3">
													<label class="form-label fw-semibold"><?= lang('UI_Text.Certificate_Name') ?><span class="text-danger">*</span></label>
													<input type="text" name="name" class="form-control" required>
												</div>

											
												<input type="hidden" name="description" value="" id="descriptionHidden">
												<input type="hidden" name="duration" value="" id="durationHidden">
												

												<div class="mb-3">
													<label class="form-label fw-semibold"><?= lang('UI_Text.Certificate_Type') ?><span class="text-danger">*</span></label>
													<select class="form-select" name="type">
														<option value="4"><?= lang('UI_Text.Certification') ?></option>
														<option value="3"><?= lang('UI_Text.Courses') ?></option>
														<option value="2"><?= lang('UI_Text.Learning_Plan') ?></option>
														<?php if ($client_id == 1) { ?>
															<option value="1"><?= lang('UI_Text.Marketplace') ?></option>
														<?php } ?>
													</select>
												</div>
											</form>
										</div>

										<div class="modal-footer border-top">
										
											<button type="submit" form="createCertificateForm" class="btn btn-primary rounded-pill px-4">
												<?= lang('Buttons.Submit') ?>
											</button>
										</div>

									</div>
								</div>
							</div>




						</li>

					<?php } ?>
			</div>
			<h4 class="page-title"><?php echo lang('Buttons.Certificate'); ?></h4>
		</div>
	</div>
</div>


<?php if (!empty($get_all_certificates)) { ?>
	<div class="row">
		<div class="col-12">
			<div class="card certificate-table-card">
				<div class="card-body">
					<table id="certificates-datatable" class="table dt-responsive nowrap w-100">
						<thead>
							<tr class="table-light">
								<th>#</th>
								<th><?= lang('UI_Text.Certificate_Name') ?></th>

								<?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
									<th><?= lang('UI_Text.Certificate_Type') ?></th>
									<th><?= lang('UI_Text.Certificate_Status') ?></th>
								<?php } ?>
								<th><?= lang('UI_Text.Created_By') ?></th>
								<th><?= lang('UI_Text.Created_On') ?></th>
								<?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
									<th><?= lang('UI_Text.Action') ?></th>
									<th></th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							$j = 0;
							foreach ($get_all_certificates as $all_cert) {
								$j++;
								$type = $all_cert['type'];
								$typeval = '';
								switch ($type) {
									case 4:
										$typeval = lang('UI_Text.Certification');
										break;
									case 3:
										$typeval = lang('UI_Text.Courses');
										break;
									case 2:
										$typeval = lang('UI_Text.Learning_Plan');
										break;
									case 1:
										$typeval = lang('UI_Text.Marketplace');
										break;
								}
								$Assigntypeval = '';
								switch ($type) {
									case 4:
										$Assigntypeval = lang('UI_Text.Learning_Plan');
										break;
									case 3:
										$Assigntypeval = lang('UI_Text.Courses');
										break;
									case 2:
										$Assigntypeval = lang('UI_Text.Learning_Plan');
										break;
									case 1:
										$Assigntypeval = lang('UI_Text.Marketplace');
										break;
								}
							?>
								<tr>
									<td><?php echo $j; ?></td>
									<td><?php echo $all_cert['name']; ?></td>

									<?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) {
									?>
										<td><?php
											echo $typeval;
											?>
										</td>
										<td><?php $status = $all_cert['status'];
											switch ($status) {
												case 1:
													echo '<span class="badge bg-soft-success text-success rounded-pill p-1 px-2">' . lang('UI_Text.Active') . '</span>';
													break;
												case 0:
													echo '<span class="badge bg-soft-danger text-danger rounded-pill p-1 px-2">' . lang('UI_Text.In_Active') . '</span>';
													break;
											}
											?>
										</td>

									<?php } ?>
									<td><?php echo isset($all_cert['createdby']) ? $all_cert['createdby'] : ''; ?></td>
									<td><?php echo ($all_cert['last_updated_on'] != 0) ? date('m-d-Y', $all_cert['last_updated_on']) : ''; ?></td>

									<?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
										<td>
											<div class="d-flex gap-2">
												<form action="<?php echo base_url('Certification/Dashboard/Edit_certificate') ?>" method="POST"><?= csrf_field() ?>
													<input type="hidden" name="certificate_id" value="<?php echo $all_cert['cert_id']; ?>">

													<button type="submit" class="btn btn-outline-warning rounded-pill waves-effect btn-xs waves-light"><?= lang('Buttons.Edit') ?></button>
												</form>
												<?php if ($status == 1) { ?>
													<form action="<?php echo base_url('Certification/Dashboard/Assign_certificate') ?>" method="POST"><?= csrf_field() ?>
														<input type="hidden" name="certificate_id" value="<?php echo $all_cert['cert_id']; ?>">

														<button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"><?php echo $Assigntypeval ?></button>
													</form>
												<?php } ?>
											</div>
										</td>
										<td>
											<?php if ($type == 4) { ?>
												<!-- <form action="<?php echo base_url('Certification/Dashboard/Assign_user_to_certification_view') ?>" method="POST"><?= csrf_field() ?>
														<input type="hidden" name="certificate_id" value="<?php echo $all_cert['cert_id']; ?>">
														<input type="hidden" name="type" value="<?php echo $type; ?>">
														<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light "><i class="mdi mdi-account-outline"></i> <?= lang('Buttons.Add') ?></button>
													</form> -->
											<?php } ?>
										</td>
									<?php } ?>







								</tr>
							<?php

							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if ($.fn.DataTable.isDataTable('#certificates-datatable')) {
				return;
			}
			$('#certificates-datatable').DataTable({
				responsive: true,
				pagingType: 'simple_numbers',
				<?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
					columnDefs: [{
						orderable: false,
						targets: [-1, -2]
					}],
				<?php } ?>
				language: {
					search: '_INPUT_',
					searchPlaceholder: '<?= esc(lang('Buttons.Search'), 'js') ?>',
					lengthMenu: '_MENU_',
					info: '<?= esc(lang('UI_Text.Datatable_Info'), 'js') ?>',
					infoEmpty: '<?= esc(lang('UI_Text.Datatable_Info_Empty'), 'js') ?>',
					paginate: {
						previous: "<i class='mdi mdi-chevron-left'>",
						next: "<i class='mdi mdi-chevron-right'>"
					}
				}
			});
		});
	</script>
<?php } else { ?>
	<div class="persistent-warning">
		<div class="danger-text">
			<?php echo lang('UI_Text.No_Certification_Assigned'); ?>
		</div>
	</div>
<?php } ?>