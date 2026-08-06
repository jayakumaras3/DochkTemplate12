<div class="inbox-rightbar">


    <div class="row mb-2">
        <div class="col-sm-4">
            <form action="<?php echo base_url("Project/projects/add_projects_view") ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="cid" value="MQ==">
                <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-plus"></i> Create New Project</button>
            </form>
        </div>
        <div class="col-sm-8">
            <div class="text-sm-end">
                <div class="btn-group mb-3">
                    <button type="button" class="btn btn-success waves-effect disabled btn-sm waves-light mb-3">Ongoing</button>
                </div>
                <div class="btn-group mb-3 ms-1">
                    <button type="button" class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3 btn-light">Finished</button>
                </div>
            </div>
        </div><!-- end col-->
    </div>

    <div class="col-lg-12 col-md-6 col-xl-4">
        <div class="card m-1 shadow-none border">
            <div class="card-body">
                <!-- Title-->
                <div class="dropdown float-end">
                    <a href="#" class="dropdown-toggle card-drop arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-horizontal m-0 text-muted h3"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="<?php echo base_url('scorm/scorm_courses/course_add_view'); ?>">Courses</a>
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item" href="#">Members</a>
                        <a class="dropdown-item" href="#">Task</a>
                        <a class="dropdown-item" href="#">Minutes</a>
                        <a class="dropdown-item" href="#">WIP</a>
                    </div>
                </div>
                <h4 class="mt-0"><a href="project-detail.html" class="text-dark"><b>(214)</b> AC4400 Elearning</a></h4>
                <p class="text-muted text-uppercase"><i class="fas fa-user-tie"></i> <small>Wabtec</small>
                <div class="badge bg-soft-success text-success mb-3">Finished</div>
                </p>

                <!-- Task info-->
                <h6 class="my-1 text-muted">Start Date: AUG 02, 2024</h6>
                <h6 class="my-1 text-muted">End Date: AUG 02, 2024</h6>
                <h6 class="my-1 text-muted">WIP: 40%</h6><br>
                <!-- Progress-->
                <div class="avatar-group mb-3" id="tooltips-container">
                    <a href="javascript: void(0);" class="avatar-group-item">
                        <img src="<?php echo base_url(); ?>public/creative/assets/images/users/user-1.jpg" class="rounded-circle avatar-sm" alt="friend" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Mat Helme" data-bs-original-title="Mat Helme">
                    </a>

                    <a href="javascript: void(0);" class="avatar-group-item">
                        <img src="<?php echo base_url(); ?>public/creative/assets/images/users/user-2.jpg" class="rounded-circle avatar-sm" alt="friend" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Michael Zenaty" data-bs-original-title="Michael Zenaty">
                    </a>

                    <a href="javascript: void(0);" class="avatar-group-item">
                        <img src="<?php echo base_url(); ?>public/creative/assets/images/users/user-3.jpg" class="rounded-circle avatar-sm" alt="friend" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="James Anderson" data-bs-original-title="James Anderson">
                    </a>

                    <a href="javascript: void(0);" class="avatar-group-item">
                        <img src="<?php echo base_url(); ?>public/creative/assets/images/users/user-4.jpg" class="rounded-circle avatar-sm" alt="friend" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Mat Helme" data-bs-original-title="Mat Helme">
                    </a>

                    <a href="javascript: void(0);" class="avatar-group-item">
                        <img src="<?php echo base_url(); ?>public/creative/assets/images/users/user-5.jpg" class="rounded-circle avatar-sm" alt="friend" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Username" data-bs-original-title="Username">
                    </a>
                </div>
                <p class="mb-2 fw-semibold">Task completed: <span class="float-end">28/78</span></p>
                <div class="progress mb-1" style="height: 7px;">
                    <div class="progress-bar"
                        role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"
                        style="width: 34%;">
                    </div><!-- /.progress-bar .progress-bar-danger -->
                </div><!-- /.progress .no-rounded -->
            </div>
        </div> <!-- end card box-->
    </div><!-- end col-->
</div>
</div>
</div>
</div>
</div>
</div>