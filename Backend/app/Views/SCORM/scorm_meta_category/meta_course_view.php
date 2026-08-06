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
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <p class="text-muted font-13 mb-4"></p>
                                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Course Name</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php $j = 0;
                                        foreach ($row  as $course) {
                                            $j = $j + 1; ?>
                                            <tr>
                                                <td><?php echo $j; ?></td>
                                                <td><?php echo  $course['course_name']; ?></td>
                                                <td>
                                                    <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="scourse_id" value="<?php echo $course['scourse_id']; ?>">
                                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>