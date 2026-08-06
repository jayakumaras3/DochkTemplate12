<?php if (session()->get('error')) :
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client =  session()->get('client');
$arraystakeholders  = explode(',', $client);
?>
<style>
    .settings-section {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: none;
    }

    .settings-section .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .settings-section .section-title i {
        color: #6658dd;
    }

    [data-bs-theme="dark"] .settings-section .section-title i {
        color: #9298f5;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/Scorm_learn_group') ?>">Course Group</a></li>

                </ol>
            </div>
            <h4 class="page-title">Add Course to Group</h4>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-book-plus-outline"></i> Add Course to Group</h5>
                <form id="addcoursesForm"><?= csrf_field() ?>
                    <div class="mb-2">
                        <select class="form-select select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" name="course_id[]" required="">
                            <?php foreach ($all_courses as $courses) {
                                $key = array_search($courses['scourse_id'], array_column($assigned_courses, 'course_id'));
                                if (!empty($key) || $key === 0) {
                                } else {
                                    echo '<option value="' . $courses['scourse_id'] . '">' . $courses['course_name'] . '</option>';
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="hidden" name="sc_cgid" value="<?php echo $row[0]['sc_cgid'] ?>">
                        <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light">
                             Add Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-format-list-bulleted"></i> Courses in Group</h5>
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Language</th>
                            <th>Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($assigned_courses  as $assigned) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo  $assigned['coursename'] ?></td>
                                <td>
                                    <?php
                                    if (strlen($assigned['language']) > 2) { ?>
                                        <?php echo $assigned['language'] ?>
                                    <?php } ?>
                                </td>
                                <td width="10%">
                                    <form class="form-horizontal" action="<?php echo base_url($form_link_1) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_cgid" value="<?php echo  $row[0]['sc_cgid'] ?>">
                                        <input type="hidden" name="assign_id" value="<?php echo $assigned['assign_id'] ?>">
                                        <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
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
                url: '<?php echo base_url($form_link) ?>',
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