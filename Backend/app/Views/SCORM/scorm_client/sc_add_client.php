<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>Admin</li><b> &nbsp;> &nbsp;</b>
            <li><a href="<?php echo base_url('scorm_client') ?>">Scorm Client List</a></li><b> &nbsp;> &nbsp;</b>
    
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <h2>Add New CLient</h2>
            <div class="x_title">
                <div class="x_content">
                    <br />
                    <div class="block block-drop-shadow">
                        <div class="content controls">
                            <form class="form" action=" <?php echo base_url('scorm_client/sc_add_client'); ?>" method="POST"><?= csrf_field() ?>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label><?php echo lang('UI_Text.Name') ?></label>
                                    <input type="text" class="form-control" name="name" placeholder="<?php echo lang('UI_Text.Name') ?>" value="<?= set_value('name') ?>" />
                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label>URL</label>
                                    <input type="text" class="form-control" name="url" placeholder="URL" value="<?= set_value('url') ?>" />

                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label>Start date</label>
                                    <input id="birthday" name="start_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>

                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label>End date</label>
                                    <input id="birthday" name="end_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>

                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label>Course count</label>
                                    <input type="text" class="form-control" name="course_count" placeholder="No of courses" value="<?= set_value('course_count') ?>" />
                                </div>
                        </div>
                        <div class="form-group row" class="col-md-9 col-sm-9 ">
                            <?php if (isset($validation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="subjoin" value="1">
                            <input type="hidden" name="createdby" value=" ">
                            <button type="submit" class="btn btn-success block">
                                <i class="ace-icon fa fa-key bigger-110"></i> Save
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