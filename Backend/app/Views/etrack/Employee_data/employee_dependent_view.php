<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Dependents
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <?php if (count($dependents) < 4) { ?>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/add_dependents'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row mb-2">
                            <div class="col-4 col-xl-3">
                                <label for="inputEmail3" class="mb-1">Saturation</label>
                                <select name="saturation" class="form-control">
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Master">Master</option>
                                    <option value="Mr.">Mr.</option>
                                </select>
                            </div>
                            <div class="col-4 col-xl-3">
                                <label for="inputEmail3" class="mb-1">First Name</label>
                                <input type="text" class="form-control" required="" name="fname" value="">
                            </div>
                            <div class="col-4 col-xl-3">
                                <label for="inputEmail3" class="mb-1">Middle Name</label>
                                <input type="text" class="form-control" name="mname" value="">
                            </div>
                            <div class="col-4 col-xl-3">
                                <label for="inputEmail3" class="mb-1">Last Name</label>
                                <input type="text" class="form-control" required="" name="lname" value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 col-xl-4">
                                <label for="inputEmail3" class="mb-1">Policy No.</label>
                                <input type="text" class="form-control" name="policyno" value="">
                            </div>
                            <div class="col-4 col-xl-4">
                                <label for="inputEmail3" class="mb-1">Relationship</label>
                                <select name="relation" class="form-control">
                                    <option value="7">Self</option>
                                    <option value="4">Husband</option>
                                    <option value="3">Wife</option>
                                    <option value="6">Daughter</option>
                                    <option value="5">Son</option>
                                </select>
                            </div>
                            <div class="col-4 col-xl-4">
                                <label for="inputEmail3" class="mb-1">Date of Birth</label>
                                <input id="start_date" name="dob" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
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
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                    Add Dependents
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Name</th>
                            <th>DOB</th>
                            <th>Relation</th>
                            <th>Policy No.</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($dependents as $dep) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $dep['saturation'] . ' ' .$dep['dep_name'] . ' ' . $dep['dep_mname'] . ' ' . $dep['dep_lname']; ?></td>
                                <td><?php echo $dep['dep_dob']; ?></td>
                                <td><?php $relation = $dep['relation'];
                                    switch ($relation) {
                                        case 3:
                                            echo 'Wife';
                                            break;
                                        case 4:
                                            echo 'Husband';
                                            break;
                                        case 5:
                                            echo 'Son';
                                            break;
                                        case 6:
                                            echo 'Daughter';
                                            break;
                                        case 7:
                                            echo 'Self';
                                            break;
                                    }  ?></td>
                                <td><?php echo $dep['policy']; ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/del_dependent'); ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="dependent_id" value="<?php echo $dep['dep_id']; ?>">
                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
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