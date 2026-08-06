<?php
$needsImage = ($row['type'] == 11 || $row['type'] == 12);
$content = $row['content'] ?? '';
$pageImageFile = $row['page_image'] ?? '';
$imageAlt = $row['image_alt'] ?? '';

$pageImageUrl = '';
$hasPageImage = false;
if ($pageImageFile !== '') {
    $pageImageDiskPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $coursedetails[0]['createdon'] . '/assets/page_images/' . $pageImageFile;
    if (file_exists($pageImageDiskPath)) {
        $hasPageImage = true;
        $pageImageUrl = base_url() . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $coursedetails[0]['createdon'] . '/assets/page_images/' . $pageImageFile;
    }
}

$imageColumn = '<div class="mb-2">'
    . '<label>Image' . ($hasPageImage ? '' : ' <span class="text-danger">*</span>') . '</label>';
if ($hasPageImage) {
    // Image already uploaded: show it + Delete only. The upload input reappears once it's deleted.
    $imageColumn .= '<div class="mb-2">'
        . '<img src="' . $pageImageUrl . '" alt="' . esc($imageAlt) . '" class="rounded border d-block mb-2 img-fluid">'
        . '<button type="submit" form="deleteTextImageForm" class="btn btn-outline-danger waves-effect btn-xs waves-light rounded-pill" onclick="return confirm(\'' . lang('Alert.Aler_003') . '\')">'
        . '<span class="mdi mdi-trash-can-outline"></span> Delete Image</button>'
        . '</div>';
} else {
    $imageColumn .= '<input type="file" name="image" id="pageImageInput" class="form-control" accept=".jpg,.jpeg,.png,.JPG,.JPEG,.PNG" />'
        . '<small class="form-text text-muted">JPG, JPEG or PNG. Max 1 MB.</small>';
}
$imageColumn .= '</div>';
if (!$hasPageImage) {
    $imageColumn .= '<div class="mb-2">'
        . '<label>Image Alt Text</label>'
        . '<input type="text" name="image_alt" class="form-control" value="' . esc($imageAlt) . '" placeholder="Describe the image for accessibility">'
        . '</div>';
}

$editorColumn = '<div class="mb-2">'
    . '<label>Content</label>'
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
                            <!-- Editing layout mirrors the final rendering: image on the left, content on the right. -->
                            <div class="row">
                                <div class="col-md-5"><?= $imageColumn ?></div>
                                <div class="col-md-7"><?= $editorColumn ?></div>
                            </div>
                        <?php } elseif ($row['type'] == 12) { ?>
                            <!-- Editing layout mirrors the final rendering: content on the left, image on the right. -->
                            <div class="row">
                                <div class="col-md-7"><?= $editorColumn ?></div>
                                <div class="col-md-5"><?= $imageColumn ?></div>
                            </div>
                        <?php } else { ?>
                            <?= $editorColumn ?>
                        <?php } ?>

                        <div class="form-group col-md-12 mb-2">
                            <?php if (isset($promovalidation)): ?>
                                <div class="form-group col-md-12">
                                    <div class="alert alert-white" role="alert">
                                        <?= $promovalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <button type="submit"
                                class="btn btn-outline-warning waves-effect btn-sm waves-light rounded-pill"
                                id="saveTextButton">Save</button>
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

<script>
    var saveTextForm = document.getElementById('saveTextForm');
    if (saveTextForm) {
        saveTextForm.addEventListener('submit', function() {
            var button = document.getElementById('saveTextButton');
            button.disabled = true;
            button.innerHTML = 'Saving...';
        });
    }
</script>
