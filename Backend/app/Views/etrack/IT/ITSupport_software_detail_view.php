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
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/ITSupport/softwares'); ?>">
                            IT Software
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                IT Software Details - <?php if($software_name){
                    echo $software_name[0]['soft_description'];
                }
                ?>
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">

        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/add_software_detail'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">License</label>
                        <div class="col-8 col-xl-9">
                            <input type="number" name="license" class="form-control" required value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Start Date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="start_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="" required>
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">End Date</label>
                        <div class="col-8 col-xl-9">
                            <input id="end_date" name="end_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="" required>
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" value="<?php echo $soft_id; ?>" name="sofid" >
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light" id="submitButton">
                                Add New License
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
                            <th>Licenses</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Assigned</th>
                            <th>Balance</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($software_by_id as $software) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $software['num_license']; ?></td>
                                <td><?php echo $software['start_date']; ?></td>
                                <td><?php echo $software['end_date']; ?></td>
                                <td><?php echo $software['used']; ?></td>
                                <td><?php echo $software['num_license']-$software['used']; ?> </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/edit_software_details'); ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="soft_detail_id" value="<?php echo $software['soft_detail_id']; ?>">
                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi mdi-eye-outline"></span></button>
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