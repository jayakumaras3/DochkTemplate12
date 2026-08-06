<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/scorm_meta_category/category'); ?>">Category</a></li>
                </ol>
            </div>
            <h4 class="page-title">Category Add Course</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('SCORM/scorm_meta_category/addclienttocategory') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <select name="client" class="form-control">
                               
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <input type="hidden" name="category_id" value="<?php echo $sc_mcid; ?>">
                            <button type="submit" class="btn btn-outline-info waves-effect waves-light">Add Course - To be done</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $j = 0;
                        foreach ($row  as $course) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo  $course['course_name']; ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="scourse_id" value="<?php echo $course['scourse_id']; ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
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
