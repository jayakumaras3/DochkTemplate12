<style>
    .switch span {
        width: 50px;
        height: 20px;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_list') ?>">Client List</a></li>

                </ol>
            </div>
            <h4 class="page-title">Settings</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>Module Access</h4>
                    <div class="x_title">
                        <div class="x_content">
                            <br />
                            <table class="table  table-sm table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Menu</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $menuData = array(
                                        // array("menu_id" => 1, "menu_name" => "My", "client_permission" => "My"),
                                        // array("menu_id" => 2, "menu_name" => "Holidays"),
                                        // array("menu_id" => 3, "menu_name" => "Projects"),
                                        // array("menu_id" => 4, "menu_name" => "Demos"),
                                        // array("menu_id" => 5, "menu_name" => "C4U Courses"),
                                        // array("menu_id" => 6, "menu_name" => "Aristo"),
                                        // array("menu_id" => 7, "menu_name" => "e-manual"),
                                        // array("menu_id" => 8, "menu_name" => "AR/VR/xAPI"),
                                        // array("menu_id" => 9, "menu_name" => "Manage"),
                                        // array("menu_id" => 10, "menu_name" => "Admin"),
                                        // array("menu_id" => 11, "menu_name" => "SCORM"),
                                        // array("menu_id" => 12, "menu_name" => "Aristo"),
                                        array("menu_id" => 13, "menu_name" => "Learning Plan"),
                                        array("menu_id" => 14, "menu_name" => "Marketplace"),
                                        array("menu_id" => 15, "menu_name" => "Certification"),
                                        array("menu_id" => 16, "menu_name" => "Gamification"),
                                        array("menu_id" => 17, "menu_name" => "Course Groups"),
                                        array("menu_id" => 18, "menu_name" => "Certificate"),
                                        array("menu_id" => 19, "menu_name" => "Reports"),
                                        array("menu_id" => 20, "menu_name" => "favorites"),
                                    );
                                    $i = 0;
                                    foreach ($menuData as $eachmenu) {
                                        $i = $i + 1;
                                        $id = $eachmenu['menu_id'];
                                        $client_id = $cid;
                                        $db = \Config\Database::connect();
                                        $builder = $db->table('menu_clientaccess as mc');
                                        $builder->select('mc.client_permission,mc.menu_id');
                                        $builder->where('mc.menu_id', $id);
                                        $builder->where('mc.client_id', $client_id);
                                        $data = $builder->get();
                                        if (count($data->getResultArray()) == 1) {
                                            $result  = $data->getRowArray();
                                            if (($result['client_permission'] == 1) && isset($result['menu_id'])) {
                                                $status = "checked";
                                            } else {
                                                $status = '';
                                            }
                                        } else {
                                            $status = '';
                                        } ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $eachmenu['menu_name'] ?></td>
                                            <td>
                                                <form class="form-horizontal" id="givepermission"><?= csrf_field() ?>
                                                    <input type="hidden" name="client_id" value="<?php echo $cid ?>">
                                                    <input type="hidden" name="menu_id" value="<?php echo $eachmenu['menu_id'] ?>">
                                                    <label>
                                                        <input type="checkbox" onchange='switchStatus(<?php echo $id; ?>,this,<?php echo $cid; ?>)' data-toggle='toggle' <?php echo $status ?> class="skip" />
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
        
        var theStatus = $(status).prop('checked') ? 1 : 0;

        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>';

        $.ajax({
            url: "<?= base_url('Settings/settings/clientaccesspermission') ?>",
            type: "POST",
            data: {
                menu_id: theID,
                client_permission: theStatus,
                client_id: thecID,
                [csrfName]: csrfHash
            },
            success: function(data) {
                console.log("Success");
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }
</script>