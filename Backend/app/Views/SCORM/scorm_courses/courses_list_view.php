<style>
    .collapsible {

        color: white;
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.2);
        width: 100%;
        border: none;
        text-align: center;
        outline: none;
        font-size: 12px;
    }

    .contented {
        padding: 0 18px;
        display: none;
        overflow: hidden;
        background-color: rgb(118, 118, 118);

    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>">Dashboard</a></li>
             
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header; ?></h4>
        </div>
    </div>
</div>
<div class="page-title">
    <?php if ($typeval == 8) { ?>
        <div class="title_right mb-3">
            <div class="col-md-5 col-sm-5   form-group pull-right">
                <a href="<?php echo base_url($create_new_course_link) ?>">
                    <button type="submit" class="btn btn-info btn-sm form-control">
                        <i class="ace-icon fa fa-key bigger-110"></i>+ Create Assessment
                    </button>
                </a>
            </div>
        </div>
    <?php } else { ?>
        <div class="title_right mb-3">
            <div class="col-md-5 col-sm-5   form-group pull-right">
                <a href="<?php echo base_url($create_new_course_link) ?>">
                    <button type="submit" class="btn btn-info btn-sm form-control">
                        <i class="ace-icon fa fa-key bigger-110"></i>+ Create Course
                    </button>
                </a>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row">
    <?php $userlevel = session()->get('userlevel');
    $userlevelarray  = explode(',', $userlevel);
    $client = session()->get('client');
    $clientarrayid = explode(',', $client); ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width=5%>#</th>
                            <th>Code - Course name</th>

                            <?php //if ($typeval != 8) { 
                            ?>
                            <!-- <th>Category</th> -->
                            <?php //} 
                            ?>
                            <?php if ($typeval != 5) { ?>
                                <th>Dur.</th>
                                <th>Uploads</th>
                            <?php } ?>
                            <?php //if (($typeval != 5) && ($typeval != 8)  && ($typeval != 9)) { 
                            ?>
                            <!-- <th width="15%">Meta</th> -->
                            <?php //} 
                            ?>
                            <?php if ($typeval == 5) { ?>
                                <th>Assigned Users</th>
                                <th>Scenarios</th>
                            <?php } ?>
                            <?php if ($typeval == 8 || $typeval == 9) { ?>
                                <th>Questions</th>
                            <?php } ?>
                            <th>Page</th>
                            <th>Settings</th>
                            <th>Edit</th>
                            <th>Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        // print_r(in_array('1',$clientarrayid));
                        if (in_array('6', $userlevelarray) || in_array('1', $clientarrayid)) {
                            foreach ($coursesDetails as $eachCourseDetails) {
                                // print_r($eachCourseDetails);
                                // echo "<pre>";
                                $clientarray = explode(',', $eachCourseDetails['client_id']);
                                $arrayEditable  = explode(',', $eachCourseDetails['editable']);


                                $course_code = (strlen($eachCourseDetails['course_code']) > 2) ? $eachCourseDetails['course_code'] . " - " : '';
                                $j = $j + 1;
                        ?>
                                <tr>
                                    <td width=5%><?php echo $j ?></td>
                                    <td><?php echo $course_code . '' . $eachCourseDetails['course_name'] ?></td>
                                    <?php //if ($typeval != 8) { 
                                    ?>
                                    <!-- <td><?php //echo $eachCourseDetails['category'];  
                                                ?></td> -->
                                    <?php //} 
                                    ?>
                                    <?php if ($typeval != 5) { ?>
                                        <td><?php echo $eachCourseDetails['duration'];  ?></td>
                                        <td><?php echo ($eachCourseDetails['promo_video'] != '') ? 'Promo' : '';  ?>
                                            <?php echo ($eachCourseDetails['promo_video'] != '' && $eachCourseDetails['thumbnail'] != '') ? "," : ''; ?>
                                            <?php echo ($eachCourseDetails['thumbnail'] != '') ? 'Thumb.' : '' ?>
                                            <?php echo ($eachCourseDetails['thumbnail'] != '' && $eachCourseDetails['upload'] != '') ? "," : ''; ?>
                                            <?php echo ($eachCourseDetails['upload'] != '') ? 'SCORM' : '' ?>
                                            <?php echo ($eachCourseDetails['pdf_filename'] != '' && $eachCourseDetails['upload'] != '') ? "," : ''; ?>
                                            <?php if ($eachCourseDetails['pdf_filename'] != '') {
                                                ",";
                                            } ?>
                                            <?php echo ($eachCourseDetails['pdf_filename'] != '') ? 'PDF' : '' ?></td>
                                    <?php } ?>
                                    <?php //if (($typeval != 5) && ($typeval != 8) && ($typeval != 9)) { 
                                    ?>
                                    <!-- <td><?php //echo $eachCourseDetails['metadata'];  
                                                ?></td> -->
                                    <?php //} 
                                    ?>
                                    <?php if ($typeval == 5) { ?>
                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/courseusersassigned_report') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id']; ?>">
                                                <button type="submit" class="btn btn-sm widget-icon btn-success"><?php echo $eachCourseDetails['user_count']; ?></button>
                                            </form>
                                        </td>
                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_scenarios_courses') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                                <input type="hidden" name="course_name" value="<?php echo $eachCourseDetails['course_name'] ?>">
                                                <button type="submit" class="btn btn-sm widget-icon btn-success"><span class="icon-book-open"></span></button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                    <?php if ($typeval == 8 || $typeval == 9) { ?>
                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url($question_list_view) ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                                <input type="hidden" name="course_name" value="<?php echo $eachCourseDetails['course_name'] ?>">
                                                <button type="submit" class="btn btn-sm widget-icon btn-success"><span class="icon-question"></span></button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                    <?php if ($eachCourseDetails['upload_type'] == '2') { ?>
                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url($page_link) ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                                <input type="hidden" name="course_name" value="<?php echo $eachCourseDetails['course_name'] ?>">
                                                <button type="submit" class="btn btn-sm widget-icon btn-primary"><span class="icon-magic"></span></button>
                                            </form>
                                        </td>
                                    <?php } else { ?>
                                        <td></td>
                                    <?php } ?>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($settings_link) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                            <input type="hidden" name="course_name" value="<?php echo $eachCourseDetails['course_name'] ?>">

                                            <button type="submit" class="btn btn-sm widget-icon btn-info"><span class="icon-settings"></span></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                            <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($delete_link) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>

                                </tr>
                                <?php }
                        } else {
                            foreach ($assignedCourselist as $eachCourseDetails) {
                                $course_code = (strlen($eachCourseDetails['course_code']) > 2) ? $eachCourseDetails['course_code'] . " - " : '';
                                $j = $j + 1;
                                $clientarray = explode(',', $eachCourseDetails['client_id']);
                                if (in_array(session()->get('client'), $clientarray)) { ?>
                                    <tr>
                                        <td width=5%><?php echo $j ?></td>
                                        <td><?php echo $course_code . '' . $eachCourseDetails['course_name'] ?></td>
                                        <?php if ($typeval != 8) { ?>
                                            <td><?php echo $eachCourseDetails['category'];  ?></td>
                                        <?php } ?>
                                        <td><?php echo $eachCourseDetails['duration'];  ?></td>
                                        <td><?php echo ($eachCourseDetails['promo_video'] != '') ? 'Promo' : '';  ?>
                                            <?php echo ($eachCourseDetails['promo_video'] != '' && $eachCourseDetails['thumbnail'] != '') ? "," : ''; ?>
                                            <?php echo ($eachCourseDetails['thumbnail'] != '') ? 'Thumb.' : '' ?>
                                            <?php echo ($eachCourseDetails['thumbnail'] != '' && $eachCourseDetails['upload'] != '') ? "," : ''; ?>
                                            <?php echo ($eachCourseDetails['upload'] != '') ? 'SCORM' : '' ?>
                                            <?php echo ($eachCourseDetails['pdf_filename'] != '' && $eachCourseDetails['upload'] != '') ? "," : ''; ?>
                                            <?php if ($eachCourseDetails['pdf_filename'] != '') {
                                                ",";
                                            } ?>
                                            <?php echo ($eachCourseDetails['pdf_filename'] != '') ? 'PDF' : '' ?></td>

                                        <?php if (($typeval != 5) && ($typeval != 8) && ($typeval != 9)) { ?>
                                            <td><?php echo $eachCourseDetails['metadata'];  ?></td>
                                        <?php } ?>
                                        <!-- <?php if ($typeval == 5) { ?>
                            <td>
                                <form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_scenarios') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                    <input type="hidden" name="course_name" value="<?php echo $eachCourseDetails['course_name'] ?>">
                                    <button type="submit" class="btn btn-sm widget-icon btn-success"><span class="icon-magic"></span></button>
                                </form>
                            </td>
                        <?php } ?> -->
                                        <?php if ($typeval == 8 || $typeval == 9) { ?>
                                            <td>
                                                <form class="form-horizontal" action="<?php echo base_url($question_list_view) ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                                    <input type="hidden" name="course_name" value="<?php echo $eachCourseDetails['course_name'] ?>">
                                                    <button type="submit" class="btn btn-sm widget-icon btn-success"><span class="icon-question"></span></button>
                                                </form>
                                            </td>
                                        <?php } ?>
                                        <?php if ($eachCourseDetails['editable'] == 1 || $eachCourseDetails['addby'] == session()->get('id_user')) { ?>
                                            <td>
                                                <form class="form-horizontal" action="<?php echo base_url($settings_link) ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                                    <input type="hidden" name="course_name" value="<?php echo $eachCourseDetails['course_name'] ?>">
                                                    <button type="submit" class="btn btn-sm widget-icon btn-info"><span class="icon-gear"></span></button>
                                                </form>
                                            </td>
                                            <td>
                                                <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="scourse_id" value="<?php echo $eachCourseDetails['scourse_id'] ?>">
                                                    <button type="submit" class="btn btn-sm widget-icon btn-warning"><span class="fa fa-pencil"></span></button>
                                                </form>
                                            </td>
                                            <td></td>
                                        <?php } else { ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        <?php } ?>
                                    </tr>
                        <?php  }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            var coll = document.getElementsByClassName("collapsible");
            var i;

            for (i = 0; i < coll.length; i++) {
                coll[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    var contented = this.nextElementSibling;
                    if (contented.style.display === "block") {
                        contented.style.display = "none";
                    } else {
                        contented.style.display = "block";

                    }
                });
            }
            $(document).ready(function() {

                $('#dynamic-table').DataTable();

            });
        </script>