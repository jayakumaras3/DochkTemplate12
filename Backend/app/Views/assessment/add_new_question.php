<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($main_header_link) ?>"><?php echo $main_header ?></a></li>
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
                <div class="x_panel">
                    <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Question</label>
                                <!-- <input type="text" class="form-control col-md-12" name="question" placeholder="Question" required /> -->
                                <textarea class="form-control col-md-12" name="question" placeholder="Question" required></textarea>
                            </div>
                        </div><br />
                        <?php if ($type == 5 || $type == 6) { ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Correct feedback</label>
                                    <input type="text" class="form-control col-md-12" name="correct" placeholder="Correct" />
                                </div>
                                <div class="col-md-4">
                                    <label>Incorrect feedback</label>
                                    <input type="text" class="form-control col-md-12" name="incorrect" placeholder="Incorrect" />
                                </div>
                                <div class="col-md-4">
                                    <label>No Attempts feedback</label>
                                    <input type="text" class="form-control col-md-12" name="noAttempts" placeholder="No Attempts" />
                                </div>
                            </div><br />
                        <?php } ?>
                        <?php if ($type == 4) { ?>
                            <div class="row">
                               <!--  <div class="col-md-4">
                                    <label>Category</label>
                                    <select name="category" class="form-select col-md-12">
                                        <?php foreach ($allcategories as $eachcategories) { ?>
                                            <option value="<?= $eachcategories['sc_mcid'] ?>"><?= $eachcategories['description'] ?></option>
                                        <?php
                                        } ?>
                                    </select>
                                </div> -->
                                <input type="hidden" name="category" value="123">
                                <div class="col-md-4">
                                    <label>Type of Question</label>
                                    <select class="form-select col-md-12" name="quiz_type">
                                        <?php foreach ($AssessmentQuestionType as $quiz_type) {
                                            echo '<option value="' . $quiz_type['id_d'] . '">' . $quiz_type['name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div><br />
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (isset($coursevalidation)) : ?>
                                    <div class=col-12 col-sm-4>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $coursevalidation->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                                <input type="hidden" name="type" value="<?php echo $type; ?>">
                                <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                                <input type="hidden" name="returnUrl" value="1">

                                <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light col-md-4" id="submitButton">
                                    </i> Add New Question
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>