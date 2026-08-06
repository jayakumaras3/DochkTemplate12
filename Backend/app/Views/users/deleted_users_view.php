<?php $userlevel = session()->get('userlevel');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
$hasPersonalDataCol = in_array('2010', $arrayuserlevel);
?>
<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .deleted-users-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .deleted-users-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .deleted-users-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .deleted-users-card table tbody td {
        vertical-align: middle;
    }

    .deleted-users-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .deleted-users-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .deleted-users-card .dataTables_length select,
    [data-bs-theme="dark"] .deleted-users-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .deleted-users-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo lang('UI_Text.User_Management') ?></a></li>

                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.Inactive_Users') ?></h4>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="card deleted-users-card">
        <div class="card-body">
            <table id="deleted-users-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr class="table-light">
                        <th>#</th>
                        <th><?php echo lang('UI_Text.Name') ?></th>
                        <th><?php echo lang('UI_Text.Designation') ?></th>
                        <th><?php echo lang('UI_Text.Last_Working_Day') ?></th>
                        <th><?php echo lang('UI_Text.Deleted_By') ?></th>
                        <th><?php echo lang('UI_Text.Deleted') . ' ' . lang('UI_Text.Date') ?></th>
                        <?php if ($hasPersonalDataCol) { ?>
                            <th><?php echo lang('UI_Text.Personal_Data') ?></th>
                        <?php } ?>
                        <th><?php echo lang('UI_Text.Activate') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $j = 0;
                    foreach ($deleteUserlist as $eachdeletedUser) {
                        $j++;
                    ?>
                        <tr>
                            <td><?php echo $j; ?></td>
                            <td><?php echo $eachdeletedUser['name'] . ' ' . $eachdeletedUser['last_name']; ?></td>
                            <td><?php echo $eachdeletedUser['designation']; ?></td>
                            <td><?php echo $eachdeletedUser['LWD']; ?></td>
                            <td><?php echo $eachdeletedUser['deleted_name']; ?></td>
                            <td><?php echo ($eachdeletedUser['last_updated_on'] != '') ? date('m-d-Y', $eachdeletedUser['last_updated_on']) : ''; ?></td>
                            <?php if ($hasPersonalDataCol) { // etrack access ?>
                                <td>

                                    <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/view_personal_data'); ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="temp_user" value="<?php echo $eachdeletedUser['id_user']; ?>">


                                        <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light">
                                            <?php echo lang('UI_Text.Personal_Data') ?>
                                        </button>

                                    </form>
                                </td>
                            <?php } ?>

                            <td><a href="<?php echo base_url($header_link . '/activateuser/' . $eachdeletedUser['id_user'] . '/' . $cid); ?>"><button type="submit" class="btn btn-outline-danger rounded-pill btn-xs waves-effect waves-light"><?php echo lang('UI_Text.Activate') ?></button></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#deleted-users-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, <?= $hasPersonalDataCol ? '6, 7' : '6' ?>]
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
