<div class="row">
    <div class="col-md-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">My Projects</h4>
        </div>
    </div>
</div>

<div class="row">


    <?php
    foreach ($project_list as $data) {
    ?>
        <div class="col-md-4">
            <div class="card m-1 shadow-none border">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle card-drop arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-horizontal m-0 text-muted h3"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!--   <form class="dropdown-item" action="<?php echo base_url('Project_Manage/PM_ucn/edit_project_details') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                                    <input type="hidden" name="returnid" value="2">
                                    <button type="submit" class="btn btn-sm btn-link">
                                        Edit Project</button>
                                </form> -->
                    <!--         <form class="dropdown-item" action="<?php echo base_url('Project_Manage/PM_ucn/project_breakdown') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                                <input type="hidden" name="returnid" value="2">
                                <button type="submit" class="btn btn-sm btn-link">
                                    Project Breakup</button>
                            </form> -->
                            <form class="dropdown-item" action="<?php echo base_url('Project_Manage/PM_ucn/edit_project_details') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                                <input type="hidden" name="returnid" value="2">
                                <button type="submit" class="btn btn-sm btn-link">
                                    Edit Project</button>
                            </form>
                            <form class="dropdown-item" action="<?php echo base_url('SCORM/scorm_courses/course_add_view') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                                <button type="submit" class="btn btn-sm btn-link">
                                    Linked Courses</button>
                            </form>
                            <form class="dropdown-item" action="<?php echo base_url('Project/Project_plan') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                                <button type="submit" class="btn btn-sm btn-link">
                                    Project Plan</button>
                            </form>
                        </div>
                    </div>


                    <form action="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn') ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="ucn_id" value="<?php echo $data['ucn']; ?>">
                        <input type="hidden" name="client" value="<?php echo $data['client']; ?>">
                        <button type="submit" class="btn">
                            UCN : <?php echo $data['ucn'] ?>
                        </button>
                    </form>

                    <form action="<?php echo base_url('Project_Manage/PM_ucn/project_breakdown') ?>" method="POST"><?= csrf_field() ?>
                        
                        <input type="hidden" name="returnid" value="2">
                        <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                        <button type="submit" class="btn">
                            <h5><?php echo $data['projectname'] ?></h5>
                        </button>
                    </form>


                    <div><small>Client : <?php echo $data['client_name'] ?></small></div>

                    <?php
                    $status = $data['status'];
                    if ($status == 1) {
                        echo '<div class="badge bg-soft-primary text-success mb-1">Active</div>';
                    }
                    if ($status == 2) {
                        echo '<div class="badge bg-soft-primary text-success mb-1">Beta</div>';
                    }
                    if ($status == 5) {
                        echo '<div class="badge bg-soft-primary text-success mb-1">Gamma</div>';
                    }
                    if ($status == 3) {
                        echo '<div class="badge bg-soft-danger text-success mb-1">On Hold</div>';
                    }

                    ?>

                    <div><small>Start Date: <?php echo $data['start_date'] ?></small></div>

                </div>
            </div> <!-- end card box-->
        </div><!-- end col-->
    <?php
    }
    ?>
</div>
</div>