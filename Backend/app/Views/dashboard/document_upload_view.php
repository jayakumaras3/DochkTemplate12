<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('Project/dashboard') ?>">Dashboard</a>
            </li><b>&nbsp;>&nbsp;</b>
            <li>
                <a href="<?php echo base_url('Project/dashboard_v1?projectid='.$projectid) ?>">Project Dashboard</a>
            </li><b>&nbsp;>&nbsp;</b>
            <li class="active">
                <?php echo $getmyassignment['0']['projectname'] ?> Document
            </li>
        </ol>
    </div>
    <div class="col-md-12">
        <div class="x_panel">
            <table class="table  table-sm table-striped">
                <thead>
                    <tr>
                        <th width=5%>#</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $j = 0;
                    foreach ($projectdocumentdata as $eachprojectdocumentdata) {
                        $j = $j + 1; ?>
                        <tr>
                            <td width=5%><?php echo $j ?></td>
                            <td><a href="<?php echo base_url() . '/assets/assets/uploads/project_document/' . $projectid . "/" . $eachprojectdocumentdata['filename'] ?>"><?php echo $eachprojectdocumentdata['description'] ?></a></td>
                        </tr>

                    <?php $j++;
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>