<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($sub_header_1_link) ?>"><?php echo $sub_header_1; ?></a></li>
               
                </ol>
            </div>
            <h4 class="page-title"><?php echo $sub_header_2; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="inbox-leftbar">
                    <div class="mail-list mt-3">
                        <a href="<?php echo base_url('holiday/holidays') ?>" class="list-group-item border-0"><i class="fe-calendar font-18 align-middle me-2"></i>Holidays</a>
                        <a href="<?php echo base_url('Project/baseline'); ?>" class="list-group-item border-0"><i class="fe-map font-18 align-middle me-2"></i>Baseline</a>
                        <a href="<?php echo base_url('SCORM/scorm_meta_category/category'); ?>" class="list-group-item border-0"><i class="fe-server font-18 align-middle me-2"></i>Categories</a>
                        <!-- <a href="#" class="list-group-item border-0"><i class="fe-chevron-down font-18 align-middle me-2"></i>Dropdown Manager</a> -->
                        <a href="<?php echo base_url('SCORM/scorm_course_group') ?>" class="list-group-item border-0"><i class="fe-shield  font-18 align-middle me-2"></i>Course Group</a>
                        <a href="<?php echo base_url('SCORM/Scorm_learn_group') ?>" class="list-group-item border-0"><i class="fe-smartphone font-18 align-middle me-2"></i>Course Group</a>
                    </div>
                </div>
                <div class="inbox-rightbar">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-muted font-13 mb-4">Edit Meta Data</p>

                                    <form action="<?php echo base_url($form_link) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                                        <div class="mb-3">
                                            <input type="text" name="description" class="form-control" placeholder="Metadata" value="<?php echo $row[0]['description'] ?>" required="" />
                                        </div><br>
                                       
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-sm btn-warning">Update</button>
                                        </div>
                                        <?php if (isset($validation)) : ?>
                                            <div class=col-12 col-sm-4>
                                                <div class="alert alert-danger" role="alert">
                                                    <?= $validation->listErrors() ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>