                                        <?php
                                        $articulate_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/Articulate/" . $row['page_id'] . "/story.html";
                                        $path = FCPATH . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/Articulate/" . $row['page_id'] . "/story.html"; ?>
                                        <?php if (file_exists($path)) { ?>
                                            <div class="iframe-container">
                                                <iframe class="responsive-iframe" src="<?php echo $articulate_path; ?>">
                                                    Your browser does not support iframes.
                                                </iframe>
                                            </div>
                                        <?php } else {
                                            echo '<h4>Page Under Development</h4>';
                                        } ?>

                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12 mg-t-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <?php
                                                $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $row['createdon'] . 'assets/Articulate/' . $row['page_id'];
                                                if (!empty($pageArticulate)) {
                                                    echo '<table class="table  table-sm">';
                                                    echo '<tr><th>Articulate Folder</th><th>Lang</th><th>On</th><th>By</th><th>Del</th></tr>';
                                                    foreach ($pageArticulate as $Articulate) {

                                                        echo '<tr><td>';
                                                        echo $Articulate['folder'];
                                                        echo '</td><td>';
                                                        if ($Articulate['language'] == 1) {
                                                            echo 'English';
                                                        } elseif ($Articulate['language'] == 2) {
                                                            echo 'Spanish';
                                                        } elseif ($Articulate['language'] == 3) {
                                                            echo 'French';
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
                                                                    onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
                                                                        class="mdi mdi-trash-can-outline"></span> Delete</button>
                                                            </form>
                                                        <?php } ?>
                                                <?php echo '</td></tr>';
                                                    }
                                                    echo '</table>';
                                                } else {
                                                    echo 'No Files';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (empty($pageArticulate)) { ?>
                                        <?php if ($row['status'] != 8) { ?>
                                            <div class="col-12 col-md-12 col-lg-12 mg-t-10">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <form class="form-horizontal1" id="uploadzipfile"
                                                                enctype="multipart/form-data"><?= csrf_field() ?>
                                                                <div class="form-group col-md-12 mb-2">
                                                                    <label>Select Language</label>
                                                                    <select name="language" class="form-control">
                                                                        <option value="1">English</option>
                                                                        <option value="2">Spanish</option>
                                                                        <option value="3">French</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-12 mb-2">
                                                                    <input type="file" name="zip_file" accept=".ZIP,.zip" required />
                                                                </div>
                                                                <div class="form-group col-md-12 mb-2">
                                                                    <input type="hidden" name="course_id"
                                                                        value="<?php echo $course_id ?>">
                                                                    <input type="hidden" name="page_id" value="<?php echo $page_id ?>">
                                                                    <button type="submit"
                                                                        class="btn btn-outline-danger waves-effect btn-sm waves-light form-control rounded-pill"
                                                                        id="uploadButton">Upload Package</button>
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

<script>
                    $('.fa').show();

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
                                    $('.my_update_panel').html(data);
                                    var obj = JSON.parse(data);

if (obj.status === 'OK') {
                                        $('#loading_spinner').hide();
                                        location.reload();
                                        alert('File Uploaded Successfully');
                                    } else {
                                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                                    }
                                },
                                error: function(xhr, textStatus, errorThrown) {
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
                            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
                        }
                    });
                </script>

                <script>
                    document.getElementById('uploadzipfile').addEventListener('submit', function() {
                        var button = document.getElementById('uploadButton');
                        button.disabled = true;
                        button.innerHTML = 'Uploading...';
                    });
                </script>