<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
        <li><a href="<?php echo base_url('my_training/read_more'); ?>">Course Detail</a></li><b>&nbsp;>&nbsp;</b>
            <li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b>
            <li><a href="<?php echo base_url($header_link_1); ?>"><?php echo $header_1; ?></a></li><b>&nbsp;>&nbsp;</b>
         
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                 <?= csrf_field() ?>
                <div class="col-md-6">
                    <div class="form-group col-md-12">
                        <label>Question Bank</label>
                        <select name="q_id" class="form-control col-md-12">
                            <?php foreach ($getQuestiondata as $eachcategories) { ?>
                                <option value="<?= $eachcategories['q_id'] ?>"><?= $eachcategories['question'] ?></option>
                            <?php
                            } ?>
                        </select>
                    </div>
                    <div class="form-group  col-md-12">
                        <?php if (isset($coursevalidation)) : ?>
                            <div class=col-12 col-sm-4>
                                <div class="alert alert-danger" role="alert">
                                    <?= $coursevalidation->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                        <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                        <button type="submit" class="btn btn-info btn-sm col-md-4">
                            <i class="ace-icon fa fa-key bigger-110"></i> Add Question
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>