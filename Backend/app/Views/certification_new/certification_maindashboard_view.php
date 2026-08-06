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
								<i class="mdi mdi-plus-circle"></i> <?php echo lang('UI_Text.Create_Certification'); ?>
							</button>
							<div class="modal fade" id="createCertificateModal" tabindex="-1" aria-hidden="true">
								<div class="modal-dialog modal-lg modal-dialog-centered">
									<div class="modal-content">

										<div class="modal-header">
											<h5 class="modal-title"><?php echo lang('UI_Text.Create_Certification'); ?></h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
										</div>

										<div class="modal-body">
											<form action="<?php echo base_url('Certification/certification_dashboard/add_new_certificate') ?>" method="POST"><?= csrf_field() ?>
												<?= csrf_field() ?>
												<div class="mb-3">
													<label class="form-label"><?php echo lang('UI_Text.Certification_Name'); ?><span class="text-danger"> *</span></label>
													<input type="text" name="name" class="form-control" required>
												</div>

												<div class="mb-3">
													<label class="form-label"><?= lang('UI_Text.Description') ?></label>
													<textarea class="ckeditor" name="description"></textarea>
												</div>
												<div class="mb-3">
													<label class="form-label"><?= lang('UI_Text.Duration') ?><span class="text-danger">*</span></label>
													<input type="number" name="duration" class="form-control" required>
												</div>

												<div class="mb-3">
													<label class="form-label"><?= lang('UI_Text.Certificate_Type') ?></label>
													<select class="form-select" name="type">
														<option value="4"><?= lang('UI_Text.Certification') ?></option>
														<option value="3"><?= lang('UI_Text.Courses') ?></option>
														<option value="2"><?= lang('UI_Text.Learning_Plan') ?></option>
														<?php if ($client_id == 1) { ?>
															<option value="1"><?= lang('UI_Text.Marketplace') ?></option>
														<?php } ?>
													</select>
												</div>

												<div class="text-center">
													<input type="hidden" name="type" value="4">
													<button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light"><?= lang('Buttons.Submit') ?></button>
												</div>

											</form>
										</div>

									</div>
								</div>
							</div>
						</li>







					<?php } ?>
				</ol>
			</div>
			<h4 class="page-title">Certifications Admin</h4>
		</div>
	</div>
</div>
</div><?php $accessmenu = session()->get('accessmenu');
		$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu)); ?><div class="row">
	<?php $current_url = current_url(true); // Returns CodeIgniter\HTTP\URI object 
	?>
	<?php $segment1 = uri_string(); // Returns string like 'my_training' 
	?>
	<?php $current_page = explode('/', uri_string())[0]; ?>
	<!-- <div class="col-12 mt-3"> -->
	<!--<div class="card">
			<div class="card-body p-0">
				<ul class="nav nav-tabs nav-bordered" role="tablist">
					<li class="nav-item" role="presentation">
						<a href="<?php echo base_url('my_training') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'my_training') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'my_training') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
							<i class="mdi mdi-motion-play-outline font-18 d-md-none d-block"></i>
							<span class="d-none d-md-block" style="color: <?= ($current_page == 'my_training') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.Dashboard'); ?></span>
						</a>
					</li>
					<li class="nav-item" role="presentation">
						<a href="<?php echo base_url('SCORM/scorm_courses') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'SCORM') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'SCORM') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
							<i class="mdi mdi-youtube-tv font-18 d-md-none d-block"></i>
							<span class="d-none d-md-block" style="color: <?= ($current_page == 'SCORM') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.My Courses'); ?></span>
						</a>
					</li>
					<?php if (in_array('13', $arrayaccessmenu)) { ?>
						<li class="nav-item" role="presentation">
							<a href="<?php echo base_url('marketplace/learning_dashboard') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'marketplace') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'marketplace') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
								<i class="mdi mdi-school-outline font-18 d-md-none d-block"></i>
								<span class="d-none d-md-block" style="color: <?= ($current_page == 'marketplace') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.Learning Plan'); ?></span>
							</a>
						</li>
					<?php } ?>
					<li class="nav-item" role="presentation">
						<a href="<?php echo base_url('Certification/Certification_Portal') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'Certification') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'Certification') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
							<i class="mdi mdi-certificate-outline font-18 d-md-none d-block"></i>
							<span class="d-none d-md-block" style="color: <?= ($current_page == 'Certification') ? '#ff5533' : '' ?>;"><?php echo lang('UI_Text.Certifications'); ?></span>
						</a>
					</li>
					<?php if (in_array('20', $arrayaccessmenu)) { ?>
						<li class="nav-item" role="presentation">
							<a href="<?php echo base_url('my_training/Favorites') ?>" class="nav-link px-3 py-2" aria-selected="false" role="tab" tabindex="-1">
								<i class="mdi mdi-heart-outline font-18 d-md-none d-block"></i>
								<span class="d-none d-md-block"><?php echo lang('Buttons.Favorites'); ?></span>
							</a>
						</li>
					<?php } ?>
					<?php
					$userlevel = session()->get('userlevel');
					$arrayuserlevel = explode(',', $userlevel);
					//print_r($arrayuserlevel);
					if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) {
					?>

						<li class="nav-item ms-auto mt-1 p-1">
							<button type="button"
								class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light float-end"
								data-bs-toggle="modal"
								data-bs-target="#createCertificateModal">
								<i class="mdi mdi-plus-circle"></i> <?php echo lang('UI_Text.Create_Certification'); ?>
							</button>
							<div class="modal fade" id="createCertificateModal" tabindex="-1" aria-hidden="true">
								<div class="modal-dialog modal-lg modal-dialog-centered">
									<div class="modal-content">

										<div class="modal-header">
											<h5 class="modal-title"><?php echo lang('UI_Text.Create_Certification'); ?></h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
										</div>

										<div class="modal-body">
											<form action="<?php echo base_url('Certification/certification_dashboard/add_new_certificate') ?>" method="POST"><?= csrf_field() ?>
												<?= csrf_field() ?>
												<div class="mb-3">
													<label class="form-label"><?php echo lang('UI_Text.Certification_Name'); ?><span class="text-danger"> *</span></label>
													<input type="text" name="name" class="form-control" required>
												</div>

												<div class="mb-3">
													<label class="form-label"><?= lang('UI_Text.Description') ?></label>
													<textarea class="ckeditor" name="description"></textarea>
												</div>
												<div class="mb-3">
													<label class="form-label"><?= lang('UI_Text.Duration') ?><span class="text-danger">*</span></label>
													<input type="number" name="duration" class="form-control" required>
												</div>

												<div class="mb-3">
													<label class="form-label"><?= lang('UI_Text.Certificate_Type') ?></label>
													<select class="form-select" name="type">
														<option value="4"><?= lang('UI_Text.Certification') ?></option>
														<option value="3"><?= lang('UI_Text.Courses') ?></option>
														<option value="2"><?= lang('UI_Text.Learning_Plan') ?></option>
														<?php if ($client_id == 1) { ?>
															<option value="1"><?= lang('UI_Text.Marketplace') ?></option>
														<?php } ?>
													</select>
												</div>

												<div class="text-center">
													<input type="hidden" name="type" value="4">
													<button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light"><?= lang('Buttons.Submit') ?></button>
												</div>

											</form>
										</div>

									</div>
								</div>
							</div>




						</li>

					<?php } ?>
				</ul>
</div>
</div>
</div> -->
	<!-- </div> -->

	<?php if (!empty($get_all_certificates)) { ?>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="alternative-page-datatable" class="table table-sm table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('UI_Text.Certification_Name'); ?></th>
										<?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
											<th><?= lang('UI_Text.Status') ?></th>
										<?php } ?>
										<th><?php echo lang('UI_Text.Created_By'); ?></th>
										<th><?php echo lang('UI_Text.Created_On'); ?></th>
										<th><?php echo lang('UI_Text.Action'); ?></th>
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
												<!-- <td><?php $status = $all_cert['status'];
															switch ($status) {
																case 1:
																	echo '<span class="badge bg-soft-success text-success p-1">' . lang('UI_Text.Active') . '</span>';
																	break;
																case 0:
																	echo '<span class="badge bg-soft-warning text-warning p-1">' . lang('UI_Text.In_Active') . '</span>';
																	break;
															}
															?> -->
												<td><?php
													if ($all_cert['certification_status'] == '3')
														echo '<span class="badge bg-soft-success text-success p-1">' . lang('UI_Text.Completed') . '</span>';
													elseif ($all_cert['certification_status'] == '2')
														echo '<span class="badge bg-soft-info text-info p-1">' . lang('UI_Text.In Progress') . '</span>';
													else {
														echo '<span class="badge bg-soft-warning text-warning p-1">' . lang('UI_Text.Not Started') . '</span>';
													}
													?>
												</td>
											<?php } ?>
											<td><?php echo isset($all_cert['createdby']) ? $all_cert['createdby'] : ''; ?></td>
											<td><?php echo ($all_cert['createdon'] != 0) ? date('m-d-Y', $all_cert['createdon']) : ''; ?></td>
											<td>
												<form action="<?php echo base_url('Certification/certification_dashboard/certification_view') ?>" method="POST"><?= csrf_field() ?>
													<?= csrf_field() ?>
													<input type="hidden" name="certificate_id" value="<?php echo $all_cert['cert_id']; ?>">
													<input type="hidden" name="cert_name" value="<?php echo $all_cert['name']; ?>">
													<input type="hidden" name="type" value="<?php echo $type; ?>">
													<button type="submit" class="btn btn-outline-info waves-effect btn-xs waves-light "><?= lang('Buttons.View') ?></button>
												</form>
											</td>
											<?php if ($all_cert['status'] == 3) { ?>
												<td>
													<form action="<?= base_url('Certification/certification_dashboard/view_certificate'); ?>" method="post" target="_blank" style="display:inline;">
														<?= csrf_field(); ?>
														<input type="hidden" name="course_mp_id" value="<?= $all_cert['cert_id']; ?>">
														<input type="hidden" name="type" value="2">
														<button type="submit" class="btn btn-outline-secondary btn-sm">
															<i class="mdi mdi-certificate-outline"></i> <?= lang('Buttons.View_Certificate') ?>
														</button>
													</form>
												</td>
											<?php } else {
											} ?>


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
		</div>
	<?php } else { ?>
		<div class="persistent-warning">
			<div class="danger-text">
				<?php echo lang('UI_Text.No_Certification_Assigned'); ?>
			</div>
		</div>
	<?php } ?>