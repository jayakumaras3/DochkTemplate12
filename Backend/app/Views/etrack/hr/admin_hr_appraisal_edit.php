<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if ($return_page == 2) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/view_appraisal_data'); ?>">
                                Appraisal Data
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($return_page == 3) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/view_all_appraisals'); ?>">
                                Appraisal Data By Date
                            </a>
                        </li>
                    <?php } ?>
                </ol>
            </div>
            <h4 class="page-title">
                Appraisal Data Edit
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/update_appraisal'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Effective Date</label>
                            <div class="col-12 col-xl-12">
                                <input id="start_date" name="effectivedate" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $appraisals_data[0]['effectivedate']; ?>">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Description</label>
                            <div class="col-12 col-xl-12">
                                <input type="text" name="description" class="form-control" required value="<?php echo $appraisals_data[0]['description']; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Designation</label>
                            <div class="col-12 col-xl-12">
                                <input type="text" name="designation" class="form-control" required value="<?php echo $appraisals_data[0]['designation']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">New CTC</label>
                            <div class="col-12 col-xl-12">
                                <input type="number" name="yearly" class="form-control" required value="<?php echo $appraisals_data[0]['yearly']; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Type</label>
                            <div class="col-12 col-xl-12">
                                <select name="type_of_engagement" class="form-control">
                                    <option value="1" <?php if ($appraisals_data[0]['type_of_engagement'] == 1) echo 'selected'; ?>>Permanent</option>
                                    <option value="2" <?php if ($appraisals_data[0]['type_of_engagement'] == 2) echo 'selected'; ?>>Contract</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Template</label>
                            <div class="col-12 col-xl-12">
                                <select name="template" class="form-control">
                                    <option value="1" <?php if ($appraisals_data[0]['template'] == 1) echo 'selected'; ?>>TalentQuest</option>
                                    <option value="2" <?php if ($appraisals_data[0]['template'] == 2) echo 'selected'; ?>>Touchstone</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Staus</label>
                            <div class="col-12 col-xl-12">
                                <select name="status" class="form-control">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Delete</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Type of Revision</label>
                            <div class="col-12 col-xl-12">
                                <select name="type_of_appraisal" class="form-control">
                                    <option value="1" <?php if ($appraisals_data[0]['type_of_app'] == 1) echo 'selected'; ?>>Salary Revision</option>
                                    <option value="2" <?php if ($appraisals_data[0]['type_of_app'] == 2) echo 'selected'; ?>>Designation and Salary</option>
                                    <option value="3" <?php if ($appraisals_data[0]['type_of_app'] == 3) echo 'selected'; ?>>Only Designation</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-12 col-xl-12">
                            <input type="hidden" name='salid' value="<?php echo $appraisals_data[0]['salid']; ?>">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Update Appraisal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>