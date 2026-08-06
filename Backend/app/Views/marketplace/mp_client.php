<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php),
       matching mp_courses.php's redesign. */
    .mp-client-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .mp-client-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .mp-client-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .mp-client-card table tbody td {
        vertical-align: middle;
    }

    .mp-client-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .mp-client-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .mp-client-card .dataTables_length select,
    [data-bs-theme="dark"] .mp-client-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .mp-client-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo lang('UI_Text.Learning_Plan'); ?></a></li>
                    <!-- <li class="breadcrumb-item"><a href="<?php echo base_url($admin_page) ?>">Admin</a></li> -->
                       <li class="breadcrumb-item"><a href="<?php echo base_url('marketplace/Learning_dashboard/learning_courses') ?>"><?php echo lang('UI_Text.Learning_Plan_Details'); ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('Buttons.Assign_Clients'); ?> - <?php echo $mp_name; ?></h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <!-- start chat users-->
    <div class="col-md-6">
        <div class="card mp-client-card">
            <div class="card-body">
                <form class="form-horizontal"
                    action="<?php echo base_url('marketplace/admin/add_client_to_marketplace') ?>" method="post"
                    id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('Buttons.Clients'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="client_id" required>
                                <option value=""><?php echo lang('UI_Text.Select_Client'); ?></option>
                                <?php
                                foreach ($get_active_clients as $clients) {
                                    $key = array_search($clients['id_c'], array_column($get_client_by_id, 'id_c'));
                                    if (!empty($key) || $key === 0) {
                                    } else {
                                        echo '<option value="' . $clients['id_c'] . '">' . $clients['client_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Cost'); ?></label>
                        <div class="col-8 col-xl-9">
                            <input type="number" class="form-control" name="cost" placeholder="<?php echo lang('UI_Text.Enter_Cost'); ?>" min="0"
                                value="0" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Payment_Type'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select name="payment_type" class="form-control" required>
                                <option value=""><?php echo lang('UI_Text.Select_Payment_Type'); ?></option>
                                <option value="1"><?php echo lang('UI_Text.Direct_Payment'); ?></option>
                                <option value="2"><?php echo lang('UI_Text.System_Payment'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Billing_Cycle'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select name="billing_cycle" class="form-control" required>
                                <option value=""><?php echo lang('UI_Text.Select_Cycle'); ?></option>
                                <option value="1"><?php echo lang('UI_Text.One_Time'); ?></option>
                                <option value="2"><?php echo lang('UI_Text.Monthly'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Currency'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select name="currency" class="form-control" required>
                                <option value=""><?php echo lang('UI_Text.Select_Currency'); ?></option>
                                <option value="1">USD</option>
                                <option value="2">EUR</option>
                                <option value="3">INR</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Discount_Percent'); ?></label>
                        <div class="col-8 col-xl-9">
                            <input type="number" class="form-control" name="discount" placeholder="<?php echo lang('UI_Text.Enter_Discount_Percent'); ?>"
                                min="0" max="100" value="0" required>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <input type="hidden" name="mp_id" value="<?php echo $mp_id; ?>">
                        <div class="col-8 col-xl-9">
                            <button type="submit" id="submitButton"
                                class="btn btn-outline-success rounded-pill btn-xs waves-effect waves-light">
                                <?php echo lang('UI_Text.Add_Client'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="card mp-client-card">
            <div class="card-body">

                    <table id="mp-client-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr class="table-light">
                                <th>#</th>
                                <th><?php echo lang('UI_Text.Client'); ?></th>
                                <th><?php echo lang('UI_Text.Cost'); ?></th>
                                <th><?php echo lang('UI_Text.Type'); ?></th>
                                <th><?php echo lang('UI_Text.Cycle'); ?></th>
                                <th><?php echo lang('UI_Text.Currency'); ?></th>
                                <th><?php echo lang('UI_Text.Discount'); ?></th>
                                <th><?php echo lang('UI_Text.Delete'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($get_client_by_id as $eachClient) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $eachClient['client_name']; ?> </td>
                                    <td><?php echo $eachClient['cost']; ?></td>
                                    <td><?php if ($eachClient['payment_type'] == '2') {
                                            echo lang('UI_Text.System');
                                        } else {
                                            echo lang('UI_Text.Direct');
                                        }
                                        ?></td>
                                    <td><?php if ($eachClient['billing_cycle'] == '1') {
                                            echo lang('UI_Text.One_Time');
                                        } elseif ($eachClient['billing_cycle'] == '2') {
                                            echo lang('UI_Text.Monthly');
                                        } ?></td>
                                    <td><?php if ($eachClient['currency'] == '1') {
                                            echo "USD";
                                        } elseif ($eachClient['currency'] == '2') {
                                            echo "EUR";
                                        } elseif ($eachClient['currency'] == '3') {
                                            echo "INR";
                                        }
                                        ?></td>

                                    <td><?php echo $eachClient['discount']; ?> %</td>
                                    <td>
                                        <form action="<?php echo base_url('marketplace/admin/delete_marketplace_client') ?>"
                                            method="POST"
                                            onsubmit="return confirm('<?php echo lang('Alert.Aler_002'); ?>');">
                                            <input type="hidden" name="mp_cl_id"
                                                value="<?php echo $eachClient['mp_cl_id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light" title="<?php echo lang('Buttons.Delete'); ?>">
                                                <span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#mp-client-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, -1]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Clients'), 'js') ?>',
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