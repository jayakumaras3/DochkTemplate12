<?php $data['cid'] = $clientid;
$cid = session()->set($data); ?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <!-- <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_list'); ?>">Client List</a></li> -->
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link); ?>">Users List</a></li>

                </ol>
            </div>
            <h4 class="page-title">Profile Edit</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4><?php echo lang('UI_Text.ChangeDetails') ?></h4>
                <form class="form-horizontal" action="<?php echo base_url($form_link . $row['id_user'] . '/' . $clientid); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Frist <?php echo lang('UI_Text.Name') ?></label>
                        <div class="col-8 col-xl-9">
                            <input class="form-control" type="text" name="name" maxlength="50" value="<?php echo $row['name']; ?>">

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Last <?php echo lang('UI_Text.Name') ?></label>
                        <div class="col-8 col-xl-9">
                            <input class="form-control" type="text" name="last_name" maxlength="50" value="<?php echo $row['last_name']; ?>">

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Email') ?></label>
                        <div class="col-8 col-xl-9">
                            <input class="form-control" type="text" id="username" name="username" value="<?php echo $row['user']; ?>" placeholder="email">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Select Timezone</label>
                        <div class="col-8 col-xl-9">
                            <select name="timezone" class="form-control">
                                <?php if (!empty($timezone)) {
                                    foreach ($timezone as $eachtimezone) {
                                        if ($row['timezone'] == $eachtimezone['id_t']) { ?>
                                            <option selected='selected' value="<?php echo $eachtimezone['id_t'] ?>"><?php echo $eachtimezone['timezone_name'] . ' - ' . $eachtimezone['timezone_pname'] ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $eachtimezone['id_t'] ?>"><?php echo $eachtimezone['timezone_name'] . ' - ' . $eachtimezone['timezone_pname'] ?></option>
                                <?php }
                                    }
                                } ?>

                            </select>
                        </div>
                    </div>
                    <?php if (isset($validationEditUsers)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validationEditUsers->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" name="id_user" value="<?php echo $row['id_user'] ?>" />
                            <input type="hidden" name="clientid" value="<?php echo $clientid ?>" />
                            <input type="hidden" name="adminuseredit" value="1" />
                            <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light" type="button" id="submitButton"> Submit </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body ">
                <?php if (session()->get('passuccess')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->get('passuccess') ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->get('passerror')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->get('passerror') ?>
                    </div>
                <?php endif; ?>

                <form class="form" action="<?php echo base_url($form_link_1 . $row['id_user'] . '/' . $clientid); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Password') ?></label>
                        <div class="col-8 col-xl-9">
                            <input class="form-control" type="text" id="password" name="password" maxlength="30" value="<?php echo $temppass; ?>">
                            <div class="password-rules compact">
                                <p class="invalid"><span class="icon"></span> Minimum 8 characters</p>
                                <p class="invalid"><span class="icon"></span> One capitalized letter</p>
                                <p class="invalid"><span class="icon"></span> One lowercase letter</p>
                                <p class="invalid"><span class="icon"></span> One number</p>
                                <p class="invalid"><span class="icon"></span> Special character (!@#$%^&*()_+-=[]{}|;:<>?)</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <?php if (isset($passvalidationEditUsers)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $passvalidationEditUsers->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" name="id_user" value="<?php echo $row['id_user'] ?>" />
                            <input type="hidden" name="clientid" value="<?php echo $clientid ?>" />
                            <input type="hidden" name="adminuseredit" value="1" />
                            <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light" type="button" id="submitBtn" disabled>
                                Submit
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <?php $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel); ?>
        <?php if (in_array('6', $arrayuserlevel)) { ?>
            <div class="card">
                <div class="card-body ">
                    <form class="form-horizontal" action="<?php echo base_url($form_link_4 . $row['id_user'] . '/' . $clientid); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Client') ?></label>
                            <div class="col-8 col-xl-9">
                                <select name="clientItem" class="form-control">
                                    <?php foreach ($clientData as $eachcategoryItem) { ?>
                                        <option value="<?php echo $eachcategoryItem['id_c'] ?>"><?php echo $eachcategoryItem['client_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <?php if (isset($validationData)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validationData->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="justify-content-end row">
                                <div class="col-8 col-xl-9">
                                    <input type="hidden" name="clientid" value="<?php echo $clientid ?>" />
                                    <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light" id="submitButton">
                                        Update Client
                                    </button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        <?php } ?>

        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url($form_link_2 . $clientid .  '/' . $row['id_user']); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Userlevel') ?></label>
                        <div class="col-8 col-xl-9">

                            <select name="userlevelItem" class="form-control">
                                <?php if (!in_array('6', $arrayuserlevel)) { ?>
                                    <!-- <option value="3">Normal Users</option> -->
                                    <option value="5">Trainers</option>
                                    <option value="44">Client Admin</option>
                                    <option value="45">Client Reviewer</option>
                                    <?php } else {

                                    foreach ($userlevelData as $eachcategoryItem) {
                                        $key = array_search($eachcategoryItem['id_ua'], array_column($clientUserlevelData, 'fk_id_d'));
                                        if (!empty($key) || $key === 0) {
                                        } else { ?> ?>

                                            <option value="<?php echo $eachcategoryItem['id_ua'] ?>"><?php echo $eachcategoryItem['name'] ?></option>
                                <?php }
                                    }
                                }

                                ?>
                            </select>

                        </div>
                    </div>

                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" name="id_user" value="<?php echo $row['id_user'] ?>" />
                            <input type="hidden" name="clientid" value="<?php echo $clientid ?>" />
                            <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light" id="submitButton">
                                <?php echo lang('Buttons.Add') . ' ' . lang('UI_Text.Userlevel') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-body ">
            <h4>Last Login</h4>
            <span><?php echo $lastLoginTime; ?></span>

        </div>
    </div>

    <div class="card">
        <div class="card-body ">
            <h4>View</h4>
            <table cellpadding="0" cellspacing="0" width="100%" class="table  table-sm table-bordered table-striped ">
                <thead>
                    <tr>

                        <th><?php echo lang('UI_Text.Category') ?></th>
                        <th><?php echo lang('UI_Text.Category') . ' ' . lang('UI_Text.Item') ?></th>
                        <th><?php echo lang('UI_Text.Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clientUserlevelData)) : foreach ($clientUserlevelData as $eachuserData) { ?>
                            <tr>
                                <?php if ($eachuserData['category_name'] != '') {
                                    if ($eachuserData['fk_id_d'] == 3) continue; ?>
                                    <td value="<?php echo $eachuserData['fk_id_dc'] ?>"><?php echo $eachuserData['category_name'] ?></td>
                                    <td value="<?php echo $eachuserData['fk_id_d'] ?>"><?php echo isset($eachuserData['Category_item']) ? $eachuserData['Category_item'] : '' ?></td>
                                    <input type="hidden" name="id_user" value="<?php echo $eachuserData['id_user'] ?>" />


                                    <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url($form_link_3 . $eachuserData['id_du'] . '/' . $eachuserData['id_user'] . '/' . $clientid) ?>" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></a></td>
                                <?php } ?>

                            </tr>

                    <?php }
                    endif; ?>
                </tbody>
            </table>
            <!-- <table cellpadding="0" cellspacing="0" width="100%" class="table  table-sm table-bordered table-striped ">

                <tbody>
                    <tr>
                        <?php //if ($eachuserData['client_name'] != '') { 
                        ?>
                            <th><?php // echo lang('UI_Text.Client') 
                                ?></th>
                            <td><?php //echo $eachuserData['client_name'] 
                                ?></td>
                        <?php // } 
                        ?>

                    </tr>


                </tbody>
            </table> -->
        </div>
    </div>
</div>
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

        validatePassword();

    });
</script>
<script>
    const password = document.getElementById('password');
    const username = document.getElementById('username');

    [password, username].forEach(input => {
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