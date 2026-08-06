<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('demo/Demo_dashboard'); ?>">Demo Dashboard</a></li>
           
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header; ?></h4> 
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>E-Mail</th>
                            <th>Notes</th>
                            <th>Link</th>
                            <th>Password</th>
                            <th>Expiry</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($reportData  as $data) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['user_email']; ?></td>
                                <td><?php echo $data['notes']; ?></td>
                                <td><a href="<?php $linknum = 5534 * $data['id'];
                                                echo base_url('quickaccess/demo/' . $linknum); ?>" target="_blank">Link</a></td>
                                <td><?php echo $data['secret_code']; ?></td>
                                <td><?php echo $data['expiry_date']; ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Demo/cart/editCart') ?>" method="POST"><?= csrf_field() ?>
                                         <?= csrf_field() ?>
                                        <input type="hidden" name="cartAssignid" value="<?php echo $data['id']; ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Demo/cart/delcart') ?>" method="POST"><?= csrf_field() ?>
                                         <?= csrf_field() ?>
                                        <input type="hidden" name="cartAssignid" value="<?php echo $data['id']; ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
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