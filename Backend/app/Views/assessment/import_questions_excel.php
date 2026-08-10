<style>
    .iqe-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    [data-bs-theme="dark"] .iqe-card {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
    }

    .iqe-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .iqe-icon-insert {
        background: rgba(10, 207, 151, 0.12);
        color: #0acf97;
    }

    .iqe-icon-update {
        background: rgba(247, 184, 75, 0.15);
        color: #f7b84b;
    }

    .iqe-card-subtitle {
        font-size: 12.5px;
        color: var(--ct-secondary-color);
    }

    .iqe-steps {
        background: var(--ct-tertiary-bg);
        border-radius: 12px;
        padding: 16px 16px 16px 36px;
        font-size: 13.5px;
        margin-bottom: 16px;
    }

    .iqe-steps li {
        margin-bottom: 8px;
    }

    .iqe-steps li:last-child {
        margin-bottom: 0;
    }

    .iqe-warning {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        background: rgba(241, 85, 108, 0.08);
        border: 1px solid rgba(241, 85, 108, 0.18);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
        color: var(--ct-body-color);
        margin-bottom: 16px;
    }

    .iqe-warning i {
        color: #f1556c;
        font-size: 16px;
        margin-top: 1px;
    }

    .iqe-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link_1) ?>"><?php echo $header_1 ?></a></li>
                </ol>
            </div>
            <h4 class="page-title">Import &amp; Update Questions</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 mb-3">
        <div class="card iqe-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="iqe-icon iqe-icon-insert"><i class="mdi mdi-file-import-outline"></i></span>
                    <div>
                        <div class="fw-bold">Insert New Questions</div>
                        <p class="iqe-card-subtitle mb-0">Add new questions to this quiz from a spreadsheet.</p>
                    </div>
                </div>

                <ol class="iqe-steps">
                    <li>Download the <b>Insertion Template</b> file below.</li>
                    <li>In the <b>Type</b> column, enter <b>MCQ</b> for Multiple Choice or <b>SCQ</b> for Single Choice.</li>
                    <li>In the <b>Correct</b> column, enter <b>TRUE</b> for the correct option.</li>
                </ol>

                <div class="iqe-warning">
                    <i class="mdi mdi-alert-circle-outline"></i>
                    <span>Question, Type, Option, and Correct fields are mandatory. Insertion stops from that record if any of these fields is missing.</span>
                </div>

                <div class="iqe-actions">
                    <a href="<?php echo base_url('assets/assets/uploads/insert_quiz_sample.xlsx'); ?>" target="_blank" class="btn btn-outline-info btn-sm rounded-pill waves-effect waves-light">
                        <i class="mdi mdi-file-download-outline"></i> Insertion Template
                    </a>
                </div>

                <form enctype="multipart/form-data" action="<?php echo base_url('Assessment/trainings/importNewquestionsOption'); ?>" method="post" id="iqeInsertForm"><?= csrf_field() ?>
                    <div class="mb-2">
                        <label class="fw-semibold mb-1">Questions File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx" required>
                        <small class="form-text text-muted">.xlsx files only.</small>
                    </div>
                    <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                    <input type="hidden" name="page_id" value="<?php echo $page_id ?>">
                    <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light w-100" id="iqeInsertButton">
                        <i class="mdi mdi-file-upload-outline"></i> Import New Questions Details
                    </button>
                    <?php if (isset($excelvalidation)) : ?>
                        <div class="alert alert-danger mt-2"><?= $excelvalidation->listErrors() ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 mb-3">
        <div class="card iqe-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="iqe-icon iqe-icon-update"><i class="mdi mdi-file-excel-outline"></i></span>
                        <div>
                            <div class="fw-bold">Update Existing Questions</div>
                            <p class="iqe-card-subtitle mb-0">Edit questions already in this quiz via a spreadsheet.</p>
                        </div>
                    </div>
                </div>

                <ol class="iqe-steps">
                    <li>Download the <b>Export Questions</b> file below.</li>
                    <li>In the <b>Type</b> column, enter <b>MCQ</b> for Multiple Choice or <b>SCQ</b> for Single Choice.</li>
                    <li>In the <b>Correct</b> column, enter <b>TRUE</b> for the correct option.</li>
                </ol>

                <div class="iqe-warning">
                    <i class="mdi mdi-alert-circle-outline"></i>
                    <span>Don't edit the grey highlighted area (Question IDs and Option IDs) in the spreadsheet - it's generated by the system.</span>
                </div>

                <div class="iqe-actions">
                    <form action="<?php echo base_url('Assessment/trainings/export_questions_excel') ?>" method="POST" data-download="1"><?= csrf_field() ?>
                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                        <input type="hidden" name="page_id" value="<?php echo $page_id ?>">
                        <input type="hidden" name="course_name" value="<?php echo $course_name ?>">
                        <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill waves-effect waves-light">
                            <i class="mdi mdi-cloud-download-outline"></i> Export Questions
                        </button>
                    </form>
                </div>

                <form enctype="multipart/form-data" action="<?php echo base_url('Assessment/trainings/importquestionsOption'); ?>" method="post" id="iqeUpdateForm"><?= csrf_field() ?>
                    <div class="mb-2">
                        <label class="fw-semibold mb-1">Questions File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file2" class="form-control" accept=".xlsx" required>
                        <small class="form-text text-muted">.xlsx files only.</small>
                    </div>
                    <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                    <input type="hidden" name="page_id" value="<?php echo $page_id ?>">
                    <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light w-100" id="iqeUpdateButton">
                        <i class="mdi mdi-file-upload-outline"></i> Update Questions Details
                    </button>
                    <?php if (isset($excelvalidation)) : ?>
                        <div class="alert alert-danger mt-2"><?= $excelvalidation->listErrors() ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function bindSavingIndicator(formId, buttonId, label) {
        var form = document.getElementById(formId);
        var button = document.getElementById(buttonId);
        if (form && button) {
            form.addEventListener('submit', function() {
                button.disabled = true;
                button.innerHTML = label;
            });
        }
    }
    bindSavingIndicator('iqeInsertForm', 'iqeInsertButton', 'Importing&hellip;');
    bindSavingIndicator('iqeUpdateForm', 'iqeUpdateButton', 'Updating&hellip;');
</script>
