<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
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
                <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                    <?= csrf_field() ?>
                    <div class="col-md-4">
                        <input type="text" class="form-control col-md-12" name="option" placeholder="option" value="<?php echo $row['values'] ?>" />
                    </div><br />
                    <div class="col-md-4">
                        <input type="text" class="form-control col-md-12" name="score" placeholder="Score" value="<?php echo $row['score'] ?>" />
                    </div><br />
                    <div class="col-md-4">
                        <label>Correct</label>&nbsp;
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" name="truefalse" class="form-check-input" value="1" <?php if ($row['truefalse'] == 1) echo 'checked'; ?>> Yes
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" name="truefalse" class="form-check-input" value="2" <?php if ($row['truefalse'] == 2) echo 'checked'; ?>> No
                            </label>
                        </div>
                    </div><br />
                    <div class=" col-md-2">
                        <?php if (isset($coursevalidation)) : ?>
                            <div class=col-12 col-sm-4>
                                <div class="alert alert-danger" role="alert">
                                    <?= $coursevalidation->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="hidden" name="q_id" value="<?php echo $row['question_id'] ?>">
                        <input type="hidden" name="scourse_id" value="<?php echo $row['question_id'] ?>">
                        <input type="hidden" name="o_id" value="<?php echo $row['o_id'] ?>">
                        <input type="hidden" name="returnUrl" value="2">
                        <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                        <button type="submit" class="btn btn-info btn-sm col-md-4">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>