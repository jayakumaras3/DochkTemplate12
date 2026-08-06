<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header_title ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('marketplace/Learning_dashboard/learning_courses') ?>"><?php echo lang('Buttons.Learning Courses'); ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo $admin; ?></h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <!-- start chat users-->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('marketplace/admin/add_new_marketplace') ?>"
                    method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Name'); ?></label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="marketplace_name" placeholder="<?php echo lang('UI_Text.Name'); ?>" value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Duration'); ?></label>
                        <div class="col-8 col-xl-9">
                            <input type="number" class="form-control" name="duration" value="" placeholder="<?php echo lang('UI_Text.Duration'); ?>" min="0"
                                required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Mode'); ?></label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="mode" placeholder="<?php echo lang('UI_Text.Mode'); ?>" required>
                                <option value=""><?php echo lang('UI_Text.Select Mode'); ?></option>
                                <option value="1"><?php echo lang('UI_Text.Sequential'); ?></option>
                                <option value="2"><?php echo lang('UI_Text.Non_Sequential'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Description'); ?></label>
                        <div class="col-8 col-xl-9">
                            <textarea class="ckeditor" name="description" value="" placeholder="<?php echo lang('UI_Text.Description'); ?>"
                                required></textarea>
                        </div>
                    </div>
                    <!--             <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Remarks</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" placeholder="Remarks" name="remarks" value=""
                                required>
                        </div>
                    </div> -->
                    <input type="hidden" name="remarks" value="">
                    <input type="hidden" name="language" value="English">
                    <!-- <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Language</label>
                        <div class="col-8 col-xl-9">
                            <select name="language" class="form-control" placeholder="Language" required>
                                <option value="">- Select Language -</option>
                                <option value="English">English</option>
                                <option value="Spanish">Spanish</option>
                                <option value="French">French</option>
                                <option value="Russian">Russian</option>
                                <option value="Portuguese">Portuguese</option>
                                <option value="Bahasa">Bahasa</option>
                                <option value="Arabic">Arabic</option>
                                <option value="German">German</option>
                                <option value="Italian">Italian</option>
                                <option value="Japanese">Japanese</option>
                                <option value="Korean">Korean</option>
                            </select>
                        </div>
                    </div> -->

                    <!-- <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Type</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="type" placeholder="Type" required>
                                <option value="">- Select Type -</option>
                                <option value="1">Marketplace</option>
                                <option value="2">Learning Plan</option>
                            </select>
                        </div>
                    </div> -->

                    <input type="hidden" name="type" value="<?php echo $type; ?>">

                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" id="submitButton"
                                class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                <?php echo lang('Buttons.Submit'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="x_panel">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo lang('UI_Text.Name'); ?></th>
                                <th><?php echo lang('UI_Text.Type'); ?></th>
                                <?php if (session()->get('client') == 1) { ?>
                                    <th><?php echo lang('Buttons.Clients'); ?></th>
                                <?php } ?>
                                <th><?php echo lang('UI_Text.Courses'); ?></th>
                                <th><?php echo lang('UI_Text.Edit'); ?></th>
                                <th><?php echo lang('UI_Text.Delete'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($get_marketplace as $eachMarketplace) {
                                $j = $j + 1;
                                // if ($eachMarketplace['client_id'] == session()->get('client')) { 
                            ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $eachMarketplace['mp_name']; ?> </td>
                                    <td><?php echo ($eachMarketplace['type'] == '1') ? 'MP' : 'LP'; ?></td>
                                    <?php if (session()->get('client') == 1) { ?>
                                        <td>
                                            <form action="<?php echo base_url('marketplace/admin/edit_client') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="mp_name"
                                                    value="<?php echo $eachMarketplace['mp_name']; ?>">
                                                <input type="hidden" name="mp_id"
                                                    value="<?php echo $eachMarketplace['mp_id']; ?>">
                                                <button type="submit"
                                                    class="btn btn-outline-dark btn-xs waves-effect waves-light">
                                                    <?php echo $eachMarketplace['total_clients']; ?></button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                    <td>
                                        <!-- eachMarketplace -->
                                        <form action="<?php echo base_url('marketplace/admin/edit_courses') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="mp_name"
                                                value="<?php echo $eachMarketplace['mp_name']; ?>">
                                            <input type="hidden" name="mp_id"
                                                value="<?php echo $eachMarketplace['mp_id']; ?>">
                                            <?php
                                            echo ' <input type="hidden" name="type" value="1">';
                                            ?>
                                            <button type="submit"
                                                class="btn btn-outline-info btn-xs waves-effect waves-light">
                                                <?php echo $eachMarketplace['total_courses']; ?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('marketplace/admin/edit_marketplace') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="mp_name"
                                                value="<?php echo $eachMarketplace['mp_name']; ?>">
                                            <input type="hidden" name="mp_id"
                                                value="<?php echo $eachMarketplace['mp_id']; ?>">
                                            <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-square-edit-outline"></span> <?php echo lang('Buttons.Edit'); ?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('marketplace/admin/delete_marketplace') ?>"
                                            method="POST"
                                            onsubmit="return confirm('<?php echo lang('Alert.Aler_002'); ?>');">
                                            <input type="hidden" name="mp_name"
                                                value="<?php echo $eachMarketplace['mp_name']; ?>">
                                            <input type="hidden" name="mp_id"
                                                value="<?php echo $eachMarketplace['mp_id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-trash-can-outline"></span> <?php echo lang('Buttons.Delete'); ?></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php // }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
</div>
</div>