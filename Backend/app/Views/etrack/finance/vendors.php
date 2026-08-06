<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Vendors
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/add_new_vendor'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Vendor Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="vendor_short_name" class="form-control" required value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Company Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="full_comp_name" class="form-control" required value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Address</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="address_01" class="form-control"  value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-8">
                            <input type="text" name="address_02" class="form-control"  value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-8">
                            <input type="text" name="address_03" class="form-control"  value="">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Primary Contact Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="contact_name_01" class="form-control"  value="">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Primary Contact Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="email_01" class="form-control"  value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">Primary Contact Phone</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="phone_01" class="form-control"  value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-form-label">GST</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="GST" class="form-control"  value="">
                        </div>
                    </div>
                    <div class="justify-content-end row mt-3">
                        <div class="col-12 col-xl-12">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Add New Vendor
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
                            <th>Vendor</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($all_vendors as $data) {
                            $j++;
                            echo '<tr><td>';
                            echo $j;
                            echo '</td><td>';
                            echo $data['vendor_short_name'];
                            echo '</td><td>';
                            echo $data['contact_name_01'];
                            echo '</td><td>';
                            echo $data['email_01'];
                            echo '</td><td>';
                            echo $data['phone_01'];
                            echo '</td>';

                        ?>
                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/view_vendor_details'); ?>" method="POST"><?= csrf_field() ?>
                                <td>
                                    <input type="hidden" name="vendor_id" value="<?php echo $data['vendor_id']; ?>">
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