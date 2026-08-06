<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url($header_link); ?>"><?php echo $header_link_name ?></a></li><b>&nbsp;>&nbsp;</b>
            <li><a href="<?php echo base_url($header_link2); ?>">Assign Users - <?php echo $coursename[0]['course_name'] ?></a></li><b>&nbsp;>&nbsp;</b>
          
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">

        <div class="x_panel">

            <div class="block block-drop-shadow">
                <div class="content">
                    <table id="dynamic-table" class="table table-sm table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Attempt</th>
                                <th>Last access</th>
                                <th>Details</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php $j = 0;
                            foreach ($getUsersdefectdata as $eachUsersdefect) {
                                // print_r($eachUsersdefect);
                                // exit();
                                if (!empty($eachUsersdefect['last_active']) && is_numeric($eachUsersdefect['last_active'])) {
                                    $lastaccess = date('m-d-Y H:i:s', $eachUsersdefect['last_active']);
                                } else {
                                    $lastaccess = '';
                                }

                                $j = $j + 1;
                            ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $eachUsersdefect['username'] ?></td>
                                    <td><?php echo $eachUsersdefect['attempt'] ?></td>
                                    <td><?php echo $lastaccess ?></td>
                                    <?php if ($eachUsersdefect['type'] == 8) { ?>
                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url($view_details) ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="sc_uid" value="<?php echo $eachUsersdefect['sc_uid'] ?>">
                                                <input type="hidden" name="attempt" value="<?php echo $eachUsersdefect['attempt']  ?>">
                                                <input type="hidden" name="xov" value="<?php echo $eachUsersdefect['xov']  ?>">
                                                <input type="hidden" name="tempusername" value="<?php echo $eachUsersdefect['username']  ?>">
                                                <input type="hidden" name="course_name" value="<?php echo $eachUsersdefect['course_name']  ?>">
                                                <button type="submit" class="btn btn-sm widget-icon btn-info">View</button>
                                            </form>

                                        </td>
                                    <?php } else { ?>
                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url($view_details) ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="sc_uid" value="<?php echo $eachUsersdefect['sc_uid'] ?>">
                                                <input type="hidden" name="attempt" value="<?php echo $eachUsersdefect['attempt']  ?>">
                                                <input type="hidden" name="xov" value="<?php echo $eachUsersdefect['xov']  ?>">
                                                <input type="hidden" name="tempusername" value="<?php echo $eachUsersdefect['username']  ?>">
                                                <input type="hidden" name="course_name" value="<?php echo $eachUsersdefect['course_name']  ?>">
                                                <button type="submit" class="btn btn-sm widget-icon btn-info">View</button>
                                            </form>

                                        </td>
                                    <?php } ?>
                                </tr>
                        </tbody>

                    <?php } ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>