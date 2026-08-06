<?php
if(isset($header_link)){
	$_SESSION['header_link'] = $header_link;
	$header_link = $_SESSION['header_link'];
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
<?php if (session()->get('success')) : ?>
	<div class="alert alert-success" role="alert">
		<?= session()->get('success') ?>
	</div>
<?php endif; ?>
<?php if (session()->get('error')) : ?>
	<div class="alert alert-danger" role="alert">
		<?= session()->get('error') ?>
	</div>
<?php endif; ?>
<div class="row">
	<div class="col-md-12">
		<div class="x_panel">
			<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
				<div class="col-md-6">
					<div class="form-group col-md-12">
						<label>Product Name</label>
						<input type="text" class="form-control col-md-12" name="product_name" placeholder="Product Name" />
					</div>


					<div class="form-group  col-md-12">
						<?php if (isset($coursevalidation)) : ?>
							<div class=col-12 col-sm-4>
								<div class="alert alert-danger" role="alert">
									<?= $coursevalidation->listErrors() ?>
								</div>
							</div>
						<?php endif; ?>
						<input type="hidden" name="user" value="">
						<input type="hidden" name="addprojects" value="1">
						<button type="submit" class="btn btn-info btn-sm col-md-4">
							<i class="ace-icon fa fa-key bigger-110"></i> <?php echo  lang('Buttons.Create') ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>