<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/certification_dashboard/certification_view') ?>">Back</a></li>
                </ol>
            </div>
            <h4 class="page-title">Settings</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Certification/certification_dashboard/update_certificate') ?>" method="POST" enctype="multipart/form-data"><?= csrf_field() ?>
                    <?= csrf_field() ?>
                    <?php $type = $get_certificate_details[0]['type'];
                    $status = $get_certificate_details[0]['status'];
                    $font_size = $get_certificate_details[0]['font_size'] ?? '16';
                    $font_weight = $get_certificate_details[0]['font_weight'] ?? 'normal';
                    $font_color = $get_certificate_details[0]['font_color'] ?? '#000000'; ?>

                    <div class="row">
                        <div class="mb-3">
                            <label for="name" class="form-label"><?= lang('UI_Text.Certificate_Name') ?></label>
                            <input type="text" name="name" class="form-control" id="name" aria-describedby="name" value="<?php echo $get_certificate_details[0]['name']; ?>" required>
                        </div>




                        <!-- <div class="row"> -->
                        <!-- <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="font_size" class="form-label">Font Size (px)</label>
                                <input type="number" name="font_size" class="form-control" id="font_size" value="<?php echo $font_size; ?>" min="8" max="72" step="1" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="font_weight" class="form-label">Font Weight</label>
                                <select class="form-select" name="font_weight" id="font_weight">
                                    <option value="300" <?php if ($font_weight == '300') echo "SELECTED"; ?>>Light</option>
                                    <option value="400" <?php if ($font_weight == '400' || $font_weight == 'normal') echo "SELECTED"; ?>>Normal</option>
                                    <option value="500" <?php if ($font_weight == '500') echo "SELECTED"; ?>>Medium</option>
                                    <option value="600" <?php if ($font_weight == '600') echo "SELECTED"; ?>>Semi-Bold</option>
                                    <option value="700" <?php if ($font_weight == '700' || $font_weight == 'bold') echo "SELECTED"; ?>>Bold</option>
                                    <option value="800" <?php if ($font_weight == '800') echo "SELECTED"; ?>>Extra Bold</option>
                                    <option value="900" <?php if ($font_weight == '900') echo "SELECTED"; ?>>Black</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="font_color" class="form-label">Font Color</label>
                                <div class="input-group">
                                    <input type="color" name="font_color" class="form-control form-control-color" id="font_color" value="<?php echo $font_color; ?>" title="Choose color" style="max-width: 60px;">
                                    <input type="text" class="form-control" id="font_color_text" value="<?php echo $font_color; ?>" readonly>
                                </div>
                            </div>
                        </div> -->

                        <!-- </div> -->
                        <div class="mb-3">
                            <label class="form-label"><?= lang('UI_Text.Duration') ?></label>
                            <input type="number" name="duration" class="form-control" value="<?php echo $get_certificate_details[0]['duration']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label"><?= lang('UI_Text.Description') ?></label>
                            <textarea class="ckeditor" name="description" rows="2"><?php echo $get_certificate_details[0]['description']; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label"><?= lang('UI_Text.Status') ?></label>
                            <select class="form-select" name="status">
                                <option value="0" <?php if ($status == 0) echo "SELECTED"; ?>><?= lang('UI_Text.In_Active') ?></option>
                                <option value="1" <?php if ($status == 1) echo "SELECTED"; ?>><?= lang('UI_Text.Active') ?></option>
                            </select>
                        </div>




                        <div class="mb-3">
                            <label for="background_image" class="form-label"><?= lang('UI_Text.Background_Image') ?></label>
                            <input type="file" name="background_image" class="form-control" id="background_image" accept="image/*">
                            <small class="form-text text-muted">JPG, PNG, GIF (Max 5MB)</small>
                            <div style="margin-top:6px;font-size:13px;color:#333;border:1px solid #ddd;padding:6px;border-radius:4px;background-color:#f9f9f9;">
                                <?= lang('Statements.State_0006') ?>
                            </div>
                            <div id="bg_preview" style="display:none;margin-top:10px;">
                                <div><strong>Preview:</strong></div>
                                <div style="margin-top:6px;">
                                    <img id="bg_preview_img" src="" alt="Background preview" style="max-width:100%;border:1px solid #ddd;padding:5px;border-radius:4px;" />
                                </div>
                                <div id="bg_preview_info" style="margin-top:6px;font-size:13px;color:#333;"></div>
                            </div>
                        </div>

                        <?php if (!empty($get_certificate_details[0]['background_image'])) { ?>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label"><?= lang('UI_Text.Current_Background') ?></label>
                                    <div>
                                        <img src="<?php echo base_url($get_certificate_details[0]['background_image']); ?>" alt="Current Background" style="max-width: 100%; max-height: 150px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                                    </div>
                                </div>
                            </div>
                        <?php } ?>





                        <div class="row">
                            <div class="col-12 text-center">
                                <input type="hidden" name="type" value="4">
                                <button type="submit" id="cert_update_btn" class="btn btn-outline-warning waves-effect btn-xs waves-light"><?= lang('Buttons.Update') ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">

                <div class="row"><?php
                                    $base = base_url();
                                    //print_r($base);
                                    //exit();
                                    if ($base == 'http://localhost:8888/projects_dochek/') {
                                        $baseloc = '/Users/pchandran/Sites/projects_dochek/';
                                    }
                                    if ($base == 'http://localhost/projects_dochek/') {
                                        $baseloc = 'D:/wampp/www/projects_dochek/';
                                    }
                                    if ($base == 'https://dochek.com/') {
                                        $baseloc = '/var/www/html/';
                                    }
                                    if ($base == 'http://localhost/DOCHEK/') {
                                        $baseloc = 'C:/wampp/www/DOCHEK/';
                                    }
                                    if ($base == 'https://staging.dochek.com/') {
                                        $baseloc = '/var/www/html/DOCHEK/';
                                    }
                                    if ($base == 'http://localhost/DOCHEKDOTCOM/') {
                                        $baseloc = 'D:/wampp/www/DOCHEKDOTCOM/';
                                    }
                                    if ($base == 'http://172.16.2.218/DOCHEK/') {
                                        $baseloc = '/var/www/DOCHEK/DOCHEK_lms/';
                                    }
                                    if ($base == 'http://172.16.2.218/DOCHEK/') {
                                        $baseloc = '/var/www/DOCHEK/DOCHEK_lms/';
                                    }

                                    ?>


                    <h5 class="mb-3 text-uppercase bg-light p-2"> Thumbnail</h5>
                    <?php if ($get_certificate_details[0]['thumbnail'] == '') { ?>
                    <?php } else { ?>
                        <div class="head bg-dot30 np tac">
                            <img style="max-height:100px;"
                                src="<?php echo base_url() ?>/assets/assets/uploads/certification_path/<?php echo $certificate_id ?>/<?php echo $get_certificate_details[0]['thumbnail'] ?>"
                                class="img-squre img-thumbnail" />
                        </div><br />
                    <?php } ?>

                    <form enctype="multipart/form-data"
                        action="<?php echo base_url('Certification/certification_dashboard/thumbnail_upload') ?>" method="post" id="submitForm"><?= csrf_field() ?>
                        <?= csrf_field() ?>
                        <p style="color: red">Note: Standard format to upload Thumbnail Size : Max 200KB and
                            dimension 518x309.</p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="firstname" class="form-label">Upload Thumbnail
                                        File</label><br>
                                    <input type="file" name="file" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" name="certificate_id" value="<?php echo $certificate_id ?>">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="tab" value="3">
                        <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"
                            id="submitButton"><i class="mdi mdi-content-save"></i> Upload</button>

                    </form>
                    <?php if (isset($thumbnailvalidation)): ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $thumbnailvalidation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>




                </div>
            </div>
        </div>
        <div class="row">
            <?php if (session()->get('client') == 1) { ?>
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal" action="<?php echo base_url('Certification/certification_dashboard/assign_certification_to_client') ?>" method="POST"><?= csrf_field() ?>
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="mb-2">
                                    <label for="clientname" class="form-label"> Assign Certification to Client</label>
                                    <select name="client" class="form-control">
                                        <?php foreach ($getclients as $client) { ?>
                                            <option value="<?php echo $client['id_c'] ?>"><?php echo $client['client_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <!-- <input required type="text" class="form-control col-md-12" name="client" placeholder="Client Name" /> -->
                                </div>
                                <div class="mb-2">
                                    <label>Editable : </label>&nbsp;
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" name="editable" class="form-check-input" value="1"> Yes
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" name="editable" class="form-check-input" value="0" checked> No
                                        </label>
                                    </div>
                                </div><br>
                                <div class="mb-1">
                                    <input type="hidden" name="certificate_id" value="<?php echo $certificate_id ?>">
                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3"
                                        id="submitButton">Submit</button>
                                </div>
                            </div>

                        </form>


                    </div>
                </div>
           
            <div class="card">
                <div class="card-body">
                    <table class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Client</th>
                                <th>Editable</th>
                                <th>Delete</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($getassignedclients as $clients) {
                                $j = $j + 1;
                            ?>
                                <tr>
                                    <td><?php echo $j;  ?></td>
                                    <td><?php echo $clients['client_name']; ?></td>
                                    <td><?php echo ($clients['can_edit'] == 1) ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Certification/certification_dashboard/delete_assigned_cert_to_client') ?>" method="POST"><?= csrf_field() ?>
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="cert_client_id" value="<?php echo $clients['cert_client_id']; ?>">
                                            <button type="submit"
                                                onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"
                                                class="btn btn-outline-danger waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span> Delete</button>
                                        </form>
                                        </form>

                                    </td>
                                </tr>
                            <?php }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
             <?php } ?>
        </div>
    </div>

    <script>
        // Sync color picker with text input
        document.getElementById('font_color').addEventListener('change', function() {
            document.getElementById('font_color_text').value = this.value;
        });

        // Allow text input for color codes
        document.getElementById('font_color_text').addEventListener('blur', function() {
            const colorRegex = /^#[0-9A-F]{6}$/i;
            if (colorRegex.test(this.value)) {
                document.getElementById('font_color').value = this.value;
            } else {
                this.value = document.getElementById('font_color').value;
            }
        });

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
                    previewBox.style.display = 'none';
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
                    previewBox.style.display = 'block';

                    // Create an Image to measure dimensions
                    const img = new Image();
                    img.onload = function() {
                        const w = img.width;
                        const h = img.height || 1;
                        const ratio = w / h;
                        const diff = Math.abs(ratio - a4Ratio);
                        let msg = 'Image size: ' + w + '×' + h + ' px. Aspect ratio: ' + ratio.toFixed(3) + '. ';
                        if (diff <= tolerance) {
                            msg += '<span style="color:green">Aspect ratio looks good for A4 portrait.</span>';
                            submitBtn.disabled = false;
                        } else {
                            msg += '<span style="color:darkorange">Warning: aspect ratio differs from A4 portrait. Please upload an image with A4 proportions (210×297 mm).</span>';
                            submitBtn.disabled = true;
                        }
                        previewInfo.innerHTML = msg;
                    };
                    img.onerror = function() {
                        previewInfo.innerHTML = '<span style="color:red">Could not read image dimensions.</span>';
                        submitBtn.disabled = true;
                    };
                    img.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            });
        })();
    </script>