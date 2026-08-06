<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($main_header_link); ?>"><?php echo $main_header; ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link_1) ?>"><?php echo $header_1 ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo $sub_header_1; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST" id="myForm">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control col-md-12" name="option" placeholder="option" />
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control col-md-12" name="score" id="scoreInput" placeholder="Score" />

                        </div>
                        <div class="col-md-4">
                            <label>Correct</label>&nbsp;
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" name="truefalse" class="form-check-input" value="1" onclick="updateScoreValue(5)"> Yes
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" name="truefalse" class="form-check-input" value="2" checked onclick="updateScoreValue('')" id="radioNo"> No
                                </label>
                            </div>
                        </div>
                        <div class=" col-md-2">
                            <?php if (isset($coursevalidation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $coursevalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="q_id" value="<?php echo $row['q_id']; ?>">
                            <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id']; ?>">
                            <input type="hidden" name="type" value="<?php echo $type; ?>">
                            <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                            <input type="hidden" name="returnUrl" value="2">
                            <button type="submit" class="btn btn-info btn-sm col-md-4" id="submitButton">
                                Add
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php $userlevel = session()->get('userlevel');
$array  = array_map('intval', str_split($userlevel)); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5><?php echo $row['question']; ?></h5>

                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width=5%>#</th>
                            <th></th>
                            <th>Option</th>
                            <th>Score</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        if ($getoptiondata != '') {
                            foreach ($getoptiondata as $eachoptiondata) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <?php if ($eachoptiondata['truefalse'] == 1) { ?>
                                        <td width="5%"><span class="fa fa-check"></span></td>
                                    <?php } else { ?>
                                        <td></td>
                                    <?php } ?>
                                    <td> <?php echo $eachoptiondata['values'] ?></td>
                                    <td> <?php echo ($eachoptiondata['score'] != 0) ? $eachoptiondata['score'] : '' ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="question_id" value="<?php echo $eachoptiondata['question_id']  ?>">
                                            <input type="hidden" name="o_id" value="<?php echo $eachoptiondata['o_id']  ?>">
                                            <button type="submit" class="btn btn-sm widget-icon btn-warning"><span class="icon-pencil"></span></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($delete_link) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="question_id" value="<?php echo $eachoptiondata['question_id'] ?>">
                                            <input type="hidden" name="o_id" value="<?php echo $eachoptiondata['o_id']  ?>">
                                            <input type="hidden" name="returnUrl" value="2">
                                            <button type="submit" class="btn btn-sm widget-icon btn-danger" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="icon-trash"></span></button>
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
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6>Image</h6>
                <div class="row">
                    <div class="col-md-6">
                        <?php

                        $base = base_url();
                        // print_r($base);
                        if ($base == 'http://localhost:8888/projects_dochek/projects_dochek') {
                            $baseloc = '/Users/pchandran/Sites/projects_dochek/projects_dochek/';
                        }
                        if ($base == 'http://172.16.2.218/DOCHEK/') {
                            $baseloc = '/var/www/DOCHEK/';
                        }
                        if ($base == 'https://dochek.com/') {
                            $baseloc = '/var/www/html/';
                        }
                        if ($base == 'https://staging.dochek.com/') {
                            $baseloc = '/var/www/html/DOCHEK/';
                        }
                        if ($base == 'https://staging.dochek.com/') {
                            $baseloc = '/var/www/html/DOCHEK/';
                        }
                        if ($base == 'http://localhost/DOCHEK/') {
                            $baseloc = 'D:/wampp/www/DOCHEK/';
                        }
                        if ($base == 'http://localhost/DOCHEKDOTCOM/') {
                            $baseloc = 'D:/wampp/www/DOCHEKDOTCOM/';
                        }
                        if ($base == 'http://172.16.2.218/DOCHEK/') {
                            $baseloc = '/var/www/DOCHEK/';
                        }
                        $folderloc = $baseloc . 'assets/assets/uploads/assessment_image/' . $row['q_id'];
                        // print_r($folderloc);
                        // exit();
                        if (is_dir($folderloc)) {
                            $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                            $sno = 0;
                            echo '<table class="table  table-sm">';
                            echo '<tr><th>#</th><th>Folder</th><th>Created</th><th>Del</th></tr>';
                            foreach ($files2 as $key => $value) {

                                if (strlen($value) > 3) {
                                    $dontshow = 0;
                                    $file_parts = pathinfo($value);
                                    if ($file_parts['extension'] != 'DS_Store') {
                                        $sno++;
                                        echo '<tr><td>';
                                        echo $sno;
                                        echo '</td><td>';

                        ?>
                                        <div class="head bg-dot30 np tac">
                                            <img style="max-height:100px;" src="<?php echo base_url() ?>/assets/assets/uploads/assessment_image/<?php echo $row['q_id']; ?>/<?php echo $value ?>" class="img-squre img-thumbnail" />
                                        </div><br />
                                        <?php

                                        echo '</td><td>';
                                        $file_creation_date = filectime($folderloc . '/' . $value);
                                        echo date('Y-m-d H:i:s', $file_creation_date);
                                        echo '</td><td>';
                                        //if ($row['thumbnail']!=$value) {
                                        ?>
                                        <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/delquestion_file'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="fileloc" value="<?php echo $folderloc . '/' . $value; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>


                        <?php
                                        //	}

                                        echo '</td><tr>';
                                    }
                                }
                            }
                            echo '</table>';
                        } else {
                            echo 'No Files';
                        }  ?>
                    </div>

                    <div class="col-md-6">
                        <div class="form-row">
                            <form class="form-horizontal1" enctype="multipart/form-data" action="<?php echo base_url($form_url_1) ?>" method="POST"><?= csrf_field() ?>
                                <div class="form-group col-md-6">
                                    <input type="file" name="file" />
                                </div><br />

                                <div class="form-group col-md-6">
                                    <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id'] ?>">
                                    <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                    <input type="hidden" name="type" value="1">
                                    <button type="submit" class="btn btn-info btn-sm form-control">Upload Image</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Upload PDF</h6>
                        <div class="col-md-6">
                            <?php

                            $folderloc = $baseloc . 'assets/assets/uploads/assessment_pdf/' . $row['q_id'];
                            if (is_dir($folderloc)) {
                                $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                $sno = 0;
                                echo '<table class="table  table-sm">';
                                echo '<tr><th>#</th><th>Folder</th><th>Created</th><th>Del</th></tr>';
                                foreach ($files2 as $key => $value) {
                                    if (strlen($value) > 3) {

                                        $dontshow = 0;
                                        $file_parts = pathinfo($value);
                                        if ($file_parts['extension'] != 'DS_Store') {
                                            $sno++;
                                            echo '<tr><td>';
                                            echo $sno;
                                            echo '</td><td>';
                                            echo $value;

                                            echo '</td><td>';
                                            $file_creation_date = filectime($folderloc . '/' . $value);
                                            echo date('Y-m-d H:i:s', $file_creation_date);
                                            echo '</td><td>';
                                            // if ($row['thumbnail'] != $value) {
                            ?>
                                            <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/delquestion_file'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="fileloc" value="<?php echo $folderloc . '/' . $value; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                            <?php
                                            // }

                                            echo '</td><tr>';
                                        }
                                    }
                                }
                                echo '</table>';
                            } else {
                                echo 'No Files';
                            } ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-row">
                            <form class="form-horizontal2" enctype="multipart/form-data" action=<?php echo base_url($form_url_2); ?> method="POST"><?= csrf_field() ?>
                                <div class="form-group col-md-6">
                                    <input type="file" name="file" />
                                </div><br />
                                <div class="form-group col-md-6">
                                    <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id'] ?>">
                                    <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                    <input type="hidden" name="type" value="2">
                                    <button type="submit" class="btn btn-warning btn-sm form-control">Upload PDF Document</button>
                                </div>
                                <?php if (isset($pdfvalidation)) : ?>
                                    <div class="form-group col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <?= $pdfvalidation->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Upload Video</h6>
                            <div class="col-md-6">
                                <?php

                                $folderloc = $baseloc . 'assets/assets/uploads/assessment_video/' . $row['q_id'];
                                if (is_dir($folderloc)) {
                                    $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                    $sno = 0;
                                    echo '<table class="table  table-sm">';
                                    echo '<tr><th>#</th><th>Folder</th><th>Created</th><th>Del</th></tr>';
                                    foreach ($files2 as $key => $value) {
                                        if (strlen($value) > 3) {

                                            $dontshow = 0;
                                            $file_parts = pathinfo($value);
                                            if ($file_parts['extension'] != 'DS_Store') {
                                                $sno++;
                                                echo '<tr><td>';
                                                echo $sno;
                                                echo '</td><td>';
                                                echo $value;

                                                echo '</td><td>';
                                                $file_creation_date = filectime($folderloc . '/' . $value);
                                                echo date('Y-m-d H:i:s', $file_creation_date);
                                                echo '</td><td>';
                                                // if ($row['thumbnail'] != $value) {
                                ?>
                                                <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/delquestion_file'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="fileloc" value="<?php echo $folderloc . '/' . $value; ?>">
                                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                                </form>
                                <?php
                                                // }

                                                echo '</td><tr>';
                                            }
                                        }
                                    }
                                    echo '</table>';
                                } else {
                                    echo 'No Files';
                                } ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <form class="form-horizontal2" enctype="multipart/form-data" action=<?php echo base_url($form_url_3); ?> method="POST"><?= csrf_field() ?>
                                    <div class="form-group col-md-6">
                                        <input type="file" name="file" />
                                    </div><br />
                                    <div class="form-group col-md-6">
                                        <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id'] ?>">
                                        <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                        <input type="hidden" name="type" value="3">
                                        <button type="submit" class="btn btn-success btn-sm form-control">Upload Video</button>
                                    </div>
                                    <?php if (isset($pdfvalidation)) : ?>
                                        <div class="form-group col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                <?= $pdfvalidation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {

                    $('#dynamic-table').DataTable();

                });
            </script>
            <script>
                function updateScoreValue(newValue) {
                    document.getElementById("scoreInput").value = newValue;
                }
            </script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const scoreInput = document.getElementById("scoreInput");
                    const trueFalseRadioYes = document.querySelector("input[value='1']");
                    const submitButton = document.getElementById("submitButton");

                    function updateScoreValue(value) {
                        scoreInput.value = value;
                    }

                    submitButton.addEventListener("click", function(event) {
                        const enteredScore = parseFloat(scoreInput.value);
                        const trueFalseChecked = trueFalseRadioYes.checked;

                        if (!trueFalseChecked && enteredScore > 0) {
                            alert("Please enter a non-positive value for the score.");
                            event.preventDefault(); // Prevent form submission
                        }
                    });
                });
            </script>