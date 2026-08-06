<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li>
             
                </ol>
            </div>
            <h4 class="page-title"><?php echo $sub_header_1; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title">Edit Client Details</h4>
                <form action="<?php echo base_url('Demo/cart/updateDemo') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                     <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">e-Mail</label>
                        <input type="text" name="email" class="form-control" placeholder="e-Mail" value="<?php echo $getUserDetails[0]['user_email']; ?>" required="" />
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Notes" value="<?php echo $getUserDetails[0]['notes']; ?>" />
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Password</label>
                        <input type="text" name="secret_code" class="form-control" placeholder="secret_code" value="<?php echo $getUserDetails[0]['secret_code']; ?>" required="" />
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Expiry date</label>
                        <input id="start_date" name="start_date" class="date-picker form-control" value="<?php echo $getUserDetails[0]['expiry_date']; ?>" placeholder="Expiry Date" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo isset($row[0]['start_date']) ? $row[0]['start_date'] : '' ?>">
                        <script>
                            function timeFunctionLong(input) {
                                setTimeout(function() {
                                    input.type = 'text';
                                }, 60000);
                            }
                        </script>
                    </div>
                    <div>
                        <input type="hidden" name="assignID" value="<?php echo $getUserDetails[0]['id']; ?>">
                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Assign</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- <h4 class="mb-3 header-title">Select C4U Course</h4> -->

                <form class="form-horizontal" id="addcoursesForm" mathed="post">
                     <?= csrf_field() ?>
                    <div class="form-row">
                        <select class="form-select select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" name="course_id[]" required="">
                            <?php foreach ($all_C4Ucourses as $courses) {
                                $key = array_search($courses['scourse_id'], array_column($getAssignedCourses, 'scourse_id'));
                                if (!empty($key) || $key === 0) {
                                } else {
                                    echo '<option value="' . $courses['scourse_id'] . '">' . $courses['course_name'] . '</option>';
                                }
                            } ?>
                        </select>
                    </div><br>
                    <div class="form-row">
                        <input type="hidden" name="type" value="2" />
                        <input type="hidden" name="cartAssignid" value="<?= $cartAssignid ?>" />
                        <input type="hidden" name="username" value="<?= $getUserDetails[0]['username']; ?>" />
                        <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">Add Course to Cart</button>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="card-body">
                <!-- <h4 class="mb-3 header-title">Select Demo Course</h4> -->
                <form class="form-horizontal" id="addcoursesDemoForm" mathed="post">
                     <?= csrf_field() ?>
                    <div class="form-row">
                        <select class="form-select select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" name="course_id[]" required="">
                            <?php foreach ($all_Democourses as $courses) {
                                $key = array_search($courses['scourse_id'], array_column($getAssignedCourses, 'scourse_id'));
                                if (!empty($key) || $key === 0) {
                                } else {
                                    echo '<option value="' . $courses['scourse_id'] . '">' . $courses['course_name'] . '</option>';
                                }
                            } ?>
                        </select>
                    </div><br>
                    <div class="form-row">
                        <input type="hidden" name="type" value="1" />
                        <input type="hidden" name="cartAssignid" value="<?= $cartAssignid ?>" />
                        <input type="hidden" name="username" value="<?= $getUserDetails[0]['username']; ?>" />
                        <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">Add Demo Course to Cart</button>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($getAssignedCourses  as $Cart) {
                            // print_r($Cart);
                            // exit();
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $Cart['course_name']; ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Demo/cart/delItemFromassignedCart') ?>" method="POST"><?= csrf_field() ?>
                                         <?= csrf_field() ?>
                                        <input type="hidden" name="cartid" value="<?php echo $Cart['cartid']; ?>">
                                        <input type="hidden" name="course_id" value="<?php echo $Cart['scourse_id']; ?>">
                                        <input type="hidden" name="username" value="<?php echo $getUserDetails[0]['username']; ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
                url: '<?php echo base_url('Demo/cart/addCoursetoassigedCart') ?>',
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
    $('#addcoursesDemoForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addcoursesDemoForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('Demo/cart/addCoursetoassigedCart') ?>',
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