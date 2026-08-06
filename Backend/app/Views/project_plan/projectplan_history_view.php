<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project/project_plan') ?>">Project Dashboard</a></li>
                 
                </ol>
            </div>
            <h4 class="page-title">History Item</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table  table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Content Type</th>
                            <th>Content</th>
                            <th>Created By</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($dealhistory as $eachdealhistory) {
                            $j = $j + 1 ?>
                            <tr>
                                <td><?php echo $j ?></td>
                                <td><?php echo $eachdealhistory['typeofvalue'] ?></td>
                                <td><?php echo $eachdealhistory['content'] ?></td>
                                <td><?php echo $eachdealhistory['add_by'] ?></td>
                                <td><?php echo date('Y-m-d', $eachdealhistory['add_dt']) ?></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /.row -->
</div><!-- /.row -->

<script LANGUAGE="JavaScript">
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,

        })
    });
</script>