<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">My Reports</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-0">Course Status Report</h4>
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <div class="container d-flex justify-container-center">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="piechart3d" style="width: 80%; height: 80%;"></div>
                        </div>
                    </div>
                </div>
                <div id="cardCollpase3" class="collapse show">
                    <div class="text-center pt-3">
                        <div class="row mt-3">
                            <?php
                            // Initialize total counts for passed and completed
                            $totalPassedCompleted = 0;
                            $totalInprogressCompleted  = 0;
                            $totalNotStartedCompleted = 0;

                            // Loop through each status
                            foreach ($status as $eachstatus) {
                                // Combine passed and completed counts
                                if ($eachstatus['count_course_status'] != 0) {
                                    if ($eachstatus['course_status'] === '2') {
                                        $totalPassedCompleted = $eachstatus['count_course_status'];
                                    }
                                    // Set custom display for incomplete and not started
                                    if ($eachstatus['course_status'] == '1') {
                                        $totalInprogressCompleted = $eachstatus['count_course_status'];
                                        $lesson_status = 'In progress';
                                    }
                                    if ($eachstatus['course_status'] == '0') {
                                        $totalNotStartedCompleted = $eachstatus['count_course_status'];
                                        $lesson_status = 'Not started';
                                    }
                                }
                            }
                            ?>
                        </div> <!-- end row -->
                    </div>
                </div> <!-- collapsed end -->
                <script>
                    $(document).ready(function() {
                        google.charts.load('current', {
                            'packages': ['corechart']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                                ['Task', 'Hours per Day'],
                                ['Completed', <?php echo $totalPassedCompleted; ?>],
                                ['In Progress', <?php echo $totalInprogressCompleted; ?>],
                                ['Not Started', <?php echo $totalNotStartedCompleted; ?>],
                            ]);

                            var options = {
                                title: '',
                                pieSliceText: 'value',
                                is3D: true
                            };
                            var chart = new google.visualization.PieChart(document.getElementById('piechart3d'));
                            chart.draw(data, options);
                        }

                    });
                </script>

            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div>

    <!-- end row -->
    <div class="col-xl-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-2">Course Completed by Month</h4>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

                <canvas id="course-comp-month"></canvas>

            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div>
</div>

<!-- end row-->
<?php
$report_user_graph_completed = array_map(function ($item) {
    $item['month'] = date('M', mktime(0, 0, 0, $item['month'], 1));
    return $item;
}, $report_user_graph_completed);

// Generate an array with all months
$allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
?>
<script>
    // Parse the JSON data in JavaScript
    var courseData = <?php echo json_encode($report_user_graph_completed); ?>;

    // Extract xValues_dyn from the courseData array
    var xValues_dyn = <?php echo json_encode($allMonths); ?>;

    // Initialize an array to store y-axis values, filled with 0
    var yValues_dyn = Array(xValues_dyn.length).fill(0);

    // Fill in the y-axis values with actual data
    courseData.forEach(item => {
        var monthIndex = xValues_dyn.indexOf(item.month);
        if (monthIndex !== -1) {
            yValues_dyn[monthIndex] += item.complete_course_count;
        }
    });

    // Use the dynamic data in the chart
    new Chart("course-comp-month", {
        type: "bar",
        data: {
            labels: xValues_dyn,
            datasets: [{
                label: 'Total Courses',
                backgroundColor: "#4b88e4",
                data: yValues_dyn
            }]
        },
        options: {
            legend: {
                display: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });
</script>