<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Ojts_consolidated/ojts_download_pdf'); ?>">OJTS Dashboard</a></li>
                </ol>
            </div>
            <h4 class="page-title">OJT Bundles</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Others/Ojts_consolidated/add_group_name') ?>" method="post" autocomplete="off" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <input type="text" name="group_name" class="form-control" placeholder="Bundle Name" required maxlength="100" />
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light " id="submitButton">Create</button>
                        </div>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">

                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>Bundle Name</th>
                            <th width="20%">Edit</th>
                            <th width="10%">PDF</th>
                            <!-- <th width="10%">Edit</th> -->
                            <th>Excel</th>
                            <th>Delete</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($ojtsgroupdata as $group) {
                            $j = $j + 1; ?>
                            <tr>
                                <td width="10%"><?php echo $j ?></td>
                                <td><?php echo $group['group_name'] ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/addojts_togroup') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="oj_group_id" value="<?php echo $group['oj_group_id'] ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil"></span></button>
                                    </form>
                                </td>
                                <td width="10%">
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/ojts_group_pdf') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="oj_group_id" value="<?php echo $group['oj_group_id']  ?>">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-file-pdf-box"></span></button>
                                        </div>
                                    </form>
                                </td>
                                <!-- <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/group_edit') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="oj_group_id" value="<?php echo $group['oj_group_id'] ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td> -->
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/export_group_OJTS_excelformat') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="oj_group_id" value="<?php echo $group['oj_group_id'] ?>">
                                        <button type="submit" class="btn btn-outline-info waves-effect btn-xs waves-light"><span class="mdi mdi-file-excel-box"></span></button>
                                    </form>
                                </td>
                                <td width="10%">
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/group_delete') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="oj_group_id" value="<?php echo  $group['oj_group_id'] ?>">
                                        <input type="hidden" name="status" value="0">
                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
</div>
</div>

<script>
    function target_popup1(url) {

        url = url.trim()
        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://' + url, 'windowname4', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }

    function target_popup2(filename, demoid) {

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://purpleframetech.us/demos/upload/client/' + demoid + '/' + filename, 'windowname5', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }
    $(document).ready(function() {

        $('#dynamic-table').DataTable();

    });
</script>