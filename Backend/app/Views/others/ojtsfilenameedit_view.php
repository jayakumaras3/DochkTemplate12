<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Ojts_consolidated/ojts_download_pdf'); ?>">OJTS Dashboard</a></li>

                </ol>
            </div>
            <h4 class="page-title">Edit Title</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/updatefilenameojts') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="form-group col-md-12 mb-2">
                            <label>Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control col-md-12 mb-1" name="filename" placeholder="Title" value="<?php echo $ojts_row[0]['filename'] ?>" maxlength="115" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group  col-md-12 mb-2">
                            <?php if (isset($ojtsvalidation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-white" role="alert">
                                        <?= $ojtsvalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="ojts_id" value="<?php echo $ojts_row[0]['ojts_id'] ?>">
                            <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light float-end col-md-12" id="submitButton">
                                Update
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>