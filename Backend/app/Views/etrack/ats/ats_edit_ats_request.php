<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/ATS'); ?>">
                            ATS Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Edit ATS Request
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/update_ats_details'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="month">Resource Type</label>
                            <select class="form-select" name="resource_type">
                                <?php
                                foreach ($roles as $role_type) {
                                ?>
                                    <option value="<?php echo $role_type['value']; ?>"
                                        <?php if ($ats_details[0]['resource_type'] == $role_type['value']) {
                                            echo 'Selected';
                                        } ?>><?php echo $role_type['name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="year">Minimum Experience</label>
                            <select class="form-select" name='min_experience'>
                                <?php
                                $endyear = date('Y');
                                for ($i = 1; $i <= 20; $i++) {
                                    echo "<option value='$i'";
                                    if ($ats_details[0]['min_experience'] == $i) {
                                        echo 'Selected';
                                    }
                                    echo   ">$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="year">Maximum Experience</label>
                            <select class="form-select" name='max_experience'>
                                <?php
                                $endyear = date('Y');
                                for ($i = 1; $i <= 20; $i++) {
                                    echo "<option value='$i'";
                                    if ($ats_details[0]['max_experience'] == $i) {
                                        echo 'Selected';
                                    }
                                    echo   ">$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="year">Type of Hire</label>
                            <select class="form-select" name='type_of_position'>
                                <option value="1" <?php if ($ats_details[0]['type_of_position'] == 1) {
                                                        echo 'Selected';
                                                    } ?>>Permanent</option>
                                <option value="2" <?php if ($ats_details[0]['type_of_position'] == 2) {
                                                        echo 'Selected';
                                                    } ?>>Contract</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <label for="month">Requirement</label>
                                <textarea class="ckeditor" name="requirement_details"><?php echo $ats_details[0]['requirement_details']; ?></textarea>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label for="month">Job Description</label>
                                <textarea class="ckeditor" name="job_description"><?php echo $ats_details[0]['job_description']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <input type="hidden" name="ats_id" value="<?php echo $ats_details[0]['ats_id']; ?>">
                            <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-info btn-xs waves-effect waves-light" value="Update Request">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>