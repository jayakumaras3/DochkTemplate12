<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>">Dashboard</a></li>
              
                </ol>
            </div>

            <h4 class="page-title">My Courses</h4>
        </div>
    </div>
</div>
<?php if (!empty($coursesDetails)) : ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Course Name</th>
                            <th>Duration</th>
                            <th>Categories</th>
                            <th>Status</th>
                            <th>Details</th>
                            <!-- <th>Certificate</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($coursesDetails as $clienteachCourseddata) {

                            $j = $j + 1; ?>
                            <tr>
                                <td class="center"><?php echo  $j ?></td>
                                <td><?php echo $clienteachCourseddata['course_name'] ?></td>
                                <td>
                                    <?php if ($clienteachCourseddata['duration'] > 0) { ?>
                                        <?php
                                        $duration = $clienteachCourseddata['duration'];
                                        if ($duration > 60) {
                                            $hours = intdiv($duration, 60);
                                            echo $hours . ' Hrs. ';
                                            $balancemin = $duration - $hours * 60;
                                            if ($balancemin > 0) {
                                                echo $balancemin . ' min';
                                            }
                                        } else {
                                            echo $duration . ' min';
                                        }

                                        ?></br>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php if (strlen($clienteachCourseddata['category']) > 2) { ?>
                                        <?php echo $clienteachCourseddata['category'] ?>
                                    <?php } ?>
                                </td>
                                <td> <?php if (strlen($clienteachCourseddata['lesson_status']) > 2) {
                                            if ($clienteachCourseddata['lesson_status'] == 'completed' || $clienteachCourseddata['lesson_status'] == 'passed') { ?>
                                            <span class="badge bg-soft-success text-success p-1"><?php echo 'Completed' ?></span>
                                        <?php  } elseif ($clienteachCourseddata['lesson_status'] == 'incomplete') { ?>
                                            <span class="badge bg-soft-info text-info p-1"><?php echo 'In progress' ?></span>
                                        <?php } elseif ($clienteachCourseddata['lesson_status'] == 'not started') { ?>
                                            <span class="badge bg-soft-warning text-warning p-1"><?php echo 'Not Started' ?></span>
                                        <?php } ?>

                                    <?php } else { ?>
                                        <span class="badge bg-soft-warning text-warning p-1"><?php echo 'Not Started'; ?></span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="crid" value="<?php echo $clienteachCourseddata['scourse_id'] ?>">
                                        <?php if ($clienteachCourseddata['demo'] == 1) {
                                            echo '<input type="hidden" name="detail_type" value="3">';
                                        } else {
                                            echo ' <input type="hidden" name="detail_type" value="2">';
                                        } ?>
                                         <input type="hidden" name="tab" value="1">
                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><i class="mdi mdi-information-outline"></i></button>
                                    </form>

                                </td>
                                <!-- <td></td> -->
                            <?php
                        } ?>
                            </tr>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

<?php endif; ?>