<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Support/Support_user/notificatoins'); ?>">Notifications</a></li>
                 
                </ol>
            </div>
            <h4 class="page-title">Create New Notification</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Support/Support_user/add_notificatoin_to_db') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Short Description <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="short_desc" placeholder="Short Description" value="" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input id="birthday" name="start_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo isset($row[0]['validity']) ? $row[0]['validity'] : '0000-00-00' ?>">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input id="birthday" name="end_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo isset($row[0]['validity']) ? $row[0]['validity'] : '0000-00-00' ?>">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Client Specific <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="client_specific" placeholder="Select Client" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Detailed Description</label>
                                <div>
                                    <input class="form-control" name="valid" type="hidden" />
                                    <textarea class="ckeditor" name="description" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    <?php echo  lang('Buttons.Create') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>