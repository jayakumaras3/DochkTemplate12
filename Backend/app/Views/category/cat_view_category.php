<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('category/dashboard') ?>">Meta Category List</a></li>
                </ol>
            </div>
            <h4 class="page-title">Category : <?php echo $mc_name; ?></h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <!-- start chat users-->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('category/dashboard/add_category_to_meta') ?>" method="POST"><?= csrf_field() ?>
                     <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Meta Category</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="category" value="">
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Add Category to Meta Category
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
                                <th>Category</th>
                                <th>Courses</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            $totalcourser = 0;
                            foreach ($get_category as $cat) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $cat['description']; ?> </td>
                                    <td>
                                        <form action="<?php echo base_url('category/dashboard/view_courses') ?>" method="POST"><?= csrf_field() ?>
                                             <?= csrf_field() ?>
                                            <input type="hidden" name="cat_id" value="<?php echo $cat['sc_mcid']; ?>">
                                            <input type="hidden" name="cat_name" value="<?php echo $cat['description']; ?>">
                                            <button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light">
                                                <?php echo $cat['total_courses']; $totalcourser = $totalcourser+$cat['total_courses']; ?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('category/dashboard/delete_category') ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                             <?= csrf_field() ?>
                                            <input type="hidden" name="cat_id" value="<?php echo $cat['sc_mcid']; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                                <span class="icon-trash"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="2"><strong>Total Courses</strong></td>
                                <td><strong><?php echo $totalcourser; ?></strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
</div>