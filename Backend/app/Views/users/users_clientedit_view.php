<?php $client = session()->get('client');
$clientarray = explode(',', $client);
$userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
//print_r($row); 
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_users'); ?>"><?php echo lang('UI_Text.User_Management') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.Edit_User') ?> - <?php echo $row['name']; ?></h4>
        </div>
    </div>
</div>
<style>
    .profile-edit-tabs {
        background: transparent;
        border-bottom: 1px solid #eef2f7;
    }

    .profile-edit-tabs .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        border-radius: 0;
        border-bottom: 2px solid transparent;
        color: #6c757d;
        font-weight: 600;
        padding: .9rem 1rem;
        background: transparent !important;
    }

    .profile-edit-tabs .nav-link .tab-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(102, 88, 221, .08);
        color: #6658dd;
        font-size: 16px;
    }

    .profile-edit-tabs .nav-link.active {
        color: #6658dd !important;
        border-bottom-color: #6658dd;
    }

    .profile-edit-tabs .nav-link.active .tab-icon {
        background: rgba(102, 88, 221, .15);
    }

    [data-bs-theme="dark"] .profile-edit-tabs {
        border-bottom-color: #36404a;
    }

    [data-bs-theme="dark"] .profile-edit-tabs .nav-link.active {
        color: #9298f5 !important;
        border-bottom-color: #9298f5;
    }

    [data-bs-theme="dark"] .profile-edit-tabs .nav-link.active .tab-icon {
        background: rgba(146, 152, 245, .18);
        color: #9298f5;
    }

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

    .password-info-box {
        background-color: rgba(102, 88, 221, .08);
        border-radius: 12px;
        padding: .65rem 1rem;
    }

    [data-bs-theme="dark"] .password-info-box {
        background-color: rgba(146, 152, 245, .12);
    }

    [data-bs-theme="dark"] .password-info-box .text-primary {
        color: #9298f5 !important;
    }

    .password-info-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(102, 88, 221, .15);
        color: #6658dd;
        flex-shrink: 0;
    }

    [data-bs-theme="dark"] .password-info-icon {
        background: rgba(146, 152, 245, .2);
        color: #9298f5;
    }

    .profile-edit-card .bullet-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }

    .profile-edit-card .bullet-list li {
        position: relative;
        padding-left: 1rem;
        margin-bottom: .35rem;
        font-size: .875rem;
        color: #6c757d;
    }

    .profile-edit-card .bullet-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: .55em;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #6658dd;
    }

    [data-bs-theme="dark"] .profile-edit-card .bullet-list li::before {
        background: #9298f5;
    }

    .access-level-table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .access-level-table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .access-level-table td {
        vertical-align: middle;
    }
</style>
<?php if ($client == 1) { ?>
    <div class="row">
        <ul class="nav nav-pills nav-fill navtab-bg profile-edit-tabs">
            <li class="nav-item">
                <a href="#profile" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                    <span class="tab-icon"><i class="mdi mdi-account-outline"></i></span>
                    <?php echo lang('UI_Text.Profile') ?>
                </a>
            </li>
            <li class="nav-item">
                <a href="#profile_advance" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                    <span class="tab-icon"><i class="mdi mdi-account-multiple-outline"></i></span>
                    <?php echo lang('UI_Text.Profile_Advanced') ?>
                </a>
            </li>
            <li class="nav-item">
                <a href="#access" data-bs-toggle="tab" aria-expanded="false" class="nav-link ">
                    <span class="tab-icon"><i class="mdi mdi-lock-outline"></i></span>
                    <?php echo lang('UI_Text.Access_Level') ?>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active show" id="profile">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card profile-edit-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <div class="profile-card-icon bg-soft-primary text-primary"><i class="mdi mdi-account-outline"></i></div>
                                            <div>
                                                <h5 class="mb-0 fw-bold"><?php echo lang('UI_Text.Profile_Information') ?></h5>
                                                <p class="text-muted mb-0 font-13"><?php echo lang('UI_Text.Profile_Information_Sub') ?></p>
                                            </div>
                                        </div>
                                        <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/editUsers/' . base64_encode($row['id_user'])); ?>" method="POST"><?= csrf_field() ?>
                                            <div class="mb-3">
                                                <label for="inputEmail3" class="form-label fw-semibold"><?php echo lang('UI_Text.First_Name') ?> <span class="text-danger">*</span></label>
                                                <div class="input-icon-group">
                                                    <i class="mdi mdi-account-outline"></i>
                                                    <input class="form-control" type="text" name="name" maxlength="50" value="<?php echo $row['name']; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputEmail3" class="form-label fw-semibold"><?php echo lang('UI_Text.Last_Name') ?> <span class="text-danger">*</span></label>
                                                <div class="input-icon-group">
                                                    <i class="mdi mdi-account-outline"></i>
                                                    <input class="form-control" type="text" name="last_name" maxlength="50" value="<?php echo $row['last_name']; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputEmail3" class="form-label fw-semibold"><?php echo lang('UI_Text.Email') ?> <span class="text-danger">*</span></label>
                                                <div class="input-icon-group">
                                                    <i class="mdi mdi-email-outline"></i>
                                                    <input class="form-control" type="text" id="username" name="email" value="<?php echo $row['email']; ?>" placeholder="<?php echo lang('UI_Text.Email') ?>">
                                                </div>
                                            </div>
                                            <?php if (isset($validationEditUsers)) : ?>
                                                <div class=col-12 col-sm-4>
                                                    <div class="alert alert-danger" role="alert">
                                                        <?= $validationEditUsers->listErrors() ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <input type="hidden" name="id_user" value="<?php echo base64_encode($row['id_user']) ?>" />
                                            <input type="hidden" name="clientid" value="<?php echo session()->get('client') ?>" />
                                            <input type="hidden" name="adminuseredit" value="1" />
                                            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                    <?php echo lang('Buttons.Update_User') ?>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card profile-edit-card">
                                    <div class="card-body ">
                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <div class="profile-card-icon bg-soft-primary text-primary"><i class="mdi mdi-key-outline"></i></div>
                                            <div>
                                                <h5 class="mb-0 fw-bold"><?php echo lang('Buttons.Update_Password') ?></h5>
                                                <p class="text-muted mb-0 font-13"><?php echo lang('UI_Text.Update_Password_Sub') ?></p>
                                            </div>
                                        </div>
                                        <?php if (session()->get('passsuccess')) : ?>
                                            <div class="alert alert-success" role="alert">
                                                <?= session()->get('passsuccess') ?>
                                            </div>
                                        <?php endif; ?>
                                        <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/passeditUsers/' . base64_encode($row['id_user'])); ?>" method="POST"><?= csrf_field() ?>
                                            <div class="mb-2">
                                                <div class="input-icon-group position-relative">
                                                    <i class="mdi mdi-lock-outline"></i>
                                                    <input class="form-control pe-5" type="password" id="password" name="password" maxlength="30" value="<?php echo $temppass; ?>">
                                                    <button type="button" id="toggleEditPassword" class="btn btn-link text-muted p-0 position-absolute top-50 end-0 translate-middle-y me-3" tabindex="-1">
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
                                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.Min_8_Characters') ?></p>
                                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.One_Capital_Letter') ?></p>
                                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.One_Lowercase_Letter') ?></p>
                                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.One_Number') ?></p>
                                                    <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.Special_Character') ?></p>
                                                </div>
                                            </div>
                                            <?php if (isset($validationpassEditUsers)) : ?>
                                                <div class=col-12 col-sm-4>
                                                    <div class="alert alert-danger" role="alert">
                                                        <?= $validationpassEditUsers->listErrors() ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="password-info-box d-flex align-items-center gap-2 mt-3">
                                                <div class="password-info-icon"><i class="mdi mdi-shield-check-outline"></i></div>
                                                <div class="text-primary fw-semibold font-13"><?php echo lang('UI_Text.Password_Requirements_Notice') ?></div>
                                            </div>
                                            <input type="hidden" name="id_user" value="<?php echo base64_encode($row['id_user']) ?>" />
                                            <input type="hidden" name="clientid" value="<?php echo session()->get('client') ?>" />
                                            <input type="hidden" name="adminuseredit" value="1" />
                                            <div class="mt-3">
                                                <button type="submit" id="submitBtn" class="btn btn-outline-danger rounded-pill" disabled>
                                                    <?php echo lang('Buttons.Update_Password') ?>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="profile_advance">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card profile-edit-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="profile-card-icon bg-soft-primary text-primary"><i class="mdi mdi-tune-variant"></i></div>
                                    <div>
                                        <h5 class="mb-0 fw-bold"><?php echo lang('UI_Text.Advanced_Profile_Information') ?></h5>
                                        <p class="text-muted mb-0 font-13"><?php echo lang('UI_Text.Advanced_Profile_Information_Sub') ?></p>
                                    </div>
                                </div>
                                <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/editprofileAdvance/' . base64_encode($row['id_user'])) ?>" method="POST"><?= csrf_field() ?>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="emplpee_id" class="form-label fw-semibold"><?php echo lang('UI_Text.Employee_ID') ?> <span class="text-danger">*</span></label>
                                                <div class="input-icon-group">
                                                    <i class="mdi mdi-card-account-details-outline"></i>
                                                    <input class="form-control" type="text" name="emp_id" value="<?php echo  isset($row['emp_id']) ? $row['emp_id'] : '' ?>">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="inputEmail3" class="form-label fw-semibold"><?php echo lang('UI_Text.Reports_To') ?> <span class="text-danger">*</span></label>
                                                <select name="manager" class="form-control">
                                                    <?php
                                                    if (isset($manager)) {

                                                        if (strlen($row['manager'] ?? '') == 0 || $row['manager'] == 0 || $row['manager'] == 'NULL') {
                                                            echo '<option value="0">' . lang('UI_Text.select') . '</option>';
                                                        }

                                                        foreach ($manager as $data) {
                                                            if ($data['report_to_you'] != 2) continue;
                                                            if ($row['manager']  == $data['id_user']) {
                                                                echo '<option  selected="selected" value="' . $data['id_user'] . '">'  . $data['name'] . ' ' . $data['last_name'] . '</option>';
                                                            } else {

                                                                echo '<option value="' . $data['id_user'] . '">'  . $data['name'] . ' ' . $data['last_name'] . '</option>';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="designation" class="form-label fw-semibold"><?php echo lang('UI_Text.Designation') ?> <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="designation" value="<?php echo  isset($row['designation']) ?  $row['designation'] : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="inputEmail3" class="form-label fw-semibold"><?php echo lang('UI_Text.Will_Someone_Report_To_You') ?> <span class="text-danger">*</span></label>
                                                <select class="form-select" name="report_to_you">
                                                    <?php
                                                    if (strlen($row['report_to_you'] ?? '') == 0 || $row['report_to_you'] == 0 || $row['report_to_you'] == 'NULL') {
                                                        echo '<option value="1">' . lang('UI_Text.select') . '</option>';
                                                    }
                                                    ?>
                                                    <option value="1" <?php echo ($row['report_to_you'] == 1) ? 'selected' : ''; ?>><?php echo lang('UI_Text.No') ?></option>
                                                    <option value="2" <?php echo ($row['report_to_you'] == 2) ? 'selected' : ''; ?>><?php echo lang('UI_Text.Yes') ?></option>
                                                </select>
                                            </div>


                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="designation" class="form-label fw-semibold"><?php echo lang('UI_Text.Production_Capacity') ?> <span class="text-danger">*</span></label>
                                                <div class="input-icon-group">
                                                    <i class="mdi mdi-chart-pie"></i>
                                                    <input class="form-control" type="number" min="0" max="100" name="default_dashboard2" value="<?php echo  isset($row['default_dashboard2']) ?  $row['default_dashboard2'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="designation" class="form-label fw-semibold"><?php echo lang('UI_Text.Team') ?> <span class="text-danger">*</span></label>
                                                <select class="form-select" name="default_dashboard1">
                                                    <option value="1" <?php echo ($row['default_dashboard1'] == 1) ? 'selected' : ''; ?>><?php echo lang('UI_Text.E_Learning') ?></option>
                                                    <option value="2" <?php echo ($row['default_dashboard1'] == 2) ? 'selected' : ''; ?>><?php echo lang('UI_Text.DOCHECK') ?></option>
                                                    <option value="3" <?php echo ($row['default_dashboard1'] == 3) ? 'selected' : ''; ?>><?php echo lang('UI_Text.Library') ?></option>
                                                    <option value="4" <?php echo ($row['default_dashboard1'] == 4) ? 'selected' : ''; ?>><?php echo lang('UI_Text.Common') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <?php echo lang('Buttons.Update') ?>
                                        </button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="access">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card profile-edit-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="profile-card-icon bg-soft-primary text-primary"><i class="mdi mdi-shield-account-outline"></i></div>
                                    <div>
                                        <h5 class="mb-0 fw-bold"><?php echo lang('UI_Text.Assign_Access') ?></h5>
                                        <p class="text-muted mb-0 font-13"><?php echo lang('UI_Text.Assign_Access_Sub') ?></p>
                                    </div>
                                </div>
                                <form action="<?php echo base_url('User_login/client_users/updateCategoryItem/' . base64_encode($row['id_user'])); ?>" method="POST"><?= csrf_field() ?>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?php echo lang('UI_Text.Access_Level') ?> <span class="text-danger">*</span></label>
                                        <select name="userlevelItem" class="form-select">
                                            <?php $assigned = array_column($clientUserlevelData, 'fk_id_d');
                                            if (!in_array('1', $clientarray)) { ?>

                                                <?php if (!in_array(5, $assigned)) { ?>
                                                    <option value="5"><?php echo lang('UI_Text.Trainers') ?></option>
                                                <?php } ?>

                                                <?php if (!in_array(44, $assigned)) { ?>
                                                    <option value="44"><?php echo lang('Buttons.Admin') ?></option>
                                                <?php } ?>
                                                <?php } else {
                                                if (in_array('6', $arrayuserlevel)) {
                                                    foreach ($userlevelData as $eachcategoryItem) {
                                                        $key = array_search($eachcategoryItem['id_ua'], array_column($clientUserlevelData, 'fk_id_d'));
                                                        if (!empty($key) || $key === 0) {
                                                        } else { ?>
                                                            <option value="<?php echo $eachcategoryItem['id_ua'] ?>"><?php echo $eachcategoryItem['name'] ?></option>
                                                    <?php }
                                                    }
                                                } elseif (in_array('2010', $arrayuserlevel)) {
                                                    ?>
                                                    <?php if (!in_array(4, $assigned)) { ?><option value="4"><?php echo lang('UI_Text.Project_Manager') ?></option><?php } ?>
                                                    <?php if (!in_array(7, $assigned)) { ?><option value="7"><?php echo lang('UI_Text.Tasks') ?></option><?php } ?>
                                                    <?php if (!in_array(46, $assigned)) { ?><option value="46"><?php echo lang('UI_Text.Developer') ?></option><?php } ?>
                                                    <?php if (!in_array(67, $assigned)) { ?><option value="67"><?php echo lang('UI_Text.Quality_Control') ?></option><?php } ?>
                                                    <?php if (!in_array(5, $assigned)) { ?><option value="5"><?php echo lang('UI_Text.Trainers') ?></option><?php } ?>
                                                    <?php if (!in_array(44, $assigned)) { ?><option value="44"><?php echo lang('Buttons.Admin') ?></option><?php } ?>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php if (isset($validation)) : ?>
                                        <div class=col-12 col-sm-4>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $validation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <input type="hidden" name="clientid" value="<?php echo session()->get('client') ?>" />
                                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <?php echo lang('Buttons.Assign') ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card profile-edit-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="profile-card-icon bg-soft-primary text-primary"><i class="mdi mdi-shield-check-outline"></i></div>
                                    <div>
                                        <h5 class="mb-0 fw-bold"><?php echo lang('UI_Text.Access_Level') ?></h5>
                                        <p class="text-muted mb-0 font-13"><?php echo lang('UI_Text.Access_Level_Sub') ?></p>
                                    </div>
                                </div>
                                <table class="table access-level-table mb-0">
                                    <thead>
                                        <tr>
                                            <!-- <th><?php echo lang('UI_Text.Category') ?></th> -->
                                            <th><?php echo lang('UI_Text.Access') ?></th>
                                            <th><?php echo lang('UI_Text.Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if (!empty($clientUserlevelData)) : foreach ($clientUserlevelData as $eachuserData) {
                                                if (!in_array('1', $clientarray) && $eachuserData['category_name'] == 'client') {
                                                } else {
                                                    if (strlen($eachuserData['Category_item'] ?? '') > 0) {
                                                        if ($eachuserData['fk_id_d'] == 3) continue; ?>
                                                        <tr>
                                                            <!-- <td value="<?php echo $eachuserData['fk_id_dc'] ?>"><?php echo ucfirst($eachuserData['category_name']); ?></td> -->
                                                            <td value="<?php echo $eachuserData['fk_id_d'] ?>"><?php echo isset($eachuserData['Category_item']) ? $eachuserData['Category_item'] : '' ?></td>
                                                            <input type="hidden" name="id_user" value="<?php echo $eachuserData['id_user'] ?>" />
                                                            <!-- <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php //echo base_url('User_login/client_users/deleteUserCategory/' . $eachuserData['id_du'] . '/' . $eachuserData['id_user'])
                                                                                                                                                ?>" class="btn btn-outline-danger btn-xs waves-effect waves-light"><span class="mdi mdi-trash-can-outline"></span> <?php echo lang('Buttons.Delete') ?></a></td> -->
                                                            <td>
                                                                <form action="<?= base_url('User_login/client_users/deleteUserCategory') ?>" method="POST" style="display:inline;" onsubmit="return confirm('<?= lang('Alert.Aler_002') ?>');">
                                                                    <!-- CSRF token -->
                                                                    <?= csrf_field() ?>

                                                                    <!-- Hidden fields for IDs -->
                                                                    <input type="hidden" name="category_id" value="<?= $eachuserData['id_du'] ?>">
                                                                    <input type="hidden" name="user_id" value="<?= $eachuserData['id_user'] ?>">

                                                                    <button type="submit" class="btn btn-outline-danger rounded-pill btn-xs waves-effect waves-light">
                                                                        <?= lang('Buttons.Delete') ?>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                        <?php
                                                    }
                                                }
                                            }
                                        endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="card profile-edit-card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/editUsers/' . base64_encode($row['id_user'])); ?>" method="POST"><?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="inputEmail3" class="form-label fw-semibold"><?php echo lang('UI_Text.First_Name') ?></label>
                                    <div class="input-icon-group">
                                        <i class="mdi mdi-account-outline"></i>
                                        <input class="form-control" type="text" name="name" maxlength="50" value="<?php echo $row['name']; ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="inputEmail3" class="form-label fw-semibold"><?php echo lang('UI_Text.Last_Name') ?></label>
                                    <div class="input-icon-group">
                                        <i class="mdi mdi-account-outline"></i>
                                        <input class="form-control" type="text" name="last_name" maxlength="50" value="<?php echo $row['last_name']; ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="inputEmail3" class="form-label fw-semibold"><?php echo lang('UI_Text.Email') ?></label>
                                    <div class="input-icon-group">
                                        <i class="mdi mdi-email-outline"></i>
                                        <input class="form-control" type="text" name="email" value="<?php echo $row['email']; ?>" placeholder="<?php echo lang('UI_Text.Email') ?>">
                                    </div>
                                </div>
                                <?php if (isset($validationEditUsers)) : ?>
                                    <div class=col-12 col-sm-4>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $validationEditUsers->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" name="id_user" value="<?php echo base64_encode($row['id_user']) ?>" />
                                <input type="hidden" name="clientid" value="<?php echo session()->get('client') ?>" />
                                <input type="hidden" name="adminuseredit" value="1" />
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <?php echo lang('Buttons.Update') ?>
                                </button>
                            </form>
                        </div>

                    </div>
                    <div class="card profile-edit-card mt-3">
                        <div class="card-body ">
                            <?php if (session()->get('passsuccess')) : ?>
                                <div class="alert alert-success" role="alert">
                                    <?= session()->get('passsuccess') ?>
                                </div>
                            <?php endif; ?>
                            <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/passeditUsers/' . base64_encode($row['id_user'])); ?>" method="POST"><?= csrf_field() ?>
                                <div class="mb-2">
                                    <div class="input-icon-group position-relative">
                                        <i class="mdi mdi-lock-outline"></i>
                                        <input class="form-control pe-5" type="password" id="password" name="password" maxlength="30" value="<?php echo $temppass; ?>">
                                        <button type="button" id="toggleEditPassword" class="btn btn-link text-muted p-0 position-absolute top-50 end-0 translate-middle-y me-3" tabindex="-1">
                                            <i class="mdi mdi-eye-outline font-18"></i>
                                        </button>
                                    </div>
                                    <div class="password-rules compact">
                                        <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.Min_8_Characters') ?></p>
                                        <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.One_Capital_Letter') ?></p>
                                        <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.One_Lowercase_Letter') ?></p>
                                        <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.One_Number') ?></p>
                                        <p class="invalid"><span class="icon"></span> <?php echo lang('UI_Text.Special_Character') ?></p>
                                    </div>
                                </div>
                                <?php if (isset($validationpassEditUsers)) : ?>
                                    <div class=col-12 col-sm-4>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $validationpassEditUsers->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" name="id_user" value="<?php echo base64_encode($row['id_user']) ?>" />
                                <input type="hidden" name="clientid" value="<?php echo session()->get('client') ?>" />
                                <input type="hidden" name="adminuseredit" value="1" />
                                <button type="submit" class="btn btn-primary rounded-pill px-4" id="submitBtn" disabled>
                                    <?php echo lang('Buttons.Update_Password') ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-lg-12">
                        <div class="card profile-edit-card mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="profile-card-icon bg-soft-primary text-primary" style="width:36px;height:36px;font-size:16px;"><i class="mdi mdi-information-outline"></i></div>
                                    <h5 class="mb-0 fw-bold"><?php echo lang('UI_Text.Information') ?></h5>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <p class="fw-semibold mb-2"><?php echo lang('UI_Text.Trainer_Create_Administer') ?></p>
                                        <ul class="bullet-list">
                                            <li><?php echo lang('UI_Text.Courses') ?></li>
                                            <li><?php echo lang('UI_Text.Learning_Plan') ?></li>
                                            <li><?php echo lang('UI_Text.Certificates') ?></li>
                                            <li><?php echo lang('UI_Text.Games') ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <p class="fw-semibold mb-2"><?php echo lang('UI_Text.Admin_Create_Administer') ?></p>
                                        <ul class="bullet-list">
                                            <li><?php echo lang('UI_Text.Users') ?></li>
                                            <li><?php echo lang('UI_Text.User_Group') ?></li>
                                            <li><?php echo lang('UI_Text.Support_Tickets') ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $options = '';

                    $assigned = array_column($clientUserlevelData, 'fk_id_d');

                    if (!in_array('1', $clientarray)) {

                        if (!in_array(5, $assigned)) {
                            $options .= '<option value="5">' . lang('UI_Text.Trainers') . '</option>';
                        }

                        if (!in_array(44, $assigned)) {
                            $options .= '<option value="44">' . lang('Buttons.Admin') . '</option>';
                        }
                    } else {

                        if (in_array('6', $arrayuserlevel)) {

                            foreach ($userlevelData as $eachcategoryItem) {
                                $key = array_search(
                                    $eachcategoryItem['id_ua'],
                                    array_column($clientUserlevelData, 'fk_id_d')
                                );

                                if ($key === false) {
                                    $options .= '<option value="' . $eachcategoryItem['id_ua'] . '">'
                                        . $eachcategoryItem['name']
                                        . '</option>';
                                }
                            }
                        } elseif (in_array('2010', $arrayuserlevel)) {

                            if (!in_array(4, $assigned)) {
                                $options .= '<option value="4">' . lang('UI_Text.Project_Manager') . '</option>';
                            }
                            if (!in_array(7, $assigned)) {
                                $options .= '<option value="7">' . lang('UI_Text.Tasks') . '</option>';
                            }
                            if (!in_array(46, $assigned)) {
                                $options .= '<option value="46">' . lang('UI_Text.Developer') . '</option>';
                            }
                            if (!in_array(67, $assigned)) {
                                $options .= '<option value="67">' . lang('UI_Text.Quality_Control') . '</option>';
                            }
                            if (!in_array(5, $assigned)) {
                                $options .= '<option value="5">' . lang('UI_Text.Trainers') . '</option>';
                            }
                            if (!in_array(44, $assigned)) {
                                $options .= '<option value="44">' . lang('Buttons.Admin') . '</option>';
                            }
                        }
                    }
                    ?>

                    <?php if (!empty($options)) : ?>
                        <div class="card profile-edit-card mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="profile-card-icon bg-soft-primary text-primary" style="width:36px;height:36px;font-size:16px;"><i class="mdi mdi-account-group-outline"></i></div>
                                    <h5 class="mb-0 fw-bold"><?php echo lang('UI_Text.Assign_Access') ?></h5>
                                </div>
                                <form class="form-horizontal"
                                    action="<?= base_url('User_login/client_users/updateCategoryItem/' . base64_encode($row['id_user'])); ?>"
                                    method="POST">
                                    <?= csrf_field() ?>

                                    <div class="mb-3">
                                        <select name="userlevelItem" class="form-select">
                                            <option value="" disabled selected><?php echo lang('UI_Text.select') ?></option>
                                            <?= $options ?>
                                        </select>
                                    </div>

                                    <input type="hidden" name="clientid"
                                        value="<?= session()->get('client') ?>">

                                    <button type="submit"
                                        class="btn btn-outline-danger rounded-pill">
                                        <?php echo lang('UI_Text.Assign_Access') ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="card profile-edit-card">
                        <div class="card-body">
                            <table class="table access-level-table mb-0">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('UI_Text.Access_Level') ?></th>
                                        <th><?php echo lang('UI_Text.Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    if (!empty($clientUserlevelData)) : foreach ($clientUserlevelData as $eachuserData) {
                                            if (!in_array('1', $clientarray) && $eachuserData['category_name'] == 'client') {
                                            } else {
                                                if (strlen($eachuserData['Category_item'] ?? '') > 0) {
                                                    if ($eachuserData['fk_id_d'] == 3) continue;
                                    ?>
                                                    <tr>
                                                        <!-- <td value="<?php echo $eachuserData['fk_id_dc'] ?>"><?php echo ucfirst($eachuserData['category_name']); ?></td> -->
                                                        <td value="<?php echo $eachuserData['fk_id_d'] ?>"><?php echo isset($eachuserData['Category_item']) ? $eachuserData['Category_item'] : '' ?></td>
                                                        <input type="hidden" name="id_user" value="<?php echo $eachuserData['id_user'] ?>" />
                                                        <!-- <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php //echo base_url('User_login/client_users/deleteUserCategory/' . $eachuserData['id_du'] . '/' . $eachuserData['id_user'])
                                                                                                                                            ?>" class="btn btn-outline-danger waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span> Delete</a></td> -->
                                                        <td>
                                                            <form action="<?= base_url('User_login/client_users/deleteUserCategory') ?>" method="POST" style="display:inline;" onsubmit="return confirm('<?= lang('Alert.Aler_002') ?>');">
                                                                <!-- CSRF token -->
                                                                <?= csrf_field() ?>

                                                                <!-- Hidden fields for IDs -->
                                                                <input type="hidden" name="category_id" value="<?= $eachuserData['id_du'] ?>">
                                                                <input type="hidden" name="user_id" value="<?= $eachuserData['id_user'] ?>">

                                                                <button type="submit" class="btn btn-outline-danger rounded-pill btn-xs waves-effect waves-light">
                                                                    <?= lang('Buttons.Delete') ?>
                                                                </button>
                                                            </form>
                                                    </tr>
                                    <?php
                                                }
                                            }
                                        }
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const password = document.getElementById('password');
        const submitBtn = document.getElementById('submitBtn');

        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,64}$/;

        function validatePassword() {
            if (regex.test(password.value)) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        // run on typing
        password.addEventListener('input', validatePassword);

        // ✅ run on page load (IMPORTANT FIX)
        validatePassword();

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const passwordField = document.getElementById('password');
        const rules = document.querySelectorAll('.password-rules p');
        const segments = [
            document.getElementById('strengthSegment1'),
            document.getElementById('strengthSegment2'),
            document.getElementById('strengthSegment3')
        ];
        const strengthLabel = document.getElementById('strengthLabel');
        const strengthLevels = [{
            className: 'strength-weak',
            text: 'Weak',
            segments: 1
        }, {
            className: 'strength-medium',
            text: 'Medium',
            segments: 2
        }, {
            className: 'strength-strong',
            text: 'Strong',
            segments: 3
        }];

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

        // 🔹 Run on typing
        passwordField.addEventListener('input', function() {
            validatePassword(this.value);
        });

        // 🔥 IMPORTANT: Run on page load (for PHP generated password)
        validatePassword(passwordField.value);

        const toggleEditPassword = document.getElementById('toggleEditPassword');
        if (toggleEditPassword) {
            toggleEditPassword.addEventListener('click', function() {
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
<script>
    // const password = document.getElementById('password');
    const email = document.getElementById('email');
    const username = document.getElementById('username');

    [username].forEach(input => {
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