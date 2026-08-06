<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('demo_client') ?>">Demo Clients</a></li><b> &nbsp;> &nbsp;</b>
        
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="x_panel">
                    <div class="block">
                        <?php if (session()->get('success')) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->get('success') ?>
                            </div>
                        <?php endif; ?>
                        <div class="header">
                            <h2>Edit Client List</h2>
                        </div>
                        <div class="content">
                            <form class="form" action="<?php echo base_url('demo_client/saveEditClientlist'); ?>" method="POST"><?= csrf_field() ?>
                                <div class="form-group col-md-12">
                                    <label>Client Name</label>
                                    <input type="text" class="form-control" name="client_name" placeholder="<?php echo lang('UI_Text.Name') ?>" value="<?php echo $row[0]['client_name'] ?>" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Validity</label>
                                    <input id="birthday" name="validity" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo isset($row[0]['validity']) ? $row[0]['validity'] : '0000-00-00' ?>">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>No of license</label>
                                    <input type="number" class="form-control" name="license" placeholder="License" value="<?= $row[0]['license'] ?>" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label>URL</label>
                                    <input type="text" class="form-control" name="url" placeholder="URL" value="<?= $row[0]['url'] ?>" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Start date</label>
                                    <input id="birthday" name="start_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo ($row[0]['start_date'] != NULL) ? date('m-d-Y', strtotime($row[0]['start_date'])) : ''; ?>">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>End date</label>
                                    <input id="birthday" name="end_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo ($row[0]['end_date'] != NULL) ? date('m-d-Y', strtotime($row[0]['end_date'])) : ''; ?>">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Access Type</label>
                                    <select name="type" class="form-control">
                                        <?php foreach ($dashboardaccesstype as $eachdashboardaccesstype) {
                                            if ($row[0]['type'] == $eachdashboardaccesstype['id_d']) { ?>
                                                <option selected='selected' value="<?php echo $eachdashboardaccesstype['id_d'] ?>"><?php echo $eachdashboardaccesstype['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $eachdashboardaccesstype['id_d'] ?>"><?= $eachdashboardaccesstype['name'] ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12" class="form-control">
                                    <?php if (isset($validation)) : ?>
                                        <div class=col-12 col-sm-4>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $validation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-warning block">
                                        <i class="ace-icon fa fa-key bigger-110"></i> Update
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