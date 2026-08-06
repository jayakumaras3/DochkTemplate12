<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href= "<?php echo base_url('scorm_client') ?>">Scorm Client List</a></li><b> &nbsp;> &nbsp;</b>
       
        </ol>
    </div>
</div>
<div class="row">
    <div class="x_panel">
        <h2><?php echo lang('UI_Text.DeletedUsers') ?></h2>
        <div class="x_title">
            <div class="x_content">
                <br />
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><?php echo lang('UI_Text.Username') ?></th>
                            <th><?php echo lang('UI_Text.Deleted') . ' ' . lang('UI_Text.Date') ?></th>
                            <th><?php echo lang('UI_Text.Activate') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($deleteUserlist as $eachdeletedUser) {
                        ?>
                            <tr>
                                <td><?php echo $eachdeletedUser['username']; ?></td>
                                <td><?php echo ($eachdeletedUser['updatedon'] != '')?date('m-d-Y', $eachdeletedUser['updatedon']):'';  ?></td>
                                <td><a href="<?php echo base_url('SCORM/scorm_users/activateuser/' . $eachdeletedUser['id_user']); ?>"><button type="submit" class="btn btn-sm btn-danger">Activate</button></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
