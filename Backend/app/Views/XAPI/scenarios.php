<?php $userlevel = session()->get('userlevel');
$arrayuserlevel = explode(',', $userlevel);

?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url("SCORM/scorm_courses") ?>">My Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more') ?>">Course Detail</a></li>
                  
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header; ?></h4>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('XAPI/XAPI_scenarios_courses/createNewScenario') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div>
                        <div class="mb-3">
                            <input type="text" name="scenario" class="form-control" value="" required="" />
                        </div>
                        <div>
                            <button type="submit" class="btn btn-sm btn-primary">Create New Scenario</button>
                        </div>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Scenario</th>
                        <!-- <th>Status</th> -->
                        <th>Created By</th>
                        <th>Created On</th>
                        <th>Settings</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $j = 0;
                    if (!empty($scenarios)) {
                        foreach ($scenarios as $s) {
                            if ($s['client'] == session()->get('client')) {
                                $j = $j + 1;
                    ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $s['scenario_name']; ?></td>
                                    <!-- <td><?php $status = $s['status'];
                                                switch ($status) {
                                                    case 1:
                                                        echo "In Development";
                                                        break;
                                                    case 2:
                                                        echo "Live";
                                                        break;
                                                }
                                                ?></td> -->
                                    <td><?php echo $s['createdby']; ?></td>
                                    <td><?php echo date('m-d-Y', $s['createdon']); ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($form_seetinglink) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="xs" value="<?php echo $s['xs'] ?>">
                                            <input type="hidden" name="scenario_name" value="<?php echo $s['scenario_name'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="icon-settings"></span></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($form_editlink) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="xs" value="<?php echo $s['xs'] ?>">
                                            <input type="hidden" name="scenario_name" value="<?php echo $s['scenario_name'] ?>">
                                            <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                    <?php }
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>