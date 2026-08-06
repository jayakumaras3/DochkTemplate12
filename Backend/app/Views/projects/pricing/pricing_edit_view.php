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
					<form class="form-horizontal" action="<?php echo base_url('Project/pricing/updatepricing_description') ?>" method="POST"><?= csrf_field() ?>
						<div class="row">
							<div class="form-group col-md-6">
								<label>Proposal Name {Less than 100 characters}</label>
								<input type="text" class="form-control col-md-12" required name="proposal_name" value="<?php echo $pricing_value[0]['proposal_name']; ?>" />
							</div>

							<div class="form-group col-md-3">
								<label>Client</label>
								<select class="form-select" name="client">
									<?php
									$clientID = $pricing_value[0]['client'];
									foreach ($client as $client_list) {
										if ($clientID == $client_list['id_c']) {
											echo '<option SELECTED value="' . $client_list['id_c'] . '">' . $client_list['client_name'] . '</option>';
										} else {
											echo '<option value="' . $client_list['id_c'] . '">' . $client_list['client_name'] . '</option>';
										}
									}
									?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label>Sales Person</label>
								<select class="form-select" name="requested_by">
									<?php
									$requested_by = $pricing_value[0]['requested_by'];
									foreach ($sales as $sale_list) {
										if ($requested_by ==  $sale_list['user_id']) {
											echo '<option SELECTED value="' . $sale_list['user_id'] . '">' . $sale_list['name'] . '</option>';
										} else {
											echo '<option value="' . $sale_list['user_id'] . '">' . $sale_list['name'] . '</option>';
										}
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
									$type = $pricing_value[0]['type'];
									foreach ($baseline as $baseline_list) {
										if ($type ==  $baseline_list['bid']) {
											echo '<option SELECTED value="' . $baseline_list['bid'] . '">' . $baseline_list['description'] . '</option>';
										} else {
											echo '<option value="' . $baseline_list['bid'] . '">' . $baseline_list['description'] . '</option>';
										}
									}
									?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label>Duration (in minutes)</label>
								<input type="number" class="form-control col-md-12" required name="duration" value="<?php echo $pricing_value[0]['duration']; ?>" />
							</div>
							<div class="form-group col-md-3">
								<label>Pricing Model</label>
								<select class="form-select" name="pricing_model">
								<?php
									$pricing_model = $pricing_value[0]['pricing_model'];
									foreach ($baselinePricing as $baselinePricing_list) {
										if ($pricing_model ==  $baselinePricing_list['bidc']) {
											echo '<option SELECTED value="' . $baselinePricing_list['bidc'] . '">' . $baselinePricing_list['type'] . '</option>';
										} else {
											echo '<option value="' . $baselinePricing_list['bidc'] . '">' . $baselinePricing_list['type'] . '</option>';
										}
									}
									?>
								</select>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-6">
								<label>Detailed Description</label>
								<textarea class="ckeditor" name="description"><?php echo $pricing_value[0]['description']; ?></textarea>
							</div>
							<div class="form-group col-md-3">
								<label>Status</label>
								<?php $status = $pricing_value[0]['status']; ?>
								<select class="form-select" name="status">
									<option value="1" <?php if ($status == 1) echo 'SELECTED'; ?>>Editing</option>
									<option value="2" <?php if ($status == 2) echo 'SELECTED'; ?>>Ready</option>
									<option value="3" <?php if ($status == 3) echo 'SELECTED'; ?>>Sales</option>
									<option value="4" <?php if ($status == 4) echo 'SELECTED'; ?>>Rejected</option>
									<option value="5" <?php if ($status == 5) echo 'SELECTED'; ?>>Approved</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="form-group  col-md-12">
								<button type="submit" class="btn btn-info btn-sm col-md-4">
									Update
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.span -->