<style>
    .form-check-inline {
        margin-right: 10px;
    }

    .form-check-label {
        font-size: 14px;
    }
</style>
<div class="col-xl-9 col-lg-12 order-lg-2 order-xl-1" id="course-builder-main-pane">

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
                        <h4><?php echo abs($row['page_number']); ?> : <?php echo $page_name; ?> (<?php
                            $type = $row['type'];
                            switch ($type) {
                                case 1:
                                    echo lang('UI_Text.CB_Type_Articulate');
                                    break;
                                case 2:
                                    echo lang('UI_Text.CB_Type_Video');
                                    break;
                                case 8:
                                    echo lang('UI_Text.CB_Type_Video_Sub_Page');
                                    break;
                                case 3:
                                    echo lang('UI_Text.CB_Type_Html');
                                    break;
                                case 4:
                                    echo lang('UI_Text.CB_Type_Quiz');
                                    break;
                                case 5:
                                    echo lang('UI_Text.CB_Type_SCQ');
                                    break;
                                case 6:
                                    echo lang('UI_Text.CB_Type_MCQ');
                                    break;
                                case 9:
                                    echo lang('UI_Text.CB_Type_Audio_Version');
                                    break;
                                case 10:
                                    echo lang('UI_Text.CB_Type_Text_Only');
                                    break;
                                case 11:
                                    echo lang('UI_Text.CB_Type_Image_Text');
                                    break;
                                case 12:
                                    echo lang('UI_Text.CB_Type_Text_Image');
                                    break;
                            }
                            ?>)</h4>
                    </div>

                    <div class="col-auto d-flex align-items-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editPageModal">
                                <i class="mdi mdi-pencil-outline" title="<?php echo lang('Buttons.Edit'); ?>"></i> <?php echo lang('Buttons.Edit'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editPageModal" tabindex="-1" aria-labelledby="editPageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content edit-page-modal">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold" id="editPageModalLabel"><?php echo lang('UI_Text.CB_Edit_Page'); ?></h5>
                            <p class="text-muted font-13 mb-0"><?php echo lang('UI_Text.CB_Update_Page_Details_Sub'); ?></p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <div id="editPageModalError" class="alert alert-danger" style="display:none;"></div>
                        <form id="editPageForm" class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/editpage') ?>" method="POST"><?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><?php echo lang('UI_Text.CB_Page_Name'); ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="page_name" placeholder="<?php echo lang('UI_Text.CB_Page_Name'); ?>" value="<?php echo $row['page_name']; ?>" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><?php echo lang('UI_Text.CB_Page_Type'); ?></label>
                                <?php
                                // Only offer swapping within a type's own "family" - types outside it don't
                                // share the underlying data (e.g. an Articulate page has no assessment_questions
                                // row), so switching to one leaves the page unable to render. Types with no
                                // listed family (Video, Video Sub Page, Audio Version, Quiz) aren't known to be
                                // safely interchangeable with anything, so they're locked to themselves.
                                $typeLabels = [
                                    1 => 'UI_Text.CB_Type_Articulate',
                                    9 => 'UI_Text.CB_Type_Audio_Version',
                                    2 => 'UI_Text.CB_Type_Video',
                                    8 => 'UI_Text.CB_Type_Video_Sub_Page',
                                    5 => 'UI_Text.CB_Type_SCQ_Full',
                                    6 => 'UI_Text.CB_Type_MCQ_Full',
                                    4 => 'UI_Text.CB_Type_Quiz',
                                    3 => 'UI_Text.CB_Type_Html',
                                    10 => 'UI_Text.CB_Type_Text_Only',
                                    11 => 'UI_Text.CB_Type_Image_Text',
                                    12 => 'UI_Text.CB_Type_Text_Image',
                                ];
                                $typeSwapFamilies = [
                                    1 => [1, 3], 3 => [1, 3],
                                    5 => [5, 6], 6 => [5, 6],
                                    10 => [10, 11, 12], 11 => [10, 11, 12], 12 => [10, 11, 12],
                                ];
                                $allowedPageTypes = $typeSwapFamilies[$row['type']] ?? [(int) $row['type']];
                                ?>
                                <select name="type" class="form-control">
                                    <?php foreach ($typeLabels as $typeValue => $typeLangKey): ?>
                                        <?php if (in_array($typeValue, $allowedPageTypes, true)): ?>
                                            <option value="<?php echo $typeValue; ?>" <?php echo ($row['type'] == $typeValue) ? 'selected' : ''; ?>><?php echo lang($typeLangKey); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-1">
                                <label class="form-label fw-semibold"><?php echo lang('UI_Text.CB_Page_Number'); ?> <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" min="1" class="form-control" name="page_number" placeholder="<?php echo lang('UI_Text.CB_Page_Number'); ?>" value="<?php echo $row['page_number']; ?>" required />
                            </div>
                            <div class="mb-1">
                                <label class="form-label fw-semibold"><?php echo lang('UI_Text.Status'); ?></label>
                                <select name="status" class="form-control">
                                    <?php // "Active" keeps whatever non-deleted status this page already has (e.g. the
                                    // Dev Completed/QA Approved workflow states used elsewhere - see types/video.php) -
                                    // it's not reset to a single fixed value. Choosing "Deleted" always submits 0. ?>
                                    <option value="<?php echo $row['status'] != 0 ? $row['status'] : 1; ?>" <?php echo ($row['status'] != 0) ? 'selected' : ''; ?>><?php echo lang('UI_Text.Active'); ?></option>
                                    <option value="0" <?php echo ($row['status'] == 0) ? 'selected' : ''; ?>><?php echo lang('UI_Text.Deleted'); ?></option>
                                </select>
                            </div>
                            <input type="hidden" name="page_id" value="<?php echo $row['page_id']; ?>">
                            <input type="hidden" name="sub_page_main" value="<?php echo $row['sub_page_main']; ?>">
                        </form>
                    </div>
                    <div class="modal-footer border-top">
                        <?php if ($row['sub_page_main'] == 0) { ?>
                            <button type="button" id="createSubPageTrigger" class="btn btn-outline-secondary rounded-pill px-4"><?php echo lang('Buttons.CB_Create_Sub_Page'); ?> <i class="mdi mdi-arrow-up-bold-circle-outline"></i></button>
                        <?php } ?>
                        <button type="submit" form="editPageForm" class="btn btn-primary rounded-pill px-4"><?php echo lang('Buttons.Update'); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($row['sub_page_main'] == 0) { ?>
            <!-- Same fields/endpoint as SCORM/course_builder/Scorm_course_pages/page_add_sub_page,
                 shown inline as a modal instead of navigating to that separate page. -->
            <div class="modal fade" id="addSubPageModal" tabindex="-1" aria-labelledby="addSubPageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content edit-page-modal">
                        <div class="modal-header border-0 pb-0">
                            <div>
                                <h5 class="modal-title fw-bold" id="addSubPageModalLabel"><?php echo lang('UI_Text.CB_Add_Sub_Page'); ?></h5>
                                <p class="text-muted font-13 mb-0"><?php echo lang('UI_Text.CB_Create_Sub_Page_Sub'); ?></p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-2">
                            <div id="addSubPageModalError" class="alert alert-danger" style="display:none;"></div>
                            <form id="addSubPageForm" class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/addsubpage') ?>" method="POST"><?= csrf_field() ?>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold"><?php echo lang('UI_Text.CB_Sub_Page_Name'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="page_name" placeholder="<?php echo lang('UI_Text.CB_Sub_Page_Name'); ?>" required />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-semibold"><?php echo lang('UI_Text.CB_Sub_Page_Type'); ?></label>
                                    <select name="type" class="form-control">
                                        <option value="2" selected><?php echo lang('UI_Text.CB_Type_Video'); ?></option>
                                        <option value="1"><?php echo lang('UI_Text.CB_Type_Articulate'); ?></option>
                                        <option value="5"><?php echo lang('UI_Text.CB_Type_SCQ_Full'); ?></option>
                                        <option value="6"><?php echo lang('UI_Text.CB_Type_MCQ_Full'); ?></option>
                                        <option value="4"><?php echo lang('UI_Text.CB_Type_Quiz'); ?></option>
                                        <option value="3"><?php echo lang('UI_Text.CB_Type_Html'); ?></option>
                                        <option value="10"><?php echo lang('UI_Text.CB_Type_Text_Only'); ?></option>
                                        <option value="11"><?php echo lang('UI_Text.CB_Type_Image_Text'); ?></option>
                                        <option value="12"><?php echo lang('UI_Text.CB_Type_Text_Image'); ?></option>
                                    </select>
                                </div>
                                <input type="hidden" name="page_number" value="<?php echo $row['page_number']; ?>">
                                <input type="hidden" name="scourse_id" value="<?php echo $course_id; ?>">
                                <input type="hidden" name="page_id" value="<?php echo $row['page_id']; ?>">
                            </form>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="submit" form="addSubPageForm" class="btn btn-primary rounded-pill px-4"><?php echo lang('UI_Text.CB_Add_Sub_Page'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <style>
            .edit-page-modal {
                border-radius: 18px;
                border: none;
                box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
            }
        </style>

        <script>
            (function() {
                var editPageFormEl = document.getElementById('editPageForm');
                // This whole pane can be refreshed in place (courseBuilderReloadMain(), called
                // after option edits elsewhere on the page) without a full page reload, which
                // re-runs this very script block against whatever #editPageForm exists at that
                // moment. Without this guard, an older element - even a since-detached one, if
                // some other refresh ever raced with this one - could end up with its own
                // listener still firing against DOM nodes (like #editPageModalError) that no
                // longer exist, which is what threw "Cannot read properties of null" here and
                // aborted the submit before the actual update request was ever sent.
                if (!editPageFormEl || editPageFormEl.dataset.boundSubmit === '1') return;
                editPageFormEl.dataset.boundSubmit = '1';

                editPageFormEl.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var form = e.target;
                    var errorBox = document.getElementById('editPageModalError');
                    if (errorBox) errorBox.style.display = 'none';
                    // fetch(), not jQuery.ajax(): something in this app's stack of ~15 jQuery
                    // plugins leaves jQuery.ajax() unable to construct its own XHR object here
                    // ("Cannot read properties of undefined (reading 'open')", status 0 - i.e.
                    // it never even reaches the network). fetch() is native and already used
                    // successfully elsewhere on this same page (courseBuilderReloadMain above).
                    fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            credentials: 'same-origin'
                        })
                        .then(function(response) {
                            var overlay = document.getElementById('loading-screen');
                            if (overlay) overlay.style.display = 'none';
                            if (!response.ok) throw new Error('Request failed with status ' + response.status);
                            // Not window.location.reload(): this page was itself reached via a
                            // POST (left-menu page navigation submits a form), and reloading a
                            // POST-loaded document can trigger the browser's "confirm form
                            // resubmission" prompt or silently fail to refresh - looking exactly
                            // like a stuck load with the edit never appearing to take effect.
                            // Navigate to the plain GET URL instead.
                            window.location.href = '<?php echo base_url('SCORM/course_builder/Editor'); ?>';
                        })
                        .catch(function(err) {
                            var overlay = document.getElementById('loading-screen');
                            if (overlay) overlay.style.display = 'none';
                            var message = 'Unable to update the page. Please try again.';
                            if (errorBox) {
                                errorBox.textContent = message;
                                errorBox.style.display = 'block';
                            } else {
                                alert(message);
                            }
                        });
                });
            })();
        </script>

        <?php if ($row['sub_page_main'] == 0) { ?>
            <script>
                (function() {
                    var createSubPageTrigger = document.getElementById('createSubPageTrigger');
                    var editModalEl = document.getElementById('editPageModal');
                    var subPageModalEl = document.getElementById('addSubPageModal');
                    var addSubPageFormEl = document.getElementById('addSubPageForm');

                    // See the equivalent guard on #editPageForm above - this pane can be
                    // refreshed in place, which would otherwise re-run this block against stale
                    // elements and throw instead of ever sending the request.
                    if (!createSubPageTrigger || !editModalEl || !subPageModalEl || !addSubPageFormEl ||
                        createSubPageTrigger.dataset.boundClick === '1') return;
                    createSubPageTrigger.dataset.boundClick = '1';

                    createSubPageTrigger.addEventListener('click', function() {
                        // Wait for the Edit Page modal to finish closing before opening the
                        // next one - Bootstrap doesn't like two modals animating at once.
                        editModalEl.addEventListener('hidden.bs.modal', function handler() {
                            editModalEl.removeEventListener('hidden.bs.modal', handler);
                            new bootstrap.Modal(subPageModalEl).show();
                        });
                        bootstrap.Modal.getInstance(editModalEl).hide();
                    });

                    addSubPageFormEl.addEventListener('submit', function(e) {
                        e.preventDefault();
                        var form = e.target;
                        var errorBox = document.getElementById('addSubPageModalError');
                        if (errorBox) errorBox.style.display = 'none';
                        // fetch(), not jQuery.ajax() - see the equivalent comment on
                        // #editPageForm's handler above.
                        fetch(form.action, {
                                method: 'POST',
                                body: new FormData(form),
                                credentials: 'same-origin'
                            })
                            .then(function(response) {
                                var overlay = document.getElementById('loading-screen');
                                if (overlay) overlay.style.display = 'none';
                                if (!response.ok) throw new Error('Request failed with status ' + response.status);
                                window.location.href = '<?php echo base_url('SCORM/course_builder/Editor'); ?>';
                            })
                            .catch(function(err) {
                                var overlay = document.getElementById('loading-screen');
                                if (overlay) overlay.style.display = 'none';
                                var message = 'Unable to add the sub page. Please try again.';
                                if (errorBox) {
                                    errorBox.textContent = message;
                                    errorBox.style.display = 'block';
                                } else {
                                    alert(message);
                                }
                            });
                    });
                })();
            </script>
        <?php } ?>

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
            $baseloc = 'C:/wamp64/www/DOCHEK/';
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
                                                <label><?php echo lang('UI_Text.Description'); ?></label>
                                                <textarea class="form-control" name="description"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-2">
                                                <label><?php echo lang('UI_Text.CB_Assign_To'); ?></label>
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
                                                <label><?php echo lang('UI_Text.CB_Level'); ?></label>
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
                                                <label><?php echo lang('UI_Text.Due_Date'); ?></label>
                                                <input class="form-control" id="due_date" name="due_date" type="date"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-2">
                                                <label><?php echo lang('UI_Text.CB_Priority'); ?></label>
                                                <select class="form-select" name="priority">
                                                    <option value="High"><?php echo lang('UI_Text.CB_Priority_High'); ?></option>
                                                    <option value="Medium"><?php echo lang('UI_Text.CB_Priority_Medium'); ?></option>
                                                    <option value="Low"><?php echo lang('UI_Text.CB_Priority_Low'); ?></option>
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
                                                    class="btn btn-sm btn-danger btn-block"><?php echo lang('Buttons.CB_Assign_Task'); ?></button>
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
                                10 => 'text', 11 => 'text', 12 => 'text',
                            ];
                            $typePartialName = $typePartials[$row['type']] ?? null;

                            if ($typePartialName !== null) {
                                echo view('page/course_builder/types/' . $typePartialName, get_defined_vars());
                            } else {
                                echo '<h4>' . lang('UI_Text.CB_Page_Under_Development') . '</h4>';
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
                                            <?php echo lang('UI_Text.CB_Content'); ?>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>

                <?php } ?>
</div>
</div>