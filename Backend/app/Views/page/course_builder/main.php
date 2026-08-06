<style>
    
    button.collapsible {
        background-color: #4CAF50;
        
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    button.collapsible:hover {
        background-color: #45a049;
        
    }

td[contenteditable="true"] {
        background-color: #f7f7f7;
        
        outline: none;
        
    }

.contented {
        display: none;
        
        background-color: #e9e9e9;
        
        padding: 10px;
        border-radius: 4px;
        margin-top: 5px;
    }

    button.collapsible:active+.contented {
        display: block;
        
    }

.form-check-inline {
        margin-right: 10px;
    }

    .form-check-label {
        font-size: 14px;
    }

    .collapsible {

cursor: pointer;
        
        width: 100%;
        border: none;
        text-align: center;
        outline: none;
        font-size: 12px;
        padding: 2px;
    }

    .contented {
        
        padding: 0 58px;
        display: none;
        overflow: hidden;

}

#addRowBtn {
        
        background-color: skyblue;
        color: white;
        border: none;
        
        cursor: pointer;

}

    #addRowBtn:hover {
        background-color: #45a049;
    }
</style>
<div class="col-xl-9 col-lg-12 order-lg-2 order-xl-1">

    <?php if (!empty($getAssessmentSettings)) {
        foreach ($getAssessmentSettings as $value) {
            $type = $value['type'];
            if ($type == 59) {
                $kyuselectscqdescrip = $value['value'];
            }
            if ($type == 60) {
                $kyuselectmcqdescrip = $value['value'];
            }
            if ($type == 61) {
                $kyusubmit = $value['value'];
            }
            if ($type == 68) {
                $kyupleaseselectanswer = $value['value'];
            }
        }
    }
    $kyuselectscqdescrip = (isset($kyuselectscqdescrip) && $kyuselectscqdescrip != '') ? $kyuselectscqdescrip : $assessment_scqmcq_sets['59'];
    $kyuselectmcqdescrip = (isset($kyuselectmcqdescrip) && $kyuselectmcqdescrip != '') ? $kyuselectmcqdescrip : $assessment_scqmcq_sets['60'];
    $kyusubmit = (isset($kyusubmit) && $kyusubmit != '') ? $kyusubmit : $assessment_scqmcq_sets['61'];
    $kyupleaseselectanswer = (isset($kyupleaseselectanswer) && $kyupleaseselectanswer != '') ? $kyupleaseselectanswer : $assessment_scqmcq_sets['68'];

$userlevel = session('userlevel');
    $arrayuserlevel = array_map('intval', explode(',', $userlevel));
    ?>

    <?php if (isset($row) && !empty($row)) { ?>
        <div class="card">
            <div class="card-body py-2">
                <div class="row justify-content-between align-items-center">
                    <div class="col-sm-7 d-flex align-items-center">
                        <h4><?php echo abs($row['page_number']); ?> : <?php echo $page_name; ?> (
                            <?php
                            $type = $row['type'];
                            switch ($type) {
                                case 1:
                                    echo 'Articulate';
                                    break;
                                case 2:
                                    echo 'Video';
                                    break;
                                case 8:
                                    echo 'Video Sub Page';
                                    break;
                                case 3:
                                    echo 'Html';
                                    break;
                                case 4:
                                    echo 'Quiz';
                                    break;
                                case 5:
                                    echo 'SCQ';
                                    break;
                                case 6:
                                    echo 'MCQ';
                                    break;
                                case 9:
                                    echo 'Audio Version';
                                    break;
                                case 10:
                                    echo 'Text';
                                    break;
                            }
                            ?>)</h4>
                    </div>

                    <div class="col-auto d-flex align-items-center">
                        <div class="btn-group">
                            <form class="form-horizontal"
                                action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>"
                                method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                                <input type="hidden" name="page_number" value="<?php echo $page_number; ?>">
                                <input type="hidden" name="page_name" value="<?php echo $page_name; ?>">

                                <button type="submit" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">
                                    <i class="mdi mdi-pencil-outline" title="Edit"></i> Edit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .card-body td:nth-child(2) {
                max-width: 200px;
                
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        </style>
        <?php $baseloc = '';
        $base = base_url();
        if ($base == 'http://localhost/Dochek_V3/Dochek_V3') {
            $baseloc = '/Users/pchandran/Sites/dochek_v3/Dochek_V3/';
        }
        if ($base == 'http://localhost/projects_dochek/') {
            $baseloc = 'D:/wampp/www/projects_dochek/';
        }
        if ($base == 'https://dochek.com/') {
            $baseloc = '/var/www/html/';
        }
        if ($base == 'https://www.aristo-tle.com') {
            $baseloc = '/';
        }
        if ($base == 'https://staging.dochek.com/') {
            $baseloc = '/var/www/html/DOCHEK/';
        }
        if ($base == 'http://localhost/DOCHEK/') {
            $baseloc = 'D:/wampp/www//DOCHEK/';
        }
        if ($base == 'http://172.16.2.218/DOCHEK/') {
            $baseloc = '/var/www/DOCHEK/';
        }
        ?>

<div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas" aria-modal="true" role="dialog">
            <div class="offcanvas-body p-3 h-100" data-simplebar="init">
                <div class="simplebar-wrapper" style="margin: -24px;">
                    <div class="simplebar-height-auto-observer-wrapper">
                        <div class="simplebar-height-auto-observer"></div>
                    </div>
                    <div class="simplebar-mask">
                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                            <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                                <div class="simplebar-content" style="padding: 24px;">
                                    <form class="form-horizontal"
                                        action="<?php echo base_url('Task/Task_manage/add_new_task') ?>" method="POST"><?= csrf_field() ?>
                                        <div class="col-12 ">
                                            <div class="form-group mb-2">
                                                <label>Description</label>
                                                <textarea class="form-control" name="description"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-2">
                                                <label>Assign To</label>
                                                <select class="form-select" name="assigned_to">
                                                    <?php if (isset($getUserlatestclientCourseByScenario)) {
                                                        foreach ($getUserlatestclientCourseByScenario as $users) {
                                                            echo '<option value="' . $users['id_user'];
                                                            echo '">';
                                                            echo $users['username'];
                                                            echo '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-2">
                                                <label>Level</label>
                                                <select class="form-select" name="unit">
                                                    <?php
                                                    for ($x = 1; $x <= 10; $x++) {
                                                        echo '<option value="' . $x . '">' . $x . '</opiton>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-2">
                                                <label>Due Date</label>
                                                <input class="form-control" id="due_date" name="due_date" type="date"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-2">
                                                <label>Priority</label>
                                                <select class="form-select" name="priority">
                                                    <option value="High">High</option>
                                                    <option value="Medium">Medium</option>
                                                    <option value="Low">Low</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mt-2 d-grid">
                                                <input type="hidden" name="feedbackid"
                                                    value="<?php echo $row['page_number']; ?>">
                                                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                                                <input type="hidden" name="type_of_task" value="3">
                                                <button
                                                    onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();"
                                                    class="btn btn-sm btn-danger btn-block">Assign Task</button>
                                            </div>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $subpages_Count = count($sub_page_content);
        if ($subpages_Count > 0) {
            echo '<div class="row">';
            foreach ($sub_page_content as $subPages) {

        ?>
                <div class="col-3 col-md-3 col-lg-3">
                    <form class="form-horizontal mb-2" action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="page_id" value="<?php echo $subPages['page_id']; ?>">
                        <input type="hidden" name="page_name" value="<?php echo $subPages['page_name']; ?>">
                        <input type="hidden" name="page_number" value="<?php echo $subPages['page_number']; ?>">
                        <button type="submit"
                            class="btn btn-outline-dark waves-effect waves-light rounded-pill"><?php echo $subPages['page_number']; ?>
                            <?php echo $subPages['page_name']; ?></button>
                    </form>
                </div>
        <?php
            }
            echo '</div>';
        }
        ?>
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12 mg-t-10">
                            <?php
                            $typePartials = [
                                1 => 'articulate', 2 => 'video', 3 => 'html', 4 => 'quiz',
                                5 => 'cyu', 6 => 'cyu', 8 => 'video', 9 => 'video',
                                10 => 'text',
                            ];
                            $typePartialName = $typePartials[$row['type']] ?? null;

                            if ($typePartialName !== null) {
                                echo view('page/course_builder/types/' . $typePartialName, get_defined_vars());
                            } else {
                                echo '<h4>Page Under Development</h4>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php } else { ?>
                    <div class="col-lg-12 col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills nav-fill navtab-bg">
                                    <li class="nav-item">
                                        <a href="#content" data-bs-toggle="tab" aria-expanded="true"
                                            class="nav-link active">
                                            Content
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>

                <?php } ?>
</div>
</div>