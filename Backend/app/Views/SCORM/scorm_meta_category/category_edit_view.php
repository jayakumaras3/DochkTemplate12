<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/scorm_meta_category/category'); ?>">Category</a></li>
                </ol>
            </div>
            <h4 class="page-title">Category Edit</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body"> 
                <form action="<?php echo base_url($form_link) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="col-lg-12 mb-2">
                        <input type="text" name="description" class="form-control" placeholder="Category" value="<?php echo $row[0]['description'] ?>" required="" />
                    </div>
                    <div class="col-lg-12 mb-2">
                        <textarea type="text" name="details" class="form-control" placeholder="Description" value="<?php echo $row[0]['details'] ?>"><?php echo $row[0]['details'] ?></textarea>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6><?php echo $row[0]['description'] ?> Logo</h6>
                <?php if ($row[0]['image']  == '') {
                ?>
                <?php } else {
                ?>
                    <div class="head bg-dot30 np tac">
                        <img style="max-height:100px;" src="<?php echo base_url() . 'assets/assets/uploads/category/' . $sc_mcid . '/' . $row[0]['image'] ?>" class="img-squre img-thumbnail" />
                    </div>
                <?php }
                ?>
                <div class="form-row">
                    <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('SCORM/scorm_meta_category/uploadCategorylogo') ?>" method="POST"><?= csrf_field() ?>
                        <div class="form-group col-md-12 mb-2">
                            <input type="file" name="file" required />
                        </div>
                        <div class="form-group col-md-12">
                            <input type="hidden" name="cid" value="<?php echo $sc_mcid; ?>">
                            <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>