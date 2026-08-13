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

    [data-bs-theme="dark"] .card {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3), 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .menu-section-title {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: var(--ct-body-color);
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
        border-bottom: 1px solid var(--ct-border-color-translucent) !important;
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
        color: var(--ct-body-color);
    }

    .menu-page-item.active .menu-page-num,
    .menu-page-item.active .menu-page-name {
        color: rgb(var(--ct-primary-rgb));
        font-weight: 700;
    }

    .menu-page-sep {
        color: var(--ct-secondary-color);
        margin: 0 4px;
    }

    .menu-page-name {
        color: var(--ct-body-color);
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
        background-color: var(--ct-tertiary-bg);
        color: var(--ct-secondary-color);
        font-size: 14px;
    }

    .menu-page-item.active .menu-type-icon {
        background-color: var(--ct-body-bg);
        color: rgb(var(--ct-primary-rgb));
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($courses_link); ?>"><?= esc($courses_link_label) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more'); ?>"><?php echo lang('UI_Text.CB_Course_Detail'); ?></a></li>
                </ol>
            </div>
            <h4 class="page-title">
                <?php echo lang('UI_Text.CB_Course_Builder'); ?>
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
                    $nxt_page = 1;
                    foreach ($pagesDetails as $pageDetails) {
                        if ((float) $pageDetails['sub_page_main'] === 0.0) {
                            $nxt_page = max($nxt_page, (int) $pageDetails['page_number'] + 1);
                        }
                    }

                    ?>
                    <!-- RIGHT: Buttons -->
                    <div class="btn-group" style="gap: 6px;">

                        <form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/view_full_sb') ?>" method="POST"><?= csrf_field() ?>
                            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                            <button type="submit" title="<?php echo lang('UI_Text.CB_Full_Storyboard'); ?>" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">
                                <i class="mdi mdi-book-open"></i> <?php echo lang('UI_Text.CB_Storyboard'); ?>
                            </button>
                        </form>

                        <?php if ($courseDetails[0]['type'] == 11 && (in_array('5', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('44', $arrayuserlevel))) { ?>
                            <form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_pdf_view') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="course_name" value="<?php echo $courseDetails[0]['course_name']; ?>">
                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                                <button type="submit" title="<?php echo lang('UI_Text.CB_Export_SCORM'); ?>" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">
                                    <i class="mdi mdi-download"></i> <?php echo lang('UI_Text.CB_Export'); ?>
                                </button>
                            </form>
                        <?php } ?>

                        <?php // Same import feature as SCORM/course_builder/Scorm_course_pages/page_add_view's
                        // "Import New Page structure" card - client-gated there too, kept the same
                        // way here rather than opening it up to clients it wasn't vetted for.
                        if ($client == 1) { ?>
                            <button type="button" title="<?php echo lang('UI_Text.CB_Import_Page_Structure'); ?>" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#importPageStructureModal">
                                <i class="mdi mdi-upload"></i> <?php echo lang('UI_Text.CB_Import'); ?>
                            </button>
                        <?php } ?>

                        <?php // Internal-use only (checking feedback data) - not something any client
                        // other than DOCHEK's own (client_id 1) should see, regardless of role.
                        if ($client == 1 && (in_array('67', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel))) { ?>
                            <form action="<?php echo base_url('SCORM/course_builder/Editor/settings') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="course_name" value="<?php echo $courseDetails[0]['course_name']; ?>">
                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                                <button type="submit" title="<?php echo lang('UI_Text.CB_Developer_Settings'); ?>" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">
                                    <i class="mdi mdi-cog-outline"></i> <?php echo lang('Buttons.Settings'); ?>
                                </button>
                            </form>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php if ($client == 1) { ?>
    <!-- Same fields/endpoint as SCORM/course_builder/Scorm_course_pages/page_add_view's
         "Import New Page structure" card, shown as a modal here instead. -->
    <div class="modal fade" id="importPageStructureModal" tabindex="-1" aria-labelledby="importPageStructureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content edit-page-modal">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold" id="importPageStructureModalLabel"><?php echo lang('UI_Text.CB_Import_Page_Structure'); ?></h5>
                        <p class="text-muted font-13 mb-0"><?php echo lang('UI_Text.CB_Upload_Spreadsheet_Bulk_Pages_Sub'); ?></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <?php if (isset($excelvalidation)) : ?>
                        <div class="alert alert-danger"><?= $excelvalidation->listErrors() ?></div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <a href="<?php echo base_url('assets/assets/uploads/import_page_structure.xlsx'); ?>" target="_blank" class="btn btn-outline-info btn-xs rounded-pill waves-effect waves-light">
                            <i class="mdi mdi-file-download-outline"></i> <?php echo lang('UI_Text.Download_Template'); ?>
                        </a>
                    </div>
                    <form id="importPageStructureForm" class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/importPageStructure') ?>" method="POST"><?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.CB_Spreadsheet_File'); ?> <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="file" accept=".csv,.xls,.xlsx" required>
                        </div>
                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                    </form>
                </div>
                <div class="modal-footer border-top">
                    <button type="submit" form="importPageStructureForm" class="btn btn-primary rounded-pill px-4"><?php echo lang('UI_Text.CB_Import'); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-xl-3 col-lg-6 order-lg-1 order-xl-1">
        <!-- start profile info -->
        <div class="card">
            <div class="card-body">
                <div class="todoapp">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 id="todo-message" class="menu-section-title mb-0"><?php echo lang('UI_Text.CB_Menu'); ?></h5>
                        <button type="button" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#createPageModal"><i class="mdi mdi-plus-circle"></i> <?php echo lang('UI_Text.CB_New_Page'); ?></button>
                    </div>

                    <div class="modal fade" id="createPageModal" tabindex="-1" aria-labelledby="createPageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content create-page-modal">
                                <div class="modal-header border-0 pb-0">
                                    <div>
                                        <h5 class="modal-title fw-bold" id="createPageModalLabel"><?php echo lang('UI_Text.CB_Create_New_Page'); ?></h5>
                                        <p class="text-muted font-13 mb-0"><?php echo lang('UI_Text.CB_Fill_Details_Add_Page_Sub'); ?></p>
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
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.CB_Page_Name'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="page_name" placeholder="<?php echo lang('UI_Text.CB_Page_Name'); ?>" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.CB_Page_Type'); ?></label>
                                            <select name="type" class="form-control">
                                                <option value="2" SELECTED><?php echo lang('UI_Text.CB_Type_Video'); ?></option>
                                                <option value="9"><?php echo lang('UI_Text.CB_Type_Audio_Version'); ?></option>
                                                <option value="8"><?php echo lang('UI_Text.CB_Type_Video_Sub_Page'); ?></option>
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
                                        <div class="mb-1">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.CB_Page_Number'); ?> <span class="text-danger">*</span></label>
                                            <input type="number" min="1" step="1" class="form-control" name="page_number" placeholder="<?php echo lang('UI_Text.CB_Page_Number'); ?>" oninput="validateCreatePageNumber(this)" value="<?php echo $nxt_page; ?>" required />
                                            <span id="createPageNumberError" style="color: red;"></span>
                                        </div>
                                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                                    </form>
                                </div>
                                <div class="modal-footer border-top">
                                    <button type="submit" form="createPageForm" class="btn btn-primary rounded-pill px-4"><?php echo lang('Buttons.CB_Add_Page'); ?></button>
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
                                errorMsg.textContent = "<?php echo lang('UI_Text.CB_Positive_Number_Required'); ?>";
                                input.value = "";
                            } else {
                                errorMsg.textContent = "";
                            }
                        }
                    </script>


                    <div>
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

                                                    // Sub-pages use the decimal part of page_number to indicate
                                                    // their parent (e.g. 1.10 under main page 1) - keep that suffix
                                                    // only for those; main pages just show the zero-padded whole
                                                    // number so the menu doesn't waste width on ".00" for everyone.
                                                    $isSubPage = ((float) $eachpagesDetails['sub_page_main']) != 0;
                                                    $pageNumberParts = explode('.', (string) $eachpagesDetails['page_number'], 2);
                                                    $displayPageNumber = str_pad((string) (int) $pageNumberParts[0], 2, '0', STR_PAD_LEFT);
                                                    if ($isSubPage && isset($pageNumberParts[1])) {
                                                        $displayPageNumber .= '.' . $pageNumberParts[1];
                                                    }
                                                ?>
                                                    <form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                        <input type="hidden" name="page_number" value="<?php echo $eachpagesDetails['page_number'] ?>">
                                                        <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                        <button type="submit" class="<?php echo $itemClass; ?>">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="text-truncate">
                                                                    <span class="menu-page-num"><?php echo $displayPageNumber; ?></span>
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
    </div>
