<style>
    tr[color_code='Delayed'] {
        color: red
    }
</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('template') ?>">Back</a>
            </li><b> &nbsp;>&nbsp;</b>
            <li class="active">
                Edit Template
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="x_panel">
                <form class="form" action="<?php echo base_url('template/edittemplate/' . $row['id_d']); ?>" method="POST"><?= csrf_field() ?>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="templatename" placeholder="Enter Template Name" value="<?php echo $row['name'] ?>" />
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-warning btn-sm form-control">
                            <i class="ace-icon fa fa-key bigger-110"></i> Update
                        </button>
                    </div>
                    <?php if (isset($validationData)) : ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validationData->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
        $('#startdate').datepicker({
            dateFormat: 'yy-mm-dd',
        });

        $('#enddate').datepicker({
            dateFormat: 'yy-mm-dd',
        });
    });
</script>