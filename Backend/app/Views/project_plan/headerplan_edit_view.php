<?php
$userlevel = session()->get('userlevel');
// \print_r( $userlevel);
$array  = explode(',', $userlevel);
if (in_array("6", $array) || in_array("4", $array)) {  ?>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project/project_plan') ?>">Project plan</a></li>
                   
                    </ol>
                </div>
                <h4 class="page-title"> Edit Project Plan header</h4>
            </div>
        </div>
    </div>
    <div class="block">

        <div class="card">
            <div class="card-body">
                <form class="form" action="<?php echo base_url('Project/project_plan/editplanheader'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="header_name" placeholder="Header Name" value="<?php echo $row['header_name'] ?>" />
                    </div><br />
                    <div class="col-md-2">
                        <input type="hidden" name="course_id" value="<?php echo $course_id ?>">
                        <input type="hidden" name="projectid" value="<?php echo $projectid ?>">
                        <input type="hidden" name="id_ph" value="<?php echo $row['id_ph']; ?>">
                        <button type="submit" class="btn btn-warning btn-sm form-control">
                            <i class="ace-icon fa fa-key bigger-110"></i> Update
                        </button>
                    </div>
                    <?php if (isset($validationData)) : ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validationData->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>