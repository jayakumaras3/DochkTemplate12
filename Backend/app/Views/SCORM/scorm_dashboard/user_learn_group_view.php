<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
if (session()->get('error')) :
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client =  session()->get('client');
$arraystakeholders  = explode(',', $client);
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
                <div class="page-title-right">
                    <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3"><i
                            class="mdi mdi-plus"></i> <a href="<?php echo base_url() . "SCORM/Scorm_learn_group"; ?>">
                            Create Course Groups
                        </a></button>
                </div>
            <?php } ?>
        </div>
        <h4 class="page-title">My Course Groups</h4>
    </div>
</div>
</div>
<div class="row">
    <div class="col-12">
        <?php if (!empty($client_group) || !empty($client_group_to_me)) {
        ?>
            <div class="row row-cols-1 row-cols-md-3 g-3">
                <?php
                $j = 0;
                foreach ($client_group as $group) {
                    $group_name = $group['description'];

                    if (isset($group['logo']) && $group['logo'] != '') {
                        $thumbnail =  base_url('assets/assets/uploads/group_logo/' . $group['sc_cgid'] . '/' . $group['logo']);
                    } else {
                        $thumbnail = base_url('assets/assets/img/course_group.jpg');
                    }
                    $playimg = base_url('assets/assets/img/play.png');
                ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card  ribbon-box">
                            <form class="form-horizontal" action="<?php echo base_url('my_training/course_group_list') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="sc_cgid" value="<?php echo $group['sc_cgid'] ?>">
                                <button style="border: none;border:0; padding-top: 5px; outline: none; background: none;">
                                    <div style="  background-color: #000; color: #fff;  opacity: .7;z-index:1;">
                                        <img src="<?= $thumbnail ?>" style="display: block; width: 100%; " alt=" ">
                                    </div><!-- <img style="  position: absolute; width: 40px;height: 40px; left: 60%; top: 45%; margin-left: -48px;  margin-top: -48px; " src="<?php echo $playimg; ?>" alt="play" class="playBtn"> -->


                                    <div class="card-body">
                                        <h5 class="font-12 my-1 sp-line-1"><a class="text-dark"><?php echo $group_name ?></a> </h5>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php
                }

                foreach ($client_group_to_me as $groupa) {
                    $group_namex = $groupa['description'];
                    $orginalthumbnail = '';
                    if (isset($groupa['logo']) && $groupa['logo'] != '') {
                        $orginalthumbnail =  base_url('assets/assets/uploads/group_logo/' . $groupa['sc_cgid'] . '/' . $groupa['logo']);
                    }
                    $samplethumbnail  = base_url('assets/assets/img/course_group.png');

                    $playimg = base_url('assets/assets/img/play.png');
                ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card  ribbon-box">

                            <form class="form-horizontal" action="<?php echo base_url('my_training/course_group_list') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="sc_cgid" value="<?php echo $groupa['sc_cgid'] ?>">
                                <button style="border: none;border:0; padding-top: 5px; outline: none; background: none;">
                                    <div style="  background-color: #000; color: #fff;  opacity: .7;z-index:1;">
                                        <?php
                                        if (strlen($orginalthumbnail) > 5) {

                                            $thumbnail = $orginalthumbnail;
                                        } else {
                                            $thumbnail = $samplethumbnail;
                                        } ?>
                                        <img src="<?= $thumbnail ?>" style="display: block; width: 100%; " alt=" ">

                                    </div><!-- <img style="  position: absolute; width: 40px;height: 40px; left: 60%; top: 45%; margin-left: -48px;  margin-top: -48px; " src="<?php echo $playimg; ?>" alt="play" class="playBtn"> -->


                                    <div class="card-body">
                                        <h5 class="font-12 my-1 sp-line-1"><a class="text-dark"><?php echo $group_namex; ?></a> </h5>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        <?php } else { ?>
            <div class="persistent-warning">
                <div class="danger-text">
                    No courses assigned.
                </div>
            </div>

        <?php }
        ?>
        <!-- end col -->
    </div>
    <!-- end row-->
</div> <!-- end col -->
</div>
<!-- end row-->


</div>

</div>