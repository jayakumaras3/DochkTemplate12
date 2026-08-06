<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Site Access</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Access/accessController/addNewAccess'); ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <?= csrf_field() ?>
                    <div class="col-lg-12 mb-2">
                        <input name="name" class="form-control" placeholder="Name" type="text" required="required">
                    </div>
                    <div class="col-lg-12 mb-2">
                        <input name="id_ua" class="form-control" placeholder="ID" type="number" required="required">
                    </div>
                    <div class="col-lg-12 mb-2">
                        <select name="type" class="form-control">
                            <option value="2">User Level</option>
                            <option value="3">Modules</option>
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-outline-primary waves-effect waves-light form-control">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <table class="table  table-sm ">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th><?= lang('UI_Text.Name') ?></th>
                            <th><?= lang('UI_Text.Type') ?></th>
                            <th>Access ID</th>
                            <th><?= lang('UI_Text.Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($userlevelData as $eachcategoryItem) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $eachcategoryItem['name'] ?></td>
                                <td><?php $fk_id_dc = $eachcategoryItem['fk_id_dc'];
                                    if ($fk_id_dc == 2) echo "User";
                                    if ($fk_id_dc == 3) echo "Modules";
                                    ?></td>
                                <td><?php echo $eachcategoryItem['id_ua'] ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Access/accessController/view_access_users'); ?>" method="POST"><?= csrf_field() ?>
                                         <input type="hidden" name="access_id" value="<?php echo $eachcategoryItem['id_ua'] ?>">
                                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">View</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>