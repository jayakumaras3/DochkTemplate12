<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b>
			<li class="active"><?php echo $sub_header_1; ?></li>
		</ol>
	</div>
</div>


<div class="row">
	<div class="col-md-4">
		<div class="x_panel">
			<h6>Thumbnail</h6>
			<?php if ($row['thumbnail'] == '') { ?>
			<?php } else { ?>
				<div class="head bg-dot30 np tac">
					<img style="max-height:100px;" src="<?php echo base_url() ?>/assets/uploads/emanual_thumbnail/<?php echo $em_id ?>/<?php echo $row['thumbnail'] ?>" class="img-squre img-thumbnail" />
				</div><br />
			<?php } ?>
			<div class="form-row">
				<form class="form-horizontal1" enctype="multipart/form-data" action="<?php echo base_url($form_url_1) ?>" method="POST"><?= csrf_field() ?>
					<div class="form-group col-md-12">
						<input type="file" name="file" />
					</div>
					<div class="form-group col-md-12">
						<input type="hidden" name="em_id" value="<?php echo $em_id ?>">
						<button type="submit" class="btn btn-info btn-sm form-control">Upload</button>
					</div>
					<?php if (isset($thumbnailvalidation)) : ?>
						<div class="form-group col-md-12">
							<div class="alert alert-danger" role="alert">
								<?= $thumbnailvalidation->listErrors() ?>
							</div>
						</div>
					<?php endif; ?>
				</form>
			</div>
		</div>
		<div class="x_panel">
			<h6>Video</h6>
			<div class="form-row">
				<form class="form-horizontal2" enctype="multipart/form-data" action=<?php echo base_url($form_url_3); ?>  method="POST"><?= csrf_field() ?>
					<div class="form-group col-md-12">
						<input type="file" name="file" />
					</div>
					<div class="form-group col-md-12">
						<input type="hidden" name="em_id" value="<?php echo $em_id ?>">
						<button type="submit" class="btn btn-success btn-sm form-control">Upload</button>
					</div>
					<?php if (isset($promovalidation)) : ?>
						<div class="form-group col-md-12">
							<div class="alert alert-danger" role="alert">
								<?= $promovalidation->listErrors() ?>
							</div>
						</div>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="x_panel">
			<form class="form-horizontal1" id="addmetadataForm">
				<div class="form-group col-md-12">
					<select class="select2" multiple="multiple" tabindex="-1" style="width:100%" name="metaCategory[]" required="">
						
					</select>
				</div>
				<div class="form-group col-md-12">
					<input type="hidden" name="em_id" value="<?php echo $em_id ?>">
					<input type="hidden" name="typeofval" value="1">
					<button type="submit" class="btn btn-success btn-sm form-control">
						<i class="ace-icon fa fa-key bigger-110"></i> Add Document name
					</button>
				</div>
				<!-- <div><i class="fa fa-spinner fa-spin">Loading..Please wait</i></div>-->

			</form>
		</div>
		<div class="x_panel">
			<div class="block block-drop-shadow">
				<div class="content">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th>ID</th>
								<th>Document</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

</div>
