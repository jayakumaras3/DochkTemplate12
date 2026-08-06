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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php
                                                if (file_exists($path) && ($Video != '')) { ?>

                                                    <video src="<?php echo $video_path; ?>" style="width: 100%; height: auto;"
                                                        id="vidArea" controls controlsList="nodownload" disablePictureInPicture>
                                                        <?php if (file_exists($vtt_path) && !empty($Vtt)) { ?>
                                                            <track id="englishTrack" kind="captions" src="<?php echo $vtt_path; ?>"
                                                                srclang="en" label="English" default>
                                                        <?php } ?>
                                                    </video>

                                                <?php } else {
                                                    echo '<h4>Page Under Development</h4>';
                                                } ?>
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
                                                                            echo '<tr><th>Video File</th><th>On</th><th>By</th><th>Del</th></tr>';

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
                                                                                            onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
                                                                                                class="mdi mdi-trash-can-outline"></span> Delete</button>
                                                                                    </form>
                                                                                <?php } ?>
                                                                        <?php echo '</td></tr>';
                                                                            }
                                                                            echo '</table>';
                                                                        } else {
                                                                            echo 'No Video Files';
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
                                                                                        <label>Select Language</label>
                                                                                        <select name="language" class="form-control">
                                                                                            <option value="1">English</option>
                                                                                            <option value="2">Spanish</option>
                                                                                            <option value="3">French</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <input type="file" name="file" accept=".mp4,.MP4"
                                                                                            required />
                                                                                    </div>
                                                                                    <div class="form-group col-md-12 mb-2">
                                                                                        <input type="hidden" name="course_id"
                                                                                            value="<?php echo $course_id ?>">
                                                                                        <input type="hidden" name="page_id"
                                                                                            value="<?php echo $page_id ?>">
                                                                                        <button type="submit"
                                                                                            class="btn btn-outline-warning waves-effect btn-sm waves-light form-control rounded-pill"
                                                                                            id="uploadButton">Upload Video</button>
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
                                                                            echo '<tr><th>VTT File</th><th>On</th><th>By</th><th>Del</th></tr>';

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
                                                                                            onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
                                                                                                class="mdi mdi-trash-can-outline"></span> Delete</button>
                                                                                    </form>
                                                                                <?php } ?>
                                                                            <?php echo '</td></tr>';
                                                                            }

                                                                            echo '</table>';
                                                                        } else {
                                                                            if ($row['status'] != 8) { ?>

                                                                                <div class="form-row">
                                                                                    <p style='color:red;'>Note: The file name should follow the format En_&lt;video name&gt; (e.g., En_en_3.vtt).</p>

                                                                                    <form class="form-horizontal2" enctype="multipart/form-data"
                                                                                        action=<?php echo base_url('SCORM/course_builder/scorm_course_pages/uploadvtt'); ?> method="post" id="uploadVttForm"><?= csrf_field() ?>
                                                                                        <div class="form-group col-md-12 mb-2">
                                                                                            <label>Select Language</label>
                                                                                            <select name="language" class="form-control">
                                                                                                <option value="1">English</option>
                                                                                                <option value="2">Spanish</option>
                                                                                                <option value="3">French</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group col-md-6 mb-2">
                                                                                            <input type="file" name="file" accept=".vtt,.VTT"
                                                                                                required />
                                                                                        </div>
                                                                                        <div class="form-group col-md-12 mb-2">
                                                                                            <input type="hidden" name="course_id"
                                                                                                value="<?php echo $course_id ?>">
                                                                                            <input type="hidden" name="page_id"
                                                                                                value="<?php echo $page_id ?>">
                                                                                            <button type="submit"
                                                                                                class="btn btn-outline-success waves-effect btn-sm waves-light form-control rounded-pill"
                                                                                                id="uploadVttButton">Upload VTT</button>
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
                                                        $button_name = 'Dev Completed';
                                                    } elseif ($row['status'] == 7 && (in_array('67', $arrayuserlevel) || in_array('46', $arrayuserlevel))) {
                                                        $status = 8;
                                                        $button_name = 'QA Approved';
                                                        $status_reject = 6;
                                                        $button_name_reject = 'Reject QA Approved';
                                                    } elseif ($row['status'] == 8 && (in_array('4', $arrayuserlevel))) {
                                                        $status = 6;
                                                        $button_name = 'Reopen';
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
                                            <div class="col-md-6">
                                                <table class="table dt-responsive wrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Audio Transcript</th>
                                                            
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
                            button.innerHTML = 'Uploading...';
                        });
                    }
                </script>
                <script>
                    var uploadVttForm = document.getElementById('uploadVttForm');
                    if (uploadVttForm) {
                        uploadVttForm.addEventListener('submit', function() {
                            var button = document.getElementById('uploadVttButton');
                            button.disabled = true;
                            button.innerHTML = 'Uploading...';
                        });
                    }
                </script>
