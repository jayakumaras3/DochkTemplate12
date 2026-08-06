<style>
    .settings-back-link {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-weight: 600;
        font-size: .875rem;
        color: #6658dd;
        text-decoration: none;
    }

    [data-bs-theme="dark"] .settings-back-link {
        color: #9298f5;
    }

    .settings-back-link:hover {
        text-decoration: underline;
    }

    .settings-section {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: none;
    }

    .settings-section .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .settings-section .section-title i {
        color: #6658dd;
    }

    [data-bs-theme="dark"] .settings-section .section-title i {
        color: #9298f5;
    }

    .upload-recommend-box {
        background: var(--ct-light);
        border-radius: 12px;
        padding: 1rem 1.1rem;
        margin-bottom: 1rem;
    }

    .upload-recommend-title {
        font-weight: 700;
        color: var(--ct-secondary-color);
        margin-bottom: .6rem;
    }

    .upload-recommend-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .upload-recommend-list li {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .875rem;
        color: var(--ct-body-color);
        margin-bottom: .5rem;
    }

    .upload-recommend-list li:last-child {
        margin-bottom: 0;
    }

    .upload-recommend-list li i {
        color: #2fb787;
        font-size: 1.05rem;
        flex-shrink: 0;
    }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('marketplace/learning_dashboard') ?>"><?php echo lang('UI_Text.Learning_Plan'); ?></a></li>
                    
                    <li class="breadcrumb-item"><a href="<?php echo base_url('marketplace/Learning_dashboard/learning_courses') ?>"><?php echo lang('UI_Text.Learning_Plan_Details') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.Learning_Plan_Settings'); ?> - <?php echo esc($row['mp_name']); ?></h4>
        </div>
    </div>
</div>


<div class="row mt-3">
    <!-- start chat users-->
    <div class="col-md-8">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-cog-outline"></i> <?php echo lang('UI_Text.Learning_Plan_Settings'); ?></h5>
                <form class="form-horizontal"
                    action="<?= base_url('marketplace/admin/update_marketplace_name') ?>"
                    method="post"
                    id="submitForm"><?= csrf_field() ?>

                    <div class="row mb-3">
                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Name'); ?></label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control"
                                name="marketplace_name"
                                value="<?= $row['mp_name']; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Duration_Min'); ?></label>
                        <div class="col-8 col-xl-9">
                            <input type="number" class="form-control"
                                name="duration" min="0"
                                value="<?= $row['duration']; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Mode'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="mode" required>
                                <option value="1" <?= ($row['mode'] == 1) ? 'selected' : '' ?>>
                                    <?php echo lang('UI_Text.Sequential'); ?>
                                </option>
                                <option value="2" <?= ($row['mode'] == 2) ? 'selected' : '' ?>>
                                    <?php echo lang('UI_Text.Non_Sequential'); ?>
                                </option>
                            </select>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Description'); ?></label>
                        <div class="col-8 col-xl-9">
                            <textarea class="ckeditor"
                                name="description"
                                required><?= $row['description']; ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Delete_LP'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="status" required>
                                <option value="0" <?= ($row['status'] == 0) ? 'selected' : '' ?>>
                                    <?php echo lang('UI_Text.Yes'); ?>
                                </option>
                                <option value="1" <?= ($row['status'] == 1) ? 'selected' : '' ?>>
                                    <?php echo lang('UI_Text.No'); ?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="remarks" value="<?= $row['remarks']; ?>">
                    <input type="hidden" name="language" value="<?= $row['language']; ?>">
                    <input type="hidden" name="mp_id" value="<?= $row['mp_id']; ?>">
                    <input type="hidden" name="type" value="<?= $row['type'] ?? 1; ?>">

                    <!-- Footer Buttons -->
                    <div class="text-end">
                        <button type="submit"
                            class="btn btn-outline-warning rounded-pill btn-sm waves-effect waves-light"
                            id="submitButton">
                            <?php echo lang('Buttons.Update'); ?>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <?php
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
    <div class="col-md-4">
        <div class="card settings-section mb-3">
            <div class="card-body">

                <h5 class="section-title"><i class="mdi mdi-image-outline"></i> Upload Thumbnail Image</h5>

                <?php if ($row['thumbnail'] == '') { ?>
                    <div class="upload-recommend-box">
                        <p class="upload-recommend-title mb-2">Recommended:</p>
                        <ul class="upload-recommend-list">
                            <li><i class="mdi mdi-check-circle-outline"></i> Maximum file size: 1 MB</li>
                            <li><i class="mdi mdi-check-circle-outline"></i> Maximum width: 500px</li>                        </ul>
                    </div>

                    <form enctype="multipart/form-data"
                        action="<?php echo base_url('marketplace/admin/thumbnail_upload') ?>" method="post" id="submitForm"><?= csrf_field() ?>
                        <div class="mb-3">
                            <input type="file" name="file" required class="form-control" />
                        </div>
                        <input type="hidden" name="mp_id" value="<?php echo $mp_id ?>">
                        <input type="hidden" name="mp_name" value="<?php echo $row['mp_name'] ?>">
                        <input type="hidden" name="tab" value="3">
                        <button type="submit" class="btn btn-outline-success rounded-pill waves-effect btn-sm waves-light mb-3"
                            id="submitButton"> <?php echo lang('Buttons.Upload_Thumbnail'); ?></button>

                    </form>
                <?php } else { ?>
                    <div class="upload-preview mb-3">
                        <img src="<?php echo base_url() ?>/assets/assets/uploads/learning_path/<?php echo $mp_id ?>/<?php echo $row['thumbnail'] ?>"
                            class="img-fluid rounded w-100" />
                    </div>
                    <form action="<?php echo base_url('marketplace/admin/delete_thumbnail') ?>" method="post"
                        onsubmit="return confirm('<?php echo lang('Alert.Aler_002'); ?>');"><?= csrf_field() ?>
                        <input type="hidden" name="mp_id" value="<?php echo $mp_id ?>">
                        <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-sm waves-light mb-3">
                            <span class="mdi mdi-trash-can-outline"></span> <?php echo lang('Buttons.Delete'); ?></button>
                    </form>
                <?php } ?>
                <?php if (isset($thumbnailvalidation)): ?>
                    <div class=col-12 col-sm-4>
                        <div class="alert alert-danger" role="alert">
                            <?= $thumbnailvalidation->listErrors() ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card settings-section mb-3">
            <div class="card-body">

                <h5 class="section-title"><i class="mdi mdi-panorama-outline"></i> Upload Banner Image</h5>

                <?php if ($row['banner'] == '') { ?>
                    <div class="upload-recommend-box">
                        <p class="upload-recommend-title mb-2">Recommended:</p>
                        <ul class="upload-recommend-list">
                            <li><i class="mdi mdi-check-circle-outline"></i> Maximum file size: 1 MB</li>
                            <li><i class="mdi mdi-check-circle-outline"></i> Required width: 1200px</li>                        </ul>
                    </div>

                    <form enctype="multipart/form-data"
                        action="<?php echo base_url('marketplace/admin/banner_upload') ?>" method="post" id="submitForm"><?= csrf_field() ?>
                        <div class="mb-3">
                            <input type="file" name="file" required class="form-control" />
                        </div>
                        <input type="hidden" name="mp_id" value="<?php echo $mp_id ?>">
                        <input type="hidden" name="mp_name" value="<?php echo $row['mp_name'] ?>">
                        <input type="hidden" name="tab" value="3">
                        <button type="submit" class="btn btn-outline-info rounded-pill waves-effect btn-sm waves-light mb-3"
                            id="submitButton"> Upload Banner Image</button>

                    </form>
                <?php } else { ?>
                    <div class="upload-preview mb-3">
                        <img src="<?php echo base_url() ?>/assets/assets/uploads/learning_banner_path/<?php echo $mp_id ?>/<?php echo $row['banner'] ?>"
                            class="img-fluid rounded w-100" />
                    </div>
                    <form action="<?php echo base_url('marketplace/admin/delete_banner') ?>" method="post"
                        onsubmit="return confirm('<?php echo lang('Alert.Aler_002'); ?>');"><?= csrf_field() ?>
                        <input type="hidden" name="mp_id" value="<?php echo $mp_id ?>">
                        <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-sm waves-light mb-3">
                            <span class="mdi mdi-trash-can-outline"></span> <?php echo lang('Buttons.Delete'); ?></button>
                    </form>
                <?php } ?>
                <?php if (isset($bannervalidation)): ?>
                    <div class=col-12 col-sm-4>
                        <div class="alert alert-danger" role="alert">
                            <?= $bannervalidation->listErrors() ?>
                        </div>
                    </div>
                <?php endif; ?>



            </div>

        </div>
    </div>

</div>