<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                e-Manual Editor
            </h4>
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/addproduct') ?>" method="POST"><?= csrf_field() ?>
                    <div class="form-group col-md-12 mb-2">
                        <label>Name</label>
                        <input type="text" class="form-control col-md-12" name="product_name" placeholder="Name" />
                    </div>
                    <div class="form-group col-md-12 mb-2">
                        <label>Description</label>
                        <textarea class="form-control col-md-12" name="description"></textarea>
                    </div>
                    <div class="form-group  col-md-12 mt-2  ">
                        <input type="hidden" name="user" value="">
                        <input type="hidden" name="addprojects" value="1">
                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
                            Create New e-Manual
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
            <table id="searchdatatable" class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width=5%>#</th>
                            <th>e-Manuals</th>
                            <th># Documents</th> 
                            <th>Thumb</th>
                           
                            <th>Edit</th>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($productDetails as $eachProductDetails) {
                            // print_r($eachProductDetails);
                            $j = $j + 1;
                        ?>
                            <tr>
                                <td width=5%><?php echo $j ?></td>
                                <td><?php echo $eachProductDetails['product_name'] ?></td>

                                <!-- <td><?php echo ($eachProductDetails['thumbnail'] != '') ? 'Yes' : '' ?></td>
                        <td><?php echo ($eachProductDetails['video_upload'] != '') ? 'Yes' : '' ?></td> -->
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($settings_link) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="em_id" value="<?php echo $eachProductDetails['em_id'] ?>">
                                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light"><?php echo isset($eachProductDetails['documentcount']) ? $eachProductDetails['documentcount'] : 0 ?></button>
                                    </form>
                                </td>
                                <td><?php echo $eachProductDetails['thumbnail'] ?></td>
                                
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="em_id" value="<?php echo $eachProductDetails['em_id'] ?>">
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