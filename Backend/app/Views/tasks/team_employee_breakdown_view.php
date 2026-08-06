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
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Task/Task_manage/team_tasks') ?>">Team Tasks</a></li>
                </ol>
            </div>
            <h4 class="page-title">Employee Task Breakdown</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table  table-sm table-striped">
                    <thead>
                        <tr>
                            <th>UCN</th>
                            <th>Project</th>
                            <th>Skill</th>
                            <th>Stage</th>
                            <th>Date</th>
                            <th>Effort (Hr)</th>
                            <th>Effort (Min)</th>
                            <th>Remarks</th>
                            <th>Save</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($active_tasks as $index => $task) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $task['ucn_id'] ?></td>
                                <td><?php echo $task['projectname'] ?></td>
                                <td><?php echo $skills[$task['skill_id']];
                                    ?></td>
                                <td><?php $stage = $task['stage'];
                                    switch ($stage) {
                                        case 1:
                                            echo 'Alpha';
                                            break;
                                        case 2:
                                            echo 'Beta';
                                            break;
                                        case 5:
                                            echo 'Gamma';
                                            break;
                                        case 0:
                                            echo 'Gen';
                                            break;
                                    }
                                    ?></td>
                                <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/employee_add_effort') ?>" method="POST"><?= csrf_field() ?>
                                    <td>
                                        <input id="start_date_<?php echo $index ?>" name="start_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                                        <?php if ($index === 0): // only add script once 
                                        ?>
                                            <script>
                                                function timeFunctionLong(input) {
                                                    setTimeout(function() {
                                                        input.type = 'text';
                                                    }, 60000);
                                                }

                                                window.onload = function() {
                                                    const inputs = document.querySelectorAll('input[id^="start_date_"]');
                                                    const today = new Date();
                                                    let targetMonth, targetYear;

                                                    if (today.getDate() <= 5) {
                                                        // Use last month
                                                        targetMonth = today.getMonth() - 1;
                                                        targetYear = today.getFullYear();

                                                        if (targetMonth < 0) {
                                                            targetMonth = 11;
                                                            targetYear -= 1;
                                                        }
                                                    } else {
                                                        // Use current month
                                                        targetMonth = today.getMonth();
                                                        targetYear = today.getFullYear();
                                                    }

                                                    const firstDay = new Date(targetYear, targetMonth, 1);
                                                    const lastDay = new Date(targetYear, targetMonth + 1, 0);

                                                    const formatDate = (date) =>
                                                        `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;

                                                    inputs.forEach(input => {
                                                        input.min = formatDate(firstDay);
                                                        input.max = formatDate(lastDay);
                                                    });
                                                };
                                            </script>
                                        <?php endif; ?>

                                    </td>
                                    <td>
                                        <select name="hrs_val" class="form-control">
                                            <?php
                                            for ($x = 0; $x <= 24; $x++) {
                                                echo '<option value="' . $x . '">' . $x . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>

                                        <select name="min_val" class="form-control">
                                            <option value=".0">00</option>
                                            <option value=".25">15</option>
                                            <option value=".5">30</option>
                                            <option value=".75">45</option>
                                        </select>

                                    </td>
                                    <td><input type="text" class="form-control" name="remarks" value=""></td>
                                    <td>
                                        <input type="hidden" name="ucn_tl_id" value="<?php echo $task['ucn_tl_id']; ?>">
                                        <input type="hidden" name="stage" value="<?php echo $task['stage']; ?>">
                                        <input type="hidden" name="project_id" value="<?php echo $task['project_id']; ?>">
                                        <input type="hidden" name="ucn_id" value="<?php echo $task['ucn_id']; ?>">
                                        <input type="hidden" name="skill_Id" value="<?php echo $task['skill_id']; ?>">
                                        <input type="hidden" name="ucn_mst_id" value="<?php echo $task['ucn_mst_id']; ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $temp_user; ?>">
                                        <input type="hidden" name="return_url" value="1">
                                        <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                            Save
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div><!-- /.row -->
    </div><!-- /.row -->
</div><!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <table class="table">
                    <thead>
                        <tr>
                            <td>UCN</td>
                            <td>Project</td>
                            <td>Skill</td>
                            <td>Stage</td>
                            <td>Date</td>
                            <td>Effort</td>
                            <td>Remarks</td>
                            <td>Del</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 0;
                        // print_r($user_effort);
                        // exit();
                        if ($user_effort) {
                            foreach ($user_effort as $data) {
                                $sno++;
                        ?>
                                <tr>
                                    <td><?php echo $data['ucn_id'] ?></td>
                                    <td><?php echo $data['projectname']; ?></td>
                                    <td><?php echo $skills[$data['skill_id']];
                                        ?></td>
                                    <td><?php $stage = $data['stage'];
                                        switch ($stage) {
                                            case 1:
                                                echo 'Alpha';
                                                break;
                                            case 2:
                                                echo 'Beta';
                                                break;
                                            case 5:
                                                echo 'Gamma';
                                                break;
                                            case 0:
                                                echo 'Gen';
                                                break;
                                        }
                                        ?></td>
                                    <td><?php echo $data['date_value']; ?></td>
                                    <td><?php echo $data['effort']; ?></td>
                                    <td><?php echo $data['remarks']; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/delete_effort') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ucn_emp_id" value="<?php echo $data['ucn_emp_id']; ?>">
                                            <input type="hidden" name="return_url" value="1">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
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