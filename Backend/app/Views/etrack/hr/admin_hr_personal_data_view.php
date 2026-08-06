<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/personal'); ?>">
                            Admin HR Personal Data
                        </a>
                    </li>
                     <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/personal_documents'); ?>">
                            Documents
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                User Personal Data - (<?php echo $username[0]['name'].' '.$username[0]['last_name']; ?>)
            </h4>
        </div>
    </div>
</div> 
<div class="row">
    <div class="col-xl-12 col-md-12">
        <!-- Portlet card -->
        <?php    if (count($user_data)>0) {  
        ?>
            <div class="card">
                 <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/update_profile_data'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Date of Birth</label>
                                <div class="col-12">
                                    <input id="start_date" name="DOB" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $user_data[0]['DOB']; ?>">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Personal Email</label>
                                <div class="col-12">
                                    <input type="text" name="personal_mail" class="form-control" required value="<?php echo $user_data[0]['personal_mail']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Personal Mobile No.</label>
                                <div class="col-12">
                                    <input type="text" name="personal_phone" class="form-control" required value="<?php echo $user_data[0]['personal_phone']; ?>">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Currrent Address</label>
                                <div class="col-12">
                                    <textarea name='current_addresss' class="form-control"><?php echo $user_data[0]['current_addresss']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Permanent Address</label>
                                <div class="col-12">
                                    <textarea name='permanent_address' class="form-control"><?php echo $user_data[0]['permanent_address']; ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Alternate Personal Mobile No.</label>
                                <div class="col-12">
                                    <input type="text" name="home_phone" class="form-control" required value="<?php echo $user_data[0]['home_phone']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Emergency Mobile No.</label>
                                <div class="col-12">
                                    <input type="text" name="emergency_phone" class="form-control" required value="<?php echo $user_data[0]['emergency_phone']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Emergency Contact Person</label>
                                <div class="col-12">
                                    <input type="text" name="emergency_contact" class="form-control" required value="<?php echo $user_data[0]['emergency_contact']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Emergency Contact Person Relation</label>
                                <div class="col-12">
                                    <input type="text" name="emergency_relation" class="form-control" required value="<?php echo $user_data[0]['emergency_relation']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">PAN Number</label>
                                <div class="col-12">
                                    <input type="text" name="PAN" class="form-control" required value="<?php echo $user_data[0]['PAN']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Date of Joining</label>
                                <div class="col-12">
                                    <input id="start_date" name="DOJ" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $username[0]['DOJ']; ?>">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Last Working Day</label>
                                <div class="col-12">
                                    <input id="start_date" name="LWD" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $username[0]['LWD']; ?>">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Bank</label>
                                <div class="col-12">
                                    <input type="text" name="bank" class="form-control" required value="<?php echo $user_data[0]['bank']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Bank A/C Nummber</label>
                                <div class="col-12">
                                    <input type="text" name="bank_account_num" class="form-control" required value="<?php echo $user_data[0]['bank_account_num']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Blood Group</label>
                                <div class="col-12">
                                    <input type="text" name="blood_group" class="form-control" required value="<?php echo $user_data[0]['blood_group']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Gender</label>
                                <div class="col-12">
                                    <select class="form-select" name="gender">
                                        <option value="1" <?php if ($username[0]['gender'] == 1) echo 'Selected'; ?>>Female</option>
                                        <option value="2" <?php if ($username[0]['gender'] == 2) echo 'Selected'; ?>>Male</option>
                                        <option value="3" <?php if ($username[0]['gender'] == 3) echo 'Selected'; ?>>Not to Disclose</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Martial status</label>
                                <div class="col-12">
                                    <select class="form-select" name="martial">
                                        <option value="1" <?php if ($user_data[0]['martial'] == 1) echo 'Selected'; ?>>Single</option>
                                        <option value="2" <?php if ($user_data[0]['martial'] == 2) echo 'Selected'; ?>>Married</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Type of Engagement</label>
                                <div class="col-12">
                                    <select class="form-select" name="engage_type">
                                        <option value="1" <?php if ($username[0]['engage_type'] == 1) echo 'Selected'; ?>>Salaried</option>
                                        <option value="2" <?php if ($username[0]['engage_type'] == 2) echo 'Selected'; ?>>Contract</option>
                                        <option value="3" <?php if ($username[0]['engage_type'] == 3) echo 'Selected'; ?>>Trainee</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Designation</label>
                                <div class="col-12">
                                    <input type="text" name="designation" class="form-control" required value="<?php echo $username[0]['designation']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Level</label>
                                <div class="col-12">
                                    <select class="form-select" name="level">
                                        <?php
                                        foreach ($level as $lev) {
                                            echo '<option value="' . $lev['value'] . '"';
                                            if ($lev['value'] == $username[0]['level']) {
                                                echo ' SELECTED ';
                                            }
                                            echo  '>' . $lev['name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Department</label>
                                <div class="col-12">
                                    <select class="form-select" name="department">
                                        <?php
                                        foreach ($department as $dep) {
                                            echo '<option value="' . $dep['value'] . '"';
                                            if ($dep['value'] == $username[0]['department']) {
                                                echo ' SELECTED ';
                                            }
                                            echo  '>' . $dep['name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4  mb-2">
                                <label for="inputEmail3" class="col-12 col-form-label">Region</label>
                                <div class="col-12">
                                    <select class="form-select" name="region">
                                        <option value="1">India</option>
                                        <option value="2">US</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="justify-content-end row">
                                <div class="col-12">
                                    <input type="hidden" name='id_user' value="<?php echo $username[0]['id_user']; ?>">
                                    <input type="hidden" name='upd_id' value="<?php echo $user_data[0]['upd_id']; ?>">
                                    <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                        Update Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php }else{ echo "No Data Available."; } ?>
    </div> <!-- end col-->

</div>