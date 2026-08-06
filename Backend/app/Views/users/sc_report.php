<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header_link_name ?></a></li>
                 
                </ol>
            </div>
            <h4 class="page-title">Assign Users - <?php echo $coursename[0]['course_name'] ?></h4>
        </div>
    </div>
</div>

<?php

$enroll_completed = 0;
$enroll_incomplete = 0;
if (count($getUserlatestclientCourseByScenario) > 0) {
    foreach ($getUserlatestclientCourseByScenario as $stat) {
        $tempstatus = $stat['lesson_status'];
        if ($tempstatus == 'completed' || $tempstatus == 'Completed') {
            $enroll_completed = $enroll_completed + 1;
        }
        if ($tempstatus == 'incomplete' || $tempstatus == 'Incomplete') {
            $enroll_incomplete = $enroll_incomplete + 1;
        }
    }
}
$completed = 0;
$incomplete = 0;
$passed = 0;
$failed = 0;
$totalAttempts = 0;
$completedper = 0;
$incompleteper = 0;
$passedper = 0;
$failedper = 0;
$average_score = 0;
$raw = 0;
$user_array = array();
$user_id_array = array();
$month_graph = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$score_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$user_score = array();
$totalTime = 0;
$average_time = 0;
//print_r($all_attempts);
if (count($all_attempts) > 0) {
    foreach ($all_attempts as $all_attempts_data) {
        $lesson_status = $all_attempts_data['lesson_status'];
        $id_user = $all_attempts_data['id_user'];
        $name = $all_attempts_data['name'];
        $createdon = $all_attempts_data['createdon'];
        if (strlen($createdon) > 8) {
            $month = date('n', $createdon);
            $month_graph[$month] = $month_graph[$month] + 1;
        }

        $search_result = array_search($id_user, $user_id_array, true);

        if (!is_int($search_result)) {
            array_push($user_array, $name);
            array_push($user_id_array, $id_user);
            array_push($user_score, $all_attempts_data['raw']);
        }
        if ($lesson_status == 'incomplete' || $lesson_status == 'Incomplete') {
            $incomplete++;
        } elseif ($lesson_status == 'completed' || $lesson_status == 'Completed') {

            if ($all_attempts_data['raw'] > 0 && $all_attempts_data['raw'] < 11) {
                $score_array[0] = $score_array[0] + 1;
            } elseif ($all_attempts_data['raw'] > 10 && $all_attempts_data['raw'] < 21) {
                $score_array[1] = $score_array[1] + 1;
            } elseif ($all_attempts_data['raw'] > 20 && $all_attempts_data['raw'] < 31) {
                $score_array[2] = $score_array[2] + 1;
            } elseif ($all_attempts_data['raw'] > 30 && $all_attempts_data['raw'] < 41) {
                $score_array[3] = $score_array[3] + 1;
            } elseif ($all_attempts_data['raw'] > 40 && $all_attempts_data['raw'] < 51) {
                $score_array[4] = $score_array[4] + 1;
            } elseif ($all_attempts_data['raw'] > 50 && $all_attempts_data['raw'] < 61) {
                $score_array[5] = $score_array[5] + 1;
            } elseif ($all_attempts_data['raw'] > 60 && $all_attempts_data['raw'] < 71) {
                $score_array[6] = $score_array[6] + 1;
            } elseif ($all_attempts_data['raw'] > 70 && $all_attempts_data['raw'] < 81) {
                $score_array[7] = $score_array[7] + 1;
            } elseif ($all_attempts_data['raw'] > 80 && $all_attempts_data['raw'] < 91) {
                $score_array[8] = $score_array[8] + 1;
            } elseif ($all_attempts_data['raw'] > 90 && $all_attempts_data['raw'] < 101) {
                $score_array[9] = $score_array[9] + 1;
            } elseif ($all_attempts_data['raw'] == 0) {
                $score_array[10] = $score_array[10] + 1;
            }
            $raw = $all_attempts_data['raw'] + $raw;
            $trimmedsessionTime = '00:00:00';
            $splitotalTime = '00:00:00';
            $sec1 = 0;
            if (strlen($all_attempts_data['session_time']) > 4) {
                if ($all_attempts_data['total_time'] == '' || $all_attempts_data['total_time'] == '00:00:00.00') {
                    $splitotalTime = '00:00:00';
                } else {
                    $splitotalTime = explode('.', $all_attempts_data['total_time'])[0];
                }
                if (strlen($all_attempts_data['session_time']) > 8) {
                    $splitsession_time = explode('.', $all_attempts_data['session_time']);
                    $trimmedsessionTime = substr($splitsession_time[0], 2);
                }
                if (strlen($all_attempts_data['session_time']) == 8) {
                    $trimmedsessionTime = explode('.', $all_attempts_data['session_time'])[0];
                }
                $matches0 = explode(':', $splitotalTime); // split up the string
                $matches1 = explode(':', $trimmedsessionTime);
                $sec0 = $matches0[0] * 60 * 60 + $matches0[1] * 60 + $matches0[2];
                $sec1 = $sec0 + $matches1[0] * 3600 + $matches1[1] * 60 + $matches1[2];
                // print_r($all_attempts_data);
            }

            $totalTime = $totalTime +  $sec1;
            // print_r($all_attempts_data['session_time']);
            $completed = $completed + 1;
        } elseif ($lesson_status == 'passed' || $lesson_status == 'Passed') {
            $passed++;
        } elseif ($lesson_status == 'failed' || $lesson_status == 'Failed') {
            $failed++;
        }
        $totalAttempts++;
        $completedper = round($completed / $totalAttempts * 100);
        $incompleteper = round($incomplete / $totalAttempts * 100);
        $passedper = round($passed / $totalAttempts * 100);
        $failedper = round($failed / $totalAttempts * 100);
        if ($completed > 0) {
            $average_score = round($raw / $completed);
            $average_time = $totalTime / $completed;
        }
    }
    // print_r($totalAttempts);
}
?>
<?php $userlevel = session()->get('userlevel');
$userlevelarray = explode(',', $userlevel);
if (in_array(6, $userlevelarray)) {
?>
    <!-- <div class="col-md-2">
        <form class="form" action=" <?php echo base_url('User_login/client_users/calcualte_totaltime'); ?>" method="POST"><?= csrf_field() ?>
            <div class="form-group row" class="col-md-2 col-sm-2 ">
                <button type="submit" class="btn btn-sm btn-success btn-block">
                    Calculate Total Time
                </button>
            </div>
        </form>
    </div> -->
<?php } ?>
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php $enrolled = count($user_id_array);
                                                                                        echo $enrolled; ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Enrollment</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $enroll_completed; ?>/<?php echo $enroll_incomplete; ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Completed/Progress</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo ($enrolled - $enroll_completed - $enroll_incomplete); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Not Started</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">><?php
                                                                                        if ($enrolled > 0) {
                                                                                            echo round($totalAttempts / $enrolled, 2);
                                                                                        } else {
                                                                                            echo 0;
                                                                                        }
                                                                                        ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Average Attempts</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">><?php echo $average_score; ?>%</span></h3>
                            <p class="text-muted mb-1 text-truncate">Average Score</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">>Average Time</span></h3>
                            <p class="text-muted mb-1 text-truncate">Average Time</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
</div>

<!-- end row-->
<div class="row">
    <div class="col-md-6 ">
        <div class="card">
            <div class="card-body">
                <div class="x_panel tile fixed_height_320">
                    <div class="x_title">
                        <h2>ATTEMPTS BREAKDOWN</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="widget_summary">
                            <div class="w_left w_25">
                                <span>Attempts</span>
                            </div>
                            <div class="w_center w_55">
                                <div class="progress">
                                    <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">

                                    </div>
                                </div>
                            </div>
                            <div class="w_right w_20">
                                <span><?php echo $totalAttempts; ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="widget_summary">
                            <div class="w_left w_25">
                                <span>In Progress</span>
                            </div>
                            <div class="w_center w_55">
                                <div class="progress">
                                    <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $incompleteper; ?>%;">
                                    </div>
                                </div>
                            </div>
                            <div class="w_right w_20">
                                <span><?php echo $incomplete; ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="widget_summary">
                            <div class="w_left w_25">
                                <span>Completed</span>
                            </div>
                            <div class="w_center w_55">
                                <div class="progress">
                                    <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $completedper; ?>%;">
                                    </div>
                                </div>
                            </div>
                            <div class="w_right w_20">
                                <span><?php echo $completed; ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="widget_summary">
                            <div class="w_left w_25">
                                <span>Passed</span>
                            </div>
                            <div class="w_center w_55">
                                <div class="progress">
                                    <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $passedper; ?>%;">
                                    </div>
                                </div>
                            </div>
                            <div class="w_right w_20">
                                <span><?php echo $passed; ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="widget_summary">
                            <div class="w_left w_25">
                                <span>Failed</span>
                            </div>
                            <div class="w_center w_55">
                                <div class="progress">
                                    <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $failedper; ?>%;">
                                    </div>
                                </div>
                            </div>
                            <div class="w_right w_20">
                                <span><?php echo $failed; ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="card">
            <div class="card-body">
                <div class="dashboard_graph x_panel">
                    <div class="x_title">
                        <h2>ATTEMPTS BY MONTH</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div>
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6 ">
        <div class="card">
            <div class="card-body">
                <h2>LEADERBOARD</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="">
                    <ul class="to_do">
                        <?php
                        $counter = -1;
                        foreach ($user_array as $name) {
                            $counter++;
                            echo '<li><p>';
                            echo $name;
                            echo ' (';
                            echo $user_score[$counter];
                            echo ')</p></li>';
                            if ($counter > 7) {
                                exit();
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6 ">
        <div class="card">
            <div class="card-body">
                <h2>SCORE GRAPH</h2>
                <div class="clearfix"></div>

                <div class="x_content">
                    <div>
                        <canvas id="score_graph"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($coursename[0]['type'] == 5) {
?>

    <!-- <div class="col-md-6 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>TOP 7 DEFECTS</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <?php
                        $unprocessed = count($allmissed);
                        if ($unprocessed > 0) {
                        ?>
                            <li><a href="<?php echo base_url('User_login/client_users/process_defects'); ?>"><button type="submit" class="btn btn-sm btn-danger">PROCESS <?php echo '(' . $unprocessed . ')'; ?></button></a>
                            </li>
                        <?php
                        }
                        ?>
                        <li><a href="<?php echo base_url('User_login/client_users/defect_full_list'); ?>" class="close-link">FULL LIST</a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="">
                        <ul class="to_do">
                            <?php
                            $counter_def = -1;
                            foreach ($outputVariables as $topDefects) {
                                $counter_def++;
                                echo '<li><p>';
                                if ($topDefects['counter'] == 1) {
                                    echo '<a href="' . base_url($form_link_1) . '">' . $topDefects['counter'] . ' user missed ' . $topDefects['negative_verb'] . ' ' . $topDefects['variable_description'] . '</a>';
                                } else {
                                    echo '<a href="' . base_url($form_link_1) . '">' . $topDefects['counter'] . ' users missed ' . $topDefects['negative_verb'] . ' ' . $topDefects['variable_description'] . '</a>';
                                }
                                echo '</p></li>';
                            }
                            ?>
                            <p>

                        </ul>
                    </div>
                </div>
            </div>
        </div> -->
<?php } ?>
<?php if ($coursename[0]['type'] == 5) {
?>

    <div class="col-md-6 ">
        <div class="card">
            <div class="card-body">
                <h2>TOP 7 DEFECTS</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <?php
                    $unprocessed = count($allmissed);
                    if ($unprocessed > 0) {
                    ?>
                        <li><a href="<?php echo base_url('User_login/client_users/process_defects_v1'); ?>"><button type="submit" class="btn btn-sm btn-danger">PROCESS <?php echo '(' . $unprocessed . ')'; ?></button></a>
                        </li>
                    <?php
                    }
                    ?>
                    <li><a href="<?php echo base_url('User_login/client_users/defect_full_list_v1'); ?>" class="close-link">FULL LIST</a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="">
                    <ul class="to_do">
                        <?php
                        $counter_def = -1;
                        foreach ($outputVariables_v1 as $topDefects) {
                            $counter_def++;

                            // print_r($topDefects);

                            if ($topDefects['counter'] == 1) {
                                echo '<li><p>';
                                echo '<a href="' . base_url($form_link_1 . '/' . $topDefects['xov']) . '" target= "_blank">' . $topDefects['counter'] . ' user missed ' . $topDefects['negative_verb'] . ' ' . $topDefects['variable_description'] . '</a>';
                                echo '</p></li>';
                            } elseif ($topDefects['counter'] != 0) {
                                echo '<li><p>';
                                echo '<a href="' . base_url($form_link_1 . '/' . $topDefects['xov']) . '" target= "_blank">' . $topDefects['counter'] . ' users missed ' . $topDefects['negative_verb'] . ' ' . $topDefects['variable_description'] . '</a>';
                                echo '</p></li>';
                            }
                        }
                        ?>

                        <p>

                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php } elseif ($coursename[0]['type'] == 8) { ?>

    <div class="col-md-6 ">
        <div class="card">
            <div class="card-body">
                <h2>TOP 7 DEFECTS</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <?php
                    // $unprocessed = count($allmissed);
                    // if ($unprocessed > 0) {
                    // 
                    ?>

                    <?php
                    // }
                    // 
                    ?>
                    <li><a href="<?php echo base_url('User_login/client_users/defect_full_list_v2'); ?>" class="close-link">FULL LIST</a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="">
                    <ul class="to_do">
                        <?php
                        $counter_def = -1;
                        foreach ($outputVariables_v2 as $topDefects) {
                            // print_r($topDefects);
                            $counter_def++;

                            // print_r($topDefects);

                            if ($topDefects['counter'] == 1) {
                                echo '<li><p>';
                                echo '<a href="' . base_url($form_link_2 . '/' . $topDefects['xov']) . '" target= "_blank">' . $topDefects['counter'] . ' user wrong answer for ' . $topDefects['question'] . '</a>';
                                echo '</p></li>';
                            } elseif ($topDefects['counter'] != 0) {
                                echo '<li><p>';
                                echo '<a href="' . base_url($form_link_2 . '/' . $topDefects['xov']) . '" target= "_blank">' . $topDefects['counter'] . ' users wrong answer for ' . $topDefects['question'] . '</a>';
                                echo '</p></li>';
                            }
                        }
                        ?>

                        <p>

                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
            datasets: [{
                label: '# Attempts',
                data: [<?php
                        for ($i = 1; $i < 12; $i++) {
                            echo $month_graph[$i], ',';
                        }
                        echo $month_graph[12];
                        ?>],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const score_graph = document.getElementById('score_graph');
    new Chart(score_graph, {
        type: 'line',
        data: {
            labels: ['0', '10', '20', '30', '40', '50', '60', '70', '80', '90', '100'],
            datasets: [{
                label: '# SCORE',
                data: [<?php
                        echo $score_array[0], ',';
                        for ($i = 0; $i < 10; $i++) {
                            echo $score_array[$i], ',';
                        }
                        echo $month_graph[10];
                        ?>],
                borderWidth: 1,
                borderColor: '#FF6384',
                backgroundColor: '#FFB1C1',
                tension: 0.5
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>