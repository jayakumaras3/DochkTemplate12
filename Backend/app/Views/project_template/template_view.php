<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>Projects </li><b> &nbsp;>&nbsp;</b>
            <li class="active"> Project Templates</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <h2>Add New Template</h2>
            <div class="x_content">
                <br />
                <div class="block block-drop-shadow">
                    <div class="content controls">
                        <form class="form" action="<?php echo base_url('template/addtemplate'); ?>" method="POST"><?= csrf_field() ?>
                            <div class="form-group row" class="col-md-9 col-sm-9 ">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="templatename" placeholder="Template Name" value="" />
                                </div>
                            </div>
                            <div>
                                <?php if (isset($validationData)) : ?>
                                    <div class=col-12 col-sm-4>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $validationData->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-info btn-sm form-control"></i> <?php echo lang('Buttons.Add') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="x_panel">
            <h2>Templates List</h2>
            <div class="x_content">
                <br />
                <table cellpadding="0" cellspacing="0" width="100%" class="table  table-sm table-bordered table-striped sortable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Template Name</th>
                            <th>Details</th>
                            <th>Edit</th>
                            <th>Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if (!empty($templatedata)) {
                            foreach ($templatedata as $template) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $template['name'] ?></td>
                                    <td><a href="<?php echo base_url('template/templatedetails/' . $template['id_d']) ?>"><button class="widget-icon  btn-info"><span class="icon-laptop"></span></button></a></td>
                                    <td><a href="<?php echo base_url('template/edittemplate/' . $template['id_d']) ?>"><button class="widget-icon  btn-warning"><span class="icon-pencil"></span></button></a></td>
                                    <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('template/deletetemplate/' . $template['id_d']) ?>"><button class="widget-icon  btn-danger"><span class="icon-trash"></span></button></a></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>