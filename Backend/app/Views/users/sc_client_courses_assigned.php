<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>">Dashboard</a></li>
                 
                </ol>
            </div>
            <h4 class="page-title">Courses</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 col-sm-12">

                <div class="x_panel">
                    <?php
                    $courseTypes = array(); // Initialize the array to hold unique course types
                    $orderedTypes = array(5,2, 1, 8, 99); // Order of tab types (C4U, AR/VR, Demo, SCORM)

                    foreach ($getTypeCourses as $courses) {
                        $type = $courses['type'];
                        if ($type <= 50 && !in_array($type, $courseTypes)) {
                            if (in_array($type, array(1, 99))) {
                                $type = 99; // Combine Demo and SCORM into the same tab
                            }
                            $courseTypes[] = $type; // Store unique course types
                        }
                    }
                    ?>
                    <?php
                    $defaultIndex = -1; // Initialize with a default value

                    foreach ($orderedTypes as $index => $type) {
                        // Check if the type value is the desired default value
                        if (in_array($type, $courseTypes)) {
                            $defaultIndex = $index;
                            break; // Exit the loop once the default index is found
                        }
                    }

                    if ($defaultIndex === -1) {
                        // Handle the case when the default index was not found
                        // You might want to set a default fallback index here
                        $defaultIndex = 0;
                    }
                    ?>
                    <div class="row">

                        <div class="card-body ">

                            <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                                <?php
                                foreach ($orderedTypes as $index => $type) {

                                    if (in_array($type, $courseTypes)) {   // print_r($index);      ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?= ($index === $defaultIndex) ? ' active' : '' ?>" id="home-tab" data-toggle="tab" href="#home<?= $type ?>" role="tab" aria-controls="home" aria-selected="<?= ($index === $defaultIndex) ? 'true' : 'false' ?>">
                                                <?php
                                                switch ($type) {
                                                    case 2:
                                                        echo 'C4U';
                                                        break;
                                                    case 3:
                                                        echo 'Aristo';
                                                        break;
                                                    case 5:
                                                        echo 'AR/VR/Sim';
                                                        break;
                                                    case 8:
                                                        echo 'Assessment';
                                                        break;
                                                    case 1:
                                                    default:
                                                        echo 'Demo & SCORM';
                                                        break;
                                                }
                                                ?>
                                            </a>
                                        </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                            <div class="card-body ">
                                <div class="tab-content" id="myTabContent">
                                    <?php
                                    foreach ($orderedTypes as $index => $type) {
                                    ?>
                                        <div class="tab-pane<?= ($index === $defaultIndex) ? ' show active' : '' ?>" id="home<?= $type ?>" role="tabpanel" aria-labelledby="home-tab">
                                            <!-- Display courses of this type -->
                                            <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Course Name</th>
                                                        <th>Duration (min)</th>
                                                        <th>Report</th>
                                                        <th>Assigned Users</th>
                                                        <?php if ($type == 5) { ?>
                                                            <th>Scenarios</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 0;
                                                    foreach ($getClientCourses as $courses) {
                                                        $courseType = $courses['type'];
                                                        if (($type == 99 && in_array($courseType, array(1, 99))) || ($courseType == $type)) {
                                                            $i = $i + 1;
                                                    ?>
                                                            <tr>


                                                                <td><?php echo $i;  ?></td>
                                                                <td><?php echo $courses['course_name']; ?></td>
                                                                <!-- <td><?php
                                                                            $type =  $courses['type'];
                                                                            switch ($type) {
                                                                                case 2:
                                                                                    echo 'C4U';
                                                                                    break;
                                                                                case 3:
                                                                                    echo 'Aristo';
                                                                                    break;
                                                                                case 5:
                                                                                    echo 'AR/VR/Sim';
                                                                                    break;
                                                                                case 8:
                                                                                    echo 'Assessment';
                                                                                    break;
                                                                                case 1:
                                                                                default:
                                                                                    echo 'Demo & SCORM';
                                                                                    break;
                                                                            }
                                                                            ?></td> -->
                                                                <td><?php echo $courses['duration']; ?></td>
                                                                <td>
                                                                    <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/report_admin'); ?>" method="POST"><?= csrf_field() ?>
                                                                        <input type="hidden" name="scourse_id" value="<?php echo $courses['course_id']; ?>">
                                                                        <button type="submit" class="btn btn-sm widget-icon btn-danger"><span class="icon-pie-chart"></span></button>
                                                                    </form>
                                                                </td>
                                                                <td>
                                                                    <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/usersassigned_report') ?>" method="POST"><?= csrf_field() ?>
                                                                        <input type="hidden" name="scourse_id" value="<?php echo $courses['course_id']; ?>">
                                                                        <button type="submit" class="btn btn-sm widget-icon btn-success"><?php echo $courses['user_count']; ?></button>
                                                                    </form>
                                                                </td>
                                                                <?php if ($type == 5) { ?>
                                                                    <td>

                                                                        <form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_scenarios/XAPIMangeCourses') ?>" method="POST"><?= csrf_field() ?>
                                                                            <input type="hidden" name="scourse_id" value="<?php echo $courses['course_id'] ?>">
                                                                            <input type="hidden" name="course_name" value="<?php echo $courses['course_name'] ?>">
                                                                            <input type="hidden" name="type" value="<?php echo $courses['type'] ?>">
                                                                            <button type="submit" class="btn btn-sm widget-icon btn-info"><span class="icon-settings"></span></button>
                                                                        </form>

                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        $('#addcoursesForm').on('submit', function(event) {

            event.preventDefault();

            var dataString = new FormData($('#addcoursesForm')[0]);

            if (typeof FormData !== 'undefined') {

                $.ajax({
                    url: '<?php echo base_url('SCORM/scorm_client/add_course_to_client') ?>',
                    type: "POST",
                    data: dataString,
                    async: false,
                    processData: false,
                    contentType: false,
                    success: function(data) {

                        var obj = JSON.parse(data);

                        console.log(obj);

                        if (obj.status === 'OK') {
                            console.log('inside on condition');
                            //window.location.href = 'project_settings.php';
                            location.reload();


                        } else {

                            alert('error', 'Something Went Wrong! Please contact Site Admin!');
                        }

                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('request failed');
                    }
                })
            } else {
                message("Your Browser Don't support FormData API! Use IE 10 or Above!");
            }

        });
        $(document).ready(function() {
            $('#dynamic-table').DataTable();
        });
    </script>