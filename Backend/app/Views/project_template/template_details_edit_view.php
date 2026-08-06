<style>
    tr[color_code='Delayed'] {
        color: red
    }
</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('template/templatedetails/' . $row['fk_template_id']) ?>">Back</a>
            </li><b> &nbsp;>&nbsp;</b>
            <li class="active">
                Edit Template Details
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="content">
                <div class="x_panel">
                    <form class="form" action="<?php echo base_url('template/edittemplatedetails/' . $row['fk_template_id'] . '/' . $row['t_id']) ?>" method="POST"><?= csrf_field() ?>
                        <div class="col-md-2">
                            <select name="item_type" class="form-control">
                                <?php if (!empty($clientdata)) {
                                    foreach ($clientdata as $eachcategoryData) {
                                        if ($row['item_type'] == $eachcategoryData['id_d']) { ?>
                                            <option selected='selected' value="<?php echo $eachcategoryData['id_d'] ?>"><?php echo $eachcategoryData['name'] ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $eachcategoryData['id_d'] ?>"><?php echo $eachcategoryData['name'] ?></option>
                                <?php }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="item_description" placeholder="Description" value="<?php echo $row['item_description'] ?>" />
                        </div>
                        <div class="col-md-2">
                            <input type="text" id='days' class="form-control" name="duration" placeholder="Duration" value="<?php echo $row['duration'] ?>" />
                        </div>


                        <div class="col-md-3">
                            <button type="submit" class="btn btn-warning btn-sm form-control">
                                <i class="ace-icon fa fa-key bigger-110"></i> Edit Item
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