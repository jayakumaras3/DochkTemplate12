<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Reports/client_reports') ?>"><?php echo lang('Buttons.My Courses'); ?> <?php echo lang('Buttons.Report'); ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.Monthly_Report'); ?> | <?php echo $date_range; ?></h4>
        </div>
    </div>
</div>
<?php if (!empty($full_report)) : ?>
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
                            <th><?= lang('UI_Text.Status'); ?></th>
                            <th><?= lang('UI_Text.Score'); ?></th>
                            <th><?= lang('UI_Text.Total_Time'); ?></th>
                            <th><?= lang('UI_Text.Last_Active'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($full_report as $report) {

                            $j = $j + 1; ?>
                            <tr>
                                <td class="center"><?php echo  $j ?></td>
                                <td><?php echo $report['name'] . ' ' . $report['last_name']; ?></td>
                                <td><?php echo $report['course_name']; ?></td>
                                <td><?php echo $report['attempt']; ?></td>
                                <td><?php echo ucfirst($report['lesson_status']); ?></td>
                                <td><?php echo $report['score']; ?></td>
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