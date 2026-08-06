                                        <?php
                                        $html_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/html/" . $row['page_id'] . "/Screen_01.html";
                                        $path = FCPATH . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/html/" . $row['page_id'] . "/Screen_01.html"; ?>
                                        <?php if (file_exists($path)) { ?>
                                            <div class="iframe-container">
                                                <iframe class="responsive-iframe" src="<?php echo $html_path; ?>">
                                                    Your browser does not support iframes.
                                                </iframe>
                                            </div>
                                        <?php } else {
                                            echo '<h4>Page Under Development</h4>';
                                        } ?>

                                <?php
                                $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $row['createdon'] . '/assets/html';
                                ?>

                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <?php $currentFolder = $folderloc . '/' . $row['page_id']; ?>

                                                <?php if (is_dir($currentFolder)) { ?>
                                                    
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <th>Folder</th>
                                                            <th>Lang</th>
                                                            <th>On</th>
                                                            <th>By</th>
                                                            <th>Del</th>
                                                        </tr>

                                                        <?php foreach ($pageArticulate as $Articulate) { ?>
                                                            <tr>
                                                                <td><?= $Articulate['page_id'] ?></td>
                                                                <td>
                                                                    <?php
                                                                    if ($Articulate['language'] == 1)
                                                                        echo 'English';
                                                                    elseif ($Articulate['language'] == 2)
                                                                        echo 'Spanish';
                                                                    elseif ($Articulate['language'] == 3)
                                                                        echo 'French';
                                                                    ?>
                                                                </td>
                                                                <td><?= date('d-m-Y h:i:s', $Articulate['createdon']) ?></td>
                                                                <td><?= $Articulate['createdby'] ?></td>
                                                                <td>
                                                                    <?php if ((in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel)) && $Articulate['status'] != 8) { ?>
                                                                        <form method="POST"
                                                                            action="<?= base_url('SCORM/course_builder/Scorm_course_pages/del_folder'); ?>">
                                                                            <input type="hidden" name="page_id"
                                                                                value="<?= $Articulate['page_id'] ?>">
                                                                            <input type="hidden" name="folderloc"
                                                                                value="<?= $currentFolder ?>">
                                                                            <input type="hidden" name="folder_name"
                                                                                value="<?= $Articulate['page_id'] ?>">
                                                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light rounded-pill"
                                                                                onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')">
                                                                                <span class="mdi mdi-trash-can-outline"></span>
                                                                            </button>
                                                                        </form>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table>

                                                <?php } else { ?>
                                                    
                                                    <?php if ($row['status'] != 8) { ?>
                                                        <div class="form-row">
                                                            <form class="form-horizontal1" id="uploadhtmlfile"
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
                                                                    <input type="hidden" name="course_id" value="<?= $course_id ?>">
                                                                    <input type="hidden" name="page_id" value="<?= $page_id ?>">
                                                                    <button type="submit" class="btn btn-sm btn-danger form-control"
                                                                        id="uploadButton">
                                                                        Upload HTML Zip Package
                                                                    </button>
                                                                </div>
                                                            </form>
                                                            <div class="progress" style="display:none;">
                                                                <div class="progress-bar" role="progressbar" style="width: 0%;"
                                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                <script>
                    $('.fa').show();

                    $('#uploadhtmlfile').on('submit', function(event) {
                        event.preventDefault();

                        var dataString = new FormData($('#uploadhtmlfile')[0]);

                        if (typeof FormData !== 'undefined') {

                            $.ajax({
                                url: '<?php echo base_url('SCORM/course_builder/Scorm_course_pages/uploadHTML') ?>',
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
                    document.getElementById('uploadhtmlfile').addEventListener('submit', function() {
                        var button = document.getElementById('uploadButton');
                        button.disabled = true;
                        button.innerHTML = 'Uploading...';
                    });
                </script>