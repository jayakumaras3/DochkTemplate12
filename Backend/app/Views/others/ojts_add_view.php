<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Ojts_consolidated'); ?>">OJT Edit</a></li>

                </ol>
            </div>
            <h4 class="page-title">Add Task Details</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="card">
        <div class="card-body">
            <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/add_ojts') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                <div class="row">
                    <div class="form-group col-md-4 mb-2">
                        <label>Sequence Number<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="sl_no" min="1" placeholder="Sl No" id="sl_no" value="<?php echo isset($ojts_consolidatedData[0]['sl_no']) ? $ojts_consolidatedData[0]['sl_no'] + 1 : 1; ?>" required />
                    </div>
                    <div class="form-group col-md-8 mb-2">
                        <label>Task Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Task Title" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12 mb-2">
                        <label>Task<span class="text-danger">*</span></label>
                        <textarea class="ckeditor" name="task" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12 mb-2">

                        <input type="hidden" name="ojts_id" value="<?php echo $ojts_id;  ?>">
                        <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light float-end col-md-12" id="submitButton">
                            Create
                        </button>
                    </div>
                    <?php if (isset($ojtsavalidation)) : ?>
                        <div class="col-12 col-sm-4">
                            <div class="alert alert-danger" role="alert">
                                <?= $ojtsavalidation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </form>
        </div>
    </div>
</div>