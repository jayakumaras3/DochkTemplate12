<?php $sessionclient = session()->get('client');
$arrayclient  = explode(',', $sessionclient);
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <?php $userlevel = session()->get('userlevel');
                $arrayuserlevel  = explode(',', $userlevel);

                if ($clientlicense[0]['user_count'] <=  $clientlicense[0]['license']) {
                    $disablebutton = '';
                ?>

                    <div class="modal fade" id="addUserModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content add-user-modal">

                                <div class="modal-header border-0 pb-0">
                                    <div>
                                        <h5 class="modal-title fw-bold"><?php echo lang('Buttons.Add_User') ?></h5>
                                        <p class="text-muted font-13 mb-0">Fill in the details below to create a new user.</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body pt-2">

                                    <?php if (isset($validation)) : ?>
                                        <div class="alert alert-danger">
                                            <?= $validation->listErrors() ?>
                                        </div>
                                    <?php endif; ?>

                                    <form id="addUserForm" action="<?= base_url('User_login/client_users/addclientregister') ?>" method="POST"><?= csrf_field() ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.First_Name') ?> <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" placeholder="Enter first name"
                                                value="<?= set_value('name') ?>" required minlength="3">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Last_Name') ?> <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control" placeholder="Enter last name"
                                                value="<?= set_value('last_name') ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Email') ?> <span class="text-danger">*</span></label>
                                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address"
                                                value="<?= set_value('email') ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Password') ?> <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <input type="password" id="password" name="password" class="form-control pe-5" placeholder="Enter password"
                                                    value="<?= $temppass ?>" required>
                                                <button type="button" id="togglePassword" class="btn btn-link text-muted p-0 position-absolute top-50 end-0 translate-middle-y me-3" tabindex="-1">
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

                                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                        <input type="hidden" name="app_username" value="">
                                        <input type="hidden" name="timezone" value="6">
                                        <input type="hidden" name="lang" value="en">
                                        <input type="hidden" name="subjoin" value="1">
                                        <input type="hidden" name="createdby" value="">
                                    </form>
                                </div>

                                <div class="modal-footer border-top">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                        <?php echo lang('Buttons.Cancel') ?>
                                    </button>
                                    <button type="submit" form="addUserForm" class="btn btn-primary rounded-pill px-4">
                                        <?php echo lang('Buttons.Add_User') ?>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <style>
                        .add-user-modal {
                            border-radius: 18px;
                            border: none;
                            box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
                        }

                        .add-user-modal .form-control {
                            border-radius: 10px;
                            padding: 0.55rem 0.9rem;
                        }
                    </style>


                <?php
                } else {
                    $disablebutton = 'style="display: none"';
                    $disablemsg =  '<p>Note: Maximum users reached</p>';
                }
                ?>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.User_Management') ?></h4>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    /* Tile design matching my_training's summary tiles */
    .summary-tile.card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-tile.card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.09), 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .summary-tile .card-body {
        padding: 0.9rem 1rem;
    }

    .summary-tile .avatar-lg {
        border-radius: 12px;
        height: 2.5rem;
        width: 2.5rem;
    }

    .summary-tile .avatar-lg i {
        font-size: 16px !important;
    }

    .summary-tile h3 {
        font-size: 1.25rem;
        margin-bottom: 0.15rem;
    }

    .summary-tile p {
        font-size: 0.7rem;
        margin-bottom: 0;
    }

    .summary-tile.tile-success {
        background: linear-gradient(135deg, #ffffff 0%, #e9f9f0 100%);
    }

    .summary-tile.tile-warning {
        background: linear-gradient(135deg, #ffffff 0%, #fff6e0 100%);
    }

    .summary-tile.tile-danger {
        background: linear-gradient(135deg, #ffffff 0%, #fdecec 100%);
    }

    .summary-tile.tile-purple {
        background: linear-gradient(135deg, #ffffff 0%, #efeaff 100%);
    }

    .summary-tile.tile-cyan {
        background: linear-gradient(135deg, #ffffff 0%, #e6f8fc 100%);
    }

    .summary-tile.tile-orange {
        background: linear-gradient(135deg, #ffffff 0%, #ffe9e0 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-success {
        background: linear-gradient(135deg, #232b36 0%, #17301f 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-warning {
        background: linear-gradient(135deg, #232b36 0%, #3a2f10 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-danger {
        background: linear-gradient(135deg, #232b36 0%, #3a1f22 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-purple {
        background: linear-gradient(135deg, #232b36 0%, #241c44 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-cyan {
        background: linear-gradient(135deg, #232b36 0%, #123138 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-orange {
        background: linear-gradient(135deg, #232b36 0%, #3a2416 100%);
    }

    [data-bs-theme="dark"] .summary-tile h3.text-dark {
        color: #f3f7f9 !important;
    }

    .bg-soft-orange {
        background-color: rgba(255, 112, 67, 0.15) !important;
    }

    .text-orange {
        color: #ff7043 !important;
    }

    .border-orange {
        border-color: #ff7043 !important;
    }
</style>

<div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-2">
    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-cyan">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg bg-soft-info border-info border">
                            <i class="fe-users font-22 avatar-title text-info"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format($total_users_count ?? 0); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Total_Users'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-success">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg bg-soft-success border-success border">
                            <i class="fe-user-check font-22 avatar-title text-success"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format($active_users_count ?? 0); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Active_Users'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-danger">
            <div class="card-body">
                <form action="<?php echo base_url('User_login/client_users/deleted_userslist'); ?>" method="POST" class="m-0"><?= csrf_field() ?>
                    <input type="hidden" name="cid" value="<?php echo $arrayclient[0]; ?>">
                    <div class="row" style="cursor:pointer;" onclick="this.closest('form').submit();">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-danger border-danger border">
                                <i class="fe-user-x font-22 avatar-title text-danger"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format($inactive_users_count ?? 0); ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Inactive_Users'); ?></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-purple">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg bg-soft-primary border-primary border">
                            <i class="fe-shield font-22 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format($admin_users_count ?? 0); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Admins'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-warning">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg bg-soft-warning border-warning border">
                            <i class="fe-briefcase font-22 avatar-title text-warning"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format($trainer_users_count ?? 0); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Trainers'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <?php if ($clientlicense[0]['user_count'] <= $clientlicense[0]['license']) : ?>
            <div class="card bg-pattern">
                <div class="card-body">
                    <div class="row" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        
                        <div class="col-12  mb-1">
                            <div class="text-end">
                                 <h3 class=" mb-1 text-truncate"><?php echo lang('Buttons.Add_User'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end widget-rounded-circle-->
        <?php else : ?>
            <div class="widget-rounded-circle card summary-tile" title="<?php echo lang('UI_Text.Maximum_Users_Reached') ?>">
                <div class="card-body">
                    <div class="row" style="opacity:.5;">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-secondary border-secondary border">
                                <i class="fe-user-plus font-22 avatar-title text-secondary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><i class="mdi mdi-plus-circle-outline"></i></h3>
                                <p class="text-muted mb-1 text-truncate"><?php echo lang('Buttons.Add_User'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end widget-rounded-circle-->
        <?php endif; ?>
    </div> <!-- end col-->
</div>

<?php if (!empty($usertable)) : ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card users-table-card">
                <div class="card-body">
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <form action="<?php echo base_url('User_login/client_users/importUsers'); ?>" method="POST" class="m-0"><?= csrf_field() ?>
                            <input type="hidden" name="cid" value="<?php echo $arrayclient[0]; ?>">
                            <button type="submit" class="btn btn-outline-dark btn-xs rounded-pill waves-effect waves-light">
                                <i class="icon-cloud-upload me-1"></i> <?php echo lang('Buttons.Import_Bulk_User') ?>
                            </button>
                        </form>
                        <form action="<?php echo base_url('User_login/client_users/exportUsers'); ?>" method="POST" class="m-0"><?= csrf_field() ?>
                            <input type="hidden" name="cid" value="<?php echo $arrayclient[0]; ?>">
                            <button type="submit" class="btn btn-outline-dark btn-xs rounded-pill waves-effect waves-light">
                                <i class="icon-cloud-download me-1"></i> <?php echo lang('Buttons.Export_Users') ?>
                            </button>
                        </form>
                    </div>

                    <table id="users-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr class="table-light">
                                <th class="center">#</th>
                                <th><?php echo lang('UI_Text.Username') ?></th>
                                <th><?php echo lang('UI_Text.Name') ?></th>
                                <th><?php echo lang('UI_Text.Access') ?></th>
                                <th><?php echo lang('UI_Text.Last_Active') ?></th>
                                <th><?php echo lang('UI_Text.Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sno = 0;
                            foreach ($usertable as $k) {
                                if ($k['clientid'] == $arrayclient[0]) {
                                    $sno++;
                                    $rowClass = ($sno % 2 != 0) ? 'table-info' : '';
                                    ?>
                                    <tr class="<?php //echo $rowClass; ?>">
                                        <td class="center"><?php echo $sno; ?></td>
                                        <td><?php echo isset($k['username']) ? $k['username'] : $k['email']; ?></td>
                                        <td><?php echo $k['name'] . ' ' . $k['last_name'] ?></td>
                                        <td title="<?php echo htmlspecialchars($k['userlevel'] ?? '', ENT_QUOTES); ?>">
                                            <?php
                                            $text = '';
                                            if (!empty($k['userlevel'])) {
                                                // Convert comma-separated string to array
                                                $levels = array_map('trim', explode(',', $k['userlevel']));

                                                // Remove "Normal Users" if it exists
                                                $levels = array_diff($levels, ['Normal']);

                                                // Convert back to string
                                                $text = implode(', ', $levels);
                                            }
                                            echo strlen($text) > 30
                                                ? substr($text, 0, 30) . '...'
                                                : $text;
                                            ?>
                                        </td>
                                        <td><?php echo ($k['dateandtime'] != '') ? date("m-d-Y", ($k['dateandtime'])) : ''; ?></td>
                                        <?php $temp_id = base64_encode($k['id_user']);
                                        ?>
                                        <?php
                                        $deleteConfirmText = sprintf(lang('Alert.Aler_020'), trim($k['name'] . ' ' . $k['last_name']));
                                        ?>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="<?php echo base_url('User_login/client_users/editUsers/' . $temp_id) ?>" class="btn btn-outline-warning waves-effect btn-xs rounded-pill waves-light"><?php echo lang('Buttons.Edit') ?></a>
                                                <form method="POST" action="<?= base_url('User_login/client_users/deleteUsers/' . $k['id_user']) ?>" onsubmit="return confirm(<?php echo htmlspecialchars(json_encode($deleteConfirmText), ENT_QUOTES); ?>)" class="mb-0">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-outline-danger waves-effect btn-xs rounded-pill waves-light"><?= lang('Buttons.Delete') ?></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                            <?php  }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .users-table-card table.dataTable thead th {
            border-bottom: 2px solid #eef2f7;
            color: #6c757d;
            font-weight: 700;
            white-space: nowrap;
        }

        [data-bs-theme="dark"] .users-table-card table.dataTable thead th {
            border-bottom-color: #36404a;
            color: #cedeef;
        }

        .users-table-card table.dataTable tbody td {
            vertical-align: middle;
        }

        .users-table-card .dataTables_length select {
            border-radius: .5rem;
            border: 1px solid #dee2e6;
            padding: .25rem 1.75rem .25rem .6rem;
        }

        .users-table-card .dataTables_filter input {
            border-radius: 2rem;
            border: 1px solid #dee2e6;
            padding: .4rem .75rem;
            min-width: 260px;
        }

        [data-bs-theme="dark"] .users-table-card .dataTables_length select,
        [data-bs-theme="dark"] .users-table-card .dataTables_filter input {
            border-color: #424e5a;
        }

        .users-table-card .pagination .page-link {
            border-radius: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#users-datatable').DataTable({
                responsive: true,
                pagingType: 'simple_numbers',
                order: [],
                columnDefs: [{
                    orderable: false,
                    searchable: false,
                    targets: [0, 5]
                }],
                language: {
                    search: '_INPUT_',
                    searchPlaceholder: '<?= esc(lang('UI_Text.Search_Users'), 'js') ?>',
                    lengthMenu: '_MENU_',
                    info: '<?= esc(lang('UI_Text.Datatable_Info'), 'js') ?>',
                    infoEmpty: '<?= esc(lang('UI_Text.Datatable_Info_Empty'), 'js') ?>',
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
            });
        });
    </script>
<?php else : ?>
    <div class="persistent-warning">
        <div class="danger-text">
            <?php echo lang('UI_Text.No_Results_Found'); ?>
        </div>
    </div>
<?php endif; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($validation)) : ?>
            var modal = new bootstrap.Modal(document.getElementById('addUserModal'));
            modal.show();
        <?php endif; ?>
    });
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

        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const isHidden = passwordField.type === 'password';
                passwordField.type = isHidden ? 'text' : 'password';
                this.querySelector('i').className = isHidden ? 'mdi mdi-eye-off-outline font-18' : 'mdi mdi-eye-outline font-18';
            });
        }

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