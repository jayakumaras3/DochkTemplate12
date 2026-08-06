<style>
    /* Same rounded-corner + shadow treatment used across the redesigned pages this session
       (e.g. Certification/Dashboard, etrack/claims). */
    .edit-certificate-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .edit-certificate-card .form-control,
    .edit-certificate-card .form-select {
        border-radius: 10px;
        padding: .55rem .9rem;
    }

    .edit-certificate-card + .edit-certificate-card {
        margin-top: 1.5rem;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/Dashboard') ?>"><?= lang('UI_Text.Certificate') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Edit_Certificate') ?></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card edit-certificate-card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Certification/Dashboard/update_certificate') ?>" method="POST" enctype="multipart/form-data"><?= csrf_field() ?>

                    <?php $type = $get_certificate_details[0]['type'];
                    $status = $get_certificate_details[0]['status']; ?>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold"><?= lang('UI_Text.Certificate_Name') ?></label>
                        <input type="text" name="name" class="form-control" id="name" aria-describedby="name" value="<?php echo $get_certificate_details[0]['name']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label fw-semibold"><?= lang('UI_Text.Certificate_Type') ?></label>
                        <select class="form-select" name="type">
                            <option value="4" <?php if ($type == 4) echo "SELECTED"; ?>><?= lang('UI_Text.Certification') ?></option>
                            <option value="3" <?php if ($type == 3) echo "SELECTED"; ?>><?= lang('UI_Text.Courses') ?></option>
                            <option value="2" <?php if ($type == 2) echo "SELECTED"; ?>><?= lang('UI_Text.Learning_Plan') ?></option>
                            <?php if ($client_id == 1) { ?>
                                <option value="1" <?php if ($type == 1) echo "SELECTED"; ?>><?= lang('UI_Text.Marketplace') ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><?= lang('UI_Text.Duration') ?></label>
                        <input type="number" name="duration" class="form-control" value="<?php echo $get_certificate_details[0]['duration']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold"><?= lang('UI_Text.Description') ?></label>
                        <textarea class="ckeditor" name="description" rows="2"><?php echo $get_certificate_details[0]['description']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold"><?= lang('UI_Text.Status') ?></label>
                        <select class="form-select" name="status">
                            <option value="0" <?php if ($status == 0) echo "SELECTED"; ?>><?= lang('UI_Text.In_Active') ?></option>
                            <option value="1" <?php if ($status == 1) echo "SELECTED"; ?>><?= lang('UI_Text.Active') ?></option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="background_image" class="form-label fw-semibold"><?= lang('UI_Text.Background_Image') ?></label>
                        <input type="file" name="background_image" class="form-control" id="background_image" accept="image/*">
                        <small class="form-text text-muted">JPG, PNG, GIF (Max 5MB)</small>
                        <div class="alert alert-warning font-13 mt-2 mb-0">
                            <?= lang('Statements.State_0006') ?>
                        </div>
                        <div id="bg_preview" class="d-none mt-3">
                            <div class="fw-semibold mb-2"><?= lang('UI_Text.Preview') ?></div>
                            <img id="bg_preview_img" src="" alt="Background preview" class="img-thumbnail" style="max-width:100%;">
                            <div id="bg_preview_info" class="font-13 text-muted mt-2"></div>
                        </div>
                    </div>

                    <?php if (!empty($get_certificate_details[0]['background_image'])) { ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><?= lang('UI_Text.Current_Background') ?></label>
                            <div>
                                <img src="<?php echo base_url($get_certificate_details[0]['background_image']); ?>" alt="Current Background" class="img-thumbnail" style="max-width: 100%; max-height: 150px;">
                            </div>
                        </div>
                    <?php } ?>

                    <div class="mt-4">
                        <button type="submit" id="cert_update_btn" class="btn btn-outline-warning rounded-pill waves-effect waves-light"><?= lang('Buttons.Update') ?></button>
                    </div>
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
    <!-- <div class="col-lg-4"> -->
        <!-- <div class="card edit-certificate-card">
            <div class="card-body">
                <h5 class="mb-3 fw-bold"><?= lang('Buttons.Thumbnail') ?></h5>
                <?php if (!empty($get_certificate_details[0]['thumbnail'])) { ?>
                    <div class="mb-3">
                        <img style="max-height:100px;"
                            src="<?php echo base_url() ?>/assets/assets/uploads/certification_path/<?php echo $certificate_id ?>/<?php echo $get_certificate_details[0]['thumbnail'] ?>"
                            class="img-thumbnail" />
                    </div>
                <?php } ?>

                <form enctype="multipart/form-data"
                    action="<?php echo base_url('Certification/Dashboard/thumbnail_upload') ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <p class="text-danger font-13"><?= lang('UI_Text.Thumbnail_Upload_Note') ?></p>

                    <div class="mb-3">
                        <label for="firstname" class="form-label fw-semibold"><?= lang('UI_Text.Upload_Thumbnail_File') ?></label>
                        <input type="file" name="file" class="form-control" required />
                        <input type="hidden" name="certificate_id" value="<?php echo $certificate_id ?>">
                    </div>
                    <input type="hidden" name="tab" value="3">
                    <button type="submit" class="btn btn-outline-success rounded-pill waves-effect waves-light"
                        id="submitButton"><?= lang('Buttons.Upload') ?></button>
                </form>
                <?php if (isset($thumbnailvalidation)): ?>
                    <div class="alert alert-danger mt-3 mb-0" role="alert">
                        <?= $thumbnailvalidation->listErrors() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div> -->

        <!-- <div class="card edit-certificate-card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Certification/Dashboard/assign_certification_to_client') ?>" method="POST"><?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="clientname" class="form-label fw-semibold"><?= lang('UI_Text.Assign_Certification_To_Client') ?></label>
                        <select name="client" class="form-select">
                            <?php foreach ($getclients as $client) { ?>
                                <option value="<?php echo $client['id_c'] ?>"><?php echo $client['client_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <input type="hidden" name="certificate_id" value="<?php echo $certificate_id ?>">
                    <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect waves-light"
                        id="submitButton"><?= lang('Buttons.Submit') ?></button>
                </form>
            </div>
        </div> -->
    <!-- </div> -->
</div>

<script>
    // Client-side preview and A4 aspect ratio check for background image
    (function() {
        const fileInput = document.getElementById('background_image');
        const previewBox = document.getElementById('bg_preview');
        const previewImg = document.getElementById('bg_preview_img');
        const previewInfo = document.getElementById('bg_preview_info');
        const submitBtn = document.getElementById('cert_update_btn');

        const a4Ratio = 210 / 297; // ~0.707
        const tolerance = 0.02; // 2%

        if (!fileInput) return;

        fileInput.addEventListener('change', function(e) {
            const file = this.files && this.files[0];
            if (!file) {
                previewBox.classList.add('d-none');
                previewImg.src = '';
                previewInfo.innerHTML = '';
                submitBtn.disabled = false;
                return;
            }

            // Basic client-side validations
            const allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
            if (!allowed.includes(file.type)) {
                alert('Only JPG, PNG, GIF and SVG files are allowed.');
                this.value = '';
                return;
            }
            if (file.size > 5242880) { // 5MB
                alert('File size must not exceed 5MB.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(ev) {
                previewImg.src = ev.target.result;
                previewBox.classList.remove('d-none');

                // Create an Image to measure dimensions
                const img = new Image();
                img.onload = function() {
                    const w = img.width;
                    const h = img.height || 1;
                    const ratio = w / h;
                    const diff = Math.abs(ratio - a4Ratio);
                    let msg = 'Image size: ' + w + '×' + h + ' px. Aspect ratio: ' + ratio.toFixed(3) + '. ';
                    if (diff <= tolerance) {
                        msg += '<span class="text-success">Aspect ratio looks good for A4 portrait.</span>';
                        submitBtn.disabled = false;
                    } else {
                        msg += '<span class="text-warning">Warning: aspect ratio differs from A4 portrait. Please upload an image with A4 proportions (210×297 mm).</span>';
                        submitBtn.disabled = true;
                    }
                    previewInfo.innerHTML = msg;
                };
                img.onerror = function() {
                    previewInfo.innerHTML = '<span class="text-danger">Could not read image dimensions.</span>';
                    submitBtn.disabled = true;
                };
                img.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        });
    })();
</script>
