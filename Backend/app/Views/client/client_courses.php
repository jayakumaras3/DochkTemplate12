<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/Scorm_client/reviews') ?>">Client Dashboard</a></li>
                </ol>
            </div>
            <h4 class="page-title">Courses</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="alternative-page-datatable" class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Course Name</th>
                            <th>Language</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Reviewers</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($courses_by_project as $data) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['course_code']; ?></td>
                                <td><?php echo $data['course_name']; ?></td>
                                <td><?php echo $data['language']; ?></td>
                                <td>
                                    <?php $type = $data['type'];
                                    // echo $type;
                                    switch ($type) {
                                        case 5:
                                            echo 'AR/VR/Sim';
                                            break;
                                        case 10:
                                            echo 'SCORM';
                                            break;
                                        case 11:
                                            echo 'Course Builder';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $status = $data['mode'];
                                    //echo $status;
                                    switch ($status) {
                                        case 1:
                                            echo 'Development';
                                            break;
                                        case 2:
                                            echo 'Live';
                                            break;
                                        case 3:
                                            echo 'Alpha Rev';
                                            break;
                                        case 4:
                                            echo 'Alpha Rev2';
                                            break;
                                        case 5:
                                            echo 'Beta Rev';
                                            break;
                                        case 6:
                                            echo 'Beta Rev2';
                                            break;
                                        case 7:
                                            echo 'Gamma Rev';
                                            break;
                                        case 8:
                                            echo 'Gamma Rev2';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <form action="<?php echo base_url('Project/client_dashboard/reviewers') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="scourse_id" value="<?php echo $data['scourse_id']; ?>">
                                        <input type="hidden" name="project_id" value="<?php echo $projectid; ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                            <span class="mdi mdi-eye-outline"></span></button>
                                    </form>
                                </td>

                                <td>

                                    <?php if ($status != 0) { ?>
                                        <form action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="detail_type" value="1">
                                             <input type="hidden" name="tab" value="1">
                                            <input type="hidden" name="crid" value="<?php echo $data['scourse_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-information-outline"></span></button>
                                        </form>

                                    <?php
                                    }
                                    ?>
                                </td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>