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
                Create New ATS Request
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/add_new_ats_request'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="month">Resource Type</label>
                            <select class="form-select" name="resource_type">
                                <?php
                                foreach ($roles as $role_type) {
                                ?>
                                    <option value="<?php echo $role_type['value']; ?>"><?php echo $role_type['name']; ?></option>
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
                                for ($i = 0; $i <= 20; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="year">Maximum Experience</label>
                            <select class="form-select" name='max_experience'>
                                <?php
                                $endyear = date('Y');
                                for ($i = 0; $i <= 20; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="year">Type of Hire</label>
                            <select class="form-select" name='type_of_position'>
                                <option value="1">Permanent</option>
                                <option value="2">Contract</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <label for="month">Requirement</label>
                                <textarea class="ckeditor" name="requirement_details" required></textarea>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label for="month">Job Description</label>
                                <textarea class="ckeditor" name="job_description" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-info btn-xs waves-effect waves-light" value="Create Request">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>