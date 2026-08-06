<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if (in_array('69', $arrayuserlevel)) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_wip'); ?>">WIP Summary</a></li>
                    <?php } ?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn'); ?>">My UCN</a></li>
                </ol>
            </div>
            <h4 class="page-title">Edit UCN - <?php echo isset($ucn) ? $ucn['ucn_id'] . ' - ' . $ucn['name'] : '';
                                                $status = isset($ucn) ? $ucn['status'] : null; ?></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <?php
        $Year = date('Y');
        $Month = date('m');
        $search_items = ["month" => $Month, "year" => $Year];
        $res = search($ucn_percent, $search_items);
        function search($array, $search_list)
        {
            $result = [];
            foreach ($array as $key => $value) {
                foreach ($search_list as $k => $v) {
                    if (!isset($value[$k]) || $value[$k] != $v) {
                        continue 2;
                    }
                }
                $result[] = $value;
            }
            return $result;
        }
        $result = count($res);
        if ($result == 0) {
        ?> <?php if (empty($ucn_percent_exist)) { ?>

                <div class="card">
                    <div class="card-body">
                        <?php
                        // Get the current day and check if it is between 1st and 5th
                        $currentDay = date('d');
                        $isFormAvailable = ($currentDay >= 1 && $currentDay <= 9);
                        ?>


                        <?php if ($isFormAvailable): ?>
                            <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/add_percentage') ?>" method="POST"><?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-1">
                                            <label for="projectname" class="form-label">Percentage Completion as of Last day of Month<span class="text-danger">*</span></label>
                                            <input required type="number" class="form-control" name="percent" min="1" max="100" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" placeholder="Percentage" value="" />
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-1">
                                            <label for="inputEmail3" class="col-form-label">Remarks</label>
                                            <textarea class="form-control" name="remarks"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-2">
                                        <input type="hidden" name="ucn" value="<?php echo $ucn_id; ?>">
                                        <div class="text-sm-end  mt-sm-0">
                                            <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                                Add UCN Total Percentage
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <div>
                                WIP can be updated from 1st to 5th of each month.
                            </div>
                        <?php endif;
                        ?>
                    </div>
                </div>

        <?php
            }
        }

        ?>
        <?php
        // Get the current day and check if it is between 1st and 5th
        $currentDay = date('d');
        $isFormAvailable = ($currentDay >= 1 && $currentDay <= 9);
        ?>


        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Remarks</th>
                                <th>WIP %</th>
                                <?php
                                if (in_array('6', $arrayuserlevel)) {
                                    echo '<th>Update</th>';
                                }
                                ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            if (count($ucn_percent) > 0) {

                                foreach ($ucn_percent as $data) {
                                    $j++;
                                    // Get the current date
                                    // $currentDate = new DateTime();
                                    // $currentDate = new DateTime($data['year'] . '-' . 02 . '02');
                                    //print_r($currentDate);
                                    // $startDate = new DateTime($data['year'] . '-' . $data['month'] . '-25');

                                    // $endDate = new DateTime($data['year'] . '-' . $data['month'] . '-25');
                                    // $endDate->modify('first day of next month'); // Move to the first of next month
                                    // $endDate->modify('+5 days'); // Add 5 days to reach the 5th of the next month

                                    // Format the month name
                                    // $dateObj   = DateTime::createFromFormat('!m', $data['month']);
                                    // $monthName = $dateObj->format('F');
                            ?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php
                                            $dateObj   = DateTime::createFromFormat('!m', $data['month']);
                                            $monthName = $dateObj->format('F');
                                            echo $monthName . '-' . $data['year']; ?></td>

                                        <td><?php echo $data['remarks']; ?></td>
                                        <?php
                                        // if ($currentDate >= $startDate && $currentDate <= $endDate) { 
                                        ?>

                                        <?php $previousMonthDate = new \DateTime();
                                        $previousMonthDate->modify('first day of last month');
                                        $previousYear = $previousMonthDate->format('Y');  // Previous Year
                                        $previousMonth = $previousMonthDate->format('m');
                                        if ($isFormAvailable && $previousMonth == $data['month']): ?>
                                            <form action="<?php echo base_url('Project_Manage/PM_ucn/update_ucn_percentage') ?>" method="POST"><?= csrf_field() ?>
                                                <td>
                                                    <input required type="number" class="form-control col-md-12" name="percent" value="<?php echo $data["percent"]; ?>" min="1" max="100" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" />
                                                </td>
                                                <input type="hidden" name="ucn_percent_id" value="<?php echo $data['ucn_percent_id']; ?>">
                                                <input type="hidden" name="ucn" value="<?php echo $ucn_id; ?>">
                                                <td> <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                        <span class="mdi mdi-square-edit-outline"></span></button>
                                                </td>
                                            </form>
                                        <?php else: ?>
                                            <td> <?php echo $data["percent"]; ?> %</td>
                                            <td></td>
                                        <?php endif; ?>


                                        <?php
                                        // } else {;
                                        //echo $startDate; 
                                        //echo ' - ';
                                        //echo $endDate;
                                        // echo '<td>' . $data["percent"];
                                        // echo ' %</td><td></td>';
                                        // }
                                        ?>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if ($ucn['status'] != 10) { ?>
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/add_user_to_pricing_sheet') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Select User <span class="text-danger">*</span></label>
                                    <select name="assignuser" class="form-control" required>
                                        <option value="">Select User</option>
                                        <?php
                                        foreach ($project_manager as $data) {
                                            echo '<option value="' . $data['id_user'] . '">'  . $data['fname'] . ' ' . $data['lname'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!--                         <div class="col-lg-6">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Select Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control">
                                    <option value="">Select Role</option>
                                    <option value="1">Project Manager</option>
                                    <option value="2">Project Coordinator</option>
                                    <option value="5">Developer</option>
                                    <option value="10">ID</option>
                                    <option value="20">Sales</option>
                                </select>
                            </div>
                        </div> -->
                            <div class="col-lg-6 mt-3">
                                <input type="hidden" name="ppid" value="<?php echo $ucn_id; ?>">
                                <input type="hidden" name="role" value="1">
                                <input type="hidden" name="returnid" value="5">
                                <input type="hidden" name="type_of_assignment" value="5">
                                <button type="submit" class="btn btn-outline-primary btn-block waves-effect btn-sm waves-light">
                                    Assign PM</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Project Manager</th>
                                <th>Un Assign</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($access as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['fname'] . ' ' . $data['lname']; ?></td>
                                    <td>

                                        <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/delete_userassignment') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="returnid" value="5">
                                            <input type="hidden" name="project_assign_id" value="<?php echo $data['project_assign_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>


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
    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <?php
                        if ($status != 10) {
                        ?>
                            <div class="mb-2">
                                <form action="<?php echo base_url('Project_Manage/PM_ucn/projects') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="ucn_id" value="<?php echo $ucn_id; ?>">
                                    <input type="hidden" name="projectclient" value="<?php echo $projectclient; ?>">
                                    <button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light  ">
                                        Add New Project
                                    </button>
                                </form>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered  mb-0">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Status</th>
                                        <th>Courses</th>
                                        <th>Effort</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_po_value = 0;
                                    $open_projects = 0;
                                    $active_pro = 0;
                                    if (count($projects) > 0) {

                                        foreach ($projects as $data) {

                                            $new_Pro =   $data['projectid'];
                                    ?>
                                            <tr>
                                                <?php
                                                if ($active_pro == $new_Pro) {
                                                    // echo '<td></td><td></td><td></td><td></td><td></td><td></td>';
                                                } else {
                                                ?>

                                                    <td><?php echo $data['projectname']; ?></td>

                                                    <td>
                                                        <?php $statusx = $data['status'];

                                                        switch ($statusx) {
                                                            case 1:
                                                                echo 'Alpha';
                                                                $open_projects = 1;
                                                                break;
                                                            case 2:
                                                                echo 'Beta';
                                                                $open_projects = 1;
                                                                break;
                                                            case 5:
                                                                echo 'Gamma';
                                                                $open_projects = 1;
                                                                break;
                                                            case 3:
                                                                echo 'On Hold';
                                                                $open_projects = 1;
                                                                break;
                                                            case 4:
                                                                echo 'Completed';
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <form action="<?php echo base_url('SCORM/scorm_courses/course_add_view') ?>" method="POST"><?= csrf_field() ?>
                                                            <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                                                            <input type="hidden" name="returnid" value="1">
                                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                                <span class="mdi mdi-youtube-tv"></span></button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="<?php echo base_url('Project_Manage/Effort_Tracker/PM_Project_Effort') ?>" method="POST"><?= csrf_field() ?>
                                                            <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                                                            <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light">
                                                                <i class="fe-clock"></i></button>
                                                        </form>
                                                    </td>
                                                    <!--             <td>
                                                        <form action="<?php echo base_url('Project_Manage/PM_ucn/project_breakdown') ?>" method="POST"><?= csrf_field() ?>
                                                            <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                                                            <input type="hidden" name="returnid" value="1">
                                                            <button type="submit" class="btn btn-outline-success waves-effect btn-xs waves-light">
                                                                <span class="mdi mdi mdi-eye-outline"></span></button>
                                                        </form>
                                                    </td> -->
                                                    <td>
                                                        <?php
                                                        if ($statusx != 4) {
                                                        ?>
                                                            <form action="<?php echo base_url('Project_Manage/PM_ucn/edit_project_details') ?>" method="POST"><?= csrf_field() ?>
                                                                <input type="hidden" name="projectid" value="<?php echo $data['projectid']; ?>">
                                                                <input type="hidden" name="returnid" value="1">
                                                                <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light">
                                                                    <span class="mdi mdi-square-edit-outline"></span></button>
                                                            </form>

                                                        <?php
                                                        }
                                                        ?>
                                                    </td>

                                                <?php
                                                }
                                                $active_pro = $new_Pro;
                                                ?>

                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                            if (in_array('69', $arrayuserlevel)) {
                            ?>

                                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/updateUCNData') ?>" method="POST"><?= csrf_field() ?>
                                    <div class="col-lg-6 mt-3">
                                        <input type="hidden" name="ucn_id" value="<?php echo $ucn['ucn_id']; ?>">
                                        <input type="hidden" name="ucn_name" value="<?php echo $ucn['name']; ?>">
                                        <?php if ($ucn['status'] != 10) {
                                        ?>
                                            <?php if ($open_projects == 0) { ?>
                                                <input type="hidden" name="status" value="10">
                                                <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light">
                                                    Close UCN
                                                </button>
                                            <?php } else {
                                                echo '<span style="color: red">UCN can be closed only if all projects are closed.</span>';
                                            } ?>
                                        <?php }  ?>


                                    </div>
                                </form>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <?php
                        if ($status != 10) {
                        ?>
                            <div class="mb-2">
                                <form action="<?php echo base_url('Project_Manage/PM_purchase_order/add_purchase_order_new') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="ucn_id" value="<?php echo $ucn_id; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-xs rounded-pill waves-effect waves-light  ">
                                        Add Purchase Order
                                    </button>
                                </form>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered  mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PO Value</th>
                                        <th>Proj Value</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $j = 0;
                                    if (count($po_details_for_ucn) > 0) {
                                        foreach ($po_details_for_ucn as $po) {
                                            $j++;

                                    ?>
                                            <tr>
                                                <td><?php echo $j; ?></td>

                                                <td><?php echo '$ ' . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $po['po_value']) . '/-'; ?></td>
                                                <td><?php echo '$ ' . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $po['project_value']) . '/-'; ?></td>
                                                <td><?php echo $po['po_status']; ?></td>
                                                <td>
                                                    <?php //if ($ucn['status'] != 10) { 
                                                    ?>
                                                    <form action="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="po_id" value="<?php echo $po['po_id']; ?>">
                                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                            <span class="mdi mdi-square-edit-outline"></span></button>
                                                    </form>
                                                    <?php //} 
                                                    ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            if ($status != 10) {
                            ?>
                                <div class="mb-2">
                                    <form action="<?php echo base_url('Project_Manage/PM_ucn/edit_effort_ucn') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="ucn_id" value="<?php echo $ucn['ucn_id']; ?>">
                                        <button type="submit" class="btn btn-outline-info btn-xs rounded-pill waves-effect waves-light  ">
                                            Edit Planned Effort and Cost
                                        </button>
                                    </form>
                                </div>
                            <?php
                            }
                            ?>
                            <table class="table table-bordered  mb-0">
                                <thead>
                                    <tr>
                                        <th>Planned Eff.</th>
                                        <th>Actual Eff.</th>
                                        <th>Planned Cost.</th>
                                        <th>Actual Cost.</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td style="text-align: right;"><?php echo $get_effort[0]['total']; ?></td>
                                        <td style="text-align: right;">
                                            <?php if (isset($get_actual)) {
                                                if ($get_actual > $get_effort[0]['total']) {
                                                    echo '<span style="color: red;">' . $get_actual . '</span>';
                                                } else {
                                                    echo $get_actual;
                                                }
                                            }  ?></td>
                                        </td>
                                        <td style="text-align: right;"><?php echo '$ ' . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $external_cost[0]['total']); ?></td>
                                        <td style="text-align: right;">
                                            <form action="<?php echo base_url('Project_Manage/PM_ucn/view_claims') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="ucn_id" value="<?php echo $ucn['ucn_id']; ?>">
                                                <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light">
                                                    $ <?php echo number_format(isset($external_actual_cost) ? $external_actual_cost : 0, 0); ?></button>
                                            </form>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>