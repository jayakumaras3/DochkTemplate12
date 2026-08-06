<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('category/dashboard') ?>">Meta Category
                            List</a></li>
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url('category/dashboard/view_category') ?>">Category</a></li>
                </ol>
            </div>
            <h4 class="page-title">Courses : <?php echo $cat_name; ?></h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <!-- start chat users-->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" id="addcategorycoursesForm">
                    <?= csrf_field() ?>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Active Courses</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select select2-multiple" data-toggle="select2" data-width="100%"
                                multiple="multiple" name="course_id[]" required="">
                                <option value="">-- Select Course --</option>
                                <?php foreach ($get_all_courses as $course) { ?>
                                    <option value="<?php echo $course['scourse_id']; ?>">
                                        <?php echo $course['course_name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit"
                                class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Link Course to Category
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="x_panel">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= lang('UI_Text.Course Code') ?></th>
                                <th><?= lang('UI_Text.Course Name') ?></th>
                                <th><?= lang('UI_Text.Duration') ?></th>
                                <th><?= lang('UI_Text.Language') ?></th>
                                <th><?= lang('UI_Text.Action') ?></th>
                                <th><?= lang('UI_Text.Unlink') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($get_cat_courses as $course) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $course['course_code']; ?> </td>
                                    <td><?php echo $course['course_name']; ?> </td>
                                    <td><?php echo $course['duration']; ?> </td>
                                    <td><?php echo $course['language']; ?> </td>
                                    <td>
                                        <form action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                            <?= csrf_field() ?>

                                            <input type="hidden" name="crid" value="<?php echo $course['scourse_id'] ?>">
                                            <input type="hidden" name="detail_type" value="6">
                                            <input type="hidden" name="tab" value="1">
                                            <button type="submit" onclick="this.form.submit(); this.disabled=true;"
                                                class="btn btn-success btn-xs waves-effect waves-light"><i
                                                    class="mdi mdi-play-circle-outline"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('category/dashboard/unlink_course') ?>"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to unlink the course?');">
                                            <?= csrf_field() ?>

                                            <input type="hidden" name="mc_id" value="<?php echo $course['mc_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-link-off"></span></button>
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
</div>
</div>
<script>
    $('#addcategorycoursesForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addcategorycoursesForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('category/dashboard/add_course_to_category') ?>',
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