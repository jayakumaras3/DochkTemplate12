<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if (isset($return_url) && $return_url == 2) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/MileStones/milestones_summary'); ?>">Milestone Summary</a></li>
                    <?php }
                    if (isset($return_url) && $return_url == 3) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Invoices'); ?>">Invoices</a></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn'); ?>">Edit
                                UCN</a></li>
                    <?php } ?>
                </ol>
            </div>
            <h4 class="page-title">Edit Purchase Order</h4>
        </div>
    </div>
</div>
<?php

$userlevel = session()->get('userlevel');
if (empty($userlevel)) {
    header('Location:' . base_url());
    exit();
}
$arrayuserlevel = explode(',', $userlevel);

?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?php if ($po_details[0]['status'] == 1) { ?>
                    <form class="form-horizontal"
                        action="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order_submit') ?>"
                        method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <input required type="text" class="form-control col-md-12" name="pricing_name"
                                        placeholder="Short Name" value="<?php echo $po_details[0]['description']; ?>" />
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">PO Value <span
                                            class="text-danger">*</span></label>
                                    <input required type="number" class="form-control col-md-12" name="po_value"
                                        placeholder="PO Value" value="<?php echo $po_details[0]['po_value']; ?>" />
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">PO Number <span
                                            class="text-danger">*</span></label>
                                    <input required type="text" class="form-control col-md-12" name="po_number"
                                        placeholder="PO Number" value="<?php echo $po_details[0]['po_number']; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="inputEmail3" class="col-form-label">Remarks</label>
                                    <textarea class="form-control"
                                        name="remarks"><?php echo $po_details[0]['remarks']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Project Value <span
                                            class="text-danger">*</span></label>
                                    <input required type="number" class="form-control col-md-12" name="project_value"
                                        placeholder="Project Value"
                                        value="<?php echo $po_details[0]['project_value']; ?>" />
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">PO Status <span
                                            class="text-danger">*</span></label>
                                    <select name="po_status" class="form-control">
                                        <option value="Received" <?php echo ($po_details[0]['po_status'] == "Received") ? 'SELECTED' : '' ?>>Received</option>
                                        <option value="In Progress" <?php echo ($po_details[0]['po_status'] == "In Progress") ? 'SELECTED' : '' ?>>In Progress</option>
                                        <option value="Email Confirmation" <?php echo ($po_details[0]['po_status'] == "Email Confirmation") ? 'SELECTED' : '' ?>>Email Confirmation</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-1">
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label class="form-label">Lock Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option value="1" SELECTED>Editing</option>
                                        <option value="6">Locked</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3  mt-2">
                                <div class="text-sm-end  mt-sm-0">
                                    <input type="hidden" name="currency" value="1">
                                    <input type="hidden" name="client" value="<?php echo $po_details[0]['client_id']; ?>">
                                    <input type="hidden" name="account_manager"
                                        value="<?php echo $po_details[0]['account_manager']; ?>">
                                    <input type="hidden" name="return_url" value="<?php echo isset($return_url) ? $return_url : 1; ?>">
                                    <input type="hidden" name="po_id" value="<?php echo $po_details[0]['po_id']; ?>">
                                    <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Name</td>
                                <td><?php echo $po_details[0]['description']; ?></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>PO Value</td>
                                <td> $ <?php echo $po_details[0]['po_value']; ?></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>PO Number</td>
                                <td><?php echo $po_details[0]['po_number']; ?></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Project Value</td>
                                <td> $ <?php echo $po_details[0]['project_value']; ?></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>PO Status</td>
                                <td><?php echo $po_details[0]['po_status']; ?></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Remarks</td>
                                <td><?php echo $po_details[0]['remarks']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php

                    if (in_array('6', $arrayuserlevel)) {

                    ?>
                        <form class="form-horizontal"
                            action="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order_submit') ?>"
                            method="POST"><?= csrf_field() ?>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="mb-1">
                                        <label for="projectname" class="form-label">PO Status <span
                                                class="text-danger">*</span></label>
                                        <select name="po_status" class="form-control">
                                            <option value="<?php echo $po_details[0]['po_status']; ?>">
                                                <?php echo $po_details[0]['po_status']; ?>
                                            </option>
                                            <option value="Received" SELECTED>Received</option>
                                            <option value="In Progress">In Progress</option>
                                            <option value="Email Confirmation">Email Confirmation</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-1">
                                        <label class="form-label">Lock Status<span class="text-danger">*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="1">Editing</option>
                                            <option value="6" SELECTED>Locked</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3  mt-2">
                                    <div class="text-sm-end  mt-sm-0">
                                        <input type="hidden" name="pricing_name"
                                            value="<?php echo $po_details[0]['description']; ?>">
                                        <input type="hidden" name="client" value="<?php echo $po_details[0]['client_id']; ?>">
                                        <input type="hidden" name="account_manager"
                                            value="<?php echo $po_details[0]['account_manager']; ?>">
                                        <input type="hidden" name="po_value" value="<?php echo $po_details[0]['po_value']; ?>">
                                        <input type="hidden" name="po_number"
                                            value="<?php echo $po_details[0]['po_number']; ?>">
                                        <input type="hidden" name="remarks" value="<?php echo $po_details[0]['remarks']; ?>">
                                        <input type="hidden" name="project_value"
                                            value="<?php echo $po_details[0]['project_value']; ?>">
                                        <input type="hidden" name="currency" value="1">
                                        <input type="hidden" name="return_url" value="<?php echo isset($return_url) ? $return_url : 1; ?>">

                                        <input type="hidden" name="po_id" value="<?php echo $po_details[0]['po_id']; ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">


        <div class="card">
            <div class="card-body">
                <h5>Supporting Documents</h5>
                <?php
                $baseloc = '';
                $base = base_url();
                if ($base == 'http://localhost/Dochek_V3/Dochek_V3') {
                    $baseloc = '/Users/pchandran/Sites/dochek_v3/Dochek_V3/';
                }
                if ($base == 'http://localhost/projects_dochek/') {
                    $baseloc = 'D:/wampp/www/projects_dochek/';
                }
                if ($base == 'https://dochek.com/') {
                    $baseloc = '/var/www/html/';
                }
                if ($base == 'http://localhost/DOCHEKDOTCOM') {
                    $baseloc = 'D:/wampp/www/DOCHEKDOTCOM/';
                }
                if ($base == 'https://staging.dochek.com/') {
                    $baseloc = '/var/www/html/DOCHEK/';
                }
                if ($base == 'http://localhost/DOCHEK/') {
                    $baseloc = '/var/www/DOCHEK/';
                }
                if ($base == 'http://172.16.2.218/DOCHEK/') {
                    $baseloc = '/var/www/DOCHEK/';
                }
                $folderloc = $baseloc . 'assets/assets/uploads/po_pdf/' . $po_id;

                ?>
                <form action="<?php echo base_url('Project_Manage/PM_purchase_order/po_upload') ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="po_id" value="<?php echo $po_details[0]['po_id']; ?>">
                    <input type="hidden" name="client" value="<?php echo $po_details[0]['client_id']; ?>">
                    <input type="hidden" name="return_url" value="<?php echo isset($return_url) ? $return_url : 1; ?>">

                    <button type="submit"
                        class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light  ">
                        Upload PO Documents
                    </button>
                </form><br />


                <h6>View PDF</h6>
                <?php
                $baseloc = '';
                $base = base_url();
                if ($base == 'http://localhost/Dochek_V3/Dochek_V3') {
                    $baseloc = '/Users/pchandran/Sites/dochek_v3/Dochek_V3/';
                }
                if ($base == 'http://localhost/projects_dochek/') {
                    $baseloc = 'D:/wampp/www/projects_dochek/';
                }
                if ($base == 'https://dochek.com/') {
                    $baseloc = '/var/www/html/';
                }
                if ($base == 'http://localhost/DOCHEKDOTCOM') {
                    $baseloc = 'D:/wampp/www/DOCHEKDOTCOM/';
                }
                if ($base == 'https://staging.dochek.com/') {
                    $baseloc = '/var/www/html/DOCHEK/';
                }
                if ($base == 'http://localhost/DOCHEK/') {
                    $baseloc = 'D:/wampp/www/DOCHEK/';
                }
                if ($base == 'http://172.16.2.218/DOCHEK/') {
                    $baseloc = '/var/www/DOCHEK/';
                }
                $folderloc = $baseloc . 'assets/assets/uploads/po_pdf/' . $po_id;
                //  print_r($fileloc);
                if (is_dir($folderloc)) {
                    $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                    $sno = 0;
                    if (!empty($po_upload_details)) {
                        $filepath = FCPATH . 'assets/assets/uploads/po_pdf/' . $po_id . '/' . $po_upload_details[0]['po_upload'];
                        // print_r($filepath);
                        if (file_exists($filepath)) {

                            echo '<table class="table  table-sm">';
                            echo '<tr><th>File</th><th>On</th></tr>';

                            foreach ($po_upload_details as $filerow) {
                                // print_r($filerow);
                                $eachfilepath = base_url() . 'assets/assets/uploads/po_pdf/' . $po_id . '/' . $filerow['po_upload'];

                                echo '<tr><td>';
                                echo '<a href= "' . $eachfilepath . '" target="_blank">' . $filerow['po_upload'] . '<a>';

                                echo '</td><td>';
                                $file_creation_date = filemtime($filepath);
                                echo date('Y-m-d H:i:s', $file_creation_date);
                                echo '</td></tr>';
                            }
                        }
                    } else {
                        echo 'No Files';
                    }
                    echo '</table>';
                } else {
                    echo 'No Files';
                } ?>

            </div>
        </div>
    </div>
</div>
<?php if ($po_details[0]['status'] == '6') { ?>
    <!-- <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo base_url('Project_Manage/PM_purchase_order/po_ucn') ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="po_id" value="<?php echo $po_details[0]['po_id']; ?>">
                        <input type="hidden" name="pricing_name" value="<?php echo $po_details[0]['description']; ?>">
                        <input type="hidden" name="projectclient" value="<?php echo $po_details[0]['client_id']; ?>">
                        <input type="hidden" name="account_manager" value="<?php echo $po_details[0]['account_manager']; ?>">
                        <button type="submit" class="btn btn-outline-danger btn-xs rounded-pill waves-effect waves-light  ">
                            Add / Link UCN
                        </button>
                    </form>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>Remarks</th>
                                    <th>Edit</th>
                                    <th>Lock</th> 
                                    <th>Projects</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $j = 0;
                                $total_po_value = 0;

                                if (count($ucn_details) > 0) {

                                    foreach ($ucn_details as $data) {
                                ?>
                                        <tr>
                                            <td><?php echo $data['ucn_id']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            <td><?php echo $data['start_dt']; ?></td>
                                            <td><?php echo $data['remarks']; ?></td>
                                            <?php $status = $data['status'];
                                            // if ($status == 1) { 
                                            ?>
                                                <td>
                                                    <form action="<?php echo base_url('Project_Manage/PM_purchase_order/edit_ucn') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="ucn_id" value="<?php echo $data['ucn_id']; ?>">
                                                        <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                            <span class="mdi mdi-square-edit-outline"></span></button>
                                                    </form>
                                                </td>
                                                <!-- <td>
                                                    <form action="<?php echo base_url('Project_Manage/PM_purchase_order/upload_docs_ucn') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="ucn_id" value="<?php echo $data['ucn_id']; ?>">
                                                        <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                            <span class="mdi mdi-file-upload-outline"></span></button>
                                                    </form>
                                                </td> -->
    <!-- <td>

                                                    <form action="<?php echo base_url('Project_Manage/PM_purchase_order/ucn_status_change') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="ucn_id" value="<?php echo $data['ucn_id']; ?>">
                                                        <input type="hidden" name="status" value="6">
                                                        <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                            <span class="mdi mdi-lock-open-plus-outline"></span></button>
                                                    </form>

                                                </td> -->
    <?php
                                        // } else {
                                        //     echo '<td>Locked</td><td>';
                                        //     if (in_array('6', $arrayuserlevel)) {
                                        //     
    ?>
    <!-- //         <form action="<?php echo base_url('Project_Manage/PM_purchase_order/ucn_status_change') ?>" method="POST"><?= csrf_field() ?>
                                            //             <input type="hidden" name="ucn_id" value="<?php echo $data['ucn_id']; ?>">
                                            //             <input type="hidden" name="status" value="1">
                                            //             <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                                            //             <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                            //                 <span class="mdi mdi-lock-remove"></span></button>
                                            //         </form> -->
    <?php
                                        //     }
                                        //     echo '</td>';
                                        // }
    ?>
    <td>
        <!--  <form action="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="ucn_id" value="<?php echo $data['ucn_id']; ?>">
                                                    <input type="hidden" name="client" value="<?php echo $data['client']; ?>">
                                                    <button type="submit" class="btn btn-outline-dark btn-xs waves-effect waves-light">
                                                        Project</button>
                                                </form>
 -->

    </td>
    </tr>
<?php
                                    }
                                }
?>
<!--</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
<?php } ?>

<?php if ($ucn_details) { ?>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal"
                        action="<?php echo base_url('Project_Manage/PM_purchase_order/add_milestones') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">UCN <span
                                            class="text-danger">*</span></label>
                                    <select name="ucn_id" class="form-control">
                                        <?php
                                        foreach ($ucn_details as $data) { ?>
                                            <option value="<?php echo $data['ucn_id'] ?>"><?php echo $data['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Billing Milestone <span
                                            class="text-danger">*</span></label>
                                    <input required type="text" class="form-control col-md-12" name="description"
                                        placeholder="Milestone Name" value="" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Currency <span
                                            class="text-danger">*</span></label>
                                    <select name="currency" class="form-control">
                                        <option value="1" SELECTED>US Dollars</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Invoice Value <span
                                            class="text-danger">*</span></label>
                                    <input required type="number" class="form-control col-md-12" name="milestone_value"
                                        placeholder="Invoice Value" value="" />
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Invoice Date <span
                                            class="text-danger">*</span></label>
                                    <input data-lpignore="true" class="form-control" id="due_date" name="invoice_date" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-1">
                                    <label for="inputEmail3" class="col-form-label">Notes</label>
                                    <div>
                                        <textarea name="notes" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                                <input type="hidden" name="return_url" value="<?php echo isset($return_url) ? $return_url : 1; ?>">

                                <div class="text-sm-end  mt-sm-0">
                                    <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                        Add New Billing Milestone
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Billing Milestone</th>
                                    <th>Value</th>
                                    <th>Percent</th>
                                    <th>Invoice Date</th>
                                    <th>Notes</th>
                                    <th>Edit</th>
                                    <th>Del</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $total_po_value = 0;

                                if (count($milestone_details) > 0) {
                                    $approved_po_value = $milestone_details[0]['po_val'];
                                    echo '<tr><td>Approved PO Value</td><td align="right">$ ' . $approved_po_value . '<td colspan=5></td>';
                                    foreach ($milestone_details as $data) {
                                        $current_po_value = $data['value'];
                                        $total_po_value = $total_po_value + $current_po_value;
                                        if ($approved_po_value != 0 && $current_po_value != 0) {
                                            $percentage = round($current_po_value / $approved_po_value * 100);
                                        } else {
                                            $percentage = 0;
                                        }

                                ?>
                                        <tr>

                                            <td><?php echo $data['description']; ?></td>
                                            <td align="right"><?php echo '$ ' . $data['value']; ?></td>
                                            <td><?php echo $percentage . ' %'; ?></td>
                                            <td><?php echo $data['invoicing_dt']; ?></td>
                                            <td><?php echo $data['notes'] ?></td>
                                            <td>
                                                <form
                                                    action="<?php echo base_url('Project_Manage/PM_purchase_order/edit_milestone') ?>"
                                                    method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="milestone_id"
                                                        value="<?php echo $data['milestone_id']; ?>">
                                                    <input type="hidden" name="return_url" value="<?php echo isset($return_url) ? $return_url : 1; ?>">
                                                    <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                                                    <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light">
                                                        <span class="mdi mdi-pencil-outline"></span></button>
                                                </form>
                                            </td>
                                            <td>
                                                <form
                                                    action="<?php echo base_url('Project_Manage/PM_purchase_order/del_milestone') ?>"
                                                    method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="milestone_id"
                                                        value="<?php echo $data['milestone_id']; ?>">
                                                    <input type="hidden" name="return_url" value="<?php echo isset($return_url) ? $return_url : 1; ?>">
                                                    <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                                                    <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light">
                                                        <span class="mdi mdi-trash-can-outline"></span></button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                    if ($approved_po_value != 0 && $total_po_value != 0) {
                                        $totalper = round($total_po_value / $approved_po_value * 100);
                                    } else {
                                        $totalper = 0;
                                    }
                                    echo '<tr><td>Total</td><td align="right">$ ' . $total_po_value . '<td>' . $totalper . ' %</td><td colspan=4></td>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/add_user_to_pricing_sheet') ?>"
                    method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Select User <span
                                        class="text-danger">*</span></label>
                                <select name="assignuser" class="form-control" required>
                                    <option value="">Select User</option>
                                    <?php
                                    foreach ($project_manager as $data) {
                                        echo '<option value="' . $data['id_user'] . '">' . $data['fname'] . ' ' . $data['lname'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 mt-3">
                            <input type="hidden" name="ppid" value="<?php echo $po_id; ?>">
                            <input type="hidden" name="returnid" value="4">
                            <input type="hidden" name="role" value="1">
                            <input type="hidden" name="type_of_assignment" value="4">
                            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light">
                                Assign Users to Purchase Order</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($access as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['fname'] . ' ' . $data['lname']; ?></td>
                                    <td>
                                        <?php if ($j > 1) { ?>
                                            <form
                                                action="<?php echo base_url('Project_Manage/PM_pricing_sheet/delete_userassignment') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="returnid" value="4">
                                                <input type="hidden" name="project_assign_id"
                                                    value="<?php echo $data['project_assign_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
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