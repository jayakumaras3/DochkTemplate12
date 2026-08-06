<div class="page-title">
    <div class="title_left">
        <h3><?php echo $header; ?></h3>
    </div>
    <div class="title_right">
        <div class="col-md-5 col-sm-5   form-group pull-right">
            <a href="<?php echo base_url($form_link1) ?>">
                <button type="submit" class="btn btn-info btn-sm form-control">
                    <i class="ace-icon fa fa-key bigger-110"></i>+ Create Pricing
                </button>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="x_panel">
        <table id="dynamic-table" class="table  table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width=5%>#</th>
                    <th>Proposal Description</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Requested By</th>
                    <th>Updated By</th>
                    <th>Status</th>
                    <th>Edit</th>
            </thead>
            <tbody>
                <?php
                $j = 0;
                foreach ($pricing as $pricingdetails) {
                    $j = $j + 1;
                ?>
                    <tr>
                        <td width=5%><?php echo $j ?></td>
                        <td><?php echo $pricingdetails['proposal_name'] ?></td>
                        <td><?php echo $pricingdetails['clientname'] ?></td>
                        <td><?php echo $pricingdetails['baselinetype']; ?></td>
                        <td><?php echo $pricingdetails['requester'] ?></td>
                        <td><?php echo $pricingdetails['user'] ?></td>
                        <td><?php $status = $pricingdetails['status'];
                            if ($status == 1) echo 'Editing';
                            if ($status == 2) echo 'Ready';
                            if ($status == 3) echo 'Sales';
                            if ($status == 4) echo 'Rejected';
                            if ($status == 5) echo 'Approved';
                            ?></td>

                        <td>
                        <form class="form-horizontal" action="<?php echo base_url($edit_effort) ?>" method="POST">
                                <input type="hidden" name="ppid" value="<?php echo $pricingdetails['ppid'] ?>">
                                <button type="submit" class="btn btn-sm widget-icon btn-warning"><span class="fa fa-pencil"></span></button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#dynamic-table').DataTable();
    });
</script>