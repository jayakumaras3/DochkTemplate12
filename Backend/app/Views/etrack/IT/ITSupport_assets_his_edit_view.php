<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
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
                <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/update_user_assign_asset'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Assigned Date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="assigned_on" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $get_history_byID[0]['assigned_on']; ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Expected Return</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="expected_return" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $get_history_byID[0]['expected_return_on']; ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Return Date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="returned_on" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $get_history_byID[0]['returned_on']; ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Remarks</label>
                        <div class="col-8 col-xl-9">
                            <textarea class="form-control" name="remarks"><?php echo $get_history_byID[0]['remarks']; ?></textarea>
                        </div>
                        <span style="color: red;">Remarks will overright. You can add to the remarks, please dont delete.</span>
                    </div>
                    
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" name='et_assets_assign_id' value='<?php echo $get_history_byID[0]['et_assets_assign_id']; ?>'>
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                Update Assignment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
</div>