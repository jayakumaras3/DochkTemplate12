<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url('Project/pricing'); ?>">Pricing</a></li><b>&nbsp;>&nbsp;</b>
		
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="x_panel">
				<div class="content controls">
					<form class="form-horizontal" action="<?php echo base_url('Project/pricing/addpricing_description') ?>" method="POST"><?= csrf_field() ?>
						<div class="row">
							<div class="form-group col-md-6">
								<label>Proposal Name {Less than 100 characters}</label>
								<input type="text" class="form-control col-md-12" required name="proposal_name" value="" />
							</div>

							<div class="form-group col-md-3">
								<label>Client</label>
								<select class="form-select" name="client">
									<?php
									foreach ($client as $client_list) {
										echo '<option value="' . $client_list['id_c'] . '">' . $client_list['client_name'] . '</option>';
									}
									?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label>Sales Person</label>
								<select class="form-select" name="requested_by">
									<?php
									foreach ($sales as $sale_list) {
										echo '<option value="' . $sale_list['user_id'] . '">' . $sale_list['name'] . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label>Type of Project</label>
								<select class="form-select" name="type">
									<?php
									foreach ($baseline as $baseline_list) {
										echo '<option value="' . $baseline_list['bid'] . '">' . $baseline_list['description'] . '</option>';
									}
									?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label>Duration (in minutes)</label>
								<input type="number" class="form-control col-md-12" required name="duration" value="" />
							</div>
							
							<div class="form-group col-md-3">
								<label>Pricing Model</label>
								<select class="form-select" name="pricing_model">
									<?php
									foreach ($baselinePricing as $baselinePricing_list) {
										echo '<option value="' . $baselinePricing_list['bidc'] . '">' . $baselinePricing_list['type'] . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label>Detailed Description</label>
								<textarea  class="ckeditor" name="description"></textarea>
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