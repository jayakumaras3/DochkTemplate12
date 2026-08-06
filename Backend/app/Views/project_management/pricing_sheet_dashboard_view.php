<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">My Effort Sheets</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <form action="<?php echo base_url("Project_Manage/PM_pricing_sheet/add_pricing_sheet") ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="cid" value="MQ==">
                    <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-plus"></i> Create New Effort Sheet</button>
                </form>
                <div class="table-responsive">
                    <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Client</th>
                                <th>Account</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Effort</th>
                                <th>PO</th>
                                <!-- <th>Export</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($pricing_sheet_list as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $data['ppid']; ?></td>
                                    <td><?php echo $data['proposal_name']; ?></td>
                                    <td><?php echo $data['client_name']; ?></td>
                                    <td><?php echo $data['fullname']; ?></td>
                                    <td><?php echo $data['requested_on']; ?></td>

                                    <td>
                                        <?php $status = $data['status'];
                                        $userlevel = session()->get('userlevel');

                                        $arrayuserlevel = explode(',', $userlevel);
                                        if (in_array('6', $arrayuserlevel) && $data['status'] == 6) {
                                        ?>
                                            <form class="updateLockedStatusForm" id="updateLockedStatus<?php echo $data['ppid']; ?>">
                                                <input type="hidden" name="status" value="3" />
                                                <input type="hidden" name="ppid" value="<?php echo $data['ppid']; ?>">
                                                <button class="btn btn-outline-primary waves-effect btn-xs waves-light" title="Unlock">
                                                    <span class="mdi mdi-lock-open-minus"></span>
                                                </button>
                                            </form>

                                        <?php
                                        } else {
                                            switch ($status) {
                                                case 1:
                                                    echo 'New';
                                                    break;
                                                case 2:
                                                    echo 'Editing';
                                                    break;
                                                case 3:
                                                    echo 'Editing';
                                                    break;
                                                case 4:
                                                    echo 'Mng Reviewed';
                                                    break;
                                                case 6:
                                                    echo 'Locked';
                                                    break;
                                                case 7:
                                                    echo 'Linked';
                                                    break;
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>

                                        <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/edit_pricing_sheet') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ppid" value="<?php echo $data['ppid']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <?php if ($status < 5) { ?>
                                                    <span class="mdi mdi-square-edit-outline"></span>
                                                <?php } else { ?>
                                                    <span class="mdi mdi-eye-outline"></span>
                                                <?php } ?>
                                            </button>
                                        </form>


                                    </td>
                                    <td>
                                        <?php if ($status < 5) { ?>
                                        <?php } else { ?>
                                            <form action="<?php echo base_url("Project_Manage/PM_purchase_order/add_purchase_order") ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="cid" value="MQ==">
                                                <input type="hidden" name="ppid" value="<?php echo $data['ppid']; ?>">
                                                <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light mb-3">PO</button>
                                            </form>
                                        <?php } ?>

                                    </td>
                                    <!-- <td>
                                        <?php if ($status == 6) { ?>
                                            <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/export_pricing_sheet') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="ppid" value="<?php echo $data['ppid']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-file-pdf-box"></span></button>
                                            </form>
                                        <?php } ?>
                                    </td> -->
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
</div>
<script>
    $(document).on('submit', '.updateLockedStatusForm', function(event) {
        event.preventDefault();

        var form = $(this);
        var dataString = new FormData(this);

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('Project_Manage/PM_pricing_sheet/updatelockstatus') ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj);

                    if (obj.status === 'OK') {
                        location.reload();
                    } else {
                        alert('Something Went Wrong! Please contact Site Admin!');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            });
        } else {
            alert("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
        }
    });
</script>