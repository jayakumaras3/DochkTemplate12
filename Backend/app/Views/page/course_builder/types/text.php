<?php
$textLayout = isset($row['text_layout']) ? (int) $row['text_layout'] : 0;
$textContent = $row['text_content'] ?? '';
$textImageFile = $row['text_image'] ?? '';

$textImageUrl = '';
$hasTextImage = false;
if ($textImageFile !== '') {
    $textImageDiskPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $coursedetails[0]['createdon'] . '/assets/text_images/' . $textImageFile;
    if (file_exists($textImageDiskPath)) {
        $hasTextImage = true;
        $textImageUrl = base_url() . 'assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $coursedetails[0]['createdon'] . '/assets/text_images/' . $textImageFile;
    }
}
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

                        <div class="form-group col-md-12 mb-2">
                            <label>Layout</label>
                            <select name="text_layout" class="form-control" id="textLayoutSelect">
                                <option value="0" <?php echo $textLayout == 0 ? 'selected' : ''; ?>>Plain Text</option>
                                <option value="1" <?php echo $textLayout == 1 ? 'selected' : ''; ?>>Image + Text</option>
                                <option value="2" <?php echo $textLayout == 2 ? 'selected' : ''; ?>>Text + Image</option>
                            </select>
                        </div>

                        <div class="form-group col-md-12 mb-2">
                            <label>Content</label>
                            <textarea class="ckeditor" name="text_content"><?php echo $textContent; ?></textarea>
                        </div>

                        <div class="form-group col-md-6 mb-2" id="textImageField" style="<?php echo $textLayout == 0 ? 'display:none;' : ''; ?>">
                            <label>Image<?php echo $hasTextImage ? '' : ' <span class="text-danger">*</span>'; ?></label>
                            <input type="file" name="image" accept=".jpg,.jpeg,.png,.JPG,.JPEG,.PNG" />
                            <?php if ($hasTextImage) { ?>
                                <div class="mt-2">
                                    <img src="<?php echo $textImageUrl; ?>" style="max-width:220px;max-height:140px;" class="rounded border">
                                </div>
                            <?php } ?>
                        </div>

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
                <?php } ?>

                <hr>
                <h5>Preview</h5>
                <div class="text-page-preview">
                    <?php if ($textLayout == 1 && $hasTextImage) { ?>
                        <div class="mb-3"><img src="<?php echo $textImageUrl; ?>" class="img-fluid rounded"></div>
                        <div><?php echo $textContent; ?></div>
                    <?php } elseif ($textLayout == 2 && $hasTextImage) { ?>
                        <div class="mb-3"><?php echo $textContent; ?></div>
                        <div><img src="<?php echo $textImageUrl; ?>" class="img-fluid rounded"></div>
                    <?php } else { ?>
                        <div><?php echo $textContent; ?></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var textLayoutSelect = document.getElementById('textLayoutSelect');
    if (textLayoutSelect) {
        textLayoutSelect.addEventListener('change', function() {
            document.getElementById('textImageField').style.display = (this.value === '0') ? 'none' : 'block';
        });
    }

    var saveTextForm = document.getElementById('saveTextForm');
    if (saveTextForm) {
        saveTextForm.addEventListener('submit', function() {
            var button = document.getElementById('saveTextButton');
            button.disabled = true;
            button.innerHTML = 'Saving...';
        });
    }
</script>
