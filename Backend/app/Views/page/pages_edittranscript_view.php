<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
	<nav aria-label="breadcrumb">
		<!-- <h2><?php echo $sub_header_1; ?></h2> -->
		<ol class="breadcrumb mg-b-0">
			<li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url($header_link_1) ?>"><?php echo $header_1; ?></a></li>
	
		</ol>
	</nav>
</div>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a>
			</li><b>&nbsp;>&nbsp;</b>
			<li class="active">
				<?php echo $sub_header_1; ?>
			</li>
		</ol>
	</div>
</div>
<div class="section-body">
	<div class="row">
		<div class="col-6 col-md-6 col-lg-12">
			<div class="card">
				<div class="card-body">
					<form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
						<div class="row">
							<div class="form-group col-md-6">
								<label>Language</label>
								<select name="language" class="form-control">
									<option value="1" <?php echo ($row['language'] == 1) ? 'selected' : ''; ?>>English</option>
									<option value="2" <?php echo ($row['language'] == 2) ? 'selected' : ''; ?>>Spanish</option>
									<option value="3" <?php echo ($row['language'] == 3) ? 'selected' : ''; ?>>French</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label>Transcript</label>
								<input class="form-control" name="valid" type="hidden" />
								<textarea class="form-control"  name="transcript" required><?php echo $row['transcript'] ?></textarea>
							</div>
						</div>

						<div class="form-group  col-md-12">
							<?php if (isset($coursevalidation)) : ?>
								<div class=col-12 col-sm-4>
									<div class="alert alert-white" role="alert">
										<?= $coursevalidation->listErrors() ?>
									</div>
								</div>
							<?php endif; ?>
							<input type="hidden" name="t_id" value="<?php echo $t_id; ?>">
							<button type="submit" class="btn btn-warning btn-xs col-md-12">
								Update
							</button>
						</div>
				</div>
				</form>
			</div>
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