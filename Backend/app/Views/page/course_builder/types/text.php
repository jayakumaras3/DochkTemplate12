<?php
$needsImage = ($row['type'] == 11 || $row['type'] == 12);
$content = $row['content'] ?? '';
$pageImageFile = $row['page_image'] ?? '';
$imageAlt = $row['image_alt'] ?? '';

$pageImageUrl = '';
$hasPageImage = false;
if ($pageImageFile !== '') {
    $pageImageDiskPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $coursedetails[0]['createdon'] . '/assets/html/' . $page_id . '/' . $pageImageFile;
    if (file_exists($pageImageDiskPath)) {
        $hasPageImage = true;
        $pageImageUrl = base_url() . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $coursedetails[0]['createdon'] . '/assets/html/' . $page_id . '/' . $pageImageFile;
    }
}

$imageColumn = '<div class="mb-2">';
if ($hasPageImage) {
    // Image already uploaded: show it + Delete only. The upload input reappears once it's deleted.
    $imageColumn .= '<label>' . lang('UI_Text.CB_Image') . '</label>'
        . '<div class="mb-2">'
        . '<img src="' . $pageImageUrl . '" alt="' . esc($imageAlt) . '" class="rounded border d-block mb-2 img-fluid">'
        . '<button type="submit" form="deleteTextImageForm" class="btn btn-outline-danger waves-effect btn-xs waves-light rounded-pill" onclick="return confirm(\'' . lang('Alert.Aler_003') . '\')">'
        . '<span class="mdi mdi-trash-can-outline"></span> ' . lang('UI_Text.CB_Delete_Image') . '</button>'
        . '</div>';
} else {
    $imageColumn .= '<input type="hidden" name="image_required" value="1">'
        . '<h6 class="fw-semibold mb-1"><i class="mdi mdi-image-outline"></i> ' . lang('UI_Text.CB_No_Image_Uploaded') . '</h6>'
        . '<p class="text-muted mb-2">' . lang('UI_Text.CB_Upload_Image_File_Sub') . '</p>'
        . '<label>' . lang('UI_Text.CB_Image') . ' <span class="text-danger">*</span></label>'
        . '<input type="file" name="image" id="pageImageInput" class="form-control" accept=".jpg,.jpeg,.png,.JPG,.JPEG,.PNG" required />'
        . '<small class="form-text text-muted">' . lang('UI_Text.CB_JPG_JPEG_PNG_Max_1MB') . '</small>';
}
$imageColumn .= '</div>';
if (!$hasPageImage) {
    $imageColumn .= '<div class="mb-2">'
        . '<label>' . lang('UI_Text.CB_Image_Alt_Text') . '</label>'
        . '<input type="text" name="image_alt" class="form-control" value="' . esc($imageAlt) . '" placeholder="' . lang('UI_Text.CB_Image_Alt_Placeholder') . '">'
        . '</div>';
}

$editorColumn = '<div class="mb-2">'
    . '<label>' . lang('UI_Text.CB_Content') . '</label>'
    . '<textarea class="ckeditor" name="content">' . $content . '</textarea>'
    . '</div>';
?>
<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php if ($row['status'] != 8) { ?>
                    <form class="form-horizontal2" enctype="multipart/form-data"
                        action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/saveTextPage'); ?>"
                        method="post" id="saveTextForm"><?= csrf_field() ?>
                        <input type="hidden" name="course_id" value="<?php echo $course_id ?>">
                        <input type="hidden" name="page_id" value="<?php echo $page_id ?>">

                        <?php if ($row['type'] == 11) { ?>
                            <!-- Image-Text: image on the left, content on the right. -->
                            <div class="row">
                                <div class="col-md-5"><?= $imageColumn ?></div>
                                <div class="col-md-7"><?= $editorColumn ?></div>
                            </div>
                        <?php } else if ($row['type'] == 12) { ?>
                            <!-- Text-Image: content on the left, image on the right. -->
                            <div class="row">
                                <div class="col-md-7"><?= $editorColumn ?></div>
                                <div class="col-md-5"><?= $imageColumn ?></div>
                            </div>
                        <?php } else { ?>
                            <?= $editorColumn ?>
                        <?php } ?>

                        <?php if (isset($promovalidation)): ?>
                            <div class="form-group col-md-12">
                                <div class="alert alert-white" role="alert">
                                    <?= $promovalidation->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-center pt-3 mt-2 border-top">
                            <button type="submit"
                                class="btn btn-primary rounded-pill px-4 waves-effect waves-light"
                                id="saveTextButton"><?php
                                    if ($row['type'] == 11) {
                                        echo lang('UI_Text.CB_Save_Image_And_Content');
                                    } elseif ($row['type'] == 12) {
                                        echo lang('UI_Text.CB_Save_Content_And_Image');
                                    } else {
                                        echo lang('Buttons.CB_Save');
                                    }
                                ?></button>
                        </div>
                    </form>

                    <?php if ($needsImage && $hasPageImage) { ?>
                        <form action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/deleteTextImage'); ?>"
                            method="post" id="deleteTextImageForm"><?= csrf_field() ?>
                            <input type="hidden" name="page_id" value="<?php echo $page_id ?>">
                        </form>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <table class="table dt-responsive wrap w">
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
    function bindSavingIndicator(formId, buttonId) {
        var form = document.getElementById(formId);
        var button = document.getElementById(buttonId);
        if (form && button) {
            form.addEventListener('submit', function() {
                button.disabled = true;
                button.innerHTML = '<?php echo lang('UI_Text.CB_Saving'); ?>';
            });
        }
    }
    bindSavingIndicator('saveTextForm', 'saveTextButton');
</script>
