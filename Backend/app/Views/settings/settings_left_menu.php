<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($link1); ?>"><?php echo $link1_name; ?></a></li>
 
                </ol>
            </div>
            <h4 class="page-title"><?php echo $link3_name; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="inbox-leftbar">
                    <div class="mail-list mt-3">
                        <a <?php if ($link3_name == 'Category') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url('SCORM/scorm_meta_category/category'); ?>" class="list-group-item border-0"><i class="mdi mdi-server font-18 align-middle me-2"></i>Categories</a>
                        <a <?php if ($link3_name == 'Course Group') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url('SCORM/Scorm_learn_group') ?>" class="list-group-item border-0"><i class="mdi mdi-lightbulb-group-outline font-18 align-middle me-2"></i>Course Group</a>
                        <a <?php if ($link3_name == 'User Group') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url('SCORM/Scorm_user_group') ?>" class="list-group-item border-0"><i class="mdi mdi-account-group-outline font-18 align-middle me-2"></i>User Group</a>
                    </div>
                </div>