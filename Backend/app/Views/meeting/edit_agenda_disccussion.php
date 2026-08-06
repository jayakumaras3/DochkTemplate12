<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('meeting_agenda/editagenda_header') ?>">Back</a>
            </li><b>&nbsp;>&nbsp;</b>
            
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="block">
            <div class="content">
                <div class="x_panel">
                    <form class="form" action="<?php echo base_url('meeting_agenda/updatemeetingagenda?id_ma='.$row['id_ma']) ?>" method="POST"><?= csrf_field() ?>

                        <div class="row-md-3">
                            <label>Completion Date : </label>
                            <input id="birthday" name="completion_dt" class="date-picker form-control" placeholder="Completion Date" type="text" value="<?php echo $row['completion_dt'] ?>" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div><br />
                        <div class="row-md-4">
                            <label>Discussion : </label>
                            <input class="form-control" name="discussion" type="hidden" value="<?php echo $row['project_status'] ?>"/>
                            <textarea class="ckeditor" name="discussion"><?php echo $row['project_status'] ?></textarea>

                            <!-- <input type="text" class="form-control" name="project_status" placeholder="Topics for discussion" value="" /> -->
                        </div><br />
                        <div class="row-md-4">
                            <label>Remarks : </label>
                            <input type="text" class="form-control" name="remarks" placeholder="Remarks" value="<?php echo $row['remarks'] ?>" />
                        </div><br />
                        <div class="row-md-1">

                            <button type="submit" class="btn btn-info btn-sm form-control">
                                <i class="ace-icon fa fa-key bigger-110"></i> Update
                            </button>
                        </div>
                        <?php if (isset($validationagenda)) : ?>
                            <div class="col-md-12">
                                <div class="alert alert-white" role="alert">
                                    <?= $validationagenda->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>