<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Dropdown Manager</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="tab-pane active" id="tab14">
                    <h4><?php echo lang('Buttons.AddNewCategory') ?></h4>
                    <form class="form" action="<?php echo base_url(); ?>/dropdown/category" method="POST"><?= csrf_field() ?>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Category'); ?></label>
                            <div class="col-8 col-xl-9">
                                <input type="text" class="form-control" name="catogery_name" placeholder="<?php echo lang('UI_Text.Name') ?>" value="<?= set_value('catogery_name') ?>" />
                            </div>
                        </div>
                        <?php if (isset($validationData)) : ?>
                            <div class=col-12 col-sm-4>
                                <div class="alert alert-danger" role="alert">
                                    <?= $validationData->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="justify-content-end row">
                            <div class="col-8 col-xl-9">
                                <button type="submit" class="btn btn-sm btn-info block">
                                    <?php echo lang('Buttons.Add') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4><?php echo lang('UI_Text.CategoryItem') ?></h4>
                <form class="form" action="<?php echo base_url(); ?>/dropdown/categoryItem" method="POST"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.select') . ' ' . lang('UI_Text.Category') ?></label>
                        <div class="col-8 col-xl-9">
                            <select name="category" class="form-control">
                                <?php foreach ($categoryData as $eachcategoryData) { ?>
                                    <option value="<?php echo $eachcategoryData['id_dc'] ?>"><?php echo $eachcategoryData['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Name') ?></label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="name" placeholder="<?php echo lang('UI_Text.Name') ?>" value="<?= set_value('name') ?>" />
                        </div>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class="col-12 col-sm-4">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" id="sub" class="btn btn-info block">
                                <i class="ace-icon fa fa-key bigger-110"></i> <?php echo lang('Buttons.Add') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h2><?php echo lang('UI_Text.view') ?></h2>
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th><?php echo lang('UI_Text.Category') ?></th>
                            <th><?php echo lang('UI_Text.Category') . ' ' . lang('UI_Text.Item') ?></th>
                            <th><?php echo lang('Buttons.Create') . ' ' . lang('UI_Text.by') ?></th>
                            <th><?php echo lang('Buttons.Create') . ' ' . lang('UI_Text.on') ?></th>
                            <th><?php echo lang('UI_Text.Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($table as $eachcategoryData) { ?>
                            <tr>
                                <td><?php echo $eachcategoryData['category_name'] ?></td>
                                <td><?php echo $eachcategoryData['Category_item'] ?></td>
                                <td><?php echo session()->get('username') ?></td>
                                <td><?php echo date("m-d-Y", $eachcategoryData['createdon']); ?></td>
                                <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('dropdown/deleteCategory/' . $eachcategoryData['id_d'])  ?>"><button class="btn btn-sm btn-danger"><span class="icon-trash"></span></button></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>