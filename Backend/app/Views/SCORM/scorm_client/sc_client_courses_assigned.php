<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a></li>
               
                </ol>
            </div>
            <h4 class="page-title"><?php echo $sub_header_1; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Course Name</th>
                            <!-- <th>Group</th>
                                <th>Users</th>
                                <th width="20%">Dispatch</th> -->
                            <th width="10%">Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($getAllCoursesForClient as $courses) {
                            $j = $j + 1;
                        ?>
                            <tr>
                                <td><?php echo $j;  ?></td>
                                <td><?php echo $courses['course_name']; ?></td>
                                <!-- <td><?php echo $courses['group_name']; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($form_link4) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="scourse_id" value="<?php echo $courses['course_id']; ?>">
                                            <button type="submit" class="btn btn-sm widget-icon btn-success"><?php echo $courses['user_count']; ?></button>
                                        </form>
                                    </td>
                                    <td></td> -->
                                <td width="10%">
                                    <form class="form-horizontal" action="<?php echo base_url($form_link1) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_cr_as_id" value="<?php echo  $courses['sc_cr_as_id']; ?>">
                                        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <?php if ($all_courses) { ?>
            <div class="card">
                <div class="card-body"> 
                    <h6>Add Single Course</h6>
                    <form class="form-horizontal" id="addcoursesForm"><?= csrf_field() ?>
                        <div class="mb-3">
                            <select class="form-select select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" name="course_id[]" required="">
                                <?php foreach ($all_courses as $courses) {
                                    $key = array_search($courses['scourse_id'], array_column($getAllCoursesForClient, 'course_id'));
                                    if (!empty($key) || $key === 0) {
                                    } else {
                                        echo '<option value="' . $courses['scourse_id'] . '">' . $courses['course_name'] . '</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Editable : </label>&nbsp;
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" name="editable" class="form-check-input" value="1"> Yes
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" name="editable" class="form-check-input" value="0" checked> No
                                </label>
                            </div>
                        </div><br>
                        <div class="mb-3">
                            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
                            <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">Add Course</button>
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


        <?php } ?>
        <?php if ($coursegroupdata) { ?>

            <div class="card">
                <div class="card-body">
                    <h6>Add Bulk Courses</h6>
                    <form action="<?php echo base_url($form_link3) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                        <div class="mb-3">
                            <select name="group_id" class="form-control">
                                <?php
                                foreach ($coursegroupdata  as $eachcoursegroupdata) {
                                    echo '<option value="' . $eachcoursegroupdata['sc_cgid'] . '">' . $eachcoursegroupdata['description'] . ' (' . $eachcoursegroupdata['assign_id_count'] . ')' . '</option>';
                                }
                                ?>
                            </select>

                        </div><br>
                        <div class="mb-3">
                            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_005') ?>')">Add Group</button>
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
</div>
<?php } ?>
</div>

<script>
    $('#addcoursesForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addcoursesForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url($form_link2) ?>',
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