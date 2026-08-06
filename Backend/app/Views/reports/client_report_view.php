<?php helper('localization'); ?>
<?php $accessmenu = session()->get('accessmenu');
$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu)); ?>
<?php $current_url = current_url(true); // Returns CodeIgniter\HTTP\URI object 
?>
<?php $segment1 = uri_string(); // Returns string like 'my_training' 
?>
<?php $current_page = explode('/', uri_string())[1];

?>
<style>
    .card {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    /* Tile design matching my_training's summary tiles */
    .summary-tile.card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-tile.card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.09), 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .summary-tile .card-body {
        padding: 0.9rem 1rem;
    }

    .summary-tile .avatar-lg {
        border-radius: 12px;
        height: 2.5rem;
        width: 2.5rem;
    }

    .summary-tile .avatar-lg i {
        font-size: 16px !important;
    }

    .summary-tile h3 {
        font-size: 1.25rem;
        margin-bottom: 0.15rem;
    }

    .summary-tile p {
        font-size: 0.7rem;
        margin-bottom: 0;
    }

    .summary-tile.tile-success {
        background: linear-gradient(135deg, #ffffff 0%, #e9f9f0 100%);
    }

    .summary-tile.tile-warning {
        background: linear-gradient(135deg, #ffffff 0%, #fff6e0 100%);
    }

    .summary-tile.tile-danger {
        background: linear-gradient(135deg, #ffffff 0%, #fdecec 100%);
    }

    .summary-tile.tile-purple {
        background: linear-gradient(135deg, #ffffff 0%, #efeaff 100%);
    }

    .summary-tile.tile-cyan {
        background: linear-gradient(135deg, #ffffff 0%, #e6f8fc 100%);
    }

    .summary-tile.tile-orange {
        background: linear-gradient(135deg, #ffffff 0%, #ffe9e0 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-success {
        background: linear-gradient(135deg, #232b36 0%, #17301f 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-warning {
        background: linear-gradient(135deg, #232b36 0%, #3a2f10 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-danger {
        background: linear-gradient(135deg, #232b36 0%, #3a1f22 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-purple {
        background: linear-gradient(135deg, #232b36 0%, #241c44 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-cyan {
        background: linear-gradient(135deg, #232b36 0%, #123138 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-orange {
        background: linear-gradient(135deg, #232b36 0%, #3a2416 100%);
    }

    [data-bs-theme="dark"] .summary-tile h3.text-dark {
        color: #f3f7f9 !important;
    }

    .bg-soft-orange {
        background-color: rgba(255, 112, 67, 0.15) !important;
    }

    .text-orange {
        color: #ff7043 !important;
    }

    .border-orange {
        border-color: #ff7043 !important;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>"><?php echo lang('UI_Text.Dashboard'); ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.Report'); ?></h4>
        </div>
    </div>
</div>

<div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-2">
    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-cyan">
            <div class="card-body">
                <a href="<?= base_url('User_login/client_users') ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-info border-info border">
                                <i class="fe-users font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format(count($total_users)); ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Total_Users'); ?></p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </a>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-purple">
            <div class="card-body">
                <a href="<?= base_url('SCORM/scorm_courses') ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-primary border-primary border">
                                <i class="fe-book-open font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format(count($total_courses)); ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Total_Courses'); ?></p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </a>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-orange">
            <div class="card-body">
                <a href="<?= base_url('Reports/client_reports/detail_report') ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-orange border-orange border">
                                <i class="fe-check font-22 avatar-title text-orange"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format($total_completed + $total_inprogress + $total_not_started); ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Total_Assigned'); ?></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-success">
            <div class="card-body">
                <a href="<?= base_url('Reports/client_reports/detail_report/2/3') ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-success border-success border">
                                <i class="fe-thumbs-up font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format($total_completed); ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Completed'); ?></p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </a>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-warning">
            <div class="card-body">
                <a href="<?= base_url('Reports/client_reports/detail_report/1') ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-warning border-warning border">
                                <i class="fe-play font-22 avatar-title text-warning"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format($total_inprogress); ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.In Progress'); ?></p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </a>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-danger">
            <div class="card-body">
                <a href="<?= base_url('Reports/client_reports/detail_report/0') ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-danger border-danger border">
                                <i class="fe-alert-triangle font-22 avatar-title text-danger"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo number_format($total_not_started); ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?php echo lang('UI_Text.Not_started'); ?></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-1 header-title"><i class="fe-user me-1"></i> <?php echo lang('UI_Text.User_Report'); ?></h4>
                <p class="text-muted font-13 mb-3"><?php echo lang('UI_Text.User_Report_Description'); ?></p>

                <form action="<?= base_url('Reports/client_reports/report_course_report') ?>" method="POST"><?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="report_select_user" class="form-label fw-semibold"><?php echo lang('UI_Text.Select_Users'); ?></label>
                        <select id="report_select_user" class="form-control" name="users">
                            <?php
                            foreach ($total_users as $users) {
                            ?>
                                <option value="<?php echo $users['id_user']; ?>"><?php echo $users['name'] . ' ' . $users['last_name']; ?></option>
                            <?php } ?>

                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary rounded-pill btn-sm"><?php echo lang('Buttons.View'); ?></button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="mb-1 header-title"><i class="fe-book-open me-1"></i> <?php echo lang('UI_Text.Course_Report'); ?></h4>
                <p class="text-muted font-13 mb-3"><?php echo lang('UI_Text.Course_Report_Description'); ?></p>
                <form action="<?= base_url('XAPI/XAPI_courses/courseusersassigned_report') ?>" method="POST"><?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="report_select_course" class="form-label fw-semibold"><?php echo lang('UI_Text.Select_Course'); ?></label>
                        <select id="report_select_course" class="form-control" name="scourse_id">
                            <?php
                            foreach ($total_courses as $courses) {
                            ?>
                                <option value="<?php echo $courses['scourse_id']; ?>"><?php echo $courses['course_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <input type="hidden" name="return_page" value="report">
                    <button type="submit" class="btn btn-outline-success rounded-pill btn-sm"><?php echo lang('Buttons.View'); ?></button>
                </form>
            </div>
        </div>

        <!--  <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo lang('UI_Text.Detailed_Report'); ?></h4>
                
                 <form action="<?= base_url('Reports/client_reports/monthly_report') ?>" method="POST"><?= csrf_field() ?>
                    <form action="<?= base_url('Reports/client_reports/assigned_report') ?>" method="POST"><?= csrf_field() ?>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label"><?php echo lang('UI_Text.Select_Month'); ?></label>
                                <select class="form-control" name="month">
                                    <?php
                                    /* for ($month = 1; $month <= 12; $month++) {
                                        // Get the month name (e.g., "January")
                                        $monthName = date("F", mktime(0, 0, 0, $month, 1));

                                        // Output the option tag with the month number as value
                                        echo "<option value='$month'>$monthName</option>";
                                    } */
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label"><?php echo lang('UI_Text.Select_Year'); ?></label>
                                <select class="form-control" name="Year">
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <button type="submit" class="btn btn-outline-warning rounded-pill btn-sm"><?php echo lang('Buttons.View'); ?></button>
                    <button type="submit" class="btn btn-outline-warning rounded-pill btn-sm"><?php echo lang('Buttons.View'); ?></button>
                </form>
            </div>
        </div>-->
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <h4 class="header-title mb-0"><i class="fe-bar-chart-2 me-1"></i> <?= lang('UI_Text.Data_for_Year'); ?> <?= $Year; ?></h4>
                    <form action="<?= base_url('Reports/client_reports/update_graph') ?>" method="POST" class="d-flex flex-wrap align-items-center gap-2 m-0"><?= csrf_field() ?>
                        <div class="d-flex align-items-center gap-1">
                            <label for="update_graph_year" class="form-label mb-0 small text-muted"><?php echo lang('UI_Text.Select_Year'); ?></label>
                            <select id="update_graph_year" class="form-select form-select-sm rounded-pill" name="Year" onchange="this.form.submit();" style="width:auto;">
                                <?php for ($y = $current_year; $y >= $current_year - 4; $y--) : ?>
                                    <option value="<?= $y ?>" <?= ($y == $Year) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <label for="update_graph_month" class="form-label mb-0 small text-muted"><?php echo lang('UI_Text.Select_Month'); ?></label>
                            <select id="update_graph_month" class="form-select form-select-sm rounded-pill" name="month" onchange="this.form.submit();" style="width:auto;">
                                <?php
                                for ($month = 1; $month <= 12; $month++) {
                                    // Translated month name (e.g., "January"), not PHP's date('F', ...) which is always English.
                                    $monthName = translated_month_name($month);
                                    $isSelected = ($month == $selected_month) ? 'selected' : '';

                                    // Output the option tag with the month number as value
                                    echo "<option value='$month' $isSelected>" . esc($monthName) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill" title="<?php echo lang('UI_Text.Update_graph'); ?>">
                            <i class="fe-refresh-cw"></i>
                        </button>
                    </form>
                </div>
                <?php if ($completed_data) { ?>
                    <div>
                        <canvas id="myChart"></canvas>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

                    <script>
                        const ctx = document.getElementById('myChart');
                        const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                        const chartAxisColor = isDarkMode ? '#cedeef' : '#6c757d';
                        const chartGridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
                                datasets: [{
                                        label: '<?php echo lang('UI_Text.Completed'); ?>',
                                        data: [
                                            <?php

                                            echo $completed_data[0]['M1'] . ',';
                                            echo $completed_data[0]['M2'] . ',';
                                            echo $completed_data[0]['M3'] . ',';
                                            echo $completed_data[0]['M4'] . ',';
                                            echo $completed_data[0]['M5'] . ',';
                                            echo $completed_data[0]['M6'] . ',';
                                            echo $completed_data[0]['M7'] . ',';
                                            echo $completed_data[0]['M8'] . ',';
                                            echo $completed_data[0]['M9'] . ',';
                                            echo $completed_data[0]['M10'] . ',';
                                            echo $completed_data[0]['M11'] . ',';
                                            echo $completed_data[0]['M12'];
                                            ?>
                                        ],
                                        backgroundColor: [
                                            'rgba(99, 255, 164, 0.3)'
                                        ],
                                        borderColor: [
                                            'rgb(99, 255, 164)'
                                        ],
                                        borderWidth: 1
                                    },
                                    //  {
                                    //     label: '<?php echo lang('UI_Text.In Progress'); ?>',
                                    //     data: [<?php
                                                    //             echo $inprogress_data[0]['M1'] . ',';
                                                    //             echo $inprogress_data[0]['M2'] . ',';
                                                    //             echo $inprogress_data[0]['M3'] . ',';
                                                    //             echo $inprogress_data[0]['M4'] . ',';
                                                    //             echo $inprogress_data[0]['M5'] . ',';
                                                    //             echo $inprogress_data[0]['M6'] . ',';
                                                    //             echo $inprogress_data[0]['M7'] . ',';
                                                    //             echo $inprogress_data[0]['M8'] . ',';
                                                    //             echo $inprogress_data[0]['M9'] . ',';
                                                    //             echo $inprogress_data[0]['M10'] . ',';
                                                    //             echo $inprogress_data[0]['M11'] . ',';
                                                    //             echo $inprogress_data[0]['M12'];
                                                    //             
                                                    ?>],
                                    //     backgroundColor: [
                                    //         'rgba(247, 255, 99, 0.3)'
                                    //     ],
                                    //     borderColor: [
                                    //         'rgb(232, 255, 99)'
                                    //     ],
                                    //     borderWidth: 1
                                    // }, {
                                    //     label: '<?php echo lang('UI_Text.Not_started'); ?>',
                                    //     data: [<?php
                                                    //             if (!empty($not_started_data)) {
                                                    //                 echo $not_started_data[0]['M1'] . ',';
                                                    //                 echo $not_started_data[0]['M2'] . ',';
                                                    //                 echo $not_started_data[0]['M3'] . ',';
                                                    //                 echo $not_started_data[0]['M4'] . ',';
                                                    //                 echo $not_started_data[0]['M5'] . ',';
                                                    //                 echo $not_started_data[0]['M6'] . ',';
                                                    //                 echo $not_started_data[0]['M7'] . ',';
                                                    //                 echo $not_started_data[0]['M8'] . ',';
                                                    //                 echo $not_started_data[0]['M9'] . ',';
                                                    //                 echo $not_started_data[0]['M10'] . ',';
                                                    //                 echo $not_started_data[0]['M11'] . ',';
                                                    //                 echo $not_started_data[0]['M12'];
                                                    //             }
                                                    //             
                                                    ?>],
                                    //     backgroundColor: [
                                    //         'rgba(255, 130, 99, 0.3)'
                                    //     ],
                                    //     borderColor: [
                                    //         'rgb(255, 130, 99)'
                                    //     ],
                                    //     borderWidth: 1
                                    // }
                                ]
                            },
                            options: {
                                scales: {
                                    x: {
                                        ticks: {
                                            color: chartAxisColor
                                        },
                                        grid: {
                                            color: chartGridColor
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: chartAxisColor
                                        },
                                        grid: {
                                            color: chartGridColor
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: chartAxisColor
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                <?php } ?>
            </div>
        </div>
    </div>

</div>