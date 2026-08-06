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
    .td {
        text-align: right;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url('Project_Manage/PM_projects/skill_mapping'); ?>">Skill Mapping</a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">Resource Allocation</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="dropdown-item"
                    action="<?php echo base_url('Project_Manage/PM_projects/resource_allocation') ?>" method="POST"><?= csrf_field() ?>
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
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h3><?php echo $week; ?> : <?php
                    echo $skills[$skill_val]; ?></h3>
                <form class="dropdown-item"
                    action="<?php echo base_url('Project_Manage/PM_projects/add_assignments') ?>" method="POST"><?= csrf_field() ?>

                    <table class="table">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Project</td>
                                <td>Client</td>
                                <td>Monday</td>
                                <td>Tuesday</td>
                                <td>Wednesday</td>
                                <td>Thursday</td>
                                <td>Friday</td>
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
                                $j++;
                                ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['projectname']; ?></td>
                                    <td><?php echo $data['client_name']; ?></td>
                                    <td><input type="number" class="form-control"
                                            name="mon_<?php echo $data['projectid']; ?>"
                                            value="<?php if (isset($data['mon'])) {
                                                echo $data['mon'];
                                                $row_total = $row_total + $data['mon'];
                                                $col_mon = $col_mon + $data['mon'];
                                            } else {
                                                echo 0;
                                            } ?>"></td>
                                    <td><input type="number" class="form-control"
                                            name="tue_<?php echo $data['projectid']; ?>"
                                            value="<?php if (isset($data['tue'])) {
                                                echo $data['tue'];
                                                $row_total = $row_total + $data['tue'];
                                                $col_tue = $col_tue + $data['tue'];
                                            } else {
                                                echo 0;
                                            } ?>"></td>
                                    <td><input type="number" class="form-control"
                                            name="wed_<?php echo $data['projectid']; ?>"
                                            value="<?php if (isset($data['wed'])) {
                                                echo $data['wed'];
                                                $row_total = $row_total + $data['wed'];
                                                $col_wed = $col_wed + $data['wed'];
                                            } else {
                                                echo 0;
                                            } ?>"></td>
                                    <td><input type="number" class="form-control"
                                            name="thu_<?php echo $data['projectid']; ?>"
                                            value="<?php if (isset($data['thu'])) {
                                                echo $data['thu'];
                                                $row_total = $row_total + $data['thu'];
                                                $col_thu = $col_thu + $data['thu'];
                                            } else {
                                                echo 0;
                                            } ?>"></td>
                                    <td><input type="number" class="form-control"
                                            name="fri_<?php echo $data['projectid']; ?>"
                                            value="<?php if (isset($data['fri'])) {
                                                echo $data['fri'];
                                                $row_total = $row_total + $data['fri'];
                                                $col_fri = $col_fri + $data['fri'];
                                            } else {
                                                echo 0;
                                            } ?>"></td>
                                    <td><?php echo $row_total; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <th>TOTAL</th>
                                <th colspan="2"></th>
                                <th><?php echo $col_mon; ?></th>
                                <th><?php echo $col_tue; ?></th>
                                <th><?php echo $col_wed; ?></th>
                                <th><?php echo $col_thu; ?></th>
                                <th><?php echo $col_fri; ?></th>
                                <th></th>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-lg-4">
                        <div class="text-sm-end  mt-3">
                            <input type="hidden" name="week" value="<?php echo $week; ?>">
                            <input type="hidden" name="skill_val" value="<?php echo $skill_val; ?>">
                            <input type="hidden" name="total_count" value="<?php echo $j; ?>">
                            <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                Save Data
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Employee</td>
                            <td>Allocation</td>
                            <td>Proficiency</td>
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
                                <td><?php echo $data['allocation']; ?> %</td>
                                <td><?php switch ($data['proficiency']) {
                                    case 1:
                                        echo 'Beginner/Novice';
                                        break;
                                    case 2:
                                        echo 'Intermediate';
                                        break;
                                    case 3:
                                        echo 'Advanced';
                                        break;
                                    case 4:
                                        echo 'Expert/Mastery';
                                        break;
                                    default:
                                        echo 'Unknown';
                                        break;
                                }
                                ; ?>


                                </td>
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