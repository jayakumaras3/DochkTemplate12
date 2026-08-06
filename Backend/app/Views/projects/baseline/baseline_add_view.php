<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>">Dashboard</a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url('Project/baseline') ?>">Baseline</a></li>
				
				</ol>
			</div>
			<h4 class="page-title">Create New Baseline</h4>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="inbox-leftbar">
					<div class="mail-list mt-3">
						<a href="<?php echo base_url('holiday/holidays') ?>" class="list-group-item border-0"><i class="fe-calendar font-18 align-middle me-2"></i>Holidays</a>
						<a href="<?php echo base_url('Project/baseline'); ?>" class="list-group-item border-0"><i class="fe-map font-18 align-middle me-2"></i>Baseline</a>
						<a href="<?php echo base_url('SCORM/scorm_meta_category/category'); ?>" class="list-group-item border-0"><i class="fe-server font-18 align-middle me-2"></i>Categories</a>
						<!-- <a href="#" class="list-group-item border-0"><i class="fe-chevron-down font-18 align-middle me-2"></i>Dropdown Manager</a> -->
						<a href="<?php echo base_url('SCORM/scorm_course_group') ?>" class="list-group-item border-0"><i class="fe-shield  font-18 align-middle me-2"></i>Course Group</a>
						<a href="<?php echo base_url('SCORM/Scorm_learn_group') ?>" class="list-group-item border-0"><i class="fe-smartphone font-18 align-middle me-2"></i>Course Group</a>
					</div>
				</div>
				<div class="inbox-rightbar">

					<div class="row">
						<div class="col-md-12">
							<div class="block">
								<div class="x_panel">
									<div class="content controls">
										<form class="form-horizontal" action="<?php echo base_url('Project/baseline/addbaselinevalues') ?>" method="POST"><?= csrf_field() ?>
											<div class="row">
												<div class="form-group col-md-6">
													<label>Name</label>
													<input type="text" class="form-control col-md-12" required name="description" value="" />
												</div>
												<div class="form-group col-md-3">
													<label>Duration in Minutes</label>
													<input type="number" class="form-control col-md-12" required name="duration" value="" />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label>ID Effort</label>
													<input type="number" class="form-control col-md-12" name="ID" value="" />
												</div>
												<div class="form-group col-md-3">
													<label>CE Effort</label>
													<input type="number" class="form-control col-md-12" name="CE" value="" />
												</div>
												<div class="form-group col-md-3">
													<label>Viz Effort</label>
													<input type="number" class="form-control col-md-12" name="Viz" value="" />
												</div>
												<div class="form-group col-md-3">
													<label>SME Effort</label>
													<input type="number" class="form-control col-md-12" name="SME" value="" />
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label>VD Effort</label>
													<input type="number" class="form-control col-md-12" name="VD" value="" />
												</div>
												<div class="form-group col-md-3">
													<label>Flash Effort</label>
													<input type="number" class="form-control col-md-12" name="flash" value="" />
												</div>
												<div class="form-group col-md-3">
													<label>3D Effort</label>
													<input type="number" class="form-control col-md-12" name="3D" value="" />
												</div>
												<div class="form-group col-md-3">
													<label>PP Effort</label>
													<input type="number" class="form-control col-md-12" name="PP" value="" />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label>Articulate</label>
													<input type="number" class="form-control col-md-12" name="Articulate" value="" />
												</div>
												<div class="form-group col-md-3">
													<label>Programmer</label>
													<input type="number" class="form-control col-md-12" name="Prog" value="" />
												</div>
												<div class="form-group col-md-3">
													<label>Unity3D</label>
													<input type="number" class="form-control col-md-12" name="Unity" value="" />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label>QA</label>
													<input type="number" class="form-control col-md-12" name="QA" value="" />
												</div>
												<div class="form-group col-md-3 mb-3">
													<label>PMO</label>
													<input type="number" class="form-control col-md-12" name="PMO" value="" />
												</div>
											</div>
											<div class="row">
												<div class="form-group  col-md-12">
													<button type="submit" class="btn btn-info btn-sm col-md-4">
														<i class="ace-icon fa fa-key bigger-110"></i> <?php echo  lang('Buttons.Create') ?>
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div><!-- /.span -->
				</div>
			</div>
		</div>
	</div>
</div>