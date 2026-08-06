<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url($sub_header_1_link); ?>">
                            Document View
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                <?php echo $sub_header_2; ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/addpage') ?>" method="POST"><?= csrf_field() ?>
                    <div class="form-group col-md-12 mb-2">
                        <label>Page Name</label>
                        <input type="text" required class="form-control col-md-12" name="page_name" />
                    </div>
                    <div class="form-group col-md-12 mb-2">
                        <label>Page Number</label>
                        <input type="number" required class="form-control col-md-12" name="page_number" />
                    </div>
                    <div class="form-group  col-md-12">
                        <input type="hidden" name="emd_id" value="<?php echo $emd_id ?>">
                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
                            Create New Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $userlevel = session()->get('userlevel');
    $array  = array_map('intval', str_split($userlevel)); ?>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <table id="searchdatatable" class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width=5%>#</th>
                            <th>Page Name</th>
                            <th>Content</th>
                            <th>Edit</th>
                    </thead>
                    <tbody>

                        <?php
                        $j = 0;
                        foreach ($getAssignpages as $eachPageDetails) {
                            $j = $j + 1;
                        ?>
                            <tr>
                                <td><?php echo $eachPageDetails['page_number'] ?></td>
                                <td><?php echo $eachPageDetails['page_name'] ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($settings_link) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="emd_id" value="<?php echo $emd_id ?>">
                                        <input type="hidden" name="empg_id" value="<?php echo $eachPageDetails['empg_id'] ?>">
                                        <input type="hidden" name="page_name" value="<?php echo $eachPageDetails['page_name'] ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-book-open-page-variant-outline"></span></button>
                                    </form>
                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="empg_id" value="<?php echo $eachPageDetails['empg_id'] ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>