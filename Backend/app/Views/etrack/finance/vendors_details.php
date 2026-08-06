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
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Fin_admin/vendors'); ?>">
                            Vendors
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Vendor Details
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/update_vendor_details'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Vendor Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="vendor_short_name" class="form-control" required value="<?php echo $vendor_details[0]['vendor_short_name']; ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Company Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="full_comp_name" class="form-control" required value="<?php echo $vendor_details[0]['full_comp_name']; ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Address</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="address_01" class="form-control" value="<?php echo $vendor_details[0]['address_01']; ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-8">
                            <input type="text" name="address_02" class="form-control" value="<?php echo $vendor_details[0]['address_02']; ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-8">
                            <input type="text" name="address_03" class="form-control" value="<?php echo $vendor_details[0]['address_03']; ?>">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Primary Contact Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="contact_name_01" class="form-control" value="<?php echo $vendor_details[0]['contact_name_01']; ?>">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Primary Contact Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="email_01" class="form-control" value="<?php echo $vendor_details[0]['email_01']; ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Primary Contact Phone</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="phone_01" class="form-control" value="<?php echo $vendor_details[0]['phone_01']; ?>">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Secondary Contact Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="contact_name_02" class="form-control" value="<?php echo $vendor_details[0]['contact_name_02']; ?>">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Secondary Contact Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="email_02" class="form-control" value="<?php echo $vendor_details[0]['email_02']; ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Secondary Contact Phone</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="phone_02" class="form-control" value="<?php echo $vendor_details[0]['phone_02']; ?>">
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">GST</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="GST" class="form-control" value="<?php echo $vendor_details[0]['GST']; ?>">
                        </div>
                    </div>

                    <div class="justify-content-end row mt-3">
                        <div class="col-12 col-xl-12">
                            <input type="hidden" name="vendor_id" value="<?php echo $vendor_details[0]['vendor_id']; ?>">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Update Vendor Details
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-md-8">
        <div class="card">
            <div class="card-body">
                <table id="searchdatatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Description</th>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($vendor_payments as $data) {
                            $j++;
                            echo '<tr><td>';
                            echo $j;
                            echo '</td><td>';
                            echo $data['description'];
                            echo '</td><td>';
                            echo $data['payment_dt'];
                            echo '</td><td>';
                            $currency = $data['currency_val'];
                            switch ($currency) {
                                case 1:
                                    echo 'USD ';
                                    break;
                                case 2:
                                    echo 'EURO ';
                                    break;
                                case 3:
                                    echo 'INR ';
                                    break;
                            }
                            echo $data['payment_amount'];
                            echo '</td><td>';

                        ?>
                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/view_vendor_details'); ?>" method="POST"><?= csrf_field() ?>
                                <td>
                                    <input type="hidden" name="ven_pay_id" value="<?php echo $data['ven_pay_id']; ?>">
                                    <button class="btn btn-info btn-xs">View</button>
                                </td>
                            </form>
                        <?php
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col-->
</div>