<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b>
     
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                 <?= csrf_field() ?>
                <div class="col-md-6">
                    <div class="form-group col-md-12">
                        <label>Training Name</label>
                        <input type="text" class="form-control col-md-12" name="name" placeholder="Training Name" value="<?php echo $row['name'] ?>" />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Training Description</label>
                        <input type="text" class="form-control col-md-12" name="description" placeholder="Training Description" value="<?php echo $row['description'] ?>" />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Link</label>
                        <input type="text" class="form-control col-md-12" name="sim_link" placeholder="Link" value="<?php echo $row['sim_link'] ?>" />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Training Cost in Points</label>
                        <input type="text" class="form-control col-md-12" name="sim_price" placeholder="Training Cost in Points" value="<?php echo $row['price'] ?>" />
                    </div>
                    <!-- <div class="form-group col-md-12">
						<label>Type of Training</label>
						<input type="text" class="form-control col-md-12" name="sim_type" value="English" />
					</div> -->


                    <div class="form-group  col-md-12">
                        <input type="hidden" name="training_id" value="<?php echo $row['id'] ?>">
                        <?php if (isset($coursevalidation)) : ?>
                            <div class=col-12 col-sm-4>
                                <div class="alert alert-danger" role="alert">
                                    <?= $coursevalidation->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-warning btn-sm col-md-4">
                            <i class="ace-icon fa fa-key bigger-110"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>