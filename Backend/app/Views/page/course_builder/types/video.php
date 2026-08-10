    <script>
        var TimeStore = 0;

        function GetVideoTime() {
            var vid1 = document.getElementById("vidArea");
            if (vid1) {
                var currentTime = vid1.currentTime;
                TimeStore = currentTime;
                return currentTime;
            }
            return '';
        }

        function formatTime(seconds) {
            var mins = Math.floor(seconds / 60);
            var secs = Math.floor(seconds % 60);
            return mins + ":" + (secs < 10 ? "0" : "") + secs;
        }

        function goToSession(timeInSeconds) {

            var vid1 = document.getElementById("vidArea");
            if (vid1) {
                vid1.currentTime = timeInSeconds;
                vid1.play();
                console.log("Jumped to:", formatTime(timeInSeconds));
            }
        }

        function showCurrentTime() {
            var formattedTime = GetVideoTime();
            if (formattedTime) {
                document.getElementById("currentTimeDisplay").innerText =
                    "Current Video Time: " + formattedTime + " (In seconds: " + TimeStore.toFixed(2) + ")";
                console.log("Current Video Time:", formattedTime);
            } else {
                document.getElementById("currentTimeDisplay").innerText =
                    "No video is currently playing.";
            }
        }
    </script>

                                        <?php
                                        $Video = isset($pageVideo[0]['filename']) ? $pageVideo[0]['filename'] : '';
                                        $Vtt = isset($pageVtt[0]['filename']) ? $pageVtt[0]['filename'] : '';
                                        if ($Video != '') {
                                            $video_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/video/" . $Video;
                                        }
                                        $vtt_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/vtt/" . $Vtt;
                                        $path = FCPATH . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/video/" . $Video;

                                    ?>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <?php
                                                if (file_exists($path) && ($Video != '')) { ?>

                                                    <video src="<?php echo $video_path; ?>" style="width: 100%; height: auto;"
                                                        id="vidArea" controls controlsList="nodownload" disablePictureInPicture>
                                                        <?php if (file_exists($vtt_path) && !empty($Vtt)) { ?>
                                                            <track id="englishTrack" kind="captions" src="<?php echo $vtt_path; ?>"
                                                                srclang="en" label="English" default>
                                                        <?php } ?>
                                                    </video>

                                                <?php } else { ?>
                                                    <div class="text-center py-4">
                                                        <h6 class="fw-semibold mb-1"><i class="mdi mdi-video-outline"></i> <?php echo lang('UI_Text.CB_No_Video_Uploaded'); ?></h6>
                                                        <p class="text-muted mb-0"><?php echo lang('UI_Text.CB_Upload_Video_File_Sub'); ?></p>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($row['type'] == 2 || $row['type'] == 8 || $row['type'] == 9) { ?>
                                                    <div class="row">
                                                        <?php if (!empty($pageVideo)) { ?>
                                                            <div class="col-12 col-md-12 col-lg-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <?php
                                                                        $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $row['createdon'] . '/assets/video';
                                                                        if (!empty($pageVideo)) {
                                                                            echo '<table class="table  table-sm">';
                                                                            echo '<tr><th>' . lang('UI_Text.CB_Video_File') . '</th><th>' . lang('UI_Text.on') . '</th><th>' . lang('UI_Text.by') . '</th><th>' . lang('UI_Text.Action') . '</th></tr>';

                                                                            foreach ($pageVideo as $video) {

                                                                                echo '<tr><td>';
                                                                                echo $video['filename'];
                                                                                echo '</td><td>';
                                                                                echo date('d-m-Y', $video['createdon']);
                                                                                echo '</td><td>';
                                                                                echo $video['createdby'];
                                                                                echo '</td><td>'; ?>
                                                                                <?php if ((in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel)) && $row['status'] != 8) { ?>
                                                                                    <form class="form-horizontal"
                                                                                        action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/del_file'); ?>"
                                                                                        method="POST"><?= csrf_field() ?>
                                                                                        <input type="hidden" name="page_id"
                                                                                            value="<?php echo $row['page_id'] ?>">
                                                                                        <input type="hidden" name="fileloc"
                                                                                            value="<?php echo $folderloc . '/' . $video['filename']; ?>">
                                                                                        <input type="hidden" name="file_name"
                                                                                            value="<?php echo $video['filename']; ?>">
                                                                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light rounded-pill"
                                                                                            title="<?php echo esc(lang('Buttons.Delete')); ?>"
                                                                                            aria-label="<?php echo esc(lang('Buttons.Delete')); ?>"
                                                                                            onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
                                                                                                class="mdi mdi-trash-can-outline" aria-hidden="true"></span></button>
                                                                                    </form>
                                                                                <?php } ?>
                                                                        <?php echo '</td></tr>';
                                                                            }
                                                                            echo '</table>';
                                                                        } else {
                                                                            echo lang('UI_Text.CB_No_Video_Files');
                                                                        }
                                                                        ?>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        <?php } ?>
                                                        <?php if (empty($pageVideo)) { ?>
                                                            <?php if ($row['status'] != 8) { ?>
                                                                <div class="col-12 col-md-12 col-lg-12 mg-t-10">
                                                                    <div class="card">

                                                                        <div class="card-body">
                                                                            <div class="form-row">
                                                                                <form class="form-horizontal2" enctype="multipart/form-data"
                                                                                    action=<?php echo base_url('SCORM/course_builder/scorm_course_pages/uploadvideo'); ?> method="post" id="uploadForm"><?= csrf_field() ?>
                                                                                    <div class="form-group col-md-12 mb-2">
                                                                                        <label><?php echo lang('UI_Text.CB_Select_Language'); ?></label>
                                                                                        <select name="language" class="form-control">
                                                                                            <option value="1"><?php echo lang('UI_Text.Language_Name_English'); ?></option>
                                                                                            <option value="2"><?php echo lang('UI_Text.Language_Name_Spanish'); ?></option>
                                                                                            <option value="3"><?php echo lang('UI_Text.Language_Name_French'); ?></option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label class="fw-semibold"><?php echo lang('UI_Text.CB_Video_File'); ?> <span class="text-danger">*</span></label>
                                                                                        <input type="file" name="file" class="form-control" accept=".mp4,.MP4"
                                                                                            required />
                                                                                    </div>
                                                                                    <div class="form-group col-md-12 mb-2">
                                                                                        <input type="hidden" name="course_id"
                                                                                            value="<?php echo $course_id ?>">
                                                                                        <input type="hidden" name="page_id"
                                                                                            value="<?php echo $page_id ?>">
                                                                                        <button type="submit"
                                                                                            class="btn btn-outline-warning waves-effect btn-sm waves-light form-control rounded-pill"
                                                                                            id="uploadButton"><?php echo lang('Buttons.Upload_Video'); ?></button>
                                                                                    </div>
                                                                                    <?php if (isset($promovalidation)): ?>
                                                                                        <div class="form-group col-md-12">
                                                                                            <div class="alert alert-white" role="alert">
                                                                                                <?= $promovalidation->listErrors() ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } ?>

                                                        <?php if ($row['type'] == 2 || $row['type'] == 8 || $row['type'] == 9) { ?>

                                                            <div class="col-12 col-md-12 col-lg-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <?php
                                                                        $fileloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $row['createdon'] . '/assets/vtt';
                                                                        if (!empty($pageVtt)) {
                                                                            echo '<table class="table  table-sm">';
                                                                            echo '<tr><th>' . lang('UI_Text.CB_VTT_File') . '</th><th>' . lang('UI_Text.on') . '</th><th>' . lang('UI_Text.by') . '</th><th>' . lang('UI_Text.Action') . '</th></tr>';

                                                                            foreach ($pageVtt as $vtt) {

                                                                                echo '<tr><td>';
                                                                                echo $vtt['filename'];
                                                                                echo '</td><td>';
                                                                                echo date('d-m-Y', $vtt['createdon']);
                                                                                echo '</td><td>';
                                                                                echo $vtt['createdby'];
                                                                                echo '</td><td>'; ?>
                                                                                <?php if ((in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel)) && $row['status'] != 8) { ?>
                                                                                    <form class="form-horizontal"
                                                                                        action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/del_file'); ?>"
                                                                                        method="POST"><?= csrf_field() ?>
                                                                                        <input type="hidden" name="page_id"
                                                                                            value="<?php echo $row['page_id'] ?>">
                                                                                        <input type="hidden" name="fileloc"
                                                                                            value="<?php echo $fileloc . '/' . $vtt['filename']; ?>">
                                                                                        <input type="hidden" name="file_name"
                                                                                            value="<?php echo $vtt['filename']; ?>">
                                                                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light rounded-pill"
                                                                                            title="<?php echo esc(lang('Buttons.Delete')); ?>"
                                                                                            aria-label="<?php echo esc(lang('Buttons.Delete')); ?>"
                                                                                            onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
                                                                                                class="mdi mdi-trash-can-outline" aria-hidden="true"></span></button>
                                                                                    </form>
                                                                                <?php } ?>
                                                                            <?php echo '</td></tr>';
                                                                            }

                                                                            echo '</table>';
                                                                        } else {
                                                                            if ($row['status'] != 8) { ?>

                                                                                <div class="form-row">
                                                                                    <h6 class="fw-semibold mb-1"><i class="mdi mdi-closed-caption-outline"></i> <?php echo lang('UI_Text.CB_Upload_Closed_Caption_File'); ?></h6>
                                                                                    <p class="text-muted mb-2"><?php echo lang('UI_Text.CB_Add_Vtt_File_Sub'); ?></p>

                                                                                    <form class="form-horizontal2 row g-2" enctype="multipart/form-data"
                                                                                        action=<?php echo base_url('SCORM/course_builder/scorm_course_pages/uploadvtt'); ?> method="post" id="uploadVttForm"><?= csrf_field() ?>
                                                                                        <div class="form-group col-12">
                                                                                            <label><?php echo lang('UI_Text.CB_Select_Language'); ?></label>
                                                                                            <select name="language" class="form-control">
                                                                                                <option value="1"><?php echo lang('UI_Text.Language_Name_English'); ?></option>
                                                                                                <option value="2"><?php echo lang('UI_Text.Language_Name_Spanish'); ?></option>
                                                                                                <option value="3"><?php echo lang('UI_Text.Language_Name_French'); ?></option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group col-12">
                                                                                            <label class="fw-semibold"><?php echo lang('UI_Text.CB_VTT_File'); ?> <span class="text-danger">*</span></label>
                                                                                            <input type="file" name="file" class="form-control" accept=".vtt,.VTT"
                                                                                                required />
                                                                                            <small class="text-muted    ">
                                                                                                <?php echo lang('UI_Text.CB_Vtt_Filename_Format_Note'); ?>
                                                                                            </small>
                                                                                        </div>
                                                                                        <div class="form-group col-12">
                                                                                            <input type="hidden" name="course_id"
                                                                                                value="<?php echo $course_id ?>">
                                                                                            <input type="hidden" name="page_id"
                                                                                                value="<?php echo $page_id ?>">
                                                                                            <button type="submit"
                                                                                                class="btn btn-outline-success waves-effect btn-sm waves-light form-control rounded-pill"
                                                                                                id="uploadVttButton"><?php echo lang('UI_Text.CB_Upload_VTT'); ?>x</button>
                                                                                        </div>
                                                                                        <?php if (isset($promovalidation)): ?>
                                                                                            <div class="form-group col-md-12">
                                                                                                <div class="alert alert-white" role="alert">
                                                                                                    <?= $promovalidation->listErrors() ?>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php endif; ?>
                                                                                    </form>
                                                                                </div>

                                                                        <?php }
                                                                        }
                                                                        ?>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                                <?php $button_name = '';
                                                $button_name_reject = '';
                                                $status = 0;
                                                if (isset($row['status'])) {
                                                    if ($row['status'] == 6 && (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel))) {
                                                        $status = 7;
                                                        $button_name = lang('UI_Text.CB_Dev_Completed');
                                                    } elseif ($row['status'] == 7 && (in_array('67', $arrayuserlevel) || in_array('46', $arrayuserlevel))) {
                                                        $status = 8;
                                                        $button_name = lang('UI_Text.CB_QA_Approved');
                                                        $status_reject = 6;
                                                        $button_name_reject = lang('UI_Text.CB_Reject_QA_Approved');
                                                    } elseif ($row['status'] == 8 && (in_array('4', $arrayuserlevel))) {
                                                        $status = 6;
                                                        $button_name = lang('Buttons.Reopen');
                                                    }
                                                } ?>
                                                <div class="row">
                                                    <?php if ($status == 6 || $status == 7 || $status == 8) { ?>
                                                        <div class="col-6 col-md-6 col-lg-6 mg-t-2">
                                                            <form class="form-horizontal"
                                                                action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/update_status') ?>"
                                                                method="POST"><?= csrf_field() ?>
                                                                <div class="form-group col-md-12 mb-1">
                                                                    <?php if (isset($coursevalidation)): ?>
                                                                        <div class=col-12 col-sm-4>
                                                                            <div class="alert alert-white" role="alert">
                                                                                <?= $coursevalidation->listErrors() ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <input type="hidden" name="status"
                                                                        value="<?php echo $status ?>">
                                                                    <input type="hidden" name="course_id"
                                                                        value="<?php echo $course_id ?>">
                                                                    <input type="hidden" name="page_id"
                                                                        value="<?php echo $page_id ?>">
                                                                    <button
                                                                        class="btn btn-outline-success waves-effect btn-sm waves-light mb-3 rounded-pill"><?php echo $button_name ?></button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($status == 8) { ?>
                                                        <div class="col-6 col-md-6 col-lg-6 mg-t-2">
                                                            <form class="form-horizontal"
                                                                action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/update_status') ?>"
                                                                method="POST"><?= csrf_field() ?>
                                                                <div class="form-group col-md-12 mb-1">
                                                                    <?php if (isset($coursevalidation)): ?>
                                                                        <div class=col-12 col-sm-4>
                                                                            <div class="alert alert-white" role="alert">
                                                                                <?= $coursevalidation->listErrors() ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <input type="hidden" name="status"
                                                                        value="<?php echo $status_reject ?>">
                                                                    <input type="hidden" name="course_id"
                                                                        value="<?php echo $course_id ?>">
                                                                    <input type="hidden" name="page_id"
                                                                        value="<?php echo $page_id ?>">
                                                                    <button
                                                                        class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3 rounded-pill"><?php echo $button_name_reject ?></button>
                                                                    
                                                                </div>
                                                            </form>
                                                        </div>
                                                    <?php } ?>
                                                </div>

</div>
                                            <div class="col-12">
                                                <table class="table dt-responsive wrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <?php echo lang('UI_Text.CB_Audio_Transcript'); ?>
                                                                <form action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST" class="d-inline"><?= csrf_field() ?>
                                                                    <?php // Deliberately not sending "crid" here - page_edit_view() has a bug where
                                                                    // posting it skips setting $data['course_id'] (only the session gets updated),
                                                                    // which makes it silently bounce back to Editor. $_SESSION['crid'] is already
                                                                    // set from being on this page, so the session fallback branch is used instead. ?>
                                                                    <input type="hidden" name="page_id" value="<?php echo $row['page_id']; ?>">
                                                                    <input type="hidden" name="page_number" value="<?php echo $row['page_number']; ?>">
                                                                    <input type="hidden" name="page_name" value="<?php echo $row['page_name']; ?>">
                                                                    <button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light" title="<?php echo lang('Buttons.Edit'); ?>">
                                                                        <i class="mdi mdi-pencil-outline"></i>
                                                                    </button>
                                                                </form>
                                                            </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $j = 0;
                                                        foreach ($page_content as $eachpagesDetails) {
                                                            $j = $j + 1;
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $eachpagesDetails['audio'] ?></td>
                                                                
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                <script>
                    var uploadVideoForm = document.getElementById('uploadForm');
                    if (uploadVideoForm) {
                        uploadVideoForm.addEventListener('submit', function() {
                            var button = document.getElementById('uploadButton');
                            button.disabled = true;
                            button.innerHTML = '<?php echo lang('UI_Text.CB_Uploading'); ?>';
                        });
                    }
                </script>
                <script>
                    var uploadVttForm = document.getElementById('uploadVttForm');
                    if (uploadVttForm) {
                        uploadVttForm.addEventListener('submit', function() {
                            var button = document.getElementById('uploadVttButton');
                            button.disabled = true;
                            button.innerHTML = '<?php echo lang('UI_Text.CB_Uploading'); ?>';
                        });
                    }
                </script>
