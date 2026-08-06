<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Reports/client_reports') ?>"><?php echo lang('Buttons.My Courses'); ?> <?php echo lang('Buttons.Report'); ?></a></li>
                </ol>
            </div>
            <?php if ($type == 1) { ?>
                <h4 class="page-title"><?php echo lang('UI_Text.Completed'); ?> <?php echo lang('UI_Text.Report'); ?></h4>
            <?php } ?>
            <?php if ($type == 2) { ?>
                <h4 class="page-title"><?php echo lang('UI_Text.In Progress'); ?> <?php echo lang('UI_Text.Report'); ?></h4>
            <?php } ?>
            <?php if ($type == 3) { ?>
                <h4 class="page-title"><?php echo lang('UI_Text.Not_started'); ?> <?php echo lang('UI_Text.Report'); ?></h4>
            <?php } ?>

        </div>
    </div>
</div>
<?php if (!empty($data_values)) : ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th><?= lang('UI_Text.User_Name'); ?></th>
                            <th><?= lang('UI_Text.Course_Name'); ?></th>
                            <th><?= lang('UI_Text.Attempts'); ?></th>
                            <th><?= lang('UI_Text.Score'); ?></th>
                            <th><?= lang('UI_Text.Total_Time'); ?></th>
                            <th><?= lang('UI_Text.Last_Active'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($data_values as $report) {

                            $j = $j + 1; ?>
                            <tr>
                                <td class="center"><?php echo  $j ?></td>
                                <td><?php echo $report['name'] . ' ' . $report['last_name']; ?></td>
                                <td><?php echo $report['course_name']; ?></td>
                                <td><?php echo $report['attempt']; ?></td>
                                <td><?php echo $report['raw']; ?></td>
                                <td><?php echo $report['total_time']; ?></td>
                                <td><?php echo date('Y-m-d', $report['last_updated_on']); ?></td>
                            <?php
                        }
                            ?>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php endif; ?>