<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_users'); ?>">Users</a></li>
              
                </ol>
            </div>
            <h4 class="page-title">Assign Course Group - <?php echo $username[0]['name']; ?></h4>
        </div>
    </div>
</div>
<div class="row">
   
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo base_url('User_login/client_users/add_learnGroup_to_users'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="mb-3">
                            <select name="group_id" class="form-control">
                                <?php
                                foreach ($courselearndata  as $eachcoursegroupdata) {
                                    echo '<option value="' . $eachcoursegroupdata['sc_cgid'] . '">' . $eachcoursegroupdata['description'] . ' (' . $eachcoursegroupdata['assign_id_count'] . ')' . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" id="due_date" name="due_date" type="date">
                        </div>
                        <div>
                            <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
                            <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light">
                                Add Course Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Group Name</th>
                            <th>Completion %</th>
                            <th>Due Date</th>
                            <th>Courses</th>
                            <th>By</th>
                            <th>On</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($learngroupdata as $eachlearngroupdata) {
                            // print_r($eachlearngroupdata);
                            // exit();
                            $j = $j + 1;

                        ?>
                            <tr>
                                <td><?php echo $j ?></td>
                                <td><?php echo $eachlearngroupdata['learn_group_name'] ?></td>

                                <td><?php $lessonstat = ($eachlearngroupdata['complete_percent'] != '') ? $eachlearngroupdata['complete_percent'] : '0';
                                    echo $lessonstat;
                                    ?></td>
                                <td><?php echo ($eachlearngroupdata['due_date'] != '0000-00-00') ? date('d-m-Y', strtotime($eachlearngroupdata['due_date'])) : '';  ?></td>
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
