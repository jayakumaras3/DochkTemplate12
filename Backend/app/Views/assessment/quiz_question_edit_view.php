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
<?php if (session()->get('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->get('success') ?>
    </div>
    <?php endif; ?><?php if (session()->get('error')) : ?>
    <div class="alert alert-danger" role="alert">
        <?= session()->get('error') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                    <?= csrf_field() ?>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label>Question</label>
                            <input type="text" class="form-control col-md-12" name="question" placeholder="Question" value="<?php echo isset($row['question']) ? htmlspecialchars($row['question']) : '' ?>" />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Category</label>
                            <select name="category" class="form-control col-md-12">
                                <?php if (!empty($allcategories)) {
                                    foreach ($allcategories as $eachcategories) {
                                        // print_r($eachcategories);
                                        if ($row['category'] == $eachcategories['sc_mcid']) { ?>
                                            <option selected='selected' value="<?php echo $eachcategories['sc_mcid'] ?>"><?php echo $eachcategories['description'] ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $eachcategories['sc_mcid'] ?>"><?php echo $eachcategories['description'] ?></option>
                                <?php }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Score</label>
                            <input type="number" min="0" max="100" class="form-control col-md-12" name="score" placeholder="Score" value="<?php echo $row['score'] ?>" oninput="clampScoreInput(this)" />
                        </div>
                        <?php if ($type == 5 || $type == 6) {
                        } else { ?>
                            <div class="form-group col-md-12">
                                <label>Type</label>
                                <select name="quiz_type" class="form-control col-md-12">
                                    <?php if (!empty($AssessmentQuestionType)) {
                                        foreach ($AssessmentQuestionType as $type) {
                                            if ($row['quiz_type'] == $type['id_d']) { ?>
                                                <option selected='selected' value="<?php echo $type['id_d'] ?>"><?php echo $type['name'] ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $type['id_d'] ?>"><?php echo $type['name'] ?></option>
                                    <?php }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php } ?>
                        <div class="form-group col-md-12">
                            <label>Correct feedback</label>
                            <input type="text" class="form-control col-md-12" name="correct" placeholder="Correct" value="<?php echo $row['correct'] ?>" />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Incorrect feedback</label>
                            <input type="text" class="form-control col-md-12" name="incorrect" placeholder="Incorrect" value="<?php echo $row['incorrect'] ?>" />
                        </div>
                        <div class="form-group col-md-12">
                            <label>No Attempts feedback</label>
                            <input type="text" class="form-control col-md-12" name="noAttempts" placeholder="No Attempts" value="<?php echo $row['noAttempts'] ?>" />
                        </div><br />
                        <div class="form-group  col-md-12">
                            <?php if (isset($coursevalidation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $coursevalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="q_id" value="<?php echo $row['q_id']; ?>">
                            <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                            <input type="hidden" name="returnUrl" value="1">
                            <button type="submit" class="btn btn-info btn-sm col-md-4">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // min/max on a number input only affect the spinner arrows and form-level validation -
    // typing or pasting a value directly bypasses them, so clamp on input too.
    function clampScoreInput(el) {
        if (el.value === '') return;
        var value = parseInt(el.value, 10);
        if (isNaN(value)) {
            el.value = '';
            return;
        }
        var min = el.min !== '' ? parseInt(el.min, 10) : null;
        var max = el.max !== '' ? parseInt(el.max, 10) : null;
        if (min !== null && value < min) value = min;
        if (max !== null && value > max) value = max;
        el.value = value;
    }
</script>