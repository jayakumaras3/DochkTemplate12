<div class="inbox-rightbar">

    <div class="row">
        <div class="page-title mb-3">
            <div class="title_right">
                <div class="col-md-5 col-sm-5   form-group pull-right">
                    <a href="<?php echo base_url($form_link1) ?>">
                        <button type="submit" class="btn btn-info btn-sm form-control">
                            <i class="ace-icon fa fa-key bigger-110"></i>+ Create Baseline
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width=5%>#</th>
                            <th>Baseline</th>
                            <th>Dur. (min)</th>
                            <th>Effort</th>
                            <th>Updated By</th>
                            <th>Updated On</th>
                            <th>Edit</th>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($baseline as $baselinedetails) {
                            $j = $j + 1;
                        ?>
                            <tr>
                                <td width=5%><?php echo $j ?></td>
                                <td><?php echo $baselinedetails['description'] ?></td>
                                <td><?php echo $baselinedetails['duration'] ?></td>
                                <td><?php echo $baselinedetails['Total'] ?></td>
                                <td><?php echo $baselinedetails['user'] ?></td>
                                <td><?php echo date('m-d-Y', $baselinedetails['last_updated_on']) ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST">
                                        <input type="hidden" name="bid" value="<?php echo $baselinedetails['bid'] ?>">
                                        <button type="submit" class="btn btn-sm widget-icon btn-warning"><span class="fa fa-edit"></span></button>
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