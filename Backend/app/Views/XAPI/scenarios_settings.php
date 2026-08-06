<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <!-- <li class="breadcrumb-item"><a href="<?php echo base_url($course_header_link) ?>"><?php echo $course_header ?></a></li> -->
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a></li>

                </ol>
            </div>
            <h4 class="page-title"><?php echo $header2; ?></h4>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Instructions</th>
                        <th>Value</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $j = 0;
                    if (!empty($scenario_settings)) {
                        foreach ($scenario_settings as $s) {
                            $j = $j + 1;
                    ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $s['variable_description']; ?></td>
                                <td><?php echo $s['instructions']; ?></td>
                                <td><?php $type = $s['input_variable_type'];
                                    if ($type == 1) {
                                        echo  $s['value'];
                                    } else {
                                        echo  $s['dvalue'];
                                    }
                                    ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($settings_form) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="xsis" value="<?php echo $s['xsis'] ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>