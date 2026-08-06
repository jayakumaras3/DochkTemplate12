<?php if (session()->get('error')) :
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client =  session()->get('client');
$arraystakeholders  = explode(',', $client);

?>

<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/scorm_courses') ?>">Courses</a></li>
					
				</ol>
			</div>
			<h4 class="page-title">Export Excel Report</h4>
		</div>
	</div>
</div>

<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="card">
            <div class="card-body">
                <table class="table  table-sm table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Category</th>
                            <th>Download</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Export All C4U Courses Details</td>
                            <td><a href="<?php echo base_url('SCORM/Scorm_course_download/AllC4UCoursesExport') ?>"><button class="btn btn-sm btn-info">Export CSV</button></a></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Export Selected C4U Courses Details </td>
                            <td>
                                <form class="form-horizontal1" id="addcoursesForm" action="<?php echo base_url('SCORM/scorm_course_download/selectedC4UCoursesExport') ?>" method="POST"><?= csrf_field() ?>
                                    <div class="form-row">
                                        <select class="form-select select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" name="course_id[]" required="">
                                            <?php foreach ($all_courses as $courses) {
                                                //$key = array_search($courses['scourse_id'], array_column($getAllCoursesForClient, 'course_id'));
                                                //if (!empty($key) || $key === 0) {
                                                // } else {
                                                echo '<option value="' . $courses['scourse_id'] . '">' . $courses['course_name'] . '</option>';
                                                //}
                                            } ?>
                                        </select>
                                    </div><br>
                                    <div class="form-row">

                                        <button type="submit" class="btn btn-sm btn-info">Export Xlsx</button>
                                    </div>
                                    <?php if (isset($validation)) : ?>
                                        <div class=col-12 col-sm-4>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $validation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </form>

                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>