<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link); ?>">Client</a></li>

                </ol>
            </div>
            <h4 class="page-title">Add User</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form" action="<?php echo base_url($register); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">First <?php echo lang('UI_Text.Name') ?><span class="text-danger">*</span></label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="name" placeholder="<?php echo lang('UI_Text.Name') ?>" value="<?= set_value('name') ?>" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Last <?php echo lang('UI_Text.Name') ?><span class="text-danger">*</span></label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="last_name" placeholder="Last <?php echo lang('UI_Text.Name') ?>" value="<?= set_value('last_name') ?>" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Email') ?><span class="text-danger">*</span></label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" id="email" name="email" value="" placeholder="Email" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Password') ?><span class="text-danger">*</span></label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" id="password" name="password" placeholder="<?php echo lang('UI_Text.Password') ?>" value="<?php echo $temppass; ?>" />
                            <div class="password-rules compact">
                                <p class="invalid"><span class="icon"></span> Minimum 8 characters</p>
                                <p class="invalid"><span class="icon"></span> One capitalized letter</p>
                                <p class="invalid"><span class="icon"></span> One lowercase letter</p>
                                <p class="invalid"><span class="icon"></span> One number</p>
                                <p class="invalid"><span class="icon"></span> Special character (!@#$%^&*()_+-=[]{}|;:<>?)</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-4 col-xl-3 col-form-label">
                            <!-- <div class="card-header" id="headingFive"> -->
                            <!-- <p class="m-0 position-relative"> -->
                            <a class="custom-accordion-title text-reset collapsed d-block"
                                data-bs-toggle="collapse" href="#collapseFive"
                                aria-expanded="false" aria-controls="collapseFive">Optional <i
                                    class="mdi mdi-chevron-down accordion-arrow"></i>
                            </a>
                            <!-- </p> -->
                            <!-- </div> -->
                        </label>

                    </div>
                    <?php $userlevel = session()->get('userlevel');
                    $arrayuserlevel  = explode(',', $userlevel); ?>
                    <div id="collapseFive" class="collapse"
                        aria-labelledby="headingFive"
                        data-bs-parent="#custom-accordion-one">
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-5 col-xl-5 col-form-label"><?php echo lang('UI_Text.Userlevel') ?></label>
                            <div class="col-7 col-xl-7">
                                <select name="userlevelItem" class="form-control">
                                    <?php if (!in_array('6', $arrayuserlevel)) { ?>
                                        <!-- <option value="3">Normal Users</option> -->
                                        <!-- <option value="44">Client Admin</option> -->
                                        <option value="45">Client Reviewer</option>
                                        <?php } else {

                                        foreach ($userlevelData as $eachcategoryItem) { ?>

                                            <option value="<?php echo $eachcategoryItem['id_ua'] ?>"><?php echo $eachcategoryItem['name'] ?></option>
                                    <?php }
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="appUsername" class="col-5 col-xl-5 col-form-label">App Username/Emp ID</label>
                            <div class="col-7 col-xl-7">
                                <input type="text" class="form-control" name="app_username" placeholder="App Username" oninput="validateInput(this)" value="<?= htmlspecialchars(set_value('app_username')) ?>" />
                                <div id="errorMsg" class="error-msg"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="appPassword" class="col-5 col-xl-5 col-form-label">App Password</label>
                            <div class="col-7 col-xl-7">
                                <input type="text" class="form-control" name="app_password" value="Welcome123" placeholder="App Password" />
                            </div>
                        </div>

                    </div>


                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <?php if (isset($validation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="timezone" value="6">
                            <input type="hidden" name="lang" value="en">
                            <input type="hidden" name="subjoin" value="1">
                            <input type="hidden" name="createdby" value=" ">
                            <input type="hidden" name="cid" value="<?php echo $clientid; ?>">

                            <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light" id="submitButton">
                                Submit
                            </button>
                        </div>
                </form>

            </div>
        </div>
    </div>
</div>
</div>
</div>
<style>
    .error-msg {
        color: red;
        font-size: 0.9em;
    }
</style>
<script>
    // JavaScript function to validate alphanumeric input and length
    function validateInput(input) {
        var regex = /^[a-zA-Z0-9]*$/; // Regular expression for alphanumeric characters
        var errorMsg = document.getElementById('errorMsg');

        if (!regex.test(input.value)) {
            errorMsg.textContent = "Please enter only alphanumeric characters.";
            input.value = input.value.replace(/[^a-zA-Z0-9]/g, ''); // Remove non-alphanumeric characters
        } else if (input.value.length > 10) {
            errorMsg.textContent = "Maximum 10 characters allowed.";
            input.value = input.value.slice(0, 10); // Trim input to maximum length
        } else {
            errorMsg.textContent = "";
        }
    }
</script>
<script>
    function toggleOptionalFields() {
        var optionalFields = document.getElementById('optionalFields');
        var toggleButton = document.getElementById('optionalToggle');
        if (optionalFields.style.display === 'none') {
            optionalFields.style.display = 'block';
            toggleButton.textContent = 'Hide Optional Fields';
        } else {
            optionalFields.style.display = 'none';
            toggleButton.textContent = 'Show Optional Fields';
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const passwordField = document.getElementById('password');
        const rules = document.querySelectorAll('.password-rules p');

        function validatePassword(val) {
            toggleRule(0, val.length >= 8);
            toggleRule(1, /[A-Z]/.test(val));
            toggleRule(2, /[a-z]/.test(val));
            toggleRule(3, /[0-9]/.test(val));
            toggleRule(4, /[^A-Za-z0-9]/.test(val));
        }

        function toggleRule(index, isValid) {
            if (!rules[index]) return; // safety check

            if (isValid) {
                rules[index].classList.add('valid');
                rules[index].classList.remove('invalid');
            } else {
                rules[index].classList.add('invalid');
                rules[index].classList.remove('valid');
            }
        }

        // 🔹 Run on typing
        passwordField.addEventListener('input', function() {
            validatePassword(this.value);
        });

        // 🔥 IMPORTANT: Run on page load (for PHP generated password)
        validatePassword(passwordField.value);

    });
</script>
<script>
    const password = document.getElementById('password');
    const email = document.getElementById('email');
   
    [password, email].forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === ' ') {
                e.preventDefault();
            }
        });

        input.addEventListener('input', function() {
            this.value = this.value.replace(/\s/g, '');
        });
    });
</script>