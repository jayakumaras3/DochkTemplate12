<?php
$profile_image = session()->get('profile_image');
$profile_foldername = session()->get('profile_foldername');
$client = session()->get('client');
$userlevel = session()->get('userlevel');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
?>
<style>
    .profile-edit-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .profile-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .profile-edit-card .input-icon-group {
        position: relative;
    }

    .profile-edit-card .input-icon-group>i:first-child {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a6ad;
        pointer-events: none;
    }

    .profile-edit-card .input-icon-group .form-control {
        border-radius: 10px;
        padding: .55rem .9rem .55rem 2.5rem;
    }

    .profile-edit-card .form-control,
    .profile-edit-card .form-select {
        border-radius: 10px;
        padding: .55rem .9rem;
    }

    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none;
    }

    .password-strength-meter {
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .strength-bar-track {
        flex: 1;
        display: flex;
        gap: 4px;
    }

    .strength-bar-segment {
        height: 5px;
        flex: 1;
        border-radius: 3px;
        background-color: #eef2f7;
    }

    [data-bs-theme="dark"] .strength-bar-segment {
        background-color: #36404a;
    }

    .strength-bar-segment.strength-weak {
        background-color: #fa5c7c;
    }

    .strength-bar-segment.strength-medium {
        background-color: #ffbc00;
    }

    .strength-bar-segment.strength-strong {
        background-color: #0acf97;
    }

    .strength-label {
        font-size: .8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .strength-label.strength-weak {
        color: #fa5c7c;
    }

    .strength-label.strength-medium {
        color: #ffbc00;
    }

    .strength-label.strength-strong {
        color: #0acf97;
    }

    .profile-detail-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: .55rem 0;
        border-bottom: 1px solid #eef2f7;
        font-size: .875rem;
    }

    [data-bs-theme="dark"] .profile-detail-row {
        border-bottom-color: #36404a;
    }

    .profile-detail-row:last-child {
        border-bottom: none;
    }

    .profile-detail-row .detail-label {
        color: #6c757d;
        font-weight: 600;
        flex-shrink: 0;
    }

    .profile-detail-row .detail-value {
        text-align: right;
    }

    .profile-edit-avatar {
        cursor: pointer;
    }

    .upload-photo-modal {
        border-radius: 18px;
        border: none;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
    }

    .upload-modal-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        background: rgba(102, 88, 221, .12);
        color: #6658dd;
    }

    [data-bs-theme="dark"] .upload-modal-icon {
        background: rgba(146, 152, 245, .18);
        color: #9298f5;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo lang('UI_Text.My_Account'); ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-xl-6">
        <div class="card profile-edit-card">
            <div class="card-body">
                <?php if ($client == 1) { ?>
                    <div class="card-widgets">
                        <a href="<?php echo base_url('User_login/profile/update_data'); ?>"><i class="mdi mdi-pencil-outline"></i></a>
                    </div>
                <?php } ?>
                <div class="text-center">
                    <img src="<?php echo (!empty($userdata['0']['profile_foldername']) && !empty($userdata['0']['profile_image']))
                                    ? base_url('assets/assets/uploads/profile/' . $userdata['0']['id_user'] . "/" . $userdata['0']['profile_foldername'] . "/" . $userdata['0']['profile_image'])
                                    : base_url('public/aristo_assets/images/User_2_1.svg'); ?>"
                        class="rounded-circle avatar-lg img-thumbnail profile-edit-avatar"
                        alt="profile-image"
                        data-bs-toggle="modal"
                        data-bs-target="#uploadPhotoModal">
                    <h4 class="mb-0 mt-2"><?php echo $userdata[0]['name'] . " " . $userdata[0]['last_name']; ?></h4>
                    <h5><?php echo $userdata[0]['designation']; ?></h5>
                    <p class="text-muted"><?php echo $userdata[0]['email']; ?></p>
                </div>
                <div class="modal fade" id="uploadPhotoModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content upload-photo-modal">
                            <div class="modal-header border-0 pb-0">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="upload-modal-icon"><i class="mdi mdi-image-outline"></i></div>
                                    <div>
                                        <h5 class="modal-title fw-bold mb-0"><?php echo lang('UI_Text.Upload_Profile_Image'); ?></h5>
                                        <p class="text-muted font-13 mb-0"><?php echo lang('UI_Text.Upload_Image_Subtitle'); ?></p>
                                    </div>
                                </div>
                                <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="<?php echo lang('Buttons.Close'); ?>">
                                </button>
                            </div>
                            <div class="modal-body pt-3">
                                <form id="uploadForm"
                                    action="<?php echo base_url('User_login/profile/uploadImage') ?>"
                                    method="post"
                                    enctype="multipart/form-data"><?= csrf_field() ?>

                                    <div class="mb-3">
                                        <label for="file" class="form-label fw-semibold"><?php echo lang('UI_Text.Upload_Profile_Image'); ?></label>
                                        <input type="file" name="file" id="file" accept="image/jpeg,image/png,.jpg,.jpeg,.png" class="form-control" required />
                                        <p class="text-muted font-13 mt-2 mb-0"><?php echo lang('UI_Text.Image_Requirements'); ?>: <?php echo lang('UI_Text.JPG_Format_Only'); ?>, <?php echo lang('UI_Text.Square_Image_Required'); ?>, <?php echo lang('UI_Text.Less_Than_100KB'); ?>.</p>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="submit" value="<?php echo lang('Buttons.Upload_Profile_Image'); ?>" name="submit"
                                            class="btn btn-primary rounded-pill px-4 waves-effect waves-light">
                                            <?php echo lang('Buttons.Upload_Profile_Image'); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <?php if (!empty($userdata[0]['emp_id'] ?? '')) { ?>
                        <div class="profile-detail-row">
                            <span class="detail-label"><?php echo lang('UI_Text.Employee_ID'); ?></span>
                            <span class="detail-value"><?php echo $userdata[0]['emp_id']; ?></span>
                        </div>
                    <?php } ?>
                    <?php if ($personal_data) { ?>
                        <?php if (!empty($personal_data[0]['DOB'] ?? '')) { ?>
                            <div class="profile-detail-row">
                                <span class="detail-label"><?php echo lang('UI_Text.Date_of_Birth'); ?></span>
                                <span class="detail-value"><?php echo date('M-d', strtotime($personal_data[0]['DOB'])); ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($personal_data[0]['personal_mail'] ?? '')) { ?>
                            <div class="profile-detail-row">
                                <span class="detail-label"><?php echo lang('UI_Text.Personal_Email'); ?></span>
                                <span class="detail-value"><?php echo $personal_data[0]['personal_mail']; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($personal_data[0]['current_addresss'] ?? '')) { ?>
                            <div class="profile-detail-row">
                                <span class="detail-label"><?php echo lang('UI_Text.Current_Address'); ?></span>
                                <span class="detail-value"><?php echo $personal_data[0]['current_addresss']; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($personal_data[0]['permanent_address'] ?? '')) { ?>
                            <div class="profile-detail-row">
                                <span class="detail-label"><?php echo lang('UI_Text.Permanent_Address'); ?></span>
                                <span class="detail-value"><?php echo $personal_data[0]['permanent_address']; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($personal_data[0]['personal_phone'] ?? '')) { ?>
                            <div class="profile-detail-row">
                                <span class="detail-label"><?php echo lang('UI_Text.Phone_Number'); ?></span>
                                <span class="detail-value"><?php echo $personal_data[0]['personal_phone']; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($personal_data[0]['home_phone'] ?? '')) { ?>
                            <div class="profile-detail-row">
                                <span class="detail-label"><?php echo lang('UI_Text.Phone_Number'); ?> 2</span>
                                <span class="detail-value"><?php echo $personal_data[0]['home_phone']; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($personal_data[0]['emergency_phone'] ?? '')) { ?>
                            <div class="profile-detail-row">
                                <span class="detail-label"><?php echo lang('UI_Text.Emergency_Phone'); ?></span>
                                <span class="detail-value"><?php echo $personal_data[0]['emergency_phone']; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($personal_data[0]['emergency_contact'] ?? '')) { ?>
                            <div class="profile-detail-row">
                                <span class="detail-label"><?php echo lang('UI_Text.Emergency_Contact'); ?></span>
                                <span class="detail-value"><?php echo $personal_data[0]['emergency_contact']; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($personal_data[0]['emergency_relation'] ?? '')) { ?>
                            <div class="profile-detail-row">
                                <span class="detail-label"><?php echo lang('UI_Text.Emergency_Relation'); ?></span>
                                <span class="detail-value"><?php echo $personal_data[0]['emergency_relation']; ?></span>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col-->

    <div class="col-lg-6 col-xl-6">
        <div class="card profile-edit-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="profile-card-icon bg-soft-primary text-primary"><i class="mdi mdi-key-outline"></i></div>
                    <div>
                        <h5 class="mb-0 fw-bold"><?php echo lang('Buttons.Update_Password') ?></h5>
                        <p class="text-muted mb-0 font-13"><?php echo lang('UI_Text.Update_Password_Self_Sub') ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form action="<?php echo base_url() . 'User_login/profile/changeUserdata' ?>" method="POST"><?= csrf_field() ?>
                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Enter_new_Password'); ?></label>
                            <div class="mb-2">
                                <div class="input-icon-group position-relative">
                                    <i class="mdi mdi-lock-outline"></i>
                                    <input type="password" class="form-control pe-5" id="password" name="password" required value="">
                                    <button type="button" id="toggleProfilePassword" class="btn btn-link text-muted p-0 position-absolute top-50 end-0 translate-middle-y me-3" tabindex="-1">
                                        <i class="mdi mdi-eye-outline font-18"></i>
                                    </button>
                                </div>
                                <div class="password-strength-meter mt-2">
                                    <div class="strength-bar-track">
                                        <div class="strength-bar-segment" id="strengthSegment1"></div>
                                        <div class="strength-bar-segment" id="strengthSegment2"></div>
                                        <div class="strength-bar-segment" id="strengthSegment3"></div>
                                    </div>
                                    <span class="strength-label" id="strengthLabel"></span>
                                </div>
                                <div class="password-rules compact">
                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.Min_8_Characters'); ?></p>
                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.One_Capital_Letter'); ?></p>
                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.One_Lowercase_Letter'); ?></p>
                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.One_Number'); ?></p>
                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.Special_Character'); ?></p>
                                </div>
                            </div>
                            <?php if (isset($validationpassword)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validationpassword->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="mt-3">
                                <button type="submit" id="submitBtn"
                                    class="btn btn-outline-danger rounded-pill waves-effect waves-light" disabled>
                                    <?php echo lang('Buttons.Update_Password'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if ($client == 1) { ?>
            <div class="card profile-edit-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="profile-card-icon bg-soft-success text-success"><i class="mdi mdi-cog-outline"></i></div>
                        <div>
                            <h5 class="mb-0 fw-bold"><?php echo lang('Buttons.Change_Site_Settings'); ?></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="<?php echo base_url() . 'User_login/profile/change_landing_page' ?>" method="POST"><?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="form-label fw-semibold"><?php echo lang('UI_Text.Default_Landing_Page'); ?></label>
                                        <select name="landing_page" class="form-select">
                                            <option value="0"><?php echo lang('Buttons.Dashboard'); ?></option>
                                            <option value="1" <?php
                                                                if ($userdata[0]['landing_page'] == 1)
                                                                    echo 'Selected';
                                                                ?>><?php echo lang('UI_Text.eTrack_Dashboard'); ?></option>
                                            <option value="2" <?php
                                                                if ($userdata[0]['landing_page'] == 2)
                                                                    echo 'Selected';
                                                                ?>><?php echo lang('Buttons.Attendance'); ?></option>
                                            <option value="7" <?php
                                                                if ($userdata[0]['landing_page'] == 7)
                                                                    echo 'Selected';
                                                                ?>><?php echo lang('Buttons.Marketplace'); ?></option>
                                            <?php if (in_array('7', $arrayuserlevel)) { ?>
                                                <option value="3" <?php
                                                                    if ($userdata[0]['landing_page'] == 3)
                                                                        echo 'Selected';
                                                                    ?>><?php echo lang('UI_Text.My_Task'); ?></option>
                                            <?php } ?>
                                            <?php if (session()->get('report_to_you') == 2) { ?>
                                                <option value="4" <?php
                                                                    if ($userdata[0]['landing_page'] == 4)
                                                                        echo 'Selected';
                                                                    ?>><?php echo lang('Buttons.Project_Tasks'); ?></option>
                                            <?php } ?>
                                            <?php if (in_array('4', $arrayuserlevel)) { ?>
                                                <option value="5" <?php
                                                                    if ($userdata[0]['landing_page'] == 5)
                                                                        echo 'Selected';
                                                                    ?>><?php echo lang('UI_Text.UCN'); ?></option>
                                                <option value="6" <?php
                                                                    if ($userdata[0]['landing_page'] == 6)
                                                                        echo 'Selected';
                                                                    ?>><?php echo lang('Buttons.My_Projects'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-6 mt-3">
                                        <label class="form-label fw-semibold"><?php echo lang('UI_Text.Top_Banner_Color'); ?></label>
                                        <input type="color" name="banner_color" id="colorpicker-default"
                                            class="form-control form-control-color"
                                            value="<?php echo !empty($userdata[0]['banner_color']) ? $userdata[0]['banner_color'] : '#6658dd'; ?>"
                                            title="<?php echo lang('UI_Text.Choose_Banner_Color'); ?>">
                                    </div>
                                    <div class="col-6   mt-3">
                                        <label class="form-label fw-semibold"><?php echo lang('UI_Text.Theme'); ?></label>
                                        <select name="theme_color" class="form-select">
                                            <option value="0" <?php if ($userdata[0]['theme_color'] == 0)
                                                                    echo 'Selected'; ?>><?php echo lang('UI_Text.Light'); ?></option>
                                            <option value="1" <?php if ($userdata[0]['theme_color'] == 1)
                                                                    echo 'Selected'; ?>><?php echo lang('UI_Text.Dark'); ?></option>
                                        </select>
                                    </div>
                                    <div class="mt-3">
                                        <input type="hidden" name="subedit" value="1" />
                                        <button type="submit"
                                            class="btn btn-primary rounded-pill waves-effect waves-light">
                                            <?php echo lang('Buttons.Change_Site_Settings'); ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
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
    document.addEventListener('DOMContentLoaded', function() {

        const passwordField = document.getElementById('password');
        const submitBtn = document.getElementById('submitBtn');
        const rules = document.querySelectorAll('.password-rules p');
        const segments = [
            document.getElementById('strengthSegment1'),
            document.getElementById('strengthSegment2'),
            document.getElementById('strengthSegment3')
        ];
        const strengthLabel = document.getElementById('strengthLabel');
        const strengthLevels = [{
            className: 'strength-weak',
            text: "<?php echo esc(lang('UI_Text.Weak'), 'js') ?>",
            segments: 1
        }, {
            className: 'strength-medium',
            text: "<?php echo esc(lang('UI_Text.Medium'), 'js') ?>",
            segments: 2
        }, {
            className: 'strength-strong',
            text: "<?php echo esc(lang('UI_Text.Strong'), 'js') ?>",
            segments: 3
        }];
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,64}$/;

        function validatePassword(val) {
            const passed = [
                val.length >= 8,
                /[A-Z]/.test(val),
                /[a-z]/.test(val),
                /[0-9]/.test(val),
                /[^A-Za-z0-9]/.test(val)
            ];
            passed.forEach((isValid, index) => toggleRule(index, isValid));
            updateStrengthMeter(val ? passed.filter(Boolean).length : -1);
            submitBtn.disabled = !regex.test(val);
        }

        function toggleRule(index, isValid) {
            if (!rules[index]) return;

            if (isValid) {
                rules[index].classList.add('valid');
                rules[index].classList.remove('invalid');
            } else {
                rules[index].classList.add('invalid');
                rules[index].classList.remove('valid');
            }
        }

        function updateStrengthMeter(passedCount) {
            if (!strengthLabel || segments.some(s => !s)) return;

            segments.forEach(segment => {
                segment.className = 'strength-bar-segment';
            });
            strengthLabel.className = 'strength-label';

            if (passedCount < 0) {
                strengthLabel.textContent = '';
                return;
            }

            const level = passedCount >= 5 ? strengthLevels[2] : (passedCount >= 3 ? strengthLevels[1] : strengthLevels[0]);

            for (let i = 0; i < level.segments; i++) {
                segments[i].classList.add(level.className);
            }
            strengthLabel.classList.add(level.className);
            strengthLabel.textContent = level.text;
        }

        passwordField.addEventListener('input', function() {
            validatePassword(this.value);
        });

        validatePassword(passwordField.value);

        const toggleProfilePassword = document.getElementById('toggleProfilePassword');
        if (toggleProfilePassword) {
            toggleProfilePassword.addEventListener('click', function() {
                const isHidden = passwordField.type === 'password';
                passwordField.type = isHidden ? 'text' : 'password';
                this.querySelector('i').className = isHidden ? 'mdi mdi-eye-off-outline font-18' : 'mdi mdi-eye-outline font-18';
            });
        }

    });
</script>
<script>
    const password = document.getElementById('password');

    [password].forEach(input => {
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