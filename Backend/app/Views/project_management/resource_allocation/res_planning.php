<?php
$skills = array();
foreach ($department_list as $data) {
    $skills[$data['value']] = $data['name'];
}
/* $skills = array(
    "52" => "Instructional Design",
    "2" => "Content Editor",
    "3" => "Graphic Design",
    "4" => "Visual Design",
    "5" => "Visualizer",
    "6" => "Post Production",
    "7" => "Articulate",
    "8" => "3D Modeling/Texturing",
    "9" => "General Programming",
    "10" => "Quality Assurance",
    "51" => "Unity3D Programming",
    "53" => "Project Manager",
    "54" => "SME"
); */
?>
<style>
    .td_align {
        text-align: right;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Resource Planning</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form class="dropdown-item"
                            action="<?php echo base_url('task/task_manage/resource_planning') ?>" method="POST"><?= csrf_field() ?>
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="mb-1">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="clientname" class="form-label">Date <span
                                                        class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-lg-8">
                                                <select name="week" class="form-control">
                                                    <?php
                                                    $startdate = strtotime("Last Monday");
                                                    $enddate = strtotime("+3 weeks", $startdate);
                                                    while ($startdate < $enddate) {
                                                        echo '<option value="' . date("Y-m-d", $startdate) . '">' . date("Y-m-d", $startdate) . '</option>';
                                                        $startdate = strtotime("+1 week", $startdate);
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="mb-1">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="clientname" class="form-label">Domain <span
                                                        class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-lg-8">
                                                <select name="skill_val" class="form-control">
                                                    <?php
                                                    foreach ($skills as $x => $y) {
                                                        echo '<option value="' . $x . '">' . $y . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="text-sm-end  mb-1">
                                        <button type="submit"
                                            class="btn btn-outline-success waves-effect btn-sm waves-light">
                                            View Data
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php if (session()->get('report_to_you') == 2) { ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="dropdown-item"
                                action="<?php echo base_url('task/task_manage/add_resource_available') ?>" method="POST"><?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="mb-1">
                                            <label for="inputEmail3" class="col-form-label">Date</label>
                                            <div>
                                                <select name="week" class="form-control">
                                                    <?php
                                                    $startdate = strtotime("Last Monday");
                                                    $enddate = strtotime("+3 weeks", $startdate);
                                                    while ($startdate < $enddate) {
                                                        echo '<option value="' . date("Y-m-d", $startdate) . '">' . date("Y-m-d", $startdate) . '</option>';
                                                        $startdate = strtotime("+1 week", $startdate);
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="mb-1">
                                            <label for="inputEmail3" class="col-form-label">Mon</label>
                                            <div>
                                                <input class="form-control" name="mon" type="number" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="mb-1">
                                            <label for="inputEmail3" class="col-form-label">Tue</label>
                                            <div>
                                                <input class="form-control" name="tue" type="number" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="mb-1">
                                            <label for="inputEmail3" class="col-form-label">Wed</label>
                                            <div>
                                                <input class="form-control" name="wed" type="number" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="mb-1">
                                            <label for="inputEmail3" class="col-form-label">Thu</label>
                                            <div>
                                                <input class="form-control" name="thu" type="number" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="mb-1">
                                            <label for="inputEmail3" class="col-form-label">Fri</label>
                                            <div>
                                                <input class="form-control" name="fri" type="number" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="hidden" name="skill_val" value="<?php echo $skill_val; ?>" />
                                        <div class="text-sm-end  mt-1 mb-1">
                                            <button type="submit"
                                                class="btn btn-outline-warning waves-effect btn-sm waves-light">
                                                Add Available Hours
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <span style="color:red"><i>Data will overight.</i></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h3><?php echo $week; ?> : <?php
                            echo $skills[$skill_val]; ?></h3>


                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <td style="width:50px;">#</td>
                                    <td>UCN</td>
                                    <td>Project</td>
                                    <td>Client</td>
                                    <td>PM</td>
                                    <td style="width:10%">Monday</td>
                                    <td style="width:10%">Tuesday</td>
                                    <td style="width:10%">Wednesday</td>
                                    <td style="width:10%">Thursday</td>
                                    <td style="width:10%">Friday</td>
                                    <td>Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $j = 0;
                                $row_total = 0;
                                $col_mon = 0;
                                $col_tue = 0;
                                $col_wed = 0;
                                $col_thu = 0;
                                $col_fri = 0;
                                foreach ($project_list as $data) {
                                    $row_total = 0;
                                    $total_effort = $data['mon'] + $data['tue'] + $data['wed'] + $data['thu'] + $data['fri'];
                                    if ($total_effort > 0) {
                                        $j++; ?>
                                        <tr>
                                            <td><?php echo $j; ?></td>
                                            <td><?php echo $data['ucn_id']; ?></td>
                                            <td><?php echo $data['projectname']; ?></td>
                                            <td><?php echo $data['client_name']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            <td class="td_align"><?php if (isset($data['mon'])) {
                                                if ($data['mon'] > 0) {
                                                    echo $data['mon'];
                                                }

                                                $row_total = $row_total + $data['mon'];
                                                $col_mon = $col_mon + $data['mon'];
                                            } else {
                                                //echo 0;
                                            } ?></td>
                                            <td class="td_align"><?php if (isset($data['tue'])) {
                                                if ($data['tue'] > 0) {
                                                    echo $data['tue'];
                                                }
                                                $row_total = $row_total + $data['tue'];
                                                $col_tue = $col_tue + $data['tue'];
                                            } else {
                                                // echo 0;
                                            } ?></td>
                                            <td class="td_align"><?php if (isset($data['wed'])) {
                                                if ($data['wed'] > 0) {
                                                    echo $data['wed'];
                                                }
                                                $row_total = $row_total + $data['wed'];
                                                $col_wed = $col_wed + $data['wed'];
                                            } else {
                                                // echo 0;
                                            } ?></td>
                                            <td class="td_align"><?php if (isset($data['thu'])) {
                                                if ($data['thu'] > 0) {
                                                    echo $data['thu'];
                                                }
                                                $row_total = $row_total + $data['thu'];
                                                $col_thu = $col_thu + $data['thu'];
                                            } else {
                                                //  echo 0;
                                            } ?></td>
                                            <td class="td_align"><?php if (isset($data['fri'])) {
                                                if ($data['fri'] > 0) {
                                                    echo $data['fri'];
                                                }
                                                $row_total = $row_total + $data['fri'];
                                                $col_fri = $col_fri + $data['fri'];
                                            } else {
                                                // echo 0;
                                            } ?></td>
                                            <td class="td_align"><?php echo $row_total; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>

                                <tr class="table-success">
                                    <th></th>
                                    <th colspan="4">TOTAL</th>
                                    <th class="td_align"><?php echo $col_mon; ?></th>
                                    <th class="td_align"><?php echo $col_tue; ?></th>
                                    <th class="td_align"><?php echo $col_wed; ?></th>
                                    <th class="td_align"><?php echo $col_thu; ?></th>
                                    <th class="td_align"><?php echo $col_fri; ?></th>
                                    <th></th>
                                </tr>
                                <?php if (count($awail_all) > 0) { ?>
                                    <tr class="table-danger">
                                        <th></th>
                                        <th colspan="4">Awailable</th>
                                        <th class="td_align"><?php echo $awail_all[0]['mon']; ?></th>
                                        <th class="td_align"><?php echo $awail_all[0]['tue']; ?></th>
                                        <th class="td_align"><?php echo $awail_all[0]['wed']; ?></th>
                                        <th class="td_align"><?php echo $awail_all[0]['thu']; ?></th>
                                        <th class="td_align"><?php echo $awail_all[0]['fri']; ?></th>
                                        <th></th>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Employee</td>
                                <td>Assigned</td>
                                <td>Utilized</td>
                                <td>Balance</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            foreach ($employee_skill as $data) {
                                $j++;
                                ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['fname'] . ' ' . $data['lname']; ?></td>

                                    <td><?php echo $data['assigned_total'] ?></td>
                                    <td><?php echo $data['utilized_total'] ?></td>
                                    <?php $balance = $data['assigned_total'] - $data['utilized_total']; ?>
                                    <td><?php echo $balance; ?> </td>
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