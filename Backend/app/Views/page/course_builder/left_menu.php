<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
$client = session()->get('client');
?>
<style>
    .card {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: none;
    }

    .menu-section-title {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #495057;
    }

    .btn-add-page {
        background-color: rgba(var(--ct-primary-rgb), 0.1);
        color: rgb(var(--ct-primary-rgb));
        border: 1px solid rgba(var(--ct-primary-rgb), 0.25);
        font-weight: 600;
        font-size: 12.5px;
    }

    .btn-add-page:hover {
        background-color: rgba(var(--ct-primary-rgb), 0.18);
        color: rgb(var(--ct-primary-rgb));
    }

    .todo-list.list-group {
        gap: 4px;
    }

    .menu-page-item {
        display: block;
        padding: 12px 14px;
        border-radius: 10px !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06) !important;
        background-color: transparent;
    }

    .menu-page-item:last-child {
        border-bottom: none !important;
    }

    .menu-page-item.active {
        background-color: rgba(var(--ct-primary-rgb), 0.08) !important;
        border: 1px solid rgba(var(--ct-primary-rgb), 0.2) !important;
        border-left: 3px solid rgb(var(--ct-primary-rgb)) !important;
    }

    .menu-page-num {
        font-weight: 700;
        color: #495057;
    }

    .menu-page-item.active .menu-page-num,
    .menu-page-item.active .menu-page-name {
        color: rgb(var(--ct-primary-rgb));
        font-weight: 700;
    }

    .menu-page-sep {
        color: #adb5bd;
        margin: 0 4px;
    }

    .menu-page-name {
        color: #495057;
        font-size: 13.5px;
    }

    .menu-page-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
        margin-left: 10px;
    }

    .menu-type-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background-color: #f1f3f5;
        color: #6c757d;
        font-size: 14px;
    }

    .menu-page-item.active .menu-type-icon {
        background-color: #fff;
        color: rgb(var(--ct-primary-rgb));
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($courses_link); ?>"><?= esc($courses_link_label) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more'); ?>">Course
                            Detail</a></li>
                </ol>
            </div>
            <h4 class="page-title">
                Course Builder
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-2">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- LEFT: Course name -->
                    <h4><?php echo $courseDetails[0]['course_name']; ?></h4>
                    <?php
                    if ($pagesDetails) {
                        $arrayleng = count($pagesDetails) - 1;
                        $nxt_page = $pagesDetails[$arrayleng]['page_number'] + 1;
                    } else {
                        $nxt_page = 1;
                    }

                    ?>
                    <!-- RIGHT: Buttons -->
                    <div class="btn-group" style="gap: 6px;">

                        <form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/view_full_sb') ?>" method="POST"><?= csrf_field() ?>
                            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                            <button type="submit" title="Full Storyboard" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">
                                <i class="mdi mdi-book-open"></i> Storyboard
                            </button>
                        </form>

                        <?php if ($courseDetails[0]['type'] == 11 && (in_array('5', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('44', $arrayuserlevel))) { ?>
                            <form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_pdf_view') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="course_name" value="<?php echo $courseDetails[0]['course_name']; ?>">
                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                                <button type="submit" title="Export SCORM" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">
                                    <i class="mdi mdi-download"></i> Export
                                </button>
                            </form>
                        <?php } ?>

                        <?php if (in_array('67', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
                            <form action="<?php echo base_url('SCORM/course_builder/Editor/settings') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="course_name" value="<?php echo $courseDetails[0]['course_name']; ?>">
                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                                <button type="submit" title="Developer Settings" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">
                                    <i class="mdi mdi-cog-outline"></i> Settings
                                </button>
                            </form>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-lg-6 order-lg-1 order-xl-1">
        <!-- start profile info -->
        <div class="card">
            <div class="card-body">
                <div class="todoapp">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 id="todo-message" class="menu-section-title mb-0">MENU</h5>
                        <button type="button" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#createPageModal"><i class="mdi mdi-plus-circle"></i> New Page</button>
                    </div>

                    <div class="modal fade" id="createPageModal" tabindex="-1" aria-labelledby="createPageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content create-page-modal">
                                <div class="modal-header border-0 pb-0">
                                    <div>
                                        <h5 class="modal-title fw-bold" id="createPageModalLabel">Create New Page</h5>
                                        <p class="text-muted font-13 mb-0">Fill in the details below to add a page to this course.</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-2">
                                    <?php if (isset($pagevalidation)) : ?>
                                        <div class="alert alert-danger">
                                            <?= $pagevalidation->listErrors() ?>
                                        </div>
                                    <?php endif; ?>
                                    <form id="createPageForm" class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/addpage') ?>" method="POST"><?= csrf_field() ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Page Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="page_name" placeholder="Page Name" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Page Type</label>
                                            <select name="type" class="form-control">
                                                <option value="2" SELECTED>Video</option>
                                                <option value="9">Audio Version</option>
                                                <option value="8">Video Sub Page</option>
                                                <option value="1">Articulate</option>
                                                <option value="5">SCQ Check your understanding</option>
                                                <option value="6">MCQ Check your understanding</option>
                                                <option value="4">Quiz</option>
                                                <option value="3">Html</option>
                                                <option value="10">Text Only</option>
                                                <option value="11">Image + Text</option>
                                                <option value="12">Text + Image</option>
                                            </select>
                                        </div>
                                        <div class="mb-1">
                                            <label class="form-label fw-semibold">Page Number <span class="text-danger">*</span></label>
                                            <input type="number" min="1" class="form-control" name="page_number" placeholder="Page number" oninput="validateCreatePageNumber(this)" value="<?php echo $nxt_page; ?>" required />
                                            <span id="createPageNumberError" style="color: red;"></span>
                                        </div>
                                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                                    </form>
                                </div>
                                <div class="modal-footer border-top">
                                    <button type="submit" form="createPageForm" class="btn btn-primary rounded-pill px-4">Add Page</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <style>
                        .create-page-modal {
                            border-radius: 18px;
                            border: none;
                            box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
                        }
                    </style>

                    <script>
                        function validateCreatePageNumber(input) {
                            var errorMsg = document.getElementById('createPageNumberError');
                            if (input.value <= 0) {
                                errorMsg.textContent = "Please enter a positive number.";
                                input.value = "";
                            } else {
                                errorMsg.textContent = "";
                            }
                        }
                    </script>


                    <div style="max-height: 480px;" data-simplebar="init">
                        <div class="simplebar-wrapper" style="margin: 0px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                                <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow:  scroll;">
                                        <div class="simplebar-content" style="padding: 0px;">
                                            <ul class="list-group list-group-flush todo-list" id="todo-list">

                                                <?php foreach ($pagesDetails as $eachpagesDetails) {
                                                    $isActivePage = isset($current_page_id) && $current_page_id == $eachpagesDetails['page_id'];
                                                    $itemClass = $isActivePage ? 'list-group-item list-group-item-action active fw-semibold border-0 menu-page-item' : 'list-group-item list-group-item-action border-0 menu-page-item';

                                                    $type = $eachpagesDetails['type'];
                                                    $typeIcon = '';
                                                    switch ($type) {
                                                        case 1:
                                                            $typeIcon = 'mdi-alpha-a-circle-outline';
                                                            break;
                                                        case 2:
                                                            $typeIcon = 'mdi-video-outline';
                                                            break;
                                                        case 8:
                                                            $typeIcon = 'mdi-video-plus-outline';
                                                            break;
                                                        case 3:
                                                            $typeIcon = 'mdi-language-html5';
                                                            break;
                                                        case 4:
                                                            $typeIcon = 'mdi-crosshairs-question';
                                                            break;
                                                        case 5:
                                                            $typeIcon = 'mdi-radiobox-marked';
                                                            break;
                                                        case 6:
                                                            $typeIcon = 'mdi-checkbox-marked-outline';
                                                            break;
                                                        case 9:
                                                            $typeIcon = 'mdi-speaker';
                                                            break;
                                                        case 10:
                                                            $typeIcon = 'mdi-text-box-outline';
                                                            break;
                                                        case 11:
                                                            $typeIcon = 'mdi-image-text';
                                                            break;
                                                        case 12:
                                                            $typeIcon = 'mdi-text-box-multiple-outline';
                                                            break;
                                                    }
                                                ?>
                                                    <form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                        <input type="hidden" name="page_number" value="<?php echo $eachpagesDetails['page_number'] ?>">
                                                        <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                        <button type="submit" class="<?php echo $itemClass; ?>">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="text-truncate">
                                                                    <span class="menu-page-num"><?php echo $eachpagesDetails['page_number']; ?></span>
                                                                    <span class="menu-page-sep">|</span>
                                                                    <span class="menu-page-name"><?php echo $eachpagesDetails['page_name']; ?></span>
                                                                </div>
                                                                <div class="menu-page-meta">
                                                                    <?php if ($typeIcon) { ?>
                                                                        <span class="menu-type-icon"><i class="mdi <?php echo $typeIcon; ?>"></i></span>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </form>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: auto; height: 480px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: visible;">
                            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                            <div class="simplebar-scrollbar" style="height: 266px; display: block; transform: translate3d(0px, 43px, 0px);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>