<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
if (!in_array('4154', $arrayuserlevel)) {
    header('Location:' . base_url('my_training'));
    exit();
}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/ITSupport/assets'); ?>">
                            IT Assets
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                IT Asset Details - <?php if ($asset_desc) {
                    echo $asset_desc[0]['assetdesc'];
                } ?>
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/add_asset_details'); ?>"
                    method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-1">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Identifier</label>
                        <div class="col-12 col-xl-12">
                            <input type="text" name="identifier" class="form-control" required value="">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Description</label>
                        <div class="col-12 col-xl-12">
                            <input type="text" name="description" class="form-control" required value="">
                        </div>
                    </div>

                    <div class="row mb-1">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Department</label>
                        <div class="col-12 col-xl-12">
                            <select name="department" class="form-control">
                                <option value="1">Learning</option>
                                <option value="2">US</option>
                                <option value="3">Finance</option>
                                <option value="4">HR</option>
                                <option value="5">Others</option>
                                <option value="6">Pool</option>
                                <option value="7">IT Store</option>
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" name='asset_id' value='<?php echo $assetid; ?>'>
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light"
                                id="submitButton">
                                Create New <?php if ($asset_desc) {
                                    echo $asset_desc[0]['assetdesc'];
                                } ?> Asset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <table id="searchdatatable" class="table table-bordered table-striped ">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Identifier</th>
                            <th>Description</th>
                            <th>Department</th>
                            <th>With</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($asset_details) {
                            $j = 0;
                            foreach ($asset_details as $assets) {
                                $j++;
                                ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $assets['fin_identifier']; ?></td>
                                    <td><?php echo $assets['description']; ?></td>
                                    <td><?php $department = $assets['department'];
                                    switch ($department) {
                                        case 1:
                                            echo 'Learning';
                                            break;
                                        case 2:
                                            echo 'US';
                                            break;
                                        case 3:
                                            echo 'Finance';
                                            break;
                                        case 4:
                                            echo 'HR';
                                            break;
                                        case 5:
                                            echo 'Others';
                                            break;
                                        case 6:
                                            echo 'Pool';
                                            break;
                                        case 7:
                                            echo 'IT Store';
                                            break;
                                    }
                                    ?></td>
                                    <td><?php echo $assets['name']; ?></td>

                                    <td>
                                        <form class="form-horizontal"
                                            action="<?php echo base_url('etrack/ITSupport/edit_assets'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="et_ass_det_id"
                                                value="<?php echo $assets['et_ass_det_id']; ?>">
                                            <button class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>