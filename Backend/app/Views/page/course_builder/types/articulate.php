                                        <?php
                                        $articulate_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/Articulate/" . $row['page_id'] . "/story.html";
                                        $path = FCPATH . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/Articulate/" . $row['page_id'] . "/story.html"; ?>
                                        <?php if (file_exists($path)) { ?>
                                            <div class="iframe-container">
                                                <iframe class="responsive-iframe" src="<?php echo $articulate_path; ?>">
                                                    <?php echo lang('UI_Text.CB_Browser_No_Iframe_Support'); ?>
                                                </iframe>
                                            </div>
                                        <?php } else { ?>
                                            <div class="text-center py-4">
                                                <h6 class="fw-semibold mb-1"><i class="mdi mdi-file-powerpoint-box"></i> <?php echo lang('UI_Text.CB_No_Articulate_Package_Uploaded'); ?></h6>
                                                <p class="text-muted mb-0"><?php echo lang('UI_Text.CB_Upload_Articulate_Package_Sub'); ?></p>
                                            </div>
                                        <?php } ?>

                                <div class="row">
                                    <?php if (!empty($pageArticulate)) { ?>
                                        <div class="col-12 col-md-12 col-lg-12 mg-t-2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <?php
                                                    $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $row['createdon'] . 'assets/Articulate/' . $row['page_id'];
                                                    echo '<table class="table  table-sm">';
                                                    echo '<tr><th>' . lang('UI_Text.CB_Articulate_Folder') . '</th><th>' . lang('UI_Text.CB_Lang') . '</th><th>' . lang('UI_Text.on') . '</th><th>' . lang('UI_Text.by') . '</th><th>' . lang('UI_Text.Action') . '</th></tr>';
                                                    foreach ($pageArticulate as $Articulate) {

                                                        echo '<tr><td>';
                                                        echo $Articulate['folder'];
                                                        echo '</td><td>';
                                                        if ($Articulate['language'] == 1) {
                                                            echo lang('UI_Text.Language_Name_English');
                                                        } elseif ($Articulate['language'] == 2) {
                                                            echo lang('UI_Text.Language_Name_Spanish');
                                                        } elseif ($Articulate['language'] == 3) {
                                                            echo lang('UI_Text.Language_Name_French');
                                                        }
                                                        echo '</td><td>';
                                                        echo date('d-m-Y h:i:s', $Articulate['createdon']);

                                                        echo '</td><td>';
                                                        echo $Articulate['createdby'];
                                                        echo '</td><td>'; ?>
                                                        <?php if ((in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel)) && $row['status'] != 8) { ?>
                                                            <form class="form-horizontal"
                                                                action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/del_folder'); ?>"
                                                                method="POST"><?= csrf_field() ?>
                                                                <input type="hidden" name="page_id" value="<?php echo $row['page_id'] ?>">
                                                                <input type="hidden" name="folderloc" value="<?php echo $folderloc; ?>">
                                                                <input type="hidden" name="folder_name"
                                                                    value="<?php echo $Articulate['folder']; ?>">
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
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <?php if ($row['status'] != 8) { ?>
                                            <div class="col-12 col-md-12 col-lg-12 mg-t-10">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <form class="form-horizontal1" id="uploadzipfile"
                                                                enctype="multipart/form-data"><?= csrf_field() ?>
                                                                <input type="hidden" name="language" value="1">
                                                                <div class="form-group col-md-12 mb-2">
                                                                    <label class="fw-semibold"><?php echo lang('UI_Text.CB_Articulate_Zip_Package'); ?> <span class="text-danger">*</span></label>
                                                                    <input type="file" name="zip_file" class="form-control" accept=".ZIP,.zip" required />
                                                                    <small class="form-text text-muted d-block mt-1">
                                                                        <?php echo lang('UI_Text.CB_Upload_Articulate_Package_Sub'); ?>
                                                                    </small>
                                                                </div>
                                                                <div class="form-group col-md-12 mb-2">
                                                                    <input type="hidden" name="course_id"
                                                                        value="<?php echo $course_id ?>">
                                                                    <input type="hidden" name="page_id" value="<?php echo $page_id ?>">
                                                                    <button type="submit"
                                                                        class="btn btn-outline-danger waves-effect btn-sm waves-light form-control rounded-pill"
                                                                        id="uploadButton"><?php echo lang('UI_Text.CB_Upload_Articulate_Zip_Package'); ?></button>
                                                                </div>
                                                            </form>
                                                            <div class="progress" style="display:none;">
                                                                <div class="progress-bar" role="progressbar" style="width: 0%;"
                                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
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
                    $('.fa').show();

                    function resetUploadButton() {
                        var button = document.getElementById('uploadButton');
                        if (button) {
                            button.disabled = false;
                            button.innerHTML = '<?php echo lang('UI_Text.CB_Upload_Articulate_Zip_Package'); ?>';
                        }
                    }

                    $('#uploadzipfile').on('submit', function(event) {
                        event.preventDefault();

                        var dataString = new FormData($('#uploadzipfile')[0]);

                        if (typeof FormData !== 'undefined') {

                            $.ajax({
                                url: '<?php echo base_url('SCORM/course_builder/Scorm_course_pages/uploadZipfile') ?>',
                                type: "POST",
                                data: dataString,
                                processData: false,
                                contentType: false,
                                beforeSend: function() {
                                    $(".progress").show();
                                },
                                success: function(data) {
                                    var obj;
                                    try {
                                        obj = JSON.parse(data);
                                    } catch (e) {
                                        console.error('Articulate upload: non-JSON response', data);
                                        resetUploadButton();
                                        alert('<?php echo lang('Messages.Error_0025'); ?>');
                                        return;
                                    }

                                    if (obj.status === 'OK') {
                                        $('#loading_spinner').hide();
                                        alert('<?php echo lang('Messages.Success_0055'); ?>');
                                        location.reload();
                                    } else {
                                        console.error('Articulate upload failed', obj);
                                        resetUploadButton();
                                        alert('<?php echo lang('Messages.Error_0025'); ?>' + (obj.message ? ('\n\n' + obj.message) : ''));
                                    }
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    console.error('Articulate upload: request failed', xhr.status, xhr.responseText);
                                    resetUploadButton();
                                    alert('<?php echo lang('Messages.Error_0025'); ?>' + ' (HTTP ' + xhr.status + ')');
                                },
                                complete: function() {
                                    $(".progress").hide();
                                },
                                xhr: function() {
                                    var xhr = new window.XMLHttpRequest();
                                    xhr.upload.addEventListener("progress", function(evt) {
                                        if (evt.lengthComputable) {
                                            var percentComplete = (evt.loaded / evt.total) * 100;
                                            $(".progress-bar").width(percentComplete + '%');
                                            $(".progress-bar").html(percentComplete.toFixed(2) + '%');
                                        }
                                    }, false);
                                    return xhr;
                                }
                            });

                        } else {
                            message("<?php echo lang('Messages.Error_0026'); ?>");
                        }
                    });
                </script>

                <script>
                    document.getElementById('uploadzipfile').addEventListener('submit', function() {
                        var button = document.getElementById('uploadButton');
                        button.disabled = true;
                        button.innerHTML = '<?php echo lang('UI_Text.CB_Uploading'); ?>';
                    });
                </script>
