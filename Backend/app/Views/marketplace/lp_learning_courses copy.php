<?php
$userlevel = session('userlevel');
$client = session('client');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
?>

<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');


?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url($header_link) ?>"><?php echo $header_title ?></a></li>

                </ol>
            </div>
            <h4 class="page-title">Learning Courses</h4>
        </div>
    </div>
</div>
<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel = explode(',', $userlevel);
if (in_array('6', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
    ?>
    <div class="row">
        <form action="<?php echo base_url('marketplace/Learning_dashboard/add_users_to_learning_plan_view'); ?>"
            method="post">
            <input type="hidden" name="cid" value="MQ==">
            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3"><i
                    class="mdi mdi-plus"></i> Add Users</button>
        </form>

    </div>
<?php } ?>

<?php
if (count($course_details) > 0) {
    foreach ($course_details as $key => $clienteachCourseddata) {

        if (isset($clienteachCourseddata['thumbnail']) && $clienteachCourseddata['thumbnail'] != '') {
            $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clienteachCourseddata['scourse_id'] . '/' . $clienteachCourseddata['thumbnail']);
        } else {
            $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
        }
        ?>
        <div id="holder" class="row row-cols-1 row-cols-md-3 g-3 ">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body" style="text-align:left; ">
                        <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>" method="POST">
                            <input type="hidden" name="crid" value="<?php echo $clienteachCourseddata['scourse_id'] ?>">
                            <input type="hidden" name="detail_type" value="9">
                            <input type="hidden" name="mp_id" value="<?php echo $mp_id ?>">
                            <input type="hidden" name="tab" value="1">
                            <button
                                style="border: none; border:0; padding-top: 5px; outline: none; background: none; width: 100%; text-align:center">
                                <div style="display: box;
                                                            display: flex;
                                                            box-align: center;
                                                            align-items: center;
                                                            box-pack: center;
                                                            justify-content: center;">
                                    <img class="img-fluid mx-auto d-block rounded" src="<?= $thumbnail ?>"
                                        style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);  height:150px;"
                                        alt="">
                                    <img style=" 
                                height: 40px;
                                width: 40px;
                                position: absolute;
                                opacity: 0.5;" src="<?php echo base_url('assets/assets/img/play.png'); ?>" alt="play"
                                        class="playBtn">
                                </div>
                            </button>
                        </form>
                        <div style="padding-left: 10px;">
                            <h5 class="font-12 my-1 sp-line-1"><a class="text-dark"
                                    title=""><?php echo $clienteachCourseddata['course_name']; ?></a> </h5>
                            <span class="font-10 text-muted"> Duration : <?php echo $clienteachCourseddata['duration']; ?>
                                min</span>
                            <?php //echo $clienteachCourseddata['course_status'];
                            
                                    $course_status = $clienteachCourseddata['mode'];
                                    $type = $clienteachCourseddata['type'];
                                    ?>
                            <?php
                            if ($course_status == '1') { ?>
                                <h5><span class="badge badge-soft-danger rounded-pill">Development

                                    <?php } elseif ($course_status == 2) { ?>
                                        <h5><?php if ($clienteachCourseddata['course_status'] == '2') { ?>
                                                <span class="badge badge-soft-success rounded-pill">Completed</span>
                                            <?php } elseif ($clienteachCourseddata['course_status'] == '1' || $clienteachCourseddata['lesson_status'] == 'incomplete') { ?>
                                                <span class="badge badge-soft-info rounded-pill">In Progress</span>
                                            <?php } elseif ($clienteachCourseddata['course_status'] == '0') { ?>
                                                <span class="badge badge-soft-danger rounded-pill">Not Started</span>
                                            <?php } else { ?>
                                                <span class="badge badge-soft-danger rounded-pill">Not Started</span>
                                            <?php } ?>

                                        </h5>
                                    <?php } elseif ($course_status == '3') { ?>
                                        <h5><span class="badge badge-soft-danger rounded-pill">Alpha
                                            <?php } elseif ($course_status == '4') { ?>
                                                <h5><span class="badge badge-soft-danger rounded-pill">Alpha 2
                                                    <?php } elseif ($course_status == '5') { ?>
                                                        <h5><span class="badge badge-soft-danger rounded-pill">Beta
                                                            <?php } elseif ($course_status == '6') { ?>
                                                                <h5><span class="badge badge-soft-danger rounded-pill">Beta 2
                                                                    <?php } elseif ($course_status == '7') { ?>
                                                                        <h5><span class="badge badge-soft-danger rounded-pill">Gamma
                                                                            <?php } elseif ($course_status == '8') { ?>
                                                                                <h5><span
                                                                                        class="badge badge-soft-danger rounded-pill">Gamma
                                                                                        2
                                                                                    <?php } else {
                                if ($clienteachCourseddata['course_status'] == 2) { ?>
                                                                                            <h5><span
                                                                                                    class="badge badge-soft-success rounded-pill">Completed
                                                                                                <?php } elseif ($clienteachCourseddata['course_status'] == 1 || $clienteachCourseddata['lesson_status'] == 'incomplete') { ?>
                                                                                                    <h5><span
                                                                                                            class="badge badge-soft-info rounded-pill">In
                                                                                                            Progress
                                                                                                        <?php } elseif ($clienteachCourseddata['course_status'] == 0) { ?>
                                                                                                            <h5><span
                                                                                                                    class="badge badge-soft-success rounded-pill">Not
                                                                                                                    Started
                                                                                                                <?php } else { ?>
                                                                                                                    <h5><span
                                                                                                                            class="badge badge-soft-success rounded-pill">Not
                                                                                                                            Started
                                                                                                                        <?php }
                            }
                            echo '</span></h5>';
                            ?>


                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">My Record - <?php echo $clienteachCourseddata['course_name']; ?></h4>
                        <div class="col-sm-12">
                            <?php if (isset($clienteachCourseddata['description'])) {
                                if (strlen($clienteachCourseddata['description']) > 10) {
                                    if (strlen($clienteachCourseddata['description']) > 5) {
                                        echo $clienteachCourseddata['description'];
                                    }
                                }
                            } ?>
                        </div>
                        <?php
                        if (strlen($clienteachCourseddata['objectives']) > 5) {
                            echo '  <div class="row"><div class="col-md-12"><div>';
                            $objectives = $clienteachCourseddata['objectives'];
                            print_r(str_replace("<li>", '<p class="text-muted"><i class="mdi mdi-checkbox-marked-circle-outline h6 text-primary me-2"></i>', $objectives, $i));
                            str_replace("</li>", '</p>', $i, $k);
                            echo '</div></div></div>';
                        }
                        ?>
                         <?php
                                        if (strlen($clienteachCourseddata['objective']) > 5) {
                                            $objectives = explode("| ", $clienteachCourseddata['objective']);

                                            echo '<ul class="text-muted">'; // Open UL with class
                                            foreach ($objectives as $obj) {
                                                echo '<li>' . htmlspecialchars($obj) . '</li>'; // Each objective in <li>
                                            }
                                            echo '</ul>'; // Close UL
                                        }
                                        ?>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>

        <?php
    }
}
?>