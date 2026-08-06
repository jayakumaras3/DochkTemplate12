<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
if (!in_array('4154', $arrayuserlevel)) {
    header('Location:' . base_url('my_training'));
    exit();
}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/ITSupport/view_assets'); ?>">
                            IT Asset Details
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                IT Asset Edit
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/assign_user_to_asset'); ?>"
                    method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">User</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select " name="user_select" required="">
                                <?php foreach ($all_users as $users) {
                                    echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Assigned Date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="assigned_on" class="date-picker form-control"
                                placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'"
                                onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)"
                                value="" required>
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function () {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Expected Return</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="expected_return" class="date-picker form-control"
                                placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'"
                                onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)"
                                value="" required>
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function () {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Remarks</label>
                        <div class="col-8 col-xl-9">
                            <textarea class="form-control" name="remarks"></textarea>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" name='et_ass_det_id'
                                value='<?php echo $get_asset_details_edit[0]['et_ass_det_id']; ?>'>
                            <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light"
                                id="submitButton">
                                Assign Asset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/update_asset_details'); ?>"
                    method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-1">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Identifier</label>
                        <div class="col-12 col-xl-12">
                            <input type="text" name="identifier" class="form-control" required
                                value="<?php echo $get_asset_details_edit[0]['fin_identifier'] ?>">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Description</label>
                        <div class="col-12 col-xl-12">
                            <input type="text" name="description" class="form-control" required
                                value="<?php echo $get_asset_details_edit[0]['description'] ?>">
                        </div>
                    </div>

                    <div class="row mb-1">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Department</label>
                        <div class="col-12 col-xl-12">
                            <select name="department" class="form-control">
                                <option value="1" <?php if ($get_asset_details_edit[0]['department'] == 1)
                                    echo 'Selected'; ?>>Learning</option>
                                <option value="2" <?php if ($get_asset_details_edit[0]['department'] == 2)
                                    echo 'Selected'; ?>>US</option>
                                <option value="3" <?php if ($get_asset_details_edit[0]['department'] == 3)
                                    echo 'Selected'; ?>>Finance</option>
                                <option value="4" <?php if ($get_asset_details_edit[0]['department'] == 4)
                                    echo 'Selected'; ?>>HR</option>
                                <option value="5" <?php if ($get_asset_details_edit[0]['department'] == 5)
                                    echo 'Selected'; ?>>Others</option>
                                <option value="6" <?php if ($get_asset_details_edit[0]['department'] == 6)
                                    echo 'Selected'; ?>>Pool</option>
                                <option value="7" <?php if ($get_asset_details_edit[0]['department'] == 7)
                                    echo 'Selected'; ?>>IT Store</option>
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" name='et_ass_det_id'
                                value='<?php echo $get_asset_details_edit[0]['et_ass_det_id']; ?>'>
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light"
                                id="submitButton">
                                Update Asset Information
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="searchdatatable" class="table table-bordered table-striped ">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Assigned To</th>
                            <th>Assigned On</th>
                            <th>Returned On</th>
                            <th>Expected Return</th>
                            <th>Remarks</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($get_asset_history) {
                            $j = 0;
                            foreach ($get_asset_history as $history) {
                                $j++;
                                ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $history['name']; ?></td>
                                    <td><?php echo $history['assigned_on']; ?></td>
                                    <td><?php echo $history['returned_on']; ?></td>
                                    <td><?php echo $history['expected_return_on']; ?></td>
                                    <td><?php echo $history['remarks']; ?></td>
                                    <td>
                                        <form class="form-horizontal"
                                            action="<?php echo base_url('etrack/ITSupport/edit_assets_history'); ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="et_assets_assign_id"
                                                value="<?php echo $history['et_assets_assign_id']; ?>">
                                            <button class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
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