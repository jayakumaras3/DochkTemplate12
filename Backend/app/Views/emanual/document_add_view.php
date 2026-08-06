<?php
if (isset($header_link)) {
	$_SESSION['header_link'] = $header_link;
	$header_link = $_SESSION['header_link'];
	// print_r($header_link);
}
?>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b>
			<li class="active"><?php echo $sub_header_1; ?></li>
		</ol>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="x_panel">
			<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
				<div class="col-md-6">
					<div class="form-group col-md-12">
						<label>Document Name</label>
						<input type="text" class="form-control col-md-12" name="document_name" placeholder="Document Name" />
					</div>
				<!-- 	<div class="form-group col-md-12">
						<label>Type of Document</label>
						<select name="type" class="form-control">
							<option value="6">Maintenance Manual</option>
							<option value="7">TroubleShooting</option>
						</select>
					</div> -->
					<input type="hidden" value="2" name="launch_link">
					<input type="hidden" value="6" name="type">
					
					<!-- <div class="form-group col-md-12">
						<label>Type of Link</label>
						<select name="launch_link" class="form-control">
							<option value="1" >Open</option>
							<option value="2" >Login Based</option>
						</select>
					</div> -->
					<div class="form-group  col-md-12">
						<?php if (isset($coursevalidation)) : ?>
							<div class=col-12 col-sm-4>
								<div class="alert alert-danger" role="alert">
									<?= $coursevalidation->listErrors() ?>
								</div>
							</div>
						<?php endif; ?>
						<input type="hidden" name="em_id" value="<?php echo $em_id ?>">
						<button type="submit" class="btn btn-info btn-sm col-md-4">
							<i class="ace-icon fa fa-key bigger-110"></i> <?php echo  lang('Buttons.Create') ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>