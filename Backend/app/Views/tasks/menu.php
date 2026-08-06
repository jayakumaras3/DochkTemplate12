<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $ActiveMenu; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="inbox-leftbar">
                    <div class="mail-list">
                        <a <?php if ($ActiveMenu == 'Task Dashboard') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Task/Task_manage") ?>" class="list-group-item border-0"><i class="mdi mdi-clipboard-check-outline font-18 align-middle me-2"></i>Task Dashboard</a>
                        <a <?php if ($ActiveMenu == 'Completed Task') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Task/Task_manage/completed_task") ?>" class="list-group-item border-0"><i class="mdi mdi-clipboard-check-multiple-outline font-18 align-middle me-2"></i>Completed Task</a>
                        <a <?php if ($ActiveMenu == 'My Task Report') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Task/Task_manage/my_task_report") ?>" class="list-group-item border-0"><i class="mdi mdi-circle-slice-5 font-18 align-middle me-2"></i>My Task Report</a>
                        <?php
                        $userlevel = session()->get('userlevel');
                        $arrayuserlevel = explode(',', $userlevel);
                        $projectManager = 0;
                        if (in_array('4', $arrayuserlevel)) {
                            $projectManager = 1;
                        }
                        ?>

                        <?php if (session()->get('report_to_you') == 2) { ?>
                            <a <?php if ($ActiveMenu == 'Team Tasks') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Task/Task_manage/team_tasks") ?>" class="list-group-item border-0"><i class="mdi mdi-clipboard-account-outline font-18 align-middle me-2"></i>Team Tasks</a>
                        <?php }
                        if (session()->get('report_to_you') == 2 || $projectManager == 1) { ?>
                            <a <?php if ($ActiveMenu == 'Project Tasks') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url("Task/Task_manage/project_tasks") ?>" class="list-group-item border-0"><i class="mdi mdi-clippy font-18 align-middle me-2"></i>Project Tasks</a>
                        <?php } ?>
                    </div>
                </div>