<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url() . "Etrack/exit_clearance" ?>">
                            Exit Clearance</a></li>
                </ol>
            </div>
            <h4 class="page-title">Exit Interview Form</h4>
        </div>
    </div>
</div>
<div class="row">

    <div class="card">
        <div class="card-body">
            <form action=" <?php echo base_url('Etrack/exit_clearance/addUpdateExitInterview'); ?>" method="post"
                id="submitForm"><?= csrf_field() ?>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">Mobile Number<span class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="tel" class="form-control" name="mobile_number" pattern="[0-9]{10}" maxlength="10"
                            minlength="10" title="Enter a valid 10-digit mobile number"
                            value="<?php echo isset($userexitInterdata[0]['mobile_number']) ? $userexitInterdata[0]['mobile_number'] : '' ?>"
                            required oninput="validatemobileInput(this)" />
                        <div id="errorPMsg" class="text-danger"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">Personal Email id<span
                            class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="email" class="form-control" name="personal_email_id"
                            placeholder="example@domain.com" oninput="validateEmail(this)"
                            value="<?php echo isset($userexitInterdata[0]['personal_email_id']) ? $userexitInterdata[0]['personal_email_id'] : '' ?>"
                            required />
                        <div id="erroreMsg" class="text-danger" aria-live="polite"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">Why did you begin looking for a
                        new job? <span class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="text" class="form-control" name="why_new_job"
                            value="<?php echo isset($userexitInterdata[0]['why_new_job']) ? $userexitInterdata[0]['why_new_job'] : '' ?>"
                            required />

                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">How would you describe the
                        culture of our company? <span class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="text" class="form-control" name="culture_of_our_comany"
                            value="<?php echo isset($userexitInterdata[0]['culture_of_our_comany']) ? $userexitInterdata[0]['culture_of_our_comany'] : '' ?>"
                            required />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">What could have been done for you
                        to remain employed here?<span class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="text" class="form-control" name="remain_employed_here"
                            value="<?php echo isset($userexitInterdata[0]['remain_employed_here']) ? $userexitInterdata[0]['remain_employed_here'] : '' ?>"
                            required />

                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">If you could change anything
                        about your job or the company, what would you change? <span class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="text" name="job_company_change" class="form-control"
                            value="<?php echo isset($userexitInterdata[0]['job_company_change']) ? $userexitInterdata[0]['job_company_change'] : '' ?>"
                            required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">Were you satisfied with the way
                        you were managed? <span class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="text" name="satisfied_manged" class="form-control"
                            value="<?php echo isset($userexitInterdata[0]['satisfied_manged']) ? $userexitInterdata[0]['satisfied_manged'] : '' ?>"
                            required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">Did you have clear goals and
                        objectives?<span class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="text" name="objectives" class="form-control"
                            value="<?php echo isset($userexitInterdata[0]['objectives']) ? $userexitInterdata[0]['objectives'] : '' ?>"
                            required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">Did you receive constructive
                        feedback to help you improve your performance? <span class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="text" name="performance" class="form-control"
                            value="<?php echo isset($userexitInterdata[0]['performance']) ? $userexitInterdata[0]['performance'] : '' ?>"
                            required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">Would you consider coming back to
                        work here in the future? In what area or function? What would need to change?<span
                            class="text-danger">*</span> </label>
                    <div class="col-7 col-xl-7">
                        <input type="text" name="work_here_future" class="form-control"
                            value="<?php echo isset($userexitInterdata[0]['work_here_future']) ? $userexitInterdata[0]['work_here_future'] : '' ?>"
                            required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">Would you recommend a friend to
                        pursue employment with this company? <span class="text-danger">*</span></label>
                    <div class="col-7 col-xl-7">
                        <input type="text" name="recommend_employment" class="form-control"
                            value="<?php echo isset($userexitInterdata[0]['recommend_employment']) ? $userexitInterdata[0]['recommend_employment'] : '' ?>"
                            required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-5 col-xl-5 col-form-label">Any other feedback<span class="text-danger">*</span>
                    </label>
                    <div class="col-7 col-xl-7">
                        <input type="text" name="feedback" class="form-control"
                            value="<?php echo isset($userexitInterdata[0]['feedback']) ? $userexitInterdata[0]['feedback'] : '' ?>"
                            required />
                    </div>
                </div>

        </div>


        <div class="justify-content-end row">
            <div class="col-7 col-xl-7">
                <?php if (isset($validation)): ?>
                    <div class=col-12 col-sm-4>
                        <div class="alert alert-danger" role="alert">
                            <?= $validation->listErrors() ?>
                        </div>
                    </div>
                <?php endif; ?>
                <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light" id="submitButton">
                    Submit
                </button>
            </div>
            </form>

        </div>
    </div>
</div>
<script>
    function validateInput(input) {
        var value = input.value;
        var errorMsg = document.getElementById('errorMsg');
        if (value < 0) {
            errorMsg.textContent = "Please enter a positive number.";
            input.value = ""; // Clear the input field
        } else {
            errorMsg.textContent = "";
        }
    }
</script>
<script>
    function validatemobileInput(input) {
        const value = input.value;
        const errorMsg = document.getElementById('errorPMsg');

        if (!/^\d*$/.test(value)) {
            errorMsg.textContent = "Only numeric values are allowed.";
            input.value = value.replace(/\D/g, ''); // Remove non-digits
        } else if (value.length > 10) {
            input.value = value.slice(0, 10); // Limit to 10 digits
        } else {
            errorMsg.textContent = "";
        }
    }
</script>
<script>
    function validateEmail(input) {
        const value = input.value.trim();
        const errorEmailMsg = document.getElementById('erroreMsg');

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (value === '') {
            errorEmailMsg.textContent = "Email is required.";
        } else if (!emailRegex.test(value)) {
            errorEmailMsg.textContent = "Please enter a valid email address.";
        } else {
            errorEmailMsg.textContent = "";
        }
    }
</script>