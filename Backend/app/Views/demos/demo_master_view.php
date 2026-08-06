<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>Demos</li><b>&nbsp;>&nbsp;</b>
            <li class="active">Create New Demo</li>
        </ol>
    </div>
</div>
<div class="main-content">
    <div class="main-content-inner">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="x_panel">
                <div class="col-xs-12">
                    <div class="col-xs-12">
                        <div class="form-row">
                            <div class="block block-drop-shadow">
                                <div class="content">
                                    <form action="<?php echo base_url('Demo/demo_master/createnewdemo') ?>" method="POST" autocomplete="off"><?= csrf_field() ?>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="description" placeholder="Demo Name" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="hidden" name="createnewdemo" value="1">
                                            <input type="hidden" name="username" value="<?php echo session()->get('username'); ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <i class="icon-key"></i> Create Demo
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <?php if (isset($demovalidation)) : ?>
                                    <div class=col-12 col-sm-4>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $demovalidation->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="x_title">
                        <div class="x_content">
                            <br />
                            <div>
                                <div class="block block-drop-shadow">
                                    <div class="content">
                                        <table id="dynamic-table" class="table  table-sm table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>

                                                    <th>#</th>
                                                    <th>Project Name</th>
                                                    <th>Description</th>
                                                    <th>Edit</th>
                                                    <th> Delete </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                if (isset($showactiveprojects['demoid'])) {
                                                    for ($i = 0; $i < count($showactiveprojects['projectname']); $i++) {
                                                        $k = $i + 1; ?>
                                                        <tr>
                                                            <td><?php echo $k ?></td>
                                                            <td><?php echo $showactiveprojects['projectname'][$i] ?></td>
                                                            <td><?php echo $showactiveprojects['description'][$i] ?></td>
                                                            <td><a href="<?php echo base_url('Demo/demo_master/demo_details_edit?demoid=' . $showactiveprojects['demoid'][$i]) ?>"><button type="submit" class="btn btn-sm  btn-warning "><i class="icon-pencil"></i></button></a></td>
                                                            <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('Demo/demo_master/deletedesc/' . $showactiveprojects['demoid'][$i]) ?>"><button type="submit" class="btn btn-sm btn-danger" onClick="return confirmPost()"><span class="icon-trash"></span></button></a></td>
                                                        </tr>
                                                <?php  }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.col -->
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#dynamic-table').DataTable();

    });
</script>