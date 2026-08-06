<style>
    .import-guide-card,
    .import-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .import-guide-card .guide-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        background: rgba(102, 88, 221, .12);
        color: #6658dd;
    }

    [data-bs-theme="dark"] .import-guide-card .guide-icon {
        background: rgba(146, 152, 245, .18);
        color: #9298f5;
    }

    .step-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }

    .step-list li {
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        margin-bottom: .55rem;
        font-size: .875rem;
    }

    .step-badge {
        flex-shrink: 0;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        font-weight: 700;
        background: #6658dd;
        color: #fff;
    }

    .mock-illustration {
        position: relative;
        width: 220px;
    }

    .mock-illustration .mock-sheet {
        border-radius: 10px;
        border: 2px solid #3b3f5c;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.15);
        background: #fff;
    }

    .mock-illustration .mock-sheet-header {
        background: #0acf97;
        height: 20px;
    }

    .mock-illustration .mock-sheet-body {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 3px;
        padding: 8px;
    }

    .mock-illustration .mock-sheet-body span {
        background: #eef2f7;
        height: 10px;
        border-radius: 2px;
    }

    .mock-illustration .mock-upload-badge {
        position: absolute;
        bottom: -14px;
        right: -14px;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #6658dd;
        color: #6658dd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 0.5rem 1rem rgba(50, 58, 70, 0.15);
    }

    [data-bs-theme="dark"] .mock-illustration .mock-sheet {
        background: #232b36;
        border-color: #36404a;
    }

    [data-bs-theme="dark"] .mock-illustration .mock-sheet-body span {
        background: #36404a;
    }

    [data-bs-theme="dark"] .mock-illustration .mock-upload-badge {
        background: #232b36;
    }

    .import-card .step-heading {
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        margin-bottom: .75rem;
    }

    .dropzone {
        border: 2px dashed #dee2e6;
        border-radius: 14px;
        padding: 2rem 1rem;
        text-align: center;
        transition: border-color .15s ease, background-color .15s ease;
    }

    .dropzone.dragover {
        border-color: #6658dd;
        background-color: rgba(102, 88, 221, .05);
    }

    [data-bs-theme="dark"] .dropzone {
        border-color: #36404a;
    }

    .dropzone-icon {
        font-size: 2.25rem;
        color: #6658dd;
    }

    [data-bs-theme="dark"] .dropzone-icon {
        color: #9298f5;
    }

    .template-table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .template-table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .template-table td {
        vertical-align: middle;
    }

    .req-pill {
        display: inline-block;
        border-radius: 2rem;
        padding: .2rem .65rem;
        font-size: .75rem;
        font-weight: 600;
    }

    .req-pill.req-yes {
        background: rgba(10, 207, 151, .15);
        color: #0acf97;
    }

    .req-pill.req-no {
        background: rgba(152, 166, 173, .15);
        color: #6c757d;
    }

    .info-callout {
        background-color: rgba(102, 88, 221, .08);
        border-radius: 12px;
        padding: .65rem 1rem;
    }

    [data-bs-theme="dark"] .info-callout {
        background-color: rgba(146, 152, 245, .12);
    }

    [data-bs-theme="dark"] .info-callout .text-primary {
        color: #9298f5 !important;
    }

    .info-callout-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(102, 88, 221, .15);
        color: #6658dd;
        flex-shrink: 0;
    }

    [data-bs-theme="dark"] .info-callout-icon {
        background: rgba(146, 152, 245, .2);
        color: #9298f5;
    }

    .security-note {
        background-color: rgba(13, 202, 240, .1);
        border: 1px solid rgba(13, 202, 240, .25);
        border-radius: 14px;
        padding: .85rem 1.1rem;
    }

    .security-note-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 202, 240, .18);
        color: #0dcaf0;
        flex-shrink: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_users'); ?>"><?= lang('UI_Text.User_Management') ?></a></li>

                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('Buttons.Import_Bulk_User') ?></h4>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" role="alert">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card import-guide-card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-start gap-3">
                            <div class="guide-icon"><i class="mdi mdi-clipboard-text-outline"></i></div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold text-primary mb-3">Follow these steps to import data</h5>
                                <ul class="step-list">
                                    <li><span class="step-badge">1</span> <span>Download the template file to insert user data.</span></li>
                                    <li><span class="step-badge">2</span> <span>Please enter valid Email ID in the Excel template.</span></li>
                                    <li><span class="step-badge">3</span> <span><strong>Warning:</strong> Email and First_name fields are mandatory. Insertion stops from that record if any of these fields is missing.</span></li>
                                    <li><span class="step-badge">4</span> <span><strong>Warning:</strong> Insertion stops from that record if Email ID is already exist.</span></li>
                                    <li><span class="step-badge">5</span> <span>Default password is common for all users, which are imported using Excel.</span></li>
                                </ul>
                                <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                                    <span class="fw-bold"><?php echo lang('UI_Text.Default_Login_Password') ?>:</span>
                                    <span id="defaultPasswordText">Welcomedochek@123</span>
                                    <button type="button" id="copyButton" class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="mdi mdi-content-copy me-1"></i><?php echo lang('Buttons.Copy') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 d-none d-lg-flex justify-content-center">
                        <div class="mock-illustration">
                            <div class="mock-sheet">
                                <div class="mock-sheet-header"></div>
                                <div class="mock-sheet-body">
                                    <?php for ($i = 0; $i < 20; $i++) { ?>
                                        <span></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="mock-upload-badge"><i class="mdi mdi-tray-arrow-up"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card import-card h-100">
            <div class="card-body">
                <div class="step-heading">
                    <span class="step-badge">1</span>
                    <div>
                        <h6 class="fw-bold mb-0"><?php echo lang('UI_Text.Download_Template') ?></h6>
                        <p class="text-muted font-13 mb-0"><?php echo lang('UI_Text.Download_Template_Sub') ?></p>
                    </div>
                </div>
                <a href="<?php echo base_url('assets/assets/uploads/sample_users_detail.xlsx'); ?>" class="btn btn-outline-success rounded-pill mb-4">
                    <i class="mdi mdi-file-excel-outline me-1"></i><?php echo lang('UI_Text.Download_Template') ?>
                </a>

                <div class="step-heading">
                    <span class="step-badge">2</span>
                    <div>
                        <h6 class="fw-bold mb-0"><?php echo lang('UI_Text.Choose_File') ?></h6>
                        <p class="text-muted font-13 mb-0"><?php echo lang('UI_Text.Choose_File_Sub') ?></p>
                    </div>
                </div>

                <form enctype="multipart/form-data" action="<?php echo base_url('User_login/client_users/bulkUsers'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="dropzone" id="dropzone">
                        <div><i class="mdi mdi-cloud-upload-outline dropzone-icon"></i></div>
                        <p class="mb-1 mt-2"><?php echo lang('UI_Text.Drag_Drop_File_Here') ?></p>
                        <p class="text-muted mb-2"><?php echo lang('UI_Text.Or') ?></p>
                        <button type="button" class="btn btn-outline-primary rounded-pill" id="chooseFileBtn"><?php echo lang('UI_Text.Choose_File') ?></button>
                        <input type="file" name="file" id="file" accept=".xlsx,.xls" required hidden>
                        <p class="text-muted font-13 mt-2 mb-0" id="fileNameDisplay"><?php echo lang('UI_Text.Xlsx_Xls_Only') ?></p>
                    </div>

                    <?php if (isset($excelvalidation)) : ?>
                        <div class="mt-3">
                            <div class="alert alert-danger" role="alert">
                                <?= $excelvalidation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary rounded-pill w-100 mt-3" id="submitButton">
                        <i class="mdi mdi-tray-arrow-up me-1"></i><?php echo lang('Buttons.Import_Bulk_User') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card import-card h-100">
            <div class="card-body">
                <h6 class="fw-bold text-primary mb-0"><?php echo lang('UI_Text.Template_Preview') ?></h6>
                <p class="text-muted font-13 mb-3"><?php echo lang('UI_Text.Template_Preview_Sub') ?></p>
                <div class="table-responsive">
                    <table class="table template-table mb-3">
                        <thead>
                            <tr>
                                <th><?php echo lang('UI_Text.Column_Name') ?></th>
                                <th><?php echo lang('UI_Text.Required') ?></th>
                                <th><?php echo lang('UI_Text.Description') ?></th>
                                <th><?php echo lang('UI_Text.Example') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>email</td>
                                <td><span class="req-pill req-yes"><?php echo lang('UI_Text.Yes') ?></span></td>
                                <td><?php echo lang('UI_Text.Users_Email_Address') ?></td>
                                <td>john.doe@company.com</td>
                            </tr>
                            <tr>
                                <td>first_name</td>
                                <td><span class="req-pill req-yes"><?php echo lang('UI_Text.Yes') ?></span></td>
                                <td><?php echo lang('UI_Text.Users_First_Name') ?></td>
                                <td>John</td>
                            </tr>
                            <tr>
                                <td>app_username</td>
                                <td><span class="req-pill req-no"><?php echo lang('UI_Text.No') ?></span></td>
                                <td><?php echo lang('UI_Text.Simulation_Login_Username') ?></td>
                                <td>johndoe</td>
                            </tr>
                            <tr>
                                <td>app_password</td>
                                <td><span class="req-pill req-no"><?php echo lang('UI_Text.No') ?></span></td>
                                <td><?php echo lang('UI_Text.Simulation_Login_Password') ?></td>
                                <td>••••••••</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="info-callout d-flex align-items-center gap-2">
                    <div class="info-callout-icon"><i class="mdi mdi-information-outline"></i></div>
                    <div class="text-primary fw-semibold font-13"><?php echo lang('UI_Text.Template_Structure_Notice') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="security-note d-flex align-items-center gap-2">
            <div class="security-note-icon"><i class="mdi mdi-shield-check-outline"></i></div>
            <div class="font-13"><strong><?php echo lang('UI_Text.Security_Note') ?>:</strong> <?php echo lang('UI_Text.Security_Note_Sub') ?></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var fileInput = document.getElementById('file');
        var dropzone = document.getElementById('dropzone');
        var chooseFileBtn = document.getElementById('chooseFileBtn');
        var fileNameDisplay = document.getElementById('fileNameDisplay');
        var defaultFileNameText = fileNameDisplay.textContent;

        function updateFileName() {
            if (fileInput.files && fileInput.files.length > 0) {
                fileNameDisplay.textContent = fileInput.files[0].name;
            } else {
                fileNameDisplay.textContent = defaultFileNameText;
            }
        }

        chooseFileBtn.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', updateFileName);

        ['dragenter', 'dragover'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
            });
        });

        dropzone.addEventListener('drop', function(e) {
            var dt = e.dataTransfer;
            if (dt && dt.files && dt.files.length > 0) {
                fileInput.files = dt.files;
                updateFileName();
            }
        });

        var copyButton = document.getElementById('copyButton');
        copyButton.addEventListener('click', function() {
            var textToCopy = document.getElementById('defaultPasswordText').textContent;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(textToCopy);
            } else {
                var textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }

            var originalHtml = copyButton.innerHTML;
            copyButton.innerHTML = '<i class="mdi mdi-check me-1"></i><?php echo esc(lang('UI_Text.Copied'), 'js') ?>';
            setTimeout(function() {
                copyButton.innerHTML = originalHtml;
            }, 2000);
        });

        var submitForm = document.getElementById('submitForm');
        var submitButton = document.getElementById('submitButton');
        submitForm.addEventListener('submit', function(e) {
            if (submitButton.disabled) {
                e.preventDefault();
                return false;
            }
            submitButton.disabled = true;
            submitButton.innerHTML = '<?php echo esc(lang('UI_Text.Uploading'), 'js') ?>';
        });
    });
</script>
