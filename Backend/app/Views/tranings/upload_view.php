<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <!-- <li><a href="<?php echo base_url('User_login/client_users/manageTrainings') ?>">Courses</a> -->
            </li><b> &nbsp;>&nbsp;</b>
            <li class="active">Upload</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <div class="form-row">
                <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('User_login/client_users/uploadDocument') ?>" method="POST"><?= csrf_field() ?>
                    <div class="form-group col-md-12">
                        <!-- Note:<br />
                                <small>1.Filename name should not contain space.</small><br />
                                <small>2.File size should be less than or equal to 1MB.</small><br />
                                <small>3.File extension supports for <b>pdf,docx,xls,ppt,pptc</b></small><br/><br/> -->
                        <!-- <input type="text" placeholder="Description" name="description" class="form-control" /> -->
                    </div>
                    <div class="form-group col-md-12">
                        <input type="file" name="file" />
                    </div>
                    <div class="form-group col-md-12">
                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                        <button type="submit" class="btn btn-danger btn-sm form-control">Upload</button>
                    </div>
                    <!-- <div><i class="fa fa-spinner fa-spin">Loading..Please wait</i></div>-->
                    <div></div>
                    <?php if (isset($validation)) : ?>  
                        <div class="form-group col-md-2">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="x_panel">

        </div>
    </div>
</div>