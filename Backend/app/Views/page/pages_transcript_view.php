<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="<?php echo base_url($header_link); ?>"> <?php echo $header; ?></a></li>

				</ol>
			</div>
			<h4 class="page-title"> <?php echo $sub_header_1; ?></h4>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-6 col-md-6 col-lg-12">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
					<div class="row">
						<div class="form-group col-md-6 mb-2">
							<label>Language</label>
							<select name="language" class="form-control">

								<?php if ($row != 0) {

									if ($row['language'] != 1) { ?>
										<option value="1">English</option>
									<?php } ?>
									<?php if ($row['language'] != 2) { ?>
										<option value="2">Spanish</option>
									<?php } ?>
									<?php if ($row['language'] != 3) { ?>
										<option value="3">French</option>
									<?php }
								} else { ?>
									<option value="1">English</option>
									<option value="2">Spanish</option>
									<option value="3">French</option>
								<?php	} ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6 mb-2">
							<label>Transcript</label>
							<input class="form-control" name="valid" type="hidden" />
							<textarea class="form-control" name="transcript"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="form-group  col-md-6 mb-2">
							<?php if (isset($coursevalidation)) : ?>
								<div class=col-12 col-sm-4>
									<div class="alert alert-white" role="alert">
										<?= $coursevalidation->listErrors() ?>
									</div>
								</div>
							<?php endif; ?>
							<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
							<button type="submit" class="btn btn-info btn-sm col-md-12">
								<i class="ace-icon fa fa-key bigger-110"></i> <?php echo  lang('Buttons.Create') ?>
							</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>



<script src="<?php echo base_url(); ?>/public/aristo_new_assets/bundles/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>/public/aristo_new_assets/js/page/ckeditor.js"></script>
<script>
	CKEDITOR.replace('editor1');
	CKEDITOR.replace('editor2');
</script>