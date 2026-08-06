<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/scorm_courses') ?>">My Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header3_link) ?>"><?php echo $header3 ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header4; ?></h4>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <p class="text-muted font-13 mb-4"></p>
            <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Statements</th>
                        <th>Value</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $j = 0;
                    //  print_r($userActivity);
                    foreach ($userActivity as $userActivityDetails) {
                        if ($userActivityDetails['verb'] == 'missed') {
                            $value = $userActivityDetails['value'];
                            $valueArray = explode(",", $value);
                            //  print_r($valueArray);
                            //  print_r($OutputVariables);
                            foreach ($valueArray as $valuedetails) {
                                // print_r($valuedetails);
                                $resultx =  array_search($valuedetails, array_column($OutputVariables, 'variable'));
                                //` echo $resultx;
                                if (is_numeric($resultx)) {
                                    $j = $j + 1;
                    ?>
                                    <tr style="background-color:#FFCCCB">
                                        <td><?php echo $j ?></td>
                                        <td><?php echo $_SESSION['tempusername'] . ' missed ' . $OutputVariables[$resultx]['negative_verb'] . ' ' . $OutputVariables[$resultx]['variable_description'] ?></td>
                                        <td></td>
                                        <td></td>

                                    </tr>

                            <?php }
                            }
                        } else if ($userActivityDetails['verb'] == 'status') {
                            $j = $j + 1;
                            ?>
                            <tr>
                                <td><?php echo $j ?></td>
                                <td><?php echo $_SESSION['tempusername'] . ' status changed to ' . $userActivityDetails['value'] . '.'; ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php
                        } else if ($userActivityDetails['verb'] == 'score') {
                            $j = $j + 1;
                        ?>
                            <tr>
                                <td><?php echo $j ?></td>
                                <td><?php echo $_SESSION['tempusername'] . ' scored ' . $userActivityDetails['value'] . '%.'; ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php
                        } else if ($userActivityDetails['verb'] == 'time') {
                        } else {
                            $j = $j + 1;
                        ?>
                            <tr>
                                <td><?php echo $j ?></td>
                                <td><?php echo $_SESSION['tempusername'] . ' ' . $userActivityDetails['verb'] . ' ' . $userActivityDetails['variable_description'] ?></td>
                                <td><?php echo $userActivityDetails['value'] ?></td>
                                <td><?php echo date('Y-m-d H:i:s', $userActivityDetails['createdtime']); ?></td>
                            </tr>

                    <?php
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>