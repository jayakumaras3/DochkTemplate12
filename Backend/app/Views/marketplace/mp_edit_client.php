<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url($header_link) ?>"><?php echo $header_title ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($admin_page) ?>"><?php echo lang('Buttons.Admin'); ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.Client'); ?> - <?php echo $mp_name; ?></h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <!-- start chat users-->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal"
                    action="<?php echo base_url('marketplace/admin/edit_client_to_marketplace') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('Buttons.Clients'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="client_id" required>
                                <option value=""><?php echo lang('UI_Text.Select_Client'); ?></option>
                                <?php foreach ($get_active_clients as $clients): ?>
                                    <option value="<?= $clients['id_c']; ?>" <?= ($row['client_id'] == $clients['id_c']) ? 'selected' : ''; ?>>
                                        <?= $clients['client_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Cost'); ?></label>
                        <div class="col-8 col-xl-9">
                            <input type="number" class="form-control" name="cost" placeholder="<?php echo lang('UI_Text.Enter_Cost'); ?>" min="0"
                                value="<?php echo isset($row['cost']) ? $row['cost'] : '0'; ?>"
                                required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Payment_Type'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select name="payment_type" class="form-control" required>
                                <option value="1" <?php echo ($row['payment_type'] == 1) ? 'SELECTED' : '' ?>>
                                    <?php echo lang('UI_Text.Direct_Payment'); ?></option>
                                <option value="2" <?php echo ($row['payment_type'] == 2) ? 'SELECTED' : '' ?>><?php echo lang('UI_Text.System_Payment'); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Billing_Cycle'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select name="billing_cycle" class="form-control" required>
                                <option value="1" <?php echo ($row['billing_cycle'] == 1) ? 'SELECTED' : '' ?>><?php echo lang('UI_Text.One_Time'); ?>
                                </option>
                                <option value="2" <?php echo ($row['billing_cycle'] == 2) ? 'SELECTED' : '' ?>><?php echo lang('UI_Text.Monthly'); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Currency'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select name="currency" class="form-control" required>
                                <option value="1" <?php echo ($row['currency'] == 1) ? 'SELECTED' : '' ?>>USD</option>
                                <option value="2" <?php echo ($row['currency'] == 2) ? 'SELECTED' : '' ?>>EUR</option>
                                <option value="3" <?php echo ($row['currency'] == 3) ? 'SELECTED' : '' ?>>INR</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Discount_Percent'); ?></label>
                        <div class="col-8 col-xl-9">
                            <input type="number" class="form-control" name="discount" placeholder="<?php echo lang('UI_Text.Enter_Discount_Percent'); ?>"
                                min="0" max="100"
                                value="<?php echo isset($row['discount']) ? $row['discount'] : '0'; ?>" required>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <input type="hidden" name="mp_id" value="<?php echo $mp_id; ?>">
                        <input type="hidden" name="mp_cl_id" value="<?php echo $row['mp_cl_id']; ?>">
                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;"
                                class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                <?php echo lang('UI_Text.Edit_Client'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>