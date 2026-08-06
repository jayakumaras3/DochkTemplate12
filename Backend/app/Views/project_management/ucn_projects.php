<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn'); ?>">My UCN</a></li>

                </ol>
            </div>
            <h4 class="page-title">Create Projects</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/add_projects') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Project Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="name" placeholder="Project Name" value="" />
                            </div>
                        </div>
                        <input type="hidden" name="type_of_project" value="E-Learning">
                        <input type="hidden" name="percentage_po" value="100">
                        <!-- <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Type of Project <span class="text-danger">*</span></label>
                                <select name="type_of_project" class="form-control">
                                    <option value="E-Learning" SELECTED>E-Learning</option>
                                    <option value="Video">Video</option>
                                    <option value="AR/VR">AR/VR</option>
                                    <option value="C4U">C4U</option>
                                    <option value="Aristo">Aristo</option>
                                </select>
                            </div>
                        </div> -->
                        <!--          </div>
                    <div class="row"> -->
                        <!--  <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Percentage <span class="text-danger">*</span></label>
                                <input required type="number" class="form-control col-md-12" name="percentage_po" placeholder="PO Percentage" value="" />
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="start_date" type="date">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="end_date" type="date">
                            </div>
                        </div> -->
                    </div>
                    <input class="form-control" type="hidden" name="remarks" />

                    <input class="form-control" name="valid" type="hidden" />
                    <input class="form-control" name="client" type="hidden" value="<?php echo $projectclient; ?>" />

                    <div class="row mt-2">
                        <div class="col-12">
                            <input type="hidden" name="ucn" value="<?php echo $ucn_id; ?>">
                            <input type="hidden" name="start_date" value="<?php echo date('Y-m-d'); ?>">
                            <input type="hidden" name="end_date" value="2025-01-01">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    Create New Project
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>