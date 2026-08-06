<?= $this->include('templates/header_view'); ?>
<style>
    .switch span {
        width: 50px;
        height: 20px;
    }
</style>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>/demo_client">Demo Client List</a></li><b> &nbsp;> &nbsp;</b>
         
            </ol>


            <div class="x_panel">
                <h2>SETTINGS</h2>
                <div class="x_title">
                    <div class="x_content">
                        <br />
                        <div class="content">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Menu</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // print_r($menuData);
                                    $i = 0;
                                    foreach ($menu as $eachmenu) {
                                        // print_r($eachmenu['client_permission']);
                                        $i = $i + 1;
                                        $id = $eachmenu['menu_id'];
                                        $status = ($eachmenu['client_permission'] == 1) ? "checked" : ""; ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $eachmenu['menu_name'] ?></td>
                                            <td>
                                                <form class="form-horizontal" id="givepermission">
                                                    <input type="hidden" name="client_id" value="<?php echo $cid ?>">
                                                    <input type="hidden" name="menu_id" value="<?php echo $eachmenu['menu_id'] ?>">
                                                    <label>
                                                        <input type="checkbox"  onchange='switchStatus(<?php echo $id; ?>,this,<?php echo $cid; ?>)' data-toggle='toggle' <?php echo $status ?> class="skip" />
                                                        <span></span>
                                                    </label>
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
        </div>
    </div>
</div>
<script>
    function switchStatus(id, status, cid) {
        var theID = id;
        var thecID = cid
        var theStatus = $(status).prop('checked');
        //console.log(theStatus);
        if (theStatus) {
            theStatus = 1;
        } else {
            theStatus = 0;
        }
        $.ajax({
            url: "<?php echo base_url('Settings/settings/clientaccesspermission') ?>",
            type: "POST",
            data: {
                menu_id: theID,
                client_permission: theStatus,
                client_id: thecID
            },
            cache: false,
            success: function(data) {
                // alert("checked");
            }
        });
    }
</script>