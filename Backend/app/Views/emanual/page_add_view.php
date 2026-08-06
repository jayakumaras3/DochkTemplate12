<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url($sub_header_2_link); ?>"><?php echo $sub_header_2; ?></a></li><b>&nbsp;>&nbsp;</b>
			<li class="active"><?php echo $sub_header_3; ?></li>
		</ol>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="x_panel">
			<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
				<div class="col-md-6">
					<div class="form-group col-md-12">
						<label>Page Name</label>
						<input type="text" class="form-control col-md-12" name="page_name" placeholder="Page Name" />
					</div>

					<div class="form-group  col-md-12">
						<?php if (isset($coursevalidation)) : ?>
							<div class=col-12 col-sm-4>
								<div class="alert alert-danger" role="alert">
									<?= $coursevalidation->listErrors() ?>
								</div>
							</div>
						<?php endif; ?>
						<input type="hidden" name="emd_id" value="<?php echo $emd_id ?>">
						<button type="submit" class="btn btn-info btn-sm col-md-4">
							<i class="ace-icon fa fa-key bigger-110"></i> <?php echo  lang('Buttons.Create') ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
