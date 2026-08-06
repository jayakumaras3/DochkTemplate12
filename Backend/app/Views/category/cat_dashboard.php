<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Meta Category List</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <!-- start chat users-->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('category/dashboard/add_new_meta_category') ?>" method="POST"><?= csrf_field() ?>
                     <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Meta Category</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="meta_category" value="">
                        </div>
                    </div>
                    <div class="justify-content-end row">

                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Create New Meta Category
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="x_panel">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Meta Category</th>
                                <th>Category</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($get_meta as $meta) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $meta['description']; ?> </td>
                                    <td>
                                        <form action="<?php echo base_url('category/dashboard/view_category') ?>" method="POST" >
                                             <?= csrf_field() ?>
                                            <input type="hidden" name="mc_id" value="<?php echo $meta['sc_mcid']; ?>">
                                            <input type="hidden" name="mc_name" value="<?php echo $meta['description']; ?>">
                                            <button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light">
                                                <?php echo $meta['total_categories']; ?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('category/dashboard/delete_meta_category') ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this meta category?');">
                                             <?= csrf_field() ?>
                                            <input type="hidden" name="mc_id" value="<?php echo $meta['sc_mcid']; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                                <span class="icon-trash"></span></button>
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
</div>