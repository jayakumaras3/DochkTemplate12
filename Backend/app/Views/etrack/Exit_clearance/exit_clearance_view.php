<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">

            </div>
            <h4 class="page-title">Exit Clearance</h4>
        </div>
    </div>
</div>
<?php //print_r($getUserlatestCourse); 


?>
<?php if ('1115' == session()->get('id_user') || '1' == session()->get('id_user')) { ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form class="row" action="<?php echo base_url('Etrack/exit_clearance/updateLwd') ?>" method="POST"
                        id="submitForm"><?= csrf_field() ?>
                        <?php echo '<input type="hidden" name="scenario" value="0">'; ?>
                        <div class="col-xl-3 col-md-3   mt-1">
                            <label>Select Users</label>
                            <select class="form-select" name="user_id" required>
                                <option value="">-- Select User --</option>
                                <?php
                                foreach ($all_users as $users) {

                                    // $key = array_search($users['id_user'], array_column($getUserlatestCourse, 'id_user'));
                                    // if (!empty($key) || $key === 0) {
                                    // } else {
                                    echo '<option value="' . $users['id_user'] . '|' . $users['name'] . ' ' . $users['last_name'] . '|' . $users['email'] . '|' . $users['manager'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';

                                    // }
                                } ?>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-3  mt-1">
                            <label>Last Working Date</label>
                            <input class="form-control" id="LWD" name="LWD" type="date" required>
                        </div>
                        <div class="col-xl-3 col-md-3  mt-3">
                            <button type="submit" class="btn btn-soft-primary btn-block btn-sm waves-effect waves-light"
                                id="submitButton">
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100"> -->
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Emp</th>
                            <th>Employee</th>
                            <th>Designation</th>
                            <th>Date</th>
                            <th>Mng</th>
                            <th>IT</th>
                            <th>Fac</th>
                            <th>Fin</th>
                            <th>HR</th>
                            <th>Details</th>
                            <th>EI</th>

                            <?php if ('1' == session()->get('id_user') || '1115' == session()->get('id_user')) { ?>

                                <th>DNLD</th>
                                <th>DNLD EI</th>
                            <?php } ?>

                        </tr>
                    </thead>
                    <tbody>

                        <?php $j = 0;
             
                        foreach ($exitUsers as $users) {
                            if (
                                $users['id_user'] == session()->get('id_user') || $users['manager'] == session()->get('id_user') || '1103' == session()->get('id_user') ||
                                '1115' == session()->get('id_user') || '1202' == session()->get('id_user') || '1115' == session()->get('id_user')
                            ) {
                                $j = $j + 1;
                                
                                  
                                ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $users['emp_id']; ?></td>
                                    <td><?php echo $users['name'] . ' ' . $users['last_name']; ?></td>
                                    <td><?php echo $users['designation']; ?></td>
                                    <td><?php echo $users['LWD']; ?></td>
                                    <td><?php $status_found = false;
                                    $fk_header_id = explode(',', $users['fk_header_id']);
                                    $ecstatus = explode(',', $users['ecstatus']);

                                    $key = array_search('1', $fk_header_id);

                                    if ($key !== false) {  // if '1' found in fk_header_id
                                        $status_found = true;

                                        switch ($ecstatus[$key]) {  // use $key to get matching status
                                            case 3:
                                                echo 'INT';
                                                break;
                                            case 2:
                                                echo 'APP';
                                                break;
                                            case 1:
                                                echo 'REJ';
                                                break;
                                            case 0:
                                                echo 'DEL';
                                                break;
                                            default:
                                                echo 'Unknown status';
                                                break;
                                        }
                                    } else {
                                        // '1' not found in fk_header_id
                                    }

                                    if (!$status_found) {
                                        echo '';
                                    } ?></td>
                                    <td><?php $status_found = false;
                                    $fk_header_id = explode(',', $users['fk_header_id']);
                                    $ecstatus = explode(',', $users['ecstatus']);

                                    $key = array_search('2', $fk_header_id);

                                    if ($key !== false) {  // if '1' found in fk_header_id
                                        $status_found = true;

                                        switch ($ecstatus[$key]) {  // use $key to get matching status
                                            case 3:
                                                echo 'INT';
                                                break;
                                            case 2:
                                                echo 'APP';
                                                break;
                                            case 1:
                                                echo 'REJ';
                                                break;
                                            case 0:
                                                echo 'DEL';
                                                break;
                                            default:
                                                echo '';
                                                break;
                                        }
                                    } else {
                                        // '1' not found in fk_header_id
                                    }

                                    if (!$status_found) {
                                        echo '';
                                    } ?></td>
                                    <td><?php $status_found = false;
                                    $fk_header_id = explode(',', $users['fk_header_id']);
                                    $ecstatus = explode(',', $users['ecstatus']);

                                    $key = array_search('3', $fk_header_id);
                                   // echo $ecstatus[$key].'-'.$key.'-'.'3';

                                    if ($key !== false) {  // if '1' found in fk_header_id
                                        $status_found = true;

                                        switch ($ecstatus[$key]) {  // use $key to get matching status
                                            case 3:
                                                echo 'INT';
                                                break;
                                            case 2:
                                                echo 'APP';
                                                break;
                                            case 1:
                                                echo 'REJ';
                                                break;
                                            case 0:
                                                echo 'DEL';
                                                break;
                                            default:
                                                echo '';
                                                break;
                                        }
                                    } else {
                                        // '1' not found in fk_header_id
                                    }

                                    if (!$status_found) {
                                        echo '';
                                    } ?></td>
                                    <td><?php $status_found = false;
                                    $fk_header_id = explode(',', $users['fk_header_id']);
                                    $ecstatus = explode(',', $users['ecstatus']);

                                    $key = array_search('4', $fk_header_id);

                                    if ($key !== false) {  // if '1' found in fk_header_id
                                        $status_found = true;

                                        switch ($ecstatus[$key]) {  // use $key to get matching status
                                            case 3:
                                                echo 'INT';
                                                break;
                                            case 2:
                                                echo 'APP';
                                                break;
                                            case 1:
                                                echo 'REJ';
                                                break;
                                            case 0:
                                                echo 'DEL';
                                                break;
                                            default:
                                                echo 'Unknown status';
                                                break;
                                        }
                                    } else {
                                        // '1' not found in fk_header_id
                                    }

                                    if (!$status_found) {
                                        echo '';
                                    } ?></td>
                                    <td><?php $status_found = false;
                                    $fk_header_id = explode(',', $users['fk_header_id']);
                                    $ecstatus = explode(',', $users['ecstatus']);

                                    $key = array_search('5', $fk_header_id);

                                    if ($key !== false) {  // if '1' found in fk_header_id
                                        $status_found = true;

                                        switch ($ecstatus[$key]) {  // use $key to get matching status
                                            case 3:
                                                echo 'INT';
                                                break;
                                            case 2:
                                                echo 'APP';
                                                break;
                                            case 1:
                                                echo 'REJ';
                                                break;
                                            case 0:
                                                echo 'DEL';
                                                break;
                                            default:
                                                echo 'Unknown status';
                                                break;
                                        }
                                    } else {
                                        // '1' not found in fk_header_id
                                    }

                                    if (!$status_found) {
                                        echo '';
                                    } ?></td>

                                    <td>
                                        <form
                                            action="<?php echo base_url(relativePath: 'Etrack/exit_clearance/exit_clearance_form') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="user_id" value="<?php echo $users['id_user'] ?>">
                                            <input type="hidden" name="manager" value="<?php echo $users['manager'] ?>">
                                            <button type="submit" title="Exit Clearance"
                                                class="btn btn-outline-danger waves-effect btn-xs waves-light mb-3"> View
                                            </button>
                                        </form>
                                    </td>
                                    <?php if ($users['id_user'] == session()->get('id_user') || '1115' == session()->get('id_user')) { ?>
                                        <td>
                                            <form action="<?php echo base_url('Etrack/exit_clearance/exit_interview_view') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="user_id" value="<?php echo $users['id_user'] ?>">

                                                <button type="submit" title="Exit Interview"
                                                    class="btn btn-outline-warning waves-effect btn-xs waves-light mb-3"> EI
                                                </button>
                                            </form>
                                        </td>
                                    <?php }else{ echo '<td></td>'; } ?>
                                    <?php if ('1' == session()->get('id_user') || '1115' == session()->get('id_user') || '834' == session()->get('id_user')) { ?>

                                        <td>
                                            <form action="<?php echo base_url('Etrack/exit_clearance/exit_clearance_pdf') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="user_id" value="<?php echo $users['id_user'] ?>">
                                                <input type="hidden" name="emp_id" value="<?php echo $users['emp_id'] ?>">
                                                <input type="hidden" name="username"
                                                    value="<?php echo $users['name'] . ' ' . $users['last_name']; ?>">
                                                <button type="submit" title="Download Exit Clearance"
                                                    class="btn btn-outline-success waves-effect btn-xs waves-light mb-3"> DNLD
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="<?php echo base_url('Etrack/exit_clearance/exit_interview_pdf') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="user_id" value="<?php echo $users['id_user'] ?>">
                                                <input type="hidden" name="emp_id" value="<?php echo $users['emp_id'] ?>">
                                                <input type="hidden" name="username"
                                                    value="<?php echo $users['name'] . ' ' . $users['last_name']; ?>">
                                                <button type="submit" title="Download Exit Interview"
                                                    class="btn btn-outline-info waves-effect btn-xs waves-light mb-3"> DNLD EI
                                                </button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php }
                        } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>