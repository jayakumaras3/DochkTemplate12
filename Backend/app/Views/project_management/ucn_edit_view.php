<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if (isset($return_page) && $return_page == 2) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Etrack/Claims/Status'); ?>">Project Status</a></li>
                    <?php } elseif (isset($return_page) && $return_page == 3) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_list/client_status'); ?>">Client Status</a></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn'); ?>">My UCN</a></li>
                    <?php } ?>
                </ol>
            </div>
            <h4 class="page-title">Edit UCN</h4>
        </div>
    </div>
</div>

<form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/update_ucn_data') ?>" method="POST"><?= csrf_field() ?>
    <?php
    $ucn_list = $ucn_list ?? [['name' => '', 'client' => '', 'account_manager' => '', 'start_dt' => '', 'end_dt' => '', 'status' => '', 'remarks' => '', 'scope' => '', 'ucn_id' => '']];
    $clientlist = $clientlist ?? [];
    $salesuser = $salesuser ?? [];
    ?>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="mb-1">
                                    <label for="purchase_order_id" class="form-label">Remarks</label>
                                    <textarea class="ckeditor" name="remarks"><?php echo $ucn_list[0]['remarks']; ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12">
                                    <input type="hidden" name="ucn_id" value="<?php echo $ucn_list[0]['ucn_id']; ?>">
                                    <input type="hidden" name="return_page" value="<?php echo $return_page ?? ''; ?>"">
                                <div class=" text-sm-end mt-sm-0">
                                    <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                        Update UCN
                                    </button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="ucn_name" value="<?php echo $ucn_list[0]['name']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">* <a href="<?php echo base_url('User_login/client_list/my_client_list') ?>">Click here to Add New Client</a></span></label>
                                <select name="client" class="form-control">
                                    <?php foreach ($clientlist as $client) { ?>
                                        <option value="<?php echo $client['id_c'] ?>"
                                            <?php
                                            if ($client['id_c'] == $ucn_list[0]['client']) {
                                                echo 'selected';
                                            }
                                            ?>><?php echo $client['client_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class=" mb-1">
                                <label for="purchase_order_id" class="form-label">Account Manager <span class="text-danger">*</span></label>
                                <select name="account_manager" class="form-control">
                                    <!-- <option value="0">Select Account Manager</option> -->
                                    <?php foreach ($salesuser as $sales) { ?>
                                        <option value="<?php echo $sales['id_user'] ?>"
                                            <?php
                                            if ($sales['id_user'] == $ucn_list[0]['account_manager']) {
                                                echo 'selected';
                                            }
                                            ?>><?php echo $sales['fullname'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="purchase_order_id" class="form-label">Start Date</label>
                                <input id="start_date" name="start_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $ucn_list[0]['start_dt']; ?>">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="purchase_order_id" class="form-label">End Date</label>
                                <input id="end_date" name="end_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $ucn_list[0]['end_dt']; ?>">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="purchase_order_id" class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php if ($ucn_list[0]['status'] == 1) {
                                                            echo 'selected';
                                                        } ?>>Active</option>
                                    <option value="4" <?php if ($ucn_list[0]['status'] == 4) {
                                                            echo 'selected';
                                                        } ?>>On Hold</option>
                                    <option value="3" <?php if ($ucn_list[0]['status'] == 3) {
                                                            echo 'selected';
                                                        } ?>>Cancelled</option>
                                    <option value="5" <?php if ($ucn_list[0]['status'] == 5) {
                                                            echo 'selected';
                                                        } ?>>Delayed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="purchase_order_id" class="form-label">Scope</label>
                                <textarea class="ckeditor" name="scope"><?php echo $ucn_list[0]['scope']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>