<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Ojts_consolidated'); ?>">OJT Edit</a></li>

                </ol>
            </div>
            <h4 class="page-title">Edit Task Details</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="card">
        <div class="card-body">
            <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/update_ojts') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                <div class="row">
                    <div class="form-group col-md-4 mb-2">
                        <label>Sequence Number<span class="text-danger">*</span></label>
                        <input type="number" class="form-control col-md-12 mb-1" name="sl_no" min="1" placeholder="Sl No" value="<?php echo $ojts_row[0]['sl_no'] ?>" required />
                    </div>
                    <div class="form-group col-md-8 mb-2">
                        <label>Task Title</label>
                        <input type="text" class="form-control col-md-12 mb-1" name="title" placeholder="Task Title" value="<?php echo isset($ojts_row[0]['title']) ? $ojts_row[0]['title'] : '' ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12 mb-2">
                        <label>Task<span class="text-danger">*</span></label>
                        <textarea class="ckeditor" name="task" required><?php echo $ojts_row[0]['task'] ?></textarea>
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
                        <input type="hidden" name="ojd_id" value="<?php echo $ojts_row[0]['ojd_id'] ?>">
                        <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light float-end col-md-12" id="submitButton">
                            Update
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>