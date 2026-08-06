<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('profile'); ?>">
                            Profile
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">My Personal Data</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <?php if ($personal_data) { ?>
                    <form class="form-horizontal" action="<?php echo base_url('User_login/profile/update_profile_data_personal'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Date of Birth</label>
                            <div class="col-6 col-xl-6">
                                <input id="start_date" name="DOB" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $personal_data[0]['DOB']; ?>">
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
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Personal Email</label>
                            <div class="col-6 col-xl-6">
                                <input type="text" name="personal_mail" class="form-control" required value="<?php echo $personal_data[0]['personal_mail']; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Currrent Address</label>
                            <div class="col-6 col-xl-6">
                                <textarea name='current_addresss' class="form-control"><?php echo $personal_data[0]['current_addresss']; ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Permanent Address</label>
                            <div class="col-6 col-xl-6">
                                <textarea name='permanent_address' class="form-control"><?php echo $personal_data[0]['permanent_address']; ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Personal Mobile No.</label>
                            <div class="col-6 col-xl-6">
                                <input type="text" name="personal_phone" class="form-control" required value="<?php echo $personal_data[0]['personal_phone']; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Alternate Personal Mobile No.</label>
                            <div class="col-6 col-xl-6">
                                <input type="text" name="home_phone" class="form-control" required value="<?php echo $personal_data[0]['home_phone']; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Emergency Mobile No.</label>
                            <div class="col-6 col-xl-6">
                                <input type="text" name="emergency_phone" class="form-control" required value="<?php echo $personal_data[0]['emergency_phone']; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Emergency Contact Person</label>
                            <div class="col-6 col-xl-6">
                                <input type="text" name="emergency_contact" class="form-control" required value="<?php echo $personal_data[0]['emergency_contact']; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Emergency Contact Person Relation</label>
                            <div class="col-6 col-xl-6">
                                <input type="text" name="emergency_relation" class="form-control" required value="<?php echo $personal_data[0]['emergency_relation']; ?>">
                            </div>
                        </div>

                        <div class="justify-content-end row">
                            <div class="col-6 col-xl-6">
                                <input type="hidden" name='PAN' value="<?php echo $personal_data[0]['PAN']; ?>">
                                <input type="hidden" name='upd_id' value="<?php echo $personal_data[0]['upd_id']; ?>">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                    Update Data
                                </button>
                            </div>
                        </div>
                    </form>
                <?php }else{ echo "No Record Found. Please contact HR."; } ?>
            </div>
        </div>
    </div> <!-- end col-->
    
</div>