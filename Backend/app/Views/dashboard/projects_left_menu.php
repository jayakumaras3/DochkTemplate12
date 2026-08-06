<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <?php if (strlen($link2_name) > 3) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url($link2); ?>"><?php echo $link2_name; ?></a></li>
                    <?php } ?>

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

                        <a <?php if ($link3_name == 'Effort Sheet') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Project_Manage/PM_pricing_sheet") ?>" class="list-group-item border-0"><i class="mdi mdi-microsoft-excel font-18 align-middle me-2"></i>Effort Sheet</a>
                        <a <?php if ($link3_name == 'Purchase Orders') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Project_Manage/PM_purchase_order") ?>" class="list-group-item border-0"><i class="mdi mdi-currency-usd font-18 align-middle me-2"></i>Purchase Orders</a>
                        <a <?php if ($link3_name == 'My UCN') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Project_Manage/PM_ucn") ?>" class="list-group-item border-0"><i class="mdi mdi-controller-classic-outline font-18 align-middle me-2"></i>My UCN</a>
                        <a <?php if ($link3_name == 'Projects') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Project_Manage/PM_projects") ?>" class="list-group-item border-0"><i class="mdi mdi-folder-key-outline font-18 align-middle me-2"></i>Active Projects</a>
                        <a <?php if ($link3_name == 'Clients') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("User_login/client_list/my_client_list") ?>" class="list-group-item border-0"><i class="mdi mdi-account-cash-outline font-18 align-middle me-2"></i>My Clients</a>
                        <?php
                        $userlevel = session()->get('userlevel');
                        $arrayuserlevel = explode(',', $userlevel);
                        if (in_array('69', $arrayuserlevel)) {
                        ?>
                            <!--<a <?php if ($link3_name == 'MileStones') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Project_Manage/MileStones") ?>" class="list-group-item border-0"><i class="mdi mdi-target font-18 align-middle me-2"></i>Billing MileStones</a>
                        <a <?php if ($link3_name == 'Invoices') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Project_Manage/MileStones/invoices") ?>" class="list-group-item border-0"><i class="fe-file-text font-18 align-middle me-2"></i>Invoices</a>-->
                            <a <?php if ($link3_name == 'WIP') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Project_Manage/PM_wip") ?>" class="list-group-item border-0"><i class="mdi mdi-network-strength-2 font-18 align-middle me-2"></i>WIP Summary</a>
                        <?php
                        }
                        ?>
                        <!-- <a <?php if ($link3_name == 'Proposals') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Project_Manage/PM_Proposals") ?>" class="list-group-item border-0"><i class="fe-feather font-18 align-middle me-2"></i>Proposals</a> -->
                        <!-- <a <?php if ($link3_name == 'Reports') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Project_Manage/PM_Proposals/reports") ?>" class="list-group-item border-0"><i class="fe-bar-chart-2 font-18 align-middle me-2"></i>Reports</a> -->
                        <!-- 
                        <a href="<?php echo base_url("SCORM/scorm_courses/course_add_view") ?>" class="btn btn-primary rounded-pill waves-effect waves-light mb-3 text-white"><i class="mdi mdi-plus"></i> Create New Course</a> -->
                    </div>
                </div>