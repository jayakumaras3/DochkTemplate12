<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url('marketplace/Learning_dashboard/learning_courses') ?>">Learning Courses</a></li>
                </ol>
            </div>
            <h4 class="page-title">Upload Thumbnail</h4>
        </div>
    </div>
</div>
<!-- end page title -->

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
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">

                <h5 class="mb-3 text-uppercase bg-light p-2"> Thumbnail</h5>
                <?php if ($row['thumbnail'] == '') { ?>
                <?php } else { ?>
                    <div class="head bg-dot30 np tac">
                        <img style="max-height:100px;"
                            src="<?php echo base_url() ?>/assets/assets/uploads/learning_path/<?php echo $mp_id ?>/<?php echo $row['thumbnail'] ?>"
                            class="img-squre img-thumbnail" />
                    </div><br />
                <?php } ?>

                <form enctype="multipart/form-data"
                    action="<?php echo base_url('marketplace/admin/thumbnail_upload') ?>" method="post" id="submitForm"><?= csrf_field() ?>
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
                                <input type="hidden" name="mp_id" value="<?php echo $mp_id ?>">
                                <input type="hidden" name="mp_name" value="<?php echo $row['mp_name'] ?>">
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
</div>