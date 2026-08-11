<style>
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

    .sb-card {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: none;
        overflow: hidden;
    }

    [data-bs-theme="dark"] .sb-card {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3), 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .sb-col-audio {
        flex: 1 1 0;
        min-width: 0;
    }

    .sb-col-onscreen {
        flex: 0 0 220px;
    }

    .sb-col-notes {
        flex: 0 0 260px;
    }

    .sb-page-block {
        border-top: 1px solid var(--ct-border-color-translucent);
    }

    .sb-page-block:first-child {
        border-top: none;
    }

    .sb-page-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        background-color: rgba(var(--ct-primary-rgb), 0.05);
        cursor: pointer;
        user-select: none;
    }

    .sb-page-num {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: rgba(var(--ct-primary-rgb), 0.15);
        color: rgb(var(--ct-primary-rgb));
        font-weight: 700;
        font-size: 12.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .sb-page-title {
        font-weight: 700;
        color: var(--ct-body-color);
    }

    .sb-page-type {
        margin-left: auto;
        font-size: 13.5px;
        color: var(--ct-body-color);
    }

    .sb-page-type strong {
        color: rgb(var(--ct-primary-rgb));
    }

    .sb-page-chevron {
        color: rgb(var(--ct-primary-rgb));
        font-size: 18px;
        transition: transform 0.2s ease;
    }

    .sb-page-header[aria-expanded="false"] .sb-page-chevron {
        transform: rotate(180deg);
    }

    .sb-page-content {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 18px 20px;
    }

    .sb-col-label {
        font-weight: 700;
        font-size: 13px;
        color: rgb(var(--ct-primary-rgb));
        margin-bottom: 6px;
    }

    .sb-col-label i {
        margin-right: 4px;
    }

    .sb-col-text {
        font-size: 13.5px;
        color: var(--ct-body-color);
    }

    .sb-col-text p {
        margin: 0 0 8px;
    }

    .sb-col-text p:last-child {
        margin-bottom: 0;
    }

    .sb-col-edit {
        flex: 0 0 110px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/course_builder/Editor'); ?>">Course Builder</a></li>

                </ol>
            </div>
            <h4 class="page-title">
                Storyboard<?php echo !empty($full_sb) ? ' - ' . esc($full_sb[0]['course_name']) : ''; ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 text-end mb-2">
        <a href="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/generate_transcript_pdf'); ?>"
            data-download="1"
            class="btn btn-outline-warning btn-sm rounded-pill waves-effect waves-light">
            <i class="mdi mdi-file-pdf-box"></i> Export Audio Transcript PDF
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card sb-card">


            <div id="storyboardAccordion">
                <?php foreach ($full_sb as $content):
                    $pageNumberLabel = abs($content['page_number']);
                    $collapseId = 'sbPage' . $content['page_id'];

                    switch ($content['type']) {
                        case 1:
                            $typeLabel = 'Articulate';
                            break;
                        case 2:
                            $typeLabel = 'Video';
                            break;
                        case 3:
                            $typeLabel = 'Html';
                            break;
                        case 4:
                            $typeLabel = 'Quiz';
                            break;
                        case 5:
                            $typeLabel = 'SCQ CYU';
                            break;
                        case 6:
                            $typeLabel = 'MCQ CYU';
                            break;
                        case 8:
                            $typeLabel = 'Video Sub Page';
                            break;
                        case 9:
                            $typeLabel = 'Audio Version';
                            break;
                        case 10:
                            $typeLabel = 'Text Only';
                            break;
                        case 11:
                            $typeLabel = 'Image-Text';
                            break;
                        case 12:
                            $typeLabel = 'Text-Image';
                            break;
                        default:
                            $typeLabel = '-';
                    }
                ?>
                    <div class="sb-page-block">
                        <div class="sb-page-header" role="button" data-bs-toggle="collapse"
                            data-bs-target="#<?php echo $collapseId; ?>" aria-expanded="false"
                            aria-controls="<?php echo $collapseId; ?>">
                            <span class="sb-page-num"><?php echo $pageNumberLabel; ?></span>
                            <span class="sb-page-title">Page <?php echo $pageNumberLabel; ?>: <?php echo esc($content['page_name']); ?></span>
                            <span class="sb-page-type">Page Type: <strong><?php echo $typeLabel; ?></strong></span>
                            <i class="mdi mdi-chevron-up sb-page-chevron"></i>
                        </div>
                        <div class="collapse" id="<?php echo $collapseId; ?>" data-bs-parent="#storyboardAccordion">
                            <div class="sb-page-content">
                                
                                <div class="sb-col-audio">
                                    <div class="sb-col-label"><i class="mdi mdi-microphone-outline"></i> Audio Text/Transcript</div>
                                    <div class="sb-col-text"><?php echo $content['audio']; ?></div>
                                </div>
                                <div class="sb-col-onscreen">
                                    <div class="sb-col-label"><i class="mdi mdi-monitor"></i> On Screen</div>
                                    <div class="sb-col-text"><?php echo $content['on_screen_text']; ?></div>
                                </div>
                                <div class="sb-col-notes">
                                    <div class="sb-col-label"><i class="mdi mdi-file-document-edit-outline"></i> Production Notes</div>
                                    <div class="sb-col-text"><?php echo $content['production_notes']; ?></div>
                                </div>
                                <div class="sb-col-edit">
                                    <form class="form-horizontal"
                                        action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>"
                                        method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="page_id" value="<?php echo $content['page_id']; ?>">
                                        <input type="hidden" name="page_number" value="<?php echo $content['page_number']; ?>">
                                        <input type="hidden" name="page_name" value="<?php echo $content['page_name']; ?>">
                                        <button type="submit" class="btn btn-add-page btn-sm rounded-pill waves-effect waves-light">
                                            <i class="mdi mdi-pencil-outline"></i> Edit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
