<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
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
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                IT Softwares
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/add_software'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Software Type</label>
                        <div class="col-12 col-xl-12">
                            <input type="text" name="software" class="form-control" required value="">
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light" id="submitButton">
                                Create New Software
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
                <table class="table table-bordered table-striped ">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Software Type</th>
                            <th>Details</th>
                             <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($all_softwares as $software) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $software['soft_description']; ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/view_softwares'); ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="soft_id" value="<?php echo $software['soft_id']; ?>">
                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi mdi-eye-outline"></span></button>
                                    </form>
                                </td>
                                <td>
                                      <form action="<?= base_url('etrack/ITSupport/delete_softwares') ?>" method="POST"><?= csrf_field() ?>
                                         <input type="hidden" name="soft_id" value="<?php echo $software['soft_id']; ?>">
                                        <input type="hidden" name="status" value="0">

                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')">
                                            <span class="mdi mdi-trash-can-outline"></span>
                                        </button>
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