<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url('Project/pricing'); ?>">Pricing</a></li><b>&nbsp;>&nbsp;</b>
	
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="x_panel">
			<form class="form-horizontal" action="<?php echo base_url('Project/pricing/updatepricing_description') ?>" method="POST"><?= csrf_field() ?>
				<div class="row">
					<div class="form-group col-md-12">
						<label>Proposal Name {Less than 100 characters}</label>
						<input type="text" class="form-control col-md-12" required name="proposal_name" value="<?php echo $pricing_value[0]['proposal_name']; ?>" />
					</div>
					<div class="form-group col-md-12">
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
					<div class="form-group col-md-12">
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

					<div class="form-group col-md-12">
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
					<div class="form-group col-md-12">
						<label>Duration (in minutes)</label>
						<input type="number" class="form-control col-md-12" required name="duration" value="<?php echo $pricing_value[0]['duration']; ?>" />
					</div>
					<div class="form-group col-md-12">
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

					<div class="form-group col-md-12">
						<label>Detailed Description</label>
						<textarea class="ckeditor" name="description"><?php echo $pricing_value[0]['description']; ?></textarea>
					</div>
					<div class="form-group col-md-12">
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
						<button type="submit" class="btn btn-warning btn-sm col-md-4">
							Update
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="col-md-6">
		<div class="block">
			<div class="x_panel">
				<div class="content controls">
					<form class="form-horizontal" action="<?php echo base_url('Project/baseline/updatebaselinevalues') ?>" method="POST"><?= csrf_field() ?>
						<table class="table  table-sm table-bordered table-striped">
							<thead>
								<tr>
									<th width=5%>#</th>
									<th>Role</th>
									<th>Baseline</th>
									<th>Planned</th>
									<th>Rate</th>
									<th>Total</th>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>ID </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['ID']; ?></td>
									<td style="text-align: right;"><input type="number"  name="ID" value="<?php echo $pricing_sheet_details[0]['ID']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['ID']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['ID'] * $pricing_sheet_details[0]['ID']; ?></td>
								</tr>
								<tr>
									<td>2</td>
									<td>CE </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['CE']; ?></td>
									<td style="text-align: right;"><input type="number" name="CE" value="<?php echo $pricing_sheet_details[0]['CE']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['CE']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['CE'] * $pricing_sheet_details[0]['CE']; ?></td>
								</tr>
								<tr>
									<td>3</td>
									<td>Viz </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['Viz']; ?></td>
									<td style="text-align: right;"><input type="number" name="Viz" value="<?php echo $pricing_sheet_details[0]['Viz']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['Viz']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['Viz'] * $pricing_sheet_details[0]['Viz']; ?></td>
								</tr>
								<tr>
									<td>4</td>
									<td>SME </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['SME']; ?></td>
									<td style="text-align: right;"><input type="number" name="SME" value="<?php echo $pricing_sheet_details[0]['SME']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['SME']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['SME'] * $pricing_sheet_details[0]['SME']; ?></td>
								</tr>
								<tr>
									<td>5</td>
									<td>VD </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['VD']; ?></td>
									<td style="text-align: right;"><input type="number" name="VD" value="<?php echo $pricing_sheet_details[0]['VD']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['VD']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['VD'] * $pricing_sheet_details[0]['VD']; ?></td>
								</tr>
								<tr>
									<td>6</td>
									<td>Flash </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['flash']; ?></td>
									<td style="text-align: right;"><input type="number" name="flash" value="<?php echo $pricing_sheet_details[0]['flash']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['flash']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['flash'] * $pricing_sheet_details[0]['flash']; ?></td>
								</tr>
								<tr>
									<td>7</td>
									<td>3D </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['3D']; ?></td>
									<td style="text-align: right;"><input type="number" name="3D" value="<?php echo $pricing_sheet_details[0]['3D']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['3D']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['3D'] * $pricing_sheet_details[0]['3D']; ?></td>
								</tr>
								<tr>
									<td>8</td>
									<td>PP </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['PP']; ?></td>
									<td style="text-align: right;"><input type="number" name="PP" value="<?php echo $pricing_sheet_details[0]['PP']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['PP']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['PP'] * $pricing_sheet_details[0]['PP']; ?></td>
								</tr>
								<tr>
									<td>9</td>
									<td>Arti. </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['Articulate']; ?></td>
									<td style="text-align: right;"><input type="number" name="Articulate" value="<?php echo $pricing_sheet_details[0]['Articulate']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['Articulate']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['Articulate'] * $pricing_sheet_details[0]['Articulate']; ?></td>
								</tr>
								<tr>
									<td>10</td>
									<td>Prog. </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['Prog']; ?></td>
									<td style="text-align: right;"><input type="number" name="Prog" value="<?php echo $pricing_sheet_details[0]['Prog']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['Prog']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['Prog'] * $pricing_sheet_details[0]['Prog']; ?></td>
								</tr>
								<tr>
									<td>11</td>
									<td>Unity </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['Unity']; ?></td>
									<td style="text-align: right;"><input type="number" name="Unity" value="<?php echo $pricing_sheet_details[0]['Unity']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['Unity']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['Unity'] * $pricing_sheet_details[0]['Unity']; ?></td>
								</tr>
								<tr>
									<td>12</td>
									<td>QA </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['QA']; ?></td>
									<td style="text-align: right;"><input type="number" name="QA" value="<?php echo $pricing_sheet_details[0]['QA']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['QA']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['QA'] * $pricing_sheet_details[0]['QA']; ?></td>
								</tr>
								<tr>
									<td>13</td>
									<td>PMO </td>
									<td style="text-align: right;"><?php echo $baselineVal[0]['PMO']; ?></td>
									<td style="text-align: right;"><input type="number" name="PMO" value="<?php echo $pricing_sheet_details[0]['PMO']; ?>" /></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['PMO']; ?></td>
									<td style="text-align: right;"><?php echo $project_pricing_details[0]['PMO'] * $pricing_sheet_details[0]['PMO']; ?></td>
								</tr>
								<tr>
									<td style="text-align: right;"></td>
									<td>TOTAL </td>
									<td style="text-align: right;"></td>
									<td style="text-align: right;"></td>
									<td style="text-align: right;"></td>
									<td style="text-align: right;"></td>
								</tr>
							</tbody>
						</table>



						<div class="row">
							<div class="form-group  col-md-12">
								<input type="hidden" name="bid" value="<?php echo $baselineVal[0]['bid']; ?>" />
								<button type="submit" class="btn btn-info btn-sm col-md-4">
									<i class="ace-icon fa fa-key bigger-110"></i> Update
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.span -->