<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Fin_admin'); ?>">
                            Finance Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Purchase Order List
            </h4>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-xl-12 col-md-12">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>PO ID</th>
                            <th>Description</th>
                            <th>Client</th>
                            <th>Sales</th>
                            <th>PO Ref</th>
                            <th>PO Value</th>
                            <th>UCN</th>
                            <th>PO Status</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        
                        foreach ($purchase_orders as $data) {
                            $j = $j + 1 ?>
                            <tr>
                            <td><?php echo  $j ?></td>
                                <td><?php echo $data['po_id'] ?></td>
                                <td><?php echo $data['description'] ?></td>
                                <td><?php echo $data['client_name'] ?></td>
                                <td><?php echo $data['acmanager'] ?> </td>
                                <td><?php echo $data['po_number'] ?></td>
                                <td align="right"><?php if ($data['po_value'] > 0) {
                                                                        echo '$ ';
                                                                        echo number_format($data['po_value']);
                                                                    } ?></td>
                                <td><?php echo $data['ucn'] ?></td>
                                <td><?php
                                    $postatus = $data['po_status'];
                                    echo $postatus;
                                    ?></td>
                                <td><?php
                                    $status = $data['status'];
                                    switch ($status) {
                                        case 1:
                                            echo 'Editing';
                                            break;
                                        case 6:
                                            echo 'Locked';
                                            break;
                                    }
                                    ?></td>
                                <td>
                                    <form action="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="po_id" value="<?php echo $data['po_id']; ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                            <span class="mdi mdi-square-edit-outline"></span></button>
                                    </form>
                                </td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col-->
</div>