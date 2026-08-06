<div id="menu_details_full">
    <!-- Sidebar Logo -->
    <?php if (!empty($clientdata[0]['logo'])) {
        // print_r($clientdata); 
    ?>
        <img src="<?php echo base_url('assets/assets/uploads/client_logo/' . $clientdata[0]['id_c'] . '/' . $clientdata[0]['logo']); ?>" alt="logo" height="40px" style="margin-top:23px;margin-bottom:23px;margin-left:50px">
    <?php } else {
    } ?>
    <!-- <img src="<?php echo base_url('assets/assets/uploads/Aristo_Theme/images/logo_.png'); ?>" alt="logo" height="60px"> -->
    <!-- Tabs for Menu and Transcript (Horizontal Layout) -->
    <?php if ($getAssessmentSettings) {
        foreach ($getAssessmentSettings as $value) {
            $type = $value['type'];
            // print_r($type."<br/>");
            if ($type == 53) {
                $MenuName = $value['value'];
            }
            if ($type == 54) {
                $TranscriptName = $value['value'];
            }
        }
        $MenuName = (isset($MenuName) &&  $MenuName != '') ? $MenuName : $assessment_sets['53'];
        $TranscriptName = (isset($TranscriptName) &&  $TranscriptName != '') ? $TranscriptName : $assessment_sets['54'];
    } ?>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#menu" title="<?php echo $MenuName ?>"><?php echo $MenuName ?></a></li>
        <li><a data-toggle="tab" href="#transcript" title="<?php echo $TranscriptName ?>"><?php echo $TranscriptName ?></a></li>
    </ul>
    <div class="tab-content">
        <div id="menu" class="tab-pane fade in active menu">
            <?php foreach ($pagedetails as $page) {
                if ($page['sub_page_main'] == 0) {
                    $isvisited = array_search(abs($page['page_number']), $scorm_suspend_data_arr);
            ?>
                    <form action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/' . $page['page_number']); ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="course_id" value="<?php echo $page['fk_course_id']; ?>" />
                        <input type="hidden" name="page_number" value="<?php echo $page['page_number']; ?>" />
                        <input type="hidden" name="typeOfLaunch" value="<?php echo $typeOfLaunch ?>" />
                        <span> <button style="all: unset; cursor: pointer;">
                                <p class="noDecoration">
                                    <?php
                                    echo $page['page_name'];
                                    ?>
                                </p>
                            </button>
                        </span>
                        <span class=" tick">
                            <?php
                            if (strlen($isvisited) > 0) {
                                echo ' <i class="fa fa-check" aria-hidden="true"></i>';
                            }
                            ?>
                        </span>
                    </form>

            <?php }
            } ?>
        </div>
        <div id="transcript" class="tab-pane fade menu">
            <p><?php if (isset($transcript)) {
                    foreach ($transcript as $script) { ?>
            <div class="transcript-item" style="font-size:12px;">
                <?php echo $script['audio']; ?>
            </div>
    <?php }
                } ?></p>
        </div>
    </div>
</div>