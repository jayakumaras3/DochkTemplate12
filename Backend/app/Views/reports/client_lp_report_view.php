<?php $accessmenu = session()->get('accessmenu');
$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu)); ?>
<?php $current_url = current_url(true); // Returns CodeIgniter\HTTP\URI object 
?>
<?php $segment1 = uri_string(); // Returns string like 'my_training' 
?>
<?php $current_page = explode('/', uri_string())[2]; 

?>
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-bordered" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="<?php echo base_url('Reports/client_reports') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'SCORM') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'SCORM') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
                            <i class="mdi mdi-youtube-tv font-18 d-md-none d-block"></i>
                            <span class="d-none d-md-block" style="color: <?= ($current_page == 'client_reports') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.My Courses'); ?> <?php echo lang('Buttons.Report'); ?></span>
                        </a>
                    </li>
                    <?php if (in_array('13', $arrayaccessmenu)) { ?>
                        <li class="nav-item" role="presentation">
                            <a href="<?php echo base_url('Reports/client_reports/lp') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'marketplace') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'marketplace') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
                                <i class="mdi mdi-school-outline font-18 d-md-none d-block"></i>
                                <span class="d-none d-md-block" style="color: <?= ($current_page == 'lp') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.Learning Plan'); ?> <?php echo lang('Buttons.Report'); ?></span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array('15', $arrayaccessmenu)) { ?>
                        <li class="nav-item" role="presentation">
                            <a href="<?php echo base_url('Reports/client_reports/cert') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'Certification') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'Certification') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
                                <i class="mdi mdi-certificate-outline font-18 d-md-none d-block"></i>
                                <span class="d-none d-md-block" style="color: <?= ($current_page == 'cert') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.Certifications'); ?> <?php echo lang('Buttons.Report'); ?></span>
                            </a>
                        </li>
                    <?php } ?>

                </ul> <!-- end nav-->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                            <i class="fe-users font-22 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo count($total_users); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Total_Users'); ?></p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                            <i class="fe-book-open font-22 avatar-title text-success"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">0</span></h3>
                            <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Total_lp'); ?></p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                            <i class="fe-thumbs-up font-22 avatar-title text-info"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $total_completed; ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Completed'); ?></p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                            <i class="fe-play font-22 avatar-title text-warning"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $total_inprogress; ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.In Progress'); ?></p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
</div>

