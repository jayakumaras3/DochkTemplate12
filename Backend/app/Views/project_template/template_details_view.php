<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('template') ?>">Back</a>
            </li><b> &nbsp;>&nbsp;</b>
            <li class="active">
                Template details
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <form class="form" action="<?php echo base_url('template/addtemplatedetails/' . $id_d) ?>" method="POST"><?= csrf_field() ?>
                <div class="col-md-2">
                    <select name="item_type" class="form-control">
                        <?php foreach ($clientdata as $eachcategoryData) { ?>
                            <option value="<?php echo $eachcategoryData['id_d'] ?>"><?php echo $eachcategoryData['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="item_description" placeholder="Description" value="" />
                </div>
                <div class="col-md-2">
                    <input type="text" id='days' class="form-control" name="duration" placeholder="Duration" value="" />
                </div>


                <div class="col-md-3">
                    <button type="submit" class="btn btn-info btn-sm form-control">
                        <i class="ace-icon fa fa-key bigger-110"></i> Add Item
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



        <div class="x_panel">
            <table id="example1" class="table table-sm  table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Assigned</th>
                        <th>Item Description</th>
                        <th>Time(Days)</th>
                        <th>Edit</th>
                        <th>Del</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $j = 0;
                    foreach ($templatedetails as $eachdealtimeline) {
                        $j = $j + 1 ?>
                        <tr>
                            <td><?php echo $j ?></td>
                            <td><?php echo $eachdealtimeline['itemtypename'] ?></td>
                            <td><?php echo $eachdealtimeline['item_description'] ?></td>
                            <td><?php echo $eachdealtimeline['duration'] ?></td>
                            <td><a href="<?php echo base_url('template/edittemplatedetails/' . $eachdealtimeline['fk_template_id'] . '/' . $eachdealtimeline['t_id']) ?>"><button class="widget-icon  btn-warning"><span class="icon-pencil"></span></button></a></td>
                            <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('template/deletetemplatedetails/' . $eachdealtimeline['fk_template_id'] . '/' . $eachdealtimeline['t_id']) ?>" ><button class="widget-icon  btn-danger"><span class="icon-trash"></span></button></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div><!-- /.row -->

<script LANGUAGE="JavaScript">
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,

        })
    });
</script>